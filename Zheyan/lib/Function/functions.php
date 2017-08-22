<?php 
	// header('content-type:text/html;charset=utf-8');  [在框架里，头部不能随意发，目前还不懂是什么意思]
	function print_const(){
		$const = get_defined_constants();
		p($const);
	}
	# 改变多维数组键名大小
	function change_case($arr,$case = CASE_UPPER){
		$arr = array_change_key_case($arr,$case);

		foreach ($arr as $k => $v) {
			if(is_array($v)){
				$arr[$k] = change_case($v,$case);

			}	
		}
		
		return $arr;
	}

	// 载入一个没有找到的类，会运行此函数    【auto 也不需要，会在框架里面使用】
	// function __autoload($className){      
	// 	// 这里面参数是一定要写的
	// 	// echo '因为找不到类，所以现在加载的是 ' . $className;

	// 	// 得到类名的好处是，以后编写的类名文件就可以自动载入
	// 	include  $className . ".class.php";  # 当然现在是不行的，因为我自己的文档编写很乱
	// }


	#拆分数组，把上传图片文件自动分组的函数
	function reset_arr(){
		$arr = array();		
			foreach ($_FILES as $v) {
				// 有时候$v['name']是一维数组，那么就需要先判断再执行
				if(is_array($v['name'])){
					foreach ($v['name'] as $key => $value) {
						$arr[] = array(
							'name' => $value,
							'type' => $v['type'][$key],
							'tmp_name' => $v['tmp_name'][$key],
							'error' => $v['error'][$key],
							'size' => $v['size'][$key],
						);
					}
				}else{
					// 单文件上传
					$arr[] = $v;
				}
			}
		
		return $arr;
	}

	# P 函数，显示数组的格式
	function p($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}

	# 测试用
	function run($action){
		static $time;
		if ($action == 'start') {
			$time = microtime(true);
		}else if ($action == 'end'){
			$time = microtime(true) - $time;
			echo $time;
		}else {
			return '请输入 "start" 或者 "end"';
		}
	}

	# C 函数
	function C($var = NULL, $value = NULL){
		// 静态变量保存配置项
		static $config = array();
		// 如果是数组的话加载到配置项
		if (is_array($var)) {
			$config = array_merge($config,$var);
			return;
		}

		// 如果第一个参数一个字符串
		// 有两种情况
		// 1.第二个参数无值
		// 2.第二个参数有值
		if(is_string($var)){
			//第二个参数无值
			if (is_null($value)) {
				// $confg['CODE_LEN']
				return isset($config[$var]) ? $config[$var] : NULL;
			}
			//第二个参数有值
			$config[$var] = $value;
			return $value;
		}

		// 两个参数都没传递
		if (is_null($var) && is_null($value)) {
			return $config;
		}
	}


	# 检测文件名
	// function tu($tu){
	// 	return ltrim(strchr($tu,'.'),'.');
	// }


	# 实体并且转译函数
	function adds_html($var){
		return addslashes(htmlspecialchars($var));
	}

	# 判断 是否使用post方式传输
	// define('IS_POST', $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false);


	#判断硬盘额总大小
	function format_size($size){
	switch (true) {
		case $size>pow(1024, 4):
			$unit = array(4,'TB');
			break;
		case $size>pow(1024, 3):
			$unit = array(3,'G');
			break;
		case $size>pow(1024, 2):
			$unit = array(2,'MB');
			break;
		case $size>pow(1024, 1):
			$unit = array(1,'KB');
			break;
		default:
			$unit = array(0,'byte');
			break;
	};
	echo round($size/pow(1024, $unit[0]),1) . $unit[1]; #这里的$unit 已经变为数组
	// 就是说它的下标对应的分别是数字和字符串 单位
};


# 删除目录函数
function del_dir($path){
	if(!is_dir($path)){
		return false;
	};
	// 扫描当下目录下的所有
	foreach (glob($path . '/*') as $v) {
		if (is_dir($v)) {
			del_dir($v);
		}else {
			unlink($v);
		}
	};
	return rmdir($path);

}

// 移动函数
function move($a,$b) {
	// 首先拷贝函数，然后删除函数
	cp($a,$b);
	del_dir($a);

};

// 复制函数
function cp($a,$b) {
	if(!is_dir($a)) return false;
	//首先规范路径
	$a = change_path($a);
	$b = change_path($b);
	//需要定义新路径
	$newPath = $b . basename($a);
	//扫描a文件夹所有文件
	is_dir($newPath) || mkdir($newPath,0777,ture);
	echo $newPath;
	foreach (glob($a . '*') as $v){
		is_dir($v) ? cp($v,$newPath) : copy($v, $newPath . '/' . basename($v));
	};
};

// 还需要一个统一路径的函数,为了保证win和linux可以同时使用
function change_path($path) {
	$path = str_replace('\\', '/', $path);
	//统一的去掉斜杠,因为有时候他们会重新输入斜杠
	$path = rtrim($path,'/') . '/';
	return $path;
};

// 时区设置
// date_default_timezone_set('PRC');

//成功提示语
function success($msg,$url){
	echo "<script>alert('{$msg}');location.href='{$url}'</script>";
	die;
}
//失败提示语
function error($msg){
	echo "<script>alert('{$msg}');history.back()'</script>";
	die;
}
// 上传内容到文件里
function data_to_file($data,$path){
	$phpDb = var_export($data,true); #把数组转为字符串
	$str = <<<str
<?php
	return {$phpDb}
?>
str;
	if(file_put_contents($path,$str)){; // 这里提交的话不能是数组，而且需要写上php的标记风格
		return true;
	} #用户有可能不想跳转
	// 4. 把留言循环出来
	
}

//文件/图片上传函数
function upload($path){
	$arr = reset_arr();
	is_dir($path) || mkdir($path,0777,true);
	foreach ($arr as $v) {
		if(is_uploaded_file($v['tmp_name'])){ #这里就可以判断是否为上传文件
			
			// 判断上传的文件类型
			$info = pathinfo($v['name']);
			$type = $info['extension'];
			// 为了避免覆盖，这时候需要给图片名字添加日期和随机数
			$fullName = date('ymdhis') . mt_rand(0,9999) . '.' . $type;
			// 拷贝后的文件路径
			$fullPath = $path . '/' . $fullName;
			// 复制到指定路径
			move_uploaded_file($v['tmp_name'], $fullPath);
		}
	}
	
}
 ?>