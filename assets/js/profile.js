function professionalModal(func, pid)
{
	if(func=='add'){
		$('#pid').val(pid);
		$('#profTitle').html('Add Professional Details');
		$('#profSubmit').html('Save');
		$('#profModal').modal('show');
	}else if(func=='edit'){
		$.ajax({
			url: baseURL+'en/getProfessional/?id='+pid,
			type: 'GET',
			async: false,
			success: (resp)=>{
				var obj = JSON.parse(resp);
				$('#org_name').val(obj[0]['company']);
				$('#designation').val(obj[0]['designation_id']).selectpicker('refresh');
				$('#datefrom').val(obj[0]['from']);
				$('#dateto').val(obj[0]['to']);
				$('#industry').val(obj[0]['industry_id']).selectpicker('refresh');
				$('#com_website').val(obj[0]['website']);
				$('#curr_ctc').val(obj[0]['current_ctc']);
				$('#notice').val(obj[0]['notice_period']);
				$('#pid').val(pid);
				$('#profTitle').html('Edit Professional Details');
				$('#profSubmit').html('Update');
				$('#profModal').modal('show');
			}
		});
	}
}
function deleteProfessional(pid)
{
	Swal.fire({
	  title: 'Are you sure?',
	  text: "You want to remove this professional detail!",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	  if (result.value) {
		  $.ajax({
			url: baseURL+'en/deleteProfessional/?id='+pid,
			type: 'GET',
			async: false,
			success: (resp)=>{
				if(resp){
					$('#prof_'+pid).remove();
					Swal.fire(
					  'Deleted!',
					  'The professional detail has been deleted.',
					  'success'
					)
				}else{
					Swal.fire(
					  'Error!',
					  'The professional detail could not be deleted.',
					  'error'
					)
				}
			}
		});
		
	  }
	})
}
function academicModal(func, acaid)
{
	if(func=='add'){
		$('#aid').val(acaid);
		$('#acaTitle').html('Add Academic Details');
		$('#acaSubmit').html('Save');
		$('#acaModal').modal('show');
	}else if(func=='edit'){
		$.ajax({
			url: baseURL+'en/getAcademic/?id='+acaid,
			type: 'GET',
			async: false,
			success: (resp)=>{
				var obj = JSON.parse(resp);
				$('#ins_name').val(obj[0]['organization']);
				$('#course_type').val(obj[0]['degree_id']).selectpicker('refresh');
				$('#acadatefrom').val(obj[0]['from']);
				$('#acadateto').val(obj[0]['to']);
				$('#marks').val(obj[0]['marks_per']);
				$('#stream').val(obj[0]['stream_id']).selectpicker('refresh');
				$('#aid').val(acaid);
				$('#acaTitle').html('Edit Academic Details');
				$('#acaSubmit').html('Update');
				$('#acaModal').modal('show');
			}
		});
	}
}
function deleteAcademic(acaid)
{
	Swal.fire({
	  title: 'Are you sure?',
	  text: "You want to remove this academic detail!",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	  if (result.value) {
		  $.ajax({
			url: baseURL+'en/deleteAcademic/?id='+acaid,
			type: 'GET',
			async: false,
			success: (resp)=>{
				if(resp){
					$('#aca_'+acaid).remove();
					Swal.fire(
					  'Deleted!',
					  'The academic detail has been deleted.',
					  'success'
					)
				}else{
					Swal.fire(
					  'Error!',
					  'The academic detail could not be deleted.',
					  'error'
					)
				}
			}
		});
		
	  }
	})
}

function getStates(country_id)
{
	var statedd = '';
	$.ajax({
		url: baseURL+'en/getAllStates',
		type: 'GET',
		data: {
			id: country_id
		},
		async: false,
		success: (res)=>{
			var data = JSON.parse(res);
			jQuery.each(data, (i, val)=>{
				statedd+='<option value="'+val['id']+'">'+val['name']+'</option>';
			})
		}
	});
	$('#state').html(statedd).selectpicker('refresh');
}
function getCities(state_id, city_id)
{
	var citydd = '';
	$.ajax({
		url: baseURL+'en/getAllCities',
		type: 'GET',
		data: {
			id: state_id
		},
		async: false,
		success: (res)=>{
			var data = JSON.parse(res);
			jQuery.each(data, (i, val)=>{
				if(city_id!=''){
					citydd+='<option value="'+val['id']+'" selected>'+val['name']+'</option>';
				}else{
					citydd+='<option value="'+val['id']+'">'+val['name']+'</option>';
				}
			})
		}
	});
	$('#city').html(citydd).selectpicker('refresh');
}