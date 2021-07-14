import $ from "jquery"

$(() => {

    $(".pagination-container .pagination .page-item button").on("click", e => {

        e.preventDefault();

        var _self = $(this);

        var row = _self.data('id');

        $("#paginationRow").attr("value", row);

        $("#pagination-form").trigger("submit");

    });
    
});


