<?php
session_start();
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = ($_POST['senha']);

    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND senha='$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['usuario'] = $usuario;
        header('Location: home.php');
    } else {
        $error = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Link com o Style (CSS) -->
    <link rel="stylesheet" href="style.css">
    <!-- Alteração de Ícone no topo (Favicon) -->
    <link rel="icon" type="image/x-icon" href="Imagens/Favicon.jpeg">
    <script src="https://kit.fontawesome.com/e08ed7ffaa.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="page">
        <!-- Div para a criação do fundo branco -->
        <div class="card">
            <!-- Formulários -->
            <h2>LOGIN</h2>
            <form method="post" action="">
                <label for="usuario" class="texto" >Usuário:</label>
                <input class="campo" type="text" name="usuario" placeholder="Digite aqui seu usuário..." required>

                <label for="senha" class="texto" >Senha:</label>
                <input class="campo" type="password" name="senha" placeholder="Digite aqui sua senha..." required>

                <a href="Senha.html">Esqueci minha senha</a>

                <button class="btn" type="submit">Entrar</button>
                <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>

                <p>Ainda não tem uma conta? <a href="Cadastre_Se.html">Cadastre-se.</a></p>
            </form>
        </div>
    </div>
</body>
</html>