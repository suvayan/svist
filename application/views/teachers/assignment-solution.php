<!--<div class="row">
	<div class="col-md-12">
		<?php /*if(count($assgn_sub)>0){ 
			if($assgn_sub[0]->details!=''){
				echo trim($assgn_sub[0]->details).'<br>';
			}
			
			if((count($asubfiles)>0) && (date('d-m-Y',strtotime($assgn_sub[0]->deadline))<=strtotime(date('d-m-Y')))){
				$j=1;
				foreach($asubfiles as $afrow){
					echo $j.') <a target="_blank" href="'.base_url().'uploads/stud_assign_sub/'.$afrow->file_name.'" class="mr-3"><i class="material-icons">attach_file</i> View File </a>';
					$j++;
				}
			}else{
				echo '<h5 class="text-center"><button class="btn btn-info btn-sm" onClick="openAssgnFileModel('.$assgn_sub[0]->sl.')">Upload your assignment solutions</button></h5>';
			}
		}else{ 
			echo '<h5 class="text-center"><button class="btn btn-primary btn-md" data-toggle="modal" data-target="#casModal">Submit your assignment</button></h5>';
		}*/ ?>
	</div>
</div>-->
<div class="material-datatables">
	<table class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th width="5%">Sl.</th>
				<th width="15%">Name</th>
				<th width="20%">Status</th>
				<th width="15%">Date Time</th>
				<th width="40%">Details</th>
				<th width="5%">Marks</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=1; if(count($assgn_sub)>0){ foreach($assgn_sub as $srow){ $stud_name = trim($srow->first_name.' '.$srow->last_name); ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $stud_name; ?></td>
					<td><?php echo trim($srow->status); ?></td>
					<td><?php echo date('j M Y h:sa', strtotime($srow->tdate)); ?></td>
					<td>
						<?php 
							if(count($asubfiles)>0){
								$j=1;
								foreach($asubfiles as $afrow){
									echo $j.') <a target="_blank" href="'.base_url().'uploads/stud_assign_sub/'.$afrow->file_name.'" class="mr-3"><i class="material-icons">attach_file</i> View File </a>';
									$j++;
								}
								echo '<br>';
							}
							echo trim($srow->details); 
						?>
					</td>
					<td>
						<?php
							if($srow->marks!=''){
								echo $srow->marks;
							}else{
								echo '<button onClick="marksModal(`'.$stud_name.'`, '.$srow->user_sl.', '.$srow->pcw_sl.')" class="btn btn-warning btn-sm">Add Marks</button>';
							}
						?>
					</td>
				</tr>
			<?php $i++; } } ?>
		</tbody>
	</table>
</div>
