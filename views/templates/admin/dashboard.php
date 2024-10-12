<div class="flex h-screen">
    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-64 bg-white border-r flex flex-col lg:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out z-40">
        <!-- Flexbox for Sidebar Sections -->
        <nav class="flex flex-col h-full bg-white">
            <!-- Brand Section (Fixed) -->
            <div class="p-6 flex justify-center items-center border-b">
                <h3 class="text-2xl font-semibold text-primary">Passito Admin</h3>
            </div>

            <!-- Scrollable Menu (Middle) -->
            <div class="flex-1 overflow-y-auto px-3">
                <ul>
                    <li class="my-2">
                        <a href="#"
                            class="flex items-center p-4 text-gray-600 hover:bg-blue-50 rounded-md transition duration-200 active:bg-blue-100">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="my-2">
                        <a href="#"
                            class="flex items-center p-4 text-gray-600 hover:bg-blue-50 rounded-md transition duration-200">
                            <i class="fas fa-tasks mr-2"></i>
                            <span>Manage Requests</span>
                        </a>
                    </li>
                    <li class="my-2">
                        <a href="#"
                            class="flex items-center p-4 text-gray-600 hover:bg-blue-50 rounded-md transition duration-200">
                            <i class="fas fa-users mr-2"></i>
                            <span>User Management</span>
                        </a>
                    </li>
                    <li class="my-2">
                        <a href="#"
                            class="flex items-center p-4 text-gray-600 hover:bg-blue-50 rounded-md transition duration-200">
                            <i class="fas fa-chart-line mr-2"></i>
                            <span>Reports and Analytics</span>
                        </a>
                    </li>
                    <li class="my-2">
                        <a href="#"
                            class="flex items-center p-4 text-gray-600 hover:bg-blue-50 rounded-md transition duration-200">
                            <i class="fas fa-cog mr-2"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- User Info Section (Fixed at Bottom) -->
            <div class="border-t p-4 flex items-center">
                <img src="https://ui-avatars.com/api/?name=Henry&background=c7d2fe&color=3730a3&bold=true" alt="User Avatar"
                    class="w-10 h-10 rounded-md">
                <div class="ml-3 truncate">
                    <h4 class="font-semibold text-gray-800">Henry</h4>
                    <span class="text-xs text-gray-500">henry2212023@gmail.com</span>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <header class="flex items-center justify-between p-5 bg-white border-b relative">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-gray-600">
                <a href="#" class="hover:text-muted">Admin Panel</a>
                <span>/</span>
                <a href="#" class="hover:text-muted">Dashboard</a>
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
                        class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-200">
                        <i class="fas fa-bell"></i>
                        <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-600 rounded-full"></span>
                    </button>
                    <div id="notificationDropdown"
                        class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-4 border-b text-gray-700 font-semibold">Notifications</div>
                        <ul class="divide-y divide-gray-200">
                            <li class="p-3 text-gray-600 hover:bg-gray-50 cursor-pointer">
                                <i class="fas fa-envelope mr-2 text-primary"></i>
                                New user registered!
                            </li>
                            <li class="p-3 text-gray-600 hover:bg-gray-50 cursor-pointer">
                                <i class="fas fa-tasks mr-2 text-primary"></i>
                                Request #105 has been approved.
                            </li>
                            <li class="p-3 text-gray-600 hover:bg-gray-50 cursor-pointer">
                                <i class="fas fa-exclamation-circle mr-2 text-primary"></i>
                                System alert: High CPU usage.
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Logout Button -->
                <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-200">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 p-6">
            <!-- Dashboard Cards -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                    <h3 class="text-lg font-semibold text-gray-700">Total Users</h3>
                    <p class="text-3xl text-blue-600">150</p>
                    <p class="text-sm text-gray-500 mt-1">Active since last month</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                    <h3 class="text-lg font-semibold text-gray-700">Active Sessions</h3>
                    <p class="text-3xl text-blue-600">25</p>
                    <p class="text-sm text-gray-500 mt-1">Currently logged in</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                    <h3 class="text-lg font-semibold text-gray-700">Pending Requests</h3>
                    <p class="text-3xl text-blue-600">12</p>
                    <p class="text-sm text-gray-500 mt-1">Awaiting approval</p>
                </div>
            </section>

            <!-- Recent Activity -->
            <section class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Recent Activity</h3>
                <div class="bg-white p-6 rounded-lg shadow">
                    <ul class="divide-y divide-gray-200">
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">New user <span class="font-semibold">John Doe</span>
                                    signed up.</p>
                            </div>
                            <p class="text-xs text-gray-400">5 min ago</p>
                        </li>
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">Request <span class="font-semibold">#104</span> was
                                    approved by admin.</p>
                            </div>
                            <p class="text-xs text-gray-400">30 min ago</p>
                        </li>
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">System alert: <span class="font-semibold">CPU
                                        usage</span> is high.</p>
                            </div>
                            <p class="text-xs text-gray-400">1 hour ago</p>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- Outpass Requests Overview -->
            <section class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Outpass Requests Overview</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Total Outpass Requests</h3>
                        <p class="text-3xl text-blue-600">80</p>
                        <p class="text-sm text-gray-500 mt-1">Requests submitted</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Approved Outpass Requests</h3>
                        <p class="text-3xl text-blue-600">65</p>
                        <p class="text-sm text-gray-500 mt-1">Requests approved</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Rejected Outpass Requests</h3>
                        <p class="text-3xl text-blue-600">15</p>
                        <p class="text-sm text-gray-500 mt-1">Requests rejected</p>
                    </div>
                </div>
            </section>
            <!-- User Management Insights -->
            <section class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">User Management Insights</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Total Students</h3>
                        <p class="text-3xl text-blue-600">300</p>
                        <p class="text-sm text-gray-500 mt-1">Total registered students</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Active Users</h3>
                        <p class="text-3xl text-blue-600">250</p>
                        <p class="text-sm text-gray-500 mt-1">Users logged in today</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Inactive Users</h3>
                        <p class="text-3xl text-blue-600">50</p>
                        <p class="text-sm text-gray-500 mt-1">Users who haven't logged in for 30 days</p>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');

    // Toggle sidebar visibility on smaller screens
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });

    // Ensure the sidebar is visible on larger screens (if resized)
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
        } else {
            sidebar.classList.add('-translate-x-full');
        }
    });
</script>