<?php
/** @var \App\Entity\User $user */
/** @var string $reset_link */
/** @var int $expires_minutes */
?>
<div style="font-family: Arial, sans-serif; line-height: 1.5; color: #111827;">
    <h2 style="margin: 0 0 12px 0; font-size: 18px;">Password reset</h2>

    <p style="margin: 0 0 12px 0;">
        Hi <?= htmlspecialchars($user->getName() ?? 'there', ENT_QUOTES, 'UTF-8') ?>,
    </p>

    <p style="margin: 0 0 12px 0;">
        We received a request to reset your password. Use the link below to set a new password.
        This link expires in <?= (int) $expires_minutes ?> minutes.
    </p>

    <p style="margin: 18px 0;">
        <a href="<?= htmlspecialchars($reset_link, ENT_QUOTES, 'UTF-8') ?>"
           style="display: inline-block; padding: 10px 14px; background: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px;">
            Reset password
        </a>
    </p>

    <p style="margin: 0 0 12px 0; color: #374151; font-size: 13px;">
        If the button doesn't work, open this link:
        <br>
        <span style="word-break: break-all;"><?= htmlspecialchars($reset_link, ENT_QUOTES, 'UTF-8') ?></span>
    </p>

    <p style="margin: 0; color: #6b7280; font-size: 13px;">
        If you didn't request this, you can ignore this email.
    </p>
</div>

