<?php
/* ======== Alioss ========
 * Author:Tiuon.com
 * ======================== */
namespace library\Upload\Drive;
use OSS\OssClient;
use OSS\Core\OssException;
class Alioss 
{
	private $config;
    private $ossclient;
    
	// 构造函数 
    public function __construct($conf=null){
    	vendor('Alioss.autoload');
    	$this->config = C('ALIOSS_CONFIG');
    	$this->config['ALIOSS_DEL_FILES'] = C('ALIOSS_DEL_FILES');
        // 实例化oss类
		$this->ossclient = new OssClient($this->config['KEY_ID'],$this->config['KEY_SECRET'],$this->config['END_POINT']);
        if($conf){
        	$this->set($conf);
        }
    }
    
    // 设置参数
	public function set($arr){
		if(is_array($arr)){
			foreach($arr as $key => $val){
				$this->$key = $val;
			}
		}
	}
	
	// 上傳文件
	public function upload($path){
	    // 先统一去除左侧的.或者/ 再添加./
	    $oss_path = ltrim($path,'./');
	    $path     = './' . $oss_path;
	    $del_path = str_replace(array('\\','/'), DS, ROOT_PATH .$oss_path);
	    $data['err'] = 0;
	    if(file_exists($path)){
	    	try{
		        // 上传到oss
	        	$this->ossclient->uploadFile($this->config['BUCKET'],$oss_path,$path);
		        // 上传成功后删除本地的文件
		        if($this->config['ALIOSS_DEL_FILES']){
		        	@unlink($del_path);
		        }
		        $data['path']  = 'http://'.$this->config['BUCKET'].'.'.$this->config['END_POINT'].'/'.$oss_path;
		    } catch(OssException $e) {
		        //如果出错这里返回报错信息
		        $data['err'] = $e->getMessage();
		    }
	    }else{
	    	 $data['err'] = "文件不存在：{$path}";
	    }
	    return $data;
	}
	
	// 删除文件
	public function del($object) {
		$data['err'] = 0;
		try {
		 	$this->ossclient->deleteObject($this->config['BUCKET'], $object);
		} catch (OssException $e) {
		 	$data['err'] = $e->getMessage();
		}
		return $data;
	}
	
	
}