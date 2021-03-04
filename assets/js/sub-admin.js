$(document).ready(function(){
	$('#frmOrg').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		submitHandler: function(form, e) {
			$('#loading').css('display', 'block');
			e.preventDefault();
			var frmData = new FormData($('#frmOrg')[0]);
			$.ajax({
				url: baseURL+'Subadmin/updateOrganization',
				type: 'POST',
				data: frmData,
				cache : false,
				processData: false,
				contentType: false,
				enctype: 'multipart/form-data',
				async: false,
				success: (res, textStatus, xhr)=>{ 
					$('#organizationEditModal').modal('hide');
					$('#frmOrg')[0].reset();
					$('#loading').css('display', 'none');
					if(xhr.status===200){
						if(res){
							getOrganizationName();
							Swal.fire("Congratulation","Organization is successfuly submitted","success");
						}else{
							Swal.fire("Sorry","There is some error", "error");
						}
					}else{
						Swal.fire("Sorry","Something went wrong. Please try again", "error");
					}
					
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	});
	$('#frmDept').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		submitHandler: function(form, e) {
			$('#loading').css('display', 'block');
			e.preventDefault();
			var frmData = new FormData($('#frmDept')[0]);
			$.ajax({
				url: baseURL+'Subadmin/cuDepartment',
				type: 'POST',
				data: frmData,
				cache : false,
				processData: false,
				contentType: false,
				enctype: 'multipart/form-data',
				async: false,
				success: (res, textStatus, xhr)=>{ 
					$('#departmentAddEditModal').modal('hide');
					$('#frmDept')[0].reset();
					$('#loading').css('display', 'none');
					if(xhr.status===200){
						if(res){
							departmentList();
							Swal.fire("Congratulation","Department is successfuly submitted","success");
						}else{
							Swal.fire("Sorry","There is some error", "error");
						}
					}else{
						Swal.fire("Sorry","Something went wrong. Please try again", "error");
					}
					
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	});

	$('#frmTeacher').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		rules: {
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
		}
	});
});

function getOrganizationName(){
    $.ajax({
        url: baseURL+'Subadmin/getOrganizationName',
        type: 'POST',
        data:{job:'getOrganizationName'},
        cache : false,
        success: function(data){
            $('#card_header').html(data);
        }        
    })
}

function departmentList(){
    $('#prg_progress').show();
    $.ajax({
        url: baseURL+'Subadmin/getDepartmentByOrganization',
        type: 'POST',
        data:{job:'getDepartmentByOrganization'},
        cache : false,
        // processData: false,
        // contentType: false,
        success: function(data){
            $('#prg_progress').hide();
            $('#prog_department').html(data);
        }
    });
}

function organizationEditModal(oper,id){
    $('#org_update').html('<i class="fa fa-circle-o-notch fa-spin"></i> loading...');
    $.ajax({
        url:baseURL+'Subadmin/organizationEditModal',
        type: 'POST',
        data:{
            id  : id,
            job :'organizationEditModal'
        },
        cache : false,
        success: function(data, textStatus, xhr){ 
			//$('#organizationModal').resizable();
			//console.log(xhr)
			if(xhr.status===200){
				$('#organizationEditModal').modal('show');
				$('#organizationEditModalLabel').html(oper+' Organization');
				$('#organizationEditModalBody').html(data);
				$('#org_update').html('<i class="material-icons">'+oper.toLowerCase()+'</i> '+oper+' Organization');
			}else{
				$('#org_update').html('<i class="material-icons">'+oper.toLowerCase()+'</i> '+oper+' Organization');
				Swal.fire("Sorry","There is some error", "error");
			}
			
        }
    });
}

function departmentAddEditModal(oper,org_id,id=null, po_title){
   let btnId = (id==null)? oper+'_dept_'+org_id : oper+'_dept_'+id;
   $('#'+btnId).html('<i class="fa fa-circle-o-notch fa-spin"></i> loading...');
   $.ajax({
       url   : baseURL+'Subadmin/departmentAddEditModal',
       type  : 'POST',
       data  : {
          org_id   : org_id,
          id       : id,
          po_title : po_title,
          job      : 'departmentAddEditModal'
       },
       cache : false,
       success: function(data, textStatus, xhr){
            if(xhr.status===200){
                $('#departmentAddEditModal').modal('show');
                $('#departmentAddEditModalLabel').html(oper+' Department');
                $('#departmentAddEditModalBody').html(data);
                $('#'+btnId).html(((id==null)? '<i class="material-icons">'+oper.toLowerCase()+'</i> '+oper+' Department' : '<i class="material-icons">'+oper.toLowerCase()+'</i>'));
            }else{
                $('#'+btnId).html(((id==null)? '<i class="material-icons">'+oper.toLowerCase()+'</i> '+oper+' Department' : '<i class="material-icons">'+oper.toLowerCase()+'</i>'));
                Swal.fire("Sorry","Something went wrong!", "error");
            }
        }
   })
}

function deleteDepartment(id){
    $.ajax({
        url   :baseURL+'Subadmin/deleteDepartment',
        type  : 'POST',
        data  : {id : id},
        cache : false,
        success: function(data, textStatus, xhr){
             if(xhr.status===200){
                Swal.fire("Success","Successfully deleted", "success");
                departmentList();
             }else{
                Swal.fire("Sorry","Something went wrong!", "error");
             }
         }
    })
}




function teacherList(){
	$('#prg_progress').show();
	$.ajax({
		url  : baseURL+'Subadmin/teacherList',
		type : 'POST',
		data : {job:'teacherList'},
		success: function(resp){
			//console.log(resp);
			$('#techTable').DataTable().destroy();
			$('#prg_progress').css('display', 'none');
			$('#techList').html(resp);
			$('#techTable').DataTable().draw();
		}	
	});
}


function removeLink(uid, oid){
	
    Swal.fire({
	title: "Are you sure?",
	text: "You will not be able to revert it",
	type: "warning",
	showCancelButton: true,
	confirmButtonColor: "#DD6B55",
	confirmButtonText: "Yes, delete it!",
	closeOnConfirm: false,
	  preConfirm: () => {
		return fetch(`${baseURL}Subadmin/removeUserOrgLinks/?uid=${uid}&oid=${oid}`)
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
	  if(result.value){
		  window.location.reload();
	  }else{
		  Swal.fire(
			  'Unsuccessful!',
			  'Something went wrong. Could remove the Link.',
			  'error'
			)
	  }
	})
}

function programList(){
	$('#prg_progress').show();
	$.ajax({
		url  : baseURL+'Subadmin/programList',
		type : 'POST',
		//data : {job:'teacherList'},
		success: function(resp){
			//console.log(resp);
			$('#progTable').DataTable().destroy();
			$('#prg_progress').css('display', 'none');
			$('#porgList').html(resp);
			$('#progTable').DataTable().draw();
		}	
	});	
}

function getLinkedTeachers(pid)
{
	$('#prg_progress').show();
    $.ajax({
        url  : baseURL+'Subadmin/linkedTeacherList',
        type : 'GET',
        data : {
            pid : pid
        },
        success: function(resp){
            $('#profTable').DataTable().destroy();
			$('#prg_progress').css('display', 'none');
            $('#profList').html(resp);
			$('#profTable').DataTable().draw();
        }
    });
}

$('#frmProfs').on('submit', (e)=>{
	e.preventDefault();
	var prog_id = $('#prog_id').val();
	$('#btn_oper').html('<i class="fa fa-circle-o-notch fa-spin"></i>');
	var frmData = new FormData($('#frmProfs')[0]);
	$.ajax({
		url: baseURL+'Subadmin/linkProgTeachers',
		type: 'POST',
		data: frmData,
		processData: false,
		contentType: false,
		success: (resp)=>{
			$('#btn_oper').html('Link It!');
			var obj = JSON.parse(resp);
			if(obj['status']){
				window.location.reload();
				//getLinkedTeachers(prog_id);
			}else{
				Swal.fire("Sorry",obj['errors'], "error");
			}
		},
		error: (errors)=>{
			$('#btn_oper').html('Link It!');
		}
	});
});