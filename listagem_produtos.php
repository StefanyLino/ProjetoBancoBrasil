<?php include('valida_sessao.php'); ?>
<?php include('conexao.php'); ?>

<?php
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM produtos WHERE id='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $mensagem = "Produto excluído com sucesso!";
    } else {
        $mensagem = "Erro ao excluir produto: " . $conn->error;
    }
}

$produtos = $conn->query("SELECT p.id, p.nome, p.descricao, p.imagem, f.nome AS fornecedor_nome FROM produtos p JOIN fornecedores f ON p.fornecedor_id = f.id");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="Imagens/Favicon.jpeg">
    <script src="https://kit.fontawesome.com/e08ed7ffaa.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Listagem.css">
    <title>Listagem de Produtos</title>
</head>
<body>
    <nav>
        <div class="logo"><img src="Imagens/Logo.png" height="50px" alt="Logo"></div>
        <div class="nav-items">
            <a href="home.php"><i class="fa-solid fa-house"></i>&nbsp;HOME</a>
            <a href="index.php"><i class="fa-solid fa-folder"></i>&nbsp;Sistema de Cadastro</a>
            <a href="login.php"><i class="fa-solid fa-user"></i>&nbsp;Sair</a>
        </div>
    </nav>
    <div class="page">
        <!-- Div para a criação do fundo branco -->
        <div class="card">
            <h2>Listagem de Produtos</h2>
            <?php if (isset($mensagem)) echo "<p class='message " . ($conn->error ? "error" : "success") . "'>$mensagem</p>"; ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Fornecedor</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
                <?php while ($row = $produtos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nome']; ?></td>
                    <td><?php echo $row['descricao']; ?></td>
                    <td><?php echo $row['fornecedor_nome']; ?></td>
                    <td>
                        <?php if ($row['imagem']): ?>
                            <img src="<?php echo $row['imagem']; ?>" alt="Imagem do produto" style="max-width: 100px;">
                        <?php else: ?>
                            Sem imagem
                        <?php endif; ?>
                    </td>
                    <div class="botoes-container">
                        <td>
                            <a class="editar" href="cadastro_produto.php?edit_id=<?php echo $row['id']; ?>">Editar</a>
                            <a class="excluir" href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </div>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <a href="index.php" class="btn">Voltar</a>
    </div>
</body>
</html>