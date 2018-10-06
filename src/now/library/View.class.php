<?php
/* ======== 模板类 =======
 * Author:Tiuon.com
 * ====================== */
namespace library;
class View{
	protected $view;
	// 构造函数
    public function __construct(){
    	$type = strtolower(C('TMP_TYPE'));
		$class = "library\\template\\connector\\{$type}";
		if(class_exists($class)){
			$this->view = new $class;
		}else{
            // 模板类没有定义
            Error('模板类没有定义: ' . $class);
       } 
    }
	// 分配模板变量
	public function assign($name, $value=''){
		$this->view->assign($name, $value);
	}
	// 渲染模板
	public function display($template='', $cache_id='', $compile_id=''){
		$this->view->display($template, $cache_id, $compile_id);
	}
	// show 方法
	public function show($html=''){
		$this->view->show($html);
	}
}