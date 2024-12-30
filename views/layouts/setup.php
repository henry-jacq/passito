<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? $this->title ?></title>
    <meta name="robots" content="index, follow">
    <meta property="og:image" content="/assets/brand/passito-icon.png">
    <meta property="og:title" content="<?= $this->config->get('app.name') ?> - <?= $this->config->get('app.desc') ?>">
    <meta property="og:site_name" content="<?= $this->config->get('app.name') ?>">
    <meta property="og:url" content="<?= $this->config->get('app.host') ?>">
    <meta property="og:type" content="website">
    <meta property="og:description" content="Join Passito for fast and efficient gatepass management. Create an account for instant approvals.">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $this->config->get('app.name') ?> - <?= $this->config->get('app.desc') ?>">
    <meta name="twitter:description" content="Easily manage your gatepasses with Passito. Sign up today for a seamless experience.">
    <meta name="twitter:image" content="/assets/brand/passito-icon.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= vite_asset('css/style.css') ?>">
    <style>
               /* Keyframes for animations */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-display leading-relaxed h-screen w-screen flex items-center justify-center overflow-hidden">

    {{contents}}

</body>
</html>
