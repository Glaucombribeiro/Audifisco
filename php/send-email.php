<?php
// Exibe erros para depuração (remova ou comente em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Carrega o autoload do Composer 
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recupera os dados enviados pelo formulário e faz uma sanitização simples
    $nome     = htmlspecialchars($_POST['nome'] ?? '');
    $email    = htmlspecialchars($_POST['email'] ?? '');
    $assunto  = htmlspecialchars($_POST['assunto'] ?? 'Novo contato do site'); // Suporte ao novo formulário
    $servico  = htmlspecialchars($_POST['servico'] ?? 'Não informado'); // Se não enviado, mantém "Não informado"
    $mensagem = htmlspecialchars($_POST['mensagem'] ?? '');

    // Validação dos campos obrigatórios
    if (!$nome || !$email || !$mensagem) {
        die("Por favor, preencha todos os campos obrigatórios.");
    }

    // Configurações do e-mail
    $destinatario = "glauco.ribeiro@audifisco.com.br"; // Altere para o seu e-mail de destino

    // Corpo do e-mail
    $corpo = "Nome: {$nome}\nEmail: {$email}\n";
    if (!empty($_POST['servico'])) {
        $corpo .= "Serviço: {$servico}\n"; // Inclui "Serviço" apenas se estiver presente
    }
    $corpo .= "Mensagem:\n{$mensagem}";

    // Cria uma instância do PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP da Hostinger
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com'; // Altere para o host SMTP correto
        $mail->SMTPAuth   = true;
        $mail->Username   = 'glauco.ribeiro@audifisco.com.br'; // Seu e-mail SMTP
        $mail->Password   = '8>8*kmcR'; // Sua senha SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS (ou use ENCRYPTION_SMTPS para SSL)
        $mail->Port       = 587; // Porta SMTP

        // Configuração de remetente e destinatário
        $mail->setFrom('glauco.ribeiro@audifisco.com.br', 'Glauco Ribeiro'); // Remetente fixo
        $mail->addReplyTo($email, $nome); // Permite que o destinatário responda para o e-mail do usuário
        $mail->addAddress($destinatario);

        // Conteúdo do e-mail
        $mail->CharSet = 'UTF-8'; // Corrige caracteres acentuados
        $mail->Subject = $assunto; // Assunto enviado pelo formulário ou padrão
        $mail->Body    = $corpo;

        // Envia o e-mail
        $mail->send();

        echo "Mensagem enviada com sucesso!";
    } catch (Exception $e) {
        echo "Erro ao enviar a mensagem: {$mail->ErrorInfo}";
    }
} else {
    echo "Método de envio inválido.";
}
        