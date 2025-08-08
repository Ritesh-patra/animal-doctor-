<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require './mailer/PHPMailer.php';
require './mailer/SMTP.php';
require './mailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name       = $_POST['name'];
    $contact    = $_POST['contact'];
    $whatsapp   = $_POST['whatsapp'];
    $email      = $_POST['email'];
    $designation= $_POST['designation'];
    $course     = $_POST['course'];
    $passout    = $_POST['passout'];
    $address    = $_POST['address'];
    $city       = $_POST['city'];
    $state      = $_POST['state'];
    $pincode    = $_POST['pincode'];
    $country    = $_POST['country'];

    // Upload files
    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    function saveFile($inputName, $uploadDir) {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
            $filename = time() . "_" . basename($_FILES[$inputName]['name']);
            $targetPath = $uploadDir . $filename;
            move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath);
            return $targetPath;
        }
        return '';
    }

    $photo = saveFile('photo', $uploadDir);
    $license = saveFile('license', $uploadDir);
    $aadharFront = saveFile('aadhar_front', $uploadDir);
    $aadharBack  = saveFile('aadhar_back', $uploadDir);

    // Send Email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Change if using other mail provider
        $mail->SMTPAuth   = true;
        $mail->Username   = 'patrasagarika654@gmail.com'; // Your email
        $mail->Password   = 'yder qkfe hbng lfcj';    // App password or email password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender & Receiver
        $mail->setFrom('patrasagarika654@gmail.com', 'Vet Registration');
        // $mail->addAddress('patrasagarika654@gmail.com'); // Receiver email

        // Attachments
        if ($photo)        $mail->addAttachment($photo, "Photo");
        if ($license)      $mail->addAttachment($license, "License");
        if ($aadharFront)  $mail->addAttachment($aadharFront, "Aadhar Front");
        if ($aadharBack)   $mail->addAttachment($aadharBack, "Aadhar Back");

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "New Vet Registration: $name";
        $mail->Body    = "
            <h3>Vet Registration Details</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Contact:</strong> $contact</p>
            <p><strong>WhatsApp:</strong> $whatsapp</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Designation:</strong> $designation</p>
            <p><strong>Course:</strong> $course</p>
            <p><strong>Passout:</strong> $passout</p>
            <p><strong>Address:</strong> $address, $city, $state, $pincode, $country</p>
        ";

        $mail->send();
        echo "<h2>Registration submitted successfully!</h2>";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
?>
