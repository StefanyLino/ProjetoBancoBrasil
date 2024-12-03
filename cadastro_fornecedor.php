<?php
// Inclui o arquivo que valida a sessão do usuário
include('valida_sessao.php');
// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');

// Função para redimensionar e salvar a imagem
function redimensionarESalvarImagem($arquivo, $largura = 80, $altura = 80) {
    $diretorio_destino = "img/";
    $nome_arquivo = uniqid() . '_' . basename($arquivo["name"]);
    $caminho_completo = $diretorio_destino . $nome_arquivo;
    $tipo_arquivo = strtolower(pathinfo($caminho_completo, PATHINFO_EXTENSION));

    // Verifica se é uma imagem válida
    $check = getimagesize($arquivo["tmp_name"]);
    if($check === false) {
        return "O arquivo não é uma imagem válida.";
    }

    // Verifica o tamanho do arquivo (limite de 5MB)
    if ($arquivo["size"] > 5000000) {
        return "O arquivo é muito grande. O tamanho máximo permitido é 5MB.";
    }

    // Permite apenas alguns formatos de arquivo
    if($tipo_arquivo != "jpg" && $tipo_arquivo != "png" && $tipo_arquivo != "jpeg" && $tipo_arquivo != "gif" ) {
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
    $largura_original = imagesx($imagem_original);
    $altura_original = imagesy($imagem_original);

    // Calcula as novas dimensões mantendo a proporção
    $ratio = min($largura / $largura_original, $altura / $altura_original);
    $nova_largura = $largura_original * $ratio;
    $nova_altura = $altura_original * $ratio;

    // Cria uma nova imagem com as dimensões calculadas
    $nova_imagem = imagecreatetruecolor($nova_largura, $nova_altura);

    // Redimensiona a imagem original para a nova imagem
    imagecopyresampled($nova_imagem, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);

    // Salva a nova imagem
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
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $telefone = $_POST['telefone']; 
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];

    // Processa o upload da imagem
    $imagem = "";
    if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $resultado_upload = redimensionarESalvarImagem($_FILES['imagem']);
        if(strpos($resultado_upload, 'img/') === 0) {
            $imagem = $resultado_upload;
        } else {
            $mensagem_erro = $resultado_upload;
        }
    }

    // Prepara a query SQL para inserção ou atualização
    if ($id) {
        // Se o ID existe, é uma atualização
        $sql = "UPDATE fornecedores SET nome='$nome', cnpj='$cnpj', telefone='$telefone',  email='$email', endereco='$endereco'";
        if($imagem) {
            $sql .= ", imagem='$imagem'";
        }
        $sql .= " WHERE id='$id'";
        $mensagem = "Fornecedor atualizado com sucesso!";
    } else {
        // Se não há ID, é uma nova inserção
        $sql = "INSERT INTO fornecedores (nome, cnpj, telefone, email, endereco, imagem) VALUES ('$nome', '$cnpj', '$telefone', '$email', '$endereco', '$imagem')";
        $mensagem = "Fornecedor cadastrado com sucesso!";
    }

    // Executa a query e verifica se houve erro
    if ($conn->query($sql) !== TRUE) {
        $mensagem = "Erro: " . $conn->error;
    }
}

// Verifica se foi solicitada a exclusão de um fornecedor
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Verifica se o fornecedor tem produtos cadastrados
    $check_produtos = $conn->query("SELECT COUNT(*) as count FROM produtos WHERE fornecedor_id = '$delete_id'")->fetch_assoc();
    
    if ($check_produtos['count'] > 0) {
        $mensagem = "Não é possível excluir este fornecedor pois existem produtos cadastrados para ele.";
    } else {
        $sql = "DELETE FROM fornecedores WHERE id='$delete_id'";
        if ($conn->query($sql) === TRUE) {
            $mensagem = "Fornecedor excluído com sucesso!";
        } else {
            $mensagem = "Erro ao excluir fornecedor: " . $conn->error;
        }
    }
}

// Busca todos os fornecedores para listar na tabela
$fornecedores = $conn->query("SELECT * FROM fornecedores");

