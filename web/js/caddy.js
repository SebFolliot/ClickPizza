// Allows to save data in a cookie
function createCookie(cName, cValue, days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    var expires = "; expires=" + date.toUTCString();

    if ('btoa' in window) {
        cValue = btoa(cValue);
    }

    document.cookie = cName + "=" + cValue + "; " + expires + ';path=/';
}

// Save all our caddy
function saveCaddy(caddyProductsNumber, caddyProducts) {
    createCookie('caddyProductsNumber', caddyProductsNumber, 1);
    createCookie('caddyProducts', JSON.stringify(caddyProducts), 1);
}

// Takes as parameter the name of the cookie and returns its value
function readCookie(cName) {
    var name = cName + "=";
    var ca = document.cookie.split(';');

    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c[0] == ' ') {
            c = c.substring(1);
        }

        if (c.indexOf(name) != -1) {
            if ('btoa' in window) {
                return atob(c.substring(name.length, c.length));
            } else {
                return c.substring(name.length, c.length);
            }
        }
    }
    return false;
}

// variables to store the number of products and their names
var caddyProductsNumber;
var caddyProducts;

// show/hide information about the caddy
function caddyInformation() {
    if (caddyProductsNumber > 0) {
        $('#caddy-dropdown .hidden').removeClass('hidden');
        $('#empty-caddy-msg').hide();
    } else {
        $('#caddy-dropdown .go-to-caddy').addClass('hidden');
        $('#empty-caddy-msg').show();
    }
}

// retrieves information stored in cookies
caddyProductsNumber = parseInt(readCookie('caddyProductsNumber') ? readCookie('caddyProductsNumber') : 0);
caddyProducts = readCookie('caddyProducts') ? JSON.parse(readCookie('caddyProducts')) : [];


caddyInformation();

// show the number of product in the caddy icon
$('#caddy-products-num').html(caddyProductsNumber);

// hydrate the caddy
var products = '';
caddyProducts.forEach(function (v) {
    products += '<li style="text-align:center" id="' + v.id + '"><strong>' + v.name + '</strong><br><small>Quantité : <span class="qt">' + v.qt + '</span></small></li>';
});

$('#caddy-dropdown').prepend(products);

// Add a product in the caddy
$('.add-caddy').click(function () {

    // Product info recovery
    var $this = $(this);
    var id = $this.attr('data-id');
    var name = $this.attr('data-name');
    var price = $this.attr('data-price');
    var qt = 1;
    caddyProductsNumber += qt;

    // Update the number of products in the widget
    $('#caddy-products-num').html(caddyProductsNumber);

    var newProduct = true;

    // Check if the product is not already in the caddy
    caddyProducts.forEach(function (v) {
        // If the product is already present, we increment the quantity
        if (v.id == id) {
            newProduct = false;
            v.qt += qt;
            $('#' + id).html('<strong>' + name + '<strong><br><small>Quantité : <span class="qt">' + v.qt + '</span></small></a>');
        }
    });

    // If it is new, it is added
    if (newProduct) {
        $('#caddy-dropdown').prepend('<li style="text-align:center" id="' + id + '"><strong>' + name + '</strong><br><small>Quantité : <span class="qt">' + qt + '</span></small></li>');
        caddyProducts.push({
            id: id,
            name: name,
            price: price,
            qt: qt,
        });
    }

    // Save the caddy
    saveCaddy(caddyProductsNumber, caddyProducts);

    // Displays the contents of the caddy if it is the first article
    caddyInformation();
});



// Caddy page
if (window.location.pathname == '/caddy') {

    var products = '';
    var subTotal = 0;
    var total = 0;
    var discount = $("#discount").attr('data-discount');


    caddyProducts.forEach(function (v) {

        products += '<tr data-id="' + v.id + '">\
             <td>' + v.name + '</a></td>\
             <td>' + v.price + ' €</td>\
             <td colspan="2" style="padding-right:7%"><span class="qt" style="margin-right: 10px">' + v.qt + '</span> <span class="less  label label-default">–</span> <span class="more label label-default">+</span>\
             <a class="delete-product pull-right" style="margin-left:20%"><i class="fas fa-eraser" style="color:#669900" title="Supprimer l\'article"></i></a></td></tr>';

        subTotal += v.price * v.qt;
    });

    total = subTotal - ((subTotal * discount) / 100);

    // Insert table content, subtotal and total
    $('#caddy-table').empty().html(products);
    $('#subtotal').html(subTotal.toFixed(2) + ' €');
    $('#total').html(total.toFixed(2) + ' €');


    // Increases the number of products
    $('.more').on('click', function () {
        var $this = $(this);
        var qt = parseInt($this.prevAll('.qt').html());
        var id = $this.parent().parent().attr('data-id');

        // Update the quantity
        caddyProductsNumber += 1;
        $this.prevAll('.qt').html(qt + 1);
        $('#caddy-products-num').html(caddyProductsNumber);
        $('#' + id + ' .qt').html(qt + 1);

        // Update caddyProducts
        caddyProducts.forEach(function (v) {

            if (v.id == id) {
                v.qt += 1;
                subTotal = subTotal + parseFloat(v.price);
                total = subTotal - ((subTotal * discount) / 100);
            }
        });

        $('#subtotal').html(subTotal.toFixed(2) + ' €');
        $('#total').html(total.toFixed(2) + ' €');
        saveCaddy(caddyProductsNumber, caddyProducts);
    });

    // Decreases the number of products
    $('.less').click(function () {
        var $this = $(this);
        var qt = parseInt($this.prevAll('.qt').html());
        var id = $this.parent().parent().attr('data-id');

        if (qt > 1) {
            // Update the quantity
            caddyProductsNumber -= 1;
            $this.prevAll('.qt').html(qt - 1);
            $('#caddy-products-num').html(caddyProductsNumber);
            $('#' + id + ' .qt').html(qt - 1);

            // Update caddyProducts
            caddyProducts.forEach(function (v) {

                if (v.id == id) {
                    v.qt -= 1;
                    subTotal = subTotal - parseFloat(v.price);
                    total = subTotal - ((subTotal * discount) / 100);
                }
            });

            $('#subtotal').html(subTotal.toFixed(2) + ' €');
            $('#total').html(total.toFixed(2) + ' €');
            saveCaddy(caddyProductsNumber, caddyProducts);
        }
    });

    // Delete a product
    $('.delete-product').click(function () {
        var $this = $(this);
        var qt = parseInt($this.prevAll('.qt').html());
        var id = $this.parent().parent().attr('data-id');
        var arrayId = 0;

        // Update the quantity
        caddyProductsNumber -= qt;
        $('#caddy-products-num').html(caddyProductsNumber);

        // Delete the product of the DOM
        $this.parent().parent().hide(700);
        $('#' + id).remove();

        // Update caddyProducts
        caddyProducts.forEach(function (v) {

            if (v.id == id) {

                console.log(qt);
                subTotal -= v.price * qt;
                total = subTotal - ((subTotal * discount) / 100);
                caddyProducts.splice(arrayId, 1);

                return false;
            }
            arrayId++;
        });

        $('#subtotal').html(subTotal.toFixed(2) + ' €');
        $('#total').html(total.toFixed(2) + ' €');
        saveCaddy(caddyProductsNumber, caddyProducts);

        caddyInformation();
    });
}

/*
// Delete the cookie
function deleteCookie(cName) {
        createCookie(cName;'',-1);
}
*/