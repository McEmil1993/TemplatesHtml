<?php
/**
 * 
 */
class LoginController extends controller
{
	
	public function login()
	{
		$username = isset($_POST['username'])? $_POST['username'] : 'admin';
		$password = isset($_POST['password'])? $_POST['password'] : 'admin';

		$auth_obj = new LoginModel();

		$result = $auth_obj->login(array('username' => $username,'password' => $password));

		echo json_encode($result);

	}

	public function Registry()
	{
		$fullname = isset($_POST['fullname'])? $_POST['fullname'] : '';
		$contact = isset($_POST['contact'])? $_POST['contact'] : '';
		$address = isset($_POST['address'])? $_POST['address'] : '';
		$password = isset($_POST['password'])? $_POST['password'] : '';
		$username = isset($_POST['username'])? $_POST['username'] : '';
		$user_type = isset($_POST['user_type'])? $_POST['user_type'] : '';

		$auth_obj = new LoginModel();

		$result = $auth_obj->Registry(array('fullname' => $fullname,'contact' => $contact,'address' => $address,'password' => $password,'username' => $username,'user_type' => $user_type));

		echo json_encode($result);

	}
	public function profile()
	{
		
		$img = isset($_POST['img'])? $_POST['img'] : '';
		$username = isset($_POST['username'])? $_POST['username'] : '';

		$auth_obj = new LoginModel();

		$result = $auth_obj->profile(array('img' => $img,'username' => $username));

		echo json_encode($result);

	}
	public function updateInfo()
	{
		# code..
		$fullname = isset($_POST['fullname'])? $_POST['fullname'] : '';
		$contact = isset($_POST['contact'])? $_POST['contact'] : '';
		$address = isset($_POST['address'])? $_POST['address'] : '';
		$password = isset($_POST['password'])? $_POST['password'] : '';
		$username = isset($_POST['username'])? $_POST['username'] : '';
		$id = isset($_POST['id'])? $_POST['id'] : '';

		$auth_obj = new LoginModel();

		$result = $auth_obj->updateInfo(array('fullname' => $fullname,'contact' => $contact,'address' => $address,'password' => $password,'username' => $username,'id' => $id));

		echo json_encode($result);
	}
}
?>