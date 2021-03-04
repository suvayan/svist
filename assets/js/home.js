$(document).ready(function() {
	getProgramNotices();
	$('#notdetails').summernote();
	$('#frmNotice').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		submitHandler: function(form, e) {
			$('#loading').css('display', 'block');
			e.preventDefault();
			var frmNoticeData = new FormData($('#frmNotice')[0]);
			$.ajax({
				url: baseURL+'Teacher/addNotice',
				type: 'POST',
				data: frmNoticeData,
				cache : false,
				processData: false,
				contentType: false,
				enctype: 'multipart/form-data',
				async: false,
				success: (res)=>{ 
					$('#noticeM').modal('hide');
					$('#frmNotice')[0].reset();
					$('#loading').css('display', 'none');
					var obj = JSON.parse(res);
					//console.log(obj)
					swal(
					  'Notice',
					  obj.msg,
					  obj.status
					).then(result=>{
						getProgramNotices();
					});
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	});
	$('#frmSchedule').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		submitHandler: function(form, e) {
			$('#loading').css('display', 'block');
			e.preventDefault();
			var frmSchData = new FormData($('#frmSchedule')[0]);
			$.ajax({
				url: baseURL+'Teacher/addScheduleClass',
				type: 'POST',
				data: frmSchData,
				cache : false,
				processData: false,
				contentType: false,
				async: false,
				success: (res)=>{ 
					$('#schedule').modal('hide');
					$('#frmSchedule')[0].reset();
					$('#loading').css('display', 'none');
					var obj = JSON.parse(res);
					//console.log(obj)
					swal(
					  'Class',
					  obj.msg,
					  obj.status
					).then(result=>{
						getProgramNotices();
					});
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	});
	$('#frmLive').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		submitHandler: function(form, e) {
			$('#loading').css('display', 'block');
			$('#liveBt').attr('disabled', true);
			e.preventDefault();
			var frmLiveData = new FormData($('#frmLive')[0]);
			$.ajax({
				url: baseURL+'Teacher/checkValidCourse',
				type: 'POST',
				data: frmLiveData,
				cache : false,
				processData: false,
				contentType: false,
				async: false,
				success: (res)=>{ 
					$('#frmLive')[0].reset();
					$('#liveBt').removeAttr('disabled');
					$('#loading').css('display', 'none');
					var obj = JSON.parse(res);
					if(obj['status']=='success'){
						window.open(baseURL+'Teacher/liveClass/?id='+btoa(obj['cid']), '_self');
					}else{
						//alert(obj['msg']);
						swal(
						  'Course Code',
						  obj['msg'],
						  obj['status']
						);
					}
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	});
});

$("#fl_notice").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

function getProgCourses(prog_sl, cp_id)
{
	var crList='<option value="0">All Courses</option>';
	$.ajax({
		url: baseURL+'Teacher/getCoursebyProg',
		type: 'GET',
		data: {sl: prog_sl},
		success: (res)=>{
			var obj = JSON.parse(res);
			$.each(obj, (i, val)=>{
				crList+='<option value="'+val['id'].trim()+'">'+val['title'].trim()+' ('+val['c_code'].trim()+')</option>';
			});
			$('#'+cp_id+'course').html(crList);
			$('#'+cp_id+'course').selectpicker('refresh');
		},
		error: (errors)=>{
			console.log(errors);
		}
	})
}