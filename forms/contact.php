<?php

// Configurações
$recipient = "suporte@brkinfo.serv00.net"; // O endereço de e-mail de destino
$default_subject_prefix = "Assunto da mensagem | "; // Prefixo para o assunto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Captura e Sanitize dos Dados
    // Use a função isset() para garantir que a variável exista antes de acessá-la
    $name = isset($_POST["name"]) ? strip_tags(trim($_POST["name"])) : '';
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    
    $email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
    
    // Captura o campo assunto que estava faltando
    $subject_form = isset($_POST["subject"]) ? strip_tags(trim($_POST["subject"])) : 'Sem Assunto';
    
    $message = isset($_POST["message"]) ? trim($_POST["message"]) : '';

    // 2. Validação dos Dados
    // Adicionada verificação para o campo 'subject' (assunto)
    if (empty($name) || empty($email) || empty($subject_form) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Por favor, preencha todos os campos obrigatórios e forneça um endereço de email válido.";
        exit;
    }
  
    // 3. Configuração do Email

    // Constrói o assunto final. Se houver um assunto do formulário, use-o com o prefixo.
    $subject = $default_subject_prefix . $subject_form;

    // CONSTRÓI O CONTEÚDO COMPLETO DO E-MAIL
    $email_content = "Nome: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Assunto: $subject_form\n\n";
    $email_content .= "Mensagem:\n";
    $email_content .= "$message\n";

    // Cabeçalhos adicionais
    // O cabeçalho 'From' é importante, mas o servidor pode impor o uso de um e-mail local.
    // Usaremos 'Reply-To' para facilitar a resposta.
    $headers = "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n"; // Boa prática para identificação
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n"; // Especifica a codificação UTF-8

    // 4. Envio do Email
    if (mail($recipient, $subject, $email_content, $headers)) {
        http_response_code(200);
        echo "OK"; // O validate.js espera "OK" para sucesso
    } else {
        http_response_code(500);
        echo "Ops! Algo deu errado e não conseguimos enviar sua mensagem. Tente novamente mais tarde.";
    }
} else {
    // 5. Método de Requisição Inválido
    http_response_code(403);
    echo "Houve um problema com o seu envio. Apenas requisições POST são permitidas.";
}
?>