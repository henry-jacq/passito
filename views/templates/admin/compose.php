<h3 class="lead fs-4">Compose Mail</h3>
<hr>
<form id="composeForm" method="POST">
    <div class="mb-3">
        <label for="subject" class="form-label">Subject</label>
        <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject" required>
    </div>
    <div class="mb-3">
        <label for="body" class="form-label">Body</label>
        <textarea class="form-control" id="body" rows="6" name="body" placeholder="Enter email body" required></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Select Institution(s)</label>
        <br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="institution1" value="institution1">
            <label class="form-check-label" for="institution1">SSN</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="institution2" value="institution2">
            <label class="form-check-label" for="institution2">SNU</label>
        </div>
    </div>
    <div class="mb-3">
        <label for="recipients" class="form-label">Recipients</label>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="year1" value="year1">
            <label class="form-check-label" for="year1">1st Year Students</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="year2" value="year2">
            <label class="form-check-label" for="year2">2nd Year Students</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="year3" value="year3">
            <label class="form-check-label" for="year3">3rd Year Students</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="year4" value="year4">
            <label class="form-check-label" for="year4">4th Year Students</label>
        </div>
    </div>


    <div class="mb-3">
        <label for="recipients" class="form-label">Recipient</label>
        <input type="text" class="form-control" id="recipients" name="recipient" placeholder="Enter recipient email" required>
        <div id="recipientsHelp" class="form-text">Enter a single email address.</div>
    </div>
    <div class="mb-3">
        <label for="attachments" class="form-label">Attachments</label>
        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
        <div id="attachmentsHelp" class="form-text">Attach files (optional)</div>
    </div>
    <button type="submit" class="btn btn-prime btn-compose"><i class="bi bi-send me-2"></i>Send</button>
</form>