<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <?php if ($data['lockRequests']): ?>
        <div class="flex items-center justify-between p-4 mb-6 border rounded-lg bg-amber-50 border-amber-200">
            <!-- Status Info -->
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-amber-100">
                    <i class="text-amber-600 fas fa-lock"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-gray-800">Outpass Requests Locked</span>
                    </div>
                    <p class="text-sm text-gray-600">Students cannot submit new outpass requests</p>
                </div>
            </div>

            <!-- Unlock Button -->
            <button id="unlockRequests"
                class="flex items-center px-4 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                <i class="mr-2 fas fa-unlock"></i>
                <span>Unlock Requests</span>
            </button>
        </div>
    <?php endif; ?>
    <section>
        <h2 class="mb-4 text-2xl font-semibold text-gray-700">Dashboard</h2>
        <!-- <p class="mb-8 text-gray-600 text-md">Monitor outpass statistics, trends, and manage requests with quick actions.</p> -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700">Total Outpasses</h3>
                <p class="text-3xl text-blue-800"><?= $data['approved'] ?></p>
                <p class="mt-1 text-sm text-gray-500">All-time approved requests</p>
            </div>
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700">Pending Requests</h3>
                <p class="text-3xl text-yellow-800"><?= $data['pending'] ?></p>
                <!-- <p class="mt-1 text-sm text-gray-500">Awaiting approval</p> -->
                <p class="mt-1 text-sm font-medium text-green-500">
                    <i class="mr-1 fa-solid fa-arrow-up"></i>
                    5% from last week
                </p>
            </div>
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700">Rejected Requests</h3>
                <p class="text-3xl text-red-800"><?= $data['rejected'] ?></p>
                <p class="mt-1 text-sm text-gray-500">Denied by wardens</p>
            </div>
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700">Checked-out Students</h3>
                <p class="text-3xl text-green-800"><?= $data['checkedOut'] ?></p>
                <p class="mt-1 text-sm text-gray-500">Currently outside</p>
            </div>
        </div>
    </section>

    <!-- Outpass Trends & Insights -->
    <section class="mt-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2">
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h3 class="mb-4 text-lg font-semibold text-gray-700">Outpass Trends (Monthly)</h3>
                <canvas id="outpassesChart"></canvas>
            </div>
            <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h3 class="mb-4 text-lg font-semibold text-gray-700">Verifier (Weekly)</h3>
                <canvas id="checkinCheckoutChart"></canvas>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="mt-6">
        <h3 class="mb-3 text-xl font-semibold text-gray-700">Quick Actions</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-1 lg:grid-cols-3">
            <div class="p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h4 class="font-semibold">Bulk Approval</h4>
                <p class="mb-3 text-sm">Quickly approve all pending requests.</p>
                <button id="bulkApproval" class="flex items-center px-2 py-1 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" aria-expanded="false">
                    <i class="mr-2 fas fa-play"></i>
                    <span>Perform</span>
                </button>
            </div>
            <div class="p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h4 class="font-semibold">Notify Students</h4>
                <p class="mb-3 text-sm">Alert students who haven't checked in.</p>
                <button id="notifyStudents" class="flex items-center px-2 py-1 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" aria-expanded="false">
                    <i class="mr-2 fas fa-play"></i>
                    <span>Perform</span>
                </button>
            </div>
            <div class="p-4 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
                <h4 class="font-semibold">Lock Requests</h4>
                <p class="mb-3 text-sm">Block new requests until unlocked.</p>
                <button id="lockRequests" class="flex items-center px-2 py-1 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" aria-expanded="false">
                    <i class="mr-2 fas fa-play"></i>
                    <span>Perform</span>
                </button>
            </div>
        </div>
    </section>
</main>


<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const createChart = (id, type, data, options) => {
        new Chart(document.getElementById(id).getContext('2d'), {
            type,
            data,
            options
        });
    };

    createChart('outpassesChart', 'line', {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Outpasses Issued',
            data: [30, 50, 80, 60, 90, 120, 100, 130, 110, 140, 150, 160],
            borderColor: 'rgb(39, 91, 175)', // Tailwind blue-500
            backgroundColor: 'rgba(65, 126, 225, 0.3)', // Tailwind blue-500 @ 20%
            fill: true,
            tension: 0.3
        }]
    }, {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    });

    createChart('checkinCheckoutChart', 'bar', {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        datasets: [{
                label: 'Checked In',
                data: [80, 100, 90, 110, 120, 130, 140],
                backgroundColor: 'rgba(37, 99, 235, 0.7)', // Tailwind blue-500 @ 80%
                borderRadius: 5
            },
            {
                label: 'Checked Out',
                data: [70, 90, 85, 95, 110, 125, 130],
                backgroundColor: 'rgba(29, 78, 216, 0.7)', // Tailwind blue-500 @ 60%
                borderRadius: 5
            }
        ]
    }, {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    });
</script>