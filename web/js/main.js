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
                console.log(res);
            },
            error: function () {
                alert ('Error!');
            }
        })
    });


});