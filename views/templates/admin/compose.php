<h3 class="lead fs-4">Compose Mail</h3>
<hr>
<!-- Form for composing email -->
<form id="composeForm" method="post">
    <?php if (isset($sent) && $sent == true): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check2-circle me-1"></i>
        <strong>Mail sent!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="subject" class="form-label">Subject</label>
        <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject">
    </div>
    <div class="mb-3">
        <label for="body" class="form-label">Body</label>
        <textarea class="form-control" id="body" rows="6" name="body" placeholder="Enter email body"></textarea>
    </div>
    <div class="mb-3">
        <label for="recipients" class="form-label">Recipient</label>
        <input type="text" class="form-control" id="recipients" name="recipient" placeholder="Enter recipient email">
        <div id="recipientsHelp" class="form-text">Enter a single email address.</div>
    </div>
    <div class="mb-3">
        <label for="attachments" class="form-label">Attachments</label>
        <input type="file" class="form-control" id="attachments" name="attachments" multiple>
        <div id="attachmentsHelp" class="form-text">Attach files (optional)</div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Send</button>
</form>

<script>
    function sendEmail() {
        var subject = document.getElementById("subject").value;
        var body = document.getElementById("body").value;
        var recipient = document.getElementById("recipients").value;
        var attachments = document.getElementById("attachments").files;
        // Other email sending logic goes here...
        // For demonstration, just log the email content
        console.log("Subject:", subject);
        console.log("Body:", body);
        console.log("Recipient:", recipient);
        console.log("Attachments:", attachments);
    }
</script>