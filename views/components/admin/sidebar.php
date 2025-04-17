<?php

use App\Enum\UserRole; ?>
<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex flex-col w-64 transition-transform duration-300 ease-in-out -translate-x-full bg-white border-r select-none lg:translate-x-0">
    <nav class="flex flex-col h-full bg-white">
        <!-- Brand Section (Fixed) -->
        <div class="flex items-center justify-center p-[20px] bg-white border-b space-x-4 text-gray-700">
            <div class="w-22">
                <img src="<?= $brandLogo ?>" alt="Brand Logo" width="300">
            </div>
        </div>

        <!-- Scrollable Menu (Middle) -->
        <div class="flex-1 px-2 overflow-y-auto scroll-smooth scrollbar scrollbar-hide">
            <!-- Main Menu Category -->
            <!-- <h4 class="px-2 mt-4 text-xs font-semibold text-gray-600 uppercase">Admin Panel</h4> -->
            <ul class="mt-2">
                <!-- Dashboard Item -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.dashboard') ?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.dashboard') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="pr-4 fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Pending requests -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.outpass.pending') ?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.outpass.pending') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center space-x-4">
                                <i class="fas fa-clock"></i>
                                <span>Pending Requests</span>
                            </div>
                            <?php if (count($pendingCount) > 0): ?>
                                <div class="flex items-center justify-center w-5 h-5 text-xs font-medium text-indigo-600 bg-indigo-200 rounded-md">
                                    <?php echo (count($pendingCount) > 9) ? '9+' : count($pendingCount); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                </li>


                <!-- Outpass Records -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.outpass.records') ?>"
                        class="flex items-center px-4 py-3 <?= (str_contains($routeName, 'admin.outpass.records')) ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="pr-4 fas fa-folder-open"></i>
                        <span>Outpass Records</span>
                    </a>
                </li>
                <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
                    <!-- Outpass Settings -->
                    <li class="my-1">
                        <a href="<?= $this->urlFor('admin.outpass.settings') ?>"
                            class="flex items-center px-4 py-3 <?= ($routeName == 'admin.outpass.settings') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                            <i class="pr-4 fas fa-sliders-h"></i>
                            <span>Outpass Settings</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <h4 class="px-2 mt-3 text-xs font-semibold text-gray-600 uppercase">Management</h4>
            <ul class="mt-2">
                <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
                    <!-- Wardens -->
                    <li class="my-1">
                        <a href="<?= $this->urlFor('admin.manage.wardens') ?>"
                            class="flex items-center px-4 py-3 <?= ($routeName == 'admin.manage.wardens') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                            <i class="pr-3 fas fa-user-shield"></i>
                            <span>Manage Wardens</span>
                        </a>
                    </li>

                    <!-- Facilities -->
                    <li class="my-1">
                        <a href="<?= $this->urlFor('admin.manage.facilities') ?>"
                            class="flex items-center px-4 py-3 <?= ($routeName == 'admin.manage.facilities') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                            <i class="pr-4 fas fa-hotel"></i>
                            <span>Manage Facilities</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Students -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.manage.students') ?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.manage.students') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="pr-4 fas fa-user-graduate"></i>
                        <span>Manage Students</span>
                    </a>
                </li>
            </ul>
            <!-- Verifiers -->
            <h4 class="px-2 mt-3 text-xs font-semibold text-gray-600 uppercase">Verifiers</h4>
            <ul class="mt-2">
                <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
                    <li class="my-1">
                        <a href="<?= $this->urlFor('admin.manage.verifiers') ?>"
                            class="flex items-center px-4 py-3 <?= ($routeName == 'admin.manage.verifiers') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                            <i class="pr-4 fas fa-qrcode"></i>
                            <span>Verifier Manager</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Logbook -->
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.manage.logbook') ?>"
                        class="flex items-center px-4 py-3 <?= ($routeName == 'admin.manage.logbook') ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'text-gray-600 hover:bg-gray-50'; ?> rounded-md transition duration-200">
                        <i class="pr-4 fas fa-book"></i>
                        <span>Verifier Logbook</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- User Info Section (Fixed at Bottom) -->
        <div class="p-2 border-t">
            <button class="w-full flex items-center p-2 rounded-md transition focus:outline-none focus:ring-2 focus:ring-indigo-500 <?= ($routeName == 'admin.settings') ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'text-gray-600 hover:bg-gray-100'; ?>"
                onclick="window.location.href='<?= $this->urlFor('admin.settings') ?>';" title="Go to Settings">
                <img src="https://ui-avatars.com/api/?name=<?= $user->getName() ?>&background=c7d2fe&color=3730a3&bold=true" alt="User Avatar" class="w-10 h-10 rounded-md">
                <div class="ml-3 text-left truncate">
                    <h4 class="font-semibold text-gray-800"><?= ucwords($user->getName()) ?></h4>
                    <span class="text-sm text-gray-500"><?= $user->getEmail() ?></span>
                </div>
            </button>
        </div>
    </nav>
</aside>