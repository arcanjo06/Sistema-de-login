<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['pswd'])) {
    
    include_once('config.php');
    $email = $_POST['email'];
    $senha = $_POST['pswd'];
    
    // Preparar a consulta para buscar o usuário com base no email
    $sql = "SELECT * FROM user WHERE Email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o usuário existe
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar se a senha digitada corresponde à senha criptografada no banco
        if (password_verify($senha, $user['Senha'])) {
            // Login bem-sucedido, salvar dados na sessão
            $_SESSION['email'] = $user['Email'];
            $_SESSION['nome'] = $user['Nome'];
            
            // Redirecionar para a página do usuário
            header('Location: user.php');
            exit();
        } else {
            // Senha incorreta
            echo "<script>alert('Senha incorreta');</script>";
            header('Location: login.html');
            exit();
        }
    } else {
        // Usuário não encontrado
        echo "<script>alert('Usuário não encontrado');</script>";
        header('Location: login.html');
        exit();
    }
} else {
    header('Location: login.html');
}
?>