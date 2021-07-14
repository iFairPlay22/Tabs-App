import $ from "jquery"

$(() => {

    $($(".nav-tabs .nav-link")[0]).addClass("active");
    $($(".tab-content .tab-pane")[0]).addClass("active show");

});