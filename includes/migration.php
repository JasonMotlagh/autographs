<?php

	include("config.php");

	$return_array = [];
	$return_array['old'] = [];
	$return_array['new'] = [];

	$old_conn = new mysqli($host, $old_user, $old_pass, $old_db);
	$old_conn->set_charset("utf8mb4");
	$old_conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

	/*$conn = new mysqli($host, $user, $pass, $db);
	$conn->set_charset("utf8mb4");
	$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);*/

	$old_sql = "SELECT *
				FROM autographs
				ORDER BY pk_id ASC";
	$old_stmt = $old_conn->prepare($old_sql);
	$old_stmt->execute();
	
	$old_res = $old_stmt->get_result();
	if ($old_res->num_rows > 0) {
		while ($obj = $old_res->fetch_object()) {
			array_push($return_array['old'], $obj);
		};
	};
	//echo json_encode($return_array);
	//die();

	$conn = new mysqli($host, $user, $pass, $db);
	$conn->set_charset("utf8mb4");
	$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
	$sql = "INSERT INTO Autographs (AutographDate, CelebrityFirstName, CelebrityLastName, Item, Event, Franchise, CelebrityCharacter, RoleId, JSANumber, Notes)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	/*$color_family = NULL;
	$lego_color_family_id = NULL;*/
	$stmt->bind_param("sssssssiss", $autographDate, $celebrityFirstName, $celebrityLastName, $item, $event, $franchise, $celebrityCharacter, $roleId, $jsaNumber, $notes);
	
	//$stmt->bind_param("si", $return_array['old'][$i]->color_family, (int) $return_array['old'][$i]->lego_color_family_id);

	//for ($i = 1969; $i < count($return_array['old']); $i++) {
	for ($i = 0; $i < count($return_array['old']); $i++) {
		//if ($return_array['old'][$i]->exact_color != NULL) {
			$autographDate = $return_array['old'][$i]->autograph_date;
			$celebrityFirstName = $return_array['old'][$i]->celebrity_firstName;
			$celebrityLastName = $return_array['old'][$i]->celebrity_lastName;
			$item = $return_array['old'][$i]->autographed_item;
			$event = $return_array['old'][$i]->location_event;
			$franchise = $return_array['old'][$i]->show_franchise == null ? NULL : $return_array['old'][$i]->show_franchise;
			$celebrityCharacter = $return_array['old'][$i]->celebrity_character == null ? NULL : $return_array['old'][$i]->celebrity_character;
			$roleId = (int) $return_array['old'][$i]->fk_roles_pk_id;
			$jsaNumber = $return_array['old'][$i]->JSA_number == null ? NULL : $return_array['old'][$i]->JSA_number;
			$notes = $return_array['old'][$i]->notes == null ? NULL : $return_array['old'][$i]->notes;

			$stmt->execute();
	};
	

	$sql = "SELECT *
			FROM Autographs
			ORDER BY AutographId ASC";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$res = $stmt->get_result();
	if ($res->num_rows > 0) {
		while ($obj = $res->fetch_object()) {
			array_push($return_array['new'], $obj);
		};
	};

	echo json_encode($return_array);
	die();

?>