<style>
    .icon-container {
        position: absolute;
        /*right: 10px;*/
        top: calc(50% - 10px);
    }
    .loader {
        position: relative;
        height: 20px;
        width: 20px;
        display: inline-block;
        animation: around 5.4s infinite;
    }

    @keyframes around {
        0% {
            transform: rotate(0deg)
        }
        100% {
            transform: rotate(360deg)
        }
    }

    .loader::after, .loader::before {
        content: "";
        background: white;
        position: absolute;
        display: inline-block;
        width: 100%;
        height: 100%;
        border-width: 2px;
        border-color: #333 #333 transparent transparent;
        border-style: solid;
        border-radius: 20px;
        box-sizing: border-box;
        top: 0;
        left: 0;
        animation: around 0.7s ease-in-out infinite;
    }

    .loader::after {
        animation: around 0.7s ease-in-out 0.1s infinite;
        background: transparent;
    }
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
						<h3 class="card-title">Program's List</h3>
					</div>
                    <div class="card-body">
                        <div class="tab-content tab-space">
                            <div class="row">
                                <div class="col-lg-8 col-md-8">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <select class="selectpicker" data-style="select-with-transition" name="org" id="org" width="300" onChange="programTable();">
                                                    <option value="">Select Organization</option>
                                                    <?php
                                                        if(!empty($organization)):
                                                            foreach($organization as $org):
                                                                echo '<option value='.$org->id.'>'.$org->title.'</option>';
                                                            endforeach;
                                                        endif;
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <select class="selectpicker" data-style="select-with-transition" data-title="" id="dept" name="dept" onChange="programTable();">
                                                </select>
                                                <div class="icon-container" style="display:none;">
                                                    <i class="loader"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <a class="btn btn-success btn-sm" href="<?php echo base_url();?>Admin/addEditProgram"> + Add New Program</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="card mt-0">
                    <div class="card-body">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" id="prg_progress" style="width: 100%; display: none;">Loading the list. Please wait...</div>
                        <div class="material-datatables">
                            <table class="table table-hover table-striped" width="100%" id="progTable">
								<thead>
									<tr>
										<th width="25%">Program</th>
										<th width="20%">Institute</th>
										<th width="15%">Department</th>
										<th width="10%">Duration</th>
										<th width="15%">Date-Time</th>
										<th width="15%">Actions</th>
									</tr>
								</thead>
								<tbody id="porgList">
								
								</tbody>
							</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/org-admin.js"></script>
<script>
	programTable();
	$('#progTable').DataTable();
</script>