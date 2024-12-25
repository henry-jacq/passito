<main class="flex-1 p-8 mt-20 overflow-y-auto bg-gray-50">
  <h2 class="text-3xl font-bold text-gray-800 mb-8">Pending Outpass Requests</h2>

  <div class="flex justify-between items-center mb-6">
    <p class="text-gray-600 text-sm">Manage pending requests by approving, rejecting, or wiping them out.</p>
    <!-- Button to wipe out all pending requests -->
    <button class="flex items-center px-4 py-2 bg-red-500 text-white hover:bg-red-600 rounded-lg shadow-md transition duration-300 ease-in-out" onclick="wipeOutAllRequests()">
      <i class="fa-solid fa-xmark"></i>
      <span class="ml-2">Wipe Out</span>
    </button>
  </div>

  <div class="mt-6">
    <!-- Responsive Table Container -->
    <div class="bg-white rounded-t-lg shadow-lg overflow-hidden">
      <!-- Table should fit the container and scroll horizontally on smaller screens -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 table-auto">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Student Name</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Digital ID</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Type</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Date & Duration</th>
              <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Files</th>
              <th class="px-6 py-3 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <!-- Table Row 1 -->
            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out cursor-pointer">
              <td class="px-6 py-4 text-sm text-gray-800 truncate">John Doe</td>
              <td class="px-6 py-4 text-sm text-gray-800">#12345</td>
              <td class="px-6 py-4 text-sm text-gray-800">Home</td>
              <td class="px-6 py-4">
                <span class="block text-sm text-gray-800">23 Dec, 2024 - 24 Dec, 2024</span>
                <span class="block text-xs text-gray-500">10:00 AM to 6:00 PM</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-800 text-center">
                <a href="#" class="text-blue-500 hover:text-blue-600">
                  <i class="fa-solid fa-link"></i>
                  Open
                </a>
              </td>
              <td class="px-6 py-4 text-sm flex justify-center space-x-2">
                <button class="flex items-center px-3 py-1 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition duration-300 ease-in-out" onclick="approveRequest('12345')">
                  <i class="fa-solid fa-check"></i>
                  <!-- <span class="ml-1">Approve</span> -->
                </button>
                <button class="flex items-center px-3 py-1 bg-red-500 text-white rounded-lg shadow-md hover:bg-red-600 transition duration-300 ease-in-out" onclick="rejectRequest('12345')">
                  <i class="fa-solid fa-xmark"></i>
                  <!-- <span class="ml-2">Reject</span> -->
                </button>
              </td>
            </tr>
            <!-- Table Row 2 -->
            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out cursor-pointer">
              <td class="px-6 py-4 text-sm text-gray-800 truncate">Jane Smith</td>
              <td class="px-6 py-4 text-sm text-gray-800">#67890</td>
              <td class="px-6 py-4 text-sm text-gray-800">Outing</td>
              <td class="px-6 py-4">
                <span class="block text-sm text-gray-800">15 Dec, 2024 - 16 Dec, 2024</span>
                <span class="block text-xs text-gray-500">8:00 AM to 3:00 PM</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-800 text-center">
                <a href="#" class="text-blue-500 hover:text-blue-600">
                  <i class="fa-solid fa-link"></i>
                  Open
                </a>
              </td>
              <td class="px-6 py-4 text-sm flex justify-center space-x-2">
                <button class="flex items-center px-3 py-1 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition duration-300 ease-in-out" onclick="approveRequest('67890')">
                  <i class="fa-solid fa-check"></i>
                  <!-- <span class="ml-1">Approve</span> -->
                </button>
                <button class="flex items-center px-3 py-1 bg-red-500 text-white rounded-lg shadow-md hover:bg-red-600 transition duration-300 ease-in-out" onclick="rejectRequest('67890')">
                  <i class="fa-solid fa-xmark"></i>
                  <!-- <span class="ml-2">Reject</span> -->
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination Section -->
    <div class="flex flex-wrap justify-between items-center p-4 bg-white border-t border-gray-200">
      <div class="text-sm text-gray-600">
        Showing <span class="font-semibold">1</span> to <span class="font-semibold">2</span> of <span class="font-semibold">10</span> results
      </div>
      <div class="flex space-x-2">
        <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-300 focus:outline-none" onclick="goToPreviousPage()">Previous</button>
        <button class="px-3 py-1 rounded-md bg-indigo-500 text-white text-sm font-medium hover:bg-indigo-600 focus:outline-none" onclick="goToNextPage()">Next</button>
      </div>
    </div>
  </div>
</main>

<!-- JavaScript for Handling Operations -->
<script>
  function approveRequest(outpassId) {
    alert("Approved Outpass ID: " + outpassId);
  }

  function rejectRequest(outpassId) {
    alert("Rejected Outpass ID: " + outpassId);
  }

  function wipeOutAllRequests() {
    if (confirm("Are you sure you want to wipe out all pending requests?")) {
      alert("All pending requests have been wiped out.");
    }
  }

  function goToPreviousPage() {
    alert("Going to the previous page");
  }

  function goToNextPage() {
    alert("Going to the next page");
  }
</script>
