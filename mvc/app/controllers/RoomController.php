<?php 

class RoomController extends controller{


	public function display(){
		$building_id = isset($_POST['building_id']) ? $_POST['building_id'] : "";
		$auth_obj = new RoomModel();
		$result = $auth_obj->display(array('building_id' => $building_id));
		echo json_encode($result);
	}

	public function displayBname(){
		$auth_obj = new RoomModel();
		$result = $auth_obj->displayBname();
		echo json_encode($result);
	}

	public function displayHome(){
		$auth_obj = new RoomModel();
		$result = $auth_obj->displayHome();
		echo json_encode($result);
	}

	public function save(){
	
    	$room_name = isset($_POST['room_name']) ? $_POST['room_name'] : "";
		$building_id = isset($_POST['building_id']) ? $_POST['building_id'] : "";
		$description = isset($_POST['description']) ? $_POST['description'] : "";
		$auth_obj = new RoomModel();
		$result = $auth_obj->save(array('building_id' => $building_id,'room_name' => $room_name,'description' => $description));
		echo json_encode($result);
	}

	public function delete(){

		$id = isset($_POST['id']) ? $_POST['id'] : "";
		$b_id = isset($_POST['b_id']) ? $_POST['b_id'] : "";
		$auth_obj = new RoomModel();
		$result = $auth_obj->delete(array('id' => $id,'b_id'=>$b_id));
		echo json_encode($result);
	}

	public function update(){

		$room_name = isset($_POST['room_name']) ? $_POST['room_name'] : "";
		$description = isset($_POST['description']) ? $_POST['description'] : "";
		$id = isset($_POST['id']) ? $_POST['id'] : "";
		$auth_obj = new RoomModel();
		$result = $auth_obj->update(array('room_name' => $room_name,'description' => $description,'id' => $id));
		echo json_encode($result);
	}

} 

?>