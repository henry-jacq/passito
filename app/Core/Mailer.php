<?php

namespace App\Core;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    public function __construct(
        public readonly PHPMailer $mailer,
        public readonly Config $config
    )
    {
        $this->initServer();
    }

    /**
     * Initialize server settings
     */
    private function initServer()
    {
        // Enable verbose debug output
        // if ((bool) $this->config->get('mail.debug')) {
        $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        // }
        
        // Send using SMTP
        $this->mailer->isSMTP();
        
        // Set the SMTP server to send through
        $this->mailer->Host = $this->config->get('mail.host');

        // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $this->mailer->Port = (int) $this->config->get('mail.port');

        // Enable SMTP authentication
        $this->mailer->SMTPAuth = true;
        
        // SMTP username
        $this->mailer->Username = $this->config->get('mail.user');
        
        // SMTP password
        $this->mailer->Password = $this->config->get('mail.pass');
        
        // Set From Address
        $this->mailer->setFrom($this->config->get('mail.from'));

        // Enable implicit TLS encryption
        if ((int) $this->config->get('mail.port') == 587) {
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }

    }

    /**
     * Add Recipient
     */
    public function addRecipient(string $recipient)
    {
        return $this->mailer->addAddress($recipient);
    }

    public function addContent(string $subject, string $body, $isHTML=true)
    {
        $this->mailer->isHTML($isHTML);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        $this->mailer->AltBody = $body;
    }
    
    /**
     * Add file attachment
     */
    public function attachFile(string $filePath, string $name='')
    {
        return $this->mailer->addAttachment($filePath, $name);
    }
    
    /**
     * Send mail
     */
    public function send()
    {
        return $this->mailer->send();
    }
    
}
