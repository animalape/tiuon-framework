<?php
/* ======== 模块基类 =======
 * Author:Tiuon.com
 * ======================== */
namespace library;
class Model{
	
    // 架构函数 取得模板对象实例
    public function __construct(){
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