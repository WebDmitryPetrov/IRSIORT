<?php


abstract class otchet_search extends ArrayObject{
	protected $sql;
	protected $parameters;
	public function __construct($params){
		$this->parameters=$params;

	}

	public function Search(){
		$sql=$this->prepareQuery();
		//var_dump($sql);die();
		$result=mysql_query($sql);

		return $result;
	}

	protected function prepareQuery(){
		
		$keys=$parameters=array();
		foreach($this->parameters as $key=>$parameter){
			$keys[]='%'.strtoupper($key).'%';
			$parameters[]=mysql_real_escape_string($parameter);
		}
	//	var_dump($keys, $this->parameters,str_replace($keys, $parameters, $this->sql)); die();
		return str_replace($keys, $parameters, $this->sql);
	}

}

class otchet_item{
	public  function __construct($params){
		
		foreach($params as $property=>$value){
			if(property_exists($this, $property)){
					$this->$property=$value;
			}
		}
	}
}