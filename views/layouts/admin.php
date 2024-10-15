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

    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php $this->getComponent('admin/sidebar', [
            'routeName' => $routeName
        ]) ?>
        
        <!-- Contents Area -->
        <div class="flex-1 flex flex-col lg:ml-64">
            <!-- Admin header -->
            <?php $this->getComponent('admin/header', [
                'routeName' => $routeName
            ]) ?>
            
            {{contents}}

        </div>
    </div>

    <script type="module" src="<?= vite_asset('js/main.js') ?>"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const notificationButton = document.getElementById('notificationButton');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const header = document.querySelector('header');

        // Toggle sidebar visibility on smaller screens
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            adjustHeaderWidth();
        });

        // Adjust header width and margin-left based on the window size
        function adjustHeaderWidth() {
            if (window.innerWidth >= 1024) {
                header.style.marginLeft = '16rem';  // Matches lg:ml-64 (64 * 0.25rem = 16rem)
                header.style.width = `calc(100% - 16rem)`;
            } else {
                header.style.marginLeft = '0';
                header.style.width = '100%';
            }
        }

        // Ensure the sidebar is visible on larger screens (if resized)
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
            adjustHeaderWidth();
        });

        // Call adjustHeaderWidth initially to set the correct state on page load
        adjustHeaderWidth();

        // Toggle notification dropdown
        notificationButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent closing the dropdown when clicking the button
            notificationDropdown.classList.toggle('hidden');
            notificationDropdown.classList.toggle('scale-95');
            notificationDropdown.classList.toggle('opacity-0');
        });

        // Close the dropdown when clicking outside
        window.addEventListener('click', (event) => {
            if (!notificationDropdown.classList.contains('hidden') &&
                !notificationDropdown.contains(event.target) &&
                !notificationButton.contains(event.target)) {
                notificationDropdown.classList.add('hidden');
                notificationDropdown.classList.add('scale-95');
                notificationDropdown.classList.add('opacity-0');
            }
        });
    </script>

</body>

</html>
