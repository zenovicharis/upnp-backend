$(document).ready(function () {
    //validate form for login
    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            }
        }
    });

    //validate form for creating news
    $("#createForm").validate({
        rules: {
            title: {
                required: true
            },
            content: {
                required: true
            },
            image: {
                required: true
            }
        }
    });

});