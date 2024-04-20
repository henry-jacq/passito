<div class="container mt-3">
    <nav class="navbar navbar-expand-lg bg-body-tertiary rounded" aria-label="Eleventh navbar example">
        <div class="container-fluid px-lg-3">
            <a class="navbar-brand link-body-emphasis" href="/">Passito</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#studentNavbar" aria-controls="studentNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="studentNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php if($title == 'Home'): echo('active'); endif; ?>" aria-current="page" href="/"><i class="bi bi-house-door-fill me-2"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if($title == 'Request'): echo('active'); endif; ?>" href="/request"><i class="bi bi-file-text me-2"></i>Request</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if($title == 'Status'): echo('active'); endif; ?>" href="#"><i class="bi bi-ui-checks me-2"></i>Status</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if($title == 'Inbox'): echo('active'); endif; ?>" href="#"><i class="bi bi-bell me-2"></i>Inbox</a>
                    </li>
                    <li class="ms-lg-2 nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person-circle me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil-square me-2"></i>Edit Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history me-2"></i>Outpass History</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>