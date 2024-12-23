<div class="min-h-screen flex flex-col bg-gray-50">
	<!-- Header Section -->
	<?= $this->getComponent('user/header', [
		'routeName' => $routeName
	]) ?>

	<!-- Main Content -->
	<main class="container mx-auto p-6 space-y-10">
		<!-- Welcome Section -->
		<section
			class="bg-gradient-to-r from-purple-100 to-white shadow-lg rounded-xl p-8 flex flex-col md:flex-row justify-between">
			<div>
				<h2 class="text-4xl font-bold text-gray-800">Welcome, Henry!</h2>
				<p class="mt-2 text-gray-800 text-lg">It’s Wednesday, 23 December 2024</p>
			</div>
		</section>

		<!-- Overview Cards -->
		<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
			<div class="p-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
				<div class="flex items-center gap-4">
					<i class="fas fa-file-alt text-purple-600 text-3xl"></i>
					<h3 class="text-xl font-medium">Total Requests</h3>
				</div>
				<p class="text-4xl font-medium mt-4">25</p>
			</div>
			<div class="p-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
				<div class="flex items-center gap-4">
					<i class="fas fa-check-circle text-purple-600 text-3xl"></i>
					<h3 class="text-xl font-medium">Accepted</h3>
				</div>
				<p class="text-4xl font-medium mt-4">15</p>
			</div>
			<div class=" p-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
				<div class="flex items-center gap-4">
					<i class="fas fa-clock text-purple-600 text-3xl"></i>
					<h3 class="text-xl font-medium">Pending</h3>
				</div>
				<p class="text-4xl font-medium mt-4">5</p>
			</div>
			<div class="p-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
				<div class="flex items-center gap-4">
					<i class="fas fa-times-circle text-purple-600 text-3xl"></i>
					<h3 class="text-xl font-medium">Rejected</h3>
				</div>
				<p class="text-4xl font-medium mt-4">5</p>
			</div>
		</section>

		<!-- Analytics Section -->
		<section class="bg-white shadow-lg rounded-xl p-8">
			<h3 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
				<i class="fa-solid fa-chart-line text-purple-600 mr-3"></i> Analytics Overview
			</h3>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				<!-- Outpass Trends Chart -->
				<div class="bg-gray-50 p-6 rounded-lg shadow-md">
					<h4 class="text-lg font-medium text-gray-700 flex items-center">
						<i class="fa-solid fa-chart-pie text-purple-600 mr-2"></i> Outpass Trends
					</h4>
					<div class="h-56 relative mt-4">
						<canvas id="outpassTrendsChart"></canvas>
					</div>
				</div>

				<!-- Approval Time Analysis -->
				<div class="bg-gray-50 p-6 rounded-lg shadow-md">
					<h4 class="text-lg font-medium text-gray-700 flex items-center">
						<i class="fa-solid fa-clock text-purple-600 mr-2"></i> Approval Time Analysis
					</h4>
					<div class="h-56 relative mt-4">
						<canvas id="approvalTimeChart"></canvas>
					</div>
				</div>
			</div>
		</section>
	</main>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	// Outpass Trends Chart
	const ctx1 = document.getElementById('outpassTrendsChart').getContext('2d');
	new Chart(ctx1, {
		type: 'line',
		data: {
			labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
			datasets: [{
				label: 'Outpasses',
				data: [12, 19, 3, 5, 2, 3],
				borderColor: '#6b21a8',
				backgroundColor: 'rgba(107, 33, 168, 0.2)',
				borderWidth: 2,
				tension: 0.4,
				pointBackgroundColor: '#6b21a8',
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: { display: false },
			},
			scales: {
				x: { grid: { display: false } },
				y: { grid: { color: '#e5e7eb' } }
			}
		}
	});

	// Approval Time Analysis Chart
	const ctx2 = document.getElementById('approvalTimeChart').getContext('2d');
	new Chart(ctx2, {
		type: 'bar',
		data: {
			labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
			datasets: [{
				label: 'Approval Time (hours)',
				data: [3, 2.5, 4, 3.5, 3, 2],
				backgroundColor: ['#6b21a8', '#9b4de7', '#7c3aed', '#6b21a8', '#9b4de7', '#7c3aed'],
				borderRadius: 5,
				borderSkipped: false,
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: { display: false },
			},
			scales: {
				x: { grid: { display: false } },
				y: { grid: { color: '#e5e7eb' } }
			}
		}
	});
</script>