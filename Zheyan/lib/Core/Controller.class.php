<?php 

class Controller{

	protected function success($data,$path){
		echo "<script>alert('{$data}')</script>;location.href='{$path}'";
		die;
	}

	protected function error($data){
		echo " <script>alert('{$data}')</script>";
		die;
	}

	protected function display(){
		if(!empty($_GET)){
			include APP_TPL_PATH . '/' . $_GET['c'] .'/' . $_GET['a'] . '.html';
		}else if(!empty($_GET) && $_GET['c'] == 'code'){
			include TOOL_PATH . '/Code.class.php';
		}else{
			include APP_TPL_PATH . '/Index/index.html';
		}
		
	}
}
 ?>