<?php if($purpose == "teacherList"):?>
<?php 
    $i=1;
    if(!empty($teacherList)):
        foreach($teacherList as $row):
?>
        <tr>
            <td><?php echo $i;?></td>
            <td><img src="<?php echo base_url($row['photo']);?>" onerror="this.src=`<?php echo base_url('assets/img/default-avatar.png');?>`" class="img-fluid pull-left mr-2" width="80"/> </td>
            <td><?php echo $row['name']?></td>
            <td>
                <?php echo $row['email']?><br>
                <?php echo $row['phone']?>
            </td>
            <td>
                <?php
                    if(!empty($row['department'])){
                        echo '<ol>';
                        foreach($row['department'] as $drow){
                            echo '<li>'.$drow.'</li>';
                        }
                        echo '</ol>';
                    }
                ?>
            </td>
            <td>
                <a href="<?php echo base_url('Subadmin/addEditTeacher/?id='.base64_encode($row['id']));?>" title="Edit" class="btn btn-sm btn-primary"><i class="material-icons">edit</i></a>

				<a href="<?php echo base_url('Subadmin/orgDepTeacher/?id='.base64_encode($row['id']));?>" title="Link" class="btn btn-sm btn-primary"><i class="material-icons">link</i></a>

				<a href="javascript:changePassword(`<?php echo base64_encode($row['id']);?>`);" title="Password" class="btn btn-sm btn-warning"><i class="material-icons">vpn_key</i></a>
            </td>
        </tr>
<?php 
        $i++;
        endforeach;
    endif;
?>

<?php endif;?>