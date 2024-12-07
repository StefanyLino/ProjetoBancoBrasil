<?php include('valida_sessao.php'); ?>
<!-- Inclui o arquivo 'valida_sessao.php' para garantir que o usuário esteja autenticado -->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/CadastroFor.css">
    <link rel="icon" type="image/x-icon" href="Imagens/Favicon.jpeg">
    <script src="https://kit.fontawesome.com/e08ed7ffaa.js" crossorigin="anonymous"></script>
    <title>Painel Principal</title>
    <!-- Link para o arquivo CSS para estilização da página -->
    <!-- <link rel="stylesheet" href="styles.css"> -->
</head>
<body>
    <nav>
        <div class="logo"><a href="home.php"><img src="Imagens/Logo.png" height="50px" alt="Logo"></a></div>
        <div class="nav-items">
            <a href="home.php"><i class="fa-solid fa-house"></i>&nbsp;HOME</a>
            <a href="index.php"><i class="fa-solid fa-folder"></i>&nbsp;Sistema de Cadastro</a>
            <a href="logout.php"><i class="fa-solid fa-user"></i>&nbsp;Sair</a>
        </div>
    </nav>
    <div class="container">

            <!-- Exibe uma mensagem de boas-vindas com o nome do usuário logado -->
            <h2>SISTEMA DE CADASTRO</h2>

        <section class="cards">
            <div class="card">
            <a href="cadastro_fornecedor.php"><img src="Imagens/fornecedores.png" alt="Cadastro de Fornecedor"></a>
            <a href="cadastro_fornecedor.php"><h3>Cadastro de Fornecedor</h3></a>
            <p>Página de Cadastro de Fornecedor.</p>
            </div>
            <div class="card">
            <a href="cadastro_produto.php"><img src="Imagens/produtos.jpeg" alt="Cadastro de Produtos"></a>
            <a href="cadastro_produto.php""><h3>Cadastro de Produtos</h3></a>
            <p>Página de Cadastro de Produtos.</p>
            </div>
            <div class="card">
            <a href="listagem_produtos.php"><img src="Imagens/listagem.png" alt="Listagem"></a>
            <a href="listagem_produtos.php"><h3>Listagem</h3></a>
            <p>Página de listagem de produtos e fornecedores.</p>
            </div>
        </section>
            
    </div>
</body>
</html>
