<?php include('conexao.php'); ?>
<?php include('valida_sessao.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal - Petrobrás</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="pag-init">
    <div class="main-container1">
        <img src="img/image3.png" alt="Logo da Petrobrás" class="logo2">
        <h1>Sistema de Cadastro</h1>
        <h2>Bem-vindo, <?php echo $_SESSION['usuario']; ?></h2>
        <nav class="nav-links">
            <ul>
                <li><a href="CadastroForne.php" class="botaoV">Cadastro de Fornecedor</a></li>
                <li><a href="CadastroProd.php">Cadastro de Produtos</a></li>
                <li><a href="Listagem.php">Listagem de Produtos</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>