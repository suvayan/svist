function applyToProgram(stud, prof, prog)
{
	if(stud=='1' && prof==''){
	  Swal.fire({
		  title: 'Apply',
		  text: "Do you want to enroll as a Student?",
		  type: 'question',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, please!'
		}).then((result) => {
		  if (result.value) {
			sendProgRoleData('Student', prog);
		  }
		});
  }else if(stud=='' && prof=='1'){
	  Swal.fire({
		  title: 'Apply',
		  text: "Do you want to apply as a role of a Teacher?",
		  type: 'question',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, please!'
		}).then((result) => {
		  if (result.value) {
			sendProgRoleData('Teacher', prog);
		  }
		});
  }else if(stud=='1' && prof=='1'){
	  Swal.fire({
		  title: 'Apply',
		  html: '<form><h4>Select the available option below</h4><div class="checkbox-radios"><div class="form-check form-check-inline"><label class="form-check-label"><input class="form-check-input" type="radio" value="Teacher" name="roleType" id="roleProf" required="true"> Teacher<span class="form-check-sign"><span class="check"></span></span></label></div><div class="form-check form-check-inline"><label class="form-check-label"><input class="form-check-input" type="radio" value="Student" name="roleType" id="roleStud" required="true"> Student<span class="form-check-sign"><span class="check"></span></span></label></div></div></form>',
		  type: 'question',
		  showCloseButton: true,
		  showCancelButton: true,
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancel',
		  confirmButtonColor: '#3085d6',
		  confirmButtonText: 'Save'
		}).then((result) => {
			if((result.value)){
				var role = $("input[type='radio'][name='roleType']:checked"). val();
				if(role!=null){
					sendProgRoleData(role, prog);
				}
			}
		});
  }
}
function sendProgRoleData(role, prog)
{
	$.ajax({
		url: baseURL+uType+'/applyRoleToPRogram',
		type: 'POST',
		data: {role: role, pid: prog},
		success: (res)=>{
			if(res=='1')
			{
				Swal.fire(
				  'Applied!',
				  'You have successfully applied for '+role+'. Please wait for approval',
				  'success'
				).then(result=>{
					window.location.reload();
				})
			}else if(res=='0'){
				Swal.fire(
				  'Failed!',
				  'Your role as a '+role+' failed. Server error.',
				  'error'
				)
			}else if(res=='3'){
				Swal.fire(
				  'Failed!',
				  'Your role as a '+role+' is already has an entry. Check your dashboard for status.',
				  'error'
				)
			}
		},
		error: (errors)=>{
			console.log(errors);
		}
	})
}