// Se foi solicitada a edição de um fornecedor, busca os dados dele
$fornecedor = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $fornecedor = $conn->query("SELECT * FROM fornecedores WHERE id='$edit_id'")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Fornecedor.css">
    <link rel="icon" type="image/x-icon" href="Imagens/Favicon.jpeg">
    <script src="https://kit.fontawesome.com/e08ed7ffaa.js" crossorigin="anonymous"></script>
    <title>Cadastro de Fornecedor</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
</head>
<body>
    <nav>
        <div class="logo"><img src="Imagens/Logo.png" height="50px" alt="Logo"></div>
        <div class="nav-items">
            <a href="home.php"><i class="fa-solid fa-house"></i>&nbsp;HOME</a>
            <a href="index.php"><i class="fa-solid fa-folder"></i>&nbsp;Sistema de Cadastro</a>
            <a href="logout.php"><i class="fa-solid fa-user"></i>&nbsp;Sair</a>
        </div>
    </nav>

    <div class="page">
        <!-- Div para a criação do fundo branco -->
        <div class="card">
            <!-- Formulários -->
            <h2>Cadastro do Fornecedor</h2>
            <!-- Formulário para cadastro/edição de fornecedor -->
            <form method="post" action="" enctype="multipart/form-data">
                <input class="campo" type="hidden" name="id" value="<?php echo $fornecedor['id'] ?? ''; ?>">
             
                <label for="nome">Nome:</label>
                <input placeholder="Digite aqui..." class="campo" type="text" name="nome" value="<?php echo $fornecedor['nome'] ?? ''; ?>" required>
                <label for="cnpj">CNPJ:</label>
                <input placeholder="Digite aqui..."  class="campo" type="text" name="cnpj" value="<?php echo $fornecedor['cnpj'] ?? ''; ?>" required>
                <label for="telefone">Telefone:</label>
                <input placeholder="Digite aqui..."  class="campo" type="text" name="telefone" value="<?php echo $fornecedor['telefone'] ?? ''; ?>" required>
             
                <label for="email">Email:</label>
                <input placeholder="Digite aqui..."  class="campo" type="email" name="email" value="<?php echo $fornecedor['email'] ?? ''; ?>" required>
             
                <label for="endereco">Endereço:</label>
                <input placeholder="Digite aqui..."  class="campo" type="text" name="endereco" value="<?php echo $fornecedor['endereco'] ?? ''; ?>" required>
             
                <label  for="imagem">Imagem:</label>
                <label for="imagem" class="label-file">Escolher Arquivo</label>
                <input type="file" id="imagem" name="imagem" class="input-file" accept="image/*">
                <?php if (isset($fornecedor['imagem']) && $fornecedor['imagem']): ?>
                    <img src="<?php echo $fornecedor['imagem']; ?>" alt="Imagem atual do fornecedor" class="update-image">
                <?php endif; ?>
                <br>
                <button class="btn btn-custom" type="submit"><?php echo $fornecedor ? 'Atualizar' : 'Cadastrar'; ?></button>
            </form>
        </div>
             
            <!-- Exibe mensagens de sucesso ou erro -->
            <?php
            if (isset($mensagem)) echo "<p class='message " . (strpos($mensagem, 'Erro') !== false ? "error" : "success") . "'>$mensagem</p>";
            if (isset($mensagem_erro)) echo "<p class='message error'>$mensagem_erro</p>";
            ?>
            <div class="card2">
            <h2>Listagem de Fornecedores</h2>
                <!-- Tabela para listar os fornecedores cadastrados -->
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Endereço</th>
                            <th>Imagem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                <?php while ($row = $fornecedores->fetch_assoc()): ?>
                <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['cnpj']; ?></td>
                        <td><?php echo $row['telefone']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['endereco']; ?></td>
                        <td>
                            <?php if ($row['imagem']): ?>
                                <img src="<?php echo $row['imagem']; ?>" alt="Imagem do fornecedor" class="thumbnail">
                            <?php else: ?>
                                Sem imagem
                            <?php endif; ?>
                        </td>
                        <td class="botoes-container">
                            <a class="editar" href="?edit_id=<?php echo $row['id']; ?>">Editar</a>
                            <a class="excluir" href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                </tr>
                <?php endwhile; ?>
            </table>
            </div>
            <a href="index.php" class="btn">Voltar</a>
    </div>
</body>
</html>