/* login View JS */
$(document).ready(function () {
    var inputvalidator = new InputValidator();
    var sweatalert = new SweatAlert();

    //登入按鈕觸發
    $('.login-view').on('click', '#login-login', function () {
        var email = $("#login-email").val();
        var password = $("#login-password").val();

        // 驗證mail
        if (!inputvalidator.isValidEmail(email)) {
            inputvalidator.displayError('login-email', '', 'Invalid email format');
            return;
        }

        // 驗證密碼
        if (password === '') {
            inputvalidator.displayError('login-password', 'password');
            return;
        }

        $.ajax({
            url: $(this).data().logincontrollerauth,
            type: 'post',
            contentType: 'application/json; charset=utf-8',  // 指定傳送的資料類型為 JSON
            data: JSON.stringify({
                'email': email,
                'password': password,
                'checkMeOut': $("#login-checkMeOut").prop('checked')
            }),  // 將資料轉換為 JSON 字串
            dataType: 'json', // 指定預期的回應類型為 JSON
            success: function (res) {
                if (res.result === true) {
                    sweatalert.showNotification("Login !", "Welcome back! You have successfully logged in.", true, 1000);
                    setTimeout(function () {
                        window.location = $('#commonUrl-baseUrl').data('baseurl');
                    }, 1000);
                } else {
                    sweatalert.showNotification("Oops...", "Login failed. Please check your credentials.", false, 1000);
                }
            },
            error: function () {
                alert('Error submitting the form.');
            }
        });
    })
    inputvalidator.resetError('#login-email, #login-password');
});