function validaEmpresa(f) {
  var ok = true;
  var msg = "Debes completar los campos:\n";

  if(f.elements["empresa"].value == "")
  {
    msg += "- Empresa\n";
    ok = false;
  }

  if(f.elements["descripcion"].value == "")
  {
    msg += "- Descripción \n";
    ok = false;
  }

  if(f.elements["logo"].value == "" &&   document.getElementById("logoImg") == null)
  {
    msg += "- Logo\n";
    ok = false;
  }

  if(f.elements["url"].value == "")
  {
    msg += "- URL\n";
    ok = false;
  }

  if(ok == false)
    alert(msg);
  return ok;
}

function validaEmpleado(f) {
  var ok = true;
  var msg = "Debes completar los campos:\n";

  if(f.elements["id_empresa"].value == "")
  {
    msg += "- Empresa\n";
    ok = false;
  }

  if(f.elements["username_empleado"].value == "")
  {
    msg += "- Username \n";
    ok = false;
  }

  if(f.elements["password_empleado"].value == "")
  {
    msg += "- Password\n";
    ok = false;
  }

  if(f.elements["nombre_empleado"].value == "")
  {
    msg += "- Nombre\n";
    ok = false;
  }

  if(f.elements["apellido_empleado"].value == "")
  {
    msg += "- Apellido\n";
    ok = false;
  }

  if(ok == false)
    alert(msg);
  return ok;
}

function validaEquipoCampeon(f) {
	  var ok = false;
	  var msg = "Debe existir sólo 1 equipo campeon:\n";

	  if(f.elements["campeon"].value == "")
	  {
	    msg += "- Ya hay un equipo campeón\n";
	    ok = false;
	  }

	  if(ok == false)
	    alert(msg);
	  return ok;
	}


