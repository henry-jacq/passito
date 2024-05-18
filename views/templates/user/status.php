<style>
    .status-card {
        transition: transform 0.2s, border 0.2s;
        border: 2px solid transparent;
    }

    .status-card:hover {
        transform: scale(1.01);
        border: 2px solid #007bff;
    }

    .status-icon {
        font-size: 3rem;
    }

    .card-title,
    .card-subtitle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .card-subtitle {
        font-size: 1.25rem;
    }

    .badge-status {
        font-size: 1rem;
    }
</style>

<div class="container my-5">
    <!-- <h3 class="fw-normal fs-3 mb-3">Outpass Status</h3>
    <hr> -->

    <!-- Current Outpass Status Section -->
    <div class="mb-5">
        <h4 class="fw-normal fs-4 mb-3">Current Status</h4>
        <div id="currentStatusContainer" class="current-status">
            <!-- Example Approved Current Outpass -->
            <div class="card status-card shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        Family Function
                        <span class="badge bg-success badge-status ms-auto"><i class="bi bi-check2-circle me-2"></i>Approved</span>
                    </h5>
                    <p class="card-subtitle">Your outpass request from <strong>2024-04-08</strong> to <strong>2024-04-10</strong> has been approved.</p>
                    <p class="card-text">Destination: 123 Main St, City</p>
                    <p class="card-text">Time to reach college: <strong>3 hours</strong></p>
                    <a href="/pass/status/id" class="stretched-link"></a>
                </div>
            </div>

            <!-- Example Pending Current Outpass -->
            <div class="card status-card shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        Medical Checkup
                        <span class="badge bg-warning badge-status ms-auto"><i class="bi bi-hourglass-split me-2"></i>Pending</span>
                    </h5>
                    <p class="card-subtitle">Your outpass request from <strong>2024-05-10</strong> to <strong>2024-05-12</strong> is pending approval.</p>
                    <p class="card-text">Destination: City Hospital</p>
                    <a href="/pass/status/id" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Outpass Status Log Section -->
    <div class="mb-5">
        <h4 class="fw-normal fs-4 mb-3">Outpass Log</h4>
        <div id="statusLogContainer" class="status-log">
            <!-- Example Approved Outpass Log -->
            <div class="card status-card shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        Family Function
                        <span class="badge bg-success badge-status ms-auto"><i class="bi bi-check2-circle me-2"></i>Approved</span>
                    </h5>
                    <p class="card-subtitle">Your outpass request from <strong>2024-04-08</strong> to <strong>2024-04-10</strong> was approved.</p>
                    <p class="card-text">Destination: 123 Main St, City</p>
                    <a href="/pass/status/id" class="stretched-link"></a>
                </div>
            </div>

            <!-- Example Pending Outpass Log -->
            <div class="card status-card shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        Medical Checkup
                        <span class="badge bg-danger badge-status ms-auto"><i class="bi bi-x-circle me-2"></i>Rejected</span>
                    </h5>
                    <p class="card-subtitle">Your outpass request from <strong>2024-05-10</strong> to <strong>2024-05-12</strong> is pending approval.</p>
                    <p class="card-text">Destination: City Hospital</p>
                    <a href="/pass/status/id" class="stretched-link"></a>
                </div>
            </div>

            <!-- Example Rejected Outpass Log -->
            <div class="card status-card shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        Personal
                        <span class="badge bg-danger badge-status ms-auto"><i class="bi bi-x-circle me-2"></i>Rejected</span>
                    </h5>
                    <p class="card-subtitle">Your outpass request from <strong>2024-06-01</strong> to <strong>2024-06-03</strong> was rejected.</p>
                    <p class="card-text">Destination: Beach Resort</p>
                    <a href="/pass/status/id" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
</div>