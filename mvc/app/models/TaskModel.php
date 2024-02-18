<?php
class TaskModel extends model {

    public function __construct(){
        $db = new database();
        $this->con = $db->connection();
    }

    public function display($param = array()){
         $status = $param['Task'];
         $bname =$param['building_name'];
         $rname = $param['room_name'];

        $stmt = $this->con->prepare("SELECT checking_tbl.id, building_tbl.building_name,room_tbl.room_name,category_tbl.cate_name,equipment_tbl.name,equipment_tbl.description AS ED, checking_tbl.date_time ,equipment_tbl.ImageUrl AS IM ,checking_tbl.status,checking_tbl.name_f FROM `checking_tbl`INNER JOIN building_tbl ON building_tbl.id = checking_tbl.building_id INNER JOIN room_tbl ON room_tbl.id =checking_tbl.room_id INNER JOIN equipment_tbl ON equipment_tbl.id = checking_tbl.equipment_id LEFT JOIN category_tbl ON category_tbl.id = equipment_tbl.category_id WHERE category_tbl.Task = ? AND building_tbl.building_name = ? AND room_tbl.room_name =?");
        $stmt->bind_param("sss",$status,$bname,$rname);
        $stmt->execute();
        $stmt->bind_result($id, $building_name,$room_name,$cate_name,$name, $ED,$date_time,$ImageUrl,$status,$name_f);
         $result = array();
        while($stmt->fetch()){ 
            $result[] = array('id' => $id,
                                'room_name' => $room_name,
                                'cate_name' => $cate_name,
                                'name' => $name,
                                'date_time' => $date_time,
                                'ED' => $ED,
                                'building_name' => $building_name,
                                'IM' => $ImageUrl,
                                 'status' => $status,
                                 'name_f'=>$name_f);
        }
        $stmt->close();
        $this->con->close();
        return $result;
    }

    public function checkStatus($param = array()){
            $status = $param['status'];
            $id = $param['id'];
            $i_name = $param['i_name'];
            $dt = "Now()";
            $result = array();
            $stmt = $this->con->prepare("UPDATE `checking_tbl` SET status = ? ,name_f=?,date_time = Now()  WHERE id=?");
            $stmt->bind_param("sss",$status,$i_name,$id);

            if ($stmt->execute()== TRUE) {

                $stmt1 = $this->con->prepare("SELECT * FROM checking_tbl WHERE id=?");
                $stmt1->bind_param("s",$id);
                $stmt1->execute();
                $result1 = $stmt1->get_result();
                $row= $result1->fetch_assoc();
 
                $result[] = array('result' => $row['date_time']);
            }else{
                $result[] = array('result' => "error");
            }
             $stmt->close();
        $this->con->close();
        return $result;
           
    }
     public function delete($param = array()){
            $id = $param['id'];

            $stmt1 =  $this->con->prepare("SELECT * FROM checking_tbl WHERE id=?"); 
            $stmt1->bind_param("s",$id);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $row1= $result1->fetch_assoc();
            $b_id = $row1['building_id'];
            $r_id = $row1['room_id'];
            $e_id = $row1['equipment_id'];

            $result = array();
            $stmt = $this->con->prepare("DELETE FROM `checking_tbl` WHERE id =?");
            $stmt->bind_param("s",$id);

            if ($stmt->execute()== TRUE) {
                $result[] = array('result' => "Successfully remove");
            }else{
                $result[] = array('result' => "error");
            }

            $stmt2 =  $this->con->prepare("SELECT * FROM checking_tbl WHERE building_id=? AND room_id=? AND equipment_id=?"); 
            $stmt2->bind_param("sss",$b_id,$r_id,$e_id);
            $stmt2->execute();
            $stmt2->store_result();
            $nrows1 = $stmt2->num_rows;
            if ($nrows1>0) {
                
            }else{
                $stmt = $this->con->prepare("UPDATE `equipment_tbl` SET quantity = quantity+1 WHERE id = ?");
                $stmt->bind_param("s",$e_id);
                $stmt->execute();
            }

             $stmt->close();
        $this->con->close();
        return $result;
           
    }

