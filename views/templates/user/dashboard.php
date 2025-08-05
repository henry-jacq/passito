<div class="flex flex-col min-h-screen bg-gray-50">
	<!-- Header Section -->
	<?= $this->getComponent('user/header', [
		'routeName' => $routeName
	]) ?>

	<!-- Main Content -->
	<main class="container px-6 py-8 mx-auto lg:px-12">
		<!-- Welcome Section -->
		<section class="flex flex-col justify-between p-8 mb-8 border shadow-lg rounded-xl md:flex-row">
			<div>
				<h2 class="text-4xl font-bold text-gray-800">Welcome, <?= ucwords($userData->getUser()->getName()) ?>!</h2>
				<p class="mt-2 text-lg text-gray-800">It's <?= date("l, d F Y") ?></p>
			</div>
		</section>

		<!-- Overview Cards -->
		<section class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
			<div class="p-6 transition-all transform rounded-lg shadow-lg hover:shadow-xl">
				<div class="flex items-center gap-4">
					<i class="text-3xl text-blue-600 fas fa-file-alt"></i>
					<h3 class="text-xl font-medium">Total Requests</h3>
				</div>
				<p class="mt-4 text-4xl font-medium">25</p>
			</div>
			<div class="p-6 transition-all transform rounded-lg shadow-lg hover:shadow-xl">
				<div class="flex items-center gap-4">
					<i class="text-3xl text-blue-600 fas fa-check-circle"></i>
					<h3 class="text-xl font-medium">Accepted</h3>
				</div>
				<p class="mt-4 text-4xl font-medium">15</p>
			</div>
			<div class="p-6 transition-all transform rounded-lg shadow-lg hover:shadow-xl">
				<div class="flex items-center gap-4">
					<i class="text-3xl text-blue-600 fas fa-clock"></i>
					<h3 class="text-xl font-medium">Pending</h3>
				</div>
				<p class="mt-4 text-4xl font-medium">5</p>
			</div>
			<div class="p-6 transition-all transform rounded-lg shadow-lg hover:shadow-xl">
				<div class="flex items-center gap-4">
					<i class="text-3xl text-blue-600 fas fa-times-circle"></i>
					<h3 class="text-xl font-medium">Rejected</h3>
				</div>
				<p class="mt-4 text-4xl font-medium">5</p>
			</div>
		</section>
	</main>
</div>
