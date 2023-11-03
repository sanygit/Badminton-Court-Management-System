<?php

if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `court_rentals` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
$gtotal = 0;
?>
<div class="mx-0 py-5 px-3 mx-ns-4 bg-gradient-primary">
	<h3><b><?= isset($id) ? "Update Court Rental Details" : "Create New Court Rental" ?></b></h3>
</div>
<div class="row justify-content-center" style="margin-top:-2em;">
	<div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-body">
				<div class="container-fluid">
					<div class="container-fluid">
						<form action="" id="court_rental-form">
							<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
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
							<div class="row">
								<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
									<label for="court_id" class="control-label">Court</label>
									<select name="court_id" id="court_id" class="form-control form-control-sm rounded-0" required="required">
										<option value="" disabled <?= !isset($client_id) ? "selected" : "" ?>></option>
										<?php 
										$court_qry = $conn->query("SELECT * FROM `court_list` where delete_flag = 0 and `status` = 1 and id not in (SELECT court_id FROM `court_rentals` where `status` = 0) ".(isset($court_id) && $court_id > 0 ? "or id = '{$court_id}'" : "")." order by `name` asc");
										while($row = $court_qry->fetch_assoc()):
										?>
										<option value="<?= $row['id'] ?>" data-price="<?= $row['price'] ?>" <?= isset($court_id) && $court_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
										<?php endwhile; ?>
									</select>
								</div>
								<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
									<label for="court_price" class="control-label">Rate per Hour</label>
									<input type="text" class="form-control form-control-sm rounded-0 text-right" id="court_price" name="court_price" value="<?= isset($court_price) ? $court_price : 0 ?>" readonly>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
									<label for="datetime_start" class="control-label">Date From</label>
									<input type="datetime-local" class="form-control form-control-sm rounded-0" id="datetime_start" name="datetime_start" value="<?= isset($datetime_start) ? date("Y-m-d\TH:i", strtotime($datetime_start)) : '' ?>" max = "<?= date("Y-m-d\TH:i") ?>" required="required">
								</div>
								<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
									<label for="hours" class="control-label">Hours</label>
									<input type="number" min="1" class="form-control form-control-sm rounded-0" id="hours" name="hours" value="<?= isset($hours) ? $hours : 1 ?>" required="required">
								</div>
								<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
									<label for="datetime_end" class="control-label">Date To</label>
									<input type="datetime-local" class="form-control form-control-sm rounded-0" id="datetime_end" name="datetime_end" value="<?= isset($datetime_end) ? date("Y-m-d\TH:i", strtotime($datetime_end)) : '' ?>" max = "<?= date("Y-m-d\TH:i") ?>"  readonly>
								</div>
								<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
									<label for="total" class="control-label">Total</label>
									<input type="text" class="form-control form-control-sm rounded-0 text-right" id="total" name="total" value="<?= isset($total) ? $total : 0 ?>" readonly>
									<?php $gtotal += (isset($total) ? ($total) : 0) ?>
								</div>
							</div>
							<div class="clear-fix mt-1"></div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
													$sales = $conn->query("SELECT s.*, p.name as product FROM `sales_transaction_items` s inner join `product_list` p on s.product_id = p.id where s.sales_transaction_id in (SELECT id FROM `sales_transaction` where court_rental_id = '{$id}')");
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
													<?php $gtotal += (isset($total) ? ($total) : 0) ?>
												</tr>
											</tfoot>
										</table>
									</fieldset>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<fieldset>
										<legend>Services</legend>
										<div class="row align-items-end mb-2">
											<div class="form-group col-lg-8 col-md-8 col-sm-12 col-xs-12">
												<label for="service" class="control-label">Choose service</label>
												<select type="text" class="form-control form-control-sm rounded-0" id="service">
													<option value="" selected disabled></option>
													<?php 
													$services = $conn->query("SELECT * FROM `service_list` where delete_flag = 0 and `status` = 1 order by `name` asc");
													while($row = $services->fetch_array()):
													?>
													<option value="<?= $row['id'] ?>" data-price="<?= $row['price'] ?>"><?= $row['name'] ?></option>
													<?php endwhile; ?>
												<select>
											</div>
											<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<button class="btn btn-primary bg-gradient-primary btn-sm rounded-0" type="button" id="add_service"><i class="fa fa-plus"></i> Add</button>
											</div>
										</div>
										<table class="table table-bordered" id="service-list">
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
													$services = $conn->query("SELECT st.*, s.name as `service` FROM `service_transaction_items` st inner join `service_list` s on st.service_id = s.id where st.service_transaction_id in (SELECT id FROM `service_transaction` where court_rental_id = '{$id}')");
													while($row = $services->fetch_assoc()):
														$total += $row['quantity'] * $row['price'];
												?>
												<tr>
													<td class="p-1 align-middle text-center">
														<input type="hidden" name="service_id[]" value="<?= $row['service_id'] ?>">
														<input type="hidden" name="service_price[]" value="<?= $row['price'] ?>">
														<a href="javascript:void(0)" class="p-1 text-decoration-none text-danger rem_prod"><i class="fa fa-times"></i></a>
													</td>
													<td class="p-1 align-middle text-center">
														<input type="number" min="1" value= '<?= $row['quantity'] ?>' class="form-control form-control-sm rounded-0 text-right" name="service_quantity[]">
													</td>
													<td class="p-1 align-middle service_name"><?= $row['service'] ?></td>
													<td class="p-1 align-middle service_price text-right"><?= format_num($row['price']) ?></td>
													<td class="p-1 align-middle service_total text-right"><?= format_num($row['price'] * $row['quantity']) ?></td>
												</tr>
												<?php endwhile; ?>
												<?php endif; ?>
											</tbody>
											<tfoot>
												<tr class="bg-gradient-secondary">
													<th class="p-1 text-center align-middle" colspan="4">Total</th>
													<th class="p-1 text-right align-middle service_gtotal"><?= format_num($total, 2) ?></th>
													<?php $gtotal += (isset($total) ? ($total) : 0) ?>
												</tr>
											</tfoot>
										</table>
									</fieldset>
								</div>
							</div>
							<div class="clear-fix mt-1"></div>
							<div class="text-right">
								<h4 class="text-right font-weight-bolder">Grand Total: <span id="gtotal"><?= format_num($gtotal, 2) ?></span></h4>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-primary btn-sm bg-gradient-primary rounded-0" form="court_rental-form"><i class="fa fa-save"></i> Save</button>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=court_rentals"><i class="fa fa-angle-left"></i> Cancel</a>
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
<noscript id="service-item">
	<tr>
		<td class="p-1 align-middle text-center">
			<input type="hidden" name="service_id[]">
			<input type="hidden" name="service_price[]">
			<a href="javascript:void(0)" class="p-1 text-decoration-none text-danger rem_prod"><i class="fa fa-times"></i></a>
		</td>
		<td class="p-1 align-middle text-center">
			<input type="number" min="1" value= '1' class="form-control form-control-sm rounded-0 text-right" name="service_quantity[]">
		</td>
		<td class="p-1 align-middle service_name"></td>
		<td class="p-1 align-middle service_price text-right"></td>
		<td class="p-1 align-middle service_total text-right"></td>
	</tr>
