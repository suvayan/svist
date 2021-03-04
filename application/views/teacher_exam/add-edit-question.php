<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="card">
            <div class="card-header card-header-info">
                All Questions Bank
            </div>
            <div class="card-body">
                <input type="hidden" id="sid" value="<?php echo $sid;?>">
                <ul class="list-group">
                    <?php
                        if(!empty($qbks)):
                            foreach($qbks as $qb):
                    ?>
                    <li class="list-group-item">
                        <a href="javascript:;" onclick="displayQuestionBlock(<?php echo $qb->id;?>)"><?php echo $qb->name?></a>
                    </li>
                    <?php
                            endforeach;
                        endif;
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-lg-9">
        <div class="card">
            <div class="card-header card-header-info">
                All Questions 
            </div>
            <div class="card-body">
                <?php
                    $i = 0;
                    if(!empty($qbks)):
                        foreach($qbks as $qb):
                ?>
                        <div id="ques_block<?php echo $qb->id;?>" style="<?php echo ($i==0)? 'display: block' : 'display: none';?>" class="ques-block" data-id="<?php echo $qb->id;?>">
                    
                            <div class="material-datatables">
                                <div id="datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 ml-5">
                                            <div class="dataTables_length" id="datatables_length">
                                                <input type="checkbox" class="form-check-input main" id="main<?php echo $qb->id?>" onChange="allQuestionSelectBySection('<?php echo $qb->id?>')">
                                                <label class="form-check-label" for="exampleCheck1">Select All</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="datatables" class="table table-striped table-no-bordered table-hover dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="datatables_info" width="100%" cellspacing="0">
                                                <tbody>
                                                    <?php
                                                        if(!empty($question)):
                                                            foreach($question as $ques):
                                                                if($ques['qb_id'] == $qb->id):
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" class="form-check-input sb sub<?php echo $qb->id?>" onchange="clickChangeEvent(<?php echo $qb->id?>)" data-value="<?php echo $qb->id;?>" value="<?php echo $ques['id'];?>" <?php echo ($ques['has'])? 'checked' : '';?>>
                                                            </td>
                                                            <td>
                                                                <p><?php echo $ques['qbody'];?></p>
                                                                <br>
                                                                <?php if($ques['type_id']==2):?>
                                                                    <div class="row">
                                                                        <?php
                                                                            foreach($options as $ops):
                                                                                if($ops->ques_id == $ques['id']):
                                                                        ?>
                                                                                <div class="col-sm-6 d-flex">
                                                                                    <i class="material-icons <?php echo (trim($ops->correct_flag)=='t')? 'text-success':'text-danger';?>"><?php echo (trim($ops->correct_flag)=='t')? 'check_circle':'remove_circle';?></i> 
                                                                                    <p><?php echo $ops->body;?></p>/<?php echo $ops->weightage;?> % marks
                                                                                </div>
                                                                        <?php
                                                                                endif;
                                                                            endforeach;
                                                                        ?>
                                                                    </div>
                                                                <?php else:?>
                                                                    <p>
                                                                        <?php echo $ques['answer'];?><?php (!empty($ques['hints']))?'('.$ques['hints'].')': '';?>
                                                                    </p>
                                                                <?php endif;?>
                                                                Weightage: <?php echo $ques['weightage'];?>, Difficulty Level: <?php echo $ques['difficulty_level'];?>, Marks: <?php echo $ques['marks'];?>,
                                                                <span class="label label-info ml-2"><?php echo $ques['type'];?> Type</span>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-5"></div>
                                        <div class="col-sm-12 col-md-7">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="saveAddUpdateQuestion()">Save</button>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="closeAddUpdateQuestion()">close</button>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                        $i++;
                        endforeach;
                    endif;
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        if($('.sb:checked').length == $('.sb').length){
            $('.main').prop('checked',true);
        }else{
            $('.main').prop('checked',false);
        }
    });

    function displayQuestionBlock(id,name){
        $('#ques_block'+id).css('display','block');
        $(".ques-block").each(function(){
            let dataId = $(this).attr('data-id');
            if(dataId != id){
                $('#ques_block'+dataId).css('display','none');
            }
        });
    }


    function allQuestionSelectBySection(id){
        if($('#main'+id).prop("checked") == true){
            $('.sub'+id).each(function(){
                this.checked = true;
            });
        }else if($('#main'+id).prop("checked") == false){
            $('.sub'+id).each(function(){
                this.checked = false;
            });
        }
    }

    function clickChangeEvent(id){
        if($('.sub'+id+':checked').length == $('.sub'+id).length){
            $('#main'+id).prop('checked',true);
        }else{
            $('#main'+id).prop('checked',false);
        }
    }

    function closeAddUpdateQuestion(){
        window.location.reload();
    }
    let findDups = (arr) =>{
        const dups    = [];
        const len     = arr.length;

        for(let i = 0; i < len; i++){
            if(dups.indexOf(arr[i]) == -1){
                dups.push(arr[i]);
            }
        }
        
        return dups;
    }
    function saveAddUpdateQuestion(){
        let allQues = [];
        let allQBks = [];
        let sid     = $('#sid').val();
        $('input[type="checkbox"]:checked').each(function(){
            if($(this).val() !== "on"){
                allQues.push($(this).val());
            }
            if($(this).attr('data-value')!==undefined){
                allQBks.push($(this).attr('data-value'));
            }
        });
        //console.log(allQBks);
        let qbArr         = findDups(allQBks);
        //console.log(qbArr);
        let questions     = allQues.join(',');
        let question_bank = qbArr.join(',');
        $.ajax({
            url:baseURL+'Teacher/saveAddUpdateQuestion',
            type: 'POST',
            data:{
                // sid   : sid,
                // ques  : questions,
                // qbank : question_bank
                sid   : sid,
                ques  : allQues,
                qbank : qbArr
            },
            success: function(data){
                console.log(data);
                var resp = JSON.parse(data);
                console.log(resp);
                if(resp.status){
                    $.notify({icon:"add_alert",message:'Quesstion add successfully.'},{type:'success',timer:3e3,placement:{from:'top',align:'right'}});
                }else{
                    $.notify({icon:"add_alert",message:'Error'},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}});
                }

            }
        });
    }
</script>