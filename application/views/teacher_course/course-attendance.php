<style>
span.present{
	color: green;
}
span.absent{
	color: red;
}
</style>
<div class="row">

    <div class="col-md-12">
        <div class="card mt-2" style="background-color: #e6ecf6;">
            <div class="card-header card-header-info card-header-text">
                <div class="card-text">
                    <h4 class="card-title"><?php echo $title; ?></h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <span>From: </span>
                        <input id="start_date" class="form-control" type="date" name="from" value="<?=$from?>">
                    </div>
                    <div class="col-md-3">
                        <span>To: </span>
                        <input id="end_date" class="form-control" type="date" name="to" value="<?=$to?>">
                    </div>
                    <div class="col-md-3">
                        <span>Live Class: </span>
                        <select class="form-control" name="live_class" id='live_class'>
                            <option value="select">Select a Class</option>
                            <?php
								foreach ($live_classes as $lc) {
									echo "<option value='".$lc->id."'>".$lc->start_time."</option>";
								}
							?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary" id="show-attendance" type="button">Show Attendance</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
						<table class="table table-no-bordered table-striped data-hover dataTable no-footer">
							<thead>
								<tr>
									<th>#</th>
									<th>Name of the Student</th>
                                    <th>Class Start Time</th>
                                    <th>Joined On</th>
									<th>Attendance</th>
								</tr>	
							</thead>
							<tbody id="tbody">
								
							</tbody>
						</table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function() {
	$('input#start_date').change(function(){
		refreshClasses();
	})

	$('input#end_date').change(function(){		
		refreshClasses();
	})


	function refreshClasses(){
		var teacher_id = <?=$userid?>;
		var start_date = $('input#start_date').val();
		var end_date = $('input#end_date').val();

		

		$.ajax({
            url: baseURL + 'Liveclass/getLiveClasses',
            type: 'GET',
            data: {
                teacher_id: teacher_id, from: start_date, to: end_date
            },
            success: (res) => {
                var obj = JSON.parse(res);
                if (obj['status'] == 'success') {
					$('#tbody').html('');
					var result = "<option value='select'>Select a Class</option>";
					obj['data'].forEach(element => {
						result += "<option value='"+element.id+"'>"+element.start_time+"</option>";
					});
					$('select#live_class').html(result);
                }
            },
            error: (errors) => {
                console.log(errors);
            }
        });
	}

    $('button#show-attendance').click(function() {
        event.preventDefault();
        var live_id = $('select#live_class').val();
        if (live_id == 'select') {
            //alert("Please select a class");
			$.notify({icon:"add_alert",message:'Please select a class'},{type:'warning',timer:3e3,placement:{from:'top',align:'right'}})
            return;
        }
        $.ajax({
            url: baseURL + 'Liveclass/getAttendance',
            type: 'GET',
            data: {
                live_id: live_id
            },
            success: (res) => {
                var obj = JSON.parse(res);
                if (obj['status'] == 'success') {
					var tdata = "";
					var slno = 1;
					obj['data'].forEach(element => {
						tdata += "<tr>";
						tdata += "<td>"+slno+".</td>";
						tdata += "<td>"+element.name+"</td>";
                        var st = moment(element.start_time);
                        var strST = st.format("DD-MM-YYYY hh:mm:ss a");
                        tdata += "<td>"+strST+"</td>";
                        if(element.join_time != null){
                            var jt = moment(element.join_time);
                            var strJT = jt.format("DD-MM-YYYY hh:mm:ss a");
                            tdata += "<td>"+strJT+"</td>";
                        }else{
                            tdata += "<td>&nbsp;</td>";
                        }
                        
						tdata += "<td>"+(element.status == "P" ? "<span class='present'>Present</span>" : "<span class='absent'>Absent</span>")+"</td>";
						tdata += "</tr>";
						slno++;
					});
					$('#tbody').html(tdata);
                } else {
					$.notify({icon:"add_alert",message:obj['msg']},{type:'warning',timer:3e3,placement:{from:'top',align:'right'}})
                    //alert(obj['msg']);
                }
            },
            error: (errors) => {
                console.log(errors);
            }
        });
    });
});
</script>