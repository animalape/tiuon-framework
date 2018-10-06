<?php
/* ======== 模板类 =======
 * Author:Tiuon.com
 * ====================== */
namespace library;
use library\template\connector\smarty;
use library\template\connector\template;
class View{
	protected $_view;
	// 构造函数
    public function __construct(){
        $class = strtolower(C('TMP_TYPE'));
        $this->_view = new $class();
    }
	// 分配模板变量
	public function assign($name, $value=''){
		$this->_view->assign($name, $value);
	}
	// 渲染模板
	public function display($template='', $cache_id='', $compile_id=''){
		$this->_view->display($template, $cache_id, $compile_id);
	}
	// show 方法
	public function show($html=''){
		$this->_view->show($html);
	}
}