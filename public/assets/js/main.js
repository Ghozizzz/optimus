function checkAuth(callback = '') {
    if (email_login === '') {
        $("#popSelectLogin").modal('show');
        return false;
    } else {
        if (login_type !== 'customer') {
            alert('please sign in as customer to negotiate a car');
            return false;
        }
        if (callback === '') {
            return true;
        }
        if (typeof callback == 'function') {
            callback();
        } else {
            window.location = callback;
        }
    }
}

function resetPassword(email, type) {
    var requestData = {
        'email': email,
        'type': type,
    };
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': token,
        },
        url: reset_password_url,
        type: 'post',
        data: requestData,
        beforeSend: function(e) {},
        success: function(e) {
            alert(e.message);
        }
    });
}

function addWishlist(car_id) {
    var requestData = {
        'car_id': car_id,
    };
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': token,
        },
        url: wishlist_url,
        type: 'post',
        data: requestData,
        beforeSend: function(e) {},
        success: function(e) {
            // swal(e.message, {
            //     icon: "success",
            // });
            alert(e.message);
        }
    });
}

function signup() {
    if ($('#email').val() == '') {
        alert('email required');
        return false;
    }
    if ($('#password').val() == '') {
        alert('password required');
        return false;
    }
    if ($('#signup_country').val() == '') {
        alert('country required');
        return false;
    }
    var requestData = {
        'email': $('#email').val(),
        'name': $('#name').val(),
        'password': $('#password').val(),
        'phone_number': $('#phone_number').val(),
        'country': $('#signup_country').val()
    };
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': token,
        },
        url: signup_url,
        type: 'post',
        data: requestData,
        beforeSend: function(e) {
            $("#signupbtn").prop('disabled', true);
            $(".signup-customer-loader").show();
        },
        success: function(e) {
            $(".signup-customer-loader").hide();
            $("#signupbtn").prop('disabled', false);
            $('#email').val('');
            $('#name').val('');
            $('#phone_number').val('');
            if (e.error == 0) {
                alert(e.message);
            } else {
                alert(e.message);
            }
            $("#customer_modal").modal('hide');
        },
        error: function(e) {
            $(".signup-customer-loader").hide();
            $("#signupbtn").prop('disabled', false);
            alert(e.message);
        }
    });
}
$('#login_password').keypress(function(e) {
    var key = e.which;
    if (key == 13) /* the enter key code */ {
        $('#loginbtn').click();
        return false;
    }
});

function signupSeller() {
    var requestData = {
        'email': $('#email_seller').val(),
        'name': $('#name_seller').val(),
        'password': $('#password_seller').val(),
        'phone_number': $('#phone_number_seler').val()
    };
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': token,
        },
        url: signupseller_url,
        type: 'post',
        data: requestData,
        beforeSend: function(e) {
            $("#signupsellerbtn").prop('disabled', true);
            $(".signup-seller-loader").show();
        },
        success: function(e) {
            $(".signup-seller-loader").hide();
            $("#signupsellerbtn").prop('disabled', false);
            $('#email_seller').val('');
            $('#name_seller').val('');
            $('#phone_number_seler').val('');
            if (e.error == 0) {
                alert(e.message);
            } else {
                alert(e.message);
            }
            $("#seller_modal").modal('hide');
        },
        error: function(e) {
            $(".signup-customer-loader").hide();
            $("#signupsellerbtn").prop('disabled', false);
            alert(e.message);
        }
    });
}
$("#loginbtn").on('click', function() {
    var email = $("#login_email").val();
    var password = $("#login_password").val();
    var requestData = {
        'email': email,
        'password': password,
    };
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': token,
        },
        url: login_url,
        type: 'post',
        data: requestData,
        beforeSend: function(e) {
            $(this).attr('disabled', true);
        },
        success: function(e) {
            $(this).attr('disabled', false);
            if (e.error == 0) {
                $(".account-bar").html(e.icon);
                $("#customer_modal").modal('hide');
                email_login = e.email_login;
                login_type = e.login_type;
            } else {
                alert(e.message);
            }
        }
    });
});
$("#loginsellerbtn").on('click', function() {
    var email = $("#login_email_seller").val();
    var password = $("#login_password_seller").val();
    var requestData = {
        'email': email,
        'password': password,
    };
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': token,
        },
        url: loginseller_url,
        type: 'post',
        data: requestData,
        beforeSend: function(e) {
            $(this).attr('disabled', true);
        },
        success: function(e) {
            $(this).attr('disabled', false);
            if (e.error == 0) {
                $(".account-bar").html(e.icon);
                $("#seller_modal").modal('hide');
                email_login = e.email_login;
            } else {
                alert(e.message);
            }
        }
    });
});
$("#signupbtn").on('click', function() {
    var error = '';
    if ($('#email').val() === '') {
        alert('email can\'t empty');
        return false;
    } else if ($('#name').val() === '') {
        alert('Name can\'t empty');
        return false;
    } else if ($('#password').lengt < 6) {
        alert('Password must more than 6 character');
        return false;
    } else if ($('#password').val() === '') {
        alert('Password can\'t empty');
        return false;
    } else if ($('#password_confirmation').val() === '') {
        alert('Password confirmation can\'t empty');
        return false;
    } else if ($('#password').val() !== $('#password_confirmation').val()) {
        alert('Password is different');
        return false;
    }
    signup();
});
$("#signupsellerbtn").on('click', function() {
    var error = '';
    if ($('#email_seller').val() === '') {
        alert('email can\'t empty');
        return false;
    } else if ($('#name_seller').val() === '') {
        alert('Name can\'t empty');
        return false;
    } else if ($('#password_seller').lengt < 6) {
        alert('Password must more than 6 character');
        return false;
    } else if ($('#password_seller').val() === '') {
        alert('Password can\'t empty');
        return false;
    } else if ($('#password_confirmation_seller').val() === '') {
        alert('Password confirmation can\'t empty');
        return false;
    } else if ($('#password_seller').val() !== $('#password_confirmation_seller').val()) {
        alert('Password is different');
        return false;
    }
    signupSeller();
});