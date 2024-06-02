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
            margin-bottom: 40px;
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
        }

        .student-section,
        .outpass-section {
            display: inline-block;
            vertical-align: top;
            width: 48%;
        }

        .purpose {
            margin-bottom: 100px;
        }

        .signature-section img {
            height: 80px;
        }

        .instruction-header {
            text-transform: uppercase;
            font-weight: bolder;
        }

        .signature-section {
            display: flex;
            justify-content: center;
            float: right;
            margin-top: 20px;
            margin-right: 30px;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <div class="org-header">
        <img src="./qrcode.png" alt="qrcode">
        <div class="text-container">
            <h3 class="org-name">SSN College of Engineering</h3>
            <p class="address-line">Rajiv Gandhi Salai (OMR), Kalavakkam - 603110</p>
            <p class="form-title">Hostel - Permission Form</p>
        </div>
    </div>
    <hr>
    <p><strong>ID: #354784</strong></p>
    </strong></p>
    <div class="sections-container">
        <div class="student-section">
            <p><strong>Name:</strong> Henry</p>
            <p><strong>Digital ID:</strong> 2210231</p>
            <p><strong>Room:</strong> D-218</p>
            <p><strong>Year:</strong> Second year</p>
            <p><strong>Branch:</strong> Information Technology (IT)</p>
        </div>
        <div class="outpass-section">
            <p><strong>From:</strong> 08-05-2024 04:30PM</p>
            <p><strong>To:</strong> 31-05-2024 08:00AM</p>
            <p><strong>Student No:</strong> +91 9578964262</p>
            <p><strong>Parent No:</strong> +91 7469812434</p>
            <p><strong>Date of Approval:</strong> 07/05/2024</p>
        </div>
        <br><br>
        <strong>PURPOSE</strong>
        <p class="purpose">To visit my hometown.</p>
    </div>
    <div class="instructions-section">
        <p class="instruction-header">Instructions:</p>
        <ul>
            <li>Ensure that you carry your college ID card at all times during the outpass period.</li>
            <li>Return to the hostel before the end time specified in the outpass.</li>
            <li>Inform the warden immediately upon your return.</li>
            <li>Keep this outpass document with you as it might be required for verification.</li>
            <li>Any changes to the outpass schedule must be reported and approved in advance.</li>
            <li>Follow all hostel rules and regulations during the outpass period.</li>
        </ul>
    </div>
    <div class="signature-section">
        <img src="./sign.png" alt="sign">
        <p>Signature of Warden</p>
    </div>
</body>

</html>