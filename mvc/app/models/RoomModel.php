<?php
class RoomModel extends model {
	
	private $con;
	
	public function __construct(){
		$db = new database();
		$this->con = $db->connection();
	}

	 
	public function display($param = array()){

		$building_id = $param['building_id'];
		$stmt = $this->con->prepare("SELECT room_tbl.id, room_tbl.room_name, building_tbl.building_name,room_tbl.description FROM room_tbl INNER JOIN building_tbl on building_tbl.id = room_tbl.building_id WHERE room_tbl.building_id = ?");
		$stmt->bind_param("s",$building_id);
		$stmt->execute();
    	$stmt->bind_result($id, $room_name,$building_name, $description);
		 $result = array();
		while($stmt->fetch()){ 
			$result[] = array('id' => $id,
								'room_name' => $room_name,
								'description' => $description,
								'building_name' => $building_name);
		}
		$stmt->close();
		$this->con->close();
		return $result;
		
	}
	public function displayBname(){

		$stmt = $this->con->prepare("SELECT building_tbl.id as B,room_tbl.id as R, room_tbl.room_name, building_tbl.building_name, building_tbl.count_room ,room_tbl.description FROM room_tbl INNER JOIN building_tbl on building_tbl.id = room_tbl.building_id GROUP BY building_tbl.building_name");
		$stmt->execute();
    	$stmt->bind_result($rid, $bid,$room_name,$building_name, $description,$count_room);
		$result = array();
		while($stmt->fetch()){ 
			$result[] = array('R' => $rid,
							  'B' => $bid,
								'room_name' => $room_name,
								'description' => $description,
								'building_name' => $building_name,
								'count_room' => $count_room);
		}
		$stmt->close();
		$this->con->close();
		return $result;
		
	}

	public function displayHome(){

		$stmt = $this->con->prepare("SELECT building_tbl.id as B,room_tbl.id as R, room_tbl.room_name, building_tbl.building_name, building_tbl.count_room ,room_tbl.description FROM room_tbl INNER JOIN building_tbl on building_tbl.id = room_tbl.building_id ");
		$stmt->execute();
    	$stmt->bind_result($rid, $bid,$room_name,$building_name, $description,$count_room);
		$result = array();
		while($stmt->fetch()){ 
			$result[] = array('R' => $rid,
							  'B' => $bid,
								'room_name' => $room_name,
								'description' => $description,
								'building_name' => $building_name,
								'count_room' => $count_room);
		}
		$stmt->close();
		$this->con->close();
		return $result;
		
	}
	public function save($param = array()){

		$room_name = $param['room_name'];
    	$description = $param['description'];
    	$building_id = $param['building_id'];
    
		$sql = "SELECT * FROM room_tbl WHERE room_name=? AND building_id=?";
		$stmt =  $this->con->prepare($sql); 
		$stmt->bind_param("ss", $room_name,$building_id);
		$stmt->execute();
		$stmt->store_result();
        $result = array();
		$nrows1 = $stmt->num_rows;
		if ($nrows1>0) {
			$result[] = array('result' => "1");
		}else{
			$stmt = $this->con->prepare("INSERT INTO `room_tbl`(building_id,`room_name`, `description`) VALUES (?,?,?)");
       	 	$stmt->bind_param("sss", $building_id,$room_name,$description);

			if ($stmt->execute()== TRUE) {
				$result[] = array('result' => "Insert room successfully!");

				 $stmt = $this->con->prepare("UPDATE building_tbl SET count_room=count_room + 1 WHERE id =?");
       			 $stmt->bind_param("i",$building_id);
        		 $stmt->execute();

			}else{
				$result[] = array('result' => "error");
			}
		}
		
		$stmt->close();
		$this->con->close();
		return $result;
		
	} 
	public function update($param = array()){

		$room_name = $param['room_name'];
    	$description = $param['description'];
		$id = $param['id'];
		
		$stmt = $this->con->prepare("UPDATE `room_tbl` SET `room_name`=?, `description`=? WHERE id=?");
        $stmt->bind_param("sss",$room_name,$description,$id);
        $result = array();

		if ($stmt->execute()==TRUE) {
			 $result[] = array('result' => "Update room successfully!");
		}else{
			 $result[] = array('result' => "error");
		}
		
		$stmt->close();
		$this->con->close();
		return $result;
	} 
	public function delete($param = array()){
		$id = $param['id'];
		$_id = $param['b_id'];
		
		$stmt = $this->con->prepare("DELETE FROM  `room_tbl` WHERE id=?");
        $stmt->bind_param("i",$id);
		$result = array();
            if($stmt->execute()==TRUE){
            	$stmt = $this->con->prepare("UPDATE building_tbl SET count_room=count_room - 1 WHERE id = ? ");
       			 $stmt->bind_param("i",$_id);
        		 $stmt->execute();

                $result[] = array('result' => "Delete room successfully!");

            }else{
                 $result[] = array('result' => "error");
            }
		$stmt->close();
		$this->con->close();
		return $result;
		
	} 

}
?>