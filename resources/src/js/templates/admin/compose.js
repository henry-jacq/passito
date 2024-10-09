$('#composeForm').on('submit', function (e) {
    let form = $(this);
    e.preventDefault();
    var formData = new FormData(this);
    var composeBtn = $('.btn-compose');
    let spinner = `<div class="spinner-border spinner-border-sm me-2" role="status"></div>`;
    let successMsg = `<div class="alert alert-success alert-dismissible fade show" role="alert"><i class="bi bi-check2-circle me-2"></i><strong>Mail sent!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    let errorMsg = `<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="bi bi-exclamation-circle me-2"></i><strong>Mail cannot be sent!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;

    composeBtn.attr('disabled', true);
    composeBtn.html(spinner + 'Sending...')

    $.ajax({
        url: '/api/admin/sendmail',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status == true) {
                composeBtn.html(`<i class="bi bi-send me-2"></i>Send`)
                $(successMsg).insertBefore(form);
                composeBtn.attr('disabled', false);
                form[0].reset();
            }
        },
        error: function (xhr, status, error) {
            composeBtn.html(`<i class="bi bi-send me-2"></i>Send`)
            $(errorMsg).insertBefore(form);
            composeBtn.attr('disabled', false);
            form[0].reset();
        }
    });
});
