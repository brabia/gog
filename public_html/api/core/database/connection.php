<?php
	
	/**
	-------------------------------------
	| Connect db - config.php Seetings 
	-------------------------------------
	*/
	
	class Connection{
		public static function make($config){
			try{
				return new PDO(
					$config['connection'].';dbname='.$config['name'],
					$config['username'],
					$config['password'],
					$config['options']
				);
			}catch(PDOException $e){
				return array(
					'code' => 300,
					'title' => 'queryBuilder!',
					'message' => $e->getMessage()
				);
			}
		}
	}
?>