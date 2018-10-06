<?php
/* ======== 日志处理类 =======
 * Author:liu21st <liu21st@gmail.com>
 * ========================== */
namespace library\log\Driver;
class File {
    protected $config;

    // 实例化并传入参数
    public function __construct($config=[]){
    	$this->config['log_time_format']	= C('LOG_TIME_FORMAT');
    	$this->config['log_file_size']		= C('LOG_FILE_SIZE');
    	$this->config['log_path']			= C('LOG_PATH');
        $this->config = array_merge($this->config,$config);
    }

    /**
     * 日志写入接口
     * @access public
     * @param string $log 日志信息
     * @param string $destination  写入目标
     * @return void
     */
    public function write($log, $destination='') {
        $now = date($this->config['log_time_format']);
        if(empty($destination)){
            $destination = $this->config['log_path'].date('y_m_d').'.log';
        }
        // 自动创建日志目录
        $log_dir = dirname($destination);
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0755, true);
        }        
        //检测日志文件大小，超过配置大小则备份日志文件重新生成
        if(is_file($destination) && floor($this->config['log_file_size']) <= filesize($destination) ){
            rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
        }
        error_log("[{$now}] ".$_SERVER['REMOTE_ADDR'].' '.$_SERVER['REQUEST_URI']."\r\n{$log}\r\n", 3,$destination);
    }
}
