/* register View JS */
$(document).ready(function () {
    var inputvalidator = new InputValidator();

    // 註冊按鈕觸發
    $('.register-view').on('click', '#register-register', function () {
        var email = $("#register-email").val();
        var password = $("#register-password").val();

        // 驗證mail
        if (!inputvalidator.isValidEmail(email)) {
            inputvalidator.displayError('register-email', '', 'Invalid email format');
            return;
        }

        // 驗證密碼
        if (password === '' || password.length < 6) {
            inputvalidator.displayError('register-password', '', 'The password must be longer than 5 characters.');
            return;
        }

        $.ajax({
            url: $(this).data().registercontrollersignup,
            type: 'post',
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify({
                'email': email,
                'password': password
            }),
            dataType: 'json',
            success: function (res) {
                if (res.result === true) {
                    console.log('success');
                }
            },
            error: function () {
                alert('Error submitting the form.');
            }
        });
    });
    inputvalidator.resetError("#register-email, #register-password");
});