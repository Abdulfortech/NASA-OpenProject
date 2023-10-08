
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ConnectX | Forgot Password</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Forgot Password</h1>
                                    </div>
                                    <div id="message" class="mt-2 alert" style="display: none"></div>
                                    <form class="user" id="login-form">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address...">
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block" type="submit" id="submitButton">
                                            Send a Link
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forget-password">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="signup">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- End of Page Wrapper -->


    <!-- Bootstrap core JavaScript-->
    <script href="../assets/vendor/jquery/jquery.min.js"></script>
    <script href="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script href="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script href="../assets/js/sb-admin-2.min.js"></script>
    <!-- Page level custom scripts -->
    <script href="../assets/js/login.js"></script>
    <script>
        
document.getElementById("login-form").addEventListener("submit", function (event) {
    event.preventDefault();

    // Get form values
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var rememberMe = document.getElementById("customCheck1").checked;
    const message = document.getElementById("message");


    // Validate form fields
    var isValid = true;
    if (email.trim() === "") {
        document.getElementById("email").classList.add("is-invalid");
        document.getElementById("emailError").textContent = "Please enter a valid email address.";
        isValid = false;
    } else {
        // document.getElementById("email").classList.remove("is-invalid");
        // document.getElementById("emailError").textContent = "";
    }

    if (password.trim() === "") {
        document.getElementById("password").classList.add("is-invalid");
        document.getElementById("passwordError").textContent = "Please enter a password.";
        isValid = false;
    } else {
        document.getElementById("password").classList.remove("is-invalid");
        // document.getElementById("passwordError").textContent = "";
    }

    if (!isValid) {
        return; // Exit the function if form fields are not valid
    }
    // Disable the submit button
    var submitButton = document.getElementById("submitButton");
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

    // Prepare the form data to be sent to PHP
    var formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);
    formData.append("rememberMe", rememberMe);


    // Perform form validation or other operations if needed

    fetch("../classes/Login.php?f=user_login", {
        method: "POST",
        body: formData,
    })
        .then(function (response) {
            // Handle the response from the server
            if (response.ok) {
                // Request successful
                return response.json();
            } else {
                console.error("Form submission failed");
            }
        })
        .then(data => {
            if (data.success) {
                message.style.display = "block";
                message.classList.remove("alert-danger");
                message.classList.add("alert-success");
                message.innerHTML = '<div class="d-flex align-items-center"><svg class="bi flex-shrink-0 me-2" width="24" height="24"><use xlink:href="#check-circle-fill"/></svg><span>' + data.message + '</span></div>';
                setTimeout(function () {
                    window.location.href = '../app/';
                }, 1000);

            } else {
                message.style.display = "block";
                message.classList.add("alert-danger");
                message.innerHTML = '<div class="d-flex align-items-center"><svg class="bi flex-shrink-0 me-2" width="24" height="24"><use xlink:href="#exclamation-circle-fill"/></svg><span class="text-danger">' + data.message + '</span></div>';

            }

            console.log(data);
        })
        .catch(function (error) {
            // Handle any errors during form submission
            console.error("Error:", error);
        })
        .finally(function () {
            // Enable the submit button and reset its text
            submitButton.disabled = false;
            submitButton.innerHTML = "Login";
        });
});

    </script>

</body>

</html>