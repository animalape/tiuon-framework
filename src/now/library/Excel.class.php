<?php
/* ======== Excel类 =======
 * Author:Tiuon.com
 * ======================= */
namespace library;
class Excel{
	
	private $letter = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
        'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
        'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
        'DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ',
        'EA','EB','EC','ED','EE','EF','EG','EH','EI','EJ','EK','EL','EM','EN','EO','EP','EQ','ER','ES','ET','EU','EV','EW','EX','EY','EZ',
        'FA','FB','FC','FD','FE','FF','FG','FH','FI','FJ','FK','FL','FM','FN','FO','FP','FQ','FR','FS','FT','FU','FV','FW','FX','FY','FZ',
        'GA','GB','GC','GD','GE','GF','GG','GH','GI','GJ','GK','GL','GM','GN','GO','GP','GQ','GR','GS','GT','GU','GV','GW','GX','GY','GZ',
        'HA','HB','HC','HD','HE','HF','HG','HH','HI','HJ','HK','HL','HM','HN','HO','HP','HQ','HR','HS','HT','HU','HV','HW','HX','HY','HZ'
    );
	
	// 构造函数 
    public function __construct($conf=null){
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
	
    // 導出excel文件
    public function export($setTitle, $columName, $list, $Sheet='Sheet1'){
    	if(empty($columName)){
            public_jump(['message'=>'列名不能为空！','warning'=>1,'jump_status'=>1,'wait'=>10]);
	    }
	    if(empty($list)){
            public_jump(['message'=>'對不起，沒有導出内容！','warning'=>1,'jump_status'=>1,'wait'=>10]);
	    }
	    //if(count($list[0]) != count($columName)) {
	        //Error('列名跟数据的列不一致');
	    //}
	    $letter = $this->letter;
    	//文件名称
        $xlsTitle  = iconv('utf-8', 'gb2312', $setTitle);
        //or $xlsTitle 文件名称可根据自己情况设定
        $fileName  = $xlsTitle.date('_YmdHis');
 	    // 实例化PHPExcel类
 	    Vendor('PHPExcel.PHPExcel');
        $PHPExcel = new \PHPExcel();
	    // 获得当前sheet对象
	    $PHPSheet = $PHPExcel->getActiveSheet();
	    // 定义sheet名称（标题）
	    $PHPSheet->setTitle($Sheet);

	    // 把列名写入第1行 A1 B1 C1 ...
	    for($i=0; $i < count($columName); $i++) {
	        // $letter[$i]1 = A1 B1 C1  $letter[$i] = 列1 列2 列3
	        $PHPSheet->setCellValue("$letter[$i]1","$columName[$i]");
	        // 设置第一行背景色
	        $PHPExcel->getActiveSheet(0)->getStyle("$letter[$i]1")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f0b513');
	    }

	    //内容第2行开始
	    foreach ($list as $key => $val) {
	        // array_values 把一维数组的键转为0 1 2 3 ..
	        foreach (array_values($val) as $key2 => $val2) {
	            // $letter[$key2].($key+2) = A2 B2 C2 ……
	            $PHPSheet->setCellValue($letter[$key2].($key+2),$val2);
	        }
	    }
	    
	   

        // 生成2005版本的xls
        //header('pragma:public');
        //header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        //header("Content-Disposition:attachment;filename=".$fileName.".xls");//attachment新窗口打印inline本窗口打印
        //$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        
        // 生成2007版本的xlsx
        header('pragma:public');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename='.$fileName.'.xlsx');
        header('Cache-Control: max-age=0');
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');
        $PHPWriter->save('php://output');
        exit;
    }
    
    // 导入excel文件
    public function import($impFile, $encode = 'utf-8'){
        // 判断文件是什么格式
        $type = pathinfo($impFile); 
        $type = strtolower($type["extension"]);
        // 判断使用哪种格式
        switch($type){
			case 'csv':
			    $type = 'csv';
            break;
            case 'xls':
                $type = 'Excel5';
            break;
            case 'xlsx':
                $type = 'Excel2007';
            break;
        }
        ini_set('max_execution_time', '0');
        // 引入第三方類庫
        Vendor('PHPExcel.PHPExcel');
        $Reader        = \PHPExcel_IOFactory::createReader($type);
        $Reader->setReadDataOnly(true);
        $PHPExcel      = $Reader->load($impFile,$encode);  
        $Worksheet     = $PHPExcel->getActiveSheet();
        // 取得总行数  
        $highestRow    = $Worksheet->getHighestRow();
        // 取得总列数 
        $highestColumn = $Worksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);  
        $Data = array();
        if($highestRow){
	        for($row = 1; $row <= $highestRow; $row++) {  
	            for($col = 0; $col < $highestColumnIndex; $col++) {  
	                $Data[$row][] =(string)$Worksheet->getCellByColumnAndRow($col, $row)->getValue();  
	            }  
	        }
        }
        return $Data;
    }
    
    // Excel 日期格式轉換  $data(要轉換的數據) $format(格式：Y-m-d)
    public function ExcelToPHP($data,$format=null){
    	Docker('Vendor.PHPExcel.PHPExcel','.php');
    	$PHPExcel=new \PHPExcel();
    	$date = \PHPExcel_Shared_Date::ExcelToPHP($data);
    	if($format){
    		$date = date($format,$date);
    	}
        return $date;
    }
    
    
    
}