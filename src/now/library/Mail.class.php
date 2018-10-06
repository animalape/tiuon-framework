<?php
/* ======== 邮件类 =======
 * Author:Tiuon.com
 * ====================== */
namespace library;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Mail{
	// SMTP服务器设置
	private $mail;
	private $smtp_debug		= 0;		// 是否开启debug,默认为0
	private $smtp_host		= '';		// SMTP服务器地址
	private $smtp_auth		= false;	// 是否开启SMTP验证,默认为false
	private $smtp_username	= '';		// SMTP用户名（你要使用的邮件发送账号）
	private $smtp_password	= '';		// SMTP密码
	private $smtp_secure	= 'tls';	// SMTP是否加密，默认为TLS
	private $smtp_port		= 25;		// 端口,默认为25
	private $smtp_html		= true;		// 设置邮件格式为HTML,默认为true
	private $charset		= 'UTF-8';	// 设置发送的邮件的编码,默认为UTF-8
	
	// 架构函数 取得模板对象实例
    public function __construct($config){
    	vendor('PHPMailer.PHPMailer.PHPMailer');
    	vendor('PHPMailer.PHPMailer.Exception');
    	vendor('PHPMailer.PHPMailer.SMTP');
    	$this->mail = new PHPMailer(true);
    	if($config) $this->set($config);
    }
    
	/**
	* 设置参数
	* @param [string,array] $name  [description]
	* @param [type] $value [description]
	*/
	public function set($name,$value=null){
		if(is_array($name)){
			foreach($name as $k=>$v){
				$this->$k = $v;
			}
		}else{
			$this->$name = $value;
		}
	}
	
	// 发送邮件
    public function send($data){
    	if(empty($data['from'])){
    		$_data['status']	= 0;
			$_data['msg']		= '请填写发件人地址！';
    		return $_data;
    	}
    	if(empty($data['address'])){
    		$_data['status']	= 0;
			$_data['msg']		= '请填写收件人地址！';
    		return $_data;
    	}
    	if(empty($data['title'])){
    		$_data['status']	= 0;
			$_data['msg']		= '请填写邮件标题！';
    		return $_data;
    	}
    	if(empty($data['content'])){
    		$_data['status']	= 0;
			$_data['msg']		= '请填写邮件内容！';
    		return $_data;
    	}
		try {
		    // 服务器设置
		    $this->mail->SMTPDebug	= $this->smtp_debug;
		    $this->mail->isSMTP();
		    $this->mail->Host			= $this->smtp_host;
		    $this->mail->SMTPAuth		= $this->smtp_auth;
		    $this->mail->Username		= $this->smtp_username;
		    $this->mail->Password		= $this->smtp_password;
		    $this->mail->SMTPSecure		= $this->smtp_secure;
		    $this->mail->Port			= $this->smtp_port;
			$this->mail->CharSet		= $this->charset;
			
		    // 发件人
		    if($data['fromname']){
		    	$this->mail->FromName	= $data['fromname'];
		    }
		    $this->mail->From		= $data['from'];
		             
		    // 收件人                
		    if(is_array($data['address'])){
		    	foreach($data['address'] as $key => $value){
		    		$this->mail->addAddress($value);
		    	}
		    }else{
		    	$this->mail->addAddress($data['address']); 
		    }
		    
		    //$mail->addReplyTo('admin@sandboxcn.com', 'SandBoxCn');       // 回复地址
		    
		    // 附件
		    if(!empty($data['attachment'])){
		    	
		    	if(is_array($data['attachment'])){
			    	foreach($data['attachment'] as $key => $value){
			    		$value = str_replace(array('\\','/'), DS, $value);
			    		$this->mail->addAttachment($value);
			    	}
			    }else{
			    	$data['attachment'] = str_replace(array('\\','/'), DS, $data['attachment']);
			    	$this->mail->addAttachment($data['attachment']); 
			    }
			    
		    }
		    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');           // 可以设定名字
		    
		    // 内容
		    $this->mail->isHTML($this->smtp_html);
		    $this->mail->Subject = $data['title'];
		    $this->mail->Body    = $data['content'];
		    //$this->mail->AltBody = 'xxxxxx';
		    $_status = $this->mail->send();
		    $_data['status']	= $_status?1:0;
		    $_data['msg']		= $_status?'发送成功！':'发送失败！';
		    return $_data;
		} catch (Exception $e) {
			$_data['status']	= 0;
			$_data['msg']		= $this->mail->ErrorInfo;
			return $_data;
		}
    }
	
	
	
	
	
	
}