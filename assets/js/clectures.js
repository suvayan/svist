$('#frmLec').validate({
	errorPlacement: function(error, element) {
	  $(element).closest('.form-group').append(error);
	},
	submitHandler: function(form, e) {
		$('#loading').show();
		e.preventDefault();
		var frmLecData = new FormData($('#frmLec')[0]);
		$.ajax({
			url: baseURL+'Teacher/cuCLecture',
			type: 'POST',
			data: frmLecData,
			cache : false,
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			async: false,
			success: (res)=>{ 
				$('#clModal').modal('hide');
				$('#loading').hide();
				var obj = JSON.parse(res);
				Swal.fire(
				  'Lecture',
				  obj.msg,
				  obj.status
				).then(result=>{
					$('#lec').click();
					//window.location.reload();
				})
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
  });


$("#lfiles").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

$('input[name="ltype"]').on('click',(e)=>{
	toggleFileTypes($('input[name="ltype"]:checked').val());
	if($('input[name="ltype"][value="fl"]').is(":checked")){
		$('#sh_file').show();
		$('#sh_lkyt').hide();
	}else{
		$('#sh_file').hide();
		$('#sh_lkyt').show();
	}
})

function toggleFileTypes(file_type)
{
	if(file_type=="fl"){
		$('#sh_file').show();
		$('#sh_lkyt').hide();
	}else if(file_type==""){
		$('#sh_file').hide();
		$('#sh_lkyt').hide();
	}else{
		$('#sh_file').hide();
		$('#sh_lkyt').show();
	}
}

function lectureModal(info, course_id, lec_id)
{
	$('#frmLec')[0].reset();
	$('input[name="ltype"]').removeAttr('checked');
	$('#lnotify').removeAttr('checked');
	toggleFileTypes('');
	if(info=='add'){
		$('#clModal #clTitle').html('Add Lecture');
		$('#clModal #clSubmit').html('Save');
		$('#clModal #cid').val(course_id);
		$('#clModal #lec_id').val(lec_id);
		$('#clModal').modal('show');
	}else if(info=='edit'){
		$('#loading').show();
		$.ajax({
			url:baseURL+'Teacher/getLecture/?clid='+lec_id,
			type: 'GET',
			success: (res)=>{
				var obj = JSON.parse(res);
				//console.log(obj)
				$('#clModal #ltitle').val(obj[0].title);
				$('#clModal #ldate').val(obj[0].lec_date);
				$('#clModal #clTitle').html('Update Lecture');
				$('#clModal #clSubmit').html('Update');
				$('#clModal #cid').val(course_id);
				$('#clModal #lec_id').val(lec_id);
				$('#'+obj[0].file_type).attr('checked', 'checked');
				toggleFileTypes(obj[0].file_type);
				$('#llink').val(obj[0].link);
				if((obj[0].notify).trim()!='f'){
					$('#lnotify').attr('checked', 'checked');
				}else{
					$('#lnotify').removeAttr('checked');
				}
				$('#loading').hide();
				$('#clModal').modal('show');
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
}

function openYTModal(lec_id)
{
	$('#loading').show();
	$.ajax({
		url:baseURL+'Teacher/getLecture/?clid='+lec_id,
		type: 'GET',
		success: (res)=>{
			$('#loading').hide();
			var obj = JSON.parse(res);
			
			$('#YTModal #ytbody').html(obj[0].link);
			$('#YTModal #ytbody iframe').attr('width', '100%');
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

function getLecFile(lec_id)
{
	$('#loading').show();
	$.ajax({
		url:baseURL+'Teacher/getLectureFile/?clid='+lec_id,
		type: 'GET',
		success: (res)=>{
			$('#loading').hide();
			if(res!='0'){
				window.open(baseURL+'uploads/courselra/'+res, '_blank');
			}else{
				$.notify({
					icon:"add_alert",
					message: "Sorry! File is missing."},
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

function deleteLecture(lec_id, lec_title)
{
	Swal.fire({
	  title: 'Are you sure?',
	  text: "You want to remove lecture: "+lec_title,
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	  if (result.value) {
		$('#loading').show();
			$.ajax({
				url:baseURL+'Teacher/deleteLectures?clid='+lec_id,
				type: 'GET',
				success: (res)=>{
					$('#loading').hide();
					$('#cl_'+lec_id).remove();
					if(res){
						$.notify({
							icon:"add_alert",
							message: "The Lecture:- "+lec_title+" has been deleted"},
							{type:"success",
							timer:3e3,
							placement:{from:'top',align:'right'}
						})
					}else{
						$.notify({
							icon:"add_alert",
							message: "The Lecture:- "+lec_title+" deletion error"},
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