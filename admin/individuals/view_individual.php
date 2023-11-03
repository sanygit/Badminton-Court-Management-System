<?php

if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT *, concat(lastname, ', ', firstname, ' ', coalesce(middlename,'')) as `name` from `individual_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
        if(isset($id)){
            $meta_qry = $conn->query("SELECT * FROM `individual_meta` where `individual_id` = '{$id}' ");
            while($row = $meta_qry->fetch_assoc()){
                ${$row['meta_field']} = $row['meta_value'];
            }
        }
    }
}
?>
<div class="content py-5 bg-gradient-primary px-4">
    <div class="container-fluid">
        <h2>Individual Details</h2>
    </div>
</div>
<style>
    #indi-img{
        width:6em;
        height:6em;
        object-fit:cover;
        object-position:center center;
    }
    #vaccine-img{
        width:100%;
        height:15em;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<div class="row justify-content-center" style="margin-top:-3em">
    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
        <div class="card card-outline-primary rounded-0 shadow">
            <div class="card-body">
                <div class="container-fluid">
                    <center>
                        <img src="<?= validate_image(isset($avatar) ? $avatar : '') ?>" alt="" class="img-thumbnail rounded-circle" id="indi-img">
                    </center>
                    <div class="d-flex w-100">
                        <div class="col-auto text-muted">Name:</div>
                        <div class="col-auto flex-shrink-1 flex-grow-1 font-weight-bolder"><?= isset($name) ? $name : '' ?></div>
                    </div>
                    <div class="d-flex w-100">
                        <div class="col-auto text-muted">Gender:</div>
                        <div class="col-auto flex-shrink-1 flex-grow-1 font-weight-bolder"><?= isset($gender) ? $gender : '' ?></div>
                    </div>
                    <div class="d-flex w-100">
                        <div class="col-auto text-muted">Email:</div>
                        <div class="col-auto flex-shrink-1 flex-grow-1 font-weight-bolder"><?= isset($email) ? $email : '' ?></div>
                    </div>
                    <div class="d-flex w-100">
                        <div class="col-auto text-muted">Contact #:</div>
                        <div class="col-auto flex-shrink-1 flex-grow-1 font-weight-bolder"><?= isset($contact) ? $contact : '' ?></div>
                    </div>
                    <div class="d-flex w-100">
                        <div class="col-auto text-muted">Address:</div>
                        <div class="col-auto flex-shrink-1 flex-grow-1 font-weight-bolder"><?= isset($address) ? $address : '' ?></div>
                    </div>
                    <div class="text-center my-2">
                        <a href="<?= isset($vaccine_card_path) ? base_url.$vaccine_card_path : '' ?>" target="_blank">
                            <img src="<?= validate_image(isset($vaccine_card_path) ? $vaccine_card_path : '') ?>" alt="" class="img-fluid" id="vaccine-img">
                        </a>
                        <label for="">Vaccination Card</label>
                    </div>
                    <div class="d-flex w-100">
                        <div class="col-auto text-muted">Status:</div>
                        <div class="col-auto flex-shrink-1 flex-grow-1 font-weight-bolder">
                            <?php
                                $status = isset($status) ? $status : 0;
								switch($status){
									case 0:
										echo '<span class="badge badge-secondary bg-gradient-secondary text-sm px-3 rounded-pill"><i class="fa fa-circle"></i> Waiting for Verification</span>';
										break;
									case 1:
										echo '<span class="badge badge-primary bg-gradient-primary text-sm px-3 rounded-pill"><i class="fa fa-check"></i> Verified</span>';
										break;
									case 2:
										echo '<span class="badge badge-danger bg-gradient-danger text-sm px-3 rounded-pill"><i class="fa fa-times"></i> Denied</span>';
										break;
									case 3:
										echo '<span class="badge badge-maroon bg-gradient-maroon text-sm px-3 rounded-pill"><i class="fa fa-exclamation-circle"></i> Blocked</span>';
										break;
								}
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer py-1 text-center">
                <button class="btn btn-primary btn-sm btn-flat" id="update_status" type="button"><i class="fa fa-edit"></i> Update Status</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#update_status').click(function(){
            uni_modal("<i class='fa fa-edit'></i> Update Status","individuals/update_status.php?id=<?= isset($id) ? $id : '' ?>")
        })
    })
</script>