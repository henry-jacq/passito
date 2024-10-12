<!DOCTYPE html>
<html lang="en">
<head>
    <meta data-n-head="1" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? $this->title ?> · <?= $this->appName ?></title>
    <!-- <link rel="icon" type="image/x-icon" href="/assets/brand/photogram-icon.png"> -->
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
    <!-- <meta name="twitter:image" content="/assets/screenshot-2.png"> -->
    <link rel="shortcut icon" href="/assets/brand/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= vite_asset('css/style.css') ?>">
</head>

<body class="bg-lightGray font-sans">

    {{contents}}

    <script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"></script>
    <script src="https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.min.js"></script>
    <script type="module" src="<?= vite_asset('js/main.js') ?>"></script>

</body>

</html>
