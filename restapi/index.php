<?php
	ini_set('display_errors', '1');
	//ini_set('display_startup_errors', '1');
	//error_reporting(E_ALL);
	
	header('Content-Type: application/json');
	session_start();
	require __DIR__ . '/vendor/autoload.php';
	use App\Lib\App;
	$dotenv = Dotenv\Dotenv::createMutable(__DIR__);
	$dotenv->load();
	/*
	$whoops = new \Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();
	*/
	require_once("App/route.php");
	
	
	App::run();
?>
