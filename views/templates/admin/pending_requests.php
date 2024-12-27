<main class="flex-1 p-8 mt-20 overflow-y-auto bg-gray-100">
  <h2 class="text-3xl font-bold text-gray-900 mb-8">Pending Outpass Requests</h2>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
    <p class="text-gray-700 text-normal mb-4 sm:mb-0">Manage pending requests by approving, rejecting, or wiping them out.</p>
    <button class="px-4 py-2 rounded-md bg-red-500 text-white hover:bg-red-600 focus:outline-none shadow transition duration-300" onclick="wipeOutAllRequests()">
      <i class="fa-solid fa-xmark"></i>
      <span class="ml-2">Wipe Out</span>
    </button>
  </div>

  <div class="mt-6">
    <div class="bg-white rounded-t-lg shadow-md">
      <!-- Desktop Table -->
      <div class="overflow-x-auto hidden lg:block rounded-t-lg">
        <table class="min-w-full border border-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Request ID</th>
              <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Student Name</th>
              <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Department</th>
              <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Type</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Date & Duration</th>
              <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Files</th>
              <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            <!-- Table Row -->
            <tr class="hover:bg-gray-100 transition duration-200">
              <td class="px-6 py-4 text-sm text-gray-800 text-center">#58133</td>
              <td class="px-6 py-4 text-sm text-gray-800 text-center">John Doe</td>
              <td class="px-6 py-4 text-sm text-gray-800 text-center">CSE</td>
              <td class="px-6 py-4 text-sm text-gray-800 text-center">Home</td>
              <td class="px-6 py-4">
                <span class="block text-sm text-gray-900">23 Dec, 2024 - 24 Dec, 2024</span>
                <span class="block text-xs text-gray-600">10:00 AM to 6:00 PM</span>
              </td>
              <td class="px-6 py-4 text-center">
                <a href="#" class="text-indigo-500 hover:underline">
                  <i class="fa-solid fa-link"></i>
                  <span class="ml-1">Open</span>
                </a>
              </td>
              <td class="px-6 py-4 flex justify-center space-x-2">
                <button class="px-3.5 py-2 bg-green-500 text-white rounded shadow-md hover:bg-green-600 transition" onclick="approveRequest('67890')">
                  <i class="fa-solid fa-check"></i>
                </button>
                <button class="px-4 py-2 bg-red-500 text-white rounded shadow-md hover:bg-red-600 transition" onclick="rejectRequest('67890')">
                  <i class="fa-solid fa-xmark"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile Card Layout -->
      <div class="lg:hidden divide-y divide-gray-200">
        <div class="p-4">
          <h3 class="text-sm font-semibold text-gray-900">Request ID: #58133</h3>
          <p class="text-sm text-gray-700">Student: John Doe</p>
          <p class="text-sm text-gray-700">Department: CSE</p>
          <p class="text-sm text-gray-700">Type: Home</p>
          <p class="text-sm text-gray-700">Duration: 23 Dec, 2024 - 24 Dec, 2024 (10:00 AM to 6:00 PM)</p>
          <div class="flex justify-between items-center mt-4">
            <a href="#" class="text-indigo-500 text-sm font-medium hover:underline">Open Files</a>
            <div class="flex space-x-2">
              <button class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600" onclick="approveRequest('67890')">Approve</button>
              <button class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600" onclick="rejectRequest('67890')">Reject</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col sm:flex-row justify-between items-center p-4 bg-white border-t border-gray-200 rounded-b-lg">
      <div class="text-sm text-gray-700 mb-2 sm:mb-0">
        Showing <span class="font-semibold">1</span> to <span class="font-semibold">2</span> of <span class="font-semibold">10</span> results
      </div>
      <div class="flex space-x-2">
        <button class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300 transition">Previous</button>
        <button class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 transition">Next</button>
      </div>
    </div>
  </div>
</main>
