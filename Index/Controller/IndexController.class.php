<?php
	class IndexController extends Controller{
		public $db;
		private $path;

		public function __construct(){
			$this->path = ROOT_PATH . '/DATA/db.php';
			$this->db = include $this->path;
		}

		public function index(){
			$this->display();
		}

		public function ajax_add_msg(){
			if(!IS_AJAX){
				exit("the address is wrong!");
			}else{
				$data = array(
				'title'  => adds_html($_POST['title']),
				'avator' => adds_html($_POST['tu']),
				'content'=> adds_html($_POST['content']),
				'time'   => time(),
				);
				$this->db[] = $data;
				if(data_to_file($this->db,$this->path)){
					$data['status'] = true;
					$data['time'] = date('l-Y/m/d-H:i:s');
					$data['lucky'] = max(array_keys($this->db));
					echo json_encode($data);
				}else{
					$data['status'] = false;
					echo json_encode($data);
				}
			}
			
		}
		
	}
?>