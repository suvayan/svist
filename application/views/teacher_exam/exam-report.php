<div class="content">
	<div class="container-fluid">       
        <div class="row">
            <div class="col-md-5 ml-auto mr-auto">
                <div class="card">
                    <div class="card-header card-header-info card-header-text">
			            <div class="card-text">
				            <h4 class="card-title">Personal Details</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive table-sales">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td><img src="<?php echo ($user_details->photo_sm)? base_url().$user_details->photo_sm : basr().'assets/img/default-avatar.png' ;?>" width="80"></td>
                                            </tr>
                                            <tr>
                                                <td>Name:</td>
                                                <td><?php echo $user_details->name;?></td>
                                            </tr>
                                            <tr>
                                                <td>Phone:</td>
                                                <td><?php echo $user_details->phone;?></td>
                                            </tr>
                                            <tr>
                                                <td>Email:</td>
                                                <td><?php echo $user_details->email;?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 ml-auto mr-auto">
                <div class="card">
                    <div class="card-header card-header-info card-header-text">
			            <div class="card-text">
				            <h4 class="card-title">Test Details</h4>
			            </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive table-sales">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Test Name:</td>
                                                <td><?php echo $test_details->title;?></td>
                                            </tr>
                                            <tr>
                                                <td>Program:</td>
                                                <td><?php echo $test_details->prog_title;?></td>
                                            </tr>
                                            <tr>
                                                <td>Start Time:</td>
                                                <td><?php echo !empty($test_details->test_start_dttm)? date('d/M/Y h:ia',strtotime($test_details->test_start_dttm)): '';?></td>
                                            </tr>
                                            <tr>
                                                <td>End Time:</td>
                                                <td><?php echo !empty($test_details->test_end_dttm)? date('d/M/Y h:ia',strtotime($test_details->test_end_dttm)) : '';?></td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>                   
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 ml-auto mr-auto">
                <div class="card">
                    <div class="card-body">
                    <?php
                        if(!empty($test_report)):
                            foreach($test_report as $key=>$data):
                                $i = 1;
                    ?>
                        <h3 class="card-title"><?php echo $key;?></h3>
                        <ul class="list-group list-group-flush">
                        <?php foreach($test_report[$key] as $row):?>
                            <li class="list-group-item">
                                <p class="card-text"><?php echo $i;?>.Question : <?php echo $row['question'];?></p>
                                Difficulty : <?php echo $row['difficulty_level'];?>, Weightage : <?php echo $row['weightage'];?>,Marks : <?php echo $row['marks'];?>, Type : <span class="label label-info ml-2"><?php echo $row['type'];?> Type</span><br>
                                Correct Answer : <?php echo $row['correct_answer'];?>, Answer : <?php echo $row['answer'];?>, Hint : <?php echo $row['hint'];?><br>
                                Given Answer : <?php echo $row['given_answer'];?>
                                <a href="#" class="btn btn-sm btn-success">Right</a>
                                <a href="#" class="btn btn-sm btn-danger">Wrong</a>
                                <hr>
                            </li>
                        <?php
                            $i++;
                            endforeach;
                        ?>
                        </ul>
                    <?php
                            endforeach;
                        endif;
                    ?>
                    </div>                           
                </div>
            </div>
        </div>
    </div>
</div>