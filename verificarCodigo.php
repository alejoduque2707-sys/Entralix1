<?php
session_start();
$conn = new mysqli("localhost", "root", "", "entralix");

// 🔥 RUTA CORRECTA
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

$correo = $_POST['email'] ?? '';

if (!$correo) {
    die("Correo requerido");
}

// 🔹 Verificar que el usuario exista
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo=?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Correo no registrado");
}

// 🔹 Generar código (más seguro)
$codigo = random_int(100000, 999999);
$expira = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// 🔹 Guardar en BD
$stmt = $conn->prepare("UPDATE usuarios SET codigo_recuperacion=?, expira_codigo=? WHERE correo=?");
$stmt->bind_param("sss", $codigo, $expira, $correo);
$stmt->execute();

// 🔹 Enviar correo
$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;

$mail->Username = 'entralix6@gmail.com';
$mail->Password = 'navf vyzq vevn hueg
';

$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('TU_CORREO@gmail.com', 'Entralix');
$mail->addAddress($correo);

$mail->isHTML(true);
$mail->Subject = 'Recuperar acceso de entralix';
$mail->Body = "
    <h2>Tu código es: $codigo</h2>
    <p>Este código expira en 5 minutos</p>
";

$mail->send();

// 🔹 Guardar sesión
$_SESSION['correo_recuperacion'] = $correo;

header("Location: confirmarCodigo.php");
exit();
?>

