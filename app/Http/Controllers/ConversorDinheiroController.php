<?php

namespace App\Http\Controllers;

use App\Models\Cotacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use stdClass;

class ConversorDinheiroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function converter(Request $request)
    {
        $mensagem = [
            'required' =>  "Todos os campos são obrigatórios, preencha corretamente a moeda de destino, valor para conversão e forma de pagamento.",
        ];

        $request->validate([
            'moeda_de_destino' => 'required|in:USD,BOB,EUR,PYG',
            'valor_para_conversao' => 'required|string',
            'forma_de_pagamento' => 'required|in:boleto,cartao_de_credito'
        ], $mensagem);

        try {
            $resposta = new stdClass();

            $resposta->moeda_origem = "BRL";
            $resposta->moeda_de_destino = $request->get('moeda_de_destino');
            $resposta->valor_para_conversao = $request->get('valor_para_conversao');
            $resposta->forma_de_pagamento = $request->get('forma_de_pagamento');

            $resposta->valor_para_conversao = str_replace('R$ ', '', $resposta->valor_para_conversao);
            $resposta->valor_para_conversao = str_replace('.', '', $resposta->valor_para_conversao);
            $resposta->valor_para_conversao = str_replace(',', '.', $resposta->valor_para_conversao);

            $resposta->taxa_de_pagamento = 0;
            $resposta->taxa_de_conversao = 0;

            $resposta->valor_descontado_taxas = 0;

            if ($resposta->valor_para_conversao >= 1000 && $resposta->valor_para_conversao <= 100000) {
                switch ($resposta->forma_de_pagamento) {
                    case 'boleto':
                        // 1.45% de taxa de pagamento

                        $resposta->taxa_de_pagamento = $resposta->valor_para_conversao * 0.0145;

                        break;
                    
                    case 'cartao_de_credito':
                        // 7.63% de taxa de pagamento

                        $resposta->taxa_de_pagamento = $resposta->valor_para_conversao * 0.0763;
                        
                        break;
                }

                if ($resposta->valor_para_conversao >= 3000) {
                    $resposta->taxa_de_conversao = $resposta->valor_para_conversao * 0.01;
                } else {
                    $resposta->taxa_de_conversao = $resposta->valor_para_conversao * 0.02;
                }

                $resposta->valor_descontado_taxas = ($resposta->valor_para_conversao - $resposta->taxa_de_pagamento) - $resposta->taxa_de_conversao;

                $cotacao = $this->buscar_cotacao_moedas($resposta->moeda_de_destino);

                if ($cotacao['status'] === 200) {
                    foreach ($cotacao['cotacao'] as $cotacao_par_moedas) {
                        $resposta->valor_moeda_destino_conversao = $cotacao_par_moedas->bid;
                    }

                    $resposta->valor_comprado_moeda_destino = $resposta->valor_descontado_taxas / $resposta->valor_moeda_destino_conversao;
                } else {
                    return redirect()->route('home')->with("resposta", [
                        'status' => 400, 
                        'mensagem' => "Aconteceu algum problema ao buscar a cotação, por favor contate o suporte!"
                    ]);
                }
            } else {
                return redirect()->route('home')->with("resposta", [
                    'status' => 400, 
                    'mensagem' => "O valor para conversão deve ser maior que R$ 1.000,00 e menor que R$ 100.000,00!"
                ]);
            }

            Cotacao::create([
                'moeda_origem' => $resposta->moeda_origem,
                'moeda_destino' => $resposta->moeda_de_destino,
                'valor_para_conversao' => $resposta->valor_para_conversao,
                'forma_de_pagamento' => $resposta->forma_de_pagamento,
                'valor_moeda_destino_conversao' => $resposta->valor_moeda_destino_conversao,
                'valor_comprado_moeda_destino' => $resposta->valor_comprado_moeda_destino,
                'taxa_de_pagamento' => $resposta->taxa_de_pagamento,
                'taxa_de_conversao' => $resposta->taxa_de_conversao,
                'valor_descontado_taxas' => $resposta->valor_descontado_taxas,
                'usuario' => Auth::user()->id
            ]);

            $this->envia_email_cotacao($resposta);
    
            return redirect()->route('home')->with("resposta", [
                'status' => 200, 
                'mensagem' => "Conversão efetuada e retornada.",
                'conversao' => $resposta
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->route('home')->with("resposta", [
                'status' => 500, 
                'mensagem' => "Aconteceu algum problema, por favor contate o suporte."
            ]);
        }
    }

    public function buscar_cotacao_moedas($moeda_de_destino = NULL) 
    {
        try {
            $moeda_padrao = "BRL";
            $moeda_de_destino_valida = "";

            switch ($moeda_de_destino) {
                case 'USD':
                    $moeda_de_destino_valida = "USD";
                    break;

                case 'BOB':
                    $moeda_de_destino_valida = "BOB";
                    break;

                case 'EUR':
                    $moeda_de_destino_valida = "EUR";
                    break;

                case 'PYG':
                    $moeda_de_destino_valida = "PYG";
                    break;
                
                default:
                    return [
                        'status' => 400, 
                        'mensagem' => "Moeda para conversão inválida."
                    ];
                    break;
            }

            $url = "https://economia.awesomeapi.com.br/last/$moeda_de_destino_valida-$moeda_padrao";
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $cotacao = json_decode(curl_exec($ch));

            return [
                'status' => 200, 
                'mensagem' => "Cotação de moedas retornada.",
                'cotacao' => $cotacao
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 500, 
                'mensagem' => "Aconteceu algum problema ao consultar a API, por favor contate o suporte."
            ];
        }
    }

    public function envia_email_cotacao($conversao = NULL)
    {
        try {
            $titulo = "Conversão entre " . $conversao->moeda_origem . " e " . $conversao->moeda_de_destino . ".";

            $detalhes = [
                'titulo' => "$titulo",
                'conversao' => $conversao
            ];
           
            Mail::to(Auth::user()->email)->send(new \App\Mail\EmailConversao($detalhes));
        } catch (\Throwable $th) {
            return [
                'status' => 500, 
                'mensagem' => "Aconteceu algum problema ao enviar o e-mail, por favor contate o suporte."
            ];
        }
    }
}
