$('#outpassForm').on('submit', function (e) {
    let form = $(this);
    e.preventDefault();
    var formData = new FormData(this);
    var requestBtn = $('.btn-request');
    let spinner = `<div class="spinner-border spinner-border-sm me-2" role="status"></div>`;
    let successMsg = `<div class="alert alert-success alert-dismissible fade show" role="alert"><i class="bi bi-check2-circle me-2"></i><strong>Outpass Created!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    let errorMsg = `<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="bi bi-exclamation-circle me-2"></i><strong>Outpass cannot be sent!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;

    requestBtn.attr('disabled', true);
    requestBtn.html(spinner + 'Sending...')

    $.ajax({
        url: '/api/outpass/create',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status == true) {
                requestBtn.html(`<i class="bi bi-send me-2"></i>Submit`)
                $(successMsg).insertBefore(form);
                requestBtn.attr('disabled', false);
                form[0].reset();
            }
        },
        error: function (xhr, status, error) {
            requestBtn.html(`<i class="bi bi-send me-2"></i>Submit`)
            $(errorMsg).insertBefore(form);
            requestBtn.attr('disabled', false);
        }
    });
});
