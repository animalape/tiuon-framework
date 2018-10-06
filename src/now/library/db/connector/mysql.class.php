<?php
/* ===== Medoo mysql ======
 * Author:Tiuon.com
 * ======================== */
namespace library\db\connector;
class mysql{
	
	protected $DB;
	protected $table;
	public $obj;
	public $where;
	public $field = '*';
	public $join;
	public $limit;
	public $order;
	public $group;
	
	// 初始化数据库
	public function __construct($table){
		// 必须配置项
		vendor('Medoo.Medoo','','.class.php');
		$this->DB = new \Medoo([
			'database_type'	=> 'mysql',
			'database_name'	=> C('DB_DATANAME'),
			'server'		=> C('DB_HOSTNAME'),
			'username'		=> C('DB_USERNAME'),
			'password'		=> C('DB_PASSWORD'),
			'charset'		=> C('DB_CHARSET'),
			'port'			=> C('DB_PORT'),
			'prefix'		=> C('DB_PREFIX')
		]);
		$this->table = $table;
	}
	
    // 查询所有数据
    public function all(){
		if($this->limit){
			$this->where['LIMIT'] = $this->limit;
		}
		if(is_array($this->order)){
			$this->where['ORDER'] = $this->order;
		}
		if($this->group){
			$this->where['GROUP'] = $this->group;
		}
		if($this->join){
			$data = $this->DB->select($this->table, $this->join, $this->field, $this->where);
		}else{
			$data = $this->DB->select($this->table, $this->field, $this->where);
		}
    	return $data;
    }
    
    // 查询一条
    public function one(){
		if($this->limit){
			$this->where['LIMIT'] = $this->limit;
		}
		if(is_array($this->order)){
			$this->where['ORDER'] = $this->order;
		}
		if($this->group){
			$this->where['GROUP'] = $this->group;
		}
		if($this->join){
			$data = $this->DB->get($this->table, $this->join, $this->field, $this->where);
		}else{
			$data = $this->DB->get($this->table, $this->field, $this->where);
		}
    	return $data;
    }
    
	// 判断数据是否存在
	public function has(){
		if($this->join){
			$data = $this->DB->has($this->table, $this->join, $this->where);
		}else{
			$data = $this->DB->has($this->table, $this->where);
		}
    	return $data;
	}
	
	// 添加数据
    public function add($data){
    	$this->DB->insert($this->table,$data);
    	return $this->DB->id();
    }
    
    // 修改数据
    public function save($data = null){
		$data = $this->DB->update($this->table,$data,$this->where);
    	return $data->rowCount();
    }
    
    // 删除数据
    public function del(){
		$data = $this->DB->delete($this->table,$this->where);
    	return $data->rowCount();
    }
    
    // 创建数据表
    public function createdata($name=null, $value = null){
    	
    	return null;
    }
    
    // 统计条数
    public function count($data){
		$this->field = $data?$data:$this->field;
		if($this->join){
			$count = $this->DB->count($this->table, $this->join, $this->field, $this->where);
		}else{
			$count = $this->DB->count($this->table, $this->field, $this->where);
		}
    	return $count;
    }
    
    // 获取最大值
    public function max($data){
		$this->field = $data?$data:$this->field;
		if($this->join){
			$max = $this->DB->max($this->table, $this->join, $this->field, $this->where);
		}else{
			$max = $this->DB->max($this->table, $this->field, $this->where);
		}
    	return $max;
    }
    
    // 获取最小值
    public function min($data){
		$this->field = $data?$data:$this->field;
		if($this->join){
			$min = $this->DB->min($this->table, $this->join, $this->field, $this->where);
		}else{
			$min = $this->DB->min($this->table, $this->field, $this->where);
		}
    	return $min;
    }
    
    // 获取平均值
    public function avg($data){
		$this->field = $data?$data:$this->field;
		if($this->join){
			$avg = $this->DB->avg($this->table, $this->join, $this->field, $this->where);
		}else{
			$avg = $this->DB->avg($this->table, $this->field, $this->where);
		}
    	return $avg;
    }
    
	// 统计字段的总和
    public function sum($data){
		$this->field = $data?$data:$this->field;
		if($this->join){
			$sum = $this->DB->sum($this->table, $this->join, $this->field, $this->where);
		}else{
			$sum = $this->DB->sum($this->table, $this->field, $this->where);
		}
		return $sum;
    }
    
    // where
    public function where($where){
    	$this->where = $where;
		return $this;
    }
    
	// 定义要查询的字段
    public function field($field){
    	$this->field = $field;
    	return $this;
    }
    
	// join
    public function join($join){
    	$this->join = $join;
    	return $this;
    }
    
    // 返回数据条数
    public function page($limit){
    	$this->limit = $limit;
    	return $this;
    }
    
	// 返回数据条数
    public function limit($limit){
		$this->limit = $limit;
    	return $this;
	}
	
    // 结果排序
    public function order($order){
    	$this->order = $order;
    	return $this;
    }
    
    // 数据分组
    public function group($group){
    	$this->group = $group;
    	return $this;
    }
    
	// 自定义增/减积分
	public function score($data){
		$score = $this->DB->update($this->table,$data,$this->where);
    	return $score->rowCount();
    }
    
    // 增加积分
    public function scoreInc($columns, $num){
		if(!is_array($columns)){
			if($columns && $num){
				$datas[$columns.'[+]'] = $num;
			}
			$score = $this->DB->update($this->table,$datas,$this->where);
			return $score->rowCount();
		}
		return null;
    }
    
	// 減积分
    public function scoreDec($columns, $num){
		if(!is_array($columns)){
			if($columns && $num){
				$datas[$columns.'[-]'] = $num;
			}
			$score = $this->DB->update($this->table,$datas,$this->where);
			return $score->rowCount();
		}
		return null;
    }

	// query
    public function query($sql){
		$data = $this->DB->query($sql)->fetchAll();
		return $data;
    }
	
}