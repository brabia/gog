<?php
	/**
	-------------------------------------
	| Router Manger
	-------------------------------------
	*/
	
	class Router{
		public $routes = array(
			'POST' => array(),
			'GET' => array()
		);
		
		public function __construct(){}
		
		public function post($uri, $controller){
			/** ----------------------------------
			| set post router
			| @return: null
			----------------------------------- **/	
			$this->routes['POST'][$uri] = $controller;
		}
		public function get($uri, $controller){
			/** ----------------------------------
			| set get router
			| @return: null
			----------------------------------- **/	
			$this->routes['GET'][$uri] = $controller;
		}
		
		public function doRequest($uri, $method){
			/** ----------------------------------
			| extract URI - ID if any
			| @return: array
			----------------------------------- **/	
		
			$URI = preg_replace("/[0-9]{1,4}/", '', $uri);
			$ID = preg_replace("/[^0-9]{1,4}/", '', $uri);
			
			if(array_key_exists($URI, $this->routes[$method])){
				return array(
					'code' => 200,
					'uri' => $this->routes[$method][$URI],
					'method' => $method,
					'id' => $ID
				);				
			}else{
				return array(
					'uri' => $uri,
					'method' => $method,
					'code' => 404
				);
			}
		}
	}
?>