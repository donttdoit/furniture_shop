<?php

class Controller_Main extends Controller
{

	function __construct()
	{
		$this->model = new Model_Main();
		$this->view = new View();
	}

	function action_index()
	{	
		$_SESSION['id_user'] = 1;
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			// foreach($_POST as $key => $val){
			// 	echo $key.' '.$val.'<br>';
			// }
			
			if (isset($_POST['add'])){
				$this->model->add_cart($_SESSION['id_user'], $_POST['id'], $_POST['count']);
			}

			if (isset($_POST['del'])){
				$this->model->del_cart($_SESSION['id_user'], $_POST['id']);
			}
		}
		$data = $this->model->get_data();
		// print_r($data);
		$this->view->generate('main_view.php', 'template_view.php', $data);
	}
}