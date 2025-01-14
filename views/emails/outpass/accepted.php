<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outpass Approval Notification</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">
    <table style="width: 100%; padding: 0; background-color: #ffffff; max-width: 600px; margin: 20px auto; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Header Section -->
        <tr>
            <td style="background-color: #4f46e5; color: #ffffff; padding: 20px 80px; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;">
                <h1 style="margin: 0; font-size: 24px; font-weight: bold;">Outpass Approved</h1>
                <p style="margin: 10px 0 0; font-size: 14px;">Your outpass request has been approved successfully</p>
            </td>
        </tr>

        <!-- Body Section -->
        <tr>
            <td style="padding: 30px;">
                <p style="margin: 0 0 20px; font-size: 18px; font-weight: bold; color: #374151;">
                    Dear <?= $studentName ?>,
                </p>
                <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #374151;">
                    We’re delighted to inform you that your outpass request has been successfully approved! The approved outpass document is attached to this email for your convenience.
                </p>

                <!-- Outpass Details Summary -->
                <table style="width: 100%; margin-top: 20px; border-collapse: collapse; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden;">
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 10px; font-size: 14px; color: #6b7280; width: 40%;">Outpass ID:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;">#<?= $outpass->getId() ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Approved On:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;"><?= $outpass->getApprovedTime()->format('F d, Y') ?> at <?= $outpass->getApprovedTime()->format('h:i A') ?></td>
                    </tr>
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Destination:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;"><?= $outpass->getDestination() ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Exit Time:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;">
                            <?= $outpass->getFromDate()->format('F d, Y') ?> at <?= $outpass->getFromTime()->format('h:i A') ?>
                        </td>
                    </tr>
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Entry Time:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;">
                            <?= $outpass->getToDate()->format('F d, Y') ?> at <?= $outpass->getToTime()->format('h:i A') ?>
                        </td>
                    </tr>
                </table>

                <p style="margin: 30px 0 20px; font-size: 16px; line-height: 1.6; color: #374151;">
                    <strong>What to Do Next:</strong>
                </p>
                <ul style="margin: 0 0 20px; padding-left: 20px; font-size: 14px; line-height: 1.6; color: #374151;">
                    <li>Download the attached outpass document and review the details for accuracy.</li>
                    <li>Carry a printed or digital copy of the outpass along with your ID card during travel.</li>
                    <li>Ensure you adhere to the timings and conditions mentioned in the outpass.</li>
                    <li>If there are any changes in your plans, promptly inform your hostel warden.</li>
                </ul>

                <p style="margin: 30px 0 0; font-size: 16px; line-height: 1.6; color: #374151;">
                    If you have any questions or need further assistance, feel free to contact the hostel office or our support team.
                </p>
            </td>
        </tr>

        <!-- Footer Section -->
        <tr>
            <td style="background-color: #f9fafb; text-align: center; padding: 20px; font-size: 14px; color: #6b7280;">
                <p style="margin: 0;">Thank you,</p>
                <p style="margin: 5px 0; font-weight: bold; color: #4f46e5;">Passito Team</p>
                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
                <p style="margin: 10px 0 0;">For support, contact us at <a href="mailto:support@passito.com" style="color: #4f46e5; text-decoration: none;">support@passito.com</a></p>
            </td>
        </tr>
    </table>
</body>

</html>