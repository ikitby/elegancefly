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
        });
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
        });
    });

    $(".delete_cache_request").on('click', function(e){
        e.preventDefault();
        if (window.confirm("Удалить запись о запросе обналички без уведомления пользователя?")) {
            var id = $(this).data('id');
            var event_id = $(this).data('event');

            $.ajax({
                url: '/admin/cachereqdel',
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

                        $.pjax.reload({container: '#refreshevent'});

                    } else {

                        alert('Невнятный ответ сервера!');

                    }
                },
                error: function () {
                    alert('Error!');
                }
            });
        }
    });

    $(".refuse_cache_request").on('click', function(e){
        e.preventDefault();
        if (window.confirm("Отказать в обналичке с уведомлением пользователя по email?")) {
            var id = $(this).data('id');
            var event_id = $(this).data('event');

            $.ajax({
                url: '/admin/cachereqrefuse',
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

                        $.pjax.reload({container: '#refreshevent'});

                    } else {

                        alert('Невнятный ответ сервера!');

                    }
                },
                error: function () {
                    alert('Error!');
                }
            });
        }
    });

    $(".approve_cache_request").on('click', function(e){
        e.preventDefault();

            var id = $(this).data('id');
            var event_id = $(this).data('event');

            //ShowDisclamer();

            $.ajax({
                url: '/admin/cachereqappr',
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

                        $.pjax.reload({container: '#refreshevent'});

                    } else {

                        alert('Невнятный ответ сервера!');

                    }
                },
                error: function () {
                    alert('Error!');
                }
            });

    });

    function ShowDisclamer() {
        $('#moddisclamer').modal();
    }

});