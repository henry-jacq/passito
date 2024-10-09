<style>
    .status-card {
        transition: transform 0.2s, border 0.2s;
        border: 2px solid transparent;
    }

    .status-card:hover {
        transform: scale(1.01);
        border: 1px solid #007bff;
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
        font-size: 1.15rem;
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
        <hr>
        <div id="currentStatusContainer" class="current-status">
            <!-- Example Approved Current Outpass -->
            <div class="card status-card shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <?= ucfirst($data['subject']) ?>
                        <span class="badge bg-<?php 
                        if ($data['status'] == 'Pending'): 
                            echo('warning bi-hourglass-split');
                        elseif ($data['status'] == 'Rejected'):
                            echo('danger bi-x-circle');
                        else:
                            echo('success bi-check2-circle');
                        endif;
                        ?> badge-status ms-auto"><span class="ms-2"><?= $data['status']?></span></span>
                    </h5>
                    <p class="card-subtitle">Your outpass request from <strong><?= $data['from_date'] ?></strong> to <strong><?= $data['to_date'] ?></strong> is <?= strtolower($data['status']) ?>.</p>
                    <p class="card-text">Destination: <?= $data['destination'] ?></p>
                    <p class="card-text">Time to reach college: <strong>0 hours</strong></p>
                    <a href="/pass/status/<?= $data['id'] ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Outpass Status Log Section -->
    <div class="mb-5">
        <h4 class="fw-normal fs-4 mb-3">Outpass Log</h4>
        <hr>
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