// Mask or display the order search form
$("#filter").click(function() {
    $("#search-form").show("slow");
    $("#filter").hide();
});

$("#search-order-btn").click(function() {
    $("#search-form").hide("slow");
    $("#filter").show();
});