<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <!-- Page Header -->
    <section>
        <div class="mb-6 space-y-2">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Create Report</h2>
            <p class="mb-10 text-gray-600 text-md">Define a new report to display on the dashboard. You can specify its name, description, data source, display type, columns, and filters.</p>
        </div>
    </section>

    <!-- Report Form -->
    <section class="mb-6">
        <div class="p-6 transition duration-200 bg-white rounded-lg shadow hover:shadow-lg">
            <form action="#" method="POST" class="space-y-6">

                <!-- Report Name -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700" for="reportName">Report Name</label>
                    <input type="text" id="reportName" name="reportName" placeholder="Enter report name"
                        class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Report Description -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700" for="reportDescription">Description</label>
                    <textarea id="reportDescription" name="reportDescription" rows="3" placeholder="Briefly describe this report"
                        class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <!-- Data Source -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700" for="dataSource">Data Source</label>
                    <select id="dataSource" name="dataSource"
                        class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a data source</option>
                        <option value="students">Students</option>
                        <option value="outpass_requests">Outpass Requests</option>
                        <option value="checkin_logs">Check-in Logs</option>
                        <!-- Add more stub sources as needed -->
                    </select>
                </div>

                <!-- Display Type -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700" for="displayType">Display Type</label>
                    <select id="displayType" name="displayType"
                        class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="list">List / Count</option>
                        <option value="table">Table</option>
                        <option value="badge">Badge / Tag</option>
                    </select>
                </div>

                <!-- Columns (for Table Display) -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700" for="columns">Columns (for table)</label>
                    <input type="text" id="columns" name="columns" placeholder="E.g., Name, Hostel, Status"
                        class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Comma-separated column names to display in the table.</p>
                </div>

                <!-- Filters -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700" for="filters">Filters</label>
                    <textarea id="filters" name="filters" rows="2" placeholder="E.g., status=Pending, hostel=Block A"
                        class="w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    <p class="mt-1 text-xs text-gray-500">Optional conditions to filter the data for this report.</p>
                </div>

                <!-- CSV Export Option -->
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="csvExport" name="csvExport" checked
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="csvExport" class="text-sm text-gray-700">Enable CSV export</label>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="px-5 py-2 font-medium text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">
                        Create Report
                    </button>
                </div>

            </form>
        </div>
    </section>
</main>