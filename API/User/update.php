<?php
	
	require_once __DIR__ . '/../../Includes/top.php';
	require_once __DIR__ . '/../utils.php';

	if(!(isset($_SESSION['admin']) and $_SESSION['admin'] == true))
	{
		header('Location: http://crime.team12.isucdc.com/index.php');
		exit("Access denied");
	}

	$_SESSION['req_data'] = parse_request();

	$required_attrs = ['id'];
	$extended_attrs = ['name', 'password', 'role'];

	if (!check_required_attrs($_SESSION['req_data'], $required_attrs)) {
		die("Error: missing required attribute");
	}

	$extended_attrs = check_extended_attrs($_SESSION['req_data'], $extended_attrs);

	$attrs = $extended_attrs;

	if (sizeof($attrs) == 0) {
		die("Missing optional attribute to update.");
	}

	if($_SESSION['req_data']['password'] == "")
	{
		$attrs = array_diff($attrs, array('password'));
	}

	$updates = array_map (

		function($attr) { 
			if($attr == 'password') 
			{	
				return password_hash($_SESSION['req_data'][$attr], PASSWORD_DEFAULT);
			} 
			else 
			{
				return $_SESSION['req_data'][$attr];
			}
		}, 
		$attrs
	);

	$updates[] = $_SESSION['req_data']['id'];

	$M_query = $pdo->prepare("UPDATE users SET " . join("= ?, ", $attrs) . "= ? WHERE id= ?;");

	error_log($M_query);

	$M_query->execute($updates);
	
	echo "success";
?>
