
$(document).ready(function() {
var q; 
var e = document.getElementById("cartContainer");



$(".product-add input").bind('click', function() {
  var e = $(this).parent().parent().children(".product-quantity");
  q = e.children("input").val();

var hidden = e.parent().parent().children(".quantity");
hidden.attr("value", q);
});


    PAYPAL.apps.MiniCart.render({
    	displayEdge: 'left',
        edgeDistance: '0px',
        parent: e
    });
PAYPAL.apps.MiniCart.show();

});