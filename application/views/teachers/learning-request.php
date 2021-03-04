<style>
#course_code::placeholder {
	color: white;
}
.dropdown bootstrap-select {
	width:100% !important;
}
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
<div class="loader" id="loading" style="display:none;">
	<img src="<?php echo base_url().'assets/img/loading.gif'; ?>">
</div>
<div class="content">
	<div class="container-fluid">
		<div class="row">
		
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info card-header-text">
						<div class="card-text">
							<h4 class="card-title">Learning Programs</h4>
						</div>
					</div>
					<div class="card-body">
						<?php
							if(!empty($progs)){
								foreach($progs as $prow){
									echo '<button class="btn btn-sm btn-info mr-3" onClick="getLearningList('.$prow->id.');">'.$prow->title.'</button>';
								}
							}else{
								echo '<h4 class="font-weight-bold text-primary text-center">Add your program based on the Learning Platform.</h4>';
							}
						?>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="progress-bar progress-bar-striped progress-bar-animated" id="prg_progress" style="width:100%; display:none;">Loading the list. Please wait...</div>
						<div id="adm_list"></div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<script>
	function getLearningList(pid)
	{
		$('#prg_progress').show();
		$('#adm_list').html("");
		$.ajax({
			url: baseURL+'Teacher/getProgLearningList',
			type: 'GET',
			data: { pid: pid },
			success: (resp)=>{
				$('#prg_progress').hide();
				$('#adm_list').html(resp);
			}
		});
	}
</script>