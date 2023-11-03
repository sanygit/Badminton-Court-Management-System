
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">List of Court Rentals</h3>
		<div class="card-tools">
			<a href="./?page=court_rentals/manage_court_rental" class="btn btn-primary bg-gradient-primary btn-flat btn-sm"><i class="fa fa-plus"></i> Create New</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="20%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Client</th>
						<th>Court</th>
						<th>Start</th>
						<th>End</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT cr.*, c.name as `court` FROM `court_rentals` cr inner join court_list c on cr.court_id = c.id order by c.`status` asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td><?php echo $row['client_name'] ?></td>
							<td><?php echo $row['court'] ?></td>
							<td class=""><?= date("M d, Y h:i A", strtotime($row['datetime_start'])) ?></td>
							<td class=""><?= date("M d, Y h:i A", strtotime($row['datetime_end'])) ?></td>
							<td class="text-center">
								<?php
								switch($row['status']){
									case 0:
										echo '<span class="badge badge-secondary bg-gradient-secondary text-sm px-3 rounded-pill">On-Going</span>';
										break;
									case 1:
										echo '<span class="badge badge-success bg-gradient-teal text-sm px-3 rounded-pill">Done</span>';
										break;
								}
								?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item view_data" href="./?page=court_rentals/view_court_rental&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this court_rental permanently?","delete_court_rental",[$(this).attr('data-id')])
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [7] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_court_rental($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_court_rental",
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
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>