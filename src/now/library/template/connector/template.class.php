<?php
/* ====== 模板引擎类 =======
 * Author:Tiuon.com
 * ======================== */
namespace library\template\connector;
class template{
	private $config;		// 配置信息
	private $label=array(); // 键值\变量
	private $my_file;		// 自定义模板路径
	private $template;	// 模板全路径
	private static $obj;	// 
	
	// 构造函数
	public function __construct(){
    	// 配置
    	// 是否使用缓存，项目调试期间，不建议启用缓存
		$this->config['CACHE_AUTO']		= C('TMP_CACHE_AUTO');
		// 缓存生命周期
		$this->config['CACHE_TIME']	= 0;
		if(!NOW_DEBUG){
			$this->config['CACHE_TIME']	= C('TMP_CACHE_TIME');
		}
		// 模板目录
		$this->config['VIEWS']			= C('TMP_VIEWS');
		// 默认模板缓存后缀
		$this->config['CACHFILE_SUFFIX']	= C('TMP_CACHFILE_SUFFIX');
		// 模板后缀
		$this->config['SUFFIX']			= C('TMP_SUFFIX');
		// 设置编译文件的静态后缀
		$this->config['STATICSUFFIX']	= C('TMP_STATIC_SUFFIX');
		// 模板字符串轉換為正则表
		$this->config['STRING_CANONICAL_TABLE'] = array(
			"(" => C('TMP_L_DELIM'),
			")" => C('TMP_R_DELIM'),
			"%%"=> "(.+?)",
			"&" => "(|\s)",
			"#" => "[\\'\"]",
			"~" => "\\",
			"^" => "/"
		);
		// 编译目录                                           
		$this->config['PHP_FILE']  = str_replace(array('\\','/'), DS, RUNTIME_PATH . C('TMP_CACHE_PATH') . DS . MODULE_NAME . DS);
		// 静态文件目录
		$this->config['HTML_FILE'] = str_replace(array('\\','/'), DS, RUNTIME_PATH . C('TMP_CACHE_HTML') . DS . MODULE_NAME . DS);
    	
	}
	// 分配模板变量
	public function assign($name, $value){
		if($name && $value){
			$this->label[$name] = $value;
		}
	}
	// 渲染模板
	public function display($file, $params){
		// 检查模板名是否不存在
		if(isset($file) && !empty($file)){
			$file_arr = explode('/',trim($file,"/"));
			$count = count($file_arr);
			if($count > 1){
				$this->my_file = $file;
			}else{
				$this->my_file = CONTROLLER_NAME . DS . $file;
			}
		}else{
			$this->my_file = CONTROLLER_NAME . DS . ACTION_NAME;
		}
		$_tmp = str_replace(array('\\','/'), DS, APP_PATH . MODULE_NAME . DS . $this->config['VIEWS'] . DS . $this->my_file. '.' . $this->config['SUFFIX']);
		$this->template = $_tmp;
		//  检查模板文件是否存在 
		if(file_exists($this->template)){
			// 将键值\赋值给变量
			extract($this->label);
			// 判斷是否開啟靜態模式
			if($this->config['CACHE_AUTO']){
				// 文件上次Html创建或者修改的时间
				$outtimeHtml = time()-@filemtime($this->get_html_file());
				// 检查编译文件是否存在/编译文件是否过期
				if(!file_exists($this->get_html_file()) || $outtimeHtml > $this->config['CACHE_TIME']){
					// 開啟编译
					$this->public_compile();
					$this->public_compile_html();
				}else{
					require_once $this->get_html_file();
				}
			} else {
				// 獲取上次PHP创建或者修改的时间
				$outtimePHP = time()-@filemtime($this->get_php_file());
				// 检查编译文件是否存在/编译文件是否过期
				if(!file_exists($this->get_php_file()) || $outtimePHP > $this->config['CACHE_TIME']){
					// 開啟编译
					$this->public_compile();
				}
				require_once $this->get_php_file();
			}
		} else {
			$this->show_error('模板錯誤','对不起，找不到模板:' . $this->template);
		}
	}
	// 删除过期编译文件
	public function del_old_file(){
		$file['php']  = $this->get_php_file();
		$file['html'] = $this->get_html_file();
		foreach($file as $key => $value){
			unlink($value);
		}
	}
	// 获取php模板文件路径
	public function get_php_file(){
		// 判断编译文件名和目录是否存在
		make_dir($this->config['PHP_FILE']);
		$php_file = $this->config['PHP_FILE'] . md5($this->my_file) . '.' . $this->config['CACHFILE_SUFFIX'];
		return $php_file;
	}
	// 获取html模板文件路径
	public function get_html_file(){
		// 判断编译文件名和目录是否存在
		make_dir($this->config['HTML_FILE']);
		$html_file = $this->config['HTML_FILE'] . md5($this->my_file) . '.' . $this->config['STATICSUFFIX'];
		return $html_file;
	}
	// 模板正则替换 {$data}
	public function tmp_replace($str){
		$replace_arr = array(
			'(&~$%%&)'                              => '<?php echo $\2; ?>',
			'(&~:%%&)'                              => '<?php echo \2; ?>',
			'(&foreach&name&=&#%%#&id&=&#%%#&*?)'   => '<?php if(is_array($\5)): foreach($\5 as $key=>$\9): ?>',
			'(&volist&name&=&#%%#&id&=&#%%#&*?)'    => '<?php if(is_array($\5)): foreach($\5 as $key=>$\9): ?>',
			'(&if&condition&=&#%%#&*?)'             => '<?php if(\5): ?>',
			'(&empty&name&=&#%%#&*?)'               => '<?php if(empty($\5)): ?>',
			'(&notempty&name&=&#%%#&*?)'            => '<?php if(!empty($\5)): ?>',
			'(&~^if&)'                              => '<?php endif; ?>',
			'(&else~^&)'                            => '<?php else: ?>',
			'(&elseif&condition&=&#%%#&*?)'         => '<?php elseif(\5): ?>',
			'(&~^empty&)'                           => '<?php endif; ?>',
			'(&~^notempty&)'                        => '<?php endif; ?>',
			'(&~^foreach&)'                         => '<?php endforeach; endif; ?>',
			'(&~^volist&)'                          => '<?php endforeach; endif; ?>'
		);
		// 解析模板中的表达式
		$comp_arr = array(' nheq '=>' !== ',' heq '=>' === ',' neq '=>' != ',' eq '=>' == ',' egt '=>' >= ',' gt '=>' > ',' elt '=>' <= ',' lt '=>' < ');
		$str = str_ireplace(array_keys($comp_arr),array_values($comp_arr),$str);
		// 标签
		if(is_array($replace_arr)){
			foreach($replace_arr as $key => $val){
				$replaceDB = $this->replace_regular($key);
				$str = preg_replace($replaceDB, $val, $str);
			}
		}
		// 表达式标签
		if(is_array($comp_arr)){
			foreach($comp_arr as $key => $value){
				$keys    = str_replace(' ','',$key);
				$vals    = str_replace(' ','',$value);
				$exp_key = $this->replace_regular('('.$keys.'&name&=#%%#&value&=#%%#&*?)');
				$exp_end = $this->replace_regular('(&~^' . $keys . '&)');
				$str     = preg_replace($exp_key,'<?php if( $\3 ' . $vals . ' \6): ?>',$str);	
				$str     = preg_replace($exp_end,'<?php endif; ?>',$str);
			}
		}
		// 解析模板中的布局标签
		$replaceVIEWSarr = array(
			"(&=&#%%#&=&)",
			"(&include&file&=&#%%#&*?)"
		);
		
		// 布局标签
		if(is_array($replaceVIEWSarr)){
			foreach($replaceVIEWSarr as $keyt => $valt){
				$regular[$keyt] = $this->replace_regular($valt);
				$str = preg_replace_callback($regular[$keyt],function($string){
					if(strpos($string[0],'include')){
						$_arr = str_replace(array('include','{','}','\'','"'),'',$string[0]);
						$_arr = explode(' ',trim($_arr,' '));
						foreach($_arr as $kt=>$vt){
							$vt = explode('=',trim($vt,'='));
							$_data[$vt[0]] = $vt[1];
						}
						$re_str = $_data;
					}else{
						$re_str = str_replace(array(' ','{','}','=','"','\''),'',$_data_[0]);
					}
					return $this->get_compile($re_str); 
				},$str);
			};
		}
		return $str;
	}
	
