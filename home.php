<?php
session_start();
include('conexao.php');
?> 

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Home.css">
    <link rel="icon" type="image/x-icon" href="Imagens/Favicon.jpeg">
    <script src="https://kit.fontawesome.com/e08ed7ffaa.js" crossorigin="anonymous"></script>
    <title>Homepage</title>
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
    <section class="hero">
        <div class="hero-container">
            <div class="card">
                <div class="column-left">
                    <h2>Bem-vindo(a), <?php echo $_SESSION['usuario']; ?></h2>
                    <p>Acompanhe informações sobre os fornecedores e visualize dados de produtos de forma fácil e intuitiva!</p>
                    <a href="index.php" class="button">Comece Já!</a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
