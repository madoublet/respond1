


var q; 

$(".product-add input").bind('click', function() {
  var e = $(this).parent().parent().children(".product-quantity");
  q = e.children("input").val();

var hidden = e.parent().parent().children(".quantity");
hidden.attr("value", q);
});

    PAYPAL.apps.MiniCart.render();
