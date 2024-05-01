/* navnar View JS */
$(document).ready(function () {

    // LogIn觸發
    $('.navbar-view').on('click', '#navbar-login', function () {
        window.location.href = $(this).data().navbarurl;
    });

});