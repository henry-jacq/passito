<?php use App\Enum\UserRole; ?>
<aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white border-r flex flex-col lg:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out z-40 select-none">
    <nav class="flex flex-col h-full bg-white">
        <!-- Brand Section (Fixed) -->
        <div class="flex items-center justify-center p-[20px] bg-white border-b space-x-4 text-gray-700">
            <svg class="w-8 h-8" viewBox="0 0 92.105 92.1">
                <g transform="translate(-2.76 -2.77) scale(0.3254)" fill="currentColor">
                    <!-- Icon Content -->
                    <g xmlns="http://www.w3.org/2000/svg">
                    <path d="M202.9,120.4L156,82.2c-3.5-2.9-8.6-2.9-12.1,0L97,120.5c-1.9,1.5-1.9,4.5,0,6l14.7,12c2.1,1.7,5.1,1.7,7.3,0l30.9-25.2l30.9,25.3c2.1,1.7,5.1,1.7,7.3,0l14.7-12C204.8,124.9,204.8,122,202.9,120.4z"></path>
                    <path d="M249.9,158.9l40.2-32.7c1.9-1.5,1.9-4.5,0-6L156,10.7c-3.5-2.9-8.6-2.9-12.1,0L9.9,120c-1.9,1.5-1.9,4.5,0,6l40.2,32.8L9.9,191.6c-1.9,1.5-1.9,4.5,0,6L124,290.7c2.5,2,6.2,0.3,6.2-3V193c0-2.9-1.3-5.7-3.6-7.5L94,158.9L72,141l-19-15.7c-1.4-1.1-1.4-3.3,0-4.5l93.4-76.2c2.1-1.7,5.1-1.7,7.3,0l93.4,76.2c1.4,1.1,1.4,3.3,0,4.5L228,140.9l-22.1,18l-32.6,26.6c-2.3,1.8-3.6,4.6-3.6,7.5v94.7c0,3.2,3.8,5,6.2,3L290,197.6c1.9-1.5,1.9-4.5,0-6L249.9,158.9zM73.9,178.3l26.7,21.8c1.3,1.1,2.2,2.8,2.2,4.5v28.8c0,1.6-1.9,2.6-3.1,1.5L53,196.8c-1.4-1.1-1.4-3.3,0-4.5l17.3-14.1C71.3,177.3,72.8,177.3,73.9,178.3zM197.3,233.4v-28.8c0-1.7,0.8-3.4,2.2-4.5l26.7-21.8c1-0.9,2.5-0.9,3.6,0l17.3,14.1c1.4,1.1,1.4,3.3,0,4.5L200.4,235C199.1,235.9,197.3,235.1,197.3,233.4z"></path>
                </g>
            </svg>
            <p class="font-heading font-normal text-2xl">Passito</p>
        </div>

        <!-- Scrollable Menu (Middle) -->
        <div class="flex-1 overflow-y-auto px-3">
            <!-- Main Menu Category -->
            <h4 class="text-gray-600 px-2 mt-4 font-semibold uppercase text-xs">Admin Panel</h4>
            <ul class="mt-2">
                <!-- Dashboard Item -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.dashboard') ?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.dashboard') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="fas fa-tachometer-alt pr-4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Pending requests -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.outpass.pending') ?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.outpass.pending') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="fas fa-clock pr-4"></i>
                        <span>Pending Requests</span>
                    </a>
                </li>

                <!-- Outpass Records -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.outpass.records') ?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.outpass.records') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="fas fa-folder-open pr-4"></i>
                        <span>Outpass Records</span>
                    </a>
                </li>              
            </ul>
            <h4 class="text-gray-600 px-2 mt-4 font-semibold uppercase text-xs">Management</h4>
            <ul class="mt-2">
                <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
                <!-- Wardens -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.manage.wardens')?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.manage.wardens') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="fas fa-user-shield pr-3"></i>
                        <span>Manage Wardens</span>
                    </a>
                </li>

                <!-- Facilities -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.manage.facilities')?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.manage.facilities') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="fas fa-hotel pr-4"></i>
                        <span>Manage Facilities</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Students -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.manage.students')?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.manage.students') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="fas fa-user-graduate pr-4"></i>
                        <span>Manage Students</span>
                    </a>
                </li>
            </ul>
            <!-- Verifiers -->
            <h4 class="text-gray-600 px-2 mt-4 font-semibold uppercase text-xs">Verifiers</h4>
            <ul class="mt-2">
                <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.manage.verifiers')?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.manage.verifiers') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="fas fa-qrcode pr-4"></i>
                        <span>Verifier Manager</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Logbook -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.manage.logbook')?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.manage.logbook') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="fas fa-book pr-4"></i>
                        <span>Verifier Logbook</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- User Info Section (Fixed at Bottom) -->
        <div class="border-t p-2">
            <button class="w-full flex items-center p-2 rounded-md transition focus:outline-none focus:ring-2 focus:ring-indigo-500 <?= ($routeName == 'admin.settings') ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'text-gray-600 hover:bg-gray-100'; ?>"
                onclick="window.location.href='<?= $this->urlFor('admin.settings') ?>';" title="Go to Settings">
                <img src="https://ui-avatars.com/api/?name=<?= $user->getName() ?>&background=c7d2fe&color=3730a3&bold=true" alt="User Avatar" class="w-10 h-10 rounded-md">
                <div class="ml-3 truncate text-left">
                    <h4 class="font-semibold text-gray-800"><?= ucwords($user->getName()) ?></h4>
                    <span class="text-sm text-gray-500"><?= $user->getEmail() ?></span>
                </div>
            </button>
        </div>
    </nav>
</aside>
