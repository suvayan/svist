var count = $('#rfcount').val();
	
function customFileName(customid)
{
	var fileName = $('#'+customid).val().split("\\").pop();
	$('#'+customid).siblings(".custom-file-label").addClass("selected").html(fileName);
}

$('#frmRes').validate({
	errorPlacement: function(error, element) {
	  $(element).closest('.form-group').append(error);
	},
	submitHandler: function(form, e) {
		e.preventDefault();
		$('#loading').show();
		var frmResData = new FormData($('#frmRes')[0]);
		$.ajax({
			url: baseURL+'Teacher/cuCResource',
			type: 'POST',
			data: frmResData,
			cache : false,
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			async: false,
			success: (res)=>{ 
				$('#crModal').modal('hide');
				$('#loading').hide();
				var obj = JSON.parse(res);
				swal(
				  'Resource',
				  obj.msg,
				  obj.status
				).then(result=>{
					$('#resc').click();
					//window.location.reload();
				})
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
  });
  $('#frmResFiles').validate({
	errorPlacement: function(error, element) {
	  $(element).closest('.form-group').append(error);
	},
	submitHandler: function(form, e) {
		e.preventDefault();
		$('#loading').show();
		var frmResfile = new FormData($('#frmResFiles')[0]);
		$.ajax({
			url: baseURL+'Teacher/cuCResourceFiles',
			type: 'POST',
			data: frmResfile,
			cache : false,
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			async: false,
			success: (res)=>{ 
				$('#crfModal').modal('hide');
				$('#loading').hide();
				Swal.fire(
				  'Resource File/s',
				  'have been uploaded',
				  'success'
				).then(result=>{
					$('#resc').click();
					//window.location.reload();
				})
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
  });

function resourceModal(info, course_id, res_id)
{
	$('#frmRes')[0].reset();
	$('#crModal #rdetails').summernote('code','');
	$('#rnotify').removeAttr('checked');
	if(info=='add'){
		$('#crModal #crTitle').html('Add Resource');
		$('#crModal #crSubmit').html('Save');
		$('#crModal #cid').val(course_id);
		$('#crModal #res_id').val(res_id);
		$('#crModal').modal('show');
	}else if(info=='edit'){
		$('#loading').show();
		$.ajax({
			url:baseURL+'Teacher/getResource/?crid='+res_id,
			type: 'GET',
			success: (res)=>{
				var obj = JSON.parse(res);
				
				$('#crModal #rtitle').val(obj[0].title);
				//$('#crModal #rdetails').val(obj[0].desc);
				$('#crModal #crTitle').html('Update Resource');
				$('#crModal #crSubmit').html('Update');
				$('#crModal #cid').val(course_id);
				$('#crModal #res_id').val(res_id);
				$('#crModal #rdetails').summernote('code',obj[0].desc);
				if((obj[0].notify).trim()!='f'){
					$('#rnotify').attr('checked', 'checked');
				}else{
					$('#rnotify').removeAttr('checked');
				}
				$('#loading').hide();
				$('#crModal').modal('show');
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
}

function resourceFileModal(course_id, res_id)
{
	$('#yfl_button').show();
	$('#crfModal #rfcount').val(0);
	$('#crl_fields').html("");
	$('#crfModal #fcid').val(course_id);
	$('#crfModal #fres_id').val(res_id);
	$('#crfModal').modal('show');
}

function addMore(type)
{
	count++;
	
	var fieldset = document.createElement("fieldset");
	fieldset.setAttribute("class", "field p-3 mb-3 removeclass" + count);
	fieldset.style.border = "2px solid #E8E8E8";
	$('#rfcount').val(count);
	
	if(type=='yt'){
		fieldset.innerHTML = '<button type="button" class="close" onClick="removeNow('+count+');" aria-hidden="true"><i class="material-icons">clear</i></button><div class="form-group"><input type="hidden" value="yt" name="youtube'+count+'" id="youtube'+count+'"><label for="yt_link'+count+'" class="text-dark">Youtube Embed Link</label><textarea class="form-control w-100" cols="80" rows="5" name="yt_link'+count+'" id="yt_link'+count+'" required="true"></textarea></div>';
	}else if(type=='fl'){
		fieldset.innerHTML = '<button type="button" class="close" onClick="removeNow('+count+');" aria-hidden="true"><i class="material-icons">clear</i></button><div class="form-group"><input type="hidden" value="fl" name="files'+count+'" id="files'+count+'"><div class="custom-file" style="margin-bottom:20px;"><input type="file" class="custom-file-input" onChange="customFileName(`fl_link'+count+'`)" name="fl_link'+count+'" id="fl_link'+count+'" accept=".pdf, .doc, .docx, .xls, .xlsx, .jpg, .mp4" required="true"><label class="custom-file-label" for="fl_link'+count+'">Choose Files/Videos</label></div></div>';
	}else{
		fieldset.innerHTML = '<button type="button" class="close" onClick="removeNow('+count+');" aria-hidden="true"><i class="material-icons">clear</i></button><div class="form-group"><input type="hidden" value="lk" name="link'+count+'" id="link'+count+'"><label for="lk_link'+count+'" class="text-dark">Copy-past Link</label><textarea class="form-control w-100" cols="80" rows="5" name="lk_link'+count+'" id="lk_link'+count+'" url="true.html" required="true"></textarea></div>';
	}
	
	$("#crl_fields").append(fieldset);
	checkFieldset();
}

function removeNow(count) {
	$('.removeclass' + count).remove();
	checkFieldset();
}

function checkFieldset()
{
	var fcount = document.getElementsByTagName('fieldset');
	if(fcount.length==3){
		$('#yfl_button').hide();
	}else{
		$('#yfl_button').show();
	}
}

function openYTModal(res_id)
{
	$('#loading').show();
	$.ajax({
		url:baseURL+'Teacher/getResourceYTLink/?crid='+res_id,
		type: 'GET',
		success: (res)=>{
			var obj = JSON.parse(res);
			
			$('#YTModal #ytbody').html(obj[0].linkfile);
			$('#YTModal #ytbody iframe').attr('width', '100%');
			$('#loading').hide();
			$('#YTModal').modal('show');
		},
		error: (errors)=>{
			console.log(errors);
		}
	});
}
$('#YTModal').on('hide.bs.modal', function (e) {
	$('#YTModal #ytbody').html("");
})

function getResFile(res_id)
{
	$('#loading').show();
	$.ajax({
		url:baseURL+'Teacher/getResourceFiles/?crid='+res_id,
		type: 'GET',
		success: (res)=>{
			$('#loading').hide();
			if(res!='0'){
				var obj = JSON.parse(res);
				var ftype = obj[0].file_type.trim();
				if(ftype=='mp4'){
					$('#YTModal #ytbody').html('<video width="100%" height="350px" autoplay controls><source src="'+baseURL+'/uploads/cresources/'+obj[0].linkfile+'"></video>');
					$('#YTModal').modal('show');
				}else{
					$.get(baseURL+'uploads/cresources/'+obj[0].linkfile)
					.done(function() { 
						window.open(baseURL+'uploads/cresources/'+obj[0].linkfile, '_blank');
					}).fail(function() { 
						$.notify({
							icon:"add_alert",
							message: "Sorry! File is missing."},
							{type:"danger",
							timer:3e3,
							placement:{from:'top',align:'right'}
						})
					});
				}
				
			}else{
				$.notify({
					icon:"add_alert",
					message: "Sorry! File is missing."},
					{type:"danger",
					timer:3e3,
					placement:{from:'top',align:'right'}
				});
			}
		},
		error: (errors)=>{
			console.log(errors);
		}
	});
}

function deleteResource(res_id, res_title)
{
	Swal.fire({
	  title: 'Are you sure?',
	  text: "You want to delete Resource: "+res_title,
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	  if (result.value) {
		$('#loading').show();
		$.ajax({
			url:baseURL+'Teacher/deleteResources?crid='+res_id,
			type: 'GET',
			success: (res)=>{
				$('#loading').hide();
				$('#cr_'+res_id).remove();
				if(res){
					$.notify({
						icon:"add_alert",
						message: "The Resource:- "+res_title+" has been deleted"},
						{type:"success",
						timer:3e3,
						placement:{from:'top',align:'right'}
					})
				}else{
					$.notify({
						icon:"add_alert",
						message: "The Resource:- "+res_title+" deletion error"},
						{type:"danger",
						timer:3e3,
						placement:{from:'top',align:'right'}
					})
				}
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	  }
	})
}

function deleteResFiles(cfid)
{
	Swal.fire({
	  title: 'Are you sure?',
	  text: "You want to delete this resource?",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	  if (result.value) {
		$('#loading').css('display', 'block');
		$.ajax({
			url:baseURL+'Teacher/deleteResourceFiles?crid='+cfid,
			type: 'GET',
			success: (res)=>{
				$('#loading').hide();
				$('#alert_'+cfid).remove();
				if(res){
					$.notify({
						icon:"add_alert",
						message: "The resource has been deleted"},
						{type:"success",
						timer:3e3,
						placement:{from:'top',align:'right'}
					})
				}else{
					$.notify({
						icon:"add_alert",
						message: "The resource deletion error"},
						{type:"danger",
						timer:3e3,
						placement:{from:'top',align:'right'}
					})
				}
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	  }
	})
}