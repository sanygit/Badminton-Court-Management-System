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
	<h3><b><?= isset($id) ? "Update Sales Details" : "Create New Sales" ?></b></h3>
</div>
<div class="row justify-content-center" style="margin-top:-2em;">
	<div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-body">
				<div class="container-fluid">
					<form action="" id="sales-form">
						<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
						<input type="hidden" name ="total" value="<?php echo isset($total) ? $total : 0 ?>">
						<div class="row">
							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
								<label for="client_name" class="control-label">Client Name</label>
								<input type="text" class="form-control form-control-sm rounded-0" id="client_name" name="client_name" value="<?= isset($client_name) ? $client_name : '' ?>" required="required">
							</div>
							<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
								<label for="contact" class="control-label">Contact</label>
								<input type="text" class="form-control form-control-sm rounded-0" id="contact" name="contact" value="<?= isset($contact) ? $contact : '' ?>" required="required">
							</div>
						</div>
						<fieldset>
							<legend>Products</legend>
							<div class="row align-items-end mb-2">
								<div class="form-group col-lg-8 col-md-8 col-sm-12 col-xs-12">
									<label for="product" class="control-label">Choose Product</label>
									<select type="text" class="form-control form-control-sm rounded-0" id="product">
										<option value="" selected disabled></option>
										<?php 
										$products = $conn->query("SELECT * FROM `product_list` where delete_flag = 0 and `status` = 1 order by `name` asc");
										while($row = $products->fetch_array()):
										?>
										<option value="<?= $row['id'] ?>" data-price="<?= $row['price'] ?>"><?= $row['name'] ?></option>
										<?php endwhile; ?>
									<select>
								</div>
								<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
									<button class="btn btn-primary bg-gradient-primary btn-sm rounded-0" type="button" id="add_product"><i class="fa fa-plus"></i> Add</button>
								</div>
							</div>
							<table class="table table-bordered" id="product-list">
								<colgroup>
									<col width="5%">
									<col width="15%">
									<col width="30%">
									<col width="20%">
									<col width="20%">
								</colgroup>
								<thead>
									<tr class="bg-gradient-primary">
										<th class="px-2 py-1 text-center"></th>
										<th class="px-2 py-1 text-center">QTY</th>
										<th class="px-2 py-1 text-center">Name</th>
										<th class="px-2 py-1 text-center">Price</th>
										<th class="px-2 py-1 text-center">Total</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$total = 0; 
									if(isset($id)):
										$sales = $conn->query("SELECT s.*, p.name as product FROM `sales_transaction_items` s inner join `product_list` p on s.product_id = p.id where s.sales_transaction_id  = '{$id}'");
										while($row = $sales->fetch_assoc()):
											$total += $row['quantity'] * $row['price'];
									?>
									<tr>
										<td class="p-1 align-middle text-center">
											<input type="hidden" name="product_id[]" value="<?= $row['product_id'] ?>">
											<input type="hidden" name="product_price[]" value="<?= $row['price'] ?>">
											<a href="javascript:void(0)" class="p-1 text-decoration-none text-danger rem_prod"><i class="fa fa-times"></i></a>
										</td>
										<td class="p-1 align-middle text-center">
											<input type="number" min="1" value= '<?= $row['quantity'] ?>' class="form-control form-control-sm rounded-0 text-right" name="product_quantity[]">
										</td>
										<td class="p-1 align-middle product_name"><?= $row['product'] ?></td>
										<td class="p-1 align-middle product_price text-right"><?= format_num($row['price']) ?></td>
										<td class="p-1 align-middle product_total text-right"><?= format_num($row['price'] * $row['quantity']) ?></td>
									</tr>
									<?php endwhile; ?>
									<?php endif; ?>
								</tbody>
								<tfoot>
									<tr class="bg-gradient-secondary">
										<th class="p-1 text-center align-middle" colspan="4">Total</th>
										<th class="p-1 text-right align-middle product_gtotal"><?= format_num($total, 2) ?></th>
									</tr>
								</tfoot>
							</table>
						</fieldset>
					</form>
				</div>
			</div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-primary btn-sm bg-gradient-primary rounded-0" form="sales-form"><i class="fa fa-save"></i> Save</button>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=sales"><i class="fa fa-angle-left"></i> Cancel</a>
			</div>
		</div>
	</div>
</div>
<noscript id="product-item">
	<tr>
		<td class="p-1 align-middle text-center">
			<input type="hidden" name="product_id[]">
			<input type="hidden" name="product_price[]">
			<a href="javascript:void(0)" class="p-1 text-decoration-none text-danger rem_prod"><i class="fa fa-times"></i></a>
		</td>
		<td class="p-1 align-middle text-center">
			<input type="number" min="1" value= '1' class="form-control form-control-sm rounded-0 text-right" name="product_quantity[]">
		</td>
		<td class="p-1 align-middle product_name"></td>
		<td class="p-1 align-middle product_price text-right"></td>
		<td class="p-1 align-middle product_total text-right"></td>
	</tr>
</noscript>
<script>
	function calc_total(){
		var gtotal = 0;
		
		$('#product-list tbody tr').each(function(){
			var price = $(this).find('[name="product_price[]"]').val()
			var quantity = $(this).find('[name="product_quantity[]"]').val()
				price = price > 0 ? price : 0;
				quantity = quantity > 0 ? quantity : 0;
			var total = parseFloat(price) * parseFloat(quantity)
			gtotal += parseFloat(total)
			$(this).find('.product_total').text(total.toLocaleString('en-US'))
		})
		$('.product_gtotal').text(parseFloat(gtotal).toLocaleString('en-US',{ style:'decimal', minimumFractionDigits:2, maximumFractionDigits:2}))
		$('[name="total"]').val(gtotal)

	}
	$(document).ready(function(){
		$('#product').select2({
			placeholder:"Please Select Product Here",
			width:"100%",
			containerCssClass:'form-control form-control-sm rounded-0'
		})
		$('#add_product').click(function(){
			var id = $('#product').val()
			var name = $('#product option[value="'+id+'"]').text()
			var price = $('#product option[value="'+id+'"]').attr('data-price')
				price = price > 0 ? price : 0
			if($('#product-list tbody').find('[name="product_id[]"][value="'+id+'"]').length > 0){
				alert("Product Already Listed.")
				return false;
			}
			var tr = $($('noscript#product-item').html()).clone()
			tr.find('[name="product_id[]"]').val(id)
			tr.find('[name="product_price[]"]').val(price)
			tr.find('.product_name').text(name)
			tr.find('.product_price').text(parseFloat(price).toLocaleString('en-US'))
			tr.find('.product_total').text(parseFloat(price).toLocaleString('en-US'))
			$('#product-list tbody').append(tr)
			calc_total()
			tr.find('.rem_prod').click(function(){
				tr.remove()
				calc_total()
			})
			tr.find('[name="product_quantity[]"]').on('input change',function(){
				calc_total()
			})
			$('#product').val('').trigger('change')
		})
		$('#product-list tbody tr').find('.rem_prod').click(function(){
			$(this).closest('tr').remove()
			calc_total()
		})
		$('#product-list tbody tr').find('[name="product_quantity[]"]').on('input change',function(){
			calc_total()
		})

		$('#sales-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			if($('#product-list tbody').find("[name='product_id[]']").length <= 0){
				alert("Please Add atleast 1 Product first.")
				return false;
			}
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_sales",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.href="./?page=sales/view_details&id="+resp.sid
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body, .modal").scrollTop(0)
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})

	})
</script>