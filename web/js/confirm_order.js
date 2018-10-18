// Show or hide the confirmation message of validation or cancellation of order, or else update or remove a commodity
$(".validate-link").click(function() {
    $(".confirm-validation").show("slow");
    $(".validate-link").hide();
    $(".cancel-link").hide();
    $(".btn-modal-close").hide();
});

$(".cancel-link").click(function() {
    $(".confirm-cancellation").show("slow");
    $(".validate-link").hide();
    $(".cancel-link").hide();
    $(".btn-modal-close").hide();
});

$(".not-confirm").click(function() {
    $(".confirm-validation").hide();
    $(".confirm-cancellation").hide();
    $(".confirm-update").hide();
    $(".confirm-remove").hide();
    $(".update-link").show("slow");
    $(".remove-link").show("slow");
    $(".validate-link").show("slow");
    $(".cancel-link").show("slow");
    $(".btn-modal-close").show("slow");
});

$(".modal-close").click(function() {
    $(".confirm-validation").hide();
    $(".confirm-cancellation").hide();
    $(".confirm-update").hide();
    $(".confirm-remove").hide();
    $(".validate-link").show();
    $(".update-link").show();
    $(".remove-link").show();
    $(".cancel-link").show();
    $(".btn-modal-close").show();
});

$(".btn-modal-close").click(function() {
    $(".confirm-validation").hide();
    $(".confirm-cancellation").hide();
    $(".confirm-update").hide();
    $(".confirm-remove").hide();
    $(".update-link").show();
    $(".remove-link").show();
    $(".validate-link").show();
    $(".cancel-link").show();
});

$(".update-link").click(function() {
    $(".confirm-update").show("slow");
    $(".update-link").hide();
    $(".remove-link").hide();
    $(".btn-modal-close").hide();
});

$(".remove-link").click(function() {
    $(".confirm-remove").show("slow");
    $(".update-link").hide();
    $(".remove-link").hide();
    $(".btn-modal-close").hide();
});