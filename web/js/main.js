$(function () {
//cart logic

    $(".add-to-cart").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/cart/add',
            data: {id: id},
            type: 'GET',
            success: function (res) {
                if (!res) alert('Error!');
                console.log(res);
                //ShowCart();
            },
            error: function () {
                alert ('Error!');
            }
        })
    });


    $(".delfromcart").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/cart/del',
            data: {id: id},
            type: 'GET',
            success: function (res) {
                if (!res) alert('Error!');
                //$.pjax.reload({container: "#ajcartwrapp", url: "/cart/index"});
                $.pjax.reload('#ajcartwrapp', {timeout : 1000});
                //setTimeout(function() {window.location.reload();}, 1000);
                //ShowCart();
            },
            error: function () {
                alert ('Error!');
            }
        })
    });

// Favourite logic
    /*
    $(".add-to-cart").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/cart/add',
            data: {id: id},
            type: 'GET',
            success: function (res) {
                if (!res) alert('Error!');
                console.log(res);
                //ShowCart();
            },
            error: function () {
                alert ('Error!');
            }
        })
    });
*/
});