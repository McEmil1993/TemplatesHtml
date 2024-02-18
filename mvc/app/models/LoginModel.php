<?php
/**
 * 
 */
class LoginModel extends model
{
	
	private $con;

    public function __construct(){
        $db = new database();
        $this->con = $db->connection();
    }

    public function login($param= array())
    {
    	$username = $param['username'];
    	$password1 = $param['password'];
        $data = array();
    	$stmt = $this->con->prepare("SELECT users_tbl.id AS D ,users_tbl.fullname,users_tbl.contact,users_tbl.address,users_tbl.username,users_tbl.password,users_tbl.user_type FROM users_tbl  WHERE username=?");
            $stmt->bind_param("s",$username);
            $stmt->execute();
            
                $stmt->bind_result($id, $fullname,$contact,$address,$username, $password,$user_type);

                if($stmt->fetch()){ 

                $hash_password = $password;
         
                if($hash_password == $password1){
                    $lg = new LoginModel();
                    $fn = $lg->getFilename($id);

                 $data[] = array('result'=>'ok','D' => $id,'username' => $username,'password' => $password,'contact' => $contact,'address' => $address,'fullname'=> $fullname,'user_type' => $user_type, 'filename'=>$fn );

              }else{
             	$data[] = array('result'=>'pass');
             } 
         } else{
           		$data[] = array('result'=>'use');
           }
            $stmt->close();
            $this->con->close();
            return $data;

           
    }

    public function Registry($param=array())
    {
    	$username = $param['username'];
    	$password = $param['password'];
    	$fullname = $param['fullname'];
    	$contact = $param['contact'];
    	$address = $param['address'];
    	$user_type = $param['user_type'];

        $data = array();
        $stmt = $this->con->prepare("SELECT * FROM users_tbl WHERE username=?");
            $stmt->bind_param("s",$username);
            $stmt->execute();
            $stmt->store_result();
            if( $stmt->num_rows > 0){
                $data[] = array('result'=>'use');
            }
    	    else{
        	$stmt = $this->con->prepare("INSERT INTO `users_tbl`(`fullname`, `contact`,address,username,password,user_type) VALUES (?,?,?,?,?,?)");
			$stmt->bind_param("ssssss",$fullname,$contact,$address,$username,$password,$user_type);
			if ($stmt->execute()== TRUE) {

				$auth_obj = new LoginModel();
		        $result = $auth_obj->getId();

			   $data[] = array('result' => "ok",'id'=>$result);
			}else{
				$data[] = array('result' => "error");
			}

           }
        $stmt->close();
        $this->con->close();
        return $data;
    }

     public function profile($param=array())
    {
    	$username = $param['username'];
    	$img = $param['img'];
    	$uid= "";
    	$filename = "";

        $data = array();
       
           $stmt = $this->con->prepare("SELECT `id` FROM `photo_tbl` WHERE `user_id` =?");

            $stmt->bind_param("s",$username);
            $stmt->execute();
            $stmt->store_result();
            if( $stmt->num_rows > 0){
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

            	if ($img == "null") {
                 	$ImagePath = "app/images/no_image.jpg";
            	}else{
                	$ImagePath = "app/images/IMG_$imagename.png";
                	file_put_contents($ImagePath,base64_decode($img));
            	}

            	$filename="http://localhost/mvc/$ImagePath";

                	$stmt = $this->con->prepare("UPDATE `photo_tbl` SET  `filename`=? WHERE id=?");
					$stmt->bind_param("ss",$filename,$username);
					if ($stmt->execute()== TRUE) {
			   			$data[] = array('result' => "ok",'filename'=>$filename);
					}else{
						$data[] = array('result' => "error");
					}
            	}else{
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

            	if ($img == "null") {
                 	$ImagePath = "app/images/no_image.jpg";
            	}else{
                	$ImagePath = "app/images/IMG_$imagename.png";
                	file_put_contents($ImagePath,base64_decode($img));
            	}

            	$filename="http://localhost/mvc/$ImagePath";
            		$stmt = $this->con->prepare("INSERT INTO `photo_tbl`(`user_id`, `filename`) VALUES (?,?)");
					$stmt->bind_param("ss",$username,$filename);
					if ($stmt->execute()== TRUE) {
						
			   			$data[] = array('result' => "ok",'filename'=>$filename);
					}else{
						$data[] = array('result' => "error");
					}
            	} 


        $stmt->close();
        $this->con->close();
        return $data;
    }

    public function getId()
    {
    	 $stmt = $this->con->prepare("SELECT * FROM users_tbl where `id` = ( SELECT MAX(`id`) FROM users_tbl )");
         $stmt->execute();
         $res = $stmt->get_result();
         $srow = $res->fetch_assoc();
         $id = $srow['id'];
         return $id;
    }
     public function getFilename($id)
    {
        $r = "";
         $stmt = $this->con->prepare("SELECT * FROM photo_tbl where `user_id` = '$id'");
         $stmt->execute();
         $res = $stmt->get_result();
         if($srow = $res->fetch_assoc()){
             $f = $srow['filename'];
             return $f;
         }else{
            return $r;
         }
        
    }
    public function updateInfo($param=array())
    {
    	# code...
    	$username = $param['username'];
    	$password = $param['password'];
    	$fullname = $param['fullname'];
    	$contact = $param['contact'];
    	$address = $param['address'];
    	$id = $param['id'];

        $data = array();
        
        $stmt = $this->con->prepare("UPDATE `users_tbl` SET `fullname`=?, `contact`=?,address=?,username=?,password=? WHERE id = ?");
		$stmt->bind_param("ssssss",$fullname,$contact,$address,$username,$password,$id);
			if ($stmt->execute()== TRUE) {

			   $data[] = array('result' => "ok");
			}else{
				$data[] = array('result' => "error");
			}

        $stmt->close();
        $this->con->close();
        return $data;

    }
}
?>