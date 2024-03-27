<head>
    <meta data-n-head="1" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->title ?> · <?= $this->appName ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/brand/photogram-icon.png">
    <meta name="author" content="Henry">
    <meta property="og:image" content="/assets/brand/photogram-icon.png">
    <meta property="site_name" content="<?= $this->appName ?>">
    <meta property="og:title" content="<?= $this->appName ?> · <?= $this->appDesc ?>">
    <meta property="og:site_name" content="http://passito.local">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/assets/brand/photogram-icon.png">
    <meta property="og:image:alt" content="<?= $this->appName ?> · Created by Henry">
    <meta property="description" content="Create an account or log in to <?= $this->appName ?>. Get instant gatepass with no hesitation.">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $this->appName ?>">
    <meta name="twitter:description" content="<?= $this->appName ?> is an easy-to-use web app for applying gatepass instantly.">
    <meta name="twitter:image" content="/assets/screenshot-2.png">
    <link rel="shortcut icon" href="/assets/brand/favicon.ico">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="/css/app.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            overflow: hidden;
        }

        .sidebar {
            position: fixed;
            top: 12px;
            left: -280px;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
            width: 280px;
            transition: left 0.5s ease;
        }

        .content {
            padding: 20px;
            overflow-y: auto;
            max-height: calc(100vh - 59px);
        }

        .content-container {
            flex-grow: 1;
            overflow-y: auto;
            margin-left: 0;
            transition: margin-left 0.5s ease;
        }

        @media (min-width: 768px) {
            .sidebar {
                left: 0;
            }

            .content-container {
                margin-left: 280px;
                transition: margin-left 0.5s ease;
            }
        }
    </style>
</head>