$('#login-btn').on('click', function(){
	var userName = $.trim($('#user_name').val());
	var userPass = $.trim($('#user_pass').val());
	var company  = $('#current-company').val();

	if(userName === '' && userPass === ''){
		$('#login-err').text('Por favor ingrese nombre de usuario y contraseña');
		$('#login-err').fadeIn();
		return false;
	}
	
	if(userName !== '' && userPass === ''){
		$('#login-err').text('Por favor ingrese contraseña');
		$('#login-err').fadeIn();
		return false;
	}
	
	if(userName === '' && userPass !== ''){
		$('#login-err').text('Por favor ingrese nombre de usuario');
		$('#login-err').fadeIn();
		return false;
	}
	
	var formData = new FormData($("#login-frm")[0]);
	
	$.ajax({
		url: '../'+company+'/doLogin',
		type: "POST",
		data: formData,
		async: false,
    cache: false,
    contentType: false,
    processData: false,
		dataType: "json",
		success: function(response){
			if(response.status == 1){
				$('#login-err').text(response.message);
				$('#login-err').fadeIn();
				
				if(response.acepto_bases !== '1'){
					$('#user_id').val(response.user_id);
					$('#login-condition-modal').trigger('click');
				}else{
					location.reload();
				}
			}else{
				$('#login-err').text(response.message);
				$('#login-err').fadeIn();
				return false;
			}			
		}
	});
});


$('#condition-accept-btn').on('click', function(){
	var company  = $('#current-company').val();
	
	if ($('#condition_checkbox').is(':checked')) {
		var accept = $('#condition_checkbox').val();
		var userId = $('#user_id').val();
		
		$.ajax({
			url: '../'+company+'/accept-login-condition',
			type: "POST",
			data: {accept:accept,user_id:userId},
			dataType: "json",
			success: function(response){
				location.reload();
			}
		});
	}else{
		alert('Tiene que aceptar las condiciones para ingresar');
	}
});