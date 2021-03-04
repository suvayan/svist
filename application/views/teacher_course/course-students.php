<div class="row">
	
	<div class="col-md-12">
		<div class="card mt-2" style="background-color: #e6ecf6;">
			<div class="card-header card-header-info card-header-text">
			  <div class="card-text">
				<h4 class="card-title"><?php echo $title; ?></h4>
			  </div>
			</div>
			<div class="card-body">
				<div class="material-datatables">
					<table class="table table-striped table-no-bordered table-hover" id="studentList" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th width="5%">Sl.</th>
								<th width="60%" colspan="2">Name</th>
								<th width="20%">Email</th>
								<th width="15%">Phone</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1; foreach($pstud as $srow){ ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td width="9%"><?php echo '<img src="'.base_url().$srow->photo_sm.'" onerror="this.src=`'.base_url('assets/img/default-avatar.png').'`" class="avatar-dp mr-2" />'; ?></td>
									<td><?php echo '<div class="td-name">'.$srow->name.'</div>'; ?></td>
									<td><?php echo $srow->email; ?></td>
									<td><?php echo $srow->phone; ?></td>
								</tr>
							<?php $i++; } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#studentList').DataTable();
</script>