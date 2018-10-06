<?php
/* ======== 控制器基类 抽象类 =======
 * Author:Tiuon.com
 * ================================ */
namespace library;
use library\View;
abstract class Controller{
    protected $view;    // 视图实例对象
    
    // 架构函数 取得模板对象实例
    public function __construct(){
        // 控制器初始化
        if(method_exists($this,'_initialize')){
        	$this->_initialize();
        }
        // 是否已開啟Session
        if(C('SESSION_AUTO')){
        	session_start();
        }
        // 强制HTTPS
        if(C('SSL_AUTO')){
			if($_SERVER['HTTPS'] != "on") {
				$domain = $_SERVER["SERVER_NAME"];
				if(C('SSL_DOMAIN')){
					$domain = C('SSL_DOMAIN');
				}
				$url = "https://" . $domain . $_SERVER['REQUEST_URI'];
				header("location:".$url);
			}
        }
        $this->view = new View();
    }
    
    // 视图公共文件
    private function views($views = ''){
    	$this->view->views($views);
    }
    // 魔术方法__call
    public function __call($method='', $params=''){
    	$_arr = explode('&',trim($method,'&'));
        $this->view->display($_arr[0], $params);
    } 
    // 变量赋值
    protected function assign($name='',$value=''){
        $this->view->assign($name, $value);
    }
    // 显示模板
    protected function display($template='', $cache_id='', $compile_id=''){
        $this->view->display($template, $cache_id, $compile_id);
    }
    // Show方法
    protected function show($value=''){
    	if(isset($value)){
    		echo $value;
    	}
    }
    // 返回get数据
    public function get($value) {
    	$get_ = Route::param('get',$value);
    	return $get_;
    }
    // 返回post数据
    public function post($value) {
    	$post_ = Route::param('post',$value);
    	return $post_;
    }
    // 返回Json数据
    protected function ajaxReturn($_data_, $arr = array()){
    	$type   = $arr['type']?$arr['type']:C('DEFAULT_AJAX_RETURN');
    	$option = $arr['option']?$arr['option']:0;
    	$domain = $arr['domain']?$arr['domain']:0;
    	$xml =array(
    	    'root'     => 'Tiuon',
    	    'item'     => 'item',
    	    'attr'     => '',
    	    'id'       => 'id',
    	    'encoding' => 'utf-8'
    	);
    	if($arr['xml']){
    		$xml = array_merge($xml, $arr['xml']);
    	}
        if($domain){
            header('Access-Control-Allow-Origin:*');// 不跨域的时候请关闭	
        }
    	switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($_data_,$option));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset='.$xml['encoding']);
                /**
                * XML编码
                    * @param mixed $data 数据
                    * @param string $root 根节点名
                    * @param string $item 数字索引的子节点名
                    * @param string $attr 根节点属性
                    * @param string $id   数字索引子节点key转换的属性名
                    * @param string $encoding 数据编码
                    * @return string
                */
                exit(xml_encode($_data_, $xml['root'], $xml['item'], $xml['attr'], $xml['id'], $xml['encoding']));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[C('GET_JSONP_HANDLER')]) ? $_GET[C('GET_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($_data_,$option).');');  
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($_data_);
        }
    }
    // php页面跳转
    protected function redirect($url = null) {
        if($url){
            header('location:' . $url);
            exit;
        }else{
        	$this->notice('請輸入URL！');
        }
    }
    // 页面提示跳转 [ $message 操作提示信息, $url 跳转路径 ]
    protected function notice($message='操作提示为空',$url = null,$wait=30){
    	// 判斷是否數組
    	if(is_array($message)){
    		$_data_ = $message;
    	}else{
    		$_data_['message']      = $message;
	        $_data_['warning']      = 1;
	        $_data_['jump_status']  = 1;
	        $_data_['wait']         = $wait;
	        if($url) $_data_['url'] = $url;
    	}
        public_jump($_data_);
    }
    // 操作已成功 [$notice 操作提示]
    protected function success($message = '操作已成功', $url = null,$wait=30) {
    	// 判斷是否數組
    	if(is_array($message)){
    		$_data_ = $message;
    	}else{
    		$_data_['message']      = $message;
	        $_data_['status']       = 1;
	        $_data_['jump_status']  = 1;
	        $_data_['code']         = 1;
	        $_data_['wait']         = $wait;
	        if($url) $_data_['url'] = $url;
    	}
        public_jump($_data_);
    }
    // 操作失败 [$notice 操作提示]
    protected function error($notice = '操作失败', $url = null,$wait=30) {
    	// 判斷是否數組
    	if(is_array($message)){
    		$_data_ = $message;
    	}else{
    		$_data_['message']      = $message;
	        $_data_['status']       = 0;
	        $_data_['jump_status']  = 1;
	        $_data_['code']         = 0;
	        $_data_['wait']         = $wait;
	        if($url) $_data_['url'] = $url;
    	}
        public_jump($_data_);
    }
}