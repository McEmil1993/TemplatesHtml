<?php

class EquipmentModel extends model {
    
    private $con;

    public function __construct(){
        $db = new database();
        $this->con = $db->connection();
    }
    public function saveEquip($param = array()){

            $name = $param['name'];
            $description = $param['description'];
            $ImageUrl = $param['ImageUrl'];
            $cate_name = $param['cate_name'];
            $quantity = $param['quantity'];
            $status = 0;
            $ids = "";

            $stmt = $this->con->prepare("SELECT id FROM category_tbl WHERE cate_name=?");
            $stmt->bind_param("s",$cate_name);
            $stmt->execute();
            $result = $stmt->get_result();
            if($row = $result->fetch_assoc()) {
                $ids = $row['id'];
            }

            $letters = '';
            $numbers = '';
            foreach (range('A', 'Z') as $char) {
            $letters .= $char;
            }
            for($i = 0; $i < 9; $i++){
            $numbers .= $i;
            }
            $imagename = substr(str_shuffle($letters), 0, 3).substr(str_shuffle($numbers), 0, 9);
            

            $ImagePath = "";

            if ($ImageUrl == "null") {
                 $ImagePath = "app/images/no_image.jpg";
            }else{
                $ImagePath = "app/images/IMG_$imagename.png";
                file_put_contents($ImagePath,base64_decode($ImageUrl));
            }

            $ServerURL="http://localhost/mvc/$ImagePath";

            $stmt = $this->con->prepare("INSERT INTO equipment_tbl(category_id, name, description, ImageUrl,quantity,status) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("ssssss", $ids,$name,$description,$ServerURL,$quantity,$status);
            $result = array();
            if($stmt->execute()==TRUE){
                $result[] = array('result' => "Insert Equipment successfully!");
              
            }else{
                 $result[] = array('result' => "error");
            }
            $stmt->close();
            $this->con->close();
            return $result;
    } 
    public function updateEquip($param = array()){

            $name = $param['name'];
            $description = $param['description'];
            $ImageUrl = $param['ImageUrl'];
            $cate_name = $param['cate_name'];
            $quantity = $param['quantity'];
            $id = $param['id'];
            $status = 0;

            $ids = "";

            $stmt = $this->con->prepare("SELECT id FROM category_tbl WHERE cate_name=?");
            $stmt->bind_param("s",$cate_name);
            $stmt->execute();
            $result = $stmt->get_result();
            if($row = $result->fetch_assoc()) {
                $ids = $row['id'];
            }

            $letters = '';
            $numbers = '';
            foreach (range('A', 'Z') as $char) {
            $letters .= $char;
            }
            for($i = 0; $i < 6; $i++){
            $numbers .= $i;
            }
            $imagename = substr(str_shuffle($letters), 0, 3).substr(str_shuffle($numbers), 0, 3);
   
           
            $ServerURL= "";
            $ImagePath = "";

            if ($ImageUrl == "null") {
                $mu = "";
                $stmt = $this->con->prepare("SELECT ImageUrl FROM equipment_tbl WHERE id=?");
                $stmt->bind_param("s",$id);
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()) {
                    $mu = $row['ImageUrl'];
                }
                $ServerURL = $mu;

            }else{
                $ImagePath = "app/images/IMG_$imagename.png";
                $ServerURL="http://localhost/mvc/$ImagePath";
                file_put_contents($ImagePath,base64_decode($ImageUrl));  
            }

            $stmt = $this->con->prepare("UPDATE equipment_tbl SET category_id=?, name=?, description=?, ImageUrl=?,quantity=?,status=? WHERE id =?");
            $stmt->bind_param("sssssss", $ids,$name,$description,$ServerURL,$quantity,$status,$id);
            $result = array();
            if($stmt->execute()==TRUE){
                $result[] = array('result' => "Update Equipment successfully!");
               
            }else{
                 $result[] = array('result' => "error");
            }
            $stmt->close();
            $this->con->close();
            return $result;
    } 


    public function getAllEquip(){
        $stmt = $this->con->prepare("SELECT equipment_tbl.id, category_tbl.cate_name , equipment_tbl.name, equipment_tbl.description, equipment_tbl.ImageUrl, equipment_tbl.quantity, equipment_tbl.status FROM equipment_tbl INNER JOIN category_tbl on category_tbl.id = equipment_tbl.category_id");
        $stmt->execute();
        $stmt->bind_result($id, $cate_name, $name,$description,$ImageUrl,$quantity,$status);
        $build_info = array();
        while($stmt->fetch()){ 
            $build_info[] = array('id' => $id,'cate_name' => $cate_name,'name' => $name,'description' => $description,'ImageUrl' => $ImageUrl,'quantity' => $quantity,'status' => $status);
        }
        $stmt->close();
        $this->con->close();
        return $build_info;
        
    }
    public function selectName($param = array()){

        $cate_name = $param['cate_name'];
        $stmt = $this->con->prepare("SELECT id FROM category_tbl WHERE cate_name=?");
        $stmt->bind_param("s", $cate_name);
        $stmt->execute();
        $stmt->bind_result($id);
        $build_info = array();
        while($stmt->fetch()){ 
            $build_info[] = array('id' => $id);
        }
        $stmt->close();
        $this->con->close();
        return $build_info;
    }

     public function delete($param = array()){

        $id = $param['id'];
        $stmt = $this->con->prepare("DELETE FROM equipment_tbl WHERE id=?");
        $stmt->bind_param("s", $id);
        $result = array();
        if($stmt->execute()==TRUE){ 
            $result[] = array('result' => "Delete equipment successfully!");
        }
         $stmt->close();
        $this->con->close();
        return $result;
    }

    public function getAllChkEquip(){
        $stmt = $this->con->prepare("SELECT building_tbl.id AS b_id,room_tbl.id AS r_id,equipment_tbl.id AS e_id ,checking_tbl.id AS c_id , building_tbl.building_name ,room_tbl.room_name,equipment_tbl.name,equipment_tbl.ImageUrl FROM `checking_tbl` INNER JOIN building_tbl on building_tbl.id = checking_tbl.building_id INNER JOIN room_tbl ON room_tbl.id = checking_tbl.room_id INNER JOIN equipment_tbl ON equipment_tbl.id = checking_tbl.equipment_id");
        $stmt->execute();
        $stmt->bind_result($b_id, $r_id, $e_id,$c_id,$building_name,$room_name,$name,$ImageUrl);
        $build_info = array();
        while($stmt->fetch()){ 
            $build_info[] = array('b_id' => $b_id,'r_id' => $r_id,'e_id' => $e_id,'c_id' => $c_id,'building_name' => $building_name,'room_name' => $room_name,'name' => $name,'ImageUrl'=>$ImageUrl);
        }
        $stmt->close();
        $this->con->close();
        return $build_info;
        
    }

}

?>