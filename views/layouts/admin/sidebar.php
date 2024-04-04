<style>
    .nav-pills .nav-link {
        margin-bottom: .10rem;
    }

    .nav-pills .nav-link:not(.active):hover {
        background-color: var(--bs-tertiary-bg);
        transition: background-color 0.25s ease;
    }
</style>
<div class="sidebar mt-5 p-3 bg-body border-end">
    <p class="text-uppercase fw-semibold text-secondary small mb-2 pt-2">Admin Panel</p>
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a href="/admin/dashboard" class="nav-link rounded-4 <?php if ($title == 'Dashboard') : echo ('active'); else : echo ('text-body'); endif; ?>" aria-current="page">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/manage/request" class="nav-link rounded-4 <?php if ($title == 'Manage Requests') : echo ('active'); else : echo ('text-body'); endif; ?>">
                <i class="bi bi-file-text me-2"></i>
                Manage Requests
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link rounded-4 text-body">
                <i class="bi bi-bus-front me-2"></i>
                Manage Buses
            </a>
        </li>
        <li class="nav-item">
            <a href="#submenu2" data-bs-toggle="collapse" class="nav-link rounded-4 text-body" aria-expanded="false">
                <i class="bi bi-people me-2"></i>
                User Management
                <i class="bi bi-caret-down-fill small"></i>
            </a>
            <ul class="list-unstyled collapse ms-4 <?php if ($title == 'Manage Users' || $title == 'Student Details' || $title == 'Student Activities') : echo ('show'); endif; ?>" id="submenu2" data-bs-parent=".sidebar">
                <li><a href="/admin/manage/users" class="nav-link rounded-4 me-3 <?php if ($title == 'Manage Users') : echo ('active'); else : echo ('text-body'); endif; ?>"><i class="bi bi-people me-2"></i>Manage Users</a></li>
                <li><a href="/admin/student/details" class="nav-link rounded-4 me-3 <?php if ($title == 'Student Details') : echo ('active'); else : echo ('text-body'); endif; ?>"><i class="bi bi-journal-text me-2"></i>Student Details</a></li>
                <li><a href="/admin/student/activities" class="nav-link rounded-4 me-3 <?php if ($title == 'Student Activities') : echo ('active'); else : echo ('text-body'); endif; ?>"><i class="bi bi-person me-2"></i>Student Activities</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="/admin/analytics" class="nav-link rounded-4 <?php if ($title == 'Analytics') : echo ('active'); else : echo ('text-body'); endif; ?>">
                <i class="bi bi-graph-up me-2"></i>
                Reports & Analytics
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link rounded-4 text-body">
                <!-- <i class="bi bi-stickies me-2"></i> -->
                <i class="bi bi-envelope-at me-2"></i>
                Template Mail
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/create/announcements" class="nav-link rounded-4 <?php if ($title == 'Announcements') : echo ('active'); else : echo ('text-body'); endif; ?>">
                <i class="bi bi-megaphone me-2"></i>
                Announcements
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/settings" class="nav-link rounded-4 <?php if ($title == 'Settings') : echo ('active'); else : echo ('text-body'); endif; ?>">
                <i class="bi bi-gear-wide-connected me-2"></i>
                Passito Tweaks
            </a>
        </li>
    </ul>
</div>
