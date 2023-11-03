<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}
	function save_court(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `court_list` where `name` = '{$name}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : '')." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Court Name already exists.';
			return json_encode($resp);
		}
		if(empty($id)){
			$sql = "INSERT INTO `court_list` set {$data} ";
		}else{
			$sql = "UPDATE `court_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$aid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['aid'] = $aid;

			if(empty($id))
				$resp['msg'] = "New Court successfully saved.";
			else
				$resp['msg'] = " Court successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_court(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `court_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Court successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_product(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `product_list` where `name` = '{$name}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : '')." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Product Name already exists.';
			return json_encode($resp);
		}
		if(empty($id)){
			$sql = "INSERT INTO `product_list` set {$data} ";
		}else{
			$sql = "UPDATE `product_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$aid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['aid'] = $aid;

			if(empty($id))
				$resp['msg'] = "New Product successfully saved.";
			else
				$resp['msg'] = " Product successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_product(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `product_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Product successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_service(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `service_list` where `name` = '{$name}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : '')." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Service Name already exists.';
			return json_encode($resp);
		}
		if(empty($id)){
			$sql = "INSERT INTO `service_list` set {$data} ";
		}else{
			$sql = "UPDATE `service_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$aid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['aid'] = $aid;

			if(empty($id))
				$resp['msg'] = "New Service successfully saved.";
			else
				$resp['msg'] = " Service successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_service(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `service_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Service successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_court_rental(){
		extract($_POST);
		$data = "";
		$cr_allowed = ['client_name', 'court_id', 'contact', 'court_price', 'datetime_start', 'datetime_end', 'hours', 'total', 'status'];
		foreach($_POST as $k =>$v){
			if(in_array($k, $cr_allowed)){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `court_rentals` set {$data} ";
		}else{
			$sql = "UPDATE `court_rentals` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$crid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['crid'] = $crid;

			if(empty($id))
				$resp['msg'] = "New Court Rental successfully saved.";
			else
				$resp['msg'] = " Court Rental successfully updated.";
			if(isset($product_id)){
				$check = $this->conn->query("SELECT * FROM `sales_transaction` where court_rental_id = '{$crid}'");
				if($check->num_rows > 0){
					$result = $check->fetch_array();
					$sales_id = $result['id'];
				}else{
					$sales_id = '';
				}
				if(empty($sales_id)){
					$sql2 = "INSERT INTO `sales_transaction` set client_name = '{$client_name}', `contact` = '{$contact}', court_rental_id = '{$crid}'";
				}else{
					$sql2 = "UPDATE `sales_transaction` set client_name = '{$client_name}', `contact` = '{$contact}', court_rental_id = '{$crid}' where `id` = '{$sales_id}'";
				}
				$save2 = $this->conn->query($sql2);
				if($save2){
					$sales_id = empty($sales_id) ? $this->conn->insert_id : $sales_id;
				}else{
					$resp['status'] = 'failed';
					$resp['msg'] = $this->conn->error;
					if(empty($id))
					$this->conn->query("DELETE FROM `court_rentals` where id = '{$crid}'");
					return json_encode($resp);
				}
				$data = "";
				$total = 0;
				foreach($product_id as $k => $v){
					$pid = $v;
					$qty = $product_quantity[$k];
					$price = $product_price[$k];
					$total += $qty * $price;
					if(!empty($data)) $data .= ", ";
					$data .="('{$sales_id}','{$pid}','{$price}', '{$qty}')";
				}
				$this->conn->query("UPDATE `sales_transaction` set total = '{$total}' where id = '{$sales_id}'");
				$this->conn->query("DELETE FROM `sales_transaction_items` where sales_transaction_id = '{$sales_id}'");
				if(!empty($data)){
					$save3 = $this->conn->query("INSERT INTO `sales_transaction_items` (`sales_transaction_id`, `product_id`, `price`, `quantity`) VALUES {$data}");
					if(!$save3){
						$resp['status'] = 'failed';
						$resp['msg'] = $this->conn->error;
						if(empty($id))
						$this->conn->query("DELETE FROM `court_rentals` where id = '{$crid}'");
						return json_encode($resp);
					}
				}
			}else{
				$this->conn->query("DELETE FROM `sales_transaction` where court_rental_id = '{$crid}'");
			}
			if(isset($service_id)){
				$check = $this->conn->query("SELECT * FROM `service_transaction` where court_rental_id = '{$crid}'");
				if($check->num_rows > 0){
					$result = $check->fetch_array();
					$st_id = $result['id'];
				}else{
					$st_id = '';
				}
				if(empty($st_id)){
					$sql2 = "INSERT INTO `service_transaction` set client_name = '{$client_name}', `contact` = '{$contact}', court_rental_id = '{$crid}'";
				}else{
					$sql2 = "UPDATE `service_transaction` set client_name = '{$client_name}', `contact` = '{$contact}', court_rental_id = '{$crid}' where `id` = '{$st_id}'";
				}
				$save2 = $this->conn->query($sql2);
				if($save2){
					$st_id = empty($st_id) ? $this->conn->insert_id : $st_id;
				}else{
					$resp['status'] = 'failed';
					$resp['msg'] = $this->conn->error;
					if(empty($id))
					$this->conn->query("DELETE FROM `court_rentals` where id = '{$crid}'");
					return json_encode($resp);
				}
				$data = "";
				$total = 0;
				foreach($service_id as $k => $v){
					$sid = $v;
					$qty = $service_quantity[$k];
					$price = $service_price[$k];
					$total += $qty * $price;
					if(!empty($data)) $data .= ", ";
					$data .="('{$st_id}','{$sid}','{$price}', '{$qty}')";
				}
				$this->conn->query("UPDATE `service_transaction` set total = '{$total}' where id = '{$st_id}'");
				$this->conn->query("DELETE FROM `service_transaction_items` where service_transaction_id = '{$st_id}'");
				if(!empty($data)){
					$save3 = $this->conn->query("INSERT INTO `service_transaction_items` (`service_transaction_id`, `service_id`, `price`, `quantity`) VALUES {$data}");
					if(!$save3){
						$resp['status'] = 'failed';
						$resp['msg'] = $this->conn->error;
						if(empty($id))
						$this->conn->query("DELETE FROM `court_rentals` where id = '{$crid}'");
						return json_encode($resp);
					}
				}
			}else{
				$this->conn->query("DELETE FROM `service_transaction` where court_rental_id = '{$crid}'");
			}
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_court_rental(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `court_rentals` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Court Rental successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function update_court_rental_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `court_rentals` set `status` = '{$status}' where id = '{$id}' ");
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = 'Rental Status has been updated successfully.';
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = $this->conn->error;
		}
		if($resp['status'])
		$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function save_sales(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id')) && !is_array($_POST[$k])){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `sales_transaction` set {$data} ";
		}else{
			$sql = "UPDATE `sales_transaction` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$sid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['sid'] = $sid;

			if(empty($id))
				$resp['msg'] = "New Sales Transaction successfully saved.";
			else
				$resp['msg'] = " Sales Transaction successfully updated.";
			$data = "";
			foreach($product_id as $k => $v){
				$pid = $v;
				$qty = $product_quantity[$k];
				$price = $product_price[$k];
				$total += $qty * $price;
				if(!empty($data)) $data .= ", ";
				$data .="('{$sid}','{$pid}','{$price}', '{$qty}')";
			}
			$this->conn->query("DELETE FROM `sales_transaction_items` where sales_transaction_id = '{$sid}'");
			if(!empty($data)){
				$save2 = $this->conn->query("INSERT INTO `sales_transaction_items` (`sales_transaction_id`, `product_id`, `price`, `quantity`) VALUES {$data}");
				if(!$save2){
					$resp['status'] = 'failed';
					$resp['msg'] = $this->conn->error;
					if(empty($id))
						$this->conn->query("DELETE FROM `sales_transaction` where id = '{$sid}'");
						return json_encode($resp);
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_sales(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `sales_transaction` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Sales Transaction successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_service_transactions(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id')) && !is_array($_POST[$k])){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `service_transaction` set {$data} ";
		}else{
			$sql = "UPDATE `service_transaction` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$sid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['sid'] = $sid;

			if(empty($id))
				$resp['msg'] = "New Service Transaction successfully saved.";
			else
				$resp['msg'] = " Service Transaction successfully updated.";
			$data = "";
			foreach($service_id as $k => $v){
				$ssid = $v;
				$qty = $service_quantity[$k];
				$price = $service_price[$k];
				$total += $qty * $price;
				if(!empty($data)) $data .= ", ";
				$data .="('{$sid}','{$ssid}','{$price}', '{$qty}')";
			}
			$this->conn->query("DELETE FROM `service_transaction_items` where service_transaction_id = '{$sid}'");
			if(!empty($data)){
				$save2 = $this->conn->query("INSERT INTO `service_transaction_items` (`service_transaction_id`, `service_id`, `price`, `quantity`) VALUES {$data}");
				if(!$save2){
					$resp['status'] = 'failed';
					$resp['msg'] = $this->conn->error;
					if(empty($id))
						$this->conn->query("DELETE FROM `service_transaction` where id = '{$sid}'");
						return json_encode($resp);
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_service_transactions(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `service_transaction` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Service Transaction successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function update_service_transcation_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `service_transaction` set `status` = '{$status}' where id = '{$id}' ");
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = 'Service Transaction Status has been updated successfully.';
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = $this->conn->error;
		}
		if($resp['status'])
		$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'save_court':
		echo $Master->save_court();
	break;
	case 'delete_court':
		echo $Master->delete_court();
	break;
	case 'save_product':
		echo $Master->save_product();
	break;
	case 'delete_product':
		echo $Master->delete_product();
	break;
	case 'save_service':
		echo $Master->save_service();
	break;
	case 'delete_service':
		echo $Master->delete_service();
	break;
	case 'save_court_rental':
		echo $Master->save_court_rental();
	break;
	case 'delete_court_rental':
		echo $Master->delete_court_rental();
	break;
	case 'update_court_rental_status':
		echo $Master->update_court_rental_status();
	break;
	case 'save_sales':
		echo $Master->save_sales();
	break;
	case 'delete_sales':
		echo $Master->delete_sales();
	break;
	case 'save_service_transactions':
		echo $Master->save_service_transactions();
	break;
	case 'delete_service_transactions':
		echo $Master->delete_service_transactions();
	break;
	case 'update_service_transcation_status':
		echo $Master->update_service_transcation_status();
	break;
	default:
		// echo $sysset->index();
		break;
}