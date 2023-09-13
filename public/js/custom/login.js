$(document).ready(function () {
  $("#formLogin").on("submit", function(e) {
    e.preventDefault();
    let username = $("#inputUser").val();
    let password = $("#inputPassword").val();
    let data = {
      username: username,
      password: password,
    };
    $.ajax({
      type: "POST",
      url: "./login_post",
      data: data,
      success: function (res) {
        if (res.status == "success") {
          //Lo redirijo a la homepage
          window.location.href = baseUrl;
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Verifique usuario y contraseña!",
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Verifique usuario y contraseña!",
        });
      },
    });
  });
});


