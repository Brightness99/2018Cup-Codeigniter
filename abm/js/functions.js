var FUNCTIONS = {};

FUNCTIONS.validate = {
    CHECK_NUMBERS: "numbers",
    CHECK_STRINGS: "strings",
    CHECK_URL: "url",
    CHECK_NAMES: "names",
    CHECK_USERNAMES: "usernames",
    CHECK_PASSWORDS: "passwords",
    CHECK_EMAILS: "emails",
    CHECK_QUESTIONS: "questions",

    numbers: function(string) {
        return (string.search(/\D+/g) == -1);
    },

    strings: function(string) {
        return (string.search(/^[a-zñáéíóú|\d]+[a-zñáéíóú|\d|\s|\.|,|\-|_|'|&|¡|!|¿|\?]*$/ig) != -1);
    },

    url: function(string) {
        return (string.search(/^[\w]+(\-\w+)*$/g) != -1);
    },

    names: function(string) {
        return (string.search(/^[a-zñáéíóú|\d]+[a-zñáéíóú|\d|\s|\.|\-|']+$/ig) != -1);
    },

    usernames: function(string) {
        //26.04.2018 => OP: por petición se permite cualquier caracter...
        //return (string.search(/^[\w]+[\w|\.|\-]+$/g) != -1);
        return true;
    },

    passwords: function(string) {
        return true;
    },

    emails: function(string) {
        return (string.search(/^[\w]+(\.\w+)*@{1}\w+(\-\w+)*\.[a-z]{2,4}(\.[a-z]{2})?$/g) != -1);
    },

    questions: function(string) {
        return (string.search(/^[a-zñáéíóú|¿]+[a-zñáéíóú|\d|\s|\.|,|\-|'|&|\?]+$/ig) != -1);
    },

    getDescription: function(object) {
        try {
            var title = $("#h1TituloOperacion").text();
            var label = $("label[for=" + object.id + "]").text();
            return title + " > " + label + "\n";
        }
        catch(e) {
            return "";
        }
    },

    field: function(object, type, not_null, min_length, description) {
        try {
            if (object.value === undefined)
                throw "Campo No Válido";
            if (not_null === undefined)
                not_null = true;
            if (object.value == "" && !not_null)
                return true;

            if (description === undefined)
                description = this.getDescription(object);
            if (min_length === undefined)
                min_length = 3;
            if (object.value.length < min_length)
                throw "La longitud mínima permitida es " + min_length + " caracteres";
            var validateFunction = eval("this." + type);
            if (!validateFunction(object.value))
                throw "Valor no permitido";
            if ($(object).prop("maxlength") !== undefined && object.value.length > $(object).prop("maxlength"))
                throw "El valor no puede exceder de " + $(object).prop("maxlength") + " caracteres";

            return true;
        }
        catch(e) {
            alert(description + e);
            object.focus();
            return false;
        }
    },

    numericField: function(object, not_null, min_length) {
        return this.field(object, this.CHECK_NUMBERS, not_null, min_length);
    },

    stringField: function(object, not_null, min_length) {
        return this.field(object, this.CHECK_STRINGS, not_null, min_length);
    },

    urlField: function(object, not_null) {
        return this.field(object, this.CHECK_URL, not_null, 3);
    },

    nameField: function(object, not_null) {
        return this.field(object, this.CHECK_NAMES, not_null, 2);
    },

    usernameField: function(object) {
        return this.field(object, this.CHECK_USERNAMES, true, 4);
    },

    passwordField: function(object, not_null) {
        return this.field(object, this.CHECK_PASSWORDS, not_null, 6);
    },

    emailField: function(object, not_null) {
        return this.field(object, this.CHECK_EMAILS, not_null, 8);
    },

    questionField: function(object, not_null) {
        return this.field(object, this.CHECK_QUESTIONS, not_null, 8);
    },

    fileExtensionImport: function(filename) {
        if (filename.search(/\.{1}(csv|xls)$/i) == -1)
            throw "La extensión del archivo no es válida";
    },

    fileExtensionImages: function(filename) {
        if (filename.search(/\.{1}(gif|jpg|jpeg|png)$/i) == -1)
            throw "La extensión del archivo no es válida";
    },

};

FUNCTIONS.uploadImagesCompany = {

    init: function() {
        var input_file = null;
        var validate_image = 1;

        function clearImage(image) {
            $("#" + image.id.replace("im", "lb")).html($("#hfMensajeImagen").val());
            $("#" + image.id.replace("im", "fl")).val("");
            validate_image = $(image).parent().siblings("input[id^='hfSource']").val() != "" ? 0 : 1;
            image.src = $(image).parent().siblings("input[id^='hfSource']").val();
        };

        function getSizeScale(size_bytes) {
            if (size_bytes < 1024)
                return parseInt(size_bytes).toFixed() + " B";
            else if (size_bytes < 1024 * 1024)
                return parseFloat(size_bytes / 1024).toFixed(1) + " KB";
            else if (size_bytes < 1024 * 1024 * 1024)
                return parseFloat(size_bytes / (1024 * 1024)).toFixed(1) + " MB";
            else
                return parseFloat(size_bytes / (1024 * 1024 * 1024)).toFixed(1) + " GB";
        };

        $("input[type='file']").on("change",
            function(event) {
                try {
                    if ($(this).val() == "")
                        return;
                    input_file = this.files[0];
                    FUNCTIONS.validate.fileExtensionImages(input_file.name);
                    $("label[for='" + this.id + "']", $(this).closest("td")).text(input_file.name);
                    var image = document.getElementById(this.id.replace("fl", "im"));
                    image.src = window.URL.createObjectURL(input_file);
                }
                catch(e) {
                    alert(e);
                    event.preventDefault();
                }
            });

        $("img", document.forms[0]).on("load",
            function(event) {
                if (validate_image == 0) {
                    $(this).css("height", "60px");
                    validate_image = 1;
                    return;
                }
                $(this).css("height", "auto");
                var image_width = parseInt(this.width.toFixed());
                var image_height = parseInt(this.height.toFixed());
                var image_size = parseInt($(this).parent().siblings("input[id^='hfSize']").val());
                var image_dimensions = $(this).parent().siblings("input[id^='hfDimensions']").val().split("x");
                image_dimensions[0] = parseInt(image_dimensions[0]);
                image_dimensions[1] = parseInt(image_dimensions[1]);
                $(this).css("height", "60px");
                if ((image_dimensions[0] != -1 && image_width > image_dimensions[0]) || (image_dimensions[1] != -1 && image_height > image_dimensions[1])) {
                    clearImage(this);
                    alert("Las dimensiones máximas de la imagen deben ser " + image_dimensions[0] + "x" + image_dimensions[1] + " píxeles");
                }
                if (image_size != -1 && image_size < input_file.size) {
                    clearImage(this);
                    alert("El tamaño máximo permitido para la imagen es de " + getSizeScale(image_size) + "\nLa imagen seleccionada tiene " + getSizeScale(input_file.size));
                }
            });

        $("body").prepend('<div id="dvEntirePage"></div>');

        $("div[id^='dvViewImage']").click(
            function(event) {
                $("#dvEntirePage").append('<img id="imPreview" title="Cerrar" src="' + document.getElementById(this.id.replace("dvViewImage", "im")).src + '">');
                var MAX_HEIGHT = parseInt(window.innerHeight * 0.7);
                if (parseInt($("#imPreview").prop("naturalHeight")) > MAX_HEIGHT)
                    $("#imPreview").css("height", MAX_HEIGHT + "px");
                $("#dvEntirePage").fadeIn(300);
            });

        $("#dvEntirePage").click(
            function(event) {
                $(this).fadeOut(300);
                $("img", $(this)).remove();
            });

        $(document).keydown(
            function(event) {
                if ($("#dvEntirePage").is(":visible") && event.which == 27) {
                    $("#dvEntirePage").fadeOut(300);
                    $("img", $("#dvEntirePage")).remove();
                }
            });

        $("div[id^='dvDownloadImage']").click(
            function(event) {
                var action_form = document.forms[0].action;
                var source_image = document.getElementById(this.id.replace("dvDownloadImage", "im")).src;
                source_image = source_image.substr(source_image.lastIndexOf("/") - source_image.length + 1);
                var source_hidden = document.getElementById(this.id.replace("dvDownloadImage", "hfSource")).value;
                source_hidden = source_hidden.substr(source_hidden.lastIndexOf("/") - source_hidden.length + 1);
                if (source_image != source_hidden) {
                    alert("Sólo se pueden descargar imágenes almacenadas en el sitio.");
                    return;
                }
                document.forms[0].action += "&download=" + document.getElementById(this.id.replace("dvDownloadImage", "hfSource")).name;
                document.forms[0].submit();
                document.forms[0].action = action_form;
            });

        $("div[id^='dvDeleteImage']").click(
            function(event) {
                var source_image = document.getElementById(this.id.replace("dvDeleteImage", "im")).src;
                source_image = source_image.substr(source_image.lastIndexOf("/") - source_image.length + 1);
                var source_hidden = document.getElementById(this.id.replace("dvDeleteImage", "hfSource")).value;
                source_hidden = source_hidden.substr(source_hidden.lastIndexOf("/") - source_hidden.length + 1);
                if (source_image != source_hidden) {
                    if (!confirm("No puede eliminar una imagen que no se ha guardado...\n\n¿Desea volver a cargar la imagen original?"))
                        return;
                    clearImage(document.getElementById(this.id.replace("dvDeleteImage", "im")));
                    return;
                }
                var index_next_slider = parseInt(this.id.replace(/\D/g, "")) + 1;
                if (index_next_slider <= 5) {
                    var id_next_slider = this.id.replace(/\d/g, index_next_slider).replace("dvDeleteImage", "im");
                    if (parseInt(document.getElementById(id_next_slider).naturalHeight) != 0) {
                        alert("No puede eliminar esta imagen...\nDebe antes eliminar la imagen #" + index_next_slider + ".");
                        return;
                    }
                }
                if (!confirm("Al CONFIRMAR se eliminará la imagen... ¿Desea continuar?"))
                    return;
                $("#" + this.id.replace("dvDeleteImage", "lb")).html($("#hfMensajeImagen").val());
                $("#" + this.id.replace("dvDeleteImage", "fl")).val("");
                $("#" + this.id.replace("dvDeleteImage", "im")).prop("src", "");
                $("#" + this.id.replace("dvDeleteImage", "hfDelete")).val("1");
            });
    }
};

FUNCTIONS.uploadImagesEmployee = FUNCTIONS.uploadImagesCompany;

FUNCTIONS.importEmployees = {

    init: function() {

        $("#frmEmpleadoImport").on("submit",
            function(event) {
                try {
                    if ($("#flImport").val() == "")
                        return;
                    FUNCTIONS.validate.fileExtensionImport($("#flImport").val());
                }
                catch(e) {
                    alert(e);
                    event.preventDefault();
                }
            });
    }
};

FUNCTIONS.listTeams = {

    init: function() {

        $("[id='chCampeon']").click(
            function(event) {
                if(confirm("Ha elegido a " + $("#tdEquipo", $(this).closest("tr")).text().toUpperCase() + " como campeón del torneo.\nSe calcularán los puntos por campeón.\n\nEsta acción es irreversible... ¿Desea continuar?"))
                    this.form.submit();
                else
                    this.checked = false;
            });
    }
};

FUNCTIONS.listPlayers = {
    edition: false,

    init: function() {
        var this_object = this;
        var goals = 0;
        var scorer = false;

        $("[id='btnEditar']").click(
            function(event) {
                if (this_object.edition)
                    return;
                this_object.edition = true;
                $("span", $(event.target).closest("tr")).each(
                    function() {
                        if (this.id == "spGoles")
                            this_object.goals = $(this).next("input").val();
                        if (this.id == "spGoleador")
                            this_object.scorer = $(this).next("input").prop("checked");
                        $(this).hide();
                        $(this).nextAll("input").prop("disabled", !this_object.edition);
                        $(this).next("input").show();
                    });
                $("button", $(event.target).closest("td")).toggle();
            });

        $("[id='btnCancelar']").click(
            function(event) {
                if (!this_object.edition)
                    return;
                this_object.edition = false;
                $("span", $(event.target).closest("tr")).each(
                    function() {
                        if (this.id == "spGoles")
                            $(this).next("input").val(this_object.goals);
                        if (this.id == "spGoleador")
                            $(this).next("input").prop("checked", this_object.scorer);
                        $(this).next("input").hide();
                        $(this).nextAll("input").prop("disabled", !this_object.edition);
                        $(this).show();
                    });
                $("button", $(event.target).closest("td")).toggle();
            });

        $("[id='btnAplicar']").click(
            function(event) {
                if (!$("[id='chkGoleador']", $(this).closest("tr")).prop("checked"))
                    return true;

                var jugador = $("#tdJugador", $(this).closest("tr")).text().toUpperCase();
                var goles = $("#nfGoles", $(this).closest("tr")).val();
                if ($("#hfMaxGoles").val() != "0" && $("#hfMaxGoles").val() != goles) {
                    alert("No puede elegir a " + jugador + " (" + goles + ") como goleador del torneo.\n" +
                        "No tiene los mismos goles que " + $("#hfMaxGoleador").val().toUpperCase() + " (" + $("#hfMaxGoles").val() + ").");
                    return false;
                }
                if(!confirm("Ha elegido a " + jugador + " como goleador del torneo.\nSe calcularán los puntos por goleador.\n\nEsta acción es irreversible... ¿Desea continuar?")) {
                    $(this).next().click();
                    return false;
                }
                else
                    return true;
            });
    }
};

FUNCTIONS.updateMatch = {

    init: function() {

        if (parseInt($("#hfFinalizado").val()) == 1) {
            alert("¡Partido finalizado!");
            $("[type='submit']").prop("disabled", true);
        }
    }
};

FUNCTIONS.updateMechanical = {

    init: function() {
        var result = parseInt($("#hfResult").val());

        if (result == 1) {
            alert("¡Mecánica de Juego actualizada!");
            location.href = "dashboard.php";
        }
        else if (result == -1)
            alert("Ocurrió un error actualizando la Mecánica de Juego...");
        $("#hfResult").val("0");
    }
};

FUNCTIONS.updateDatesTrivia = {
    dates_stages: null,

    init: function() {
        var this_object = this;
        var data;

        dates_stages = $("#hfFechasFases").val().split(";");
        $("#cboFases").on("change",
            function(event) {
                if (event.target.selectedIndex == 0) {
                    $("#dtInicio").val($("#hfInicioMundial").val());
                    $("#dtVencimiento").val($("#hfFinMundial").val());
                }
                else {
                    for (var i = 0; i < dates_stages.length; i++) {
                        this_object.data = dates_stages[i].split(",");
                        if (this_object.data[0] == event.target.value) {
                            $("#dtInicio").val(this_object.data[2]);
                            $("#dtVencimiento").val(this_object.data[3]);
                            break;
                        }
                    }
                }
            });
    }
};

FUNCTIONS.listTrivias = {

    init: function() {

        $("[id='btnCerrar']").click(
            function(event) {
                if(confirm("Ha elegido cerrar la trivia de la fase " + $("#tdFase", $(this).closest("tr")).text().toUpperCase() + ".\nSe calcularán los puntos correspondientes.\n\nEsta acción es irreversible... ¿Desea continuar?"))
                    location.href = "triviasProcesar.php?a=cerrar&id=" + $("#hfTrivia", $(event.target).closest("tr")).val();
                event.target.blur();
            });
    }
};