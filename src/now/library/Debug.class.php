<?php
/* ======== 錯誤处理类 =======
 * Author:Tiuon.com
 * ========================== */
namespace library;
class Debug{
	
		private static $starttime,$pdotime=0;
		static $errs = [];
		static $type = array (
			E_ERROR					=> '<b style="color:red;">致命错误:</b>',
			E_WARNING				=> '<b style="color:red;">运行警告:</b>',
			E_PARSE					=> '<b style="color:red;">解析错误:</b>',
			E_NOTICE				=> '<b>运行提醒:</b>',
			E_CORE_ERROR			=> '<b style="color:red;">初始化致命错误:</b>',
			E_CORE_WARNING			=> '<b>初始化警告:</b>',                  
			E_COMPILE_ERROR			=> '<b style="color:red;">编译错误:</b>',
			E_COMPILE_WARNING		=> '<b>编译警告:</b>',                  
			E_USER_ERROR			=> '<b style="color:red;">错误:</b>',
			E_USER_WARNING			=> '<b>警告:</b>',
			E_USER_NOTICE			=> '<b>提醒:</b>',
			E_STRICT				=> '<b>编码标准化警告:</b>',
			E_RECOVERABLE_ERROR		=> '<b style="color:red;">捕获致命错误:</b>',
			1100					=> '<b>文件加载:</b>',
			1101					=> '<b style="color:red;">路径错误:</b>',
			1110					=> '<b style="color:red;">SQL错误:</b>',
			1120					=> '<b>SQL查询:</b>',
			1130					=> '<b>常量:</b>',
			1140					=> '<b>模板文件:</b>',
			8192					=> '<b>运行通知:</b>',
		);
		static function start(){
			self::$starttime = microtime(true);
		}
		static function pdotime($time){
			self::$pdotime += $time;
		}
		static function fatalErrorHandler(){
			$e = error_get_last();
            switch($e['type']){
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                	if(Engine::$PDO_OBJECT){
                		Engine::$PDO_OBJECT = null;
                	}
					$showhtml  = "<div style='background-color:#f5c10c;border-radius:3px;padding:10px 10px 10px 10px;'>";
                    $showhtml .= "<div style='color:#f22424;'>[ ". self::$type[$e['type']] ." ]</div>";
                    $showhtml .= "<div>{$e['message']}[ {$e['file']} ] : {$e['line']}</div>";
                    $showhtml .= "</div>";
					self::showMsg($showhtml);
            }
		}
		static function ErrorHandler($errno, $errstr, $errfile, $errline){
			if($GLOBALS['System_Debug']){
				self::$errs[$errno][] = "{$errstr} [ ".str_replace(array('\\'.'/'), DS, $errfile)." ] : {$errline}";
			}
		}
		static function getIncludeFiles(){
			$files = get_included_files();
			foreach($files as &$v){
				$file = str_replace(array('\\','/'), DS, $v);
				self::$errs[1100][] = $file . '[ ' . file_size_format(filesize($file)) . ' ]';
			}
		}
		static function getConstants(){
			$const = get_defined_constants(true)['user'];
			foreach($const as $k=>&$v){
				$str = is_array($v) ? json_encode($v,true,JSON_UNESCAPED_UNICODE) : $v;
				self::$errs[1130][] = "[{$k}] : {$str}";
			}
		}
		static function showMsg($showhtml){
			if($GLOBALS['System_Debug']){
				$showhtml = $showhtml;
				$runtime  = microtime(true) - self::$starttime;
				$html     = $tab = '';
				self::getIncludeFiles();
				self::getConstants();
				foreach(self::$errs as $k=>&$v){
					$tab  .= '<span class="btn" id="'.$k.'">'.self::$type[$k].'['. count($v) .']</span>';
					$html .= '<div id="debug-li'.$k.'">' . implode('<br>',$v) . '</div>';
				}
				$file_debug = str_replace(array('\\','/'), DS, C('TMP_ERROR_DEBUG'));
				include $file_debug;
			}
			exit;
		}
	}
