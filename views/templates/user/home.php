<div class="flex h-screen">
	<!-- Sidebar -->
	<aside class="bg-primary text-white w-64 md:w-64 transition-all duration-300 shadow-glow">
		<div class="flex items-center justify-center h-16">
			<h1 class="text-2xl font-bold">Passito</h1>
		</div>
		<nav class="mt-10">
			<ul class="p-4 space-y-4">
				<li>
					<a href="#"
						class="flex items-center p-3 rounded-lg hover:bg-secondary transition ease-in-out duration-200">
						<i class="fa-solid fa-chart-line"></i>
						<span class="ml-3 hidden md:block">Dashboard</span>
					</a>
				</li>
				<li>
					<a href="#"
						class="flex items-center p-3 rounded-lg hover:bg-secondary transition ease-in-out duration-200">
						<i class="fa-regular fa-file-lines"></i>
						<span class="ml-3 hidden md:block">Outpass</span>
					</a>
				</li>
				<li>
					<a href="#"
						class="flex items-center p-3 rounded-lg hover:bg-secondary transition ease-in-out duration-200">
						<i class="fa-regular fa-bell"></i>
						<span class="ml-3 hidden md:block">Notifications</span>
					</a>
				</li>
				<li>
					<a href="#"
						class="flex items-center p-3 rounded-lg hover:bg-secondary transition ease-in-out duration-200">
						<i class="fa-regular fa-user"></i>
						<span class="ml-3 hidden md:block">Profile</span>
					</a>
				</li>
			</ul>
		</nav>
	</aside>

	<!-- Main Content -->
	<div class="flex-1 p-6 transition-all duration-300">
		<nav class="bg-white shadow-md rounded-lg p-4 mb-10 flex justify-between items-center">
			<!-- Brand Name / Logo -->
			<div class="flex items-center space-x-3">
				<img src="path_to_logo.png" alt="Passito Logo" class="w-10 h-10">
				<span class="text-2xl font-bold text-blue-600">Passito</span>
			</div>

			<!-- Centered Navigation Links -->
			<div class="hidden md:flex space-x-6 text-lg">
				<a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Home</a>
				<a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Outpass Requests</a>
				<a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Notifications</a>
				<a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Profile</a>
			</div>

			<!-- Right Section: Toggle Sidebar, Profile & Logout Button -->
			<div class="flex items-center space-x-4">
				<button id="toggle-sidebar"
					class="md:hidden bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
					<i class="fa-solid fa-bars"></i>
				</button>

				<div class="relative group">
					<button
						class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
						<i class="fa-regular fa-user"></i>
						<span class="hidden md:inline">Profile</span>
					</button>
					<!-- Dropdown Menu for Profile -->
					<div class="absolute right-0 hidden mt-2 w-48 bg-white rounded-lg shadow-lg group-hover:block">
						<a href="#" class="block px-4 py-2 text-gray-600 hover:bg-blue-100">Settings</a>
						<a href="#" class="block px-4 py-2 text-gray-600 hover:bg-blue-100">Logout</a>
					</div>
				</div>
			</div>
		</nav>


		<!-- Dashboard Overview -->
		<h2 class="text-4xl font-bold text-primary mb-6">Welcome, Henry</h2>
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			<!-- Outpass Statistics Card -->
			<div class="bg-white shadow-lg rounded-lg p-8">
				<h2 class="text-3xl font-semibold mb-6 border-b-2 border-secondary pb-2">Outpass Statistics</h2>
				<div class="grid grid-cols-3 gap-4">
					<div>
						<p class="text-gray-600">Total Outpasses</p>
						<p class="text-lg text-primary">10</p>
					</div>
					<div>
						<p class="text-gray-600">Approved</p>
						<p class="text-lg text-success font-semibold">7</p>
					</div>
					<div>
						<p class="text-gray-600">Pending</p>
						<p class="text-lg text-warning font-semibold">3</p>
					</div>
				</div>
			</div>

			<!-- Notifications Card -->
			<div class="bg-white shadow-lg rounded-lg p-8">
				<h2 class="text-3xl font-semibold mb-6 border-b-2 border-secondary pb-2">Notifications</h2>
				<ul class="space-y-4 text-gray-700">
					<li class="flex items-center">
						<i class="fa-solid fa-check-circle text-success mr-2"></i>
						Your outpass request for 2024-10-05 has been approved.
					</li>
					<li class="flex items-center">
						<i class="fa-solid fa-clock text-warning mr-2"></i>
						Reminder: Submit your outpass request at least 24 hours in advance.
					</li>
					<li class="flex items-center">
						<i class="fa-solid fa-exclamation-circle text-danger mr-2"></i>
						Your last outpass request is still pending approval.
					</li>
				</ul>
			</div>
		</div>

		<!-- Previous Outpasses Section -->
		<div class="bg-white shadow-lg rounded-lg p-8 mt-6">
			<h2 class="text-3xl font-semibold mb-6 border-b-2 border-secondary pb-2">Previous Outpasses</h2>
			<table class="min-w-full bg-white rounded-lg shadow-inner">
				<thead>
					<tr class="bg-primary text-white">
						<th class="border px-4 py-2 text-left">From Date</th>
						<th class="border px-4 py-2 text-left">To Date</th>
						<th class="border px-4 py-2 text-left">Pass Type</th>
						<th class="border px-4 py-2 text-left">Destination</th>
						<th class="border px-4 py-2 text-left">Status</th>
						<th class="border px-4 py-2 text-left">Lifetime Status</th>
					</tr>
				</thead>
				<tbody>
					<tr class="hover:bg-lightGray">
						<td class="border px-4 py-2">2024-10-01</td>
						<td class="border px-4 py-2">2024-10-05</td>
						<td class="border px-4 py-2">Weekend Pass</td>
						<td class="border px-4 py-2">Home</td>
						<td class="border px-4 py-2 text-success font-semibold">Approved</td>
						<td class="border px-4 py-2 text-success font-semibold">Active</td>
					</tr>
					<tr class="hover:bg-lightGray">
						<td class="border px-4 py-2">2024-09-15</td>
						<td class="border px-4 py-2">2024-09-20</td>
						<td class="border px-4 py-2">Emergency Pass</td>
						<td class="border px-4 py-2">Hospital</td>
						<td class="border px-4 py-2 text-warning font-semibold">Pending</td>
						<td class="border px-4 py-2 text-warning font-semibold">Pending</td>
					</tr>
					<tr class="hover:bg-lightGray">
						<td class="border px-4 py-2">2024-08-20</td>
						<td class="border px-4 py-2">2024-08-22</td>
						<td class="border px-4 py-2">Family Visit</td>
						<td class="border px-4 py-2">Grandparents' House</td>
						<td class="border px-4 py-2 text-success font-semibold">Approved</td>
						<td class="border px-4 py-2 text-success font-semibold">Inactive</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	document.getElementById('toggle-sidebar').addEventListener('click', function () {
		const sidebar = document.querySelector('aside');
		sidebar.classList.toggle('w-0');
		sidebar.classList.toggle('md:w-64');
	});
</script>