<!-- Dashboard Content -->
<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <!-- Key Metrics -->
    <section>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Dashboard</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        <p class="text-sm text-gray-600">New user <span class="font-semibold">John Doe</span>
                            signed up.</p>
                    </div>
                    <p class="text-xs text-gray-400">5 min ago</p>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Outpass Request <span class="font-semibold">#202</span>
                            was approved.</p>
                    </div>
                    <p class="text-xs text-gray-400">30 min ago</p>
                </li>
                <li class="py-3 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Outpass Request <span class="font-semibold">#198</span>
                            was rejected.</p>
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