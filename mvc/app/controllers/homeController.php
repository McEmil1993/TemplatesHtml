<?php

/**
 * 
 */
class homeController extends Controller
{
	private $controller;
	function __construct()
	{
		$this->controller = new Controller();
	}

	public function index()
	{
		$this->model  = new homeModel();

		$data = $this->model->info();

		$this->controller->view()->render('home.php',$data);
	}
	public function about()
	{
		# code...
		$v = isset($_GET['b'])? $_GET['b'] : '';
		
		$data  = array('value' => $v );

		$this->controller->view()->render('about.php',$data);
	}

}
?>