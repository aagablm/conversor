<?php
// Função para validar se a entrada é um número positivo
function validar_numero($valor) {
    return is_numeric($valor) && $valor > 0;
}

// Função para converter o valor com base na taxa de câmbio
function converter_moeda($valor, $taxa) {
    return $valor * $taxa;
}

// Função para obter a taxa de câmbio da API
function obter_taxa_cambio($moeda_origem, $moeda_destino) {
    $url = "https://api.exchangerate-api.com/v4/latest/{$moeda_origem}"; // Substitua pela API que você escolher
    $dados = json_decode(file_get_contents($url), true);
    
    // Verifica se a moeda de destino está disponível
    if (isset($dados['rates'][$moeda_destino])) {
        return $dados['rates'][$moeda_destino];
    } else {
        die("Moeda de destino não encontrada.");
    }
}

// Captura os dados do formulário
$moeda_origem = $_POST['moeda_origem'];
$moeda_destino = $_POST['moeda_destino'];
$valor_input = $_POST['valor'];

// Valida a entrada
if (!validar_numero($valor_input)) {
    die("Por favor, insira um valor numérico positivo.");
}

// Obtém a taxa de conversão da API
$taxa_conversao = obter_taxa_cambio($moeda_origem, $moeda_destino);

// Converte o valor
$valor_convertido = converter_moeda($valor_input, $taxa_conversao);

// Exibe o resultado
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
        <p class="result"><?php echo "{$valor_input} {$moeda_origem} = " . number_format($valor_convertido, 2) . " {$moeda_destino}"; ?></p>
        <a href="index.html">Voltar</a>
    </div>
</body>
</html>
