## Projeto de conversão de moedas
Disponibilizado opção para conversão de BRL para USD (Dólar Americano), BOB (Boliviano), EUR (Euro) e PYG (Guarani Paraguaio).

Regras de negócios cumpridas:
- Moeda de origem BRL;
- Informar uma moeda de compra que não seja BRL (exibir no mínimo 2 opções);
- Valor da Compra em BRL (deve ser maior que R$ 1.000,00 e menor que R$ 100.000,00);
- Formas de pagamento (taxas aplicadas no valor da compra e aceitar apenas as opções abaixo);
  - Para pagamentos em boleto, taxa de 1,45%;
  - Para pagamentos em cartão de crédito, taxa de 7,63%;
- Aplicar taxa de 2% pela conversão para valores abaixo de R$ 3.000,00 e 1% para valores maiores que R$ 3.000,00,
essa taxa deve ser aplicada apenas no valor da compra e não sobre o valor já com a taxa de forma de pagamento;

Bônus cumpridos:
- Enviar cotação realizada por email;
- Autenticação de usuário;
- Histórico de cotações feita pelo usuário;

Banco de dados utilizado foi o PostgreSQL, precisará habilitar o pgsql nas variáveis do PHP.ini caso não utilize esse banco.

Criado um e-mail no google e uma conta no sendinblue para ser enviado por e-mail via SMTP as cotações para os usuários.

Autenticação via Laravel Auth utilizando o front-end do bootstrap via Laravel Ui.

Histórico das conversões foi criado uma tabela com paginação abaixo do formulário de conversão para mostrar as cotações retroativas.

Linguagens:
  - PHP 8.1;
  - Laravel 9;
  - Bootstrap 5;
  - PostgreSQL;

Libs:
  - Toast;
  - Jquery mask money;
  - Select2;
  
