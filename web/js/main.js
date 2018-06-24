$(function () {
//cart logic

    $(".add-to-cart").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/cart/add',
            data: {id: id},
            type: 'GET',
            success: function (data) {
                if (!data) alert('Error!');
                var data = jQuery.parseJSON(data);
                $('.cartcountres').html(data['cartcount']);
                $('.cartsummres').html(data['cartsum']);
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
        if (confirm("Are you sure you want to delete?")) {
            $.ajax({
                url: '/cart/del',
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    if (!data) alert('Error!');
                    var selector = "#catprodrow_" + id;
                    $(selector).hide(500);
                    setTimeout(function () {
                        $(selector).detach()
                    }, 1000);

                    var data = jQuery.parseJSON(data);
                    $('.cartcountres').html(data['cartcount']);
                    $('.cartsummres').html(data['cartsum']);

                    //ShowCart();
                },
                error: function () {
                    alert('Error!');
                }
            })
        }
    });


    $(".emptycart").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm("Are you sure you want to delete all from cart?")) {
            $.ajax({
                url: '/cart/clear',
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    if (!data) alert('Error!');
                    var data = jQuery.parseJSON(data);
                    var time = 100;
                    $(".cartrowpr").each(function (index) {
                        $(this).delay(100 * index).hide(100);
                        setTimeout(function () {
                            $(this).detach()
                        }, time);
                        $('.cartcountres').html(0);
                        $('.cartsummres').html(0);
                        time += 100;
                        $('.carttable').delay(time + 1000).hide(500);

                    });

                    //ShowCart();
                },
                error: function () {
                    alert('Error!');
                }
            })
        }
    });


    $(".checkoutcart").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/cart/checkout',
            data: {id: id},
            type: 'GET',
            success: function (data) {
                
                if (!data) alert('Error!');
                var data = jQuery.parseJSON(data);

                //ShowCart();
            },
            error: function () {
                alert ('Error!');
            }
        })
    });


    $(".project-download").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        var link='/download/project?id='+id;


        function loadDocument(url) {
            var iframeId = 'loadDocument';

            var iframe = document.getElementById(iframeId);
            if (!iframe)
            {
                iframe = document.createElement('iframe');
                iframe.setAttribute('id', iframeId);
                iframe.style.display = 'none';
                document.body.appendChild(iframe);
            }

            iframe.src = url;
        }

        loadDocument(link);

    });

    $(".deletemyproject").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        var link='/catalog/delete/'+id;
        if (confirm("Are you sure you want to delete?")) {

            $.ajax({
                url: link,
                data: {id: id},
                type: 'POST',
                success: function (data) {

                    if (!data) alert('Error!');
                    var data = jQuery.parseJSON(data);

                    var selector = "#project_" + id;
                    $(selector).hide(500);
                    setTimeout(function () {
                        $(selector).detach()
                    }, 1000);


                    console.log(data);

                    //ShowCart();
                },
                error: function () {
                    alert('Error!');
                }
            })
        }

    });

    $(".publishproject").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var thclass = $('a.state_'+id).toggleClass("btn-success btn-default");
        var lbclass = $('span.label.state_'+id).toggleClass("label-success label-warning");


        $.ajax({
            url: '/profile/publishproject',
            data: {id: id},
            type: 'POST',
            success: function (data) {

                if (!data) alert('Error!');
                var data = jQuery.parseJSON(data);
                console.log(data);
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