function initAjaxFormBusqueda() {
  $("body").on("submit", ".ajaxFormBusqueda", function (e) {
    e.preventDefault();

    var buttonSubmit = "#" + $(this).attr("nombreSubmitButton");
    var nombreForm_body = "#" + $(this).attr("nombreForm_body");
    var nombreForm_error = "#" + $(this).attr("nombreForm_error");

    $(buttonSubmit).attr("disabled", true);

    $.ajax({
      type: $(this).attr("method"),
      url: $(this).attr("action"),
      data: $(this).serialize(),
    })
      .done(function (data) {
        if (typeof data.message !== "undefined") {
          //alert(data.message);
          $(nombreForm_body).html(data.form);
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        if (typeof jqXHR.responseJSON !== "undefined") {
          if (jqXHR.responseJSON.hasOwnProperty("form")) {
            $(nombreForm_body).html(jqXHR.responseJSON.form);
          }

          $(nombreForm_error).html(jqXHR.responseJSON.message);
        } else {
          alert(errorThrown);
        }
      });
  });
}

function initAjaxForm() {
  $("body").on("submit", ".ajaxForm", function (e) {
    e.preventDefault();
    //msgbox('Atencion');

    $("#ajaxFormSubmitButton").attr("disabled", true);

    $.ajax({
      type: $(this).attr("method"),
      url: $(this).attr("action"),
      data: $(this).serialize(),
    })
      .done(function (data) {
        if (typeof data.message !== "undefined") {
          //alert(data.message);
          $(".modal-generico").modal("hide");
          location.reload(); //refresh page
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        if (typeof jqXHR.responseJSON !== "undefined") {
          if (jqXHR.responseJSON.hasOwnProperty("form")) {
            $("#form_body").html(jqXHR.responseJSON.form);
          }

          $(".form_error").html(jqXHR.responseJSON.message);
        } else {
          alert(errorThrown);
        }
      });
  });
}

function exportToExcel(tableId) {
  var scroll = $(window).scrollTop();
  var $table = $("#" + tableId);
  var namefile = $table.attr("namefile");

  $table.table2excel({
    exclude: ".noExl",
    name: namefile,
    filename: namefile,
    fileext: ".xls",
  });

  $(window).scrollTop(scroll);
  return false;
}

$("body").on("keydown", "input, select", function (e) {
  var self = $(this),
    form = self.parents("form:eq(0)"),
    focusable,
    next;
  if (e.keyCode == 13) {
    focusable = form.find("input,a,select,button").filter(":visible");
    next = focusable.eq(focusable.index(this) + 1);
    if (next.length) {
      next.focus();
    } else {
      form.submit();
    }
    return false;
  }
});

//Prevent multiple submission
$("body").on("submit", "form", function () {
  $(this).submit(function () {
    return false;
  });
  return true;
});

//Loading Gif full screen
$(document).on({
  ajaxStart: function () {
    $("#loading").css("display", "block");
  },
  ajaxStop: function () {
    $("#loading").css("display", "none");
  },
});
