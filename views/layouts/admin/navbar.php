<nav class="navbar sticky-top border-bottom shadow shadow-bottom navbar-expand-md bg-body px-2" aria-label="Passito admin navbar">
    <div class="container-fluid">
        <a class="navbar-brand link-body-emphasis fs-4" href="/admin/dashboard">Passito</a>
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