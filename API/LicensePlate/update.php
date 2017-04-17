<?php
	require_once __DIR__ . '/../../Includes/top.php';
	require_once __DIR__ . '/../utils.php';

	$_SESSION['req_data'] = parse_request();

	$required_attrs = ['id'];
	$extended_attrs = ['plate', 'brand', 'model', 'color', 'owner'];

	if (!check_required_attrs($_SESSION['req_data'], $required_attrs)) {
		die("Error: missing required attribute");
	}

	$extended_attrs = check_extended_attrs($_SESSION['req_data'], $extended_attrs);

	$attrs = $extended_attrs;

	if (sizeof($attrs) == 0) {
		die("Missing optional attribute to update.");
	}

	$updates = array_map(function($attr) { return $attr . '="' . $_SESSION['req_data'][$attr] . '"'; }, $attrs);

	$M_query = "UPDATE license_plate SET " . join(", ", $updates) . " WHERE id=" . $_SESSION['req_data']['id'] . ";";
	error_log($M_query);
	$M_result = $mysqli->query($M_query);
	if (!$M_result) {
		error_log($mysqli->error);
		die($mysqli->error);
	}
	echo "success";
?>
