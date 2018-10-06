<?php
//----------------------------------
// 公共函数库
//----------------------------------

	/**
	 * 格式化输出数组
	 * @param Array $array 数组或对象
	 * @return void
	 */
	function P($array){
		if(is_bool($array)){
			var_dump($array);
	    } else if(is_null($array)) {
		    var_dump(NULL);
	    } else {
		    echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>". print_r($array, true) ."</pre><br>";
	    }
	}
	
	/**
	 * 取文件配置项
	 * @param String $name 要取的配置文件名前缀
	 * @param String $key 要取的值
	 * @return mixed
	 */
	function C($name,$key=null){
		// 核心配置文件
		$s_path = str_replace(array('\\','/'), DS, NOW_PATH . 'config/convention' . CONF_EXT);
		// 应用公共配置文件
		$c_path = str_replace(array('\\','/'), DS, APP_CONF_PATH . 'config' . CONF_EXT);
		// 模块应用配置文件
		if('MODULE_NAME' !== MODULE_NAME){
			$a_path = str_replace(array('\\','/'), DS, APP_PATH . strtolower(MODULE_NAME) . '/common/config/config' . CONF_EXT);
		}
		// 尝试获取配置数组
		$conf = array();
		if(is_file($s_path)){
			$conf = include($s_path);
		}
		if(is_file($c_path)){
			$c_conf = include($c_path);
		}
		if(is_file($a_path)){
			$a_conf = include($a_path);
		}
		// 判断应用公共配置文件是否存在
	    if(is_array($c_conf)){
	    	// 项目配置覆盖系统默认配置（分组配置是在解析分组后执行覆盖）
	    	$conf = array_merge($conf, $c_conf);
	    }
	    // 判断模块应用配置文件是否存在
	    if(is_array($a_path)){
		    // 项目配置覆盖系统默认配置（分组配置是在解析分组后执行覆盖）
		    $conf = array_merge($conf, $a_conf);
	    }
	    if(isset($key)){
	    	$data = $conf[$name][$key];
	    }else{
	    	$data = $conf[$name];
	    }
	    return $data;
	}
	
	// 语言配置文件
	function L($name =null, $value =null) {
	    // 无参数时返回Null
	    if(is_null($name)){
	    	return null;
	    }
	    // 检查是否在数组中，防止出现任意文件包含漏洞
	    $get = 'get.'.C('VAR_LANGUAGE');
	    $get = I($get);
	    if(isset($get)){
	    	$langs = explode(',',trim(C('LANG_LIST'),","));
			if(in_array($get, $langs)){
			    session(array("name"=>"DEFAULT_LANG","value" => $get));
			}
		}
	    // 判斷session數據是否存在
	    $language = session('DEFAULT_LANG')?strtolower(session('DEFAULT_LANG')). '.php':strtolower(C('DEFAULT_LANG')). '.php';
		// 核心語言配置文件
		$s_lang = str_replace(array('\\','/'), DS, RES_PATH . 'lang/' . $language);
		// 模块应用語言配置文件
		$a_lang = null;
		if('MODULE_NAME' !== MODULE_NAME){
			$a_lang = str_replace(array('\\','/'), DS, APP_PATH . strtolower(MODULE_NAME) . '/common/lang/' . $language);
		}
		// 应用公共語言配置文件
		$c_lang = str_replace(array('\\','/'), DS, CONF_PATH . C('DEFAULT_C_COMMON') . DS . C('DEFAULT_LANG_PATH') . DS . $language);
		// 系统默认配置
	    $lang  = include($s_lang);
	    if(is_file($a_lang)){
	    	$a_lang = include($a_lang);
	    }
	    if(is_file($c_lang)){
	    	$c_lang = include($c_lang);
	    }
	    if(is_array($a_lang) && $a_lang[0]){
	    	$lang  = array_merge($lang, $a_lang); 
	    }elseif(is_array($c_lang) && $c_lang[0]){
	    	$lang  = array_merge($lang, $c_lang); 
	    }                                   
	    // 优先执行设置获取或赋值
	    $data = isset($value)?$lang[$name][$value]:$lang[$name];
	    return $data;
	}
	
	// URL方法
	function U($path = null,$get = array()){
		$array['ru']		= $_SERVER['REQUEST_URI'];
		$array['qs']		= $_SERVER['QUERY_STRING'];
		$array['depr']		= C('URL_PATHINFO_DEPR');
		$array['get_']		= array('?'.C('GET_MODULE').'=','&'.C('GET_CONTROLLER').'=', '&'.C('GET_ACTION').'=');
		$array['get']		= array(C('GET_MODULE'),C('GET_CONTROLLER'),C('GET_ACTION'));
		$array['path']		= explode('/',trim($path,"/"));
	    $array['path_num']	= count($array['path']);
		if($array['qs']){
			$c_qs = str_replace(array('s=','S='),'',$array['qs']);
			$_arr  = explode('/',trim($c_qs,'/'));
		}elseif($array['ru']){
			$_arr  = explode('/',trim($array['ru'],'/'));
		}
		$_arr = str_replace('.'.C('URL_HTML_SUFFIX'),'',$_arr);
		// 判断数组0是否存在特定的函数，存在即删除特定函数
		if(strstr($_arr[0], '.php')){
			$array['index'] = $_arr[0];
			// 删除数组的第一个元素
			array_shift($_arr);
		}
			
	    // URL映射
		if(C('URL_ROUTER_ON')){
			$routes  = C('URL_MAP_RULES');
			foreach ($routes as $key => $value){
				$key_ = explode('/',trim($key,'/'));
				$val_ = explode('/',trim($value,'/'));
				if(is_array($key_) && $key_[1]){
					if($key_[0] == $_arr[0]){
						$c['m'] = $val_[0];
					}
					if($key_[1] == $_arr[1]){
						$c['c'] = $val_[1];
					}
					if($key_[2] == $_arr[2]){
						$c['a'] = $val_[2];
					}
				} else {
					if($key_[0] == $_arr[0]){
						$c['m'] = $val_[0];
					}
				}
			}
			$get_path['m'] = !empty($c['m'])?strtolower($c['m']):strtolower(C('MODULE'));
			$get_path['c'] = !empty($c['c'])?strtolower($c['c']):strtolower(C('CONTROLLER'));
			$get_path['a'] = !empty($c['a'])?$c['a']:C('ACTION');
		}else{
			$get_path['m'] = !empty($_arr[0])?strtolower($_arr[0]):strtolower(C('MODULE'));
			$get_path['c'] = !empty($_arr[1])?strtolower($_arr[1]):strtolower(C('CONTROLLER'));
			$get_path['a'] = !empty($_arr[2])?$_arr[2]:C('ACTION');
		}
		
	    // 判断路径第一个字符是否指定字符
	    if(substr($path , 0 , 1) == '/'){
	    	switch($array['path_num']){
				case 1:
					$paths[0] = strtolower($array['path'][0]);
			    	$paths[1] = $get_path['c'];
			    	$paths[2] = $get_path['a'];
				break;
				case 2:
			    	$paths[0] = strtolower($array['path'][0]);
			    	$paths[1] = strtolower($array['path'][1]);
			    	$paths[2] = $get_path['a'];
				break;
				case 3:
					$paths[0] = strtolower($array['path'][0]);
			    	$paths[1] = strtolower($array['path'][1]);
			    	$paths[2] = $array['path'][2];
				break;
			}
	    }else{
	    	switch($array['path_num']){
				case 1:
					$paths[0] = $get_path['m'];
			    	$paths[1] = $get_path['c'];
			    	$paths[2] = $array['path'][0];
				break;
				case 2:
					$paths[0] = $get_path['m'];
			    	$paths[1] = strtolower($array['path'][0]);
			    	$paths[2] = $array['path'][1];
				break;
				case 3:
					$paths[0] = strtolower($array['path'][0]);
			    	$paths[1] = strtolower($array['path'][1]);
			    	$paths[2] = $array['path'][2];
				break;
			}
	    }
	    // get 参数
	    $get = http_build_query($get);
		// 判断当前Url模式
		
		if(empty($array['qs'][0]) || $array['qs'][0] == 's' || in_array($array['qs'][0],$array['get']) == false){
			// Pathinfo模式
	    	if($get){
				$get = str_replace(['=','&'],"/",$get);
				$is_get = $array['depr'].$get;
			}
			if($array['index']){
		    	$is_index = $array['index'].$array['depr'];
		    }
			$data = is_domain().$array['depr'].$is_index.$paths[0].$array['depr'].$paths[1].$array['depr'].$paths[2].$is_get.'.'.C('URL_HTML_SUFFIX');
		}else{
			// 普通模式
			if($get){
				$is_get = '&'.$get;
			}
			if($array['index']){
		    	$is_index = $array['index'];
		    }
			$data = is_domain().'/'.$is_index.$array['get_'][0].$paths[0].$array['get_'][1].$paths[1].$array['get_'][2].$paths[2].$is_get;
		}
		// 返回url
	    return $data;
	}
	
	/**
	 * 获取输入参数 支持过滤和默认值
	 * 使用方法:
	 * <code>
	 * I('id',0); 获取id参数 自动判断get或者post
	 * I('post.name','','htmlspecialchars'); 获取$_POST['name']
	 * I('get.'); 获取$_GET
	 * </code>
	 * @param string $name 变量的名称 支持指定类型
	 * @param mixed $default 不存在的时候默认值
	 * @param mixed $filter 参数过滤方法
	 * @param mixed $datas 要获取的额外数据源
	 * @return mixed
	 */
	function I($name, $default='', $filter=null, $datas=null) {
		static $_PUT	=	null;
		if(strpos($name,'/')){ // 指定修饰符
			list($name,$type) 	=	explode('/',$name,2);
		}elseif(true){ // 默认强制转换为字符串
	        $type   =   's';
	    }
	    if(strpos($name,'.')) { // 指定参数来源
	        list($method,$name) =   explode('.',$name,2);
	    }else{ // 默认为自动判断
	        $method =   'param';
	    }
	    switch(strtolower($method)) {
	        case 'get':   
	        	$input =& $_GET;
	        	break;
	        case 'post':   
	        	$input =& $_POST;
	        	break;
	        case 'put':   
	        	if(is_null($_PUT)){
	            	parse_str(file_get_contents('php://input'), $_PUT);
	        	}
	        	$input 	=	$_PUT;        
	        	break;
	        case 'param':
	            switch($_SERVER['REQUEST_METHOD']) {
	                case 'POST':
	                    $input  =  $_POST;
	                    break;
	                case 'PUT':
	                	if(is_null($_PUT)){
	                    	parse_str(file_get_contents('php://input'), $_PUT);
	                	}
	                	$input 	=	$_PUT;
	                    break;
	                default:
	                    $input  =  $_GET;
	            }
	            break;
	        case 'path':   
	            $input  =   array();
	            if(!empty($_SERVER['PATH_INFO'])){
	                $depr   =   '-';
	                $input  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));            
	            }
	            break;
	        case 'request' :   
	        	$input =& $_REQUEST;   
	        	break;
	        case 'session' :   
	        	$input =& $_SESSION;   
	        	break;
	        case 'cookie'  :   
	        	$input =& $_COOKIE;    
	        	break;
	        case 'server'  :   
	        	$input =& $_SERVER;    
	        	break;
	        case 'globals' :   
	        	$input =& $GLOBALS;    
	        	break;
	        case 'data'    :   
	        	$input =& $datas;      
	        	break;
	        default:
	            return null;
	    }
	    // 获取全部变量
	    if(''==$name) {
	        $data       =   $input;
	        $filters    =   isset($filter)?$filter:'htmlspecialchars';
	        if($filters) {
	            if(is_string($filters)){
	                $filters = explode(',',$filters);
	            }
	            foreach($filters as $filter){
	            	// 参数过滤
	                $data = array_map_recursive($filter,$data);
	            }
	        }
	        // 取值操作
	    }elseif(isset($input[$name])) {
	        $data       =   $input[$name];
	        $filters    =   isset($filter)?$filter:'htmlspecialchars';
	        if($filters) {
	            if(is_string($filters)){
	                if(0 === strpos($filters,'/')){
	                    if(1 !== preg_match($filters,(string)$data)){
	                        // 支持正则验证
	                        return isset($default) ? $default : null;
	                    }
	                }else{
	                    $filters = explode(',',$filters);                    
	                }
	            }elseif(is_int($filters)){
	                $filters = array($filters);
	            }
	            
	            if(is_array($filters)){
	                foreach($filters as $filter){
	                    if(function_exists($filter)) {
	                        $data = is_array($data) ? array_map_recursive($filter,$data) : $filter($data); // 参数过滤
	                    }else{
	                        $data = filter_var($data,is_int($filter) ? $filter : filter_id($filter));
	                        if(false === $data) {
	                            return isset($default) ? $default : null;
	                        }
	                    }
	                }
	            }
	        }
	        if(!empty($type)){
	        	switch(strtolower($type)){
	        		case 'a':	// 数组
	        			$data 	=	(array)$data;
	        			break;
	        		case 'd':	// 数字
	        			$data 	=	(int)$data;
	        			break;
	        		case 'f':	// 浮点
	        			$data 	=	(float)$data;
	        			break;
	        		case 'b':	// 布尔
	        			$data 	=	(boolean)$data;
	        			break;
	                case 's':   // 字符串
	                default:
	                    $data   =   (string)$data;
	        	}
	        }
	    }else{ // 变量默认值
	        $data = isset($default)?$default:null;
	    }
	    is_array($data) && array_walk_recursive($data,'my_filter');
	    return $data;
	}
	
	/**
	 * 创建数据库对象
	 * @param String $table 要操作的表名
	 */
	function DB($table){
	  	$Conn = new library\Db($table);
	    return $Conn;
	}
	
	// 实例化模型类
	function M($name='') {
	    $file = str_replace(array('\\','/'), DS, APP_PATH . MODULE_NAME . '/model/' . $name . 'Model.class.php');
	    if(is_file($file)){
	    	$model = require_cache($file);
	    	$class = '\\' . MODULE_NAME . '\\model\\' . $name . 'Model';
	    	$obj = new $class();
	    }
	    return $obj;
	}
	
	// 抛出异常处理 
	function E($err=null,$title=null){
		if(NOW_DEBUG){
			$title = isset($title)?$title:L('_ABNORMAL_INFORMATION_');
			$html  = "<div style='background-color:#f5c10c;border-radius:3px;padding:10px 10px 10px 10px;'>";
		    $html .= "<div style='color:#f22424;'>[ {$title} ]</div>";
		    $html .= "<div>{$err}</div>";
		    $html .= "</div>";
		    $data = $html;
		    if(is_array($err)){
		    	$data = $err['html'];
		    }
			$class = 'library\\Debug';
			$class::showMsg($data);
		} else {
			$file_404 = str_replace(array('\\','/'), DS, C('TMP_ERROR_404'));
			include($file_404);
	        exit;
		}
	}
	
	// 公共跳转
    function public_jump($prompt=null){
        $file_jump = str_replace(array('\\','/'), DS, C('TMP_ERROR_ACCESS'));
        if(is_file($file_jump)){
        	require_once($file_jump);
            exit;
        }else{
        	E($prompt['message']);
        }
    }

	/**
	 * 发起GET请求
	 * @param String $url 目标网填，带http://
	 * @return bool
	 */
	function httpGet($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 6);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array('Accept-Encoding: gzip, deflate'));
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 3);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	
	//发起POST请求
	function httpPost($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	/**
	 * 获取客户端IP地址
	 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
	 * @return mixed
	 */
	function get_client_ip($type = 0) {
	    $type       =  $type ? 1 : 0;
	    static $ip  =   NULL;
	    if ($ip !== NULL) return $ip[$type];
	    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	        $pos    =   array_search('unknown',$arr);
	        if(false !== $pos) unset($arr[$pos]);
	        $ip     =   trim($arr[0]);
	    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
	        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
	    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
	        $ip     =   $_SERVER['REMOTE_ADDR'];
	    }
	    // IP地址合法验证
	    $long = sprintf("%u",ip2long($ip));
	    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	    return $ip[$type];
	}
	
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
	function xml_encode($data, $root='data', $item='item', $attr='', $id='id', $encoding='utf-8') {
	    if(is_array($attr)){
	        $_attr = array();
	        foreach ($attr as $key => $value) {
	            $_attr[] = "{$key}=\"{$value}\"";
	        }
	        $attr = implode(' ', $_attr);
	    }
	    $attr   = trim($attr);
	    $attr   = empty($attr) ? '' : " {$attr}";
	    $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
	    $xml   .= "<{$root}{$attr}>";
	    $xml   .= data_to_xml($data, $item, $id);
	    $xml   .= "</{$root}>";
	    return $xml;
	}
	
	/**
	 * 数据XML编码
	 * @param mixed  $data 数据
	 * @param string $item 数字索引时的节点名称
	 * @param string $id   数字索引key转换为的属性名
	 * @return string
	 */
	function data_to_xml($data, $item='item', $id='id') {
	    $xml = $attr = '';
	    foreach ($data as $key => $val) {
	        if(is_numeric($key)){
	            $id && $attr = " {$id}=\"{$key}\"";
	            $key  = $item;
	        }
	        $xml    .=  "<{$key}{$attr}>";
	        $xml    .=  (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
	        $xml    .=  "</{$key}>";
	    }
	    return $xml;
	}
	
	// 将XML中的数据,转换为json/数组对象
	function xml_to_arr($file){
		if($file){
		    $objectxml		= simplexml_load_file($file); //将XML中的数据,读取到数组对象中
		    $data['json']	= json_encode($objectxml );//将对象转换个JSON
		    $data['array']	= json_decode($data['json'],true);//将json转换成数组
		    return $data;
		}
		return null;
	}

	// 返回Json数据
    function ajaxReturn($_data_, $arr = array()){
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
    
	/**
	 * 加密解密
	 * @param String $string 欲处理的文本
	 * @param String $operation 选项，DECODE.解密   ENCODE.加密
	 * @param String $key 密码文本
	 * @param Int $expiry 到期时间(秒)
	 * @return String 返回处理后的文本
	 */
	function RCode($string, $operation, $key = 'a002601', $expiry = 0) { 
		$ckey_length	= 0;
		$key			= md5($key ? $key : md5(AUTHKEY.$_SERVER['HTTP_USER_AGENT']));
		$keya			= md5(substr($key, 0, 16));
		$keyb			= md5(substr($key, 16, 16));
		$keyc			= $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : ''; $cryptkey = $keya.md5($keya.$keyc);
		$key_length		= strlen($cryptkey);
		$string			= $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length	= strlen($string);
		$result			= '';
		$box			= range(0, 255);
		$rndkey			= [];
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			   return substr($result, 26);
			} else {
			   return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}
	
	// ==================== 异位或加密字符串 =========================
	// $string [需要加密的字符串]
	// $operation 选项，DECODE.解密   ENCODE.加密
	// $key 密码文本
	// $expiry 到期时间(秒)
	// encryption('it@kenson.com.cn|10|alipay|1533752180','ENCODE','Rabbitunicom',60)
	// encryption('AwAKUlMGVFcPXF4YCQ1McVlTXkoKDU0HCQweW1YaU1ZOVFUIFFIYHghRUQYAUQoACgY','DECODE','Rabbitunicom')
	// ============================================================
	function encryption($string, $operation='ENCODE', $key, $expiry=0){
		$key = md5($key?$key:C('ENCTYPTION_KEY'));
		$keylength = strlen($key);
		$coded = '';
		switch (strtoupper($operation)){
            case 'ENCODE':
	            //if(strlen($string)>=32){
					//return '字符串不能大于32位字符!';
				//}
				$string = sprintf('%010d', $expiry ? $expiry + time() : 0)."<->".$string;
		        $leng	= strlen($string);
		        for($i	= 0, $count = $leng; $i < $count; $i += $keylength) {
		            $coded .= substr($string, $i, $keylength) ^ $key;
		        }
		        return str_replace('=', '', base64_encode($coded));
				//unset($string,$key,$coded,$keylength,$leng);
            break;
            case 'DECODE':
		        $string = base64_decode($string);
		        for ($i = 0, $count = strlen($string); $i < $count; $i += $keylength){
		            $coded .= substr($string, $i, $keylength) ^ $key;
		        }
		        $arr = explode("<->",trim($coded,"<->"));
		        if($arr[0] != 0000000000){
		        	if($arr[0] >= time()){
		        		return $arr[1];
		        	}
		        	return null;
		        }else{
		        	return $arr[1];
		        }	
				//unset($string,$key,$keylength);
            break;
       }
		return null;
	}
	

	
	// 密码加密
	function passencryption($value){
	  	$key	= md5(C('ENCTYPTION_KEY'));
	  	$base	= base64_encode($key.'_'.md5($value));
	  	$data	= sha1($base);
	  	return $data;
	}
	
	// ==================== 随机码 =================================
	// $num    [随机码位数]
	// $array  [数组]
	// ============================================================
	function getRandomCode($num=16, $array=null){
		$tmpstr = "";
		
		if(is_array($array)){
			$data = $array;
		}else{
			$data = array(
		    	'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
		    	'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
		    	'0','1','2','3','4','5','6','7','8','9'
			);
		}
		$max = count($data);
		for ($i = 0; $i < $num; $i++){
			$key = rand(0,$max-1);
			$tmpstr .= $data[$key];
		}
		return $tmpstr;
	}

	/**
	* Session操作
	* @param session名称 $name
	* @param session值 $value
	* @param session过期时间 $second
	*/
	function session($name = null,$value='tk-no',$second=0){
		
	  	if(is_array($name)){
	  		$arr['name']   = $name['name']?$name['name']:null;
	  		$arr['value']  = $name['value']?$name['value']:'tk-no';
	  		if($second) {
	  			$arr['second'] = $name['second'];
	  		}
	  	}else{
	  		$arr['name']   = $name;
	  		$arr['value']  = $value;
	  		if($second){
	  			$arr['second'] = $second;
	  		}
	  	}
	  	
	  	require_cache(LIB_PATH.'Session'.EXT);
		$class = 'library\\Session';
	  	$class::start($arr);
	  	
		if($arr['name']){
			// 设置
			if($arr['value'] !== 'tk-no'){
				return $class::set($arr['name'],$arr['value']);
			}else{
				if($arr['value'] == 'tk-no'){
					return $class::get($arr['name']);
				}
				return $class::set($arr['name'],$arr['value']);
			}
		} else {
			// 删除所有
			return $class::del();
		}
		// 查看所有session列表
		if($arr['all']){
			return $class::get();
		}
		return null;
	}
	
	// ==================== 引入第三方类 =======================
	// $class	[是路径与第三方类名] 
	// $baseUrl	[起始路径]
	// $ext		[导入的文件扩展名] 默认是 .class.php
	// 使用格式为     
	//			docker('org.test.test');		//	框架第三方类文件夹	/new/resources/org/
	//			docker('vendor.test.test#info');//	框架第三方类文件夹	/new/resources/vendor/
	//			docker('@.test.test');		//	模块第三方类文件夹	/app/当前模块/vendor/
	//			docker('*.test.test');		//	根目录第三方类文件夹	/vendor/
	// =======================================================
	function docker($class, $baseUrl = '', $ext=EXT){
		static $_file = array();
	    $class = str_replace(array('.', '#'), array('/', '.'), $class);
	    if(isset($_file[$class . $baseUrl])){
	    	return true;
	    } else {
	    	$_file[$class . $baseUrl] = true;
	    }
    	$class = explode('/', $class);
    	$type_	= strtoupper($class[0]);
	    if(empty($baseUrl)){
	    	if(in_array($type_,array('@','*','VENDOR','ORG','MODULE_NAME'))){
	    		// 删除数组的第一个元素
				array_shift($class);
	    		switch($type_){
			 		// 當前項目下 (./app/当前模块/vendor/要引入的文件)
					case '@':
					case 'MODULE_NAME':
						$MODULE = MODULE_NAME;
					    if('MODULE_NAME' == MODULE_NAME){
					    	$MODULE = C('MODULE').DS;
					    }
					    $baseUrl = APP_PATH . $MODULE . 'vendor';
				    break;
				    // 框架目錄下 (./tiuon/resources/vendor/要引入的文件)
				    case 'VENDOR':
					    $baseUrl = VENDOR_PATH;
					break;
					// 框架目錄下 (./tiuon/resources/org/要引入的文件)
				    case 'ORG':
					    $baseUrl = RES_PATH . 'org';
					break;
					// 根目錄下 (./vendor/要引入的文件)
					case '*':
					    $baseUrl = ROOT_PATH.'vendor';
					break;
				}
	        }
	    }
	    $baseUrl = str_replace(array('\\','/'), DS, $baseUrl);
	    if(substr($baseUrl, -1) != DS){
	    	$baseUrl    .= DS;
	    }
	    $class = implode(DS, $class);
	    $classfile = $baseUrl . $class . $ext;
	    //p($classfile);
	    if(!class_exists(basename($class),false)) {
	        // 如果类不存在 则导入类库文件
	        return require_cache($classfile);
	    }
	    return null;
	}
	
	// ==================== 引入第三方类 =======================
	// $class   [是路径与第三方类名] 
	// $ext    [.php]
	// 使用格式为
	//		vendor('test.test#init','','.class.php');
	//		vendor('test.test#init');
	//		vendor('test.test#init#class#php');
	//		vendor('test.test#init#php');
	// =======================================================
	function vendor($class, $baseUrl = '', $ext='.php'){
		if(strstr(strtolower($class), '#php') && !strstr(strtolower($class), '#class#php')){
			$class = str_replace(array('#php'), '', $class);
		}
		if(strstr(strtolower($class), '#class#php')){
			$class = str_replace(array('#class#php'), '', $class);
			$ext = EXT;
		}
		if(empty($baseUrl)){
			$baseUrl = VENDOR_PATH;
		}
		
	  	docker($class, $baseUrl, $ext);
	}
	
	/**
	 * 优化的 require_once
	 * @param string $filename 文件地址
	 * @return boolean
	 */
	function require_cache($filename) {
	    static $_importFiles = array();
	    if (!isset($_importFiles[$filename])) {
	        if (file_exists_case($filename)) {
	            require($filename);
	            $_importFiles[$filename] = true;
	        } else {
	            $_importFiles[$filename] = false;
	        }
	    }
	    return $_importFiles[$filename];
	}
	
	/**
	 * 区分大小写的文件存在判断
	 * @param string $filename 文件地址
	 * @return boolean
	 */
	function file_exists_case($filename) {
	    if (is_file($filename)) {
	        if (IS_WIN && NOW_DEBUG) {
	            if (basename(realpath($filename)) != basename($filename))
	                return false;
	        }
	        return true;
	    }
	    return false;
	}
	
	/**
	* 文件大小格式化
	* @param integer $size 初始文件大小，单位为byte
	* @return array 格式化后的文件大小和单位数组，单位为byte、KB、MB、GB、TB
	*/
	function file_size_format($size=0,$dec=2){
		$unit = array("B","KB","MB","GB","TB","PB");
		$pos = 0;
		while($size >= 1024){
			$size /= 1024;
			$pos++;
		}
		$result['size'] = round($size, $dec);
		$result['unit'] = $unit[$pos];
		return $result['size'].$result['unit'];
	}
	
	// 删除空参数
	function dele_empty($parameter){
		foreach($parameter as $key => $value){
			if($parameter[$key] =='' || $parameter[$key] ==null){
				unset($parameter[$key]);
			}
		}
	}
	
    // 创建目录
	function make_dir($path,$mode=0755,$recursive=true){
		if(!file_exists($path)){
			if(!mkdir($path,$mode,$recursive)){
				return false;
			}
		}
		return true;
	}
	
	// 生成PHP文件
	function CreateFile($temp_file, $dest_file, $charset="utf-8"){
		if($temp_file && $dest_file){
		    header('content-type:text/html; charset='.$charset);//防止生成的页面乱码
		    $fp  = fopen($temp_file, "r"); //只读打开模板
		    $str = fread($fp, filesize($temp_file));//读取模板中内容
		    fclose($fp);
		    $handle = fopen($dest_file, "w"); //写入方式打开需要写入的文件
		    fwrite($handle, $str); //把刚才替换的内容写进生成的HTML文件
		    fclose($handle);//关闭打开的文件，释放文件指针和相关的缓冲区
		}
	    return false;
	}
	
	// 當前域名
	function is_domain(){
		$ssl = is_ssl()?'https://':'http://';
		$domain = $ssl . $_SERVER['HTTP_HOST'];
		return $domain;
	}
	
	// 检测链接是否是SSL连接
	function is_ssl(){
	    if(!isset($_SERVER['HTTPS']))  
	    	return false;  
	    if($_SERVER['HTTPS'] === 1){  //Apache  
	    	return true;  
	    }elseif($_SERVER['HTTPS'] === 'on'){ //IIS  
	    	return true;  
	    }elseif($_SERVER['SERVER_PORT'] == 443){ //其他  
	    	return true;  
	    }  
	    return false;  
	}

	// 新json解码
	function new_json_decode($string){
		// error handle ,错误处理
		if(!json_decode($string, true)){
	        $ret = json_last_error();
			switch($ret){
				case 0:
					$result = ['errcode'=>1,'msg'=>'返回的JSON数据：没有错误发生！'];
				break;
				case 1:
					$result = ['errcode'=>1,'msg'=>'返回的JSON数据：到达了最大堆栈深度！'];
				break;
				case 2:
					$result = ['errcode'=>1,'msg'=>'返回的JSON数据：无效或异常的 JSON！'];
				break;
				case 3:
					// 控制字符错误，可能是编码不对
					$string = preg_replace('/[\x00-\x1F]/','', $string);
					$result = json_decode($string);
				break;
				case 4:
					// 语法错误
					$string = trim($string, "\xEF\xBB\xBF");
					$result = json_decode($string, true);
				break;
				case 5:
					// 异常的 UTF-8 字符，也许是因为不正确的编码
					//$string = mb_convert_encoding($string, "utf8", "gbk");
					$string = iconv('gbk', 'utf8', $string);
					$result = json_decode($string, true);
				break;
			}
			return $result;
		}else{
			$result = json_decode($string, true);
			return $result;
		}
	}
	
	// 删除空格
	function trimall($str){
		return preg_replace('/ /','',$str);
	}
	
	// 判斷是否數字
	function is_stringNumbr($str){
	    return preg_match("/^\d*$/",$str);
	}
	
	// 防止中文乱码
	function getPrevent($str){
		$search = urldecode(iconv("gbk","utf-8",$str));
		return $search;
	}
	
	// 多个连续空格只保留一个
	function merge_spaces($string){
    	return preg_replace ("/\s(?=\s)/","\\1",trim($string));
	}
	
	// 清空文件夹函数和清空文件夹后删除空文件夹函数的处理
	function deldir($path){
		//如果是目录则继续
		if(is_dir($path)){
			//扫描一个文件夹内的所有文件夹和文件并返回数组
			$p = scandir($path);
			foreach($p as $val){
				//排除目录中的.和..
				if($val !="." && $val !=".."){
					//如果是目录则递归子目录，继续操作
					if(is_dir($path.$val)){
						//子目录中操作删除文件夹和文件
						deldir($path.$val.'/');
						//目录清空后删除空文件夹
						@rmdir($path.$val.'/');
					}else{
						//如果是文件直接删除
						unlink($path.$val);
					}
				}
			}
		}
	}