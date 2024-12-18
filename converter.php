<?php

function validar_numero($valor) {
    return is_numeric($valor) && $valor > 0;
}

function converter_moeda($valor, $taxa) {
    return $valor * $taxa;
}

function obter_taxa_cambio($moeda_origem, $moeda_destino) {
    $url = "https://api.exchangerate-api.com/v4/latest/{$moeda_origem}"; 
    $dados = json_decode(file_get_contents($url), true);
    
    if (isset($dados['rates'][$moeda_destino])) {
        return $dados['rates'][$moeda_destino];
    } else {
        die("Moeda de destino não encontrada.");
    }
}

$moeda_origem = $_POST['moeda_origem'];
$moeda_destino = $_POST['moeda_destino'];
$valores_input = $_POST['valores'];

$valores_array = array_map('trim', explode(';', $valores_input));
$valores_validos = array_filter($valores_array, 'validar_numero');

$taxa_conversao = obter_taxa_cambio($moeda_origem, $moeda_destino);

$valores_convertidos = array_map(function($valor) use ($taxa_conversao) {
    return converter_moeda($valor, $taxa_conversao);
}, $valores_validos);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Conversão</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Resultado da Conversão</h1>
        <ul class="result">
            <?php
                foreach ($valores_validos as $index => $valor) {
                    $valor_formatado = number_format($valor, 2, ',', '.');
                    $valor_convertido_formatado = number_format($valores_convertidos[$index], 2, ',', '.');
                    echo "<li>{$valor_formatado} {$moeda_origem} = {$valor_convertido_formatado} {$moeda_destino}</li>";
                }
            ?>
        </ul>
        <a href="index.html">Voltar</a>
    </div>
</body>
</html>
