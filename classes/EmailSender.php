<?php
require '../vendor/autoload.php';
require_once "../config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class EmailSender
{
    private $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader('../assets/email_templates/');
        $this->twig = new Environment($loader);
    }

    public function sendEmail($recipient, $subject, $templateName, $templateVariables)
    {
        // Retrieve SMTP configurations from the config file
        $smtpHost = SMTP_HOST;
        $smtpUsername = SMTP_USERNAME;
        $smtpPassword = SMTP_PASSWORD;
        $smtpSecure = SMTP_SECURE;
        $smtpPort =  SMTP_PORT;

        $template = $this->twig->load($templateName);
        $body = $template->render($templateVariables);

        try {
            $mail = new PHPMailer(true);

            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = $smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUsername;
            $mail->Password = $smtpPassword;
            $mail->SMTPSecure = $smtpSecure;
            $mail->Port = $smtpPort;

            // Sender and recipient
            $mail->setFrom(FROM_EMAIL, WEBSITE_NAME);
            $mail->addAddress($recipient);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Send the email
            $mail->send();
            // echo 'Email sent successfully';
        } catch (Exception $e) {
            echo 'Email could not be sent. Error: ', $mail->ErrorInfo;
        }
    }
}
