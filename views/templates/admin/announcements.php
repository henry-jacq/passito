<h3 class="lead fs-4">Create Announcement</h3>
<hr>
<div class="row justify-content-center">
    <form>
        <div class="form-group mb-3">
            <label for="announcementTitle" class="form-label">Title</label>
            <input type="text" class="form-control" id="announcementTitle" placeholder="Enter title" required>
        </div>
        <div class="form-group mb-3">
            <label for="announcementContent" class="form-label">Content</label>
            <div class="card">
                <div class="card-header p-2">
                    <button type="button" class="btn btn-sm border focus-ring focus-ring-prime"><i class="bi bi-type-bold fs-5"></i></button>
                    <button type="button" class="btn btn-sm border focus-ring focus-ring-prime"><i class="bi bi-type-italic fs-5"></i></button>
                    <button type="button" class="btn btn-sm border focus-ring focus-ring-prime"><i class="bi bi-type-underline fs-5"></i></button>
                </div>
                <div class="card-body p-1">
                    <textarea class="form-control rounded-0 border-0 px-2 shadow-none" id="announcementContent" rows="6" placeholder="Enter announcement content" required></textarea>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-prime btn-block">Create</button>
    </form>
</div>