<?php
// config/MailHelper.php

class MailHelper {
    public static function send($to, $subject, $message) {
        $headers = "From: no-reply@immoaffaire.ci\r\n";
        $headers .= "Reply-To: no-reply@immoaffaire.ci\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // In a real environment, you'd use PHPMailer for SMTP.
        // On local XAMPP, this usually requires 'sendmail' configuration.
        // We simulate the success for now to keep the flow working.
        
        $htmlMessage = "
        <html>
        <head><title>{$subject}</title></head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
            <div style='background: #2563eb; color: white; padding: 20px;'>
                <h2>ImmoAffaire - Notification</h2>
            </div>
            <div style='padding: 20px; border: 1px solid #e2e8f0;'>
                {$message}
            </div>
        </body>
        </html>
        ";

        return @mail($to, $subject, $htmlMessage, $headers);
    }
}