</noscript>
<script>
	function get_dt_end(){
		var dt_start = $('#datetime_start').val()
		var hrs = $('#hours').val()
		if(dt_start == ''  || hrs == '' || hrs <= 0)
		return false;
		dt_start = new Date(dt_start)
		var dt_end = new Date(dt_start.getTime() + hrs * 60 *60 *1000)
		dt_end = dt_end.getFullYear() + "-" + ((dt_end.getMonth() + 1).toString().padStart(2, 0)) + "-" + ((dt_end.getDate()).toString().padStart(2, 0)) + "\T" + ((dt_end.getHours()).toString().padStart(2, 0)) + ":" + ((dt_end.getMinutes()).toString().padStart(2, 0)) ;
		$('#datetime_end').val(dt_end)
		calc_total()
	}
	function get_hour_total(){
		var hrs = $('#hours').val()
		var price = $('#court_price').val()
		hrs = hrs > 0 ? hrs : 0 ;
		price = price > 0 ? price : 0 ;
		var total = parseFloat(hrs) * parseFloat(price);
		$('#total').val(total)
		calc_total()

	}
	function calc_total(){
		var gtotal = 0;
		var ptotal = 0;
		var stotal = 0;
		var court_price = $('#court_price').val();
			court_price = court_price > 0 ? parseFloat(court_price) : 0; 
			gtotal += court_price
		$('#product-list tbody tr').each(function(){
			var price = $(this).find('[name="product_price[]"]').val()
			var quantity = $(this).find('[name="product_quantity[]"]').val()
				price = price > 0 ? price : 0;
				quantity = quantity > 0 ? quantity : 0;
			var total = parseFloat(price) * parseFloat(quantity)
			ptotal += parseFloat(total)
			$(this).find('.product_total').text(total.toLocaleString('en-US'))
		})
		$('.product_gtotal').text(parseFloat(ptotal).toLocaleString('en-US',{ style:'decimal', minimumFractionDigits:2, maximumFractionDigits:2}))
		gtotal += ptotal;
		$('#service-list tbody tr').each(function(){
			var price = $(this).find('[name="service_price[]"]').val()
			var quantity = $(this).find('[name="service_quantity[]"]').val()
				price = price > 0 ? price : 0;
				quantity = quantity > 0 ? quantity : 0;
			var total = parseFloat(price) * parseFloat(quantity)
			stotal += parseFloat(total)
			$(this).find('.service_total').text(total.toLocaleString('en-US'))
		})
		$('.service_gtotal').text(parseFloat(stotal).toLocaleString('en-US',{ style:'decimal', minimumFractionDigits:2, maximumFractionDigits:2}))
		gtotal += stotal;
		$('#gtotal').text(parseFloat(gtotal).toLocaleString('en-US',{ style:'decimal', minimumFractionDigits:2, maximumFractionDigits:2}))

	}
	$(document).ready(function(){
		$('#court_id').select2({
			placeholder:"Please Select Court Here",
			width:"100%",
			containerCssClass:'form-control form-control-sm rounded-0'
		})
		$('#product').select2({
			placeholder:"Please Select Product Here",
			width:"100%",
			containerCssClass:'form-control form-control-sm rounded-0'
		})
		$('#service').select2({
			placeholder:"Please Select Service Here",
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
		$('#add_service').click(function(){
			var id = $('#service').val()
			var name = $('#service option[value="'+id+'"]').text()
			var price = $('#service option[value="'+id+'"]').attr('data-price')
				price = price > 0 ? price : 0
			if($('#service-list tbody').find('[name="service_id[]"][value="'+id+'"]').length > 0){
				alert("Service Already Listed.")
				return false;
			}
			var tr = $($('noscript#service-item').html()).clone()
			tr.find('[name="service_id[]"]').val(id)
			tr.find('[name="service_price[]"]').val(price)
			tr.find('.service_name').text(name)
			tr.find('.service_price').text(parseFloat(price).toLocaleString('en-US'))
			tr.find('.service_total').text(parseFloat(price).toLocaleString('en-US'))
			$('#service-list tbody').append(tr)
			calc_total()
			tr.find('.rem_prod').click(function(){
				tr.remove()
				calc_total()
			})
			tr.find('[name="service_quantity[]"]').on('input change',function(){
				calc_total()
			})
			$('#service').val('').trigger('change')
		})
		$('#service-list tbody tr').find('.rem_prod').click(function(){
			$(this).closest('tr').remove()
			calc_total()
		})
		$('#service-list tbody tr').find('[name="service_quantity[]"]').on('input change',function(){
			calc_total()
		})
		$('#datetime_start, #hours').on('input change',function(){
			get_dt_end()
			get_hour_total()
		})
		$('#court_id').change(function(){
			var id = $(this).val()
			var price = $('#court_id option[value="'+id+'"]').attr('data-price')
			$('#court_price').val(price)
			get_hour_total()
		})
		$('#court_rental-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_court_rental",
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
						location.href = "./?page=court_rentals/view_court_rental&id="+resp.crid
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