Instalação:
  - Clone do projeto em alguma pasta que preferir (git clone https://github.com/ruancmeira/money_converter.git)
  - Duplicar o arquivo .env.example removendo o .example do nome do arquivo;
  - Rodar o comando de instalação dos componentes do composer (composer install);
  - Rodar os comandos para instalação dos pacotes do front via Node (npm install && npm run build);
  - Deve criar um banco de dados no seu localhost no pgadmin4 chamado oliveira_trust_money_converter; 
  - Agora pode rodar o comando para rodar as migrações das tabelas (php artisan migrate);
  - Por fim para rodar o projeto deverá rodar o comando para subir o servidor laravel (php artisan serve);

Para realizar o cadastro, na tela inicial 127.0.0.1:8000 no canto superior direito tem os links para login e register;

Link para o vídeo de apresentação do projeto:
https://www.loom.com/share/a4ffca6a3d604361848f62f8f94fd7a0


<!-- # Oliveira Trust PHP Money Converter

<p>
    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQIAOtqQ5is5vwbcEn0ZahZfMxz1QIeAYtFfnLdkCXu1sqAGbnX" width="300">
 </p>
 
## Desafio para candidatos à vaga de Desenvolvedor PHP (Jr/Pleno/Sênior).
Olá caro desenvolvedor, nosso principal objetivo é conseguir ver a lógica implementada independente da sua experiência, framework ou linguagem utilizada para resolver o desafio. Queremos avaliar a sua capacidade em aplicar as regras de négocios na aplicação, separar as responsabilidades e ter um código legível para outros desenvolvedores, as instruções nesse projeto são apenas um direcional para entregar o desafio mas pode ficar livre para resolver da forma que achar mais eficiente. 🚀 

Não deixe de enviar o seu teste mesmo que incompleto!

## Tecnologias a serem utilizadas
* HTML
* CSS
* Javascript
* PHP (Laravel, Yii, Lumen, etc...)

## O que vamos avaliar:
- Legibilidade do código
- Modularização
- Lógica para aplicar a regra de négocio
- Utilização da API

## Instruções para o desafio:
Você vai implementar uma aplicação que faça a conversão da nossa moeda nacional para uma moeda estrangeira, aplicando algumas taxas e regras, no final da conversão o resultado deverá ficar em tela de forma detalhada.

Pode utilizar qualquer API para conversão de valores, mas recomendamos essa aqui: https://docs.awesomeapi.com.br/api-de-moedas pela facilidade e boa documentação.

## O Desafio:
O usuário precisa informar 3 informações em tela, moeda de destino, valor para conversão e forma de pagamento. A nossa moeda nacional BRL será usada como moeda base na conversão.

### As Regras de négocio:
- Moeda de origem BRL;
- Informar uma moeda de compra que não seja BRL (exibir no mínimo 2 opções);
- Valor da Compra em BRL (deve ser maior que R$ 1.000,00 e menor que R$ 100.000,00)
- Formas de pagamento (taxas aplicadas no valor da compra e aceitar apenas as opções abaixo)
  - Para pagamentos em boleto, taxa de 1,45%
  - Para pagamentos em cartão de crédito, taxa de 7,63%
- Aplicar taxa de 2% pela conversão para valores abaixo de R$ 3.000,00 e 1% para valores maiores que R$ 3.000,00, 
essa taxa deve ser aplicada apenas no valor da compra e não sobre o valor já com a taxa de forma de pagamento.

### Exemplos de entrada:
- Moeda de origem: BRL (default)
- Moeda de destino:
  - Exemplo: USD, BTC, ...
- Valor para conversão:
  - Exemplo: 5.000,00, 1.000,00, 70.000,00, ...
- Forma de pagamento:
  - Boleto ou Cartão de Crédito

### Exemplo de funcionamento:

#### Parâmetros de entrada:
- Moeda de origem: BRL (default)
- Moeda de destino: USD
- Valor para conversão: 5.000,00
- Forma de pagamento: Boleto

#### Parâmetros de saída:
- Moeda de origem: BRL
- Moeda de destino: USD
- Valor para conversão: R$ 5.000,00
- Forma de pagamento: Boleto
- Valor da "Moeda de destino" usado para conversão: $ 5,30
- Valor comprado em "Moeda de destino": $ 920,18 (taxas aplicadas no valor de compra diminuindo no valor total de conversão)
- Taxa de pagamento: R$ 72,50
- Taxa de conversão: R$ 50,00
- Valor utilizado para conversão descontando as taxas: R$ 4.877,50

### Critério de aceitação:
Deve ser possível escolher uma moeda estrangeira entre pelo menos 2 opções sendo o seu valor de compra maior que R$ 1.000 e menor que R$ 100.000,00
e sua forma de pagamento em boleto ou cartão de crédito tendo como resultado o valor que será adquirido na moeda de destino e as taxas aplicadas;

### Bônus:
* Enviar cotação realizada por email;
* Autenticação de usuário;
* Histórico de cotações feita pelo usuário;
* Uma opção no painel para configurar as taxas aplicadas na conversão;

## Informações úteis da api:
- Conversão BRL para USD
    - https://economia.awesomeapi.com.br/json/last/BRL-USD
- Moedas para conversão
    - https://docs.awesomeapi.com.br/api-de-moedas#moedas-com-conversao-para
- Tradução das moedas
    - https://economia.awesomeapi.com.br/json/available/uniq
- Combinações possíveis
    - https://economia.awesomeapi.com.br/json/available
- Legendas
    - https://docs.awesomeapi.com.br/api-de-moedas#legendas

## Finalização e Instruções para a Apresentação

Avisar sobre a finalização e enviar para correção.

1. Confira se você respondeu o Scorecard da Vaga que chegou no seu email;
2. Confira se você respondeu o Mapeamento Comportamental que chegou no seu email;
3. Acesse: [https://coodesh.com/challenges/review](https://coodesh.com/challenges/review);
4. Adicione o repositório com a sua solução;
5. Grave um vídeo, utilizando o botão na tela de solicitar revisão da Coodesh, com no máximo 5 minutos, com a apresentação do seu projeto. Foque em pontos obrigatórios e diferenciais quando for apresentar.
6. Adicione o link da apresentação do seu projeto no README.md.
7. Verifique se o Readme está bom e faça o commit final em seu repositório;
8. Confira a vaga desejada;
9. Envie e aguarde as instruções para seguir no processo. Sucesso e boa sorte. =)

## Suporte

Use a [nossa comunidade](https://coodesh.com/desenvolvedores#community) para tirar dúvidas sobre o processo ou envie um e-mail para contato@coodesh.com.


### Boa sorte! 🚀 -->
