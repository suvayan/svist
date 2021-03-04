<?php if($purpose == "getOrganizationName"):?>
        <h3 class="card-title">
            <?php echo $org_name[0]->title;?>
			<a href="javascript:organizationEditModal(`Edit`,<?php echo $org_name[0]->id;?>);" title="Update" id="org_update" class="btn btn-success btn-sm pull-right"><i class="material-icons">edit</i> Edit Organization</a>
		</h3>
<?php elseif($purpose == "getDepartmentByOrganization"):?>
<!--=========================== Department List =======================================================================================-->
        <ol type="1">
        <?php
            if(!empty($department)):
                foreach($department as $data):
        ?>
                    <li class="mb-2" id="list_4">
                        <?php echo $data->title; echo (!empty($data->short_name))? '('.$data->short_name.')':'';?>
                        <a href="javascript:departmentAddEditModal(`Edit`,<?php echo $data->org_id;?>,<?php echo $data->id;?>,`<?php echo $org[0]->title;?>`)" title="Edit" id="Edit_dept_<?php echo $data->id;?>" class="text-info"><i class="material-icons">edit</i></a>
                        <a href="javascript:deleteDepartment(<?php echo $data->id;?>)" title="Delete" class="text-danger"><i class="material-icons">delete</i></a>
                    </li>
        <?php
                endforeach;
            endif;
        ?>
        </ol>
<!--=====================================================================================================================================-->


<?php elseif($purpose == "organizationEditModal"):?>
<!--=========================================== Organization Edit Modal Body ==================================================================================-->
        <div class="row">
			<div class="col-md-6">
				<label for="title">Logo</label>
				<div class="picture-container">
					<div class="picture">
						<label>
                            <img src="<?php echo base_url('assets/img/institute/'.trim($org[0]->logo));?>" class="picture-src" onerror="this.src=`<?php echo base_url('assets/img/image_placeholder.jpg');?>`" id="wizardPicturePreview" title="" width="250"/>
							<input type="file" name="avatar" id="avatar" accept="image/jpg, image/jpeg, image/png" style="display:none;" onchange="avaterOnChange()">
						</label>
						<input type="hidden" name="crop_img" id="crop_img" value="" />
					</div>
				</div>
			</div>
			<div class="col-md-6" id="cropImagePop" style="display:none;">
				<div id="upload-demo" class="center-block"></div>
				<br>
				<button type="button" onClick="cropImageBtn()" class="btn btn-primary">Crop</button>
				<button type="button" class="btn btn-danger btn-link" onClick="$(`#cropImagePop`).hide()">Cancel</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="title">Title <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="title" id="title" value="<?php echo trim($org[0]->title);?>" required="true">
				</div>
				<div class="form-group">
					<label for="url">Website</label>
					<input type="url" class="form-control" name="weburl" id="weburl" value="<?php echo trim($org[0]->website);?>">
				</div>
				<div class="form-group">
					<input type="hidden" class="form-control" name="id" id="id" value="<?php echo $org[0]->id;?>">
					<input type="hidden" class="form-control" name="logo" id="logo" value="<?php echo trim($org[0]->logo);?>">
				</div>
				<div class="form-group">
					<label for="contact">Contact</label><br>
					<textarea class="form-control" name="contact" id="contact" rows="3"></textarea>
					<script>$("#contact").summernote("code", `<?php echo trim($org[0]->contact_info);?>`);</script>
				</div>
				<div class="form-group">
					<label for="details">Details</label><br>
					<textarea class="form-control" name="details" id="details" rows="3"></textarea>
					<script>$("#details").summernote("code", `<?php echo trim($org[0]->details);?>`);</script>
				</div>
			</div>
		</div>
<!--============================================================================================================================================================-->
<?php elseif($purpose == "departmentAddEditModal"):?>   
    <div class="row">
        <div class="col-md-12">
            <h5>Under: <?php echo trim($dept[0]->po_title);?></h5>
            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title" id="title" value="<?php echo trim($dept[0]->title);?>" required="true">
            </div>
            <div class="form-group">
                <label for="short_name">Short Form <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="short_name" id="short_name" value="<?php echo trim($dept[0]->short_name);?>" required="true">
            </div>
            <div class="form-group">
                <label for="url">Website</label>
                <input type="url" class="form-control" name="weburl" id="weburl" value="<?php echo trim($dept[0]->website);?>">
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $dept[0]->id;?>">
                <input type="hidden" class="form-control" name="org_id" id="org_id" value="<?php echo $dept[0]->org_id;?>">
                <input type="hidden" class="form-control" name="logo" id="logo" value="<?php echo trim($dept[0]->logo);?>">
            </div>
            <div class="form-group">
                <label for="contact">Contact</label><br>
                <textarea class="form-control" name="contact" id="contact" rows="3"></textarea>
                <script>$("#contact").summernote("code", `<?php echo trim($dept[0]->contact_info);?>`);</script>
            </div>
            <div class="form-group">
                <label for="details">Details</label><br>
                <textarea class="form-control" name="details" id="details" rows="3"></textarea>
                <script>$("#details").summernote("code", `<?php echo trim($dept[0]->details);?>`);</script>
            </div>
        </div>
    </div>
<?php endif;?>