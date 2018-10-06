<?php
/* ======== 模板类 =======
 * Author:Tiuon.com
 * ====================== */
namespace library;
use library\template\connector\template;
use library\template\connector\smarty;
class View{
	protected $view;
	// 构造函数
    public function __construct(){
    	$type = strtolower(C('TMP_TYPE'));
		$class = "library\\template\\connector\\{$type}";
        $this->view = new $class;
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