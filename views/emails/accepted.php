<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outpass Approval Notification</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">
    <table style="width: 100%; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Body Section -->
        <tr>
            <td style="padding: 30px;">
                <p style="margin: 0 0 20px; font-size: large; line-height: 1.6; color: #374151;">
                    Dear <strong style="color: #4f46e5;"><?= $studentName ?></strong>,
                </p>
                <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #374151;">
                    We are pleased to inform you that your outpass request has been approved successfully. For your convenience, the approved outpass document has been attached to this email.
                </p>

                <!-- Outpass Details Summary -->
                <table style="width: 100%; margin-top: 20px; border-collapse: collapse; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden;">
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 10px; font-size: 14px; color: #6b7280; width: 40%;">Outpass ID:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;">#<?= $outpass->getId() ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Approval Date:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;"><?= $outpass->getApprovedTime()->format('F d, Y') ?></td>
                    </tr>
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Destination:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;"><?= $outpass->getDestination() ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Departure Date:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;"><?= $outpass->getFromDate()->format('F d, Y') ?></td>
                    </tr>
                    <tr style="background-color: #f9fafb;">
                        <td style="padding: 10px; font-size: 14px; color: #6b7280;">Return Date:</td>
                        <td style="padding: 10px; font-size: 14px; color: #111827;"><?= $outpass->getToDate()->format('F d, Y') ?></td>
                    </tr>
                </table>

                <p style="margin: 30px 0 20px; font-size: 16px; line-height: 1.6; color: #374151;">
                    <strong>What to Do Next:</strong>
                </p>
                <ul style="margin: 0 0 20px; padding-left: 20px; font-size: 14px; line-height: 1.6; color: #374151;">
                    <li>Download and review the attached outpass document for accuracy.</li>
                    <li>Ensure you carry a printed or digital copy of the outpass along with your ID card during travel.</li>
                    <li>Inform the hostel office immediately if you notice any discrepancies in the details.</li>
                </ul>

                <p style="margin: 30px 0 0; font-size: 16px; line-height: 1.6; color: #374151;">
                    If you have any questions or require further assistance, feel free to contact the hostel office or our support team.
                </p>
            </td>
        </tr>

        <!-- Footer Section -->
        <tr>
            <td style="background-color: #f9fafb; text-align: center; padding: 25px; font-size: 14px; color: #6b7280;">
                <p style="margin: 0;">Thank you,</p>
                <p style="margin: 5px 0; font-weight: bold; color: #4f46e5;">Passito Team</p>
                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
                <p style="margin: 0;">For support, contact us at <a href="mailto:support@passito.com" style="color: #4f46e5; text-decoration: none;">support@passito.com</a></p>
            </td>
        </tr>
    </table>
</body>

</html>
