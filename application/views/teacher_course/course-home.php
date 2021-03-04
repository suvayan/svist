<div class="row">
	
	<div class="col-md-12">
		<div class="card mt-2" style="background-color: #e6ecf6;">
			<div class="card-header card-header-info card-header-text">
			  <div class="card-text">
				<h4 class="card-title">Course Overview</h4>
			  </div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-4 col-md-6">
					  <ul class="nav nav-pills nav-pills-info nav-pills-icons flex-column" role="tablist">
						<li class="nav-item">
						  <a class="nav-link active" data-toggle="tab" href="#link110" role="tablist">
							Overview
						  </a>
						</li>
						<li class="nav-item">
						  <a class="nav-link" data-toggle="tab" href="#link111" role="tablist">
							Description
						  </a>
						</li>
						<li class="nav-item">
						  <a class="nav-link" data-toggle="tab" href="#link112" role="tablist">
							Importance
						  </a>
						</li>
					  </ul>
					</div>
					<div class="col-md-8">
					  <div class="tab-content">
						<div class="tab-pane active tex-justify" id="link110">
							<button class="btn btn-sm btn-success btn-link">Credit: <?php echo $cd[0]->total_credit; ?></button>
							<h5>
							<?php
							echo '<strong>Lectures: </strong>'.$cd[0]->lec.';	<strong>Tutorials: </strong>'.$cd[0]->tut.';	<strong>Practicals: </strong>'.$cd[0]->prac;
							?>
							</h5>
							<?php
							if($cd[0]->syllabus!=''){
							echo '<h5><a href="'.base_url().'uploads/courses/'.$cd[0]->syllabus.'" target="_blank">Syllabus</a></h5>';
							}
							?>
						  
						</div>
						<div class="tab-pane tex-justify" id="link111">
							<?php echo $cd[0]->overview; ?>
						</div>
						<div class="tab-pane tex-justify" id="link112">
						  <?php echo $cd[0]->importance; ?>
						</div>
					  </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>