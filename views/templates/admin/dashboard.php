<div class="flex h-screen">
    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-64 bg-white border-r flex flex-col lg:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out z-40">
        <!-- Flexbox for Sidebar Sections -->
        <nav class="flex flex-col h-full bg-white">
            <!-- Brand Section (Fixed) -->
            <div class="p-6 flex justify-center items-center border-b">
                <svg class="w-8 h-8" viewBox="0 0 92.105 92.1">
                    <g transform="translate(-2.76 -2.77) scale(0.3254)" fill="currentColor">
                        <g xmlns="http://www.w3.org/2000/svg">
                        <path d="M202.9,120.4L156,82.2c-3.5-2.9-8.6-2.9-12.1,0L97,120.5c-1.9,1.5-1.9,4.5,0,6l14.7,12c2.1,1.7,5.1,1.7,7.3,0l30.9-25.2l30.9,25.3c2.1,1.7,5.1,1.7,7.3,0l14.7-12C204.8,124.9,204.8,122,202.9,120.4z"></path>
                        <path d="M249.9,158.9l40.2-32.7c1.9-1.5,1.9-4.5,0-6L156,10.7c-3.5-2.9-8.6-2.9-12.1,0L9.9,120c-1.9,1.5-1.9,4.5,0,6l40.2,32.8L9.9,191.6c-1.9,1.5-1.9,4.5,0,6L124,290.7c2.5,2,6.2,0.3,6.2-3V193c0-2.9-1.3-5.7-3.6-7.5L94,158.9L72,141l-19-15.7c-1.4-1.1-1.4-3.3,0-4.5l93.4-76.2c2.1-1.7,5.1-1.7,7.3,0l93.4,76.2c1.4,1.1,1.4,3.3,0,4.5L228,140.9l-22.1,18l-32.6,26.6c-2.3,1.8-3.6,4.6-3.6,7.5v94.7c0,3.2,3.8,5,6.2,3L290,197.6c1.9-1.5,1.9-4.5,0-6L249.9,158.9zM73.9,178.3l26.7,21.8c1.3,1.1,2.2,2.8,2.2,4.5v28.8c0,1.6-1.9,2.6-3.1,1.5L53,196.8c-1.4-1.1-1.4-3.3,0-4.5l17.3-14.1C71.3,177.3,72.8,177.3,73.9,178.3zM197.3,233.4v-28.8c0-1.7,0.8-3.4,2.2-4.5l26.7-21.8c1-0.9,2.5-0.9,3.6,0l17.3,14.1c1.4,1.1,1.4,3.3,0,4.5L200.4,235C199.1,235.9,197.3,235.1,197.3,233.4z"></path>
                        </g>
                    </g>
                </svg>
            </div>

            <!-- Scrollable Menu (Middle) -->
            <div class="flex-1 overflow-y-auto px-3">
                <!-- Main Menu Category -->
                <h4 class="text-gray-600 px-2 mt-4 font-semibold uppercase text-xs">Main Menu</h4>
                <ul>
                    <li class="my-2">
                        <a href="#"
                            class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-md transition duration-200 active:bg-gray-100">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="my-2">
                        <a href="#"
                            class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-md transition duration-200 active:bg-gray-100">
                            <i class="fas fa-tasks mr-2"></i>
                            <span>Manage Requests</span>
                        </a>
                    </li>
                    <li class="my-2">
                        <a href="#"
                            class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-md transition duration-200 active:bg-gray-100">
                            <i class="fas fa-users mr-2"></i>
                            <span>User Management</span>
                        </a>
                    </li>
                </ul>

                <!-- System Settings Category -->
                <h4 class="text-gray-600 px-2 mt-4 font-semibold uppercase text-xs">System Settings</h4>
                <ul>
                    <li class="my-2">
                        <a href="#"
                            class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-md transition duration-200 active:bg-gray-100">
                            <i class="fas fa-chart-line mr-2"></i>
                            <span>Reports & Analytics</span>
                        </a>
                    </li>
                    <li class="my-2">
                        <a href="#"
                            class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-md transition duration-200 active:bg-gray-100">
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
        class="hidden absolute right-0 mt-2 w-72 bg-white border rounded-lg shadow-lg overflow-hidden transition-all duration-200 ease-in-out transform scale-95 origin-top-right">
        <div class="p-4 border-b text-gray-700 font-semibold bg-white">Notifications</div>
        <ul class="flex-1 p-1 gap-3 select-none">
            <li class="px-3 py-2 flex items-center hover:bg-gray-50 rounded transition duration-200 cursor-pointer">
                <i class="fas fa-envelope mr-3 text-accent"></i>
                <div>
                    <p class="text-sm">New user registered!</p>
                    <p class="text-xs text-gray-400">5 minutes ago</p>
                </div>
            </li>
            <li class="px-3 py-2 flex items-center hover:bg-gray-50 rounded transition duration-200 cursor-pointer">
                <i class="fas fa-tasks mr-3 text-primary"></i>
                <div>
                    <p class="text-sm">Request #105 has been approved.</p>
                    <p class="text-xs text-gray-400">30 minutes ago</p>
                </div>
            </li>
            <li class="px-3 py-2 flex items-center hover:bg-gray-50 rounded transition duration-200 cursor-pointer">
                <i class="fas fa-exclamation-circle mr-3 text-danger"></i>
                <div>
                    <p class="text-sm">System alert: High CPU usage.</p>
                    <p class="text-xs text-gray-400">1 hour ago</p>
                </div>
            </li>
        </ul>
        <div class="p-2 text-center border-t">
            <a href="#" class="text-sm text-primary hover:underline">Mark as read</a>
        </div>
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
            <!-- Key Metrics -->
            <section>
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Dashboard</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Students -->
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Total Students</h3>
                        <p class="text-3xl text-blue-600">300</p>
                        <p class="text-sm text-gray-500 mt-1">All registered students</p>
                    </div>
                    <!-- Active Students Today -->
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Active Students</h3>
                        <p class="text-3xl text-blue-600">250</p>
                        <p class="text-sm text-gray-500 mt-1">Logged in today</p>
                    </div>
                    <!-- Outpass Requests This Month -->
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Outpass Requests (This Month)</h3>
                        <p class="text-3xl text-blue-600">80</p>
                        <p class="text-sm text-gray-500 mt-1">Requests submitted</p>
                    </div>
                    <!-- Rejected Requests -->
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Rejected Requests</h3>
                        <p class="text-3xl text-blue-600">15</p>
                        <p class="text-sm text-gray-500 mt-1">Requests rejected</p>
                    </div>
                </div>
            </section>
            <!-- Outpass Insights -->
            <section class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Outpass Requests</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Approved Requests -->
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Approved Requests</h3>
                        <p class="text-3xl text-blue-600">65</p>
                        <p class="text-sm text-gray-500 mt-1">Approved this month</p>
                    </div>
                    <!-- Pending Requests -->
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Pending Requests</h3>
                        <p class="text-3xl text-blue-600">10</p>
                        <p class="text-sm text-gray-500 mt-1">Awaiting approval</p>
                    </div>
                    <!-- Total Requests -->
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Total Requests</h3>
                        <p class="text-3xl text-blue-600">80</p>
                        <p class="text-sm text-gray-500 mt-1">Submitted this month</p>
                    </div>
                    <!-- Rejected Requests -->
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Rejected Requests</h3>
                        <p class="text-3xl text-blue-600">15</p>
                        <p class="text-sm text-gray-500 mt-1">Rejected this month</p>
                    </div>
                </div>
            </section>
            <!-- Recent Activity -->
            <section class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Recent Activities</h3>
                <div class="bg-white p-6 rounded-lg shadow">
                    <ul class="divide-y divide-gray-200">
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">New user <span class="font-semibold">John Doe</span> signed up.</p>
                            </div>
                            <p class="text-xs text-gray-400">5 min ago</p>
                        </li>
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">Outpass Request <span class="font-semibold">#202</span> was approved.</p>
                            </div>
                            <p class="text-xs text-gray-400">30 min ago</p>
                        </li>
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">Outpass Request <span class="font-semibold">#198</span> was rejected.</p>
                            </div>
                            <p class="text-xs text-gray-400">1 hour ago</p>
                        </li>
                    </ul>
                </div>
            </section>
            <!-- Students Insights -->
            <section class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Students Insights</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Hostel 1</h3>
                        <p class="text-3xl text-blue-600">120</p>
                        <p class="text-sm text-gray-500 mt-1">Students residing</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Year 3 Students</h3>
                        <p class="text-3xl text-blue-600">80</p>
                        <p class="text-sm text-gray-500 mt-1">Third-year students</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                        <h3 class="text-lg font-semibold text-gray-700">Double Occupancy Rooms</h3>
                        <p class="text-3xl text-blue-600">90</p>
                        <p class="text-sm text-gray-500 mt-1">Students in double rooms</p>
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

    // Toggle notification dropdown
    notificationButton.addEventListener('click', () => {
        notificationDropdown.classList.toggle('hidden');
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