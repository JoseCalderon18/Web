<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ajustar la ruta según la estructura de tu proyecto
require '../../vendor/autoload.php';

header('Content-Type: application/json');

try {
    // Validar datos recibidos
    if (empty($_POST['nombre']) || empty($_POST['email']) || 
        empty($_POST['asunto']) || empty($_POST['mensaje'])) {
        throw new Exception('Todos los campos son obligatorios');
    }
 
    // Sanitización de datos
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $asunto = htmlspecialchars($_POST['asunto'], ENT_QUOTES, 'UTF-8');
    $mensaje = htmlspecialchars($_POST['mensaje'], ENT_QUOTES, 'UTF-8');

    // Validación del email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El formato del email no es válido');
    }

    // Configurar PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    
    // Configura aquí tu correo y contraseña de aplicación de Gmail
    $mail->Username = 'bioespacio.alcobendas@gmail.com';
    $mail->Password = 'xxxx xxxx xxxx xxxx'; // Aquí va tu contraseña de aplicación
    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // Configurar el correo
    $mail->setFrom('bioespacio.alcobendas@gmail.com', 'BioEspacio Web');
    $mail->addAddress('bioespacio.alcobendas@gmail.com'); // Correo que recibirá los mensajes
    $mail->isHTML(true);
    $mail->Subject = "Nuevo mensaje de contacto: $asunto";
    $mail->Body = "
        <h1>Nuevo mensaje de contacto</h1>
        <p><strong>Nombre:</strong> $nombre</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Asunto:</strong> $asunto</p>
        <p><strong>Mensaje:</strong> $mensaje</p>
    ";

    $mail->send();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
