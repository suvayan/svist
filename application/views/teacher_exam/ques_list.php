<div class="material-datatables">
	<table class="table table-striped table-no-bordered table-hover" id="ques_tbl" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th width="5%">Sl.</th>
				<th width="85%">Questions</th>
				<th width="10%">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if(!empty($ques)){
					$i=1;
					
					foreach($ques as $qow)
					{
						$qsid = $qow->id;
						$qbid = $qow->qb_id;
						$type = trim($qow->type_name);
						echo '<tr id="quesl_'.$qsid.'">';
						echo '<td>'.$i.'</td>';
						echo '<td>'.trim($qow->qbody);
						if($type=='Numeric'){
							echo 'Tolerance: '.trim($qow->tolerance).', Correct upto decimal: '.trim($qow->qdecimal).'<br>';
						}else if($type=='MCQ'){
							echo 'Multi-select: '.(($qow->multi_select=='f')? 'False':'True').', Random flag: -<br>';
							if(!empty(${'ops_'.$i})){
								echo '<div class="row">';
								foreach(${'ops_'.$i} as $opow){
									echo '<div class="col-sm-6 d-flex">';
									echo ($opow->correct_flag!='f')? '<i class="material-icons text-success">check_circle</i> ':'<i class="material-icons text-danger">remove_circle</i> ';
									echo trim($opow->body).'/'.trim($opow->weightage).' % marks</div>';
								}
								echo '</div>';
							}
						}
						echo 'Weightage: '.trim($qow->weightage).', Difficulty Level: '.trim($qow->difficulty_level).', Marks: '.trim($qow->marks).',<span class="label label-info ml-2">'.(($type=='Text')? 'Text Question' : $type.' Type').'</span>
							  </td>';
						echo '<td class="td-actions text-right">
								<a href="'.base_url('Exam/cruQuestion/?qbid='.base64_encode($qbid).'&id='.base64_encode($qsid)).'" title="Edit" class="text-danger"><i class="material-icons">edit</i></a>
								<a href="javascript:;" onClick="deleteQues('.$qsid.')" title="Delete" class="text-danger"><i class="material-icons">delete</i></a>
							  </td>';
						$i++;
						echo '</tr>';
					}
				}
				
			?>
		</tbody>
	</table>
</div>
<script>
	$('#ques_tbl').DataTable();
</script>