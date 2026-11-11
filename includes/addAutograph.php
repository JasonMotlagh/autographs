<?php

	include("config.php");

	$return_array = [];
	$return_array['autographs'] = [];
	$return_array['roles'] = [];

	$conn = new mysqli($host, $user, $pass, $db);
	$conn->set_charset("utf8mb4");
	$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

    $sql = "INSERT INTO Autographs (AutographDate, CelebrityFirstName, CelebrityLastName, Item, Event, Franchise, CelebrityCharacter, RoleId, JSANumber, Notes)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssiss", $autographDate, $celebrityFirstName, $celebrityLastName, $item, $event, $franchise, $celebrityCharacter, $roleId, $jsaNumber, $notes);

    $autographDate = $_POST['aut_date'];
    $celebrityFirstName = $_POST['aut_fname'];
    $celebrityLastName = $_POST['aut_lname'];
    $item = $_POST['aut_item'];
    $event = $_POST['aut_event'];
    $franchise = $_POST['aut_franchise'] == '' ? NULL : $_POST['aut_franchise'];
    $celebrityCharacter = $_POST['aut_character'] == '' ? NULL : $_POST['aut_character'];
    $roleId = (int) $_POST['aut_role'];
    $jsaNumber = $_POST['aut_jsa'] == '' ? NULL : $_POST['aut_jsa'];
    $notes = $_POST['aut_notes'] == '' ? NULL : $_POST['aut_notes'];

    if($stmt->execute()) {
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
    };
	/*
	SELECT aut.AutographId, aut.AutographDate, aut.CelebrityFirstName, aut.CelebrityLastName, aut.Item, aut.Event, aut.Franchise, aut.CelebrityCharacter, rol.Role, aut.RoleId, aut.JSANumber, aut.Notes
	FROM Autographs AS aut
		INNER JOIN Roles AS rol ON rol.RoleId = aut.RoleId
	*/

?>