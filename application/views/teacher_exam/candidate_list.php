<div class="col-md-12">
<?php if(!empty($testpub)){ ; ?>
	<div class="align-items-center d-flex">
		<input type="hidden" name="tp_id" id="tp_id" value="<?php echo $testpub[0]->tp_id; ?>"/>
		<div class="form-check">
		  <label class="form-check-label">
			<input class="form-check-input" type="checkbox" value="" id='checkallusers'>
			<span class="form-check-sign">
			  <span class="check"></span>
			</span>
			Select All
		  </label>
		</div>
		<button type="button" class="btn btn-sm btn-info" onClick="inviteSelected();" id="invite_select" disabled>Invite Selected</button>
	</div>
	<div class="table-responsive">
	<table class="table table-striped" width="100%">
		<thead>
			<tr>
				<th width="5%">#</th>
				<th width="25%">Candidates</th>
				<th width="25%">Contact Details</th>
				<th width="20%">Test Key</th>
				<th width="25%">Test Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i=1;
				if(!empty($candi)){
					foreach($candi as $row){
						echo '<tr>';
						echo '<td>';
						if(empty(${'rtc_'.$i})){
							echo '<div class="form-check">
									  <label class="form-check-label">
										<input class="form-check-input chkcandi" type="checkbox" value="'.$row->id.'" name="chk_candi[]" id="ch_candi_'.$i.'">
										<span class="form-check-sign">
										  <span class="check"></span>
										</span>
									  </label>
									</div>';
						}else{
							echo $i;
						}
						echo '</td>';
						echo '<td>';
						$photo = trim($row->photo_sm);
						if($photo!=""){
							if(file_exists('./'.$photo)){
								echo '<img src="'.base_url($photo).'" class="img-fluid pull-left mr-2" style="width:80px;"/>';
							}
						}
						echo trim($row->name).'</td>';
						echo '<td>'.trim($row->email).'<br>'.trim($row->phone).'</td>';
						if(!empty(${'rtc_'.$i})){
							echo '<td>'.trim(${'rtc_'.$i}[0]->pswd).'</td>';
							echo '<td>';
							$tsf = trim(${'rtc_'.$i}[0]->test_start_flag);
							$tef = trim(${'rtc_'.$i}[0]->test_end_flag);
							if($tsf=='f'){
								echo '<span class="text-warning font-weight-bold">Invited for Test</span>';
							}else if($tsf!='f' && $tef=='f'){
								echo '<span class="text-success font-weight-bold">Test Going On</span><br><i>on '.date('d/M/Y h:ia',strtotime(${'rtc_'.$i}[0]->test_start_dttm)).'</i>';
							}else if($tsf!='f' && $tef!='f'){
								echo '<span class="text-danger font-weight-bold">Test Completed</span><br><i>on '.date('d/M/Y h:ia',strtotime(${'rtc_'.$i}[0]->test_end_dttm)).'</i><br>';
								echo '<a href="'.base_url().'Exam/testReport/'.base64_encode($row->id).'/'.base64_encode($testpub[0]->tt_id).'/'.base64_encode($testpub[0]->tp_id).'" class="btn btn-sm btn-warning" target="_blank">Test Details</a><a href="javascript:;" class="btn btn-sm btn-warning">View Report</a><a href="javascript:;" class="btn btn-sm btn-warning">Answers</a>';
							}
							echo '</td>';
						}else{
							echo '<td>-</td>';
							echo '<td><button type="button" onClick="inviteSingle('.$row->id.');" class="btn btn-sm btn-warning">Invite for test</button></td>';
						}
						echo '</tr>';
						$i++;
					}
				}
			?>
		</tbody>
	</table>
	</div>
<?php }else{ echo '<h4 class="text-center">Publish the test, before selecting the candidates.</h4>'; } ?>
</div>
<script>
$('#checkallusers').on('change', ()=>{
	var checked = $('#checkallusers').is(':checked');
	if ($('.chkcandi').length > 0){
		if(checked){
		   $(".chkcandi").each(function() {
			  $(".chkcandi").prop('checked',true);
		   });
		   $('#invite_select').prop('disabled', false);
		 }else{
		   $(".chkcandi").each(function() {
			 $(".chkcandi").prop('checked',false);
		   });
		   $('#invite_select').prop('disabled', true);
		 } 
	}
});
$(".chkcandi").on('change', ()=>{
	var checked = $('.chkcandi').is(':checked');
	if(checked){
		$('#invite_select').prop('disabled', false);
	}else{
		$('#invite_select').prop('disabled', true);
	}
});

</script>