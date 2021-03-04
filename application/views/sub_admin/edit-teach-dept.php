<div class="content">
    <div class="container">
		<div class="row">
            <div class="col-lg-10 col-md-10 mx-auto">
                <div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title">Organisation and Department Links - Teacher
							<a href="<?php echo base_url('Subadmin/teacherMaster'); ?>" class="btn btn-primary btn-sm pull-right"><i class="material-icons">list</i> Teacher List</a>
						</h3>
					</div>
					<div class="card-body">
						<?php
							if($this->session->flashdata('errors')!=''){
								echo '<div class="alert alert-warning alert-dismissible">
									  <button type="button" class="close" data-dismiss="alert">&times;</button>
									  '.$this->session->flashdata('errors').'
									</div>';
							}
						?>
						<div class="row">
							<div class="col-md-4 justify-content-center">
								<img src="<?php echo base_url('assets/img/users/'.$prof[0]->photo_sm); ?>" class="img-thumbnail rounded" onerror="this.src='<?php echo base_url('assets/img/default-avatar.png'); ?>'" id="wizardPicturePreview" title="" width="200"/>
							</div>
							<div class="col-md-8">
								<p class="text-justify">
									<?php
										echo trim($prof[0]->salutation.' '.$prof[0]->first_name.' '.$prof[0]->last_name).'<br><br>';
										echo trim($prof[0]->email)." ".trim($prof[0]->phone).'<br>';
										if(trim($prof[0]->about_me)){
											echo trim($prof[0]->about_me);
										}
									?>
								</p>
							</div>
						</div>
						<h6 class="text-center"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#ODModal"><i class="material-icons">link</i> Add Link</button></h6>
						<div class="row">
							<?php
								echo '<div class="col-md-12"><div class="card"><div class="card-body">
								<button class="close" onClick="removeLink('.$ord_dept['uid'].', '.$ord_dept['org_id'].')"><i class="material-icons">delete</i></button>								
								<h5>'.trim($ord_dept['org_title']).'</h5>';
								if(!empty($ord_dept['dept'])){
									foreach($ord_dept['dept'] as $drow){
										echo '<h6>'.trim($drow).'</h6>';
									}
								}
								echo '</div></div></div>';
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ODModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title" id="mheader"></h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<form method="POST" action="<?php echo base_url('Subadmin/cuOrgTeacher'); ?>" id="frmTeacher">
		<div class="modal-body" id="mbody">
		  <div class="row">
				<div class="col-sm-6">
					<div class="form-group"> 
                        <input type="hidden" name="userid" id="userid" value="<?php echo $prof[0]->id; ?>"/>
						<select class="selectpicker" multiple data-style="select-with-transition" data-title="Select department <span class='text-danger'>*</span>" id="dept" name="dept[]">
                            <?php
                                if(!empty($department)){
                                    foreach($department as $dept){
                                        echo '<option value="'.$dept->id.'">'.$dept->title.'</option>';
                                    }
                                }
                            ?>
						</select>
					</div>     
				</div>
			</div>
		</div>
		<div class="modal-footer">
		  <button type="submit" class="btn btn-link">Save</button>
		  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
		</div>
		</form>
	  </div>
	</div>
</div>
<script src="<?php echo base_url();?>/assets/js/sub-admin.js"></script>