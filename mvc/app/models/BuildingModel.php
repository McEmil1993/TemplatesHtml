<?php
class BuildingModel extends model {
	
	private $con;

	public function __construct(){
		$db = new database();
		$this->con = $db->connection();
	}
	public function getAllBuilding(){
		$stmt = $this->con->prepare("SELECT id, building_name, description,count_room FROM building_tbl");
		$stmt->execute();
    	$stmt->bind_result($id, $building_name, $description, $count_room);
		$build_info = array();
		while($stmt->fetch()){ 
			$build_info[] = array('id' => $id,
								'building_name' => $building_name,
								'description' => $description,
								'count_room' => $count_room);
		}
		$stmt->close();
		$this->con->close();
		return $build_info;
		
	}
	public function saveBuilding($param = array()){

			
		$building_name = $param['building_name'];
		$description = $param['description'];

		$sql = "SELECT * FROM building_tbl WHERE building_name=?";
		$stmt =  $this->con->prepare($sql); 
		$stmt->bind_param("s", $building_name);
		$stmt->execute();
		$stmt->store_result();
        $result = array();
		$nrows1 = $stmt->num_rows;
		if ($nrows1>0) {
			$result[] = array('result' => "1");
		}else{
		
			$stmt = $this->con->prepare("INSERT INTO `building_tbl`(`building_name`, `description`) VALUES (?,?)");
			$stmt->bind_param("ss",$building_name,$description);
			if ($stmt->execute()== TRUE) {
				$result[] = array('result' => "Insert building successfully!");
			}else{
				$result[] = array('result' => "error");
			}

		}
		
		$stmt->close();
		$this->con->close();
		return $result;
		
	} 
	public function updateBuilding($param = array()){
		$building_name = $param['building_name'];
		$description = $param['description'];
		$id = $param['id'];
		
		$stmt = $this->con->prepare("UPDATE `building_tbl` SET `building_name`=?, `description`=? WHERE id=?");
        $stmt->bind_param("sss",$building_name,$description,$id);
        $result = array();

		if ($stmt->execute()==TRUE) {
			 $result[] = array('result' => "Update building successfully!");
		}else{
			 $result[] = array('result' => "error");
		}
		
		$stmt->close();
		$this->con->close();
		return $result;
	} 
	public function deleteBuilding($param = array()){
		$id = $param['id'];
		
		$stmt = $this->con->prepare("DELETE FROM  `building_tbl` WHERE id=?");
        $stmt->bind_param("i",$id);
		$result = array();
            if($stmt->execute()==TRUE){
                $result[] = array('result' => "Delete building successfully!");
            }else{
                 $result[] = array('result' => "error");
            }

        $stmt = $this->con->prepare("DELETE FROM  `room_tbl` WHERE building_id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();

            
		$stmt->close();
		$this->con->close();
		return $result;
		
	} 
	public function bname($param = array()){
		$building_name = $param['building_name'];
		
		$stmt = $this->con->prepare("SELECT id FROM building_tbl WHERE building_name =?");
		$stmt->execute();
        $stmt->bind_param("s",$building_name);
		$stmt->bind_result($id);
		$build_info = array();
		while($stmt->fetch()){ 
			$build_info[] = array('id' => $id);
		}
		$stmt->close();
		$this->con->close();
		return $build_info;
	} 


}
?>