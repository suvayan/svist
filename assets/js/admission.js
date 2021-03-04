$(document).ready(function() {
  // Initialise the wizard
  $('#frmApply').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		rules: {
			fname: {
				required: true,
				minlength: 3
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
			dob: {
				required: true
			},
			gender: {
				required: true
			},
			newpass: {
				required: true,
				minlength: 6,
			},
			cnfpass: {
				equalTo: '#newpass'
			}
		},
		messages: {
			fname: {
				required: 'Must enter firstname.',
				minlength: 'Minimum 3 characters.'
			},
			lname: {
				required: 'Must enter lasstname.'
			},
			email: {
				required: 'Must enter email address.',
				email: 'Invalid email address.'
			},
			phone: {
				required: 'Must enter mobile number.',
				digits: 'Only digits.',
				minlength: 'Only 10 digits.',
				maxlength: 'Only 10 digits.'
			},
			dob: {
				required: 'Must enter date of birth.'
			},
			gender: {
				required: 'Must select your gender.'
			},
			newpass: {
				required: 'Must enter your password',
				minlength: 'Password length must be minimum 6.',
			},
			cnfpass: {
				equalTo: 'Password not matched.'
			}
		},
		submitHandler: (form, e)=>{
			e.preventDefault();
			$('#loading').show();
			$('#btnApply').attr('disabled', true);
			var frmApplyData = new FormData($('#frmApply')[0]);
			$.ajax({
				url: baseURL+'Login/userAdmission',
				type: 'POST',
				data: frmApplyData,
				cache : false,
				processData: false,
				contentType: false,
				enctype: 'multipart/form-data',
				async: false,
				success: (res)=>{ 
					$('#frmApply')[0].reset();
					$('#btnApply').removeAttr('disabled');
					$('#loading').hide();
					var obj = JSON.parse(res);
					swal(
					  obj['title'],
					  obj['msg'],
					  obj['status']
					).then(result=>{
						window.location.reload();
					});
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	})
  demo.initMaterialWizard();
  setTimeout(function() {
	$('.card.card-wizard').addClass('active');
  }, 600);
  $('#frmProgLogin').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		submitHandler: (form, e)=>{
			e.preventDefault();
			$('#loading').show();
			$('#btnLogin').attr('disabled', true);
			var frmData = new FormData($('#frmProgLogin')[0]);
			$.ajax({
				url: baseURL+'Login/userProgLogin',
				type: 'POST',
				data: frmData,
				cache : false,
				processData: false,
				contentType: false,
				enctype: 'multipart/form-data',
				async: false,
				success: (resp)=>{ 
					$('#frmProgLogin')[0].reset();
					$('#btnLogin').removeAttr('disabled');
					$('#loading').hide();
					$('#logModel').modal('hide');
					var obj = JSON.parse(resp);
					if(obj['accessGranted'])
					{
						window.location.href = baseURL+'Student';
					}else{
						$.notify({icon:"add_alert",message:obj['errors']},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
					}
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	});
});