<?php
namespace index\model;
use library\Model;
class indexModel extends Model{
	
	public function show(){
		$version = NOW_VERSION;
		echo "
	    	<!DOCTYPE html>
			<html>
			<head>
				<title>Hello world</title>
			<style>
				html{background-color:#fff;color:#32353a;font-family:Tahoma, Verdana, Segoe, sans-serif;line-height:1.5;}
				body{margin:0;}
				h1{font-family:sans-serif;font-size:4em;}
				.counter{margin-bottom:1rem;border-bottom:.1875em dashed #d2d6dd;}
				.calories{color:#8b919b;font-size:.875em;}
				.calories a{color:#8b919b;text-decoration:none}
				.splash{position: relative;height:100vh;max-width:36em;text-align:center;}
				.splash{box-sizing:border-box;max-width:36em;margin:0 auto;padding:1.5em;}
				.splash p{color:#8b919b;}
				.counter{position:relative;padding-top:30vh;}
				.counter h1{position:relative;text-align:center;margin:0;line-height: .625;z-index:2;transform-origin: bottom center;color:#8b919b;}
			</style>
			</head>
				<body>
				    <div class=\"splash\">
				        <div class=\"counter\">
				          <div><h1>Hello world</h1></div>
				        </div>
				        <p class=\"calories\">version: {$version} - <a href='http://www.tiuon.com/index/index/docs/v/0.2.html'>Doc</a></p>
				    </div>
				</body>
			</html>";
	}
}