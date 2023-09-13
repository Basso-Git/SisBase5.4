let preventDblClick;

$(document).ready(function () {
  preventDblClick = 0;
  getAllAreas();
  paintSelectedRow("areaTable");
});

//Esta funcion no la puedo sacar de acá es personal de cada uno
function getAllAreas() {
  let table = $("#areaTable");

  $(table).DataTable({
    destroy: true,
    language: {
      url: baseUrl + "/json/spanishDatatable.json",
    },
    order: [[0, "desc"]],
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    ajax: {
      url: baseUrl + "area/get_all",
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
        data: "DESCRIPCION_DIRECCION",
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
            "area/get/",
            "saveAreaModal",
            "areaTable"
          );
        },
      },
    ],
    createdRow: function (row, data, dataIndex) {
      $(row).addClass("text-center");
    },
    initComplete: function () {
      filterFooterText(this.api(), [0, 1, 3]);
      filterFooterSelect(this.api(), [2]);
      $("#spinner-div").hide();
      $(".table-container").css("display", "block");
    },
  });
}

//Esto escucha cuando se cierra algun modal en la pagina direccion y vuelvo el input hidden al value "create"
$(".modal").on("hidden.bs.modal", function () {
  backToModeCreateModal("saveAreaModal");
});

$("#saveAreaForm").submit(function (event) {
  event.preventDefault();

  //Con esto busco el input hidden que me determina si es create o edit
  let inputs = $("#saveAreaForm :input");
  let mode = inputs.first().val();

  if (preventDblClick == 0) {
    let form = $("#saveAreaForm");
    let tableName = "areaTable";
    let modal = $("#saveAreaModal");
    ajaxSubmitForm(mode, form, tableName, modal);
    preventDblClick++;
  }
});