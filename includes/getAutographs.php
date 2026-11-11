<?php

	include("config.php");

	$return_array = [];
	$return_array['autographs'] = [];
	$return_array['roles'] = [];

	$conn = new mysqli($host, $user, $pass, $db);
	$conn->set_charset("utf8mb4");
	$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

	$sql = "SELECT aut.AutographId, aut.AutographDate, aut.CelebrityFirstName, aut.CelebrityLastName, aut.Item, aut.Event, aut.Franchise, aut.CelebrityCharacter, rol.Role, aut.RoleId, aut.JSANumber, aut.Notes
			FROM Autographs AS aut
				INNER JOIN Roles AS rol ON rol.RoleId = aut.RoleId
			ORDER BY aut.AutographDate DESC, aut.CelebrityLastName ASC";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$res = $stmt->get_result();
	if ($res->num_rows > 0) {
		while ($obj = $res->fetch_object()) {
			array_push($return_array['autographs'], $obj);
		};
	};

	$sql = "SELECT *
			FROM Roles
			ORDER BY RoleId ASC";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$res = $stmt->get_result();
	if ($res->num_rows > 0) {
		while ($obj = $res->fetch_object()) {
			array_push($return_array['roles'], $obj);
		};
	};

	echo json_encode($return_array);
	die();
	/*
	SELECT aut.AutographId, aut.AutographDate, aut.CelebrityFirstName, aut.CelebrityLastName, aut.Item, aut.Event, aut.Franchise, aut.CelebrityCharacter, rol.Role, aut.RoleId, aut.JSANumber, aut.Notes
	FROM Autographs AS aut
		INNER JOIN Roles AS rol ON rol.RoleId = aut.RoleId
	*/

?>