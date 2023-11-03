<?php

if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `service_transaction` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="mx-0 py-5 px-3 mx-ns-4 bg-gradient-primary">
	<h3><b>Service Transaction Details</b></h3>
</div>
<div class="row justify-content-center" style="margin-top:-2em;">
	<div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-body">
				<div class="container-fluid">
					<div class="text-right">
						<span><small class="text-muted mr-2"><em>Status</em></small></span>
						<?php $status = isset($status) ? $status : ''; ?>
						<?php 
							switch($status){
								case 0:
									echo '<span class="badge badge-light bg-gradient-light py-1 border rounded-pill px-3"><i class="fa fa-circle text-secondary"></i> Pending</span>';
									break;
								case 1:
									echo '<span class="badge badge-light bg-gradient-light py-1 border rounded-pill px-3"><i class="fa fa-circle text-success"></i> Done</span>';
									break;
							}
						?>
					</div>
					<div class="clear-fix mt-2"></div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
							<label for="control-label">Client Name</label>
							<div class="pl-4"><?= isset($client_name) ? $client_name : '' ?></div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
							<label for="control-label">Contact #</label>
							<div class="pl-4"><?= isset($contact) ? $contact : '' ?></div>
						</div>
					</div>
					<hr>
					<fieldset>
                        <legend>Services</legend>
                        <table class="table table-bordered">
                            <colgroup>
                                <col width="20%">
                                <col width="40%">
                                <col width="20%">
                                <col width="20%">
                            </colgroup>
                            <thead>
                                <tr class="bg-gradient-primary">
                                    <th class="p-1 text-center">QTY</th>
                                    <th class="p-1 text-center">Service</th>
                                    <th class="p-1 text-center">Price</th>
                                    <th class="p-1 text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0; 
                                if(isset($id)):
									$services = $conn->query("SELECT st.*, s.name as `service` FROM `service_transaction_items` st inner join `service_list` s on st.service_id = s.id where st.service_transaction_id = '{$id}'");
                                    while($row = $services->fetch_assoc()):
                                        $total += $row['quantity'] * $row['price'];
                                ?>
                                <tr>
                                    <td class="p-1 align-middle text-center"><?= format_num($row['quantity']) ?></td>
                                    <td class="p-1 align-middle"><?=$row['service'] ?></td>
                                    <td class="p-1 align-middle text-right"><?= format_num($row['price']) ?></td>
                                    <td class="p-1 align-middle text-right"><?= format_num($row['price'] * $row['quantity']) ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gradient-secondary">
                                    <th class="p-1 text-center align-middle" colspan="3">Total</th>
                                    <th class="p-1 text-right align-middle"><?= format_num($total,2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </fieldset>
				</div>
			</div>
			<div class="card-footer py-1 text-center">
				<a class="btn btn-info btn-sm bg-gradient-info rounded-0" href="javascript:void(0)" id="update_status"> Update Status</a>
				<a class="btn btn-primary btn-sm bg-gradient-primary rounded-0" href="./?page=service_transactions/manage_service_transaction&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
				<button class="btn btn-danger btn-sm bg-gradient-danger rounded-0" type="button" id="delete-data"><i class="fa fa-trash"></i> Delete</button>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=service_transactions"><i class="fa fa-angle-left"></i> Back to List</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#update_status').click(function(){
            uni_modal("<i class='fa fa-edit'></i> Update Status","service_transactions/update_status.php?id=<?= isset($id) ? $id : '' ?>")
        })
        $('#delete-data').click(function(){
			_conf("Are you sure to delete this service_transactions permanently?","delete_service_transactions",['<?= isset($id) ? $id : '' ?>'])
		})
	})
    function delete_service_transactions($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_service_transactions",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.replace("./?page=service_transactions");
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>