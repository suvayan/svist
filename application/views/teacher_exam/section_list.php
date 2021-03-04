<?php
	if(!empty($sections)){
		$i=1;
		foreach($sections as $srow){
			$sid = $srow->id;
			$stitle = trim($srow->section_name);
			echo '<div class="col-sm-6 mb-3">
					<div class="card my-0">
						<div class="card-header border-bottom">
							<h5 class="card-title">'.trim($stitle).'
							<button class="btn btn-info btn-sm pull-right" onClick="questionSelectForTest('.$sid.');">Add/View Questions</button>
							</h5>
						</div>
						<div class="card-body">';
						// <button class="btn btn-info btn-sm pull-right" onClick="questionModal('.$sid.');">Add/View Questions</button>
						//<a class="btn btn-info btn-sm pull-right" href="'.base_url().'Teacher/getAllQBsQuestions/'.base64_decode($id).'">Add/View Questions</a>
			if(!empty(${'ques_'.$i})){
				echo 'Total Questions Added: '.count(${'ques_'.$i});
				/*echo '<ol type="1" class="mb-0">';
				foreach(${'ques_'.$i} as $qrow){
					echo '<li class="d-flex">'.trim($qrow->qbody).' ('.trim($qrow->marks).')</li>';
				}
				echo '</ol>';*/
			}else{
				echo '<h5 class="text-center">Add Questions from Question Bank.</h5>';
			}			
			echo '</div><div class="card-footer">
					<i>Created on: '.date('d/M/Y',strtotime($srow->create_section_time)).'</i>
					<div class="pull-right d-flex">
					<div class="togglebutton mr-2">
                        <label>
                          <input type="checkbox" name="randfg_'.$sid.'" id="randfg_'.$sid.'" onChange="randomToggle('.$sid.', true);" value="1" '.(($srow->random_flag!='f')? 'checked':'').'>
                          <span class="toggle"></span>
                          Random
                        </label>
                    </div>
					<div class="form-group mr-2">
						<label>Question Number</label>
						<input type="number" name="secNumQues_'.$sid.'" id="secNumQues_'.$sid.'" onChange="upRandomSec('.$sid.');" class="form-control" maxlength="'.count(${'ques_'.$i}).'" value="'.$srow->ques_number.'" placeholder="Question number" disabled/>
					</div>
					<div class="form-group">
						<label>Benchmark</label>
						<input type="number" name="secBenchmark_'.$sid.'" id="secBenchmark_'.$sid.'" onChange="upRandomSec('.$sid.');" class="form-control" value="'.$srow->benchmark.'" placeholder="Benchmark" disabled/>
					</div>
					</div>
					<div class="d-flex pull-right">
						<a href="javascript:;" title="Edit Section" onClick="sectionModal(`edit`, `'.$stitle.'`, '.$sid.')" title="Edit" class="text-info"><i class="material-icons">edit</i></a>
					</div>
				  </div></div></div>';
				  /*<a href="javascript:;" onClick="deleteSection(`'.$stitle.'`, '.$sid.')" title="Delete" class="text-danger"><i class="material-icons">delete</i></a>*/
			echo '<script>randomToggle('.$sid.', false);</script>';
			$i++;
		}
	}else{
		echo '<h5 class="text-center">Click above button to add a new section</h5>';
	}
?>