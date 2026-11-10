var g_OLD_AUTO = [];

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