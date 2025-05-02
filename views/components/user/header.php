<header class="text-white shadow-md bg-gradient-to-r from-blue-600 to-blue-800">
    <div class="container flex items-center justify-between px-6 py-5 mx-auto">
        <!-- Left: Brand Logo -->
        <div class="flex items-center">
            <div class="flex items-center justify-center px-3">
                <svg class="w-8 h-8" viewBox="0 0 92.105 92.1">
                    <g transform="translate(-2.76 -2.77) scale(0.3254)" fill="currentColor">
                        <!-- Icon Content -->
                        <g xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M202.9,120.4L156,82.2c-3.5-2.9-8.6-2.9-12.1,0L97,120.5c-1.9,1.5-1.9,4.5,0,6l14.7,12c2.1,1.7,5.1,1.7,7.3,0l30.9-25.2l30.9,25.3c2.1,1.7,5.1,1.7,7.3,0l14.7-12C204.8,124.9,204.8,122,202.9,120.4z">
                            </path>
                            <path
                                d="M249.9,158.9l40.2-32.7c1.9-1.5,1.9-4.5,0-6L156,10.7c-3.5-2.9-8.6-2.9-12.1,0L9.9,120c-1.9,1.5-1.9,4.5,0,6l40.2,32.8L9.9,191.6c-1.9,1.5-1.9,4.5,0,6L124,290.7c2.5,2,6.2,0.3,6.2-3V193c0-2.9-1.3-5.7-3.6-7.5L94,158.9L72,141l-19-15.7c-1.4-1.1-1.4-3.3,0-4.5l93.4-76.2c2.1-1.7,5.1-1.7,7.3,0l93.4,76.2c1.4,1.1,1.4,3.3,0,4.5L228,140.9l-22.1,18l-32.6,26.6c-2.3,1.8-3.6,4.6-3.6,7.5v94.7c0,3.2,3.8,5,6.2,3L290,197.6c1.9-1.5,1.9-4.5,0-6L249.9,158.9zM73.9,178.3l26.7,21.8c1.3,1.1,2.2,2.8,2.2,4.5v28.8c0,1.6-1.9,2.6-3.1,1.5L53,196.8c-1.4-1.1-1.4-3.3,0-4.5l17.3-14.1C71.3,177.3,72.8,177.3,73.9,178.3zM197.3,233.4v-28.8c0-1.7,0.8-3.4,2.2-4.5l26.7-21.8c1-0.9,2.5-0.9,3.6,0l17.3,14.1c1.4,1.1,1.4,3.3,0,4.5L200.4,235C199.1,235.9,197.3,235.1,197.3,233.4z">
                            </path>
                        </g>
                    </g>
                </svg>
            </div>
            <h1 class="hidden text-2xl font-medium tracking-tight font-heading md:block">Passito</h1>
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
    <div id="mobileMenu" class="hidden bg-gradient-to-r from-blue-600 to-blue-800 md:hidden">
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