$(function () {

    $(function() {
        $('[rel="popover"]').popover({
            placement: "top",
            html: true,
            title: "",
            trigger: "hover"
        });
    });

//PROMO logic
    $(".prodpromoset").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var pid = $(this).data('pid');

        $.ajax({
            url: '/promo/accept',
            data: {id: id, pid: pid},
            type: 'POST',
            success: function (data) {
                if (!data) alert('Ahtung!');
            },
            error: function () {
                alert ('Error!');
            }
        });
    });



   $(".prodpromodel").on('click', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var pid = $(this).data('pid');

            $.ajax({
                url: '/promo/reject',
                data: {id: id, pid: pid},
                type: 'POST',
                success: function (data) {
                    if (!data) alert('Ahtung!');

                },
                error: function () {
                    alert ('Error!');
                }
            });
        });

    $(".add-to-cart").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/cart/add',
            data: {id: id},
            type: 'GET',
            success: function (data) {
                //if (!data) alert('Error!');
                var data = jQuery.parseJSON(data);
                $('.cartcountres').html(data['cartcount']);
                $('.cartsummres').html(data['cartsum']);
                //ShowCart();
            },
            error: function () {
                alert ('Error!');
            }
        });
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
                    ///if (!data) alert('Error!');
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
                    //if (!data) alert('Error!');
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
                alert (data);
                window.location.reload();

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

                    //if (!data) alert('Error!');
                    var data = jQuery.parseJSON(data);

                    var selector = "#project_" + id;
                    $(selector).hide(500);
                    setTimeout(function () {
                        $(selector).detach()
                    }, 1000);

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

        $.ajax({
            url: '/profile/publishproject',
            data: {id: id},
            type: 'POST',
            success: function (data) {
                //if (!data) alert('Error!');
                var data = jQuery.parseJSON(data);

                if (data === true)
                {
                    $('a.state_'+id).toggleClass("btn-warning btn-success").text('Unpublish');
                    $('span.label.state_'+id).toggleClass("label-warning label-success").text("Продается");
                    //console.log(true);
                }
                else if (data === false)
                {
                    $('a.state_'+id).toggleClass("btn-success btn-warning").text('Publish');
                    $('span.label.state_'+id).toggleClass("label-success label-warning").text("Не продается");
                    //console.log(false);
                }



            },

            error: function () {
                alert ('Error!');
            }
        })
    });

    $(".limitproject").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/profile/setlimit',
            data: {id: id},
            type: 'POST',
            success: function (data) {

                $('#UniqProject .modal-body').html(data);

                //if (!data) alert('Error!');
                //var data = jQuery.parseJSON(data);
                ShowUniQuery();

            },
            error: function () {
                alert ('Error!');
            }
        })
        ShowUniQuery();
    });

    $(".setlimitproject").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/profile/setlimit',
            data: {id: id},
            type: 'POST',
            success: function (data) {
                //console.log(data);
                $('#UniqProject .modal-body').html(data);

                //if (!data) alert('Error!');
                //var data = jQuery.parseJSON(data);
                ShowUniQuery();

            },
            error: function () {
                alert ('Error!');
            }
        })

    });

    $(".send_deposit").on('click', function(e){
        e.preventDefault();
        var count = $(this).parent().parent().parent().find('.form-control').val();
        $.ajax({
            url: '/profile/deposite',
            data: {count: count},
            type: 'post',
            success: function (data) {
                //if (!data) alert('Error!');
                location.href = data;
            },
            error: function () {
                //alert ('Error!');
            }
        })

    });

    $(".profileup").on('click', function(e){
        e.preventDefault();
        ShowUpQuery();
        var id = $(this).data('id');

        $.ajax({
            url: '/profile/upgrade',
            data: {id: id},
            type: 'POST',
            success: function (data) {
                //console.log(data);


                $('#upgProfile .modal-body').html(data);

                //if (!data) alert('Error!');
                //var data = jQuery.parseJSON(data);
                ShowUpQuery();

            },
            error: function () {
                alert ('Error!');
            }
        })

    });

    $(".profilecrup ").on('click', function(e){
        e.preventDefault();

        var id = $(this).data('id');

        $.ajax({
            url: '/profile/upgrade',
            data: {imCreator: id},
            type: 'POST',
            success: function (data) {
                //console.log(data);
                if (!data) alert('Error!');
                if (data == 'ok') {
                    $('.profilecrup').fadeOut(300, function () {
                        $('.profileupli').html('<a>Profile upgrade sended!</a>').removeClass("profileup");
                    });
                }
                //$('#upgProfile .modal-body').html(data);

                //if (!data) alert('Error!');
                //var data = jQuery.parseJSON(data);
                //ShowUpQuery();

            },
            error: function () {
                alert ('Error!');
            }
        })

    });

    $("#imPainter").on('click', function(e){
        e.preventDefault();
        $.ajax({
            url: '/profile/upgrade',
            data: {imPainter: 'ok'},
            type: 'POST',
            success: function (data) {
                //console.log(data);
                if (!data) alert('Error!');
                if (data == 'ok') {
                    $('.profileup').fadeOut(300, function () {
                        $('.profileupli').html('<a>Profile upgrade sended!</a>').removeClass("profileup");
                    });

                    $('#upgProfile .modal-body').fadeOut();
                    $('#upgProfile .modal-footer').fadeOut();
                    setTimeout(function () {
                        $('#upgProfile .modal-body').fadeIn().html('<div style="text-align: center"><h3>Спасибо!</h3>Администратор получил ваш запрос и рассмотрит его в ближайшее время.</div>');
                    }, 500);
                    setTimeout(function () {
                        $('#upgProfile').modal('hide');
                    }, 3000);
                }
                //var data = jQuery.parseJSON(data);
                ShowUpQuery();

            },
            error: function () {
                alert ('Error!');
            }
        })

    });



    function ShowUpQuery(UpProfile) {
        $('#upgProfile .modal-body').html(UpProfile);
        $('#upgProfile').modal();
    }
    function ShowUniQuery(UniqProject) {
        $('#UniqProject .modal-body').html(UniqProject);
        $('#UniqProject').modal();
    }

    $('.paypal-buttonkit').on('click', function () {
        location.href = '/cart/ext-checkout/paypal';
    });

