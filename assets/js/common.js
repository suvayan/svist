$(document).ready(function($){
	$("form#frmLogin").validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		rules: {
			username: {
				required: true,
				email: true
			},
			password: {
				required: true
			}
		},

		messages: {
			username: {
				required: 'Please enter your username.',
				email: 'Enter valid email address.'
			},
			password: {
				required: 'Please enter your password.'
			}
		},

		// Form Processing via AJAX
		submitHandler: function(form, e)
		{
			e.preventDefault();
			//show_loading_bar(70); // Fill progress bar to 70% (just a given value)
			$('#btn_login').addClass('home-active');
			$('#btn_login').attr('disabled', true);
			/*var email = $(form).find('#username').val();
			var password = $(form).find('#password').val();*/
			var frmData = new FormData($('#frmLogin')[0]);
			$.ajax({
				url: baseURL+'Login/loginMe',
				method: 'POST',
				dataType: 'json',
				data: frmData,
				contentType: false,
				processData: false,
				success: function(resp)
				{
					if(resp.accessGranted)
					{
						window.location.href = baseURL+(resp.utype);
					}else{
						$('#btn_login').removeClass('home-active');
						$('#btn_login').removeAttr('disabled');
						$('#frmLogin')[0].reset();
						grecaptcha.reset();
						$.notify({icon:"add_alert",message:resp.errors},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
					}
				}
			});
		}
	});
	
	$("form#frmReset").validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		rules: {
			vcode: {
				required: true,
				digits: true,
				maxlength: 6,
				minlength: 6
			},
			password: {
				required: true
			},
			cpassword: {
				required: true,
				equalTo: '#password'
			}
		},

		messages: {
			vcode: {
				required: 'Must enter OTP.',
				digits: 'Only numbers.',
				maxlength: 'Must enter all 6 digits.',
				minlength: 'Must enter all 6 digits.'
			},
			password: {
				required: 'Must enter password.'
			},
			cpassword: {
				required: 'Must confirm password.',
				equalTo: 'Password not matched.'
			}
		},
		submitHandler: function(form, e)
		{
			e.preventDefault();
			//show_loading_bar(70); // Fill progress bar to 70% (just a given value)
			$('#btn_login').addClass('home-active');
			$('#btn_login').attr('disabled', true);
			var frmData = new FormData($('#frmReset')[0]);
			$.ajax({
				url: baseURL+'Login/updatePassword',
				method: 'POST',
				dataType: 'json',
				data: frmData,
				contentType: false,
				processData: false,
				success: function(resp)
				{
					if(resp.accessGranted)
					{
						window.location.href = baseURL+'login';
					}else{
						$('#btn_login').removeClass('home-active');
						$('#frmReset')[0].reset();
						grecaptcha.reset();
						$('#btn_login').removeAttr('disabled');
						$.notify({icon:"add_alert",message:resp.errors},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
						$(form).find('#passwd').select();
					}
				}
			});
		}
	});
	
	$("form#register").validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		rules: {
			org: {
				required: true
			},
			dept: {
				required: true
			},
			fname: {
				required: true,
				minlength: 3
			},
			lname: {
				required: true,
				minlength: 3
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true,
				digits: true,
				minlength: 10,
				maxlength: 10
			},
			passwd: {
				required: true,
				minlength: 6
			},
			repasswd: {
				required: true,
				equalTo: '#passwd'
			}
		},

		messages: {
			org: {
				required: 'This field is required.'
			},
			dept: {
				required: 'This field is required.'
			},
			fname: {
				required: 'Must enter your firstname.',
				minlength: 'The firstname must be minimum 3characters.'
			},
			lname: {
				required: 'Must enter your lastname.',
				minlength: 'The lastname must be minimum 3characters.'
			},
			email: {
				required: 'Must enter your email address.',
				email: 'Enter valid email address'
			},
			phone: {
				required: 'Must enter your phone number.',
				digits: 'Must enter digits only.',
				minlength: 'Phone must be 10 digits.',
				maxlength: 'Phone must be 10 digits.'
			},
			passwd: {
				required: 'Must enter your password.',
				minlength: "The password's length mus be minimum 6."
			},
			repasswd: {
				required: 'Must confirm your password.',
				confirm: 'Password not matched.'
			}
		},
		submitHandler: function(form, e)
		{
			e.preventDefault();
			$('#btn_login').addClass('home-active');
			$('#btn_login').attr('disabled', true);
			var frmReg = new FormData($('#register')[0]);
			$.ajax({
				url: baseURL+'Login/userAuthentication',
				method: 'POST',
				data: frmReg,
				processData: false,
				contentType: false,
				success: function(resp)
				{
					var obj = JSON.parse(resp);
					$('#btn_login').removeClass('home-active');
					
					if(obj['accessGranted'])
					{
						swal('Congratulation', 'Your registration is done. Please check your mail to complete the registration by confirming your email.', 'success').then(result=>{
							window.location.reload();
						});
						
					}else{
						$('#btn_login').removeAttr('disabled');
						$('#register')[0].reset();
						$('form#register')[0].reset();
						grecaptcha.reset();
						$('#org').selectpicker('refresh');
						$('#dept').selectpicker('refresh');
						$.notify({icon:"add_alert",message:obj['errors']},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
					}
				}
			});
		}
	});
	
	$("form#reqDemo").validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		rules: {
			fname: {
				required: true,
				minlength: 3
			},
			lname: {
				required: true,
				minlength: 3
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true,
				digits: true,
				minlength: 10,
				maxlength: 10
			}
		},

		messages: {
			fname: {
				required: 'Must enter your firstname.',
				minlength: 'The firstname must be minimum 3characters.'
			},
			lname: {
				required: 'Must enter your lastname.',
				minlength: 'The lastname must be minimum 3characters.'
			},
			email: {
				required: 'Must enter your email address.',
				email: 'Enter valid email address'
			},
			phone: {
				required: 'Must enter your phone number.',
				digits: 'Must enter digits only.',
				minlength: 'Phone must be 10 digits.',
				maxlength: 'Phone must be 10 digits.'
			}
		},
		submitHandler: function(form, e)
		{
			e.preventDefault();
			$('#btn_login').addClass('home-active');
			$('#btn_login').attr('disabled', true);
			var frmReq = new FormData($('#reqDemo')[0]);
			$.ajax({
				url: baseURL+'Login/receiveRequestDemo',
				method: 'POST',
				data: frmReq,
				processData: false,
				contentType: false,
				success: function(resp)
				{
					var obj = JSON.parse(resp);
					$('#btn_login').removeClass('home-active');
					$('form#reqDemo')[0].reset();
					$('#btn_login').removeAttr('disabled');
					grecaptcha.reset();
					if(obj['accessGranted'])
					{
						swal('Congratulation', 'Your request for demo is received. Please check your mail to receive your request serial code MLP_XX_XXXXXXX for future purpose.', 'success');
					}else{
						
						$.notify({icon:"add_alert",message:obj['errors']},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
					}
				}
			});
		}
	});
	
	$('#frmStudRegister').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		rules: {
			ayear: {
				required: true
			},
			org: {
				required: true
			},
			dept: {
				required: true
			},
			prog: {
				required: true
			},
			sem: {
				required: true
			},
			enroll: {
				required: true
			},
			fname: {
				required: true
			},
			lname: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true,
				digits: true,
				minlength: 10,
				maxlength: 10
			},
			passwd: {
				required: true,
				minlength: 6
			},
			repasswd: {
				required: true,
				equalTo: '#passwd'
			}
		},
		messages: {
			ayear: {
				required: 'This field is required'
			},
			org: {
				required: 'This field is required'
			},
			dept: {
				required: 'This field is required'
			},
			prog: {
				required: 'This field is required'
			},
			sem: {
				required: 'This field is required'
			},
			enroll: {
				required: 'This field is required'
			},
			fname: {
				required: 'This field is required'
			},
			lname: {
				required: 'This field is required'
			},
			email: {
				required: 'This field is required',
				email: 'Invalid email address'
			},
			phone: {
				required: 'This field is required',
				digits: 'Must be digits',
				minlength: 'Must be 10 digits',
				maxlength: 'Must be 10 digits'
			},
			passwd: {
				required: 'This field is required',
				minlength: 'Minimum length 6.'
			},
			repasswd: {
				required: 'This field is required',
				equalTo: 'Password not matched'
			}
		}
	});
	
	// Set Form focus
	$("form#register .form-group:has(.form-control):first .form-control").focus();

	// Set Form focus
	$("form#frmReset .form-group:has(.form-control):first .form-control").focus();

	// Set Form focus
	$("form#login .form-group:has(.form-control):first .form-control").focus();
});
function SendOTP()
{
	Swal.fire({
	  title: 'Submit your email address',
	  input: 'text',
	  inputAttributes: {
		autocapitalize: 'off'
	  },
	  showCancelButton: true,
	  confirmButtonText: 'Get OTP',
	  showLoaderOnConfirm: true,
	  preConfirm: (login) => {
		return fetch(baseURL+`Login/SendMailOTPConfirmation/?qemail=${login}`)
		  .then(response => {
			if (!response.ok) {
			  throw new Error(response.statusText)
			}
			return response.json()
		  })
		  .catch(error => {
			Swal.showValidationMessage(
			  `Request failed: ${error}`
			)
		  })
	  },
	  allowOutsideClick: () => !Swal.isLoading()
	}).then((result) => {
	  if (result.value) {
		  if(result.value.status!='success'){
				Swal.fire({
					title: result.value.title,
					text: result.value.msg,
					icon: result.value.status,
				});
			}else{
				Swal.fire({
					title: result.value.title,
					text: result.value.msg,
					icon: result.value.status,
				}).then(res=>{
					window.open(baseURL+'Login/resetPassword/?email='+btoa(result.value.email)+'&vercode='+btoa(result.value.code), '_self');
				})
			}
	  }
	})
}
