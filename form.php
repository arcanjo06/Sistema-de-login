<?php
session_start();
if (isset($_POST['submit'])) {
    include_once('config.php');

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $cargo = $_POST['cargo'];
    $senha = $_POST['pswd'];

    // Criptografar a senha antes de salvar no banco
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // Inserir o usuário no banco de dados
    $sql = "INSERT INTO user(Nome, Email, Data_Nascimento, Cargo, Senha) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssss", $nome, $email, $data_nascimento, $cargo, $senha_hash);
    
    if ($stmt->execute()) {
        // Cadastro bem-sucedido, agora faz login automático
        $_SESSION['email'] = $email;

        // Redireciona para a página do usuário
        header('Location: user.php');
        exit();
    } else {
        // Se ocorrer um erro, redireciona para a página de cadastro novamente
        echo "Erro ao cadastrar usuário.";
        header('Location: cadastro.html');
        exit();
    }
}
?>