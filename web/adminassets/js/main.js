$(function () {
//cart logic

    $(".approve_request").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var event_id = $(this).data('event');

        $.ajax({
            url: '/admin/userup',
            data: {id: id, event_id: event_id},
            type: 'POST',
            success: function (data) {
                if (!data) alert('Error!');

                if (data == 'ok') {

                    var selector = "#eventid_" + id;
                    $(selector).hide(50);
                    setTimeout(function () {
                        $(selector).detach()
                    }, 50);
                    $.pjax.reload({container : '#refreshevent'});

                } else {

                    alert ('Невнятный ответ сервера!');

                }
            },
            error: function () {
                alert ('Error!');
            }
        })
    });

    $(".refuse_request").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var event_id = $(this).data('event');

        $.ajax({
            url: '/admin/userref',
            data: {id: id, event_id: event_id},
            type: 'POST',
            success: function (data) {
                if (!data) alert('Error!');

                if (data == 'ok') {

                    var selector = "#eventid_" + id;
                    $(selector).hide(10);
                    setTimeout(function () {
                        $(selector).detach();
                    }, 10);

                    $.pjax.reload({container : '#refreshevent'});

                } else {

                    alert ('Невнятный ответ сервера!');

                }
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