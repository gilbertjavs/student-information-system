// Client-side interaction: delete confirmation + instant form validation

// 1. Confirm before deleting
document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (ev) {
        if (!window.confirm(form.getAttribute('data-confirm'))) ev.preventDefault();
    });
});

// 2. Validate the student form before it is sent (server still re-checks everything)
var studentForm = document.getElementById('student-form');
if (studentForm) {
    studentForm.addEventListener('submit', function (ev) {
        var firstBad = null;
        studentForm.querySelectorAll('input, select').forEach(function (field) {
            var ok = field.checkValidity();
            field.classList.toggle('invalid', !ok);
            if (!ok && !firstBad) firstBad = field;
        });
        if (firstBad) {
            ev.preventDefault();
            firstBad.focus();
            firstBad.reportValidity();
        }
    });
    studentForm.addEventListener('input', function (ev) {
        ev.target.classList.remove('invalid');
    });
}

// 3. Fade out the success message after a few seconds
var alertBox = document.querySelector('.alert-success');
if (alertBox) {
    setTimeout(function () { alertBox.classList.add('fade'); }, 3500);
}
