<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use App\Core\Config;
use App\Core\Storage;
use App\Entity\EmailQueue;
use Doctrine\ORM\EntityManagerInterface;

class MailService
{
    public function __construct(
        protected readonly Config $config,
        protected readonly Storage $storage,
        protected readonly PHPMailer $mailer,
        protected readonly EntityManagerInterface $entityManager
    ) {
        $this->initServer();
    }

    /**
     * Initialize SMTP server settings
     */
    private function initServer(): void
    {
        // Enable verbose debug output if configured
        if ($this->config->get('notification.mail.debug') === true) {
            $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        }

        // Set the mailer to use SMTP
        $this->mailer->isSMTP();

        // Set the SMTP host, port, and security settings
        $this->mailer->Host = $this->config->get('notification.mail.host');
        $this->mailer->Port = $this->config->get('notification.mail.port');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->config->get('notification.mail.user');
        $this->mailer->Password = $this->config->get('notification.mail.pass');
        $this->mailer->setFrom($this->config->get('notification.mail.from'), $this->config->get('app.name'));

        // Set encryption type
        if ($this->config->get('notification.mail.port') == 587) {
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
     */
    public function notify(string $recipientEmail, string $subject, string $message, bool $isHTML=true): bool
    {
        $this->mailer->clearAllRecipients();
        $this->mailer->clearAttachments();

        try {
            // Set up the email content
            $this->setContent($subject, $message, $isHTML);
            $this->addRecipient($recipientEmail);

            // Attempt to send the email
            return $this->send();
        } catch (\Exception $e) {
            // Log or handle the exception if needed
            return false;
        }
    }

    /**
     * Queue an email to be sent later
     */
    public function queueEmail(
        string $subject,
        string $body,
        string $recipient,
        ?array $attachments = null,
        ?\DateTimeInterface $timeToSend = null
    ): bool {
        try {
            $emailQueue = new EmailQueue($subject, $body, $recipient, $attachments, $timeToSend);
            $this->entityManager->persist($emailQueue);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return false;
        }
    }

    /**
     * Process the email queue
     */
    public function processQueue(): void
    {
        // Fetch emails scheduled to be sent up to the current time
        $emails = $this->entityManager
            ->getRepository(EmailQueue::class)
            ->createQueryBuilder('e')
            ->where('e.timeToSend <= :now')
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getResult();

        foreach ($emails as $email) {
            // Reset the PHPMailer instance for a clean state
            $this->mailer->clearAllRecipients();
            $this->mailer->clearAttachments();

            // Set up the email content
            $this->setContent($email->getSubject(), $email->getBody(), true);

            // TODO: Add recipient name if available (optional)
            $this->addRecipient($email->getRecipient());

            // Dynamically attach the files if they exist
            $attachments = $email->getAttachments();
            if (!empty($attachments) && is_iterable($attachments)) {
                foreach ($attachments as $attachment) {

                    $file = $this->storage->getFullPath($attachment);
                    
                    if (is_readable($file)) {
                        $this->attachFile($file); // Attach the file
                    } else {
                        error_log("Attachment file not found: $file");
                    }
                }
            }

            // Send the email
            if ($this->send()) {
                echo "Email sent to {$email->getRecipient()} with subject: {$email->getSubject()}\n";
                // Delete the email from the queue after successful sending
                $this->entityManager->remove($email);
                $this->entityManager->flush();
            }
        }
    }
}
