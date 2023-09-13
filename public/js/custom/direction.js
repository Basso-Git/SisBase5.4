let preventDblClick;

$(document).ready(function () {
  preventDblClick = 0;
  getAllDirections();
  paintSelectedRow("directionsTable");
});

//Esta funcion no la puedo sacar de acá es personal de cada uno
function getAllDirections() {
  let table = $("#directionsTable");

  $(table).DataTable({
    destroy: true,
    language: {
      url: baseUrl + "/json/spanishDatatable.json",
    },
    order: [[0, "desc"]],
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    ajax: {
      url: baseUrl + "direction/get_all",
      dataSrc: "data",
    },
    columns: [
      {
        data: "ID",
        type: "num",
      },
      {
        data: "DESCRIPCION",
      },
      {
        data: "FECHA_M",
        render: function (data, type, row, meta) {
          if (row["FECHA_M"] !== null) {
            var dateFormat = formatDate(row["FECHA_M"]);
            return dateFormat;
          } else {
            return "";
          }
        },
      },
      {
        data: "",
        defaultContent: "",
        sClass: "actions noExl text-center",
        render: function (data, type, row) {
          return renderActionButton(
            row,
            "direction/get/",
            "saveDirectionModal",
            "directionsTable"
          );
        },
      },
    ],
    createdRow: function (row, data, dataIndex) {
      $(row).addClass("text-center");
    },
    initComplete: function () {
      filterFooterText(this.api().columns());
      $("#spinner-div").hide();
      $(".table-container").css("display", "block");
    },
  });
}

//Esto escucha cuando se cierra algun modal en la pagina direccion y vuelvo el input hidden al value "create"
$(".modal").on("hidden.bs.modal", function () {
  backToModeCreateModal("saveDirectionModal");
});

$("#saveDirectionForm").submit(function (event) {
  event.preventDefault();

  //Con esto busco el input hidden que me determina si es create o edit
  let inputs = $("#saveDirectionForm :input");
  let mode = inputs.first().val();

  if (preventDblClick == 0) {
    let form = $("#saveDirectionForm");
    let tableName = "directionsTable";
    let modal = $("#saveDirectionModal");
    ajaxSubmitForm(mode, form, tableName, modal);
    preventDblClick++;
  }
});