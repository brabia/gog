<?php	
	/**
	-------------------------------------
	| Please edit this file 
	-------------------------------------
	*/
	
	return [
		'database' => [
			'name' => 'gag',
			'username' => 'root',
			'password' => '123',
			'connection' => 'mysql:host=127.0.0.1',
			'options' => [
				pdo::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
			]
		]
	];
?>
