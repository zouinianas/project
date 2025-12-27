<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CMail {
    public static function send($config) {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->SMTPDebug = 2; // Permet d'afficher des logs plus détaillés
            $mail->Debugoutput = function($str, $level) {
                error_log("PHPMailer [{$level}]: {$str}");
            };
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'sandbox.smtp.mailtrap.io'); // Utiliser directement les variables d'environnement
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
            $mail->Port       = env('MAIL_PORT', 587);

            // Recipients
            $mail->setFrom(
                $config['from_address'] ?? env('MAIL_FROM_ADDRESS'),
                $config['from_name'] ?? env('MAIL_FROM_NAME')
            );
            $mail->addAddress($config['recipient_address'], $config['recipient_name'] ?? '');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $config['subject'];
            $mail->Body    = $config['body'];

            // Envoyer l'e-mail
            if ($mail->send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("Erreur PHPMailer: " . $mail->ErrorInfo);
            return false;
        }

    }
}
