<?php
class CategoryModel extends model {
	
	private $con;
	
	public function __construct(){
		$db = new database();
		$this->con = $db->connection();
	}
	public function display(){
		$stmt = $this->con->prepare("SELECT `id`, `cate_name`, `Task` FROM `category_tbl` ");
		$stmt->execute();
    	$stmt->bind_result($id, $cate_name, $Task);
		$result = array();
		while($stmt->fetch()){ 
			$result[] = array('id' => $id,'cate_name' => $cate_name,'Task' => $Task);
		}
		$stmt->close();
		$this->con->close();
		return $result;
		
	}
	public function save($param = array()){

		$cate_name = $param['cate_name'];
		$Task = $param['Task'];

		$sql = "SELECT * FROM category_tbl WHERE cate_name=?";
		$stmt =  $this->con->prepare($sql); 
		$stmt->bind_param("s", $cate_name);
		$stmt->execute();
		$stmt->store_result();
        $result = array();
		$nrows1 = $stmt->num_rows;
		if ($nrows1>0) {
			$result[] = array('result' => "1");
		}else{
		
			$stmt = $this->con->prepare("INSERT INTO `category_tbl`(`cate_name`, `Task`) VALUES (?,?)");
			$stmt->bind_param("ss",$cate_name,$Task);
			if ($stmt->execute()== TRUE) {
				$result[] = array('result' => "ok");
			}else{
				$result[] = array('result' => "error");
			}

		}
		
		$stmt->close();
		$this->con->close();
		return $result;
		
	} 
	public function update($param = array()){
		$cate_name = $param['cate_name'];
		$Task = $param['Task'];
		$id = $param['id'];
		
		$stmt = $this->con->prepare("UPDATE `category_tbl` SET `cate_name`=?, `Task`=? WHERE id=?");
        $stmt->bind_param("sss",$cate_name,$Task,$id);
        $result = array();

		if ($stmt->execute()==TRUE) {
			 $result[] = array('result' => "ok");
		}else{
			 $result[] = array('result' => "error");
		}
		
		$stmt->close();
		$this->con->close();
		return $result;
	} 
	public function delete($param = array()){
		$id = $param['id'];
		
		$stmt = $this->con->prepare("DELETE FROM  `category_tbl` WHERE id=?");
        $stmt->bind_param("i",$id);
		$result = array();
            if($stmt->execute()==TRUE){
                $result[] = array('result' => "ok");
            }else{
                 $result[] = array('result' => "error");
            }
		$stmt->close();
		$this->con->close();
		return $result;
		
	} 
}
?>