<?php include('conexao.php'); ?>
<?php include('valida_sessao.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <title>Petrobras - Lista de produtos</title>
</head>
<body>
    <div id="container">
        <div id="lista-itens">
            <h1>Sistema de Cadastro</h1>
            <h2>Listagem de produtos</h2>
            <div id="pesquisa">
                <input type="button" value="Pesquisar" id="btn">
                <input type="text" id="barra">
                <select name="Seleção">
                    <option value="ambos">Todos</option>
                    <option value="produtos">Por Produto</option>
                    <option value="fornecedores">Por Fornecedor</option>
                </select>
            </div>       
            <a href="pag-principal.html">Voltar</a>
        </div>
    </div>
</body>
</html>