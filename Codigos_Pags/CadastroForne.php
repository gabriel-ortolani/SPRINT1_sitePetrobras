<?php
// Inclui o arquivo que valida a sessão do usuário
include('valida_sessao.php');

// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');

// Função para redimensionar e salvar a imagem
Function redimensionarESalvarImagem($arquivo, $largura = 80, $altura = 80) {
    // Define o diretório de destino e cria um nome único para o arquivo
    $diretorio_destino = "img/";
    $nome_arquivo = uniqid() . "_" . basename($arquivo["name"]);
    $caminho_completo = $diretorio_destino . $nome_arquivo;
    $tipo_arquivo = strtolower(pathinfo($caminho_completo, PATHINFO_EXTENSION));

    // Verifica se é uma imagem válida
    $check = getimagesize($arquivo["tmp_name"]);
    if ($check === false) {
        return "O arquivo não é uma imagem válida.";
    }

    // Verifica o tamanho do arquivo (limite de 5MB)
    if ($arquivo["size"] > 5000000) {
        return "O arquivo é muito grande. O tamanho máximo permitido é 5MB.";
    }

    // Permite apenas alguns formatos de arquivo
    if ($tipo_arquivo != "jpg" && $tipo_arquivo != "png" && $tipo_arquivo != "jpeg" && $tipo_arquivo != "gif") {
        return "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
    }

    // Cria uma nova imagem a partir do arquivo enviado
    if ($tipo_arquivo == "jpg" || $tipo_arquivo == "jpeg") {
        $imagem_original = imagecreatefromjpeg($arquivo["tmp_name"]);
    } elseif ($tipo_arquivo == "png") {
        $imagem_original = imagecreatefrompng($arquivo["tmp_name"]);
    } elseif ($tipo_arquivo == "gif") {
        $imagem_original = imagecreatefromgif($arquivo["tmp_name"]);
    }

    // Obtém as dimensões originais da imagem
    $largura_original = imagesy($imagem_original);
    $altura_original = imagesx($imagem_original);

    // Calcula as novas dimensões mantendo a proporção
    $ratio = min($largura / $largura_original, $altura / $altura_original);
    $nova_largura = $largura_original * $ratio;
    $nova_altura = $altura_original * $ratio;

    // Cria uma nova imagem com as dimensões calculadas
    $nova_imagem = imagecreatetruecolor($nova_largura, $nova_altura);

    // Redimensiona a imagem original para a nova imagem
    imagecopyresampled($nova_imagem, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);

    // Salva a nova imagem no formato apropriado
    if ($tipo_arquivo == "jpg" || $tipo_arquivo == "jpeg") {
        imagejpeg($nova_imagem, $caminho_completo, 90);
    } elseif ($tipo_arquivo == "png") {
        imagepng($nova_imagem, $caminho_completo);
    } elseif ($tipo_arquivo == "gif") {
        imagegif($nova_imagem, $caminho_completo);
    }

    // Libera a memória
    imagedestroy($imagem_original);
    imagedestroy($nova_imagem);

    return $caminho_completo;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $id = $_POST['id_fornecedor'];
    $nome = $_POST['nome_fornecedor'];
    $cnpj = $_POST['cnpj'];
    $endereco = $_POST['endereco'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $observacoes = $_POST['observacoes'];

    // Processa o upload da imagem
    $imagem = "";
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $resultado_upload = redimensionarESalvarImagem($_FILES['imagem']);
        if (strpos($resultado_upload, 'img/') === 0) {
            $imagem = $resultado_upload;
        } else {
            $mensagem_erro = $resultado_upload;
        }
    }

    // Prepara a query SQL para inserção ou atualização
    if ($id) {
        // Se o ID existe, é uma atualização
        $sql = "UPDATE cadastro_fornecedores SET nome_fornecedor='$nome', email='$email', telefone='$telefone', cnpj='$cnpj', endereco='$endereco', observacoes='$observacoes'";
        if ($imagem) {
            $sql .= ", imagem='$imagem'";
        }
        $sql .= " WHERE id_fornecedor='$id'";
        $mensagem = "Fornecedor atualizado com sucesso!";
    } else {
        // Se não há ID, é uma nova inserção
        $sql = "INSERT INTO cadastro_fornecedores (nome_fornecedor, email, telefone, imagem, cnpj, endereco, observacoes) 
                VALUES ('$nome', '$email', '$telefone', '$imagem', '$cnpj', '$endereco', '$observacoes')";
        $mensagem = "Fornecedor cadastrado com sucesso!";
    }

    // Executa a query e verifica se houve erro
    if ($conn->query($sql) !== TRUE) {
        $mensagem = "Erro:" . $conn->error;
    }
}

