<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? $this->title ?></title>
    
    <link rel="icon" type="image/x-icon" href="/assets/brand/passito-icon.png">
    <meta name="author" content="<?= $this->config->get('app.name') ?>">
    <meta name="description" content="Join Passito to create and manage your gatepasses easily. Experience instant approvals with our user-friendly web application.">
    <meta name="keywords" content="gatepass, management system, Passito, online gatepass, instant gatepass, gatepass application, secure gatepass, user-friendly gatepass management">
    <meta name="robots" content="index, follow">
    
    <meta property="og:image" content="/assets/brand/passito-icon.png">
    <meta property="og:title" content="<?= $this->config->get('app.name') ?> - <?= $this->config->get('app.desc') ?>">
    <meta property="og:site_name" content="<?= $this->config->get('app.name') ?>">
    <meta property="og:url" content="<?= $this->config->get('app.host') ?>">
    <meta property="og:type" content="website">
    <meta property="og:description" content="Join Passito for fast and efficient gatepass management. Create an account for instant approvals.">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#ffffff">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $this->config->get('app.name') ?> - <?= $this->config->get('app.desc') ?>">
    <meta name="twitter:description" content="Easily manage your gatepasses with Passito. Sign up today for a seamless experience.">
    <meta name="twitter:image" content="/assets/brand/passito-icon.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= vite_asset('css/style.css') ?>">
</head>


<body class="bg-lightGray font-sans">

    {{contents}}

    <?php
    if ($this->isAuthenticated()): ?>
        <script type="module" src="<?= vite_asset('js/main.js') ?>"></script>
    <?php else: ?>
        <script type="module" src="<?= vite_asset('js/auth.js') ?>"></script>
    <?php endif; ?>

</body>

</html>
