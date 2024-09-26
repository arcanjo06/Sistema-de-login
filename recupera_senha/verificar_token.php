<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro EHS</title>
    <link rel="stylesheet" href="../login.css">
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

            <legend><b>Verificar Token!!</b></legend>
            <br>

            <div class="main">  	
                <input type="checkbox" id="chk" aria-hidden="true">
        
                    <div class="signup">
                        <form action="verificar_token.php" method="POST">
                        <label for="chk" aria-hidden="true">Verificar Token</label>
                            <input type="text" name="token" placeholder="Digite o token" required="">
                            <input type="submit" name="submit" value="Verificar Token" id="submit">
                        </form>
                    </div>
<?php
session_start();
include_once('../config.php');

if (isset($_POST['token'])) {
    $token = $_POST['token'];

    // Verificar se o token é válido e não expirou
    $sql = "SELECT * FROM token WHERE token = ? AND expiração > NOW()";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Token válido, redirecionar para a página de redefinição de senha
        $_SESSION['token'] = $token; // Armazena o token na sessão
        header('Location: redefinir_senha.php');
        exit();
    } else {
        echo "Token inválido ou expirado.";
    }
}
?>
                    
    </div>
    <script>
    </script>

</body>
</html>