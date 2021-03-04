$('#resume_upload').on("change", function () {
	var allowExt = ["doc", "txt", "docx", "pdf", "xls", "xlsx"];
	var path = './uploads/resume/Magnox'+(Math.floor(Math.random() * 10000))+'-';
	path += $('#resume_upload').val().substring(12);
	var nameArr = $('#resume_upload').val().split(".");
	var ext = nameArr[nameArr.length-1];
	if(jQuery.inArray( ext, allowExt ) > -1) {
		var formData = new FormData();
		formData.append('myfile', $('#resume_upload')[0].files[0]);
		formData.append('path', path);
		$('#resume_upload_btn').attr('disabled', 'disabled');
		$.notify({
			icon: "add_alert",
			message: "<b>Uploading your Resume</b> Please wait."
		}, {
			type: 'warning',
			timer: 3e3,
			placement: {
				from: 'top',
				align: 'right'
			}
		});
		$('#resume_upload_progress').css('display', 'block');
		 $.ajax({
		  url: baseURL+'en/UploadResume',
		  data: formData,
		  processData: false,
		  contentType: false,
		  type: 'POST',
		  enctype: 'multipart/form-data',
		  success: function (data) {
			  //console.log(data);
			  var obj = jQuery.parseJSON( data );
			  if(obj.status == 'success') {
				  $.notify({
						icon: "add_alert",
						message: "<b>Hurray!</b> Your resume is <strong>successfully</strong> uploaded..."
					}, {
						type: 'success',
						timer: 3e3,
						placement: {
							from: 'top',
							align: 'right'
						}
					});
			  }
			  else {
				  $.notify({
					icon: "add_alert",
					message: "Oh snap! Something wents wrong."
				}, {
					type: 'danger',
					timer: 3e3,
					placement: {
						from: 'top',
						align: 'right'
					}
				});
			  }
			  $('#resume_upload_progress').css('display', 'none');
		  }
	  });
	}

	});