<?php
	$i=1;
	if(!empty($profs)){
		foreach($profs as $row){
			echo '<tr>
					<td>'.$i.'</td>
					<td><img src="'.base_url($row->photo_sm).'" onerror="this.src=`'.base_url('assets/img/default-avatar.png').'`" class="img-fluid pull-left mr-2" width="80"/> '.$row->name;
					if(!empty(${'strms_'.$i})){
						$org_id=0;
						foreach(${'strms_'.$i} as $stow){
							if($org_id!=$stow->org_id){
								echo '<br><strong>'.trim($stow->po_title).'</strong>';
								$org_id=$stow->org_id;
							}
							echo '<br>'.trim($stow->ps_title);
						}
					}
					echo '</td>
					<td>'.trim($row->about_me).'</td>
					<td>'.trim($row->linkedin_link).'</td>
					<td>
						<a href="'.base_url('Admin/addEditTeacher/?id='.base64_encode($row->id)).'" title="Edit" class="btn btn-sm btn-primary"><i class="material-icons">edit</i></a>
						<a href="'.base_url('Admin/orgDepTeacher/?id='.base64_encode($row->id)).'" title="Link" class="btn btn-sm btn-primary"><i class="material-icons">link</i></a>
						<a href="javascript:changePassword(`'.base64_encode($row->id).'`);" title="Password" class="btn btn-sm btn-warning"><i class="material-icons">vpn_key</i></a>
					</td>
				</tr>';
			$i++;
		}
	}
?>