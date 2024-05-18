<div class="container my-5">
    <div class="row">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-normal fs-3">Inbox</h3>
            <div>
                <button id="markAllRead" class="btn btn-outline-secondary btn-sm me-2">Mark All as Read</button>
                <button id="fetchMore" class="btn btn-outline-primary btn-sm">Fetch More Notifications</button>
            </div>
        </div>
        <hr class="mb-4">
        <div class="col-md-3">
            <h5><i class="bi bi-sliders me-2"></i>Filter</h5>
            <div class="list-group list-group-flush py-3">
                <a href="#" class="list-group-item list-group-item-action active" aria-current="true">All Notifications</a>
                <a href="#" class="list-group-item list-group-item-action">Outpass Notifications</a>
                <a href="#" class="list-group-item list-group-item-action">Admin Notifications</a>
                <a href="#" class="list-group-item list-group-item-action">Others</a>
            </div>
        </div>
        <div class="col-md-9">

            <!-- Inbox Section -->
            <div id="inboxContainer" class="list-group">
                <!-- Example Unread Message Card -->
                <div class="list-group-item list-group-item-action flex-column align-items-start unread">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">New Message from Administration</h5>
                        <small><span class="badge bg-primary">Unread</span></small>
                    </div>
                    <p class="mb-1">Bus routes changed, please check the updated schedule.</p>
                    <small class="text-muted">Received on: 2024-05-18</small>
                </div>

                <!-- Example Read Message Card -->
                <div class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Outpass Approval</h5>
                        <small><span class="badge bg-secondary">Read</span></small>
                    </div>
                    <p class="mb-1">Your outpass request from 2024-04-08 to 2024-04-10 has been approved.</p>
                    <small class="text-muted">Received on: 2024-04-07</small>
                </div>

                <!-- Additional messages can be added in the same format -->
            </div>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('markAllRead').addEventListener('click', function() {
        const unreadBadges = document.querySelectorAll('.badge.bg-primary');
        unreadBadges.forEach(badge => {
            badge.classList.remove('bg-primary');
            badge.classList.add('bg-secondary');
            badge.textContent = 'Read';
            badge.closest('.list-group-item').classList.remove('unread');
        });
    });

    document.getElementById('fetchMore').addEventListener('click', function() {
        // Simulate fetching more messages
        const inboxContainer = document.getElementById('inboxContainer');
        const newMessageCard = `
            <div class="list-group-item list-group-item-action flex-column align-items-start unread">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">New Event Notification</h5>
                    <small><span class="badge bg-primary">Unread</span></small>
                </div>
                <p class="mb-1">You have been invited to the annual sports event.</p>
                <small class="text-muted">Received on: 2024-05-19</small>
            </div>`;
        inboxContainer.insertAdjacentHTML('beforeend', newMessageCard);
    });
</script>