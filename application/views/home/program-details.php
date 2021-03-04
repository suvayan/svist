<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw==" crossorigin="anonymous" />
<style>
#course_code::placeholder {
	color: white;
}
iframe: {
	width: 100% !important;
}
.heading {
	padding: 20px 15px;
	background-color: #11075f;
	background-image: linear-gradient(135deg, #0aceab, #efa915, #fbc52f, #1db2f5);
	color: #fff;
}
.video_btn {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}
.play_icon {
	font-size: 5rem;
    color: #fff;
    transition: all 0.5s;
}
.play_icon:hover {
	color: red;
    font-size: 6rem;
}
.word-more {
	height: 200px;
	overflow: hidden;
	transition: height 4s;
}
.show-more {
	height: auto !important;
	overflow: none;
}
</style>
<div class="content">
	<div class="container-fluid">
		<div class="card bg-light">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6 align-self-center text-center text-dark">
						<?php
							echo '<h2 class="font-weight-bold">'.$prog[0]->title.' ('.$prog[0]->yearnm.')</h2>';
							if(!empty($institute)){
								if($institute[0]->logo!=""){
									if(file_exists('./assets/img/institute/'.$institute[0]->logo)){
										echo '<img src="'.base_url('assets/img/institute/'.$institute[0]->logo).'" class="rounded-circle img-fluid" style="width: 90px;"/>';
									}
								}
								echo '<h5 class="font-weight-bold">';
								if(!empty($stream)){
									echo $stream[0]->title.', ';
								}
								echo $institute[0]->title.'</h5>';
							}
							echo '<h5 class="font-weight-bold">';
							$type = trim($prog[0]->ptype);
							$category = trim($prog[0]->category);
							if($type!=""){
								echo $type.', ';
							}
							if($category!=""){
								echo $category.', ';
							}
							$fee = intval(trim($prog[0]->total_fee));
							$feetype = trim($prog[0]->feetype);
							$credit = trim($prog[0]->total_credit);
							$dur = intval(trim($prog[0]->duration));
							if($dur!=0){
								echo 'Duration: '.$dur.' '.trim($prog[0]->dtype).(($dur==1)? '':'s').',   '; 
							}
							echo (($feetype=='Paid')? 'Total Fees: Rs '.$fee : 'Free');
							if($credit!="" || $credit!=0){
								echo ', Total Credit: '.$credit;
							}	
							echo '</h5>';
							if($prog[0]->facebook!=""){
								echo '<a href="'.$prog[0]->facebook.'" target="_blank" class="btn btn-just-icon btn-link btn-facebook"><i class="fa fa-facebook-square"> </i></a>';
							}
							if($prog[0]->twitter!=""){
								echo '<a href="'.$prog[0]->twitter.'" target="_blank" class="btn btn-just-icon btn-link btn-twitter"><i class="fa fa-twitter"> </i></a>';
							}
							if($prog[0]->linkedin!=""){
								echo '<a href="'.$prog[0]->linkedin.'" target="_blank" class="btn btn-just-icon btn-link btn-linkedin"><i class="fa fa-linkedin"> </i></a>';
							}
							if($prog[0]->start_date!=NULL){
								echo '<h6>'.date('jS M Y',strtotime($prog[0]->start_date));
								if($prog[0]->end_date!=NULL){
									echo ' to '.date('jS M Y',strtotime($prog[0]->end_date));
								}
								echo '</h6>';
							}
							if($prog[0]->aend_date!=NULL){
								echo '<h6>Admission Deadline: '.date('jS M Y',strtotime($prog[0]->aend_date)).'</h6>';
							}
							$curdate = strtotime(date('d-m-Y'));
							$ldt = strtotime(date('d-m-Y',strtotime($prog[0]->aend_date)));
							$sdt = strtotime(date('d-m-Y',strtotime($prog[0]->astart_date)));
							if($sdt!=19800 || $ldt!=19800){
								if($curdate>=$sdt && $curdate<=$ldt){
									echo '<a href="'.base_url('programAdmission/?id='.base64_encode($prog[0]->id)).'" class="btn btn-warning btn-sm">Apply Now</a>';
								}
							}
							if($prog[0]->program_brochure!=null){
								if(file_exists('./uploads/programs/'.$prog[0]->program_brochure)){
									echo '<a href="'.base_url('uploads/programs/'.$prog[0]->program_brochure).'" class="btn btn-info btn-sm"><i class="fa fa-download"></i> Brochure</a>';
								}
							}
						?>
					</div>
					<div class="col-md-6">
						<?php
							$src = '';
							$banner = $prog[0]->banner;
							if($banner!=""){
								if(file_exists('./assets/img/banner/'.$banner)){
									$src = 'assets/img/banner/'.$banner;
								}else{
									$src = 'assets/img/sample.jpg';
								}
							}else{
								$src = 'assets/img/sample.jpg';
							}
							echo '<img src="'.base_url().$src.'" class="img-responsive w-100"/>';
							$intro = trim($prog[0]->intro_video_link);
							if($intro!=""){
								echo '<div class="video_btn">
									<a data-fancybox href="'.$intro.'"> <i class="material-icons play_icon">play_circle_filled</i> </a>
								</div>';
							}
						?>
						
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-9">
				<div class="card mt-0" style="background:transparent;">
					<div class="card-body text-justify">
						<?php
							if(trim($prog[0]->overview)!=''){
								echo '<div class="text-justify mb-4"><h5 class="font-weight-bold heading">Overview</h5>'.trim($prog[0]->overview).'</div>';
							}
							echo '<div class="text-justify mb-4"><h5 class="font-weight-bold heading">Course(s)</h5>';
							echo '<ul class="timeline timeline-simple">';
							$i=1; 
							$countsms = count($sems);
							if($countsms==0 || $countsms==1){
								if(!empty($procourse)){
								foreach($procourse as $pc){
									echo '<li class="timeline-inverted">';
									echo '<div class="timeline-badge success">'.$i.'</div>';
									echo '<div class="timeline-panel">
											<div class="timeline-heading">
											  <span class="badge badge-pill badge-info">'.$pc->title.' ('.$pc->c_code.')</span>
											</div>
											<div class="timeline-body">
											<a href="javascript:;" class="mr-2">Lectures ('.$pc->lec.')</a>
											<a href="javascript:;" class="mr-2">Tutorials ('.$pc->tut.')</a>
											<a href="javascript:;" class="mr-2">Practicals ('.$pc->prac.')</a>';
											if($pc->syllabus!=''){
												echo '<a href="'.base_url().'uploads/course/'.$pc->syllabus.'" target="_blank">Shyllabus</a>';
											}
									echo '</div></div></li>';
									$i++;
								} 
								}
							}else{
								foreach($sems as $srow){
									echo '<li class="timeline-inverted">';
									echo '<div class="timeline-badge success">'.$i.'</div>';
									echo '<div class="timeline-panel">
											<div class="timeline-heading">
											  '.$srow->title.'
											</div>
											<div class="timeline-body">';
											if(!empty(${'procourse_'.$i})){
												foreach(${'procourse_'.$i} as $pc){
													echo '<span class="badge badge-pill badge-info">'.$pc->title.' ('.$pc->c_code.')</span><br>
													<a href="javascript:;" class="mr-2">Lectures ('.$pc->lec.')</a>
													<a href="javascript:;" class="mr-2">Tutorials ('.$pc->tut.')</a>
													<a href="javascript:;" class="mr-2">Practicals ('.$pc->prac.')</a>';
													if($pc->syllabus!=''){
														echo '<a href="'.base_url().'uploads/course/'.$pc->syllabus.'" target="_blank">Shyllabus</a>';
													}
													echo '<br>';
												}
											}
									echo '</div></div></li>';
									$i++;
								}
							}
							
							echo '</ul></div>';
							echo '<div class="text-justify mb-4"><h5 class="font-weight-bold heading">Teachers</h5><div class="row">';
							foreach($pprof as $trow){
								echo '<div class="col-sm-4 mb-2">
									<div class="card m-0">
									<div class="card-body">
									<h4 class="text-center"><img src="'.base_url().$trow->photo_sm.'" onerror="this.src=`'.base_url('assets/img/default-avatar.png').'`" class="avatar-dp" style="float:none; width:60px; height:60px;" /><br><strong>'.$trow->name.'</strong></h4>
									<span class="text-justify">';
									if(trim($trow->designation)!=""){
										echo trim($trow->designation);
									}
									if(trim($trow->organization)!=""){
										echo '<br>'.trim($trow->organization);
									}
									echo '</span></div></div></div>';
							}
							echo '</div></div>';
							$cert = trim($prog[0]->certificate_sample);
							if($cert!=""){
								if(file_exists('./uploads/programs/'.$cert)){
									$src = 'uploads/programs/'.$cert;
								}
								echo '<div class="text-justify mb-4"><h5 class="font-weight-bold heading">Program Certification</h5>';
								echo '<p><a href="'.base_url($src).'" target="_blank" title="Sample Certificate">
										<img src="'.base_url($src).'" class="img-thumbnail" style="width: 445px;"/>
									  </a></p></div>';
							}
							
							if($feetype=='Paid'){
								if(trim($prog[0]->fee_details)!=""){
									echo '<div class="text-justify mb-4"><h5 class="font-weight-bold heading">Fees Structure</h5>'.trim($prog[0]->fee_details).'</div>';
								}
							}
							if(trim($prog[0]->why_learn)!=''){
								echo '<div class="text-justify mb-4"><h5 class="font-weight-bold heading">Benefits</h5>'.trim($prog[0]->why_learn).'</div>';
							}
							if(trim($prog[0]->requirements)!=''){
								echo '<div class="text-justify mb-4"><h5 class="font-weight-bold heading">Eligibility</h5>'.trim($prog[0]->requirements).'</div>';
							}
							if(trim($prog[0]->contact_info)!=''){
								echo '<div class="text-justify mb-4"><h5 class="font-weight-bold heading">Contact Information</h5>'.trim($prog[0]->contact_info).'</div>';
							}
							if(trim($prog[0]->placement)!=''){
								echo '<div class="text-justify"><h5 class="font-weight-bold heading">Placement</h5>'.trim($prog[0]->placement).'</div>';
							}
						?>
					</div>
				</div>
			</div>
			<aside class="col-md-3 order-last">
				<div class="card mt-0" style="background:transparent">
					<div class="card-body">
						<div class="card mt-0">
							<div class="card-body">
								<small>Email</small><br>
								<a href="mailto:<?php echo $prog[0]->email; ?>"> <?php echo $prog[0]->email; ?> | <i class="material-icons">email</i></a>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<small>Mobile</small><br>
								<a href="tel:<?php echo $prog[0]->mobile; ?>"> <?php echo '+91 - '.$prog[0]->mobile; ?> | <i class="material-icons">phone</i></a>
							</div>
						</div>
						<?php
							
							if($sdt!=19800 || $ldt!=19800){
								if($curdate<$sdt){
									echo '<h4 class="text-center text-primary">Admission starts from '.date('jS M Y',strtotime($prog[0]->aend_date)).'</h4>';
								}else{
									if($curdate>=$sdt && $curdate<=$ldt){
										echo '<a href="'.base_url('programAdmission/?id='.base64_encode($prog[0]->id)).'" class="btn btn-warning btn-sm btn-block">Apply for Admission</a>';
									}else{
										echo '<h4 class="text-center text-danger">Admission Closed</h4>';
									}
								}
							}else{
								echo '<h4 class="text-center text-danger">Admission Closed</h4>';
							}
						?>
					</div>
				</div>
			</aside>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
	$('.dtinsstrm').DataTable();
});
function toggleMore(code, id)
{
	$('#word_'+code+'_'+id).toggleClass('show-more');
	if($('#word_'+code+'_'+id).hasClass('show-more')){
		$('#btn_'+code+'_'+id).html('Read Less');
	}else{
		$('#btn_'+code+'_'+id).html('Read More');
	}
}
</script>