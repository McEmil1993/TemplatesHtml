<?php 

class CategoryController extends controller{


	public function display(){
		
		$auth_obj = new CategoryModel();
		$result = $auth_obj->display();
		echo json_encode($result);
	}

	public function save(){
		$cate_name = isset($_POST['cate_name']) ? $_POST['cate_name'] : "";
		$Task = isset($_POST['Task']) ? $_POST['Task'] : "";
		$auth_obj = new CategoryModel();
		$result = $auth_obj->save(array('cate_name' => $cate_name,'Task' => $Task));
		
		echo json_encode($result);
	}

	public function delete(){

		$id = isset($_POST['id']) ? $_POST['id'] : "";
		$auth_obj = new CategoryModel();
		$result = $auth_obj->delete(array('id' => $id));
		echo json_encode($result);
	}

	public function update(){
		$cate_name = isset($_POST['cate_name']) ? $_POST['cate_name'] : "";
		$Task = isset($_POST['Task']) ? $_POST['Task'] : "";
		$id = isset($_POST['id']) ? $_POST['id'] : "";
		$auth_obj = new CategoryModel();
		$result = $auth_obj->update(array('cate_name' => $cate_name,'Task' => $Task,'id' => $id));
		echo json_encode($result);
	}

} 

?>