$(document).ready(function () {
  const togglePasswordIcon = document.getElementById("togglePassword");
  const passwordInput = document.getElementById("inputPassword");

  togglePasswordIcon.addEventListener("click", function () {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      togglePasswordIcon.classList.remove("fa-eye-slash");
      togglePasswordIcon.classList.add("fa-eye");
    } else {
      passwordInput.type = "password";
      togglePasswordIcon.classList.remove("fa-eye");
      togglePasswordIcon.classList.add("fa-eye-slash");
    }
  });
});
