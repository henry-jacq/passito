<div class="flex flex-col min-h-screen bg-gray-50">
	<!-- Header Section -->
	<?= $this->getComponent('user/header', [
		'routeName' => $routeName
	]) ?>

	<!-- Main Content -->
	<main class="container px-6 py-8 mx-auto lg:px-12">
		<!-- Welcome Section -->
		<section class="flex flex-col justify-between p-8 mb-8 shadow-lg bg-gradient-to-r from-blue-100 to-white rounded-xl md:flex-row">
			<div>
				<h2 class="text-4xl font-bold text-gray-800">Welcome, <?= ucwords($userData->getUser()->getName())?>!</h2>
				<p class="mt-2 text-lg text-gray-800">It’s <?= date("l, d F Y") ?></p>
			</div>
		</section>

		<!-- Overview Cards -->
		<section class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
			<div class="p-6 transition-all transform rounded-lg shadow-lg hover:shadow-xl hover:scale-105">
				<div class="flex items-center gap-4">
					<i class="text-3xl text-blue-600 fas fa-file-alt"></i>
					<h3 class="text-xl font-medium">Total Requests</h3>
				</div>
				<p class="mt-4 text-4xl font-medium">25</p>
			</div>
			<div class="p-6 transition-all transform rounded-lg shadow-lg hover:shadow-xl hover:scale-105">
				<div class="flex items-center gap-4">
					<i class="text-3xl text-blue-600 fas fa-check-circle"></i>
					<h3 class="text-xl font-medium">Accepted</h3>
				</div>
				<p class="mt-4 text-4xl font-medium">15</p>
			</div>
			<div class="p-6 transition-all transform rounded-lg shadow-lg hover:shadow-xl hover:scale-105">
				<div class="flex items-center gap-4">
					<i class="text-3xl text-blue-600 fas fa-clock"></i>
					<h3 class="text-xl font-medium">Pending</h3>
				</div>
				<p class="mt-4 text-4xl font-medium">5</p>
			</div>
			<div class="p-6 transition-all transform rounded-lg shadow-lg hover:shadow-xl hover:scale-105">
				<div class="flex items-center gap-4">
					<i class="text-3xl text-blue-600 fas fa-times-circle"></i>
					<h3 class="text-xl font-medium">Rejected</h3>
				</div>
				<p class="mt-4 text-4xl font-medium">5</p>
			</div>
		</section>

		<!-- Analytics Section -->
		<section class="p-8 mb-8 bg-white shadow-lg rounded-xl">
			<h3 class="flex items-center mb-6 text-2xl font-semibold text-gray-800">
				<i class="mr-3 text-blue-600 fa-solid fa-chart-line"></i> Analytics Overview
			</h3>
			<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
				<!-- Outpass Trends Chart -->
				<div class="p-6 rounded-lg shadow-md bg-gray-50">
					<h4 class="flex items-center text-lg font-medium text-gray-700">
						<i class="mr-2 text-blue-600 fa-solid fa-chart-pie"></i> Outpass Trends
					</h4>
					<div class="relative h-56 mt-4">
						<canvas id="outpassTrendsChart"></canvas>
					</div>
				</div>

				<!-- Approval Time Analysis -->
				<div class="p-6 rounded-lg shadow-md bg-gray-50">
					<h4 class="flex items-center text-lg font-medium text-gray-700">
						<i class="mr-2 text-blue-600 fa-solid fa-clock"></i> Approval Time Analysis
					</h4>
					<div class="relative h-56 mt-4">
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