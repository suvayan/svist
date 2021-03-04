<div class="col-sm-12">
	<input type="hidden" name="secid" id="secid" value="<?php echo $sid; ?>">
	<input type="hidden" name="qbcount" id="qbcount" value="<?php echo count($qbks); ?>"/>
	<div id="accordion" role="tablist">
		<?php $i=1; foreach($qbks as $qbow){ ?>
		<div class="card-collapse">
		  <div class="card-header" role="tab" id="headingOne">
			<h5 class="mb-0">
			  <a data-toggle="collapse" href="#collapse_<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $i; ?>" class="collapsed">
				<?php echo '#'.$i.' '.trim($qbow->name); ?>
				<i class="material-icons">keyboard_arrow_down</i>
			  </a>
			</h5>
		  </div>
		  <div id="collapse_<?php echo $i; ?>" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
			<div class="card-body">
			  <?php echo '<input type="checkbox" class="checkbox mr-2" onChange="sltAllQues('.$i.')" name="chk_qb'.$i.'" id="chk_qb'.$i.'" value="1"/> Select All'; ?>
			  <input type="hidden" name="qbid_<?php echo $i; ?>" id="qbid_<?php echo $i; ?>" value="<?php echo $qbow->id; ?>"/>
			  <div class="material-datatables">
				<table class="table table-condensed table-no-bordered" id="ques_tbl" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th width="5%"></th>
							<th width="5%">Sl.</th>
							<th width="90%">Questions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if(!empty(${'ques_'.$i})){
								$j=1;
								
								foreach(${'ques_'.$i} as $qow)
								{
									$qsid = $qow->id;
									$type = trim($qow->type_name);
									echo '<tr id="quesl_'.$qsid.'">';
									echo '<td>
											<input type="checkbox" class="checkbox cbquestion_'.$i.'" name="chk_ques'.$i.'[]" id="chk_ques'.$qsid.'" value="'.$qsid.'" '.(($qow->ques_id!=NULL)? 'checked':'').'/>
										  </td>';
									echo '<td>'.$j.'</td>';
									echo '<td>'.trim($qow->qbody);
									if($type=='Numeric'){
										echo 'Tolerance: '.trim($qow->tolerance).', Correct upto decimal: '.trim($qow->qdecimal).'<br>';
									}else if($type=='MCQ'){
										echo 'Multi-select: '.(($qow->multi_select=='f')? 'False':'True').', Random flag: -<br>';
										if(!empty(${'ops_'.$j})){
											echo '<div class="row">';
											foreach(${'ops_'.$j} as $opow){
												echo '<div class="col-sm-6 d-flex">';
												echo ($opow->correct_flag!='f')? '<i class="material-icons text-success">check_circle</i> ':'<i class="material-icons text-danger">remove_circle</i> ';
												echo trim($opow->body).'/'.trim($opow->weightage).' % marks</div>';
											}
											echo '</div>';
										}
									}
									echo 'Weightage: '.trim($qow->weightage).', Difficulty Level: '.trim($qow->difficulty_level).', Marks: '.trim($qow->marks).',<span class="label label-info ml-2">'.(($type=='Text')? 'Text Question' : $type.' Type').'</span>
										  </td>';
									$j++;
									echo '</tr>';
								}
							}
							
						?>
					</tbody>
				</table>
			</div>

			</div>
		  </div>
		</div>
		<?php $i++; } ?>
	</div>
</div>
