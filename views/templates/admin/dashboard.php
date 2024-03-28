<nav class="navbar sticky-top border-bottom shadow shadow-bottom navbar-expand-md bg-body-tertiary px-2" aria-label="Passito admin navbar">
    <div class="container-fluid">
        <a class="navbar-brand fs-4" href="/admin/dashboard">Passito</a>
        <button class="btn sidebar-toggler me-auto ms-md-5 d-md-none">
            <i class="bi bi-list-nested"></i>
        </button>
        <div class="dropdown ms-auto">
            <a class="text-body-emphasis" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside">
                <i class="bi bi-bell me-sm-4 me-4"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-0">
                <div class="card border-0 overflow-auto" style="width: 320px; height: 80%;">
                    <div class="card-header">
                        <p class="small text-uppercase fw-semibold m-0">Notifications</p>
                    </div>
                    <div class="card-body p-2">
                        <div class="list-group overflow-auto">
                            <a href="#" class="list-group-item list-group-item-action border-0 rounded mt-1 shadow-sm" aria-current="true">
                                <div class="d-flex w-100 justify-content-between">
                                    <p class="mb-1"><i class="bi bi-clock-history text-warning me-2"></i>Approve Request</p>
                                    <small>1 hour ago</small>
                                </div>
                                <p class="text-wrap small mb-1">Henry has Inititated outpass request!</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action border-0 rounded mt-1 shadow-sm" aria-current="true">
                                <div class="d-flex w-100 justify-content-between">
                                    <p class="mb-1"><i class="bi bi-clock-history text-warning me-2"></i>Approve Request</p>
                                    <small>6 hours ago</small>
                                </div>
                                <p class="text-wrap small mb-1">John has Inititated outpass request!</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action border-0 rounded mt-1 shadow-sm" aria-current="true">
                                <div class="d-flex w-100 justify-content-between">
                                    <p class="mb-1"><i class="bi bi-exclamation-circle text-danger me-2"></i>Pending approvals</p>
                                    <small>1 day ago</small>
                                </div>
                                <p class="text-wrap small mb-1">Validate the pending outpass requests!</p>
                            </a>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <a role="button" class="small">Clear all</a>
                            <a role="button" class="small">
                                <i class="bi bi-check2-all"></i>
                                Mark as read
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                <a href="/admin/dashboard" class="nav-link <?php if ($title == 'Dashboard') : echo ('active');
                                                            else : echo ('text-body');
                                                            endif; ?>" aria-current="page">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="/admin/manage/request" class="nav-link <?php if ($title == 'Manage Requests') : echo ('active');
                                                                else : echo ('text-body');
                                                                endif; ?>">
                    <i class="bi bi-file-text me-2"></i>
                    Manage Requests
                </a>
            </li>
            <li>
                <a href="/admin/manage/users" class="nav-link <?php if ($title == 'Manage Users') : echo ('active');
                                                                else : echo ('text-body');
                                                                endif; ?>">
                    <i class="bi bi-people me-2"></i>
                    User Management
                </a>
            </li>
            <li>
                <a href="/admin/analytics" class="nav-link <?php if ($title == 'Analytics') : echo ('active');
                                                            else : echo ('text-body');
                                                            endif; ?>">
                    <i class="bi bi-graph-up me-2"></i>
                    Reports and Analytics
                </a>
            </li>
            <li>
                <a href="/admin/settings" class="nav-link <?php if ($title == 'Settings') : echo ('active');
                                                            else : echo ('text-body');
                                                            endif; ?>">
                    <i class="bi bi-gear-wide-connected me-2"></i>
                    Settings
                </a>
            </li>
        </ul>
    </div>
    <div class="content-container">
        <div class="content">
            <h3 class="lead fs-3"><?= $title ?></h3>
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