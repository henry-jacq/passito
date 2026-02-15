<div style="font-family: Arial, sans-serif; line-height: 1.5; color: #111827;">
    <h2 style="margin: 0 0 12px 0; font-size: 18px;">Account removed</h2>

    <p style="margin: 0 0 12px 0;">
        Hi <?= htmlspecialchars($name ?: 'there', ENT_QUOTES, 'UTF-8') ?>,
    </p>

    <p style="margin: 0 0 12px 0;">
        Your <?= htmlspecialchars($app_name ?: 'Passito', ENT_QUOTES, 'UTF-8') ?> account for
        <strong><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></strong> has been removed.
    </p>

    <p style="margin: 0 0 12px 0; color: #374151; font-size: 13px;">
        If you believe this was done in error, contact support:
        <a href="mailto:<?= htmlspecialchars($support_email, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($support_email, ENT_QUOTES, 'UTF-8') ?></a>
    </p>

    <p style="margin: 0; color: #6b7280; font-size: 13px;">
        This is an automated notification.
    </p>
</div>

