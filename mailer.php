<?php
require_once __DIR__ . '/config.php';

/**
 * Envoi d'un email simple en texte ou HTML via mail().
 * Pour plus de fiabilité, configurez un relais SMTP côté hébergeur.
 */
function send_mail(string $to, string $subject, string $html, string $textFallback = null) : bool {
    $boundary = uniqid('np');
    $from = MAIL_FROM_NAME . " <" . MAIL_FROM . ">";

    if ($textFallback === null) {
        // Crée un fallback texte depuis le HTML brut
        $textFallback = trim(strip_tags(preg_replace('/<br\s*\/?>/i', "\n", $html)));
    }

    $headers = [];
    $headers[] = "From: {$from}";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Return-Path: " . MAIL_RETURN_PATH;
    $headers[] = "Content-Type: multipart/alternative; boundary=" . $boundary;

    $message = "";
    $message .= "--$boundary\r\n";
    $message .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
    $message .= $textFallback . "\r\n";
    $message .= "--$boundary\r\n";
    $message .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
    $message .= $html . "\r\n";
    $message .= "--$boundary--";

    $ok = @mail($to, "=?UTF-8?B?".base64_encode($subject)."?=", $message, implode("\r\n", $headers));

    if (MAIL_LOG) {
        $log = __DIR__ . '/mail.log';
        file_put_contents($log, "[".date('c')."] to=$to subject=$subject ok=" . ($ok?'1':'0') . "\n", FILE_APPEND);
    }
    return $ok;
}
