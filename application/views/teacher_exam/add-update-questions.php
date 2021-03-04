<script src="<?php echo base_url('assets/vendor/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/questions.js'); ?>"></script>

<style>
.loader {
  background-color: #ffffff;
  opacity:0.5;
  position: fixed;
  z-index: 999999;
  height: 100%;
  width: 100%;
  top: 0;
  left: 0;
}
.loader img {
  position: absolute;
  top: 50%;
  left: 50%;
  text-align: center;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}
</style>
<?php
	//$mcount = count($qoptions);
?>
<div class="loader" id="loading" style="display:none;">
	<img src="<?php echo base_url().'assets/img/loading.gif'; ?>">
</div>
<div class="content">
	<div class="container-fluid">
		<div class="card" style="background:transparent;">
			<div class="card-header card-header-info">
				<h3 class="card-title">Add Question
					<a href="<?php echo base_url('Teacher/questionBank'); ?>" class="btn btn-sm btn-primary pull-right"><i class="material-icons">list</i> Back to Question bank</a>
				</h3>
			</div>
			<div class="card-body">
				<form id="frmQues" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-6">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-4 mx-auto">
											<div class="form-group mb-0">
												<select name="qbs_test" id="qbs_test" class="selectpicker w-100" data-style="select-with-transition" title="Test type <span class='text-danger'>*</span>" required="true">
													<?php 
														foreach($ttype as $ttrow){ 
															echo '<option value="'.$ttrow->id.'" '.(($ttrow->id==$ques[0]->type_id)? 'selected':'').'>'.$ttrow->type_name.'</option>';
														}
													?>
												</select>
												<input type="hidden" name="qbs_qb" id="qbs_qb" value="<?php echo $ques[0]->qb_id; ?>"/>
												<input type="hidden" name="qbs_prog" id="qbs_prog" value="<?php echo $ques[0]->cat_id; ?>"/>
												<input type="hidden" name="qbs_course" id="qbs_course" value="<?php echo $ques[0]->scat_id; ?>"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-12 border">
											<div class="form-group mb-0">
												<label for="qbstitle" class="text-dark">Question <span class='text-danger'>*</span></label><br>
												<textarea class="form-control" name="qbstitle" id="qbstitle" ckrequired="true"><?php echo $ques[0]->qbody; ?></textarea>
												<input type="hidden" name="qbs_id" id="qbs_id" value="<?php echo $ques[0]->id; ?>">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group mb-0">
												<label for="qbs_ans" class="text-dark">Answer</label><br>
												<textarea class="form-control" style="width:100%;" name="qbs_ans" id="qbs_ans"><?php echo $ques[0]->answer; ?></textarea>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group mb-0">
												<label for="qbs_hint" class="text-dark">Hint</label><br>
												<textarea class="form-control" style="width:100%;" name="qbs_hint" id="qbs_hint"><?php echo $ques[0]->hints; ?></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card">
								<div class="card-body">
									
									<div class="row">
										<div class="col-sm-2">
											<div class="form-group mb-0">
												<label for="qbs_marks" class="text-dark">Marks <span class='text-danger'>*</span></label>
												<input type="text" class="form-control" name="qbs_marks" id="qbs_marks" required="true" digits="true" value="<?php echo $ques[0]->marks; ?>"/>
											</div>
										</div>
										<div class="col-sm-5">
											<div class="form-group mb-0">
												<select name="qbs_wht" id="qbs_wht" data-size="5" class="selectpicker" data-style="select-with-transition" title="Weightage <span class='text-danger'>*</span>" required="true">
													<?php
														for($a=1; $a<=10; $a++ ){
															echo '<option value="'.$a.'"'.(($a==$ques[0]->weightage)? 'selected':'').'>'.$a.'</option>';
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-sm-5">
											<div class="form-group mb-0">
												<select name="qbs_dlvl" id="qbs_dlvl" class="selectpicker" data-style="select-with-transition" title="Difficulty Level <span class='text-danger'>*</span>" required="true">
													<option value="Easy" <?php echo ($ques[0]->difficulty_level=='Easy')? 'selected':''; ?>>Easy</option>
													<option value="Medium" <?php echo ($ques[0]->difficulty_level=='Medium')? 'selected':''; ?>>Medium</option>
													<option value="Hard" <?php echo ($ques[0]->difficulty_level=='Hard')? 'selected':''; ?>>Hard</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row" id="num_all" style="display:none;">
										<div class="col-sm-6">
											<div class="form-group">
												<label for="tolerance">Tolerance <span class='text-danger'>*</span></label>
												<input type="text" class="form-control" name="tolerance" id="tolerance" value="<?php echo $ques[0]->tolerance; ?>" required="true">
											</div>
										</div>	
										<div class="col-sm-6">
											<div class="form-group">
												<label for="qdecimal">Correct upto decimal <span class='text-danger'>*</span></label>
												<input type="text" class="form-control" name="qdecimal" id="qdecimal" value="<?php //echo $ques[0]->qdecimal; ?>" required="true">
											</div>
										</div>
									</div>
									<div class="row" id="mcqs_all" style="display:none;">
										<div class="col-sm-12">
											<div class="form-group">
												<div class="checkbox-radios">
													<div class="form-check form-check-inline">
													  <label class="form-check-label text-dark">
														<input class="form-check-input" type="checkbox" value="1" name="ms" id="ms" <?php if($ques[0]->multi_select){ echo 'checked'; } ?>> Multi-select
														<span class="form-check-sign">
														  <span class="check"></span>
														</span>
													  </label>
													</div>
													<div class="form-check form-check-inline">
													  <label class="form-check-label text-dark">
														<input class="form-check-input" type="checkbox" value="1" name="rf" id="rf"> Random Flag
														<span class="form-check-sign">
														  <span class="check"></span>
														</span>
													  </label>
													</div>
												</div>
											</div>
										</div>
										<div class="col-sm-12">
											<?php 
												$mcount = (isset($qoptions))? count($qoptions) : 0;
											?>
											<input type="hidden" name="mcount"  id="mcount" value="<?php echo $mcount; ?>"/>
											<?php
												if(isset($qoptions)){
													$k=0;
													foreach($qoptions as $opts){
														echo '<fieldset class="mb-3" id="fld_'.$k.'" style="border: 1px solid #a1a1a1; border-radius:10px; padding: 10px;"><div class="form-group"><div class="row"><div class="col-sm-1 mb-4"><div class="form-check"><label class="form-check-label text-dark"><input class="form-check-input" type="radio" value="1" name="chkmcq[]" id="chkmcq_'.$k.'" required="true" '.(($opts->correct_flag)? 'checked':'').'><span class="circle"><span class="check"></span></span></label></div></div><div class="col-sm-8 border"><textarea class="form-control" name="mcqs_'.$k.'" id="mcqs_'.$k.'" ckrequired="true">'.trim($opts->body).'</textarea></div><div class="col-sm-2"><div class="input-group"><input type="number" name="msnum_'.$k.'" id="msnum_'.$k.'" class="form-control" value="'.trim($opts->weightage).'"><div class="input-group-prepend"><span class="input-group-text">% marks</span></div></div></div><div class="col-sm-1 align-self-center"><button type="button" class="btn btn-sm btn-danger btn-link p-0 pull-right" onClick="removeMSet('.$k.')" id="btn_'.$k.'"><i class="material-icons">close</i></button></div></div></div></fieldset>';
														echo "<script>CKEDITOR.inline('mcqs_".$k."', {
														extraPlugins: 'uploadimage,colorbutton,embedsemantic,mathjax,font,justify,table,specialchar,tabletools',
														height: 100,
														mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
													});</script>";
														$k++;
													}
												}
											?>
											<div class="form-group" id="mcqs_set"></div>
											<button type="button" onClick="addMore();" id="addSet" class="btn btn-sm btn-success pull-right"><i class="material-icons">add</i> Add More</button>
											<span id="mcq_error" class="text-danger"></span>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12 text-center">
							<button type="submit" id="frmsubmit" class="btn btn-primary btn-md">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready( function() {
		toggleOptions('<?php echo $ques[0]->type_id; ?>','<?php echo $mcount; ?>');
	});
</script>