<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro EHS</title>
    <link rel="stylesheet" href="../login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, max-scale=1.0">
    <style>
        /* Estiliza a largura e altura da barra de rolagem */
        ::-webkit-scrollbar {
            width: 10px; /* Largura da barra vertical */
            height: 10px; /* Altura da barra horizontal */
        }

        /* Estiliza o fundo da barra de rolagem */
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.6);/* Cor do fundo da barra */
        }

        /* Estiliza a parte de rolagem da barra */
        ::-webkit-scrollbar-thumb {
            background: blue; /* Cor da parte rolável */
        }

        /* Estiliza a parte de rolagem ao passar o mouse */
        ::-webkit-scrollbar-thumb:hover {
            background: #555; /* Cor ao passar o mouse */
        }
        #logo{
            width: 10vw;
            position: absolute;
            top:0%;
            left:45%;
            border: 2px solid rgb(0, 0, 10);
            border-radius: 20%;
            background-color: rgb(0, 0, 10);
        }
        
        .box{
            left:30%;
            height:450px;
            margin-top: 50px;
        }
        .main{
            height: 90%;
            width: 50%;
        }
        #submit{
            height: 40px;
            font-size: 15px;
            margin-top: 50px;
        }
        input{
            width: 80%;
            text-align: center;
        }
        label{
            font-size: 20px;
            text-wrap: nowrap;
            margin-top: 80px;
        }
        /* Estilo para uma div de exemplo */
    </style>
</head>

<body>
    <img id="logo" src="../images/logo.png" alt="">

    <div class="box">


        <fieldset>

            <legend><b>Redefinir Senha!!</b></legend>
            <br>

            <div class="main">  	
                <input type="checkbox" id="chk" aria-hidden="true">
        
                    <div class="signup">
                        <form action="redefinir_senha.php" method="POST">
                        <label for="chk" aria-hidden="true">Redefina sua Senha</label>
                            <input type="password" name="senha" placeholder="Digite a senha nova" required="">
                            <button type="button" class="toggleSenha">
                                <i class="fa fa-eye"></i>
                            </button>
                            <input type="submit" name="submit" value="Redefinir Senha" id="submit">
                        </form>
                    </div>
<?php
session_start();
include_once('../config.php');

// Capturar a nova senha do formulário
if (!isset($_SESSION['token'])) {
    // Redireciona o usuário para a página de login
    header('Location: ../login.html');
    exit(); // Garante que o script seja interrompido após o redirecionamento
}
if (isset($_POST['senha'])) {
    if (isset($_SESSION['token'])) {
        $token = $_SESSION['token']; // Buscar o token armazenado na sessão
        echo "<script>alert('Token na sessão: " . htmlspecialchars($token) . "<br>')</script>"; // Verificar o token
    } else {
        echo "Token não encontrado na sessão.<br>";
        exit();
    }

    $nova_senha = $_POST['senha'];
    $nova_senha_hash = password_hash($nova_senha, PASSWORD_BCRYPT);
    echo "Nova senha (hash): " . $nova_senha_hash . "<br>"; // Mostrar o hash da nova senha

    // Passo 1: Buscar o user_id associado ao token
    $sql = "SELECT user_id FROM token WHERE token = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tokenData = $result->fetch_assoc();
        $user_id = $tokenData['user_id'];

        // Passo 2: Atualizar a senha do usuário no banco de dados
        $sql = "UPDATE user SET Senha = ? WHERE ID = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("si", $nova_senha_hash, $user_id);

        if ($stmt->execute()) {
            // Passo 3: Excluir o token após o uso
            $sql = "DELETE FROM token WHERE token = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("s", $token);
            if ($stmt->execute()) {
                echo "Token excluído com sucesso.<br>";
            } else {
                echo "Erro ao excluir o token: " . $stmt->error . "<br>";
            }

            unset($_SESSION['token']);
            echo "Sua senha foi redefinida com sucesso!";
            header("Location: ../login.html");
            exit();
        } else {
            echo "Erro ao atualizar a senha: " . $stmt->error . "<br>";
        }
    } else {
        echo "Token inválido ou expirado.<br>";
    }
} else {
    echo "Por favor, preencha todos os campos.";
}
?>
    </div>
    <script>
        alert('Token Validado');
        document.querySelectorAll('.toggleSenha').forEach(button => {
    button.addEventListener('click', function () {
        // Acha o campo de senha que está associado ao botão clicado
        const senhaInput = this.previousElementSibling;
        const iconeOlho = this.querySelector('i');

        // Alterna entre mostrar e esconder a senha
        if (senhaInput.type === "password") {
            senhaInput.type = "text";
            iconeOlho.classList.remove("fa-eye");
            iconeOlho.classList.add("fa-eye-slash");
        } else {
            senhaInput.type = "password";
            iconeOlho.classList.remove("fa-eye-slash");
            iconeOlho.classList.add("fa-eye");
        }
    });
        })

    </script>

</body>
</html>