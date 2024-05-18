<div class="my-4">
    <div class="p-5 bg-body-tertiary shadow rounded-3">
        <h3 class="text-body-emphasis mb-4">Request Outpass</h3>
        <form id="outpassForm">
            <!-- Student Information Section -->
            <div class="mb-4">
                <h5 class="fw-bold">Student Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="studentName" class="form-label">Student Name</label>
                        <input type="text" class="form-control disabled" id="studentName" value="John Doe" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="studentID" class="form-label">Student ID</label>
                        <input type="text" class="form-control disabled" id="studentID" value="2212023" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phoneNo" class="form-label">Phone Number</label>
                        <input type="text" class="form-control disabled" id="phoneNo" value="1234567890" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="homeAddress" class="form-label">Home Address</label>
                        <input type="text" class="form-control disabled" id="homeAddress" value="123 Main St, City" readonly>
                    </div>
                </div>
            </div>

            <!-- Outpass Details Section -->
            <div class="mb-4">
                <h5 class="fw-bold">Outpass Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fromDate" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="fromDate" name="from_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="toDate" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="toDate" name="to_date" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fromTime" class="form-label">From Time</label>
                        <input type="time" class="form-control" id="fromTime" name="from_time" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="toTime" class="form-label">To Time</label>
                        <input type="time" class="form-control" id="toTime" name="to_time" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="passType" class="form-label">Type of Pass</label>
                    <select class="form-select" id="passType" name="pass_type" required onchange="toggleDestinationInput()">
                        <option value="home">Home Pass</option>
                        <option value="outing">Outing Pass</option>
                    </select>
                </div>
                <div class="mb-3" id="destinationField" style="display: none;">
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

            <!-- Submit Button -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleDestinationInput() {
        const passType = document.getElementById('passType').value;
        const destinationField = document.getElementById('destinationField');
        const homeAddress = document.getElementById('homeAddress').value;

        if (passType === 'outing') {
            destinationField.style.display = 'block';
            document.getElementById('destination').value = '';
        } else {
            destinationField.style.display = 'none';
            document.getElementById('destination').value = homeAddress;
        }
    }

    document.getElementById('outpassForm').addEventListener('submit', function(event) {
        event.preventDefault();
        // Collect form data and submit
        const formData = new FormData(event.target);
        console.log(Object.fromEntries(formData.entries()));
    });

    // Initialize the destination field based on the selected pass type
    toggleDestinationInput();
</script>