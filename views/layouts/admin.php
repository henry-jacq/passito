<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->title ?></title>
    
    <link rel="icon" type="image/x-icon" href="/assets/brand/passito-icon.png">
    <meta name="author" content="<?= $this->config->get('app.name') ?>">
    <meta name="theme-color" content="#ffffff">
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
    <meta name="csrf-cookie-name" content="<?= $this->config->get('csrf.cookie.name') ?>">
    <meta name="csrf-header-name" content="<?= $this->config->get('csrf.header') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= vite_asset('css/style.css') ?>">
</head>


<body class="font-sans leading-relaxed bg-lightGray">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php $this->getComponent('admin/sidebar', get_defined_vars()) ?>
        
        <!-- Contents Area -->
        <div class="flex flex-col flex-1 lg:ml-64">
            <!-- Admin header -->
            <?php $this->getComponent('admin/header', get_defined_vars()) ?>
            
            {{contents}}

        </div>
    </div>

    <div id="preloader" class="fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300 bg-white opacity-0 pointer-events-none">
    <div class="w-10 h-10 border-4 border-blue-600 rounded-full border-t-transparent animate-spin"></div>
    </div>

    <!-- Modal Stub -->
    <?php $this->getComponent('admin/modal', get_defined_vars()) ?>

    <script type="module" src="<?= vite_asset('js/admin.js') ?>"></script>
    <?php 
    use App\Enum\UserRole;
    if ($user->getRole() === UserRole::SUPER_ADMIN) : ?>
        <script type="module" src="<?= vite_asset('js/superAdmin.js') ?>"></script>
    <?php endif; ?>

</body>

</html>
