<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ajustar la ruta según la estructura de tu proyecto
require '../../vendor/autoload.php';

// Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Asegurar headers JSON
header('Content-Type: application/json');

try {
    if (empty($_POST['nombre']) || empty($_POST['email']) || 
        empty($_POST['asunto']) || empty($_POST['mensaje'])) {
        throw new Exception('Todos los campos son obligatorios');
    }

    $mail = new PHPMailer(true);
    
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'josse1808042@gmail.com'; // Tu correo
    $mail->Password = 'vrwj zsqo gmjy lnyc'; // Tu contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Configuración del correo
    $mail->setFrom('josse1808042@gmail.com', 'BioEspacio'); // El correo se envía desde BioEspacio
    $mail->addReplyTo($_POST['email'], $_POST['nombre']); // El reply-to será el correo del usuario
    $mail->addAddress('josse1808042@gmail.com', 'BioEspacio'); // Correo donde recibirás los mensajes
    
    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Formulario de contacto: ' . $_POST['asunto'];
    $mail->Body = "
        <h2>Nuevo mensaje de contacto</h2>
        <p><strong>Nombre:</strong> {$_POST['nombre']}</p>
        <p><strong>Email:</strong> {$_POST['email']}</p>
        <p><strong>Asunto:</strong> {$_POST['asunto']}</p>
        <p><strong>Mensaje:</strong></p>
        <p>{$_POST['mensaje']}</p>
    ";

    $mail->send();
    
    echo json_encode([
        'exito' => true,
        'mensaje' => 'Mensaje enviado correctamente'
    ]);

} catch (Exception $e) {
    error_log('Error en el envío: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error al enviar el mensaje: ' . $e->getMessage()
    ]);
}
