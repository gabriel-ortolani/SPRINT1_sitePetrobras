<?php 
// Inclui o arquivo de conexão com o banco de dados
include('conexao.php'); 

// Inclui o arquivo para validação de sessão, garantindo que o usuário esteja autenticado
include('valida_sessao.php'); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Define a codificação do documento e as configurações básicas -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal - Petrobrás</title>
    <!-- Link para o arquivo de estilos CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Importa uma fonte do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="pag-init">
    <!-- Container principal da página -->
    <div class="main-container1">
        <!-- Logo da Petrobrás -->
        <img src="img/image3.png" alt="Logo da Petrobrás" class="logo2">
        <!-- Título da página -->
        <h1>Sistema de Cadastro</h1>
        <!-- Mensagem de boas-vindas com o nome do usuário logado -->
        <h2>Bem-vindo, <?php echo $_SESSION['usuario']; ?></h2>
        <!-- Navegação principal com links para outras funcionalidades -->
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
