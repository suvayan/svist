$(document).ready(function(){
	$('form#frmCourse').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		rules: {
			semid: {
				required: true
			},
			title: {
				required: true,
				minlength: 2
			},
			type: {
				required: true
			},
			ccode: {
				required: true
			}
		},
		messages: {
			semid: {
				required: 'Must select a semester.'
			},
			title: {
				required: 'Must enter course title.',
				minlength: 'The title must be minimum 2 characters.'
			},
			type: {
				required: 'Must select a type.'
			},
			ccode: {
				required: 'Must enter course code.'
			}
		},
		submitHandler: function(form, e) {
			e.preventDefault();
			var cid = parseInt($('#cid').val());
			var prog = parseInt($('#prog_id').val());
			$('#loading').css('display', 'block');
			var frmData = new FormData($('#frmCourse')[0]);
			$.ajax({
				url: baseURL+'Teacher/cuProCourse',
				type: 'POST',
				data: frmData,
				cache : false,
				processData: false,
				contentType: false,
				enctype: 'multipart/form-data',
				async: false,
				success: (data)=>{
					$('#loading').fadeOut(1000);
					var obj = JSON.parse(data);
					$.notify({icon:"add_alert",message:'The Test '+obj['msg']},{type:obj['status'],timer:3e3,placement:{from:'top',align:'right'}})
					if(obj['status']=='success'){
						$('#frmCourse')[0].reset();
						$('#semid').val("").selectpicker('refresh');
						$('#crtype').val("").selectpicker('refresh');
						$('#lecture').val("").selectpicker('refresh');
						$('#tutorial').val("").selectpicker('refresh');
						$('#practical').val("").selectpicker('refresh');
						if(cid!=0){
							if(obj['utype']=='teacher'){
								window.open(baseURL+'Teacher/courseDetails/?cid='+btoa(cid)+'&prog='+btoa(prog), '_self');
							}else if(obj['utype']=='subadmin'){
								window.open(baseURL+'Subadmin/viewProgCourse/?&pid='+btoa(prog), '_self');
							}else{
								window.open(baseURL+'Admin/viewProgCourse/?&pid='+btoa(prog), '_self');
							}
							
						}
					}
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	});
});