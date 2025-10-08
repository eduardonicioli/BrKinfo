<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupere os dados do formulário
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Verifique se os dados estão válidos
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Por favor, preencha todos os campos e forneça um endereço de email válido.";
        exit;
    }
  
     
    // Configure o destinatário e a mensagem do email
    $recipient = "brkinfo2004@gmail.com";
    $subject = "Nova mensagem de $name";
    $email_content = "Email: $email\n\n";
    $email_content = "$message\n";

    // Cabeçalhos adicionais
    $headers = "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n"; // Especifica a codificação UTF-8


    // Envie o email
    if (mail($recipient, $subject, $email_content, $headers)) {
        http_response_code(200);
        echo "Obrigado! Sua mensagem foi enviada com sucesso.";
    } else {
        http_response_code(500);
        echo "Ops! Algo deu errado e não conseguimos enviar sua mensagem.";
    }
} else {
    http_response_code(403);
    echo "Houve um problema com o seu envio. Por favor, tente novamente.";
}
?>