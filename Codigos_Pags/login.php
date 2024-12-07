<?php
// Inicia a sessão para gerenciar autenticação
session_start();

// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados enviados pelo formulário
    $usuario = $_POST['usuario'];
    // Criptografa a senha usando MD5 (não recomendado para segurança moderna)
    $senha = md5($_POST['senha']);

    // Query para verificar se o usuário e a senha estão corretos
    $sql = "SELECT * FROM usuario WHERE nome_usuario='$usuario' AND senha='$senha'";
    $result = $conn->query($sql);

    // Verifica se a consulta retornou algum resultado
    if ($result->num_rows > 0) {
        // Armazena o nome de usuário na sessão
        $_SESSION['usuario'] = $usuario;
        // Redireciona para a página principal
        header('Location: pag-principal.php');
    } else {
        // Caso a autenticação falhe, define uma mensagem de erro
        $error = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Define a codificação do documento e configurações básicas -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Petrobras</title>
    <!-- Importa o arquivo de estilos CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Importa uma fonte do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="blogin">
    <!-- Container para o formulário de login -->
    <div class="login-container">
        <!-- Imagem do login -->
        <img src="img/image2.png" alt="">
        <h3>Login</h3>
        <!-- Formulário de login -->
        <form class="login-form" method="POST">
            <!-- Campo de entrada para o nome de usuário -->
            <div class="input-group">
                <label for="username">Usuário:</label>
                <input type="text" name="usuario" placeholder="Digite seu nome de usuário" required>
            </div>
            <!-- Campo de entrada para a senha -->
            <div class="input-group">
                <label for="password">Senha:</label>
                <input type="password" name="senha" placeholder="Digite sua senha" required>
            </div>
            <!-- Link para recuperar a senha (não funcional no exemplo) -->
            <div class="input-group">
                <a href="#" class="forgot-password">Esqueceu sua senha?</a>
            </div>
            <!-- Botão para enviar o formulário -->
            <button type="submit" id="btn-login">Entrar</button>
            <!-- Exibe mensagem de erro, caso exista -->
            <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>
