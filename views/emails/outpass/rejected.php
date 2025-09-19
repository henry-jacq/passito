<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outpass Rejected</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6;">
    <table style="width: 100%; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin: 20px auto; max-width: 600px;">
        <!-- Header Section -->
        <tr>
            <td style="background: linear-gradient(to right, #dc2626, #b91c1c); color: #ffffff; text-align: center; padding: 30px;">
                <h1 style="margin: 0; font-size: 24px; font-weight: bold;">Outpass Rejected</h1>
                <p style="margin: 10px 0 0; font-size: 14px; color: #fef2f2;">Unfortunately, we couldn't approve your request</p>
            </td>
        </tr>

        <!-- Body Section -->
        <tr>
            <td style="padding: 30px;">
                <p style="margin: 0 0 20px; font-size: 18px; font-weight: bold; color: #374151;">
                    Dear <?= $outpass->getStudent()->getUser()->getName() ?>,
                </p>
                <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #374151;">
                    We regret to inform you that your outpass request has been rejected. Here are the details of your request:
                </p>

                <!-- Details Section -->
                <table style="width: 100%; margin-top: 20px; border-collapse: collapse; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden;">
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Outpass ID:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;">#<?= $outpass->getId() ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Exit Time:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;">
                            <?= $outpass->getFromDate()?->format('F d, Y') ?? 'N/A' ?>
                            at <?= $outpass->getFromTime()?->format('h:i A') ?? '' ?>
                        </td>
                    </tr>
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Entry Time:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;">
                            <?= $outpass->getToDate()?->format('F d, Y') ?? 'N/A' ?>
                            at <?= $outpass->getToTime()?->format('h:i A') ?? '' ?>
                        </td>
                    </tr>
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Destination:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;"><?= $outpass->getDestination() ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Reason for Rejection:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;"> <?= !empty($outpass->getRemarks()) ? $outpass->getRemarks() : 'None' ?></td>
                    </tr>
                </table>

                <p style="margin: 20px 0 0; font-size: 16px; line-height: 1.6; color: #374151;">
                    If you believe this decision was made in error or need further clarification, please contact the admin or warden.
                </p>

                <!-- Call-to-Action -->
                <div style="text-align: center; margin-top: 30px;">
                    <a href="mailto:support@passito.com" style="background-color: #dc2626; color: #ffffff; text-decoration: none; font-weight: bold; padding: 12px 24px; border-radius: 6px; display: inline-block;">
                        Contact Support
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer Section -->
        <tr>
            <td style="background-color: #f9fafb; text-align: center; padding: 25px; font-size: 14px; color: #6b7280;">
                <p style="margin: 0;">Thank you for your understanding,</p>
                <p style="margin: 5px 0; font-weight: bold; color: #dc2626;">Passito Team</p>
                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
                <p style="margin: 0;">Need help? Email us at <a href="mailto:support@passito.com" style="color: #dc2626; text-decoration: none;">support@passito.com</a></p>
            </td>
        </tr>
    </table>
</body>

</html>