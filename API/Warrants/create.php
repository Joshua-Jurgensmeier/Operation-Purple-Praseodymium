<?php

	require_once __DIR__ . '/../../Includes/top.php';
	require_once __DIR__ . '/../utils.php';

	if(!(isset($_SESSION['admin']) and $_SESSION['admin'] == true))
	{
		header('Location: http://crime.team12.isucdc.com/index.php');
		exit("Access denied");
	}

	$_SESSION['req_data'] = parse_request();

	$required_attrs = ['title', 'person', 'granted_date', 'served_at', 'served_by'];

	if (!check_required_attrs($_SESSION['req_data'], $required_attrs)) {
		error_log(json_encode($_SESSION['req_data']));
		error_log(json_encode($_POST));
		die("Error: missing required attribute");
	}

	$attrs = $required_attrs;

	#A glorified for each append
	$values = array_map(function($attr) { return $_SESSION['req_data'][$attr]; }, $attrs);
	
	#Create a string of question marks for every attribute seperated by commas
	$placeholders = rtrim(str_repeat("?,", count($attrs)), ",");

	$M_query = $pdo->prepare("INSERT INTO warrants (" . join(', ', $attrs) . ") VALUES (" . $placeholders . ");");
	error_log($M_query);

	$M_query->execute($values);
	
	echo "success";
?>
