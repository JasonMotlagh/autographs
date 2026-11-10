window.addEventListener('load', () => {
	console.log("document.ready");
	if ('IdleDetector' in window) {
		consoleReporting("IdleDetector supported");
		const controller = new AbortController();
		const signal = controller.signal;
	} else {
		// Fallback to the event listener approach
		consoleReporting("IdleDetector NOT supported");
	}

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
	var temp_html = `<table>
						<tr>
							<th>Id</th>
							<th>Date</th>
							<th>Name</th>
							<th>Item</th>
							<th>Event</th>
							<th>Franchise</th>
							<th>Char.</th>
							<th>Role</th>
							<th>JSA</th>
							<th>Notes</th>
						</tr>`;
	var num_autographs = g_AUTOGRAPH_DATA['Autographs'].length;
	for(i = 0; i < num_autographs; i++) {
		temp_html += `<tr>
						<td>${g_AUTOGRAPH_DATA['Autographs'][i].AutographId}</td>
						<td>${g_AUTOGRAPH_DATA['Autographs'][i].AutographDate}</td>
						<td>${g_AUTOGRAPH_DATA['Autographs'][i].CelebrityLastName}, ${g_AUTOGRAPH_DATA['Autographs'][i].CelebrityFirstName}</td>
						<td>${g_AUTOGRAPH_DATA['Autographs'][i].Item}</td>
						<td>${g_AUTOGRAPH_DATA['Autographs'][i].Event}</td>
						<td>${g_AUTOGRAPH_DATA['Autographs'][i].Franchise}</td>
						<td>${g_AUTOGRAPH_DATA['Autographs'][i].CelebrityCharacter}</td>
						<td>${g_AUTOGRAPH_DATA['Autographs'][i].Role}</td>
						<td>${g_AUTOGRAPH_DATA['Autographs'][i].JSANumber}</td>
						<td>${g_AUTOGRAPH_DATA['Autographs'][i].Notes}</td>
					<tr>`
	};
	temp_html += `</table>`;
	return temp_html;
};