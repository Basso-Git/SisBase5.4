$(document).ready(function () {
  initializeTinyMCE();
});

function initializeTinyMCE(){
  tinymce.init({
    selector: ".tinymce",
    language: "es",
    plugins: "lists",
    toolbar: "bold underline bullist",
    menubar: false,
    setup: function (editor) {
      editor.on("init", function () {
        var maxLength = editor.getElement().getAttribute("maxlength");
        if (maxLength) {
          editor.on("keydown", function (e) {
            var content = editor.getContent();
            if (content.length >= maxLength && e.keyCode !== 8) {
              e.preventDefault();
              e.stopPropagation();
              return false;
            }
          });

          editor.on("paste", function (e) {
            // Espera un breve momento para que el texto pegado se inserte en el editor
            setTimeout(function () {
              var content = editor.getContent();
              if (content.length > maxLength) {
                // Si el texto pegado excede el límite de caracteres, recorta el exceso
                editor.setContent(content.substring(0, maxLength));
              }
            }, 100);
          });
        }
      });
    },
    // Opciones adicionales de configuración de TinyMCE si las necesitas
  });
}