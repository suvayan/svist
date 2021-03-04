var count = $('#afcount').val();
	
$('#frmAssgn').validate({
	errorPlacement: function(error, element) {
	  $(element).closest('.form-group').append(error);
	},
	submitHandler: function(form, e) {
		$('#loading').css('display', 'block');
		e.preventDefault();
		var frmAssgnData = new FormData($('#frmAssgn')[0]);
		$.ajax({
			url: baseURL+'Teacher/cuCAssignment',
			type: 'POST',
			data: frmAssgnData,
			cache : false,
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			async: false,
			success: (res)=>{ 
				$('#caModal').modal('hide');
				$('#loading').hide();
				var obj = JSON.parse(res);
				Swal.fire(
				  'Assignment',
				  obj.msg,
				  obj.status
				).then(result=>{
					$('#assign').click();
					//window.location.reload();
				})
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
});
  $('#frmAssgnFiles').validate({
	errorPlacement: function(error, element) {
	  $(element).closest('.form-group').append(error);
	},
	submitHandler: function(form, e) {
		$('#loading').css('display', 'block');
		e.preventDefault();
		var frmAssgnFile = new FormData($('#frmAssgnFiles')[0]);
		$.ajax({
			url: baseURL+'Teacher/cuCAssignmentFiles',
			type: 'POST',
			data: frmAssgnFile,
			cache : false,
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			async: false,
			success: (res)=>{ 
				$('#cafModal').modal('hide');
				$('#loading').hide();
				Swal.fire(
				  'Assignment File/s',
				  'has been uploaded',
				  'success'
				).then(result=>{
					$('#assign').click();
					//window.location.reload();
				})
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
  });

function customFileName(customid)
{
	var fileName = $('#'+customid).val().split("\\").pop();
	$('#'+customid).siblings(".custom-file-label").addClass("selected").html(fileName);
}

function assignmentModal(info, course_id, assgn_id)
{
	$('#frmAssgn')[0].reset();
	$('#aPublish').removeAttr('checked');
	$('#anotify').removeAttr('checked');
	$('#caModal #adetails').summernote('code','');
	if(info=='add'){
		$('#caModal #caTitle').html('Add Assignment');
		$('#caModal #caSubmit').html('Save');
		$('#caModal #cid').val(course_id);
		$('#caModal #assgn_id').val(assgn_id);
		$('#caModal').modal('show');
	}else if(info=='edit'){
		$('#loading').css('display', 'block');
		$.ajax({
			url:baseURL+'Teacher/getAssignment/?caid='+assgn_id,
			type: 'GET',
			success: (res)=>{
				var obj = JSON.parse(res);
				
				$('#caModal #atitle').val(obj[0].title.trim());
				$('#caModal #adetails').summernote('code',obj[0].details.trim());
				$('#caModal #amarks').val(obj[0].marks.trim());
				$('#caModal #sdate').val(obj[0].tdate);
				$('#caModal #ddate').val(obj[0].deadline);
				$('#caModal #caTitle').html('Update Assignment');
				$('#caModal #caSubmit').html('Update');
				$('#caModal #cid').val(course_id);
				$('#caModal #assgn_id').val(assgn_id);
				if(obj[0].publish=='f'){
					$('#aPublish').removeAttr('checked');
				}else{
					$('#aPublish').attr('checked', true);
				}
				if(obj[0].notify=='f'){
					$('#anotify').removeAttr('checked');
				}else{
					$('#anotify').attr('checked', true);
				}
				$('#loading').hide();
				$('#caModal').modal('show');
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
}

function assignmentFileModal(course_id, assgn_id)
{
	$('#cafModal #fcid').val(course_id);
	$('#cafModal #fassgn_id').val(assgn_id);
	$('#cafModal').modal('show');
}
function addMore(type)
{
	count++;
	
	var fieldset = document.createElement("fieldset");
	fieldset.setAttribute("class", "field p-3 mb-3 removeclass" + count);
	fieldset.style.border = "2px solid #E8E8E8";
	$('#afcount').val(count);
	
	if(type=='fl'){
		fieldset.innerHTML = '<button type="button" class="close" onClick="removeNow('+count+');" aria-hidden="true"><i class="material-icons">clear</i></button><div class="form-group"><input type="hidden" value="fl" name="files'+count+'" id="files'+count+'"><div class="custom-file" style="margin-bottom:20px;"><input type="file" class="custom-file-input" onChange="customFileName(`fl_link'+count+'`)" name="fl_link'+count+'" id="fl_link'+count+'" accept=".pdf, .doc, .docx, .xls, .xlsx, .jpg" required="true"><label class="custom-file-label" for="fl_link'+count+'">Choose Files/Videos</label></div></div>';
	}else{
		fieldset.innerHTML = '<button type="button" class="close" onClick="removeNow('+count+');" aria-hidden="true"><i class="material-icons">clear</i></button><div class="form-group"><input type="hidden" value="lk" name="link'+count+'" id="link'+count+'"><label for="lk_link'+count+'" class="text-dark">Copy-past Link</label><textarea class="form-control w-100" cols="80" rows="5" name="lk_link'+count+'" id="lk_link'+count+'" url="true.html" required="true"></textarea></div>';
	}
	
	$("#cal_fields").append(fieldset);
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

function deleteAssignment(assid, atitle)
{
	Swal.fire({
	  title: 'Are you sure?',
	  text: "You want tp delete Assigment: "+atitle,
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	  if (result.value) {
		$('#loading').css('display', 'block');
		$.ajax({
			url:baseURL+'Teacher/deleteAssignments?caid='+assid,
			type: 'GET',
			success: (res)=>{
				$('#loading').hide();
				$('#ca_'+assid).remove();
				if(res){
					$.notify({
						icon:"add_alert",
						message: "The Assignment:- "+atitle+" has been deleted"},
						{type:"success",
						timer:3e3,
						placement:{from:'top',align:'right'}
					})
				}else{
					$.notify({
						icon:"add_alert",
						message: "The Assignment:- "+atitle+" deletion error"},
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
function deleteAssgnFiles(caid)
{
	Swal.fire({
	  title: 'Are you sure?',
	  text: "You want to delete this assignment file?",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	  if (result.value) {
		$('#loading').css('display', 'block');
		$.ajax({
			url:baseURL+'Teacher/deleteAssignmentFiles?caid='+caid,
			type: 'GET',
			success: (res)=>{
				$('#loading').hide();
				$('#alert_'+caid).remove();
				if(res){
					$.notify({
						icon:"add_alert",
						message: "The assignment file has been deleted"},
						{type:"success",
						timer:3e3,
						placement:{from:'top',align:'right'}
					})
				}else{
					$.notify({
						icon:"add_alert",
						message: "The assignment file deletion error"},
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