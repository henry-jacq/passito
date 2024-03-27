<style>
    /* Custom CSS for fixed sidebar and scrollable content */
    .sidebar {
        position: fixed;
        top: 0;
        left: -280px;
        /* Initially hidden off-screen */
        bottom: 0;
        z-index: 100;
        margin-top: 59px;
        overflow-y: auto;
        width: 280px;
        transition: left 0.5s ease;
        /* Smooth transition for sliding effect */
    }

    .content {
        padding: 20px;
        padding-top: 70px;
        transition: margin-left 0.5s ease;
        /* Smooth transition for content */
    }

    @media (min-width: 768px) {
        .sidebar {
            left: 0;
            /* Always visible on medium and larger screens */
        }

        .content {
            margin-left: 280px;
            /* Adjust content margin for sidebar */
            transition: none;
            /* No transition for content on larger screens */
        }
    }
</style>
<div class="d-flex">
    <nav class="navbar fixed-top border-bottom navbar-expand-md bg-body-tertiary px-2" aria-label="Fourth navbar example">
        <div class="container-fluid">
            <a class="navbar-brand fs-4" href="#">Passito</a>
            <button class="btn sidebar-toggler me-auto ms-md-5">
                <i class="bi bi-list-nested"></i>
            </button>
            <a role="button" class="text-body-emphasis" href="#">
                <i class="bi bi-box-arrow-left me-md-1"></i>
                <span class="d-none d-md-inline-block">Logout</span>
            </a>
        </div>
    </nav>
    <div class="sidebar p-3 border-end bg-body-tertiary text-white">
        <p class="text-uppercase fw-semibold text-secondary small mb-2 pt-2">Admin Panel</p>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="#" class="nav-link active" aria-current="page">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-file-text me-2"></i>
                    Manage Requests
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-people me-2"></i>
                    User Management
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-graph-up me-2"></i>
                    Reports and Analytics
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-gear-wide-connected me-2"></i>
                    Settings
                </a>
            </li>
        </ul>
    </div>
    <div class="content">
        <h3>Dashboard</h3>
        <div class="p-5 bg-body-tertiary shadow rounded-3">
            <h3 class="text-body-emphasis mb-3">Hello Admin! 👋</h3>
            <p class="">Welcome to Passito! <br> Begin gatepass request approvals now!</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var toggler = document.querySelector(".sidebar-toggler");
        var sidebar = document.querySelector(".sidebar");
        var content = document.querySelector(".content");

        toggler.addEventListener("click", function() {
            if (window.innerWidth < 768) { // For screens below 768px (sm and below)
                sidebar.style.left = (sidebar.style.left === '0px' ? '-280px' : '0px');
            }
        });
    });
</script>