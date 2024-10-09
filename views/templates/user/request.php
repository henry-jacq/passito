<div class="my-4">
    <div class="p-5 bg-body-tertiary shadow rounded-3">
        <h4 class="text-body-emphasis mb-4">Request Outpass</h4>
        <form id="outpassForm" method="POST">
            <!-- Outpass Details Section -->
            <div class="mb-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fromDate" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="fromDate" name="from_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fromTime" class="form-label">From Time</label>
                        <input type="time" class="form-control" id="fromTime" name="from_time" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="toDate" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="toDate" name="to_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="toTime" class="form-label">To Time</label>
                        <input type="time" class="form-control" id="toTime" name="to_time" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="passType" class="form-label">Type of Pass</label>
                    <select class="form-select" id="passType" name="pass_type" required>
                        <option value="home">Home Pass</option>
                        <option value="outing">Outing Pass</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="destination" class="form-label">Destination</label>
                    <input type="text" class="form-control" id="destination" name="destination" placeholder="Enter the place you are visiting">
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter the subject">
                    <div class="form-text">Keep it short and crisp.</div>
                </div>
                <div class="mb-3">
                    <label for="purpose" class="form-label">Purpose/Reason</label>
                    <textarea class="form-control" id="purpose" rows="3" name="purpose" placeholder="Enter the purpose or reason" required></textarea>
                    <div class="form-text">Outpass will be issued if the reason is valid.</div>
                </div>
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments</label>
                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple="">
                    <div id="attachmentsHelp" class="form-text">Add required documents (optional)</div>
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-request btn-primary"><i class="bi bi-send me-2"></i>Submit</button>
            </div>
        </form>
    </div>
</div>