<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-normal">Outpass Details</h3>
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-download me-2"></i>Download</button>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Request Details</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label"><strong>From Date:</strong></label>
                    <p><?= $data['from_date'] ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><strong>To Date:</strong></label>
                    <p><?= $data['to_date'] ?></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label"><strong>From Time:</strong></label>
                    <p><?= $data['from_time'] ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><strong>To Time:</strong></label>
                    <p><?= $data['to_time'] ?></p>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Pass Type:</strong></label>
                <p><?= ucfirst($data['pass_type']) ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Destination:</strong></label>
                <p><?= ucfirst($data['destination']) ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Subject:</strong></label>
                <p><?= ucfirst($data['subject']) ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Purpose:</strong></label>
                <p><?= ucfirst($data['purpose']) ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Attachments:</strong></label>
                <ul class="list-unstyled">
                    <li><a href="#" class="link-primary">Attachment 1</a></li>
                </ul>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Status:</strong></label>
                <p class="text-warning"><?= $data['status'] ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Warden Approval Time:</strong></label>
                <p>-</p>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Created on:</strong></label>
                <p><?= $data['created_at'] ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label">
                    <strong>Warden Remarks:</strong>
                </label>
                <p>- <?= $data['remarks'] ?></p>
            </div>
        </div>
    </div>
</div>