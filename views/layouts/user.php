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
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $this->config->get('app.name') ?> - <?= $this->config->get('app.desc') ?>">
    <meta name="twitter:description" content="Easily manage your gatepasses with Passito. Sign up today for a seamless experience.">
    <meta name="twitter:image" content="/assets/brand/passito-icon.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= vite_asset('css/style.css') ?>">
</head>


<body class="bg-lightGray font-sans">

    {{contents}}

    <script type="module" src="<?= vite_asset('js/main.js') ?>"></script>

</body>

</html>
