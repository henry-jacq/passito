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
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .org-header {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .org-name {
            font-size: 1.5rem;
            margin: 0;
            text-align: center;
            flex-grow: 1;
        }

        .address-line {
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 10px;
        }

        .form-title {
            text-transform: uppercase;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .org-header img {
            width: 100px;
            height: 100px;
            margin-right: 20px;
        }

        .sections-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
        }

        .student-section,
        .outpass-section {
            flex: 1;
        }

        .purpose {
            margin-bottom: 40px;
        }

        .instructions-section {
            margin-top: 20px;
        }

        .instruction-header {
            text-transform: uppercase;
            font-weight: bolder;
            margin-bottom: 10px;
        }

        ul {
            padding-left: 20px;
        }

        ul li {
            margin-bottom: 8px;
        }

        .signature-section {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .signature-section img {
            height: 60px;
            margin-right: 10px;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="org-header">
            <img src="./qrcode.png" alt="QR Code">
            <div>
                <h3 class="org-name">SSN College of Engineering</h3>
                <p class="address-line">Rajiv Gandhi Salai (OMR), Kalavakkam - 603110</p>
                <p class="form-title">Hostel - Permission Form</p>
            </div>
        </div>

        <hr>

        <p><strong>ID:</strong> #354784</p>

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
        </div>

        <p><strong>Purpose:</strong></p>
        <p class="purpose">To visit my hometown.</p>

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
            <img src="./sign.png" alt="Signature">
            <p>Signature of Warden</p>
        </div>
    </div>
</body>

</html>