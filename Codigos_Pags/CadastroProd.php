<?php
// Inclui os arquivos de sessão e conexão com o banco de dados
include('valida_sessao.php');
include('conexao.php');

// Função para tratar e salvar a imagem
function processarImagem($arquivo) {
    $diretorio = "img/";
    if (!file_exists($diretorio)) {
        mkdir($diretorio, 0777, true);
    }
    $nomeArquivo = uniqid() . '_' . basename($arquivo['name']);
    $caminhoCompleto = $diretorio . $nomeArquivo;
    $tipoArquivo = strtolower(pathinfo($caminhoCompleto, PATHINFO_EXTENSION));

    $tamanhosPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($tipoArquivo, $tamanhosPermitidos)) {
        return ['erro' => 'Formatos permitidos: JPG, JPEG, PNG e GIF'];
    }

    if ($arquivo['size'] > 5000000) { // Limite de 5MB
        return ['erro' => 'O tamanho máximo permitido é 5MB'];
    }

    if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
        return ['caminho' => $caminhoCompleto];
    } else {
        return ['erro' => 'Erro ao salvar a imagem'];
    }
}

// Verifica se está editando um produto
$produtoAtual = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $query = $conn->prepare("SELECT * FROM cadastro_produto WHERE id_produto = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $resultado = $query->get_result();
    $produtoAtual = $resultado->fetch_assoc();
}

// Trata envio de formulário (cadastro/edição)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_produto'] ?? null;
    $fornecedor = $_POST['id_fornecedor'];
    $nome = $_POST['nome_produto'];
    $descricao = $_POST['descricao_produto'];
    $quantidade = $_POST['quantidade_estoque'];
    $codigo = $_POST['codigo_produto'];
    $preco = str_replace(',', '.', $_POST['preco']); // Converte vírgulas para pontos
    $imagem = $produtoAtual['imagem'] ?? null;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $upload = processarImagem($_FILES['imagem']);
        if (isset($upload['erro'])) {
            $mensagemErro = $upload['erro'];
        } else {
            $imagem = $upload['caminho'];
        }
    }

    if (!isset($mensagemErro)) {
        if ($id) {
            // Atualiza produto existente
            $sql = "UPDATE cadastro_produto SET 
                        nome_produto=?, codigo_produto=?, descricao_produto=?, 
                        quantidade_estoque=?, preco=?, fornecedor=?, imagem=? 
                    WHERE id_produto=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdsisi", $nome, $codigo, $descricao, $quantidade, $preco, $fornecedor, $imagem, $id);
        } else {
            // Insere novo produto
            $sql = "INSERT INTO cadastro_produto (nome_produto, codigo_produto, descricao_produto, quantidade_estoque, preco, fornecedor, imagem) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdsis", $nome, $codigo, $descricao, $quantidade, $preco, $fornecedor, $imagem);
        }
        $stmt->execute();
        header("Location: CadastroProd.php");
        exit;
    }
}

// Trata exclusão de produto
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM cadastro_produto WHERE id_produto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: CadastroProd.php");
    exit;
}

// Lista produtos e fornecedores
$produtos = $conn->query("SELECT p.*, f.nome_fornecedor FROM cadastro_produto p JOIN cadastro_fornecedores f ON p.fornecedor = f.id_fornecedor");
$fornecedores = $conn->query("SELECT id_fornecedor, nome_fornecedor FROM cadastro_fornecedores");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cadastro de Produtos</title>
</head>
<body>
    <div id="caixa-cadastro">
        <h1>Cadastro de Produtos</h1>
        <!-- Formulário de Cadastro/Edição -->
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_produto" value="<?php echo $produtoAtual['id_produto'] ?? ''; ?>">
            <label>Fornecedor:</label>
            <select name="id_fornecedor" required>
                <?php while ($fornecedor = $fornecedores->fetch_assoc()): ?>
                    <option value="<?php echo $fornecedor['id_fornecedor']; ?>"
                        <?php if ($produtoAtual && $produtoAtual['fornecedor'] == $fornecedor['id_fornecedor']) echo 'selected'; ?>>
                        <?php echo $fornecedor['nome_fornecedor']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label>Nome do Produto:</label>
            <input type="text" name="nome_produto" value="<?php echo $produtoAtual['nome_produto'] ?? ''; ?>" required>
            <label>Código:</label>
            <input type="text" name="codigo_produto" value="<?php echo $produtoAtual['codigo_produto'] ?? ''; ?>" required>
            <label>Descrição:</label>
            <textarea name="descricao_produto" required><?php echo $produtoAtual['descricao_produto'] ?? ''; ?></textarea>
            <label>Quantidade em Estoque:</label>
            <input type="number" name="quantidade_estoque" class="number" value="<?php echo $produtoAtual['quantidade_estoque'] ?? ''; ?>" required><br>
            <label>Preço:</label>
            <input type="text" name="preco" value="<?php echo $produtoAtual['preco'] ?? ''; ?>" required>
            <label>Imagem:</label>
            <input type="file" name="imagem">
            <?php if ($produtoAtual && $produtoAtual['imagem']): ?>
                <img src="<?php echo $produtoAtual['imagem']; ?>" alt="Imagem do produto" style="max-width: 100px;">
            <?php endif; ?>
            <button type="submit"><?php echo $produtoAtual ? 'Atualizar Produto' : 'Cadastrar Produto'; ?></button>
        </form>
        <!-- Tabela de Produtos -->
        <h2>Lista de Produtos</h2>
        <table border="1">
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
                <?php while ($produto = $produtos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $produto['id_produto']; ?></td>
                        <td><?php echo $produto['nome_produto']; ?></td>
                        <td><?php echo $produto['codigo_produto']; ?></td>
                        <td><?php echo $produto['descricao_produto']; ?></td>
                        <td><?php echo $produto['quantidade_estoque']; ?></td>
                        <td><?php echo 'R$ ' . number_format($produto['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo $produto['nome_fornecedor']; ?></td>
                        <td>
                            <?php if ($produto['imagem']): ?>
                                <img src="<?php echo $produto['imagem']; ?>" alt="Imagem" class="thumbnail">
                            <?php else: ?>
                                Sem Imagem
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="?edit_id=<?php echo $produto['id_produto']; ?>">Editar</a>
                            <a href="?delete_id=<?php echo $produto['id_produto']; ?>" onclick="return confirm('Confirmar exclusão?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="pag-principal.php" class="back-button">Voltar</a>
    </div>
</body>
</html>
