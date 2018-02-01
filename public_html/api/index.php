<?php
	/**
	-------------------------------------
	| api.gogo.com | a GOG Client
	-------------------------------------
	*/
	
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	header('Access-Control-Allow-Origin: *');
	
	$query = require 'core/bootstrap.php';
	new App();
?>