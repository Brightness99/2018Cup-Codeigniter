var CHECK_FORM = {};

CHECK_FORM.Company = function() {

    this.do = function() {
        if (!FUNCTIONS.validate.stringField(document.getElementById("tfEmpresa")))
            return false;
        if (!FUNCTIONS.validate.urlField(document.getElementById("tfURL")))
            return false;
        if (!FUNCTIONS.validate.stringField(document.getElementById("tfDescripcion")))
            return false;

        return true;
    };
};

CHECK_FORM.Employee = function() {

    this.do = function() {
        if (!FUNCTIONS.validate.usernameField(document.getElementById("tfUsuario")))
            return false;
        if (!FUNCTIONS.validate.passwordField(document.getElementById("tfClave"), false))
            return false;
        if (!FUNCTIONS.validate.nameField(document.getElementById("tfNombre")))
            return false;
        if (!FUNCTIONS.validate.nameField(document.getElementById("tfApellido")))
            return false;
        if (!FUNCTIONS.validate.emailField(document.getElementById("tfCorreoE"), false))
            return false;
        if (!FUNCTIONS.validate.stringField(document.getElementById("tfDepartamento"), false))
            return false;
        if (!FUNCTIONS.validate.stringField(document.getElementById("tfUbicacion"), false))
            return false;

        return true;
    };
};

CHECK_FORM.Match = function() {

    this.do = function() {
        if (!$("#rbFinalizadoSi").prop("checked"))
            return true;

        return confirm("Ha elegido dar por finalizado este partido.\nSe calcularán los puntos correspondientes.\n\nEsta acción es irreversible... ¿Desea continuar?");
    };
};

CHECK_FORM.Trivia = function() {

    this.do = function() {
        var this_object = this;
        var returns = true;

        $("input[type='text']", document.getElementById("frmTrivia")).each(
            function() {
                if (!returns)
                    return;
                if (this.id.substr(0,4) == "tfPr") {
                    if (!FUNCTIONS.validate.questionField(document.getElementById(this.id))) {
                        returns = false;
                        return;
                    }
                }
                else {
                    if (!FUNCTIONS.validate.stringField(document.getElementById(this.id), true, 1)) {
                        returns = false;
                        return;
                    }
                }
            });

        return returns;
    };
};

CHECK_FORM.Validator = function() {
    var form = document.forms[0];
    var validator = null;
    var actions = null;
    var result;

    if (form !== undefined) {
        switch(form.id) {
            case "frmEmpresa":
                validator = new CHECK_FORM.Company();
                actions = FUNCTIONS.uploadImagesCompany;
                break;
            case "frmEmpleado":
                validator = new CHECK_FORM.Employee();
                actions = FUNCTIONS.uploadImagesEmployee;
                break;
            case "frmEmpleadoImport":
                actions = FUNCTIONS.importEmployees;
                break;
            case "frmEquipos":
                actions = FUNCTIONS.listTeams;
                break;
            case "frmBuscarJugadores":
                actions = FUNCTIONS.listPlayers;
                break;
            case "frmPartido":
                validator = new CHECK_FORM.Match();
                actions = FUNCTIONS.updateMatch;
                break;
            case "frmMecanica":
                actions = FUNCTIONS.updateMechanical;
                break;
            case "frmTrivia":
                validator = new CHECK_FORM.Trivia();
                actions = FUNCTIONS.updateDatesTrivia;
        };
    }
    else {
        switch($("#h1TituloOperacion").text()) {
            case "Trivias":
                actions = FUNCTIONS.listTrivias;
        }
    }

    this.get = function() {
        return form;
    };

    this.hasValidator = function() {
        return (validator !== null);
    };

    this.validate = function() {
        result = validator.do();
    };

    this.isOK = function() {
        return result;
    };

    this.hasActions = function() {
        return (actions !== null);
    };

    this.initActions = function() {
        actions.init();
    };
};

$(document).ready(function() {
    try {
        var form = new CHECK_FORM.Validator();

        if (form.hasValidator())
            $(form.get()).on("submit",
                function(event) {
                    form.validate();
                    if (!form.isOK())
                        event.preventDefault();
                });

        if (form.hasActions())
            form.initActions();

        $("input[type=text], textarea").on("blur",
            function() {
                this.value = $.trim(this.value);
            });
    }
    catch (e) {}
});