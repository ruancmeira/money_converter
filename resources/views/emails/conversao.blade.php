<!DOCTYPE html>
<html>
<head>
    <title>Money Converter</title>
</head>
<body>
    <h1>{{ $detalhes['titulo'] }}</h1>
    
    <ul>
        <li>Moeda de origem: {{ $detalhes['conversao']->moeda_origem }}</li>
        <li>Moeda de destino: {{ $detalhes['conversao']->moeda_de_destino }}</li>
        <li>Valor para conversão: R$ {{ number_format($detalhes['conversao']->valor_para_conversao, 2, ',', '.') }}</li>
        <li>Forma de pagamento: {{ ($detalhes['conversao']->forma_de_pagamento === "boleto" ? "Boleto" : "Cartão de Crédito") }}</li>
        <li>Valor da "Moeda de destino" usado para conversão: {{ $detalhes['conversao']->valor_moeda_destino_conversao }}</li>
        <li>Valor comprado em "Moeda de destino": {{ number_format($detalhes['conversao']->valor_comprado_moeda_destino, 2, ',', '.') }} (taxas aplicadas no valor de compra diminuindo no valor total de conversão)</li>
        <li>Taxa de pagamento: R$ {{ number_format($detalhes['conversao']->taxa_de_pagamento, 2, ',', '.') }}</li>
        <li>Taxa de conversão: R$ {{ number_format($detalhes['conversao']->taxa_de_conversao, 2, ',', '.') }}</li>
        <li>Valor utilizado para conversão descontando as taxas: R$ {{ number_format($detalhes['conversao']->valor_descontado_taxas, 2, ',', '.') }}</li>
    </ul>
</body>
</html>
