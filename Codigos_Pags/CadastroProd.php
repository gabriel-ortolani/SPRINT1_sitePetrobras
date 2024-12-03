<?php
// Inclui o arquivo que valida a sessão do usuário
include('valida_sessao.php');
// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');

// Função para redimensionar e salvar a imagem
function redimensionarESalvarImagem($arquivo, $largura = 80, $altura = 80) {
    $diretorio_destino = "img/";
    if (!file_exists($diretorio_destino)) {
        mkdir($diretorio_destino, 0777, true);
    }
    $nome_arquivo = uniqid() . '_' . basename($arquivo["name"]);
    $caminho_completo = $diretorio_destino . $nome_arquivo;
    $tipo_arquivo = strtolower(pathinfo($caminho_completo, PATHINFO_EXTENSION));

    $check = getimagesize($arquivo["tmp_name"]);
    if ($check === false) {
        return "O arquivo não é uma imagem válida.";
    }

    if ($arquivo["size"] > 5000000) {
        return "O arquivo é muito grande. O tamanho máximo permitido é 5MB.";
    }

    if ($tipo_arquivo != "jpg" && $tipo_arquivo != "png" && $tipo_arquivo != "jpeg" && $tipo_arquivo != "gif") {
        return "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
    }

    if ($tipo_arquivo == "jpg" || $tipo_arquivo == "jpeg") {
        $imagem_original = imagecreatefromjpeg($arquivo["tmp_name"]);
    } elseif ($tipo_arquivo == "png") {
        $imagem_original = imagecreatefrompng($arquivo["tmp_name"]);
    } elseif ($tipo_arquivo == "gif") {
        $imagem_original = imagecreatefromgif($arquivo["tmp_name"]);
    }

    $largura_original = imagesx($imagem_original);
    $altura_original = imagesy($imagem_original);

    $ratio = min($largura / $largura_original, $altura / $altura_original);
    $nova_largura = $largura_original * $ratio;
    $nova_altura = $altura_original * $ratio;

    $nova_imagem = imagecreatetruecolor($nova_largura, $nova_altura);
    imagecopyresampled($nova_imagem, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);

    if ($tipo_arquivo == "jpg" || $tipo_arquivo == "jpeg") {
        imagejpeg($nova_imagem, $caminho_completo, 90);
    } elseif ($tipo_arquivo == "png") {
        imagepng($nova_imagem, $caminho_completo);
    } elseif ($tipo_arquivo == "gif") {
        imagegif($nova_imagem, $caminho_completo);
    }

    imagedestroy($imagem_original);
    imagedestroy($nova_imagem);

    return $caminho_completo;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_produto'] ?? '';
    $id_fornecedor = $_POST['id_fornecedor'];
    $nome = $_POST['nome_produto'];
    $descricao = $_POST['descricao_produto'];
    $estoque = $_POST['quantidade_estoque'];
    $codigo = $_POST['codigo_produto'];
    $preco = str_replace(',', '.', $_POST['preco']);

    $imagem = "";
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $resultado_upload = redimensionarESalvarImagem($_FILES['imagem']);
        if (strpos($resultado_upload, 'img/') === 0) {
            $imagem = $resultado_upload;
        } else {
            $mensagem_erro = $resultado_upload;
        }
    }

    if ($id) {
        $sql = "UPDATE cadastro_produto SET 
                    nome_produto=?, 
                    codigo_produto=?, 
                    descricao_produto=?, 
                    quantidade_estoque=?, 
                    preco=?, 
                    fornecedor=?";
        $params = [$nome, $codigo, $descricao, $estoque, $preco, $id_fornecedor];
        if ($imagem) {
            $sql .= ", imagem=?";
            $params[] = $imagem;
        }
        $sql .= " WHERE id_produto=?";
        $params[] = $id;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        $mensagem = "Produto atualizado com sucesso!";
    } else {
        $sql = "INSERT INTO cadastro_produto (nome_produto, codigo_produto, descricao_produto, quantidade_estoque, preco, fornecedor, imagem) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiss", $nome, $codigo, $descricao, $estoque, $preco, $id_fornecedor, $imagem);
        $mensagem = "Produto cadastrado com sucesso!";
    }

    if ($stmt->execute()) {
        $class = "success";
    } else {
        $mensagem = "Erro: " . $stmt->error;
        $class = "error";
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM cadastro_produto WHERE id_produto=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $mensagem = "Produto excluído com sucesso!";
        $class = "success";
    } else {
        $mensagem = "Erro ao excluir produto: " . $stmt->error;
        $class = "error";
    }
}

