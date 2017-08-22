<?php 
// 图类
class Image{
	//缩略图
	public function thumb($path=NULL,$w=NULL,$h=NULL){ 
		header('Content-type:image/jpeg'); //[头部文件必须在功能类里面，很奇怪]
		//1. 建立画布
		$img = imagecreatetruecolor($w, $h);
		//2. 上色
		imagecolorallocate($img, 255, 255, 255);
		//3. open the image
		$size = getimagesize($path);
		$type = ltrim(strrchr($size['mime'],'/'),'/');
		$str = 'imagecreatefrom' . $type;
		$srcImg = $str($path);
		//4. 开始缩略
		if(!function_exists('imagecopyresampled')){
			imagecopyresized($img, $srcImg, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
		}else{
			imagecopyresampled($img,$srcImg, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
		}
		//5 . 释放和删除图片
		imagejpeg($img,'../upload/thumb_zheyan.jpg');
		imagedestroy($img);
		imagedestroy($srcImg);
		//6. 得到路径，并且返回

	}

}


 ?>