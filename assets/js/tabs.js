import $ from "jquery"

$(document).ready(() => {

    $($(".nav-tabs .nav-link")[0]).addClass("active")
    $($(".tab-content .tab-pane")[0]).addClass("active show")

});