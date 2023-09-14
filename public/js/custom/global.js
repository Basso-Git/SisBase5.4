const host = window.location.host;
const project = window.location.pathname;
const baseUrl = `http://${host}${project}`;

function ajaxSubmitForm(mode, form, table, modal) {
  let url = mode == "create" ? "create" : "edit/" + $("#id").val();
  let typeAjax = mode == "create" ? "POST" : "PUT";

  $.ajax({
    url: url,
    type: typeAjax,
    data: form.serialize(),
    success: function (res) {
      if (res.status == "success") {
        alertResponse(mode);
        refreshTableData(table, mode, res.data);
      } else {
        alertResponse(mode, true);
      }
    },
    error: function (xhr, status, error) {
      alertResponse(mode, true);
    },
  });

  modal.modal("hide");
}

function alertResponse(typeCrud, error = false) {
  let title = "";
  if (!error) {
    title =
      typeCrud == "create"
        ? "Se cargó correctamente"
        : "Se editó correctamente";
  } else {
    title =
      typeCrud == "create"
        ? "Ocurrió un error al cargar el registro"
        : "Ocurrió un error al editar el registro";
  }

  //Si llega un error le cambio el iconAlert a error
  let iconAlert = !error ? "success" : "error";

  Swal.fire({
    icon: iconAlert,
    title: title,
    showConfirmButton: false,
    showCloseButton: true,
    timer: 1200,
  });

  preventDblClick = 0;
}

function backToModeCreateModal(modalId) {
  let modal = document.getElementById(modalId);
  let modeInput = modal.querySelector('input[name="mode"]');
  if (modeInput) {
    modeInput.value = "create";
  }
  clearModalInputs(modal);
}

function clearModalInputs(modal) {
  let excludedInputNames = ["dateM", "userM", "mode"]; // Nombres de los inputs que no se deben limpiar
  let inputs = modal.querySelectorAll("input");
  let selects = modal.querySelectorAll("select");

  inputs.forEach(function (input) {
    if (!excludedInputNames.includes(input.name)) {
      input.value = "";
    }
  });

  selects.forEach(function (select) {
    select.value = "";
  });
}

function getRegister(id, url, modal) {
  $.ajax({
    url: baseUrl + url + id,
    type: "GET",
    success: function (res) {
      for (let key in res.data) {
        if (res.data.hasOwnProperty(key)) {
          if (key === "mode" || key === "id") {
            continue; // Ignorar los campos "mode" y "id"
          }

          setValueToForm(res.data, key);
        }
      }

      //Acá cambio el valor del input hidden MODE a EDITAR, ya que a esta funcion vengo cuando se le hace click a ese botón
      $("#mode").val("edit");
      //Acá asigno el valor del ID al otro input hidden
      $("#id").val(id);
      $("#" + modal).modal("show");
    },
    error: function (xhr, status, error) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: xhr.responseJSON.message,
      });
    },
  });
}

function setValueToForm(data, key) {
  let value = data[key];
  let inputField = $("[name='" + key + "']");

  if (inputField.length > 0) {
    if (inputField.is("input")) {
      //Si es una fecha y tiene timestamp lo convierto
      if (key === "dateM" && value.timestamp) {
        value = formatTimestampToDate(value.timestamp);
      }
    } else if (inputField.is("select")) {
      //Aca puedo tener casos que el select sea un objeto (osea una FK) como es el caso de area con directionID
      //O un select común (a este caso lo voy a hacer despúes)
      if (key.toLowerCase().includes("id") && typeof value === "object") {
        value = value.id;
      } else {
        console.log("Soy un select comun");
      }
    }

    inputField.val(value);
  }
}

function filterFooterText(datatable, columns = null) {
  let columnsTable;
  if (columns != null) {
    columnsTable = datatable.columns(columns);
  } else {
    columnsTable = datatable.columns();
  }

  columnsTable.every(function () {
    var that = this;
    var input = $('<input class="form-control" type="text" />')
      .appendTo($(this.footer()).empty())

      .on("keyup change", function () {
        if (that.search() !== this.value) {
          that.search(this.value).draw();
        }
      });
  });
}

function filterFooterSelect(datatable, columns = null) {
  let columnsTable;
  if (columns != null) {
    columnsTable = datatable.columns(columns);
  } else {
    columnsTable = datatable.columns();
  }

  columnsTable.every(function () {
    var column = this;
    var select = $(
      '<select class="form-control custom-select"><option value=""></option></select>'
    )
      .appendTo($(column.footer()).empty())
      .on("change", function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());

        column.search(val ? "^" + val + "$" : "", true, false).draw();
      });

    column
      .data()
      .unique()
      .sort()
      .each(function (d, j) {
        select.append('<option value="' + d + '">' + d + "</option>");
      });
  });
}

function formatDate(date) {
  console.log(date);
  const today = new Date(date);
  const yyyy = today.getFullYear();
  let mm = today.getMonth() + 1; // Months start at 0!
  let dd = today.getDate();

  if (dd < 10) dd = "0" + dd;
  if (mm < 10) mm = "0" + mm;

  const formattedToday = dd + "/" + mm + "/" + yyyy;

  return formattedToday;
}

function formatTimestampToDate(timestamp) {
  const date = new Date(timestamp * 1000); // Multiplicar por 1000 para convertir de segundos a milisegundos
  const day = String(date.getDate()).padStart(2, "0");
  const month = String(date.getMonth() + 1).padStart(2, "0"); // Sumar 1 al mes ya que en JavaScript los meses van de 0 a 11
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
}

function paintSelectedRow(table) {
  let selectedRow = null; // Almacenar la fila seleccionada previamente

  // Agrega el evento de clic a las filas
  $("#" + table).on("click", "tr", function () {
    if (selectedRow !== null) {
      // Si hay una fila seleccionada previamente, eliminar la clase "selected-row"
      $(selectedRow).removeClass("selected-row");
    }

    if ($(this).hasClass("selected-row")) {
      // Si la fila actual ya tiene la clase "selected-row", se "despinta"
      $(this).removeClass("selected-row");
      selectedRow = null; // No hay ninguna fila seleccionada
    } else {
      // Si la fila actual no tiene la clase "selected-row", se le aplica y se guarda como la fila seleccionada
      $(this).addClass("selected-row");
      selectedRow = this;
    }
  });
}

function refreshTableData(tableName, mode, data) {
  let table = $("#" + tableName).DataTable();

  if (mode == "create") {
    table.row.add(data).draw(false);
  } else {
    let code = data["ID"];
    //Con esto busco la fila que quiero a través del ID que envío, que se llama ID en la tabla
    let modifiedRow = table
      .rows()
      .eq(0)
      .filter(function (indice) {
        return table.row(indice).data()["ID"] === code;
      });

    if (modifiedRow.length > 0) {
      let fileData = table.row(modifiedRow[0]).data();
      fileData = data;
      table.row(modifiedRow[0]).data(fileData).draw(false);
    }
  }
}

function renderActionButton(row, url, modal, tableName) {
  let html = `
    <div class="btn-group">
      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          Opciones
      </button>
      <ul class="dropdown-menu">
          <li>
              <a class="dropdown-item" href="#" title="Editar" onclick="getRegister('${row["ID"]}', '${url}', '${modal}')">
                  Editar registro <i class="ml-1 text-success fas fa-edit"></i>
              </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
              <a class="dropdown-item" href="#" title="Eliminar" onclick="ajaxDelete(${row["ID"]}, './delete', ${tableName})">
                  Eliminar registro <i class="ml-1 text-danger fas fa-trash-alt"></i>
              </a>
          </li>
      </ul>
    </div>`;

  return html;
}
