<?php

if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `sales_transaction` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="mx-0 py-5 px-3 mx-ns-4 bg-gradient-primary">
	<h3><b>Sales Transaction Details</b></h3>
</div>
<div class="row justify-content-center" style="margin-top:-2em;">
	<div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-body">
				<div class="container-fluid">
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
                        <legend>Products</legend>
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
                                    <th class="p-1 text-center">Product</th>
                                    <th class="p-1 text-center">Price</th>
                                    <th class="p-1 text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0; 
                                if(isset($id)):
                                    $sales = $conn->query("SELECT s.*, p.name as product FROM `sales_transaction_items` s inner join `product_list` p on s.product_id = p.id where s.sales_transaction_id ='{$id}'");
                                    while($row = $sales->fetch_assoc()):
                                        $total += $row['quantity'] * $row['price'];
                                ?>
                                <tr>
                                    <td class="p-1 align-middle text-center"><?= format_num($row['quantity']) ?></td>
                                    <td class="p-1 align-middle"><?=$row['product'] ?></td>
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
				<a class="btn btn-primary btn-sm bg-gradient-primary rounded-0" href="./?page=sales/manage_sale&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
				<button class="btn btn-danger btn-sm bg-gradient-danger rounded-0" type="button" id="delete-data"><i class="fa fa-trash"></i> Delete</button>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=sales"><i class="fa fa-angle-left"></i> Back to List</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
        $('#delete-data').click(function(){
			_conf("Are you sure to delete this sales permanently?","delete_sales",['<?= isset($id) ? $id : '' ?>'])
		})
	})
    function delete_sales($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_sales",
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
					location.replace("./?page=sales");
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>