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

        $.ajax({
            url: '/cart/del',
            data: {id: id},
            type: 'GET',
            success: function (data) {
                if (!data) alert('Error!');
                var selector = "#catprodrow_"+id;
                $(selector).hide(500);
                setTimeout(function(){
                    $(selector).detach()
                }, 1000);

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


    $(".emptycart").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/cart/clear',
            data: {id: id},
            type: 'GET',
            success: function (data) {
                if (!data) alert('Error!');
                var data = jQuery.parseJSON(data);
                var time = 100;
                $(".cartrowpr").each(function (index) {
                        $(this).delay(100*index).hide(100);
                        setTimeout(function(){
                            $(this).detach()
                        }, time);
                    $('.cartcountres').html(0);
                    $('.cartsummres').html(0);
                    time += 100;
                $('.carttable').delay(time+1000).hide(500);

                });

                //ShowCart();
            },
            error: function () {
                alert ('Error!');
            }
        })
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

        var link='http://elegancefly.loc/download/project?id='+id;


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


        //link='http://elegancefly.loc/download/project?id='+id;
/*
        $.ajax({
            url: '/download/project',
            data: {id: id},
            type: 'GET',
            success: function () {
                console.log("OK!");
                //ShowCart();
            },
            error: function () {
                alert ('Error!');
            }
        })
        */
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