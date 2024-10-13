<aside id="sidebar"
    class="fixed inset-y-0 left-0 w-64 bg-white border-r flex flex-col lg:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out z-40">
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
                <!-- Dashboard Item -->
                <li class="my-2">
                    <a href="#"
                        class="flex items-center px-4 py-3 bg-indigo-100 text-indigo-600 hover:bg-indigo-200 rounded-md transition duration-200">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Additional Menu Items -->
                <li class="my-2">
                    <a href="#"
                        class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-md transition duration-200">
                        <i class="fas fa-tasks mr-3"></i>
                        <span>Manage Requests</span>
                    </a>
                </li>
                
                <!-- User Management with Nested Options -->
                <li class="my-2 relative">
                    <button id="userManagementToggle"
                        class="flex justify-between items-center w-full px-4 py-3 text-gray-600 hover:bg-gray-50 active:bg-gray-100 rounded-md transition duration-200">
                        <span class="flex items-center">
                            <i class="fas fa-users mr-3"></i>
                            <span>User Management</span>
                        </span>
                        <span id="userManagementArrow" class="transform transition-transform duration-300">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </button>

                    <ul id="userManagementSubmenu" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out pl-4 mt-2">
                        <li class="relative">
                            <a href="#"
                                class="flex items-center ml-5 px-3 py-2 text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition duration-200">
                                <span>Add User</span>
                            </a>
                            <div class="absolute left-2 top-1/2 w-0.5 h-full bg-indigo-600 transform -translate-y-1/2"></div>
                        </li>
                        <li class="relative">
                            <a href="#"
                                class="flex items-center ml-5 px-3 py-2 text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition duration-200">
                                <span>Manage Roles</span>
                            </a>
                            <div class="absolute left-2 top-1/2 w-0.5 h-full bg-indigo-600 transform -translate-y-1/2"></div>
                        </li>
                        <li class="relative">
                            <a href="#"
                                class="flex items-center ml-5 px-3 py-2 text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition duration-200">
                                <span>View Users</span>
                            </a>
                            <div class="absolute left-2 top-1/2 w-0.5 h-full bg-indigo-600 transform -translate-y-1/2"></div>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- System Settings Category -->
            <h4 class="text-gray-600 px-2 mt-4 font-semibold uppercase text-xs">System Settings</h4>
            <ul>
                <li class="my-2">
                    <a href="#"
                        class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-md transition duration-200">
                        <i class="fas fa-chart-line mr-3"></i>
                        <span>Reports & Analytics</span>
                    </a>
                </li>
                <li class="my-2">
                    <a href="#"
                        class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-md transition duration-200">
                        <i class="fas fa-cog mr-3"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- User Info Section (Fixed at Bottom) -->
        <div class="border-t p-4 flex items-center">
            <img src="https://ui-avatars.com/api/?name=Henry&background=c7d2fe&color=3730a3&bold=true"
                alt="User Avatar" class="w-10 h-10 rounded-md">
            <div class="ml-3 truncate">
                <h4 class="font-semibold text-gray-800">Henry</h4>
                <span class="text-xs text-gray-500">henry2212023@gmail.com</span>
            </div>
        </div>
    </nav>
</aside>

<script>
    // Toggle nested submenu for 'User Management'
    const userManagementToggle = document.getElementById('userManagementToggle');
    const userManagementSubmenu = document.getElementById('userManagementSubmenu');
    const userManagementArrow = document.getElementById('userManagementArrow');

    userManagementToggle.addEventListener('click', () => {
        userManagementToggle.classList.toggle('bg-gray-100'); // Highlights the menu item
        userManagementSubmenu.classList.toggle('max-h-0'); // Toggles max-height
        userManagementSubmenu.classList.toggle('max-h-32'); // Set a max-height for transition
        userManagementArrow.classList.toggle('rotate-180'); // Rotates the arrow
    });
</script>
