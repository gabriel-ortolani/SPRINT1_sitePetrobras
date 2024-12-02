<?php
session_start();
include('conexao.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $usuario = $_POST['usuario'];
    $senha = md5($_POST['senha']);

    $sql = "SELECT * FROM usuario WHERE nome_usuario='$usuario' AND senha='$senha'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $_SESSION['usuario'] = $usuario;
        header('Location: pag-principal.php');
    } 
    else{
        $error = "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Petrobras</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="blogin">
    <div class="login-container">
        <img src="img/image2.png" alt="">
        <h3>Login</h3>
        <form class="login-form" method="POST">
            <div class="input-group">
                <label for="username">Usuário:</label>
                <input type="text" name="usuario" placeholder="Digite seu nome de usuário" required>
            </div>

            <div class="input-group">
                <label for="password">Senha:</label>
                <input type="password" name="senha" placeholder="Digite sua senha" required>
            </div>


            <div class="input-group">

                <a href="#" class="forgot-password">Esqueceu sua senha?</a>
            </div>

            <button type="submit" id="btn-login">Entrar</button>
            <?php if(isset($error)) echo "<p class='message error'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>