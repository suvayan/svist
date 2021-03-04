$("#new-pass").on('keyup', function() {
	//($(this).val().length)+1
	//alert(($(this).val().length)+1);
	var password = $(this).val();
	$("#pass-progress").css('width', ((password.length)/8) * 100 + '%');
	var strength = 0;
	if (password.length > 7) strength += 1;
	if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1;
	if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1;
	if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
	if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
	if (strength < 2) {
		$('#pass-progress').removeClass();
		$('#pass-progress').addClass('progress-bar progress-bar-red');
	} else if (strength == 2) {
		$('#pass-progress').removeClass();
		$('#pass-progress').addClass('progress-bar progress-bar-warning');
	} else {
		$('#pass-progress').removeClass();
		$('#pass-progress').addClass('progress-bar progress-bar-success');
	}
});

$("#btn_save_pass").click(function() {
	var npassword = $("#new-pass").val();
	if(npassword.length >=8) {
		var pclass = $('#pass-progress').attr('class');
		if(pclass == 'progress-bar progress-bar-red') {
			$.notify({
				icon: "add_alert",
				message: "Oh snap! Password shoud be comdinations of A-Z, a-z, 0-9, !,%,&,@,#,$,^,*,?,_,~."
			}, {
				type: 'warning',
				timer: 3e3,
				placement: {
					from: 'top',
					align: 'right'
				}
			});
		}else {
			var password = $("#re-pass").val();
			if($.trim(npassword) == $.trim(password)) {
				$.ajax({
					url: baseURL+uType+'/updateUserPassword',
					type: 'POST',
					data: {pass: npassword},
					success: (data)=>{
						var obj = jQuery.parseJSON( data );
						if(obj.status == 'success') {
						  $.notify({
								icon: "add_alert",
								message: "Your password <strong>successfully</strong> uploaded..."
							}, {
								type: 'success',
								timer: 3e3,
								placement: {
									from: 'top',
									align: 'right'
								}
							});
						  jQuery('#settings-modal').modal('hide', {backdrop: 'static'});
						}
						else {
						  $.notify({
								icon: "add_alert",
								message: "Oh snap! Something wents wrong."
							}, {
								type: 'warning',
								timer: 3e3,
								placement: {
									from: 'top',
									align: 'right'
								}
							});

						}
					},
					error: (errors)=>{
						console.log(errors);
					}
				});
			}
			else {
				$.notify({
					icon: "add_alert",
					message: "Oh snap! Password shoud match."
				}, {
					type: 'danger',
					timer: 3e3,
					placement: {
						from: 'top',
						align: 'right'
					}
				});

			}
		}
	}
	else {
		$.notify({
			icon: "add_alert",
			message: "Oh snap! Password shoud be minimum 8 charecter long."
		}, {
			type: 'danger',
			timer: 3e3,
			placement: {
				from: 'top',
				align: 'right'
			}
		});
	}
});