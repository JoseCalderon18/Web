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
    $mail->Username = 'tu_correo@gmail.com'; // Cambia esto por tu correo
    $mail->Password = 'tu_contraseña_de_aplicacion'; // Cambia esto por tu contraseña de aplicación
    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // Configurar el correo
    $mail->setFrom($email, $nombre);
    $mail->addAddress('correo_destino@gmail.com'); // Cambia esto por el correo donde quieres recibir los mensajes
    $mail->isHTML(true);
    
    // Asunto según el valor seleccionado
    $asuntoTexto = match($asunto) {
        '1' => 'Solicitud de Información',
        '2' => 'Consulta sobre producto',
        '3' => 'Consulta sobre terapias',
        '4' => 'Otro',
        default => 'Contacto desde la web'
    };

    $mail->Subject = "Nuevo mensaje de contacto: $asuntoTexto";
    $mail->Body = "
        <h2>Nuevo mensaje de contacto</h2>
        <p><strong>Nombre:</strong> $nombre</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Asunto:</strong> $asuntoTexto</p>
        <p><strong>Mensaje:</strong></p>
        <p>$mensaje</p>
    ";

    $mail->send();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
