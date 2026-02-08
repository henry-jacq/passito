<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use App\Core\Config;
use App\Core\Storage;

class MailService
{
    public function __construct(
        protected readonly Config $config,
        protected readonly Storage $storage,
        protected readonly PHPMailer $mailer
    ) {
        $this->initServer();
    }

    /**
     * Initialize SMTP server settings
     */
    private function initServer(): void
    {
        if ($this->config->get('notification.mail.debug') === true) {
            $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        }

        $this->mailer->isSMTP();
        $this->mailer->Host = $this->config->get('notification.mail.host');
        $this->mailer->Port = $this->config->get('notification.mail.port');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->config->get('notification.mail.user');
        $this->mailer->Password = $this->config->get('notification.mail.pass');
        $this->mailer->setFrom($this->config->get('notification.mail.from'), $this->config->get('app.name'));

        if ($this->config->get('notification.mail.port') == 587) {
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }
    }

    /**
     * Add a recipient to the email
     */
    public function addRecipient(string $recipient, string $name = ''): void
    {
        $this->mailer->addAddress($recipient, $name);
    }

    /**
     * Set the email subject and body content
     */
    public function setContent(string $subject, string $body, bool $isHTML = true): void
    {
        $this->mailer->isHTML($isHTML);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        $this->mailer->AltBody = strip_tags($body);
    }

    /**
     * Attach a file to the email
     */
    public function attachFile(string $filePath, string $fileName = ''): void
    {
        $this->mailer->addAttachment($filePath, $fileName);
    }

    /**
     * Send the email
     */
    public function send(): bool
    {
        try {
            return $this->mailer->send();
        } catch (Exception $e) {
            // TODO: log error
            return false;
        }
    }

    /**
     * Send a notification email (with optional attachments)
     */
    public function notify(
        string $recipientEmail,
        string $subject,
        string $message,
        bool $isHTML = true,
        ?array $attachments = null
    ): bool {
        $this->mailer->clearAllRecipients();
        $this->mailer->clearAttachments();

        try {
            $this->setContent($subject, $message, $isHTML);
            $this->addRecipient($recipientEmail);

            if (!empty($attachments) && is_iterable($attachments)) {
                foreach ($attachments as $attachment) {
                    $file = $this->storage->getFullPath($attachment);
                    if (is_readable($file)) {
                        $this->attachFile($file);
                    } else {
                        error_log("Attachment not found: $file");
                    }
                }
            }

            return $this->send();
        } catch (\Exception $e) {
            // TODO: log exception
            return false;
        }
    }
}