    public function getAllEquip($param = array()){
        $task = $param['Task'];
        $stmt = $this->con->prepare("SELECT equipment_tbl.id, category_tbl.cate_name , equipment_tbl.name, equipment_tbl.description, equipment_tbl.ImageUrl, equipment_tbl.quantity, equipment_tbl.status FROM equipment_tbl INNER JOIN category_tbl on category_tbl.id = equipment_tbl.category_id WHERE category_tbl.Task = '$task'");
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
    public function save($param = array()){

        $b_id = $param['b_id'];
        $r_id = $param['r_id'];
        $ename = $param['ename'];
        $Task = $param['Task'];
        $result = array();

        $sql2 = "SELECT * FROM building_tbl WHERE building_name='$b_id'";
        $stmt2 =  $this->con->prepare($sql2); 
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $row2 = $result2->fetch_assoc();

        $bid = $row2['id'];

        $sql3 = "SELECT * FROM room_tbl WHERE building_id='$bid' AND room_name = '$r_id'";
        $stmt3 =  $this->con->prepare($sql3); 
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        $row3 = $result3->fetch_assoc();

        $rid = $row3['id'];

        $sql = "SELECT * FROM equipment_tbl WHERE name='$ename'";
        $stmt =  $this->con->prepare($sql); 
        $stmt->execute();
        $result1 = $stmt->get_result();

        if ($row = $result1->fetch_assoc()) {
            $id = $row['id'];

            $sql = "SELECT * FROM checking_tbl WHERE room_id = '$rid' AND building_id = '$bid' AND equipment_id = '$id' AND date_time = '0000-00-00 00:00:00' AND status = 0";
            $stmt =  $this->con->prepare($sql); 
            $stmt->execute();
            $stmt->store_result();
            $nrows1 = $stmt->num_rows;
            if ($nrows1>0) {
                $result[] = array('result' => "1");
            }else{
        
                $stmt = $this->con->prepare("INSERT INTO `checking_tbl`( `building_id`, `room_id`, `equipment_id`) VALUES (?,?,?)");
                $stmt->bind_param("sss",$bid,$rid,$id);
                if ($stmt->execute()== TRUE) {
                    $result[] = array('result' => "Insert Equiment successfully!");
                        
                        // $stmt2 =  $this->con->prepare("SELECT * FROM checking_tbl WHERE building_id=? AND room_id=? AND equipment_id=?"); 
                        // $stmt2->bind_param("sss",$bid,$rid,$id);
                        // $stmt2->execute();
                        // $stmt2->store_result();
                        // $nrows2 = $stmt2->num_rows;
                        // if ($nrows2 == 1) {
                            $stmt1 = $this->con->prepare("UPDATE `equipment_tbl` SET quantity = quantity - 1 WHERE id = ?");
                            $stmt1->bind_param("s",$id);
                            $stmt1->execute();
                        // }else{

                        // }

                }else{
                    $result[] = array('result' => "error");
                }

            }
        }
        
        $stmt->close();
        $this->con->close();
        return $result;
        
    } 
    public function task_tbl($param=array())
    {
        # code...
        $id = $param['task'];
        $date_sched = $param['date_sched'];
        
        $stmt = $this->con->prepare("UPDATE `task_tbl` SET `date_sched`=? WHERE id = ?");
        $stmt->bind_param("si",$date_sched,$id);
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
    public function displaySched(){

        $stmt = $this->con->prepare("SELECT `id`, `name`, `date_sched` FROM `task_tbl`");
        $stmt->execute();
        $stmt->bind_result($id, $name,$date_sched);
         $result = array();
        while($stmt->fetch()){ 
            $result[] = array('id' => $id,
                                'name' => $name,
                                'date_sched' => $date_sched);
        }
        $stmt->close();
        $this->con->close();
        return $result;
    }

    public function mSched(){
        $m = "";
       
        $stmt = $this->con->prepare("SELECT `date_sched` FROM `task_tbl` WHERE id = 1");
        $stmt->execute();
         $stmt->bind_result($date1);
        if($stmt->fetch()){ 

            $dt = strtotime($date1);
            $dta = date("Y-m-d", $dt);
            $m = date("Y-m-d", strtotime("+1 month", $dt));
            if ($dta <= date("Y-m-d")) {
                $lg = new TaskModel();
                $lg->updatTask(1,1);
                return 1;
            }else{
                return 0;
            }
        }
    }
     public function qSched(){
        $m = "";

        $stmt = $this->con->prepare("SELECT `date_sched` FROM `task_tbl` WHERE id = 2");
        $stmt->execute();
        $stmt->bind_result($date1);
        if($stmt->fetch()){ 
            $dt = strtotime($date1);
            $dta = date("Y-m-d", $dt);
            $m = date("Y-m-d", strtotime("+2 month", $dt));
            if ($dta <= date("Y-m-d")) {
                $lg = new TaskModel();
                $lg->updatTask(1,2);
                return 1;
            }else{
                return 0;
            }
        }
        
    }
    public function updatTask($sta,$value)
    {
        $stmt2 = $this->con->prepare("UPDATE task_tbl SET sta = '$sta' WHERE id = '$value' ");
        $stmt2->execute();    
    }

     public function aSched(){
        $m = "";

        $stmt = $this->con->prepare("SELECT `date_sched` FROM `task_tbl` WHERE id = 3");
        $stmt->execute();
         $stmt->bind_result($date1);
        if($stmt->fetch()){ 
            $dt = strtotime($date1);
            $dta = date("Y-m-d", $dt);
            $m = date("Y-m-d", strtotime("+12 month", $dt));
            if ($dta <= date("Y-m-d")) {
                $lg = new TaskModel();
                $lg->updatTask(1,3);
                return 1;
            }else{
                return 0;
            }
        }
    }

    public function getAllTask(){

        $stmt = $this->con->prepare("SELECT  `id`, `name`, `date_sched` FROM `task_tbl`");
        $stmt->execute();
         $stmt->bind_result($id,$name,$date_sched);
         $data = array();
        while($stmt->fetch()){ 
           $data[] = array('id'=>$id,'name'=>$name,'date_sched'=>$date_sched);
        }
        return $data;
    }

     public function clickMTask($status){

        if ($status == 1) {
            $stmt1 = $this->con->prepare("SELECT checking_tbl.building_id, checking_tbl.room_id, checking_tbl.equipment_id FROM `checking_tbl` INNER JOIN equipment_tbl ON equipment_tbl.id = checking_tbl.equipment_id INNER JOIN category_tbl ON category_tbl.id = equipment_tbl.category_id WHERE category_tbl.Task = 1");
           $stmt1->execute();
           $data = array();
           $stmt1->bind_result($building_id,$room_id,$equipment_id);
           $stmt1->store_result();
           if( $stmt1->num_rows > 0){
              while($stmt1->fetch()){ 
                 $data[] = array('building_id'=>$building_id,'room_id'=>$room_id,'equipment_id'=>$equipment_id);

              }
           }
           $stmt = $this->con->prepare("INSERT INTO `checking_tbl`( `building_id`, `room_id`, `equipment_id`) VALUES (?,?,?)");
           foreach($data as $row)
            {
                $stmt->bind_param('sss', $row['building_id'], $row['room_id'], $row['equipment_id']);
                $stmt->execute();
            }
            $date1 = date('Y-m-d');
            $dt = strtotime($date1);
            $dta = date("Y-m-d", $dt);
            $m = date("Y-m-d", strtotime("+1 month", $dt));

            $stmt2 = $this->con->prepare("UPDATE task_tbl SET date_sched='$m', sta = 0 WHERE id = 1 ");
            $stmt2->execute();

            return 1;  
        }

    }

    public function clickQTask($status){

        if ($status == 1) {
            $stmt1 = $this->con->prepare("SELECT checking_tbl.building_id, checking_tbl.room_id, checking_tbl.equipment_id FROM `checking_tbl` INNER JOIN equipment_tbl ON equipment_tbl.id = checking_tbl.equipment_id INNER JOIN category_tbl ON category_tbl.id = equipment_tbl.category_id WHERE category_tbl.Task = 2");
           $stmt1->execute();
           $data = array();
           $stmt1->bind_result($building_id,$room_id,$equipment_id);
           $stmt1->store_result();
           if( $stmt1->num_rows > 0){
              while($stmt1->fetch()){ 
                 $data[] = array('building_id'=>$building_id,'room_id'=>$room_id,'equipment_id'=>$equipment_id);

              }
           }
           $stmt = $this->con->prepare("INSERT INTO `checking_tbl`( `building_id`, `room_id`, `equipment_id`) VALUES (?,?,?)");
           foreach($data as $row)
            {
                $stmt->bind_param('sss', $row['building_id'], $row['room_id'], $row['equipment_id']);
                $stmt->execute();
            }
            $date1 = date('Y-m-d');
            $dt = strtotime($date1);
            $dta = date("Y-m-d", $dt);
            $m = date("Y-m-d", strtotime("+2 month", $dt));

             $stmt2 = $this->con->prepare("UPDATE task_tbl SET date_sched='$m', sta = 0 WHERE id = 2 ");
            $stmt2->execute();
            return 1;  
        }

    }
    public function clickATask($status){

        if ($status == 1) {
            $stmt1 = $this->con->prepare("SELECT checking_tbl.building_id, checking_tbl.room_id, checking_tbl.equipment_id FROM `checking_tbl` INNER JOIN equipment_tbl ON equipment_tbl.id = checking_tbl.equipment_id INNER JOIN category_tbl ON category_tbl.id = equipment_tbl.category_id WHERE category_tbl.Task = 3");
           $stmt1->execute();
           $data = array();
           $stmt1->bind_result($building_id,$room_id,$equipment_id);
           $stmt1->store_result();
           if( $stmt1->num_rows > 0){
              while($stmt1->fetch()){ 
                 $data[] = array('building_id'=>$building_id,'room_id'=>$room_id,'equipment_id'=>$equipment_id);
              }
           }
           $stmt = $this->con->prepare("INSERT INTO `checking_tbl`( `building_id`, `room_id`, `equipment_id`) VALUES (?,?,?)");
           foreach($data as $row)
            {
                $stmt->bind_param('sss', $row['building_id'], $row['room_id'], $row['equipment_id']);
                $stmt->execute();
            }
            $date1 = date('Y-m-d');
            $dt = strtotime($date1);
            $dta = date("Y-m-d", $dt);
            $m = date("Y-m-d", strtotime("+12 month", $dt));
             $stmt2 = $this->con->prepare("UPDATE task_tbl SET date_sched='$m', sta = 0 WHERE id = 3 ");
            $stmt2->execute();
            return 1;  
        }

    }

}
  

 ?>