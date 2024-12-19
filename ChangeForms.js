document.addEventListener("DOMContentLoaded", function () {
    const authForm = document.getElementById("auth-form");
    const registerForm = document.getElementById("register-form");
    const showRegisterLink = document.getElementById("show-register");
    const showAuthLink = document.getElementById("show-auth");

    if (showRegisterLink && authForm && registerForm) {
        showRegisterLink.addEventListener("click", function (event) {
            event.preventDefault();
            authForm.style.display = "none";
            registerForm.style.display = "block";
        });
    }

    if (showAuthLink && authForm && registerForm) {
        showAuthLink.addEventListener("click", function (event) {
            event.preventDefault();
            registerForm.style.display = "none";
            authForm.style.display = "block";
        });
    }
});
