<?php 
// 框架基本文件【用于启动框架的第一个类】 【过程如下图】
					//单入口 【通过APP_NAME 来判断,框架下面的index文件】
						//
					//核心类  【./sam/sam.php】
						//
	//设置常量--//创建目录--//载入必须文件--//执行应用  //[一个public方法中包含多个私有方法]
												//
	//初始化框架--//设置外部路径--//自动载入--//创建deomo--//实例化控制器	
																//
															//输出欢迎语			


// 建立核心类， 然后【设置常量】【创建文件夹】【载入必须文件】【执行应用类】
class Zheyan{
	public static function run(){
		// 设置常量 【步骤1】
		self::_set_const();
		// 创建用户应用文件夹
		self::_create_dir();
		// 载入框架必需文件
		self::_import_file();
		// 应用类的执行
		Application::run();
	}

	// 载入必需文件方法
	private static function _import_file(){
		// 找到functions.php 的路径
		$arr = array(
			FUNCTION_PATH . '/functions.php', 
			CORE_PATH . '/Controller.class.php', //[必须要在Application上面，因为实例化的时候需要用到Controller class]
			CORE_PATH . '/Application.class.php',

			);
		// 加载路径
		foreach ($arr as $v) {
			require_once $v;
		}

	}

	// 创建用户应用方法
	private static function _create_dir(){
		// 1. 把用户应用路径放在 $fillArr 中
		$fileArr = array(
			APP_PATH,
			APP_CONFIG_PATH,
			APP_CONTROLLER_PATH,
			APP_TPL_PATH,
			APP_PUBLIC,
			);
		// 用短路将这些路径创建出来
		foreach ($fileArr as $v) {
			is_dir($v) || mkdir($v);
		}
	}

	// 设置框架所需常量
	private static function _set_const(){
		// var_dump(__FILE__); # 因为路径中有 '\',所以还需要替换出来
		$path = str_replace('\\', '/', __FILE__);

		// [框架根路径]，定义常量路径，常量的特性是，它的值不能再改变
		define('ZHEYAN_PATH', dirname($path)); 
		// [项目根目录]
		define('ROOT_PATH', dirname(ZHEYAN_PATH));
		// 框架的配置项路径
		define('CONFIG_PATH', ZHEYAN_PATH . '/Config');
		// 框架数据路径
		define('DATA_PATH', ZHEYAN_PATH . '/Data');
		// 框架扩展路径
		define('EXTENDS_PATH', ZHEYAN_PATH . '/Extends');
		// ORG路径 【放置bootstrap啥的第三方程序】
		define('ORG_PATH', EXTENDS_PATH . '/Org');
		// 工具类，【包括code，upload这些类】
		define('TOOL_PATH', EXTENDS_PATH . '/Tool');
		// 核心库类
		define('LIB_PATH', ZHEYAN_PATH . '/Lib');
		// 核心类文件
		define('CORE_PATH', LIB_PATH . '/Core');
		//函数文件夹
		define('FUNCTION_PATH', LIB_PATH . '/Function');


		// 前台应用路径
		define('APP_PATH', ROOT_PATH . '/' . APP_NAME); // [通过单入口文件，这里不用写死]
		// 前台配置项路径
		define('APP_CONFIG_PATH', APP_PATH . '/Config');
		// 前台控制器路径
		define('APP_CONTROLLER_PATH', APP_PATH . '/Controller');
		// 前台模板路径
		define('APP_TPL_PATH', APP_PATH . '/Tpl');
		// 前台 模板下的 public 路径 [放CSS或者JS]
		define('APP_PUBLIC', APP_TPL_PATH . '/Public');
		
		// 这个暂时还不知道是什么意思
		define('IS_POST', $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false);
		// 是否是ajax提交
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
			define('IS_AJAX', true);
		}else{
			define('IS_AJAX', false);
		}

		// 定义用户的文件夹常量 [Index->用户目录，应该被直接创建]
			// 【Config-》用户配置项】 【Controller-》控制器？】 【Tpl-》模板 public？】
		// 定义后台的文件夹常量 【Admin-》后台】
	}
}

Zheyan::run();
 ?>