<header class="flex items-center justify-between p-[18.5px] bg-white border-b fixed top-0 left-0 w-full z-30">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-gray-600">
        <a href="<?= $this->urlFor('admin.dashboard')?>" class="hover:text-gray-500">Admin</a>
        <span>/</span>
        <a href="<?= $this->urlFor($routeName, get_defined_vars())?>" class="hover:text-gray-500"><?= ucwords(str_replace('.', ' ', str_replace('admin.', '', $routeName))) ?></a>
    </nav>

    <!-- Actions -->
    <div class="flex items-center space-x-4">
        <!-- Sidebar Toggle Button -->
        <button id="sidebarToggle"
            class="p-2 text-gray-600 transition duration-200 rounded-lg hover:bg-gray-100 lg:hidden">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Notification Button with Dropdown -->
        <div class="relative">
            <button id="notificationButton"
                class="p-2 text-gray-600 transition duration-200 rounded-lg hover:bg-gray-100">
                <i class="fas fa-bell"></i>
                <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-600 rounded-full"></span>
            </button>

            <!-- Improved Dropdown -->
            <div id="notificationDropdown"
                class="absolute right-0 z-50 hidden mt-2 overflow-hidden transition-all ease-in-out origin-top-right transform scale-95 bg-white border border-gray-100 rounded-lg shadow-lg opacity-0 w-80">
                <!-- Dropdown Header -->
                <div class="p-4 font-semibold text-gray-700 bg-gray-100 border-b">Notifications</div>

                <!-- Notification List -->
                <ul class="flex-1 p-2 space-y-2 bg-white">
                    <!-- Notification Item 1 - Unread -->
                    <li
                        class="flex items-center p-3 transition duration-200 rounded-lg shadow cursor-pointer hover:bg-gray-50">
                        <!-- Unread Indicator -->
                        <span class="w-2 h-2 mr-3 bg-blue-200 rounded-full"></span>
                        <i class="mr-3 text-blue-500 fas fa-envelope"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800">New user registered!</p>
                            <p class="mt-1 text-xs text-gray-500">5 minutes ago</p>
                        </div>
                    </li>
                    <!-- Notification Item 2 - Unread -->
                    <li
                        class="flex items-center p-3 transition duration-200 rounded-lg shadow cursor-pointer hover:bg-gray-50">
                        <!-- Unread Indicator -->
                        <span class="w-2 h-2 mr-3 bg-blue-200 rounded-full"></span>
                        <i class="mr-3 text-red-500 fas fa-exclamation-circle"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800">System alert: High CPU usage.</p>
                            <p class="mt-1 text-xs text-gray-500">1 hour ago</p>
                        </div>
                    </li>
                    <!-- Notification Item 3 - Read -->
                    <li
                        class="flex items-center p-3 transition duration-200 bg-gray-100 rounded-lg shadow cursor-pointer hover:bg-gray-50">
                        <i class="mr-3 text-green-500 fas fa-tasks"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Request #105 has been approved.</p>
                            <p class="mt-1 text-xs text-gray-500">30 minutes ago</p>
                        </div>
                    </li>
                </ul>
                <!-- Dropdown Footer -->
                <div class="p-2 text-center border-t">
                    <a href="#" class="text-sm text-blue-500 hover:underline">Mark all as read</a>
                </div>
            </div>
        </div>
        <!-- Logout Button using anchor tag -->
        <a href="<?= $this->urlFor('auth.logout') ?>" class="inline-flex items-center p-2 text-gray-600 transition duration-200 rounded-lg hover:bg-gray-100">
            <i class="mr-2 fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>
</header>