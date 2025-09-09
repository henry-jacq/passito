<div class="px-2 space-y-6">
    <h3 class="text-xl font-bold text-gray-900">Report: Daily Movement Summary</h3>

    <div class="space-y-5">
        <!-- Frequency -->
        <div class="space-y-2">
            <label for="frequency" class="block font-semibold text-gray-700 text-md">Report Frequency</label>
            <select id="frequency" name="frequency"
                class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
        </div>

        <!-- Weekly: Day of Week -->
        <div id="weekly-options" class="hidden space-y-2">
            <label for="day-of-week" class="block font-semibold text-gray-700 text-md">Day of Week</label>
            <select id="day-of-week" name="day-of-week"
                class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md">
                <option value="1">Monday</option>
                <option value="2">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="4">Thursday</option>
                <option value="5">Friday</option>
                <option value="6">Saturday</option>
                <option value="7">Sunday</option>
            </select>
        </div>

        <!-- Monthly: Day of Month -->
        <div id="monthly-options" class="hidden space-y-2">
            <label for="day-of-month" class="block font-semibold text-gray-700 text-md">Day of Month</label>
            <input type="number" id="day-of-month" name="day-of-month" min="1" max="31"
                class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md">
        </div>

        <!-- Yearly: Month + Day -->
        <div id="yearly-options" class="hidden space-y-2">
            <label for="month" class="block font-semibold text-gray-700 text-md">Month</label>
            <select id="month" name="month"
                class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md">
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>

            <label for="yearly-day-of-month" class="block font-semibold text-gray-700 text-md">Day of Month</label>
            <input type="number" id="yearly-day-of-month" name="yearly-day-of-month" min="1" max="31"
                class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md">
        </div>

        <!-- Time -->
        <div class="space-y-2">
            <label for="time" class="block font-semibold text-gray-700 text-md">Time to Send</label>
            <input type="time" id="time" name="time"
                class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md"
                value="08:00">
        </div>

        <!-- Recipients -->
        <div class="space-y-2">
            <label for="recipients" class="block font-semibold text-gray-700 text-md">Recipients</label>
            <textarea id="recipients" name="recipients" rows="3"
                class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md"
                placeholder="Enter recipient emails, separated by commas"></textarea>
        </div>
    </div>
</div>

<script>
    const frequencySelect = document.getElementById('frequency');
    const weeklyOptions = document.getElementById('weekly-options');
    const monthlyOptions = document.getElementById('monthly-options');
    const yearlyOptions = document.getElementById('yearly-options');

    function toggleOptions() {
        weeklyOptions.classList.add('hidden');
        monthlyOptions.classList.add('hidden');
        yearlyOptions.classList.add('hidden');

        switch (frequencySelect.value) {
            case 'weekly':
                weeklyOptions.classList.remove('hidden');
                break;
            case 'monthly':
                monthlyOptions.classList.remove('hidden');
                break;
            case 'yearly':
                yearlyOptions.classList.remove('hidden');
                break;
        }
    }

    frequencySelect.addEventListener('change', toggleOptions);
    toggleOptions(); // run on page load
</script>