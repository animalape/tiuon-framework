<?php
/* ======== 分页类 =======
 * Author:Tiuon.com
 * ====================== */
namespace library;
class Page{
    private $num;//总的文章数
    private $cnt;//每页显示的文章数
    private $curr;//当前的页码数
    private $p = 'p';//分页参数名
    private $pageCnt = 5;//分栏总共显示的页数
    private $firstRow;//每页的第一行数据
    private $pageIndex = array();//分页信息

    /**
     * 构造函数
     * @param int $num 总的文章数
     * @param int $cnt 每页显示的文章数
	 * @param int $vmget 自定义分页参数
     */
    public function __construct($num,$cnt=10,$vmget=null){
        $this->num  = $num;
        $this->cnt  = $cnt;
		if(!$vmget){
			$vmget = $_GET[$this->p]?intval($_GET[$this->p]):1;
		}
        $this->firstRow = $this->cnt * ($vmget - 1);
		$this->curr = $vmget;
        $this->getPage();
    }

    /**
     * 分页方法
     */
    private function getPage(){
        $page  = ceil($this->num / $this->cnt);                // 总的页数
        $left  = max(1,$this->curr - floor($this->pageCnt/2)); // 计算最左边页码
        $right = min($left + $this->pageCnt - 1 ,$page);       // 计算最右边页码
        $left  = max(1,$right - ($this->pageCnt - 1));         // 当前页码往右靠，需要重新计算左边页面的值
        for($i=$left;$i<=$right;$i++){
            if($i == 1){
                $name = '1';
            }else if($i == $page){
                $name = '最后一页';
            }else{
                $name = $i;
            }
            $str = $_SERVER['REQUEST_URI'];
            if(strpos($str,'?')){
            	$strs1 = '/\?p=(.*)/u';
            	$strs2 = '/\&p=(.*)/u';
            	$arr1  = preg_replace($strs1,'?p='.$i, $str);
            	$arr2  = preg_replace($strs2,'&p='.$i, $str);
            	if(preg_match($strs1,$str)){
            		$url = $arr1;
            	}elseif(preg_match($strs2,$str)){
            		$url = $arr2;
            	}else{
            		$url = $str.'&p='.$i;
            	}
    	    } else {
    	    	$url = '?'.$this->p.'='.$i;
    	    }
            $this->pageIndex[$i] = array("id"=>$i,"url"=>$url,"num"=>$this->curr,"name"=>$name);
        }
    }

    /**
     * 返回分页信息数据
     * @return [type] [description]
     */
    public function show(){
    	$show['pageIndex'] = $this->pageIndex; // 分页信息
    	$show['firstRow']  = $this->firstRow;  // 每页的第一行数据
    	$show['cnt']       = $this->cnt;       // 每页显示的文章数
    	$show['curr']      = $this->curr;      // 当前的页码数
        return $show;
    }
}