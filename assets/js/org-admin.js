$(document).ready(function(){
    $('.icon-container').hide();
    //organizationAndDepartment();
    /*programTable();
    preProgramTypeCheck();
    preProgramFeeCheck();
    teacherList();*/
	// Organization Add And Update Operation
	$('#frmOrg').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		submitHandler: function(form, e) {
			$('#loading').css('display', 'block');
			e.preventDefault();
			var frmData = new FormData($('#frmOrg')[0]);
			$.ajax({
				url: baseURL+'Admin/cuOrganization',
				type: 'POST',
				data: frmData,
				cache : false,
				processData: false,
				contentType: false,
				enctype: 'multipart/form-data',
				async: false,
				success: (res, textStatus, xhr)=>{ 
					$('#organizationModal').modal('hide');
					$('#frmOrg')[0].reset();
					$('#loading').css('display', 'none');
					if(xhr.status===200){
						if(res){
							organizationAndDepartment();
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
				url: baseURL+'Admin/cuDepartment',
				type: 'POST',
				data: frmData,
				cache : false,
				processData: false,
				contentType: false,
				enctype: 'multipart/form-data',
				async: false,
				success: (res, textStatus, xhr)=>{ 
					$('#departmentModal').modal('hide');
					$('#frmDept')[0].reset();
					$('#loading').css('display', 'none');
					if(xhr.status===200){
						if(res){
							organizationAndDepartment();
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
			org: {
				required: true
			},
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
			org: {
				required: 'This field is required.'
			},
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

/*********************************************************************************************************************************/
/*                                          Organization And Department                                                          */
/*********************************************************************************************************************************/

// Organization List
function organizationAndDepartment(){
    $('#prg_progress').show();
    $.ajax({
        url : baseURL+'Admin/organizationAndDepartment',
        type : 'POST',
        success: function(data){
            $('#prg_progress').hide();
            $('#prog_department').html(data);
        }
    })
}
/***************************************************** */
// Organization Add And Update Modal
function organizationModal(oper,id=null){
	
    var operId = (id==null)? oper+'_org' : oper+'_org_'+id;
    $('#'+operId).html('<i class="fa fa-circle-o-notch fa-spin"></i> loading...');
    $.ajax({
        url:baseURL+'Admin/organizationModalCall',
        type: "GET",
        data:{
            id : id
        },
        success: function(data, textStatus, xhr){ 
			//$('#organizationModal').resizable();
			//console.log(xhr)
			if(xhr.status===200){
				$('#organizationModal').modal('show');
				$('#organizationModalLabel').html(oper+' Organization');
				$('#organizationModalBody').html(data);
				$('#'+operId).html('<i class="material-icons">'+oper.toLowerCase()+'</i> '+oper+' Organization');
			}else{
				$('#'+operId).html('<i class="material-icons">'+oper.toLowerCase()+'</i> '+oper+' Organization');
				Swal.fire("Sorry","There is some error", "error");
			}
			
        }
    });
}
/************************************************************ */

// Department Add And Update Modal
function departmentModal(oper,org_id,id=null, po_title){
    var operId = (id==null)? oper+'_dept_'+org_id : oper+'_dept_'+id;
	$('#'+operId).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
    $.ajax({
        url:baseURL+'Admin/departmentModalCall',
        type: "GET",
        data:{
            org_id : org_id,
			po_title: po_title,
            id     : id
        },
        success: function(data, textStatus, xhr){
			
			if(xhr.status===200){
				$('#departmentModal').modal('show');
				$('#departmentModalLabel').html(oper+' Department');
				$('#departmentModalBody').html(data);
				$('#'+operId).html(((id==null)? '<i class="material-icons">'+oper.toLowerCase()+'</i> '+oper+' Department' : '<i class="material-icons">'+oper.toLowerCase()+'</i>'));
			}else{
				$('#'+operId).html(((id==null)? '<i class="material-icons">'+oper.toLowerCase()+'</i> '+oper+' Department' : '<i class="material-icons">'+oper.toLowerCase()+'</i>'));
				Swal.fire("Sorry","Something went wrong!", "error");
			}
        }
    });
}
function deleteDepartment(id, title)
{
	Swal.fire({
		title: 'Are you sure?',
		text: "You want to delete department: "+title,
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!',
		showLoaderOnConfirm: true,
	  preConfirm: () => {
		return fetch(baseURL+'Admin/deleteDepartment/?id='+id)
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
	  console.log(result)
	  if (result.value) {
		$('#list_'+id).remove();
		Swal.fire(
		  'Successfull',
		  'The department has been remove.',
		  'success'
		)
	  }else{
		  Swal.fire(
			  'Error',
			  'Something went wrong. Could not delete.',
			  'error'
			)
	  }
	})
}
/*********************************************************************************************************************************/
/*                                                  Program                                                                      */
/*********************************************************************************************************************************/

function programTable(){
    $('#prg_progress').css('display', 'block');
    $('#porgList').html('');
	var org_id = $('#org').val();
	var dept_id = $('#dept').val();
	departByOrganization(org_id, null);
    $.ajax({
        url  : baseURL+'Admin/programTable',
        type : 'GET',
		data: { org_id: org_id, dept_id: dept_id }, 
        success: function(data){
            $('#progTable').DataTable().destroy();
			$('#prg_progress').css('display', 'none');
            $('#porgList').html(data);
			$('#progTable').DataTable().draw();
        }
    });
}

function departByOrganization(org_id, dept_id){
    //$('#dept').html('').selectpicker('refresh');
	//alert(org_id+', '+dept_id)
    $('.icon-container').show();
    if(org == ''){
        $('.icon-container').hide();
        $('#dept').html('').selectpicker('refresh');
    }else{
        $('.icon-container').show();
        $.ajax({
            url  : baseURL+'Admin/departmentByOrganization',
            type : 'GET',
            data :{
                org : org_id
            },
            success : function(data){
                $('.icon-container').hide();
				console.log(data);
                $('#dept').html(data);
				$('#dept').selectpicker('refresh');
            },
			complete: ()=>{
				
				if(dept_id!=null){
					$('#dept').selectpicker('val', dept_id);
				}
			}
        });
    }
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
		return fetch(`${baseURL}Admin/removeUserOrgLinks/?uid=${uid}&oid=${oid}`)
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

/*********************************************************************************************************************************/
/*                                                  Teacher                                                                      */
/*********************************************************************************************************************************/


function teacherList(organization=null,department=null){
    $('#prg_progress').show();
    $.ajax({
        url  : baseURL+'Admin/teacherList',
        type : 'GET',
        data : {
            org : organization,
            dept: department
        },
        success: function(resp){
            $('#profTable').DataTable().destroy();
			$('#prg_progress').css('display', 'none');
            $('#profList').html(resp);
			//$('#testList').html(resp);
			//console.log(resp)
			$('#profTable').DataTable().draw();
        }
    });
}
function getLinkedTeachers(pid)
{
	$('#prg_progress').show();
    $.ajax({
        url  : baseURL+'Admin/linkedTeacherList',
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
		url: baseURL+'Admin/linkProgTeachers',
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

