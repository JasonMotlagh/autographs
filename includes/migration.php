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
				FROM autograph_archive
				ORDER BY pk_id ASC";
	$old_stmt = $old_conn->prepare($old_sql);
	$old_stmt->execute();
	
	$old_res = $old_stmt->get_result();
	if ($old_res->num_rows > 0) {
		while ($obj = $old_res->fetch_object()) {
			array_push($return_array['old'], $obj);
		};
	};
	echo json_encode($return_array);
	die();

	$conn = new mysqli($host, $user, $pass, $db);
	$conn->set_charset("utf8mb4");
	$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
	$sql = "INSERT INTO Sets (SetNumber, SetName, SetYear, SetNumberPieces, SetNumberMinifigures, SetThemeId, SetSubThemeId, SetPrice, SetQuantity, SetBuilt, SetInvestment, SetSorted, SetRetired, SetRetiredYear, SetGrams, SetBuildTime, SetThirdParty, SetThirdPartyCompany, SetNotes)
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	/*$color_family = NULL;
	$lego_color_family_id = NULL;*/
	$stmt->bind_param("ssiiiiisiiiiiisiiss", $setNumber, $setName, $setYear, $setNumberPieces, $setNumberMinifigures, $setThemeId, $setSubThemeId, $setPrice, $setQuantity, $setBuilt, $setInvestment, $setSorted, $setRetired, $setRetiredYear, $setGrams, $setBuildTime, $setThirdParty, $setThirdPartyCompany, $setNotes);
	
	//$stmt->bind_param("si", $return_array['old'][$i]->color_family, (int) $return_array['old'][$i]->lego_color_family_id);

	//for ($i = 1969; $i < count($return_array['old']); $i++) {
	for ($i = 0; $i < count($return_array['old']); $i++) {
		//if ($return_array['old'][$i]->exact_color != NULL) {
			$setNumber = $return_array['old'][$i]->set_number;
			$setName = $return_array['old'][$i]->set_name;
			$setYear = (int) $return_array['old'][$i]->year;
			$setNumberPieces = (int) $return_array['old'][$i]->num_pieces;
			$setNumberMinifigures = (int) $return_array['old'][$i]->num_minifigs;
			
			
			$setPrice = $return_array['old'][$i]->price;
			$setQuantity = (int) $return_array['old'][$i]->quantity;
			$setBuilt = (int) $return_array['old'][$i]->built;
			$setInvestment = (int) $return_array['old'][$i]->investment;
			$setSorted = (int) $return_array['old'][$i]->sorted;
			$setRetired = (int) $return_array['old'][$i]->retired;
			$setRetiredYear = (int) $return_array['old'][$i]->retired_year;
			$setGrams = $return_array['old'][$i]->weight_g;
			$setBuildTime = (int) $return_array['old'][$i]->build_time_minutes;
			$setThirdParty = (int) $return_array['old'][$i]->third_party;
			$setThirdPartyCompany = $return_array['old'][$i]->third_party_name;
			$setNotes = $return_array['old'][$i]->description;

			$setThemeId = (int) $return_array['old'][$i]->fk_theme;
			switch($setThemeId) {
				case ($setThemeId > 25):
					$setThemeId -= 3;
					break;
			};

			$setSubThemeId = (int) $return_array['old'][$i]->fk_sub_theme;
			switch($setSubThemeId) {
				case ($setSubThemeId > 37 && $setSubThemeId < 42):
					$setSubThemeId -= 1;
					break;
				
				case ($colorId > 41 && $colorId < 63):
					$colorId -= 2;
					break;
				
				case ($colorId > 62):
					$colorId -= 8;
					break;
			};
			$stmt->execute();
		//}
		//$stmt->bind_param("si", $return_array['old'][$i]->color_family, $return_array['old'][$i]->lego_color_family_id);
		/*try {
			$stmt->execute();
		} catch (Exception $e) {
			echo json_encode($e->getMessage());
			die();
		};*/
		
		/*echo json_encode('execute() called');
		die();*/
	};
	

	$sql = "SELECT *
			FROM Sets
			ORDER BY SetId ASC";
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