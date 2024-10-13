<header class="flex items-center justify-between p-5 bg-white border-b fixed top-0 left-0 w-full z-30">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-gray-600">
        <a href="#" class="hover:text-gray-500">Admin Panel</a>
        <span>/</span>
        <a href="#" class="hover:text-gray-500">Dashboard</a>
    </nav>

    <!-- Actions -->
    <div class="flex items-center space-x-4">
        <!-- Sidebar Toggle Button -->
        <button id="sidebarToggle"
            class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-200 lg:hidden">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Notification Button with Dropdown -->
        <div class="relative">
            <button id="notificationButton"
                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-200">
                <i class="fas fa-bell"></i>
                <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-600 rounded-full"></span>
            </button>

            <!-- Improved Dropdown -->
            <div id="notificationDropdown"
                class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-100 rounded-lg shadow-lg overflow-hidden transition-all transform scale-95 opacity-0 origin-top-right ease-in-out z-50">
                <!-- Dropdown Header -->
                <div class="p-4 bg-gray-100 font-semibold border-b text-gray-700">Notifications</div>

                <!-- Notification List -->
                <ul class="flex-1 p-2 space-y-2 bg-white">
                    <!-- Notification Item 1 - Unread -->
                    <li
                        class="flex items-center p-3 hover:bg-gray-50 rounded-lg shadow transition duration-200 cursor-pointer">
                        <!-- Unread Indicator -->
                        <span class="w-2 h-2 bg-blue-200 rounded-full mr-3"></span>
                        <i class="fas fa-envelope text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-800 font-medium">New user registered!</p>
                            <p class="text-xs text-gray-500 mt-1">5 minutes ago</p>
                        </div>
                    </li>
                    <!-- Notification Item 2 - Unread -->
                    <li
                        class="flex items-center p-3 hover:bg-gray-50 rounded-lg shadow transition duration-200 cursor-pointer">
                        <!-- Unread Indicator -->
                        <span class="w-2 h-2 bg-blue-200 rounded-full mr-3"></span>
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-800 font-medium">System alert: High CPU usage.</p>
                            <p class="text-xs text-gray-500 mt-1">1 hour ago</p>
                        </div>
                    </li>
                    <!-- Notification Item 3 - Read -->
                    <li
                        class="flex items-center p-3 bg-gray-100 hover:bg-gray-50 rounded-lg shadow transition duration-200 cursor-pointer">
                        <i class="fas fa-tasks text-green-500 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-800 font-medium">Request #105 has been approved.</p>
                            <p class="text-xs text-gray-500 mt-1">30 minutes ago</p>
                        </div>
                    </li>
                </ul>
                <!-- Dropdown Footer -->
                <div class="p-2 text-center border-t">
                    <a href="#" class="text-sm text-blue-500 hover:underline">Mark all as read</a>
                </div>
            </div>
        </div>
        <!-- Logout Button -->
        <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-200">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </div>
</header>