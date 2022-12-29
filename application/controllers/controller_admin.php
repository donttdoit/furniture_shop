<?php

class Controller_Admin extends Controller
{
	function action_index()
	{	
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			// foreach($_POST as $key => $val){
			// 	echo $key.' '.$val.'<br>';
			// }

            if (isset($_POST['name']) && $_POST['name'] == 'admin' &&
                isset($_POST['password']) && $_POST['password'] == '1234'){
                    $this->view->generate('admin_view.php', 'template_view.php');
                }
            else{
                echo 'Неверный логин/пароль';
                $this->view->generate('check_view.php', 'template_view.php');
            }
        }
        else{
            $this->view->generate('check_view.php', 'template_view.php');
        }
	}
}