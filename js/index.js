window.addEventListener('load', () => {
	console.log("document.ready");
	/*if ('IdleDetector' in window) {
		consoleReporting("IdleDetector supported");
		const controller = new AbortController();
		const signal = controller.signal;
	} else {
		// Fallback to the event listener approach
		consoleReporting("IdleDetector NOT supported");
	}*/

	getAutographs();
});

const g_SEARCH_ENTITIES = ["Ø", "°",
						"\"", "\'",
						"©", "®", "™",
						"à", "á", "À", "Á",
						"è", "é", "È", "É",
						"’", "–"];

const g_REPLACE_ENTITIES = ["&Oslash;", "&deg;",
						"&quot;", "&apos;",
						"&copy;", "&reg;", "&trade;",
						"&agrave;", "&aacute;", "&Agrave;", "&Aacute;",
						"&egrave;", "&eacute;", "&Egrave;", "&Eacute;",
						"&apos;", "&mdash;"];

var g_AUTOGRAPH_DATA = [];

const buildLocalDateTime = () => {
	var date = new Date();
	year = date.getFullYear();
	month = String(date.getMonth() + 1).padStart(2, '0');
	day = String(date.getDate()).padStart(2, '0');
	hour = String(date.getHours()).padStart(2, '0');
	minute = String(date.getMinutes()).padStart(2, '0');
	second = String(date.getSeconds()).padStart(2, '0');
	millisecond = String(date.getMilliseconds()).padStart(3, '0');

	return year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' +second + '.' + millisecond;
};

const dataCleanUp = (param_string) => {
	var temp_string = param_string.trim().replace(/&/g, "&amp;");
	var temp_len = g_SEARCH_ENTITIES.length;
	for(i = 0; i < temp_len; i++) {
		temp_string = temp_string.replace(new RegExp(g_SEARCH_ENTITIES[i], 'g'), g_REPLACE_ENTITIES[i]);
	}
	return temp_string;
};
const reverseEntities = (param_string) => {
	var temp_string;
	if(typeof param_string == "string") {
		temp_string = param_string.trim().replace(/&amp;/g, "&");
		var temp_len = g_SEARCH_ENTITIES.length;
		for(i = 0; i < temp_len; i++) {
			temp_string = temp_string.replace(new RegExp(g_REPLACE_ENTITIES[i], 'g'), g_SEARCH_ENTITIES[i]);
		}
		return temp_string;
	}
};

const consoleReporting = (param) => {
	console.log(param);
};

const getAutographs = () => {
	const formData = new FormData();
	formData.append( 'cur_date', buildLocalDateTime() );

	const requestOptions = {
		method: "POST",
		body: formData
	};

	fetch('./includes/getAutographs.php', requestOptions)
		.then((response) => response.json())
		.then((result) => {
			g_AUTOGRAPH_DATA['Autographs'] = [];
			g_AUTOGRAPH_DATA['Roles'] = [];
			g_AUTOGRAPH_DATA['Autographs'] = result['autographs'];
			g_AUTOGRAPH_DATA['Roles'] = result['roles'];

			document.getElementById('autograph-role').innerHTML = buildRolesDropdown();
			document.getElementById('autographs').innerHTML = buildAutographTable();
		})
		.catch((error) => console.error("error:", error));
};

const buildRolesDropdown = () => {
	var role_length = g_AUTOGRAPH_DATA['Roles'].length;
	var temp_html = ``;
	for(i = 0; i < role_length; i++) {
		temp_html += `<option value="${g_AUTOGRAPH_DATA['Roles'][i].RoleId}">${g_AUTOGRAPH_DATA['Roles'][i].Role}</option>`;
	};
	return temp_html;
};

