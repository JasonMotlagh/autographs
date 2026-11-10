var g_OLD_AUTO = [];

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

const getOldAutographs = () => {
    const formData = new FormData();
    formData.append( 'cur_date', buildLocalDateTime() );

    const requestOptions = {
        method: "POST",
        body: formData
    };

    fetch('./includes/migration.php', requestOptions)
        .then((response) => response.json())
        .then((result) => {
            g_OLD_AUTO = [];
            g_OLD_AUTO = result;
        })
        .catch((error) => console.error("error:", error));
};