<?php 

class EquipmentController extends controller{

 
	public function saveEquip(){

		$cate_name = isset($_POST['cate_name']) ? $_POST['cate_name'] : "";
		$name = isset($_POST['name']) ? $_POST['name'] : "";
		$description = isset($_POST['description']) ? $_POST['description'] : "";
		$ImageUrl = isset($_POST['ImageUrl']) ? $_POST['ImageUrl'] : "";
		$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : "";

		$auth_obj = new EquipmentModel();
		$result = $auth_obj->saveEquip(array('cate_name' => $cate_name,'name' => $name,'description' => $description,'ImageUrl' => $ImageUrl,'quantity' => $quantity));
		
		echo json_encode($result);
	}

	public function updateEquip(){

		$cate_name = isset($_POST['cate_name']) ? $_POST['cate_name'] : "";
		$name = isset($_POST['name']) ? $_POST['name'] : "";
		$description = isset($_POST['description']) ? $_POST['description'] : "";
		$ImageUrl = isset($_POST['ImageUrl']) ? $_POST['ImageUrl'] : "";
		$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : "";
		$id = isset($_POST['id']) ? $_POST['id'] : "";

		$auth_obj = new EquipmentModel();
		$result = $auth_obj->updateEquip(array('cate_name' => $cate_name,'name' => $name,'description' => $description,'ImageUrl' => $ImageUrl,'quantity' => $quantity,'id' => $id));
		
		echo json_encode($result);
	}

	public function selectName(){
		$cate_name = isset($_POST['cate_name']) ? $_POST['cate_name'] : "";
		$auth_obj = new EquipmentModel();
		$result = $auth_obj->selectName(array('cate_name' => $cate_name));
		
		echo json_encode($result);
	}

	public function getAllEquip(){

		$id = isset($_POST['id']) ? $_POST['id'] : "";
		$cate_name = isset($_POST['cate_name']) ? $_POST['cate_name'] : "";
		$name = isset($_POST['name']) ? $_POST['name'] : "";
		$description = isset($_POST['description']) ? $_POST['description'] : "";
		$ImageUrl = isset($_POST['ImageUrl']) ? $_POST['ImageUrl'] : "";
		$status = isset($_POST['status']) ? $_POST['status'] : "";
		$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : "";

		$auth_obj = new EquipmentModel();
		$result = $auth_obj->getAllEquip(array('id' => $id,'cate_name' => $cate_name,'name' => $name,'description' => $description,'ImageUrl' => $ImageUrl,'quantity' => $quantity,'status' => $status));

		echo json_encode($result);

    }

    public function getAllChkEquip(){

		

		$auth_obj = new EquipmentModel();
		$result = $auth_obj->getAllChkEquip();

		echo json_encode($result);

    }

    public function delete(){
		$id = isset($_POST['id']) ? $_POST['id'] : "";
		$auth_obj = new EquipmentModel();
		$result = $auth_obj->delete(array('id' => $id));
		
		echo json_encode($result);
	}


} 

?>