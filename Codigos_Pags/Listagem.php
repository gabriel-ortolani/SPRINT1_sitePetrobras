<?php
// Inclui o arquivo que valida a sessão do usuário
include('valida_sessao.php');

// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');

// Verifica se foi solicitado excluir um produto
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // Monta a query para excluir o produto com o ID especificado
    $sql = "DELETE FROM cadastro_produto WHERE id_produto='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $mensagem = "Produto excluído com sucesso!";
    } else {
        // Caso ocorra um erro durante a exclusão, exibe a mensagem de erro
        $mensagem = "Erro ao excluir produto: " . $conn->error;
    }
}

// Verifica se o formulário de pesquisa foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pesquisa'])) {
    $pesquisa = trim($_POST['pesquisa']);
    
    // Previne SQL Injection escapando os caracteres especiais
    $pesquisa = $conn->real_escape_string($pesquisa);
    
    // Query para buscar produtos ou fornecedores que contenham o termo pesquisado
    $sql = "
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
        WHERE 
            p.nome_produto LIKE '%$pesquisa%' OR 
            f.nome_fornecedor LIKE '%$pesquisa%'
    ";
} else {
    // Caso nenhuma pesquisa tenha sido realizada, carrega todos os produtos
    $sql = "
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
    ";
}

// Executa a consulta no banco de dados
$query = $conn->query($sql);

// Verifica se a consulta retornou resultados
if ($query) {
    $resultados = $query->fetch_all(MYSQLI_ASSOC);
}
?>

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
            <form id="pesquisa" method="post">
                <input type="text" name="pesquisa" placeholder="Pesquise por produtos ou fornecedores">
                <button type="submit">Pesquisar</button>
            </form>

            <?php if (isset($mensagem)) echo "<p class='message" .($conn->error ? "error" : "success"). "'> $mensagem</p>"; ?>

            <!-- Resultados -->
            <?php if (!empty($resultados)): ?>
                <h2>Produtos:</h2>
                <div class="tabela-rolavel-wrapper"> <!-- Div que possibilita a rolagem -->
                    <table class="fornecedores-list">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produto</th>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>Estoque</th>
                                <th>Preço</th>
                                <th>Fornecedor</th>
                                <th>Imagem</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados as $row): ?>
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
                                            <img src="<?php echo $row['imagem']; ?>" alt="Imagem do produto" style="max-width: 100px;">
                                        <?php else: ?>
                                            Sem imagem
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="CadastroProd.php?edit_id=<?php echo $row['id_produto']; ?>">Editar</a>
                                        <a href="?delete_id=<?php echo $row['id_produto']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div> <!-- Fim da tabela rolável -->
            <?php else: ?>
                <p>Nenhum produto encontrado.</p>
            <?php endif; ?>

            <a href="pag-principal.php">Voltar</a>
        </div>
    </div>
</body>
</html>