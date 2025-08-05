<header class="text-white bg-blue-900 shadow-lg">
    <div class="flex items-center justify-between px-12 py-6 mx-auto">
        <!-- Left: Brand Logo -->
        <div class="flex items-center">
            <div class="flex items-center justify-center px-3">
                <div class="w-22">
                    <img src="<?= $brandLogo ?>" alt="Brand Logo" width="300" style="filter: invert(1) brightness(3);">
                </div>
            </div>
        </div>

        <!-- Center: Navigation -->
        <nav class="hidden space-x-6 md:flex">
            <a href="<?= $this->urlFor('student.dashboard') ?>" class="relative text-lg group">
                Dashboard
                <span class="absolute left-0 bottom-0 w-full h-0.5 bg-white <?= $routeName == 'student.dashboard' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' ?> transition-transform origin-left"></span>
            </a>
            <a href="<?= $this->urlFor('student.outpass.request') ?>" class="relative text-lg group">
                Request Outpass
                <span class="absolute left-0 bottom-0 w-full h-0.5 bg-white <?= $routeName == 'student.outpass.request' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' ?> transition-transform origin-left"></span>
            </a>
            <a href="<?= $this->urlFor('student.outpass.status') ?>" class="relative text-lg group">
                Status
                <span class="absolute left-0 bottom-0 w-full h-0.5 bg-white <?= $routeName == 'student.outpass.status' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' ?> transition-transform origin-left"></span>
            </a>
            <a href="<?= $this->urlFor('student.outpass.history') ?>" class="relative text-lg group">
                History
                <span class="absolute left-0 bottom-0 w-full h-0.5 bg-white <?= $routeName == 'student.outpass.history' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' ?> transition-transform origin-left"></span>
            </a>
        </nav>

        <!-- Right: Notifications and Profile -->
        <div class="flex items-center space-x-8">
            <!-- Notification Icon -->
            <button aria-label="Notifications" class="relative focus:outline-none">
                <i class="text-2xl text-blue-300 fa-solid fa-bell"></i>
                <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full"></span>
            </button>

            <!-- Profile Dropdown -->
            <div class="relative hidden md:block">
                <button id="profileMenuButton" class="focus:outline-none">
                    <img src="https://avatars.githubusercontent.com/u/89177279" alt="Profile"
                        class="w-10 h-10 rounded-full shadow-sm">
                </button>
                <div id="profileMenu"
                    class="absolute right-0 hidden w-40 mt-2 text-gray-700 transition-opacity bg-white rounded-md shadow-lg">
                    <a href="<?= $this->urlFor('student.profile') ?>" class="block px-4 py-2 hover:bg-gray-100 hover:rounded-md">My Profile</a>
                    <a href="<?= $this->urlFor('auth.logout') ?>" class="block px-4 py-2 hover:bg-gray-100 hover:rounded-md">Logout</a>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button id="mobileMenuToggle" class="block text-2xl text-white md:hidden focus:outline-none">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobileMenu" class="hidden bg-blue-900 md:hidden">
        <nav class="flex flex-col p-4 space-y-3">
            <a href="<?= $this->urlFor('student.dashboard') ?>" class="text-lg transition hover:text-blue-200">Dashboard</a>
            <a href="<?= $this->urlFor('student.outpass.request') ?>" class="text-lg transition hover:text-blue-200">Request Outpass</a>
            <a href="<?= $this->urlFor('student.outpass.status') ?>" class="text-lg transition hover:text-blue-200">Status</a>
            <a href="<?= $this->urlFor('student.outpass.history') ?>" class="text-lg transition hover:text-blue-200">History</a>
            <a href="<?= $this->urlFor('student.profile') ?>" class="text-lg transition hover:text-blue-200">My Profile</a>
            <a href="<?= $this->urlFor('auth.logout') ?>" class="text-lg transition hover:text-blue-200">Logout</a>
        </nav>
    </div>
</header>