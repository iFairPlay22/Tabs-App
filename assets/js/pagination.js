import $ from "jquery"

$(document).on("click", ".pagination-container .pagination .page-item button", function(e) {

    e.preventDefault();

    var _self = $(this);

    var row = _self.data('id');

    $("#paginationRow").attr("value", row)

    $("#pagination-form").submit()
});