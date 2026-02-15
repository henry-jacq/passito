<div style="font-family: Arial, sans-serif; line-height: 1.5; color: #111827;">
    <h2 style="margin: 0 0 12px 0; font-size: 18px;">Your account is ready</h2>

    <p style="margin: 0 0 12px 0;">
        Hi <?= htmlspecialchars($name ?: 'there', ENT_QUOTES, 'UTF-8') ?>,
    </p>

    <p style="margin: 0 0 12px 0;">
        Your <?= htmlspecialchars($app_name ?: 'Passito', ENT_QUOTES, 'UTF-8') ?> account has been created for
        <strong><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></strong>.
    </p>

    <div style="margin: 14px 0 12px 0; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
        <div style="font-size: 14px; font-weight: 700; margin-bottom: 8px;">Login credentials</div>
        <div style="font-size: 13px; color: #111827; margin-bottom: 6px;">
            Email: <strong><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
        <div style="font-size: 13px; color: #111827;">
            Password:
            <?php if (!empty($default_password)): ?>
                <strong><?= htmlspecialchars($default_password, ENT_QUOTES, 'UTF-8') ?></strong>
            <?php else: ?>
                <strong>(not included)</strong>
                <span style="color:#6b7280;">Use the reset link below to set a password.</span>
            <?php endif; ?>
        </div>
    </div>

    <p style="margin: 18px 0;">
        <a href="<?= htmlspecialchars($login_url, ENT_QUOTES, 'UTF-8') ?>"
           style="display: inline-block; padding: 10px 14px; background: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px;">
            Sign in
        </a>
    </p>

    <p style="margin: 0 0 12px 0; color: #374151; font-size: 13px;">
        For security, reset/change your password after your first sign in:
        <br>
        <a href="<?= htmlspecialchars($forgot_url, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($forgot_url, ENT_QUOTES, 'UTF-8') ?></a>
    </p>

    <p style="margin: 0; color: #6b7280; font-size: 13px;">
        If you think this was a mistake, please contact your hostel administration.
    </p>
</div>
