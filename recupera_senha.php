<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro EHS</title>
    <link rel="stylesheet" href="login.css">
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
    <img id="logo" src="images/logo.png" alt="">

    <div class="box">


        <fieldset>

            <legend><b>RECUPERAÇÂO DE SENHA!!</b></legend>
            <br>

            <div class="main">  	
                <input type="checkbox" id="chk" aria-hidden="true">
        
                    <div class="signup">
                        <form action="recupera_senha.php" method="POST">
                        <label for="chk" aria-hidden="true">Esqueceu a Senha</label>
                            <input type="email" name="email" placeholder="Digite seu Email" required="">
                            <input type="submit" name="submit" value="Recupere a Senha" id="submit">
                        </form>
                    </div>
                    <?php
session_start();
include_once('config.php');

// Inclui o autoloader do Composer ou o arquivo principal do PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verifica se o formulário foi submetido e se o e-mail foi fornecido
if (isset($_POST['submit']) && !empty($_POST['email'])) {
    $email = $_POST['email'];

    // Verifica se o e-mail existe no banco de dados
    $sql = "SELECT * FROM user WHERE Email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Gerar token único e definir tempo de expiração
        $token = bin2hex(random_bytes(4));

        // Salvar o token no banco de dados com o ID do usuário
        $sql = "INSERT INTO token (user_id, token, expiração) VALUES (?, ?, ?)";
        $expiracao = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iss", $user['ID'], $token, $expiracao);

        // Execute e verifique se ocorreu erro
        if ($stmt->execute()) {
            // Criar uma nova instância do PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Configurações do servidor
                $mail->isSMTP(); // Definindo que vai usar SMTP
                $mail->CharSet='UTF-8';
                $mail->Host = 'smtp.gmail.com'; // Definindo o servidor SMTP
                $mail->SMTPAuth = true; // Ativando autenticação SMTP
                $mail->Username = 'educationhighsphere@gmail.com'; // Seu e-mail
                $mail->Password = 'lcjj jued nslh wysc'; // Sua senha
                $mail->SMTPSecure = 'tls'; // Ativando o TLS
                $mail->Port = 587; // Porta para o SMTP com TLS

                // Destinatários
                $mail->setFrom('educationhighsphere@gmail.com', 'EHSYNC');
                $mail->addAddress($email); // Adiciona um destinatário

                // Conteúdo do e-mail
                $mail->isHTML(true); // Ativando o envio em formato HTML
                $mail->Subject = "Seu Token de Recuperação de Senha";
                $mail->Body = "Aqui está o seu token para redefinir sua senha: <b>" . $token . "</b><br>Ele expira em 1 hora.";
                $mail->AltBody = "Aqui está o seu token para redefinir sua senha: " . $token . " Ele expira em 1 hora.";

                // Enviar e-mail
                $mail->send();
                echo "<script>alert('Um email foi enviado com o token de recuperação');</script>";
                header('Location: recupera_senha/verificar_token.php');
                exit(); // Adicionando exit após o redirecionamento
            } catch (Exception $e) {
                echo "<script>alert('Erro ao enviar o email. Erro: {$mail->ErrorInfo}');</script>";
            }
        } else {
            echo "<script>alert('Erro ao salvar o token no banco de dados: " . $stmt->error . "');</script>";
        }
    } else {
        echo "<script>alert('Email não encontrado.');</script>";
    }
} else {
    echo "<script>alert('Por favor, forneça um e-mail.');</script>";
}
?>

    </div>
    <script>
    </script>

</body>
</html>