$(function() {
    $('#btn_checkout').on('click', function() {
        $('#page-area-checkout').empty();
        $('#page-area-checkout').load('Cart/Checkout?_=' + (new Date()).getTime());
    });
});
