<style>
    body {
        overflow: hidden;
    }

    .sidebar {
        position: fixed;
        top: 12px;
        left: -280px;
        bottom: 0;
        z-index: 100;
        overflow-y: auto;
        width: 280px;
        transition: left 0.5s ease;
    }

    .content {
        padding: 20px;
        overflow-y: auto;
        max-height: calc(100vh - 59px);
    }

    .content-container {
        flex-grow: 1;
        overflow-y: auto;
        margin-left: 0;
        transition: margin-left 0.5s ease;
    }

    @media (min-width: 768px) {
        .sidebar {
            left: 0;
        }

        .content-container {
            margin-left: 280px;
            transition: margin-left 0.5s ease;
        }
    }
</style>

<nav class="navbar sticky-top border-bottom shadow shadow-bottom navbar-expand-md bg-body-tertiary px-2" aria-label="Passito admin navbar">
    <div class="container-fluid">
        <a class="navbar-brand fs-4" href="/admin">Passito</a>
        <button class="btn sidebar-toggler me-auto ms-md-5">
            <i class="bi bi-list-nested"></i>
        </button>
        <a role="button" class="text-body-emphasis" href="#">
            <i class="bi bi-box-arrow-left me-md-1"></i>
            <span class="d-none d-md-inline-block">Logout</span>
        </a>
    </div>
</nav>
<div class="d-flex">
    <div class="sidebar mt-5 p-3 bg-body border-end">
        <p class="text-uppercase fw-semibold text-secondary small mb-2 pt-2">Admin Panel</p>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="#" class="nav-link active" aria-current="page">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-body">
                    <i class="bi bi-file-text me-2"></i>
                    Manage Requests
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-body">
                    <i class="bi bi-people me-2"></i>
                    User Management
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-body">
                    <i class="bi bi-graph-up me-2"></i>
                    Reports and Analytics
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-body">
                    <i class="bi bi-gear-wide-connected me-2"></i>
                    Settings
                </a>
            </li>
        </ul>
    </div>
    <div class="content-container">
        <div class="content">
            <h3 class="lead fs-3">Dashboard</h3>
            <hr>
            <div class="p-5 bg-body-tertiary shadow rounded-3">
                <h3 class="text-body-emphasis mb-3">Hello Admin! 👋</h3>
                <p>Welcome to Passito! Begin gatepass request approvals now!</p>
            </div>
            <div class="p-5 mt-3 bg-body-tertiary shadow rounded-3">
                <h3 class="text-body-emphasis mb-3">Description</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde, exercitationem tempora quis harum eaque ea dolor dolore odit aut architecto error aspernatur fuga veniam facere at consectetur explicabo porro fugiat. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Placeat cupiditate nisi totam numquam odio. Perspiciatis ea, eius ducimus, id eaque nostrum dolorem non expedita sapiente, necessitatibus delectus quibusdam aperiam nesciunt. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quis in eveniet nulla delectus? Facere culpa perferendis magni numquam, dicta voluptatum blanditiis quas tempora quibusdam sequi, ratione veniam ea quisquam dolores! Lorem ipsum dolor sit amet consectetur adipisicing elit. Facere obcaecati temporibus cupiditate harum dignissimos eligendi cum vero maxime recusandae. Minima vitae dolore quibusdam ipsa aliquid earum nostrum nemo veritatis quas! Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum odio obcaecati tempore laudantium, similique optio in ut! Recusandae nam architecto sed officia ullam repellendus eveniet sit hic libero? Doloremque, totam? Lorem ipsum dolor sit amet consectetur adipisicing elit. Id blanditiis ipsam praesentium unde, voluptatum eum laudantium corporis! Nobis, quam nam nulla, dolorum molestias eaque architecto dolores saepe, dignissimos pariatur amet. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Deleniti quis iusto ullam consequuntur magnam, excepturi ducimus tempore aliquam beatae recusandae perferendis debitis obcaecati! Ducimus laboriosam distinctio modi alias ipsum quasi!</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var toggler = document.querySelector(".sidebar-toggler");
        var sidebar = document.querySelector(".sidebar");
        var contentContainer = document.querySelector(".content-container");

        // Function to handle sidebar toggle
        function toggleSidebar() {
            if (!sidebar.getAttribute('style')) {
                console.log(window.innerWidth);
                if (window.innerWidth >= 768) {
                    sidebar.style.left = '-280px';
                } else {
                    sidebar.style.left = '0px';
                }
            } else {
                sidebar.style.left = (sidebar.style.left === '0px' ? '-280px' : '0px');
            }
        }

        // Event listener for sidebar toggler
        toggler.addEventListener("click", function() {
            toggleSidebar();
        });

        // Event listener for window resize
        window.addEventListener("resize", function() {
            if (sidebar.getAttribute('style')) {
                sidebar.style = null;
            }
        });
    });
</script>