// Verifica se foi solicitada a exclusão de um fornecedor
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Verifica se o fornecedor tem produtos cadastrados
    $check_produtos = $conn->query("SELECT COUNT(*) as count FROM cadastro_produto WHERE fornecedor = '$delete_id'")->fetch_assoc();

    if ($check_produtos['count'] > 0) {
        $mensagem = "Não é possível excluir este fornecedor pois existem produtos cadastrados para ele.";
    } else {
        // Realiza a exclusão do fornecedor
        $sql = "DELETE FROM cadastro_fornecedores WHERE id_fornecedor='$delete_id'";
        if ($conn->query($sql) === TRUE) {
            $mensagem = "Fornecedor excluído com sucesso!";
        } else {
            $mensagem = "Erro ao excluir fornecedor:" . $conn->error;
        }
    }
}

// Busca todos os fornecedores para listar na tabela
$fornecedores = $conn->query("SELECT * FROM cadastro_fornecedores");

// Se foi solicitada a edição de um fornecedor, busca os dados dele
$fornecedor = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $fornecedor = $conn->query("SELECT * FROM cadastro_fornecedores WHERE id_fornecedor='$edit_id'")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Petrobrás</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="cadastro-container">
        <div class="header">
            <img src="img/Frase sem fundo.png" alt="Logo da Petrobrás" class="logo">
            <h1>Sistema de Cadastro</h1>
            <h2>Cadastro de Fornecedor</h2>
        </div>

        <form class="cadastro-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_fornecedor" value="<?php echo $fornecedor['id_fornecedor'] ?? ''; ?>">
            <div class="input-group">
                <label>Nome:</label>
                <input type="text" name="nome_fornecedor" value="<?php echo $fornecedor['nome_fornecedor'] ?? ''; ?>" required>
            </div>
            <div class="input-group">
                <label>CNPJ:</label>
                <input type="text" name="cnpj" value="<?php echo $fornecedor['cnpj'] ?? ''; ?>" required>
            </div>
            <div class="input-group">
                <label>Endereço:</label>
                <input type="text" name="endereco" value="<?php echo $fornecedor['endereco'] ?? ''; ?>" required>
            </div>
            <div class="input-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $fornecedor['email'] ?? ''; ?>" required>
            </div>
            <div class="input-group">
                <label>Telefone:</label>
                <input type="tel" name="telefone" value="<?php echo $fornecedor['telefone'] ?? ''; ?>" required>
            </div>
            <div class="input-group">
                <label>observações:</label>
                <input type="text" name="observacoes" value="<?php echo $fornecedor['observacoes'] ?? ''; ?>" required>
            </div>
            <div class="input-group">
                <label for="imagem">Imagem:</label>
                <input type="file" name="imagem" accept="image/*">
            </div>

            <button type="submit">Salvar</button>
        </form>

        <?php if (isset($mensagem)) : ?>
            <p class="mensagem"><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <!-- Tabela de Fornecedores -->
        <div class="cadastro-container2">
            <div class="tabela-rolavel-wrapper">
                <table class="fornecedores-list">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Endereço</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Observações</th>
                            <th>Imagem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $fornecedores->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id_fornecedor']; ?></td>
                                <td><?php echo $row['nome_fornecedor']; ?></td>
                                <td><?php echo $row['cnpj']; ?></td>
                                <td><?php echo $row['endereco']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['telefone']; ?></td>
                                <td><?php echo $row['observacoes']; ?></td>
                                <td>
                                    <?php if ($row['imagem']): ?>
                                        <img src="<?php echo $row['imagem']; ?>" alt="Imagem do fornecedor" class="thumbnail">
                                    <?php else: ?>
                                        Sem imagem
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="?edit_id=<?php echo $row['id_fornecedor']; ?>">Editar</a>
                                    <a href="?delete_id=<?php echo $row['id_fornecedor']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <a href="pag-principal.php" class="back-button">Voltar</a>
        </div>
    </div>
</body>
</html>
