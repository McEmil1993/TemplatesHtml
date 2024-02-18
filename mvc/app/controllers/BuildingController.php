<?php 

class BuildingController extends controller{


	public function getAllBuilding(){

		$auth_obj = new BuildingModel();
		$result = $auth_obj->getAllBuilding();
		echo json_encode($result);
	}

	public function saveBuilding(){
		$building_name = isset($_POST['building_name']) ? $_POST['building_name'] : "";
		$description = isset($_POST['description']) ? $_POST['description'] : "";
		$auth_obj = new BuildingModel();
		$result = $auth_obj->saveBuilding(array('building_name' => $building_name,'description' => $description));
		
		echo json_encode($result);
	}

	public function deleteBuilding(){

		$id = isset($_POST['id']) ? $_POST['id'] : "";
		$auth_obj = new BuildingModel();
		$result = $auth_obj->deleteBuilding(array('id' => $id));
		echo json_encode($result);
	}

	public function updateBuilding(){
		$building_name = isset($_POST['building_name']) ? $_POST['building_name'] : "";
		$description = isset($_POST['description']) ? $_POST['description'] : "";
		$id = isset($_POST['id']) ? $_POST['id'] : "";
		$auth_obj = new BuildingModel();
		$result = $auth_obj->updateBuilding(array('building_name' => $building_name,'description' => $description,'id' => $id));
		echo json_encode($result);
	}

	public function bname(){

		$building_name = isset($_POST['building_name']) ? $_POST['building_name'] : "";
	
		$auth_obj = new BuildingModel();
		$result = $auth_obj->bname(array('building_name' => $building_name));
		echo json_encode($result);
	}

} 

?>