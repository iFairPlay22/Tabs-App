const $ = require("jquery");

require("bootstrap");

import "./styles/app.scss";

let url = document.URL;

// Ajout du /index.php en prod
let domain = ".herokuapp.com";
if (url.includes(domain) && !url.includes("/index.php")) {
    let data = url.split(domain);
    document.location = data[0] + `${domain}/index.php` + data[1];
}