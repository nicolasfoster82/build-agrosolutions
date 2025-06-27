<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Honeypot spam prevention
    if (!empty($_POST['contact_hidden'])) {
        echo json_encode(['success' => true]);
        exit;
    }

    $nombre    = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $apellido  = htmlspecialchars(trim($_POST['apellido'] ?? ''));
    $email     = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $mensaje   = htmlspecialchars(trim($_POST['mensaje'] ?? ''));

    if ($nombre && $apellido && $email && $mensaje) {
        $to      = 'nicolasfoster82@gmail.com';
        $subject = 'Nuevo mensaje de contacto';
        $logoUrl = sprintf('https://agrosolutions2025.com/static/media/logo.7a51a12202d91caaae49.png', $_SERVER['HTTP_HOST']);

        $body = "<html><body style='font-family:Arial,sans-serif;color:#333;'>" .
                "<div style='text-align:center;margin-bottom:20px'>" .
                "<img src='{$logoUrl}' alt='logo' style='max-width:150px'/>" .
                "</div>" .
                "<div style='padding:10px;border:1px solid #ddd'>" .
                "<h2 style='color:#444'>Nuevo mensaje de contacto</h2>" .
                "<p><strong>Nombre:</strong> {$nombre}</p>" .
                "<p><strong>Apellido:</strong> {$apellido}</p>" .
                "<p><strong>Email:</strong> {$email}</p>" .
                "<p><strong>Mensaje:</strong><br/>" . nl2br($mensaje) . "</p>" .
                "</div>" .
                "</body></html>";

        $from    = 'no-reply@' . $_SERVER['HTTP_HOST'];
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: Sitio Web <{$from}>\r\n";
        $headers .= "Reply-To: {$nombre} {$apellido} <{$email}>\r\n";

        $sent = mail($to, $subject, $body, $headers);
        echo json_encode(['success' => $sent]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false]);
}
?>