<?php 

class TaskController extends controller{


	public function display(){
        
		$Task = isset($_POST['Task']) ? $_POST['Task'] : "";
		$room_name = isset($_POST['room_name']) ? $_POST['room_name'] : "";
		$building_name = isset($_POST['building_name']) ? $_POST['building_name'] : "";
		
		$auth_obj = new TaskModel();
		$result = $auth_obj->display(array('Task' => $Task,
                                'room_name' => $room_name,
                                'building_name' => $building_name));
		echo json_encode($result);
	}

	public function getAllEquip(){
       $Task = isset($_POST['Task']) ? $_POST['Task'] : "";
		$auth_obj = new TaskModel();
		$result = $auth_obj->getAllEquip(array('Task' => $Task));
		echo json_encode($result);
    }

    public function save(){
    	$b_id = isset($_POST['b_id']) ? $_POST['b_id'] : "";
    	$r_id = isset($_POST['r_id']) ? $_POST['r_id'] : "";
		$ename = isset($_POST['ename']) ? $_POST['ename'] : "";
		$Task = isset($_POST['Task']) ? $_POST['Task'] : "";
		$auth_obj = new TaskModel();
		$result = $auth_obj->save(array('ename' => $ename,'Task' => $Task,'b_id' => $b_id,'r_id' => $r_id));
		
		echo json_encode($result);
	}

	public function checkStatus()
	{
		# code...i_name
		$status = isset($_POST['status']) ? $_POST['status'] : "1";
    	$id = isset($_POST['id']) ? $_POST['id'] : "4";
    	$i_name = isset($_POST['i_name']) ? $_POST['i_name'] : "sdsd";
		$auth_obj = new TaskModel();
		$result = $auth_obj->checkStatus(array('status'=>$status,'id'=>$id,'i_name'=>$i_name));
		
		echo json_encode($result);
	}
	public function delete()
	{
    	$id = isset($_POST['id']) ? $_POST['id'] : "";
		$auth_obj = new TaskModel();
		$result = $auth_obj->delete(array('id'=>$id));
		
		echo json_encode($result);
	}
    public function displaySched()
	{
		$auth_obj = new TaskModel();
		$result = $auth_obj->displaySched();
		
		echo json_encode($result);
	}
	public function cSched()
	{
		$auth_obj = new TaskModel();
		$m = $auth_obj->mSched();
		$q = $auth_obj->qSched();
		$a = $auth_obj->aSched();

        $auth_obj->updatTask($m,1);
		$auth_obj->updatTask($q,2);
		$auth_obj->updatTask($a,3);
		
		$result[] = array('1'=>$m,'2'=>$q,'3'=>$a);
		echo json_encode($result);
	}
    public function qur()
    {
    	$da = '';
    	$date_sched = isset($_POST['date_sched']) ? $_POST['date_sched'] : "";
    	$id = isset($_POST['task']) ? $_POST['task'] : "";

		$auth_obj = new TaskModel();
		$result = $auth_obj->task_tbl(array('task'=>$id,'date_sched'=>$date_sched));
		
		echo json_encode($result);
    }

     public function getAllTask()
    {

		$auth_obj = new TaskModel();
		$result = $auth_obj->getAllTask();
		echo json_encode($result);
    }

     public function clickTask()
    {
		$auth_obj = new TaskModel();
		$m = $auth_obj->mSched();
		$q = $auth_obj->qSched();
		$a = $auth_obj->aSched();

		$auth_obj->clickMTask($m);
		$auth_obj->clickQTask($q);
		$auth_obj->clickATask($a);

    }
    public function ok()
    {
    	# code...
    	$date1 = date('Y-m-d');
            $dt = strtotime($date1);
            $dta = date("Y-m-d", $dt);
            $m = date("Y-m-d", strtotime("+1 month", $dt));

            echo $m;
    }

}
?>