$('.depowidget .deposit_show').on('click', function () {
    $(this).hide();
    $(this).prev().show(300);
    }
);
$('.depowidget .deposit_hide').on('click', function () {
        $(this).parent().parent().parent().hide();
        $(this).parent().parent().parent().next().show(50);
    }
);

    $(".getusercashe").on('click', function(e){
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/profile/getcache',
            data: {id: id},
            type: 'POST',
            success: function (data) {

                $('.getusercashe').addClass("blackZone").attr("disabled", "disabled").html('Sended!');

                //if (!data) alert('Error!');disabled="disabled"
                //var data = jQuery.parseJSON(data);
                ShowUniQuery();
            },
            error: function () {
                alert ('Error!');
            }
        })
        ShowUniQuery();
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

        var	$window = $(window),
            $body = $('body'),
            $nav = $('#nav');

        // Breakpoints.
        breakpoints({
            wide:      [ '961px',  '1880px' ],
            normal:    [ '961px',  '1620px' ],
            narrow:    [ '961px',  '1320px' ],
            narrower:  [ '737px',  '960px'  ],
            mobile:    [ null,     '736px'  ]
        });

        // Play initial animations on page load.
        $window.on('load', function() {
            window.setTimeout(function() {
                $body.removeClass('is-preload');
            }, 100);
        });

        // Nav.
        var $nav_a = $nav.find('a');

        $nav_a
            .addClass('scrolly')
            .on('click', function(e) {

                var $this = $(this);

                // External link? Bail.
                if ($this.attr('href').charAt(0) != '#')
                    return;

                // Prevent default.
                e.preventDefault();

                // Deactivate all links.
                $nav_a.removeClass('active');

                // Activate link *and* lock it (so Scrollex doesn't try to activate other links as we're scrolling to this one's section).
                $this
                    .addClass('active')
                    .addClass('active-locked');

            });

        $(
            '<div id="headerToggle">' +
            '<a href="#header" class="toggle"></a>' +
            '</div>'
        ).appendTo($body);
        // Header.
        $('#header')
            .panel({
                delay: 500,
                hideOnClick: true,
                hideOnSwipe: true,
                resetScroll: true,
                resetForms: true,
                side: 'left',
                target: $body,
                visibleClass: 'header-visible'
            });
});