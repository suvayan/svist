$(document).ready(function() {
	getProgramNotices();
	$('#org_contact').summernote();
	$('#strm_contact').summernote();
});

function modalInstitute(func, prog_id, inst_id)
{
	if(func=='add'){
		$('#frmInstitute')[0].reset();
		$('#addInstitute #org_contact').summernote("code", "");
		$('#addInstitute #iprog_id').val(prog_id);
		$('#addInstitute #org_id').val(inst_id);
		$('#addInstitute #inst_title').html('Add Institute');
		$('#addInstitute #orgSubmit').html('Save');
		$('#addInstitute').modal('toggle');
		$('#addInstitute').draggable({
			cursor: "move"
		});
	}else{
		$('#loading').show();
		$.ajax({
			url: baseURL+'Teacher/getOrgById/?org='+inst_id,
			type: 'GET',
			success: (data)=>{
				$('#loading').fadeOut(1000);
				var res = JSON.parse(data);
				if(res[0].logo!=""){
					$('#addInstitute #wizardPicturePreview').attr('src', baseURL+'assets/img/institute/'+res[0].logo);
				}
				$('#addInstitute #iprog_id').val(prog_id);
				$('#addInstitute #org_id').val(inst_id);
				$('#addInstitute #org_title').val(res[0].title);
				$('#addInstitute #org_web').val(res[0].website);
				$('#addInstitute #org_contact').summernote("code", res[0].contact_info);
				$('#addInstitute #inst_title').html('Update Institute');
				$('#addInstitute #orgSubmit').html('Update');
				$('#addInstitute').modal('show');
				$('#addInstitute').draggable({
					cursor: "move"
				});
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
}

function deleteInstitute(org_id)
{
	swal({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-danger',
		confirmButtonText: 'Yes, delete it!',
		buttonsStyling: false
	}).then(function() {
		$('#loading').show();
		$.ajax({
			url: baseURL+'Teacher/delOrg/?org='+org_id,
			type: 'GET',
			success: (res)=>{
				$('#loading').fadeOut(1000);
				if(res)
				{
					swal({
						title: 'Deleted!',
						text: 'The Institute has been deleted.',
						type: 'success',
						confirmButtonClass: "btn btn-success",
						buttonsStyling: false
					}).then((result)=>{
						window.location.reload();
					})
				}
			}
		})
	}).catch(swal.noop)
}

$('#orgSubmit').on('click', ()=>{
	$('#loading').show();
	var title = $('#org_title').val().trim();
	if(title==''){
		$('#loading').hide();
		$('#org_title').focus();
	}else{
		var frmIns = new FormData($('#frmInstitute')[0]);
		$.ajax({
			url: baseURL+'Teacher/cuORG',
			type: 'POST',
			data: frmIns,
			cache : false,
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			async: false,
			success: (data)=>{
				$('#frmInstitute')[0].reset();
				$('#addInstitute').modal('hide');
				$('#loading').fadeOut(1000);
				if(data){
					swal({
						title: "Institute",
						text: "saved successfully",
						buttonsStyling: false,
						confirmButtonClass: "btn btn-success",
						type: "success"
					}).then((result)=>{
						window.location.reload();
					}).catch(swal.noop)
				}else{
					swal({
						title: "Institute",
						text: "saved error",
						buttonsStyling: false,
						confirmButtonClass: "btn btn-warning",
						type: "warning"
					}).catch(swal.noop)
				}
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
})

function modalStream(func, prog_id, strm_id)
{
	if(func=='add'){
		$('#frmStream')[0].reset();
		$('#addStream #strm_org').selectpicker('refresh');
		$('#addStream #strm_contact').summernote("code", "");
		$('#addStream #prog_id').val(prog_id);
		$('#addStream #strm_id').val(strm_id);
		$('#addStream #s_title').html('Add Stream');
		$('#addStream #strmSubmit').html('Save');
		$('#addStream').modal('toggle');
	}else{
		$('#loading').show();
		$.ajax({
			url: baseURL+'Teacher/getStreamById/?strm='+strm_id,
			type: 'GET',
			success: (data)=>{
				$('#loading').fadeOut(1000);
				var res = JSON.parse(data);
				
				$('#addStream #prog_id').val(prog_id);
				$('#addStream #strm_id').val(strm_id);
				$('#addStream #strm_title').val(res[0].title);
				$('#addStream #strm_web').val(res[0].website);
				$('#addStream #strm_org').val(res[0].org_id).selectpicker('refresh');
				$('#addStream #strm_contact').summernote("code", res[0].contact_info);
				$('#addStream #s_title').html('Update Stream');
				$('#addStream #strmSubmit').html('Update');
				$('#addStream').modal('show');
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
}

function deleteStream(strm_id)
{
	swal({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-danger',
		confirmButtonText: 'Yes, delete it!',
		buttonsStyling: false
	}).then(function() {
		$('#loading').show();
		$.ajax({
			url: baseURL+'Teacher/delStream/?strm='+strm_id,
			type: 'GET',
			success: (res)=>{
				$('#loading').fadeOut(1000);
				if(res)
				{
					swal({
						title: 'Deleted!',
						text: 'The Stream has been deleted.',
						type: 'success',
						confirmButtonClass: "btn btn-success",
						buttonsStyling: false
					}).then((result)=>{
						window.location.reload();
					})
				}
			}
		})
	}).catch(swal.noop)
}

$('#strmSubmit').on('click', ()=>{
	$('#loading').show();
	var stitle = $('#strm_title').val().trim();
	var org_id = $('#strm_org').val();
	if(stitle=='' || org_id==null){
		$('#strm_title').focus();
	}else{
		
		$.ajax({
			url: baseURL+'Teacher/cuStream',
			type: 'POST',
			data: {
				'prog_id': $('#prog_id').val(),
				'strm_id': $('#strm_id').val(),
				'title': stitle,
				'org_id':  org_id,
				'web': $('#strm_web').val().trim(),
				'contact': $('#strm_contact').val().trim()
			},
			async: false,
			cache : false,
			success: (data)=>{
				$('#loading').fadeOut(1000);
				$('#frmStream')[0].reset();
				$('#addStream').modal('hide');
				if(data){
					
					swal({
						title: "Stream",
						text: "saved successfully",
						buttonsStyling: false,
						confirmButtonClass: "btn btn-success",
						type: "success"
					}).then((result)=>{
						window.location.reload();
					}).catch(swal.noop)
				}else{
					swal({
						title: "Stream",
						text: "saved error",
						buttonsStyling: false,
						confirmButtonClass: "btn btn-warning",
						type: "warning"
					}).catch(swal.noop)
				}
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
})