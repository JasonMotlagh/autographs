<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Autographs</title>
</head>
<body>
	<app>
		<div id="new-autograph-container">
			<form id="new-autograph-form">
				<label for="autograph-date">Date</label>
				<input id="autograph-date" name="autograph-date" type="date">

				<label for="autograph-fname">First Name</label>
				<input id="autograph-fname" name="autograph-fname" type="text">

				<label for="autograph-lname">Last Name</label>
				<input id="autograph-lname" name="autograph-lname" type="text">

				<label for="autograph-item">Item</label>
				<input id="autograph-item" name="autograph-item" type="text">

				<label for="autograph-event">Event</label>
				<input id="autograph-event" name="autograph-event" type="text">

				<label for="autograph-franchise">Franchise</label>
				<input id="autograph-franchise" name="autograph-franchise" type="text">

				<label for="autograph-character">Character</label>
				<input id="autograph-character" name="autograph-character" type="text">

				<label for="autograph-role">Role</label>
				<select id="autograph-role" name="autograph-role"></select>

				<label for="autograph-jsa">JSA</label>
				<input id="autograph-jsa" name="autograph-jsa" type="text">

				<label for="autograph-notes">Notes</label>
				<textarea id="autograph-notes" name="autograph-notes" rows="3" cols="30"></textarea>

				<button type="button" onclick="addNewAutograph()">Add</button>
			</form>
		</div>
		<div id="autographs"></div>
	</app>
	<script src="js/index.js"></script>
</body>
</html>