$produtos = $conn->query("
    SELECT 
        p.id_produto, 
        p.nome_produto, 
        p.codigo_produto, 
        p.descricao_produto, 
        p.quantidade_estoque, 
        p.preco,
        p.imagem,
        f.nome_fornecedor 
    FROM 
        cadastro_produto p 
    JOIN 
        cadastro_fornecedores f 
    ON 
        p.fornecedor = f.id_fornecedor
");

$fornecedores = $conn->query("SELECT id_fornecedor, nome_fornecedor FROM cadastro_fornecedores");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <title>Petrobras - Cadastro de produto</title>
</head>
<body>
    <form id="caixa-cadastro">
        <h1>Sistema de Cadastro</h1>
        <h2>Cadastro de Produto</h2>
        <!-- Formulário para cadastro/edição de produto -->
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_produto" value="<?php echo $produto['id_produto'] ?? ''; ?>">
                
            <label for="id_fornecedor">Fornecedor:</label>
            <select name="id_fornecedor" required>
                <?php while ($row = $fornecedores->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_fornecedor']; ?>" <?php if ($produto && $produto['fornecedor'] == $row['id_fornecedor']) echo 'selected'; ?>><?php echo $row['nome_fornecedor']; ?></option>
                <?php endwhile; ?>
            </select>
                
            <label for="nome">Nome:</label>
            <input type="text" name="nome_produto" value="<?php echo $produto['nome_produto'] ?? ''; ?>" required>

            <label for="descricao">Código:</label>
            <input type="text" name="codigo_produto" value="<?php echo $produto['codigo_produto'] ?? ''; ?>" required>
                
            <label for="descricao">Descrição:</label>
            <textarea name="descricao_produto"><?php echo $produto['descricao_produto'] ?? ''; ?></textarea>

            <label for="descricao">Quantidade:</label>
            <input type="text" name="quantidade_estoque" value="<?php echo $produto['quantidade_estoque'] ?? ''; ?>" required>
                
            <label for="preco">Preço:</label>
            <input type="text" name="preco" value="<?php echo $produto['preco'] ?? ''; ?>" required>
                
            <label for="imagem">Imagem:</label>
            <input type="file" name="imagem" accept="image/*">
            <?php if (isset($produto['imagem']) && $produto['imagem']): ?>
                <img src="<?php echo $produto['imagem']; ?>" alt="Imagem atual do produto" class="update-image">
            <?php endif; ?>
            <br>
            <button type="submit"><?php echo $produto ? 'Atualizar' : 'Cadastrar'; ?></button>
            
            
            <!-- Exibe mensagens de sucesso ou erro -->
            <?php if (isset($mensagem)): ?>
                <p class="message <?php echo $class; ?>"><?php echo $mensagem; ?></p>
            <?php endif; ?>

            <h2>Listagem de Produtos</h2>
            <!-- Tabela para listar os produtos cadastrados -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Estoque</th>
                    <th>Preço</th>
                    <th>Fornecedor</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="produtos_cd">
                <?php while ($row = $produtos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_produto']; ?></td>
                    <td><?php echo $row['nome_produto']; ?></td>
                    <td><?php echo $row['codigo_produto']; ?></td>
                    <td><?php echo $row['descricao_produto']; ?></td>
                    <td><?php echo $row['quantidade_estoque']; ?></td>
                    <td><?php echo 'R$ ' . number_format($row['preco'], 2, ',', '.'); ?></td>
                    <td><?php echo $row['nome_fornecedor']; ?></td>
                    <td>
                        <?php if ($row['imagem']): ?>
                            <img src="<?php echo $row['imagem']; ?>" alt="Imagem do produto" class="thumbnail">
                        <?php else: ?>
                            Sem imagem
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="?edit_id=<?php echo $row['id_produto']; ?>">Editar</a>
                        <a href="?delete_id=<?php echo $row['id_produto']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="pag-principal.php" class="back-button">Voltar</a>
    </form>
</body>
</html>