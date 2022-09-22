@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        {!! Form::open(['id' => '', 'class' => '', 'name'=>'', 'route'=>['converter'], 'method'=>'post']) !!}
                            @csrf
                            @method('POST')

                            <div class="col-12">
                                <div class="row mx-10">
                                    <div class="col-12 my-2">
                                        <label for="moeda_de_destino">Moeda de destino</label>
                                        <select class="form-control select2" name="moeda_de_destino" id="moeda_de_destino">
                                            <option value="USD">Dólar Americano (USD)</option>
                                            <option value="BOB">Boliviano (BOB)</option>
                                            <option value="EUR">Euro (EUR)</option>
                                            <option value="PYG">Guarani Paraguaio (PYG)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 my-2">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="valor_para_conversao" id="valor_para_conversao" value="{{ old('valor_para_conversao') }}">
                                            <label for="valor_para_conversao">Valor para conversão</label>
                                        </div>
                                    </div>
                                    <div class="col-12 my-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="forma_de_pagamento" id="boleto" value="boleto" checked>
                                            <label class="form-check-label" for="boleto">
                                                Boleto
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="forma_de_pagamento" id="cartao_de_credito" value="cartao_de_credito">
                                            <label class="form-check-label" for="cartao_de_credito">
                                                Cartão de Crédito
                                            </label>
                                        </div>      
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button id="" type="submit" class="btn btn-primary btn-shadow-hover">Submeter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            @if (isset(session('resposta')['conversao']))
                <div class="col-md-8 mt-3">
                    <div class="card">
                        <div class="card-header">Cotação</div>
                        <div class="card-body">
                            <ul>
                                <li>Moeda de origem: {{ session('resposta')['conversao']->moeda_origem }}</li>
                                <li>Moeda de destino: {{ session('resposta')['conversao']->moeda_de_destino }}</li>
                                <li>Valor para conversão: R$ {{ number_format(session('resposta')['conversao']->valor_para_conversao, 2, ',', '.') }}</li>
                                <li>Forma de pagamento: {{ (session('resposta')['conversao']->forma_de_pagamento === "boleto" ? "Boleto" : "Cartão de Crédito") }}</li>
                                <li>Valor da "Moeda de destino" usado para conversão: {{ session('resposta')['conversao']->valor_moeda_destino_conversao }}</li>
                                <li>Valor comprado em "Moeda de destino": {{ number_format(session('resposta')['conversao']->valor_comprado_moeda_destino, 2, ',', '.') }} (taxas aplicadas no valor de compra diminuindo no valor total de conversão)</li>
                                <li>Taxa de pagamento: R$ {{ number_format(session('resposta')['conversao']->taxa_de_pagamento, 2, ',', '.') }}</li>
                                <li>Taxa de conversão: R$ {{ number_format(session('resposta')['conversao']->taxa_de_conversao, 2, ',', '.') }}</li>
                                <li>Valor utilizado para conversão descontando as taxas: R$ {{ number_format(session('resposta')['conversao']->valor_descontado_taxas, 2, ',', '.') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($cotacoes) && (!$cotacoes->isEmpty()))
                <div class="table-responsive mt-3">
                    <table class="table table-head-custom table-vertical-center m-0" id="kt_advance_table_widget_4">
                        <thead>
                            <tr class="text-left">
                                <th>Moeda de origem</th>
                                <th>Moeda de destino</th>
                                <th>Valor para conversão</th>
                                <th>Forma de pagamento</th>
                                <th>Valor da moeda de destino</th>
                                <th>Valor comprado em moeda de destino</th>
                                <th>Taxa de pagamento</th>
                                <th>Taxa de conversão</th>
                                <th>Valor descontado taxas</th>
                                <th>Data da cotação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cotacoes as $cotacao)
                                <tr>
                                    <td>
                                        <span class="font-weight-bolder d-block font-size-lg">{{ $cotacao->moeda_origem }}</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bolder d-block font-size-lg">{{ $cotacao->moeda_destino }}</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bolder d-block font-size-lg">{{ number_format($cotacao->valor_para_conversao, 2, ',', '.') }}</span>
                                        <span class="text-muted font-weight-bold">{{ $cotacao->moeda_origem }}</span>
                                    </td>
                                    <td>
                                        @switch($cotacao->forma_de_pagamento)
                                            @case('boleto')
                                                <span class="font-weight-bolder d-block font-size-lg">Boleto</span>
                                                @break
                                            @case('cartao_de_credito')
                                                <span class="font-weight-bolder d-block font-size-lg">Cartão de crédito</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="font-weight-bolder d-block font-size-lg">{{ $cotacao->valor_moeda_destino_conversao }}</span>
                                        <span class="text-muted font-weight-bold">{{ $cotacao->moeda_destino }}</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bolder d-block font-size-lg">{{ number_format($cotacao->valor_comprado_moeda_destino, 2, ',', '.') }}</span>
                                        <span class="text-muted font-weight-bold">{{ $cotacao->moeda_destino }}</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bolder d-block font-size-lg">{{ number_format($cotacao->taxa_de_pagamento, 2, ',', '.') }}</span>
                                        <span class="text-muted font-weight-bold">{{ $cotacao->moeda_origem }}</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bolder d-block font-size-lg">{{ number_format($cotacao->taxa_de_conversao, 2, ',', '.') }}</span>
                                        <span class="text-muted font-weight-bold">{{ $cotacao->moeda_origem }}</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bolder d-block font-size-lg">{{ number_format($cotacao->valor_descontado_taxas, 2, ',', '.') }}</span>
                                        <span class="text-muted font-weight-bold">{{ $cotacao->moeda_origem }}</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bolder d-block font-size-lg">{{ $cotacao->created_at }}</span>
                                    </td>
                                </tr>    
                            @endforeach
                        </tbody>
                    </table>
                    <p>{{ $cotacoes->links() }}</p>
                </div>
            @else
                <p class="text-white m-0">@lang('transacoes.table_have_transactions')</p>
            @endif
        </div>
    </div>
@endsection
