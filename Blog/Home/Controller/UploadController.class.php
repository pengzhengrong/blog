<?php

namespace Home\Controller;
use Think\Controller;

Class UploadController extends Controller {

	Public function index() {
		if( IS_POST ) {
			$config = array(
			        'mimes'         =>  array(), //允许上传的文件MiMe类型
			        'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
			        'exts'          =>  array(), //允许上传的文件后缀
			        'autoSub'       =>  true, //自动子目录保存文件
			        'subName'       =>  array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
			        'rootPath'      =>  $_SERVER['DOCUMENT_ROOT'].'/uploadfile/', //保存根路径
			        'savePath'      =>  '', //保存路径
			        'saveName'      =>  '',//array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
			        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
			        'replace'       =>  true, //存在同名是否覆盖
			        'hash'          =>  true, //是否生成hash编码
			        'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
			        'driver'        =>  '', // 文件上传驱动
			        'driverConfig'  =>  array(), // 上传驱动配置
       				 );
			$upload = new \Think\Upload($config);
			$file = $_FILES;
			$info = $upload->uploadOne($file['file']);
			$error = $upload->getError();
			$this->ajaxReturn(setAjaxReturn($info,$error,$info));
		}
		// P($_SERVER);die;
		$this->display();
	}

}