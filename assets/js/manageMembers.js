import $ from "jquery"

$(() => {

    $(".removeMemberBtn").on("click", e => {

        e.preventDefault();

        var _self = $(this);

        var userId = _self.data('id');

        var route = window.location.pathname.replace("/manage", `/remove/${userId}`);

        $("#confirmRemoveBtn").attr("href", route);

        // $(_self.attr('href')).modal('show');

    });
    
});
