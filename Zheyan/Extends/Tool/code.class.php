<?php 
// 作业-完成验证码类

// include "../../function.php";
// 这里必须要先写入数值，否则就报错
C(include CONFIG_PATH . "/config.php");
C(include APP_CONFIG_PATH . "/config.php");

class Code{

	private $img;
	private $width;
	private $height;
	private $color;
	private $seed;
	private $code;
	private $img_path;

	// $bgColor = '#ffffff';
	// $color = hexdec($this->bgColor); 这里的命令是用来将 10进制的数字变为 数字0，这里和CSS不同，必须要写成 #fff000,不能写为3位

	public function __construct($width=NULL,$height=NULL){ #这里的传入参数，很有意思，我已经错了很多遍
		$this->width = is_null($this->width) ? C('CODE_WIDTH') : $this->width;
		$this->height = is_null($this->height) ? C('CODE_HEIGHT') : $this->height;
	}

	public function show(){
		//1 . 发头部
		header('Content-type:image/png');
		//2 . 创建画布，填充颜色
		$this->_create_bg();
		//3. 写字
		$this->_xie_zi();
		//4. 创造干扰
		$this->_gan_rao();
		//5. 输出
		$this->_shu_chu();
		//6. 销毁
		$this->_xiao_hui();
	}

	//创建画布方法
	private function _create_bg(){
		$this->img = imagecreatetruecolor($this->width, $this->height);
		$this->color = imagecolorallocate($this->img, 0, 0, 0);
	}

	//写字方法
	private function _xie_zi(){
		$this->img_path = DATA_PATH . '/msyh.ttf';
		$this->seed = "asdfghjkwertfyghjksdfcgvhbnzxcvbzxcvbn1234567812345678098765";
		$this->code = $this->seed[mt_rand(0,strlen($this->seed)- 1)];
		$this->color = imagecolorallocate($this->img, 255, 255, 255);

		for ($i=0; $i < 4; $i++) { 
			$x = $this->width / 4 *$i + 40;
			$y = ($this->height+70) / 2;
			imagettftext($this->img, 70, mt_rand(-45,45), $x, $y, $this->color, $this->img_path, $this->code);
		}
	}

	//干扰方法
	private function _gan_rao(){

		for ($i=0; $i < 100; $i++) { 
			$this->color = imagecolorallocate($this->img, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
			imageline($this->img, mt_rand(0,$this->width), mt_rand(0,$this->width), mt_rand(0,$this->width), mt_rand(0,$this->height), $this->color);
		}

		for ($i=0; $i < 5000; $i++) { 
			$this->color = imagecolorallocate($this->img, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
			imagesetpixel($this->img, mt_rand(0,$this->width), mt_rand(0,$this->height), $this->color);
		}
	}

	// 输出方法
	private function _shu_chu(){

		// 6. 输出
		imagepng($this->img);

		
	}

	// 销毁方法
	private function _xiao_hui(){
		// 7. 销毁资源
		imagedestroy($this->img);
	}
}


$code = new Code;
$code->show();

 ?>