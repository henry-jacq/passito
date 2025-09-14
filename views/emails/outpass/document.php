<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outpass Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .org-header {
            margin-bottom: 30px;
        }

        .org-name {
            font-size: x-large;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .address-line {
            font-size: small;
            text-align: center;
            padding-bottom: 20px;
        }

        .form-title {
            text-transform: uppercase;
            text-align: center;
            font-weight: bold;
        }

        .org-header img {
            width: 130px;
            height: 130px;
            float: left;
            margin-right: 20px;
        }

        .text-container {
            overflow: hidden;
            padding: 5px;
            margin-left: -120px;
        }

        .text-container p {
            margin: 0;
        }

        .fw-bold {
            font-weight: bold;
        }

        .sections-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 120px;
        }

        .student-section,
        .outpass-section {
            display: inline-block;
            vertical-align: top;
            width: 48%;
        }

        .signature-section img {
            height: 80px;
        }

        .instruction-header {
            text-transform: uppercase;
            font-weight: bolder;
        }

        .instructions-section {
            margin-bottom: 60px;
        }

        .footer-section {
            text-align: center;
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 120px;
            line-height: 1.5;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <?php $student = $outpass->getStudent(); ?>

    <div class="org-header">
        <img src="<?= $this->storage->getFullPath("qr_codes/{$qrCodeFile}") ?>" alt="<?= $this->storage->getFullPath("qr_codes/{$qrCodeFile}") ?>">
        <div class="text-container">
            <h3 class="org-name"><?= $student->getProgram()->getProvidedBy()->getName() ?></h3>
            <p class="address-line"><?= $student->getProgram()->getProvidedBy()->getAddress() ?></p>
            <p class="form-title">Hostel - Permission Form</p>
        </div>
    </div>
    <hr>
    <p><strong>No.: #<?= $outpass->getId() ?></strong></p>
    </strong></p>
    <div class="sections-container">
        <div class="student-section">
            <p><strong>Exit Time:</strong> <?= $outpass->getFromDate()->format('d-m-Y') . ' ' . $outpass->getFromTime()->format('h:iA') ?></p>
            <p><strong>Name:</strong> <?= $student->getUser()->getName() ?></p>
            <p><strong>Branch:</strong> <?= $student->getProgram()->getCourseName() ?></p>
            <p><strong>Hostel:</strong> <?= $student->getHostel()->getName() ?></p>
            <p><strong>Reason:</strong> <?= $outpass->getReason() ?></p>
            <p><strong>Student No:</strong> <?= $student->getUser()->getContactNo() ?></p>
            <p><strong>Approved On:</strong> <?= $outpass->getApprovedTime()->format('d-m-Y h:iA') ?></p>
        </div>
        <div class="outpass-section">
            <p><strong>Entry Time:</strong> <?= $outpass->getToDate()->format('d-m-Y') . ' ' . $outpass->getToTime()->format('h:iA') ?></p>
            <p><strong>ID No:</strong> <?= $student->getDigitalId() ?></p>
            <p><strong>Year:</strong> <?= formatStudentYear($student->getYear()) ?> Year</p>
            <p><strong>Room:</strong> <?= $student->getRoomNo() ?></p>
            <p><strong>Destination:</strong> <?= $outpass->getDestination() ?></p>
            <p><strong>Parent No:</strong> <?= $student->getParentNo() ?></p>
            <p><strong>Approved By:</strong> <?= $outpass->getApprovedBy()->getName() ?></p>
        </div>
        <br><br>
    </div>
    <div class="instructions-section">
        <p class="instruction-header">Instructions:</p>
        <ul>
            <li>Ensure that you carry your ID card during the outpass period.</li>
            <li>Return to the hostel before the end time specified in the outpass.</li>
            <li>Any changes to the outpass schedule must be done advance.</li>
            <li>Keep this outpass document with you as it is required for verification.</li>
            <li>Follow all hostel rules and regulations during the outpass period.</li>
            <li>For any queries, contact the hostel warden.</li>
        </ul>
    </div>
    <div class="footer-section">
        <p>Issued on <?= date('d-m-Y h:iA') ?></p>
        <p>This is a system-generated document and does not require a signature.</p>
    </div>

</body>

</html>