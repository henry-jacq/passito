<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use App\Core\Config;
use App\Entity\EmailQueue;
use Doctrine\ORM\EntityManagerInterface;

class MailService
{
    public function __construct(
        protected readonly PHPMailer $mailer,
        protected readonly Config $config,
        protected readonly EntityManagerInterface $entityManager
    )
    {
        $this->initServer();
    }

    /**
     * Initialize SMTP server settings
     */
    private function initServer(): void
    {
        // Enable verbose debug output if configured
        if ($this->config->get('mail.debug') === true) {
            $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        }

        // Set the mailer to use SMTP
        $this->mailer->isSMTP();
        
        // Set the SMTP host, port, and security settings
        $this->mailer->Host = $this->config->get('mail.host');
        $this->mailer->Port = $this->config->get('mail.port');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->config->get('mail.user');
        $this->mailer->Password = $this->config->get('mail.pass');
        $this->mailer->setFrom($this->config->get('mail.from'));

        // Set encryption type
        if ($this->config->get('mail.port') == 587) {
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }
    }

    /**
     * Add a recipient to the email
     * 
     * @param string $recipient
     * @param string $name (optional)
     */
    public function addRecipient(string $recipient, string $name = ''): void
    {
        $this->mailer->addAddress($recipient, $name);
    }

    /**
     * Set the email subject and body content
     * 
     * @param string $subject
     * @param string $body
     * @param bool $isHTML (default: true)
     */
    public function setContent(string $subject, string $body, bool $isHTML = true): void
    {
        $this->mailer->isHTML($isHTML);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        $this->mailer->AltBody = strip_tags($body); // Plain-text version of the email for non-HTML email clients
    }

    /**
     * Attach a file to the email
     * 
     * @param string $filePath
     * @param string $fileName (optional)
     */
    public function attachFile(string $filePath, string $fileName = ''): void
    {
        $this->mailer->addAttachment($filePath, $fileName);
    }

    /**
     * Send the email
     * 
     * @return bool
     */
    public function send(): bool
    {
        try {
            return $this->mailer->send();
        } catch (Exception $e) {
            // Log the error or handle it as needed
            return false;
        }
    }

    /**
     * Send a notification email (simple usage)
     * 
     * @param string $recipientEmail
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function sendNotification(string $recipientEmail, string $subject, string $message): bool
    {
        try {
            $this->setContent($subject, $message, true);
            $this->addRecipient($recipientEmail);
            return $this->send();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Queue an email to be sent later
     * 
     * @param string $subject
     * @param string $body
     * @param string $recipient
     * @return bool
     */
    public function queueEmail(string $subject, string $body, string $recipient): bool
    {
        try {
            $emailQueue = new EmailQueue($subject, $body, $recipient);
            $this->entityManager->persist($emailQueue);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return false;
        }
    }

    /**
     * Process the email queue and send the queued emails
     */
    public function processQueue(): void
    {
        $emails = $this->entityManager->getRepository(EmailQueue::class)->findBy(['sent' => false]);

        foreach ($emails as $email) {
            $this->setContent($email->getSubject(), $email->getBody());
            $this->addRecipient($email->getRecipient());

            if ($this->send()) {
                // Mark the email as sent
                $email->setSent(true);
                $this->entityManager->flush();
            }
        }
    }
}
