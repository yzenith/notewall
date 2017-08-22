<?php 

class Application{

	public static function run(){
		// 1. 初始化框架
		self::_init();

		// 2. 设置外部路径
		self::_set_url();
		// 3. 设置auto load [__CLASS__ 获取当前类的名字，这样Application]
		spl_autoload_register(array(__CLASS__,'_autoload'));
		// 4. 创建demo
		self::_create_demo();
		// 5. 实例化控制器
		self::_app_run();
	}

	// 实例化 demo 【通过GET参数控制】
	private static function _app_run(){
		$c = isset($_GET['c']) ? $_GET['c'] : 'Index';  //[试着打开frame-app，那么就会变为路径Index]
		$a = isset($_GET['a']) ? $_GET['a'] : 'index';

		$c .= 'Controller';

		$obj = new $c;
		$obj->$a();
	}

	// 创建demo[创建一个控制器]
	private static function _create_demo(){
		// [设置路径]
		$controller = APP_CONTROLLER_PATH . '/IndexController.class.php';
		
		// 判断文件不否存在并且建立
		if(!is_file($controller)){ //[这里的本来就是路径，所以说路径的设置很重要]
			// 创建Controller.class.php文件
			$str = <<<str
<?php
	class IndexController extends Controller{
		public function index(){
			header('Content-type:text/html;charset=utf-8');
			echo "欢迎使用闫哲的MVC";
		}
	}
?>
str;
		file_put_contents($controller, $str); //[这里的创建文件也是用的路径]
		}
	}


	// autoload 自动载入方法
	private static function _autoload($className){

		// echo APP_CONTROLLER_PATH . '/' .  $className . '.class.php';
		// die; [路径测试]
		// 判断是否为Controller项目【从包含Controller的字节数目去判断，例如：indexController】
		if(strlen($className) > 10 && substr($className, -10) == 'Controller'){
			include APP_CONTROLLER_PATH . '/' .  $className . '.class.php';
		}else{ //[其他的去Tool的路径下去找]
			include TOOL_PATH . '/' . $className . '.class.php';
		}
		
	}

	// 设置外部路径
	private static function _set_url(){
		// p($_SERVER); [里面的 HTTP_HOST 就是域名] [SCRIPT_NAME 就是脚本路径]
		$path = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
		// echo $path;
		$path = str_replace('\\', '/', $path);
		# [开始定义路径] [__APP__ 就是首页路径]
		define('__APP__', $path);
		define('__ROOT__', dirname(__APP__)); //[需要注意的是，它的末尾不带 / ]
		define('__TPL__', __ROOT__ . '/' . APP_NAME . '/Tpl');
		define('__PUBLIC__', __TPL__ . '/Public');

	}

	// init方法
	private static function _init(){

		// 加载框架配置项 [可以使用C函数，因为zheyan.php已经加载了]
		C(include CONFIG_PATH . '/config.php');

		// 加载用户配置项 【APP目录下的config.php 文件】
		$userConfig = APP_CONFIG_PATH . '/config.php';
		if(!is_file($userConfig)){
			$str = <<<str
<?php 
	return array(
		// 配置项 => 配置值
	);
?>
str;
			file_put_contents($userConfig, $str);
		}
		C(include $userConfig);

		//设置默认时区 [用C函数让时区自动设置]
		date_default_timezone_set(C('DEFAULT_TIMEZONE'));
		
	}
}

 ?>