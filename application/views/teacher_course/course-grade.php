<div class="row">
	
	<div class="col-md-12">
		<div class="card mt-2" style="background-color: #e6ecf6;">
			<div class="card-header card-header-info card-header-text">
			  <div class="card-text">
				<h4 class="card-title">Grades</h4>
			  </div>
			</div>
			<div class="card-body">
				<div class="material-datatables">
					<table class="table table-striped table-no-bordered table-hover" id="gradeList" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th width="5%" rowspan="2">Sl.</th>
								<th width="10%" rowspan="2">Students</th>
								<?php 
									$cnt_pcw = count($pcwt);
									foreach($pcwt as $pcrow){
										echo '<th>'.$pcrow->subject.'</th>';
									   
									}
								?>
								<th width="5%" rowspan="2">Grand Total</th>
								<th width="5%" rowspan="2">Final Grade</th>
								<!--<-->
							</tr>
							<?php
								echo '<tr>';
								foreach($pcwt as $wtrow){
									echo '<td>'.$wtrow->weightage.'</td>';
								   
								}
								echo '</tr>';
							?>
						</thead>
						<tbody>
							<?php $i=1; foreach($pstud as $srow){ ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo trim($srow->name); ?></td>
									<?php
											
										$cnt_psm = count(${'setGrade_'.$i});
										$diff_cnt = $cnt_pcw-$cnt_psm; 
										$grand=0;
										$tf_marks = 0;
										$fin_grad=0;
										$wt = 0;
										if(!empty(${'setGrade_'.$i})){
											foreach(${'setGrade_'.$i} as $grow){
												echo '<td>'.$grow->marks.' / '.$grow->full_marks.'</td>';
												$grand+=(int)$grow->marks;
												$tf_marks+=(int)$grow->full_marks;
											}
										}
										if($diff_cnt!=0){
											for($j=1; $j<=$diff_cnt; $j++){
												echo '<td>Marks not added</td>';
											}
										}
										if($grand!=null || $tf_marks!=null){
											$fin_grad = (double)($grand/$tf_marks)*100;
										}
										
										echo '<td>'.$grand.'</td><td>'.$fin_grad.'%</td>';
									?>
								</tr>
							<?php $i++; } ?>
						</tbody>
					</table>
				</div>
			
			</div>
		</div>
	</div>
</div>
<script>
	$('#gradeList').DataTable();
</script>