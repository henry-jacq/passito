<?php

$hasData = (bool) ($has_data ?? false);
$appName = trim((string) ($app_name ?? 'Passito'));
$title = (string) ($report?->getReportKey()?->label() ?? 'Scheduled Report');
$description = (string) ($report?->getReportKey()?->description() ?? 'Here is the scheduled report summary for your institution(s).');
$scheduledFor = ($scheduled_for instanceof \DateTimeInterface) ? $scheduled_for->format('d M Y, h:i A') : '—';
$generatedAt = ($generated_at instanceof \DateTimeInterface) ? $generated_at->format('d M Y, h:i A') : date('d M Y, h:i A');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;font-family:system-ui,-apple-system,'Segoe UI',Roboto,Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;background:#f8fafc;padding:24px 0;">
        <tr>
            <td align="center" style="padding:0 12px;">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 8px 24px rgba(2,6,23,0.06);">
                    <!-- Content -->
                    <tr>
                        <td style="padding:28px 24px;color:#1e293b;">
                            <h1 style="margin:0;font-size:18px;font-weight:600;color:#0f172a;">
                                Hello Admin,
                            </h1>

                            <p style="margin:14px 0 0 0;font-size:16px;line-height:1.7;color:#475569;">
                                Your scheduled report from <?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?> is now ready.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:24px;width:100%;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
                                <tr style="background:#f8fafc;">
                                    <td style="padding:12px 14px;font-size:14px;color:#64748b;width:38%;">Report</td>
                                    <td style="padding:12px 14px;font-size:14px;font-weight:600;color:#0f172a;">
                                        <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 14px;font-size:14px;color:#64748b;">Scheduled for</td>
                                    <td style="padding:12px 14px;font-size:14px;font-weight:600;color:#0f172a;">
                                        <?= htmlspecialchars($scheduledFor, ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                </tr>
                                <tr style="background:#f8fafc;">
                                    <td style="padding:12px 14px;font-size:14px;color:#64748b;">Generated at</td>
                                    <td style="padding:12px 14px;font-size:14px;font-weight:600;color:#0f172a;">
                                        <?= htmlspecialchars($generatedAt, ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:16px 0 0 0;font-size:14px;line-height:1.6;color:#475569;">
                                The full report is attached to this email. You can also access it from your dashboard by downloading the report there.<br><br>
                                Please note that daily report data is not saved after the day has passed, so be sure to download it if you need to keep a copy, or configure this scheduled report to run at the end of the day before 11:59 PM to receive the reports without losing data.<br><br>
                                If you have any questions or would like to adjust your reporting preferences, you can update your settings in the app under <b>System Settings > Automated Reports</b>.<br><br>
                                Thank you for using <?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?>.
                            </p>

                            <div style="margin-top:20px;border:1px solid #e2e8f0;border-radius:10px;padding:12px 14px;background:#eff6ff;color:#1e40af;font-size:14px;line-height:1.6;">
                                <?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>
                            </div>

                            <?php if ($hasData): ?>
                                <p style="margin:16px 0 0 0;font-size:14px;line-height:1.6;color:#475569;">
                                    The CSV export for this reporting cycle is attached to this email for your review.
                                </p>
                            <?php else: ?>
                                <p style="margin:16px 0 0 0;font-size:14px;line-height:1.6;color:#b45309;">
                                    No report data was found for this reporting cycle.
                                </p>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;text-align:center;">
                            <p style="margin:0;font-size:12px;color:#64748b;">
                                This is an automated notification from <?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?>.
                            </p>
                            <p style="margin:6px 0 0 0;font-size:12px;color:#94a3b8;">
                                Please do not reply directly to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
