<?php
/* ======== 数据库类 =======
 * Author:Tiuon.com
 * ======================== */
namespace library;
use library\db\connector\mysql;
use library\db\connector\sqlite;
use library\db\connector\pdo;
class Db{
	protected $DB;
	// 构造函数
    public function __construct($table){
    	$type = strtolower(C('DB_TYPE'));
		$class = "library\\db\\connector\\{$type}";
		if(class_exists($class)){
			$this->DB = new $class($table);
		}else{
            // 数据库类没有定义
            Error('数据库类没有定义: ' . $class);
       } 
    }
    
    // 查询所有数据
    public function all(){
    	$all = $this->DB->all();
    	return $all;
    }
    // 查询一条
    public function one(){
    	$one = $this->DB->one();
    	return $one;
    }
    // 添加数据
    public function add($data = null){
    	$add =  $this->DB->add($data);
    	return $add;
    }
    // 修改数据
    public function save($data = null){
    	$save = $this->DB->save($data);
    	return $save;
    }
    // 删除数据
    public function del(){
    	$del = $this->DB->del();
    	return $del;
    }
    // 创建数据表
    public function createdata($name='', $value=''){
    	$createdata = $this->DB->createdata($name,$value);
    	return $createdata;
    }
    // 统计条数
    public function count($data=''){
    	$count = $this->DB->count($data);
    	return $count;
    }
    // 获取最大值
    public function max($data=''){
    	$max = $this->DB->max($data);
    	return $max;
    }
    // 获取最小值
    public function min($data=''){
    	$min = $this->DB->min($data);
    	return $min;
    }
    // 获取平均值
    public function avg($data=''){
    	$avg = $this->DB->avg($data);
    	return $avg;
    }
	// 统计字段的总和
    public function sum($data=''){
		$sum = $this->DB->sum($data);
		return $sum;
    }
    // join
    public function join($data=''){
    	$join = $this->DB->join($data);
    	return $join;
    }
    // where
    public function where($where=''){
    	$where = $this->DB->where($where);
    	return $where;
    }
    // 返回数据条数
    public function page($limit=''){
    	$page = $this->DB->page($page);
    	return $page;
    }
	// 新返回数据条数
    public function limit($limit=''){
		$limit = $this->DB->limit($limit);
    	return $limit;
	}
    // 结果排序
    public function order($order=''){
    	$order = $this->DB->order($order);
    	return $order;
    }
    // 定义要查询的字段
    public function field($field=''){
    	$field = $this->DB->field($field);
    	return $field;
    }
	// 判断数据是否存在
    public function has(){
    	$has = $this->DB->has();
    	return $has;
    }
    // 数据分组
    public function group($group=''){
    	$group = $this->DB->group($group);
    	return $group;
    }
	// 自定义增/减积分
    public function score($data=''){
    	$score = $this->DB->score($data);
    	return $score;
    }
    // 增加积分
    public function scoreInc($data='',$num=''){
    	$scoreInc = $this->DB->scoreInc($data,$num);
    	return $scoreInc;
    }
    // 減积分
    public function scoreDec($data='',$num=''){
    	$scoreDec = $this->DB->scoreDec($data,$num);
    	return $scoreDec;
    }
    public function query($sql=''){
    	$scoreDec = $this->DB->query($sql);
    	return $scoreDec;
    }
}
