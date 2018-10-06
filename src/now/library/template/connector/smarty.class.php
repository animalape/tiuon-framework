<?php
/* ======== smarty ========
 * Author:Tiuon.com
 * ======================== */
namespace library\template\connector;
// 引入Smarty模板类
vendor('Smarty.Smarty','','.class.php');
class smarty{
	protected $view;
	
	// 构造函数
	public function __construct(){
		// 实例化Smarty类对象
		$this->view = new \Smarty();
		// 调试模式
		$this->view->debugging	= C('TMP_DEBUG');
		// 设置模板目录
		$this->view->setTemplateDir(APP_PATH . MODULE_NAME . DS . C('TMP_VIEWS') . DS);
		// 设置编译目录
		$this->view->setCompileDir(RUNTIME_PATH . C('TMP_COMPILE_DIR') . DS . MODULE_NAME . DS);
		//设置配置目录
		//$this->view->setConfigDir("./config");
		// 指定缓存文件目录
		$this->view->setCacheDir(RUNTIME_PATH . C('TMP_CACHE_PATH') . DS . MODULE_NAME . DS);
		// 是否使用缓存，项目调试期间，不建议启用缓存
		$this->view->caching	=	C('TMP_CACHE_AUTO');
		// 缓存生命周期
		if(C('TMP_CACHE_TIME') && !C('TMP_DEBUG')){
			$this->view->cache_lifetime	= C('TMP_CACHE_TIME');
		}
		// 左右边界符,默认是{},但实际应用中容易与JavaScript冲突，可以设计为<{ and }>这样的形式
		$this->view->left_delimiter		=	C('TMP_L_DELIM');//指定左标签
		$this->view->right_delimiter	=	C('TMP_R_DELIM');//指定右标签
	}
	
		// 分配模板变量
	public function assign($name, $value){
		if($name && $value){
			$this->view->assign($name, $value);
		}
	}
	
	// 渲染模板
	public function display($template='', $cache_id='', $compile_id=''){
		// 检查模板名是否不存在
		if(!empty($template)){
			$file_arr = explode('/',trim($template,'/'));
			$count = count($file_arr);
			if($count > 1){
				$this->display_file = $template;
			}else{
				$this->display_file = CONTROLLER_NAME . DS . $template;
			}
		}else{
			$this->display_file	= CONTROLLER_NAME . DS . ACTION_NAME;
		}
		$_tmp =  $this->display_file . '.' .  C('TMP_SUFFIX');
		if(!empty($cache_id) && empty($compile_id)){
			$this->view->display($_tmp);
		}elseif(!empty($cache_id) && !empty($compile_id)){
			$this->view->display($_tmp, $cache_id);
		}elseif(empty($cache_id) && empty($compile_id)){
			$this->view->display($_tmp, $cache_id, $compile_id);
		}
	}
	
}