<?php
/*
 * Index
 */
namespace index\controller;
class indexController extends commonController{
	
	public function index(){
		M('index')->show();
	}
	
}