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
    <meta name="csrf-cookie-name" content="<?= $this->config->get('csrf.cookie.name') ?>">
    <meta name="csrf-header-name" content="<?= $this->config->get('csrf.header') ?>">
    <link rel="stylesheet" href="<?= vite_asset('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="font-sans leading-relaxed bg-lightGray">
    <div class="min-h-screen">
        <header class="sticky top-0 z-30 bg-white border-b">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
                        <?= strtoupper(substr($user->getName(), 0, 1)) ?>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Verifier Console</p>
                        <p class="text-xs text-gray-500"><?= $user->getEmail() ?></p>
                    </div>
                </div>
                <a href="<?= $this->urlFor('auth.logout') ?>" class="inline-flex items-center px-3 py-2 text-sm text-gray-700 transition bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                    <i class="mr-2 fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </header>

        <main class="px-6 py-6">
            {{contents}}
        </main>
    </div>

    <script type="module" src="<?= vite_asset('js/verifier.js') ?>"></script>
</body>
</html>
