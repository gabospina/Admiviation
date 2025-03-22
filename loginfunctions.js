function displayNoty(type, text) {
    new Noty({
        layout: "top",
        type: type,
        text: text,
        timeout: 10000,
        killer: true
    }).show();
}

function logIn(form) {
    const $form = $(form);
    const username = $form.find("#user").val().trim();
    const password = $form.find("#password").val();

    if (!username || !password) {
        $("#loginError").text("Please enter your username and password.");
        return;
    }

    // Hash the password using SHA512
    // const hashedPassword = sha512(password);

    console.log("Username:", username);
    console.log(" Password:", password);

    $.ajax({
        type: "POST",
        url: "login.php",
        data: {
            username: username,  // Use "username" to match the PHP
            password: password,  // Use "password" to match the PHP
            // keepSignedIn: $form.find("[name='keepSignedIn']").is(":checked") ? "Yes" : "No"
        },
        dataType: "json",
        success: function (response) {
            console.log("Raw Response from login.php:", response);
            if (response.success) {
                // window.location.href = "/admviation/home.php";
                window.location.href = response.redirect;
            } else {
                $("#loginError").text(response.error || "Invalid username or password.");
            }
        },
        error: function (xhr) {
            console.error("AJAX Error:", xhr.responseText);
            displayNoty("error", "Login failed. Please try again.");
        }
    });
}


function signUp(form) {
    var companyName = $(form).find("#signup_companyName").val(),
        companyNationality = $(form).find("#signup_companyNationality").val(),
        fname = $(form).find("#signup_firstname").val(),
        lname = $(form).find("#signup_lastname").val(),
        userNationality = $(form).find("#signup_user-nationality").val(),
        email = $(form).find("#signup_email").val(),
        phone = $(form).find("#signup_phone").val(),
        username = $(form).find("#signup_username").val(),
        usernameValid = $(form).find("#signup_username").hasClass("input-success"),
        password = $(form).find("#signup_password").val(),
        confpassword = $(form).find("#signup_confpassword").val();

    console.log("companyName:", companyName);
    console.log("companyNationality:", companyNationality);
    console.log("fname:", fname);
    console.log("lname:", lname);
    console.log("email:", email);
    console.log("phone:", phone);
    console.log("username:", username);
    console.log("usernameValid:", usernameValid);
    console.log("password:", password);
    console.log("confpassword:", confpassword);

    if (companyName && companyNationality && fname && lname && email && username && password && password === confpassword) {
        if ($("#user-agreement-check").prop("checked")) {
            $.ajax({
                type: "POST",
                url: "signup.php",
                data: {
                    companyName: companyName,
                    companyNationality: companyNationality,
                    firstname: fname,
                    lastname: lname,
                    userNationality: userNationality,
                    email: email,
                    phone: phone,
                    username: username,
                    password: password,
                    ajax: true
                },
                success: function (response) {
                    console.log("Raw Response:", response);  // Add this
                    if (response.trim() === "success") {
                        // window.location.href = "home.php";
                        window.location.href = "/admviation/home.php";
                    } else {
                        displayNoty("error", response);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error (signup):", status, error, xhr.responseText);
                    displayNoty("error", "An error occurred during signup. Please try again.");
                }
            });
        } else {
            displayNoty("error", "Please read and agree to our terms of use.");
        }
    } else {
        displayNoty("error", "Please fill out all required fields and ensure the passwords match.");
    }
}


$(document).ready(function () {
    var error = window.location.search.substring(7);
    $("#loginError").text(error.replace(/%20/g, " "));

    $("#loginForm #password").keydown(function (e) {
        if (e.which === 13) {
            $("#loginBtn").trigger("click");
        }
    });

    $("#signUpForm #password").keyup(function () {
        $("#signUpForm #confpassword").removeClass("input-error");
    });

    $("#signUpForm #confpassword").keyup(function () {
        if ($(this).val() !== $("#signUpForm #password").val()) {
            $(this).addClass("input-error");
        } else {
            $(this).removeClass("input-error");
        }
    });

    $("#signUpForm #username").keyup(function () {
        const $this = $(this);
        const username = $this.val().trim();
        $this.val(username);

        if (username.length < 4) {
            $this.removeClass("input-success").addClass("input-error");
            return;
        }

        $.ajax({
            type: "POST",
            url: "check_username.php",
            data: {
                username: username,
                csrf_token: "<?php echo $_SESSION['csrf_token']; ?>"
            },
            dataType: "json",
            success: function (response) {
                // Handle response
            },
            error: function (xhr) {
                console.error("Error checking username:", xhr.responseText);
            }
        });
    })

});