const buildAutographTable = () => {
	var temp_html = `<div class="header-container table-columns">
						<!--<p class="header-item">Id</p>-->
						<p class="header-item">Date</p>
						<p class="header-item">Name</p>
						<p class="header-item">Item</p>
						<p class="header-item">Event</p>
						<p class="header-item">Franchise</p>
						<p class="header-item">Char.</p>
						<p class="header-item">Role</p>
						<p class="header-item">JSA</p>
						<p class="header-item">Notes</p>
					</div>`;
	var num_autographs = g_AUTOGRAPH_DATA['Autographs'].length;
	for(i = 0; i < num_autographs; i++) {
		temp_html += `<div class="table-item-container table-columns" data-id="${g_AUTOGRAPH_DATA['Autographs'][i].AutographId}">
						<!--<p class="table-item">${g_AUTOGRAPH_DATA['Autographs'][i].AutographId}</p>-->
						<p class="table-item">${g_AUTOGRAPH_DATA['Autographs'][i].AutographDate}</p>
						<p class="table-item">${g_AUTOGRAPH_DATA['Autographs'][i].CelebrityLastName}, ${g_AUTOGRAPH_DATA['Autographs'][i].CelebrityFirstName}</p>
						<p class="table-item">${g_AUTOGRAPH_DATA['Autographs'][i].Item}</p>
						<p class="table-item">${g_AUTOGRAPH_DATA['Autographs'][i].Event}</p>
						<p class="table-item">${g_AUTOGRAPH_DATA['Autographs'][i].Franchise}</p>
						<p class="table-item">${g_AUTOGRAPH_DATA['Autographs'][i].CelebrityCharacter}</p>
						<p class="table-item">${g_AUTOGRAPH_DATA['Autographs'][i].Role}</p>
						<p class="table-item">${g_AUTOGRAPH_DATA['Autographs'][i].JSANumber}</p>
						<p class="table-item">${g_AUTOGRAPH_DATA['Autographs'][i].Notes}</p>
					</div>`
	};
	return temp_html;
};

const addNewAutograph = () => {
	console.log("addNewAutograph() called");

	const formData = new FormData();
	formData.append( 'cur_date', buildLocalDateTime() );
	formData.append( 'aut_date', document.getElementById('autograph-date').value );
	formData.append( 'aut_fname', document.getElementById('autograph-fname').value );
	formData.append( 'aut_lname', document.getElementById('autograph-lname').value );
	formData.append( 'aut_item', document.getElementById('autograph-item').value );
	formData.append( 'aut_event', document.getElementById('autograph-event').value );
	formData.append( 'aut_franchise', document.getElementById('autograph-franchise').value );
	formData.append( 'aut_character', document.getElementById('autograph-character').value );
	formData.append( 'aut_role', document.getElementById('autograph-role').value );
	formData.append( 'aut_jsa', document.getElementById('autograph-jsa').value );
	formData.append( 'aut_notes', document.getElementById('autograph-notes').value );

	const requestOptions = {
		method: "POST",
		body: formData
	};

	fetch('./includes/addAutograph.php', requestOptions)
		.then((response) => response.json())
		.then((result) => {
			g_AUTOGRAPH_DATA['Autographs'] = [];
			g_AUTOGRAPH_DATA['Roles'] = [];
			g_AUTOGRAPH_DATA['Autographs'] = result['autographs'];
			g_AUTOGRAPH_DATA['Roles'] = result['roles'];

			document.getElementById('autograph-date').value = '';
			document.getElementById('autograph-fname').value = '';
			document.getElementById('autograph-lname').value = '';
			document.getElementById('autograph-item').value = '';
			document.getElementById('autograph-event').value = '';
			document.getElementById('autograph-franchise').value = '';
			document.getElementById('autograph-character').value = '';
			document.getElementById('autograph-jsa').value = '';
			document.getElementById('autograph-notes').value = '';

			document.getElementById('autograph-role').innerHTML = buildRolesDropdown();
			document.getElementById('autographs').innerHTML = buildAutographTable();
		})
		.catch((error) => console.error("error:", error));
};