	// 字符串替換正则表达式
	public function replace_regular($str){
		$sct = $this->config['STRING_CANONICAL_TABLE'];
		$num=0;
		foreach($sct as $k => $v){
			if(0 == $num){
				$arr = $str;
			} else {
				$arr = $val;
			}
			$val = str_replace($k,$v,$arr);
			$num++;
		}
		return "/" . $val . "/is";
	}
	
	// 公共视图文件
	public function get_compile($val = null){
		$content = null;
		if($val){
			if(is_array($val)){
				$path = str_replace(array('\\','/'), DS, APP_PATH . MODULE_NAME . DS . $this->config['VIEWS'] . DS . $val['file']);
			}else{
				$path = str_replace(array('\\','/'), DS, APP_PATH . MODULE_NAME . DS . $this->config['VIEWS'] . DS . $val);
			}
			$arr = $this->public_compile($path);
			$content = $arr['tmp'];
		}else{
			$this->show_error('公共模板錯誤','对不起，找不到公共模板:' . $path);
		}
		return $content;
	}
	
	// 模板编译
	public function public_compile($path=null){
		if(!$path){
			$path = $this->template;
		}
		// 模板文件未编译前所有内容
		$content = file_get_contents($path);   
		// 模板文件经过解析后内容
		$tmp = $this->tmp_replace($content);
		//开始编译
		$byte = file_put_contents($this->get_php_file(),$tmp);
		if($byte < 0){
			$this->show_error('模板錯誤','对不起，文件 ' . $this->get_php_file() . '编译失败！');
		}else{
			$arr = array('byte' => $byte,'tmp'  => $tmp);
			return $arr;
		}
	}
	
	// 靜態模板编译
	public function public_compile_html(){
		// 打开缓冲区 
		ob_start();
		// 将键值\赋值给变量
		extract($this->label);
		require_once $this->get_php_file();
		$tmp = ob_get_contents();
		// 开始编译
		$byte = file_put_contents($this->get_html_file(),$tmp);
		if($byte < 0){
			$this->show_error('模板錯誤','对不起，文件 '.$this->get_html_file().'编译失败！');
		}else{
			$arr = array('byte' => $byte,'tmp'  => $tmp);
			return $arr;
		}
		// 清空先前输出
		ob_clean();
	}
	
	// 输出錯誤语句
	public function show_error($message = "", $arr = "") {
		if($arr){
			$html  = "<div style='background-color:#f5c10c;border-radius:3px;padding:10px 10px 10px 10px;'>";
			$html .= "<div style='color:#f22424;'>[ {$message} ]</div>";
			$html .= "<div>{$arr}</div>";
			$html .= "</div>";
			E(array('html'=>$html));
		}else{
			E($message . $arr);
		}
	}
	
}