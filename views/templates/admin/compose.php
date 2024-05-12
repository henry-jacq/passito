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