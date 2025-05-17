<?php

use App\Enum\UserRole; ?>
<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex flex-col w-64 transition-transform duration-300 ease-in-out -translate-x-full bg-white border-r select-none lg:translate-x-0">
    <nav class="flex flex-col h-full bg-white">
        <div class="flex items-center justify-center p-[20px] bg-white border-b space-x-4 text-gray-700">
            <div class="w-22">
                <img src="<?= $brandLogo ?>" alt="Brand Logo" width="300">
            </div>
        </div>

        <div class="flex-1 px-2 overflow-y-auto scroll-smooth">
            <h4 class="px-2 mt-4 text-xs font-semibold text-gray-600 uppercase">Main Navigation</h4>
            <ul class="mt-2">
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.dashboard') ?>"
                        class="flex items-center px-4 py-2.5 <?= ($routeName == 'admin.dashboard') ? 'bg-blue-500/20 text-blue-800 hover:bg-blue-600/20 border-blue-800/80' : 'text-gray-600 hover:bg-gray-50 border-transparent'; ?> transition duration-200 rounded-md border-l-4">
                        <i class="pr-4 fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="my-1">
                    <?php $hostelParam = UserRole::isAdmin($user->getRole()->value) ? ['hostel' => 'default'] : []; ?>
                    <a href="<?= $this->urlFor('admin.outpass.pending', [], $hostelParam) ?>"
                        class="flex items-center px-4 py-2.5 <?= ($routeName == 'admin.outpass.pending') ? 'bg-blue-500/20 text-blue-800 hover:bg-blue-600/20 border-blue-800/80' : 'text-gray-600 hover:bg-gray-50 border-transparent'; ?> transition duration-200 rounded-md border-l-4">
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center space-x-5">
                                <i class="fas fa-clock"></i>
                                <span>Pending</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.outpass.records') ?>"
                        class="flex items-center px-4 py-2.5 <?= ($routeName == 'admin.outpass.records') ? 'bg-blue-500/20 text-blue-800 hover:bg-blue-600/20 border-blue-800/80' : 'text-gray-600 hover:bg-gray-50 border-transparent'; ?> transition duration-200 rounded-md border-l-4">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-folder-open"></i>
                            <span>Records</span>
                        </div>
                    </a>
                </li>
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.manage.logbook') ?>"
                        class="flex items-center px-4 py-2.5 <?= ($routeName == 'admin.manage.logbook') ? 'bg-blue-500/20 text-blue-800 hover:bg-blue-600/20 border-blue-800/80' : 'text-gray-600 hover:bg-gray-50 border-transparent'; ?> transition duration-200 rounded-md border-l-4">
                        <div class="flex items-center space-x-5">
                            <i class="fas fa-book"></i>
                            <span>Logbook</span>
                        </div>
                    </a>
                </li>
            </ul>
            <h4 class="px-2 mt-3 text-xs font-semibold text-gray-600 uppercase">Management</h4>
            <ul class="mt-2">
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.manage.students') ?>"
                        class="flex items-center px-4 py-2.5 <?= ($routeName == 'admin.manage.students') ? 'bg-blue-500/20 text-blue-800 hover:bg-blue-600/20 border-blue-800/80' : 'text-gray-600 hover:bg-gray-50 border-transparent'; ?> transition duration-200 rounded-md border-l-4">
                        <i class="pr-4 fas fa-user-graduate"></i>
                        <span>Students</span>
                    </a>
                </li>
                <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
                    <li class="my-1">
                        <a href="<?= $this->urlFor('admin.manage.wardens') ?>"
                            class="flex items-center px-4 py-2.5 <?= ($routeName == 'admin.manage.wardens') ? 'bg-blue-500/20 text-blue-800 hover:bg-blue-600/20 border-blue-800/80' : 'text-gray-600 hover:bg-gray-50 border-transparent'; ?> transition duration-200 rounded-md border-l-4">
                            <i class="pr-3 fas fa-user-shield"></i>
                            <span>Wardens</span>
                        </a>
                    </li>

                    <li class="my-1">
                        <a href="<?= $this->urlFor('admin.manage.facilities') ?>"
                            class="flex items-center px-4 py-2.5 <?= ($routeName == 'admin.manage.facilities') ? 'bg-blue-500/20 text-blue-800 hover:bg-blue-600/20 border-blue-800/80' : 'text-gray-600 hover:bg-gray-50 border-transparent'; ?> transition duration-200 rounded-md border-l-4">
                            <i class="pr-4 fas fa-hotel"></i>
                            <span>Facilities</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <h4 class="px-2 mt-3 text-xs font-semibold text-gray-600 uppercase">System</h4>
            <ul class="mt-2">
                <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
                    <li class="my-1">
                        <a href="<?= $this->urlFor('admin.manage.verifiers') ?>"
                            class="flex items-center px-4 py-2.5 <?= ($routeName == 'admin.manage.verifiers') ? 'bg-blue-500/20 text-blue-800 hover:bg-blue-600/20 border-blue-800/80' : 'text-gray-600 hover:bg-gray-50 border-transparent'; ?> transition duration-200 rounded-md border-l-4">
                            <i class="pr-4 fas fa-qrcode"></i>
                            <span>Verifier Panel</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="my-1">
                    <a href="<?= $this->urlFor('admin.outpass.settings') ?>"
                        class="flex items-center px-4 py-2.5 <?= ($routeName == 'admin.outpass.settings') ? 'bg-blue-500/20 text-blue-800 hover:bg-blue-600/20 border-blue-800/80' : 'text-gray-600 hover:bg-gray-50 border-transparent'; ?> transition duration-200 rounded-md border-l-4">
                        <i class="pr-4 fas fa-shield"></i>
                        <span>Firewall Rules</span>
                    </a>
                </li>
                <?php if (UserRole::isSuperAdmin($user->getRole()->value)): ?>
                    <li class="my-1">
                        <a href="<?= $this->urlFor('admin.outpass.templates') ?>"
                            class="flex items-center px-4 py-2.5 <?= ($routeName == 'admin.outpass.templates') ? 'bg-blue-500/20 text-blue-800 hover:bg-blue-600/20 border-blue-800/80' : 'text-gray-600 hover:bg-gray-50 border-transparent'; ?> transition duration-200 rounded-md border-l-4">
                            <i class="pr-4 fas fa-layer-group"></i>
                            <span>Template Builder</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        
        <div class="p-2 border-t">
            <button class="w-full flex items-center p-2 rounded-md transition focus:outline-none focus:ring-2 focus:ring-blue-700 <?= ($routeName == 'admin.settings') ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'text-gray-600 hover:bg-gray-100'; ?>"
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