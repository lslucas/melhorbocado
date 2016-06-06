$(function() {

    // conta total de itens no carrinho
    $.get( '/admin/cart/1', function(resp) {
        $('.cart-count').text(resp.length);

        // marca lista de produtos com os já ativos
        $('ul.produtos li').each(function(index) {

            wrap = $(this);
            obj  = wrap.find('.action-bar:first :input').serializeObject();

            resp.forEach(function(cartItem) {
                if ( cartItem.id==obj.id )
                    wrap.addClass('active');
            });

        });
    });

    $('.add-cart').on('click', function() {
        wrap = $(this).parent().parent().parent();
        obj  = wrap.find('.action-bar:first :input').serializeObject();

        // valida se foi preenchido os campos
        if ( obj.qtd=='' ) {
            wrap.find('input[name="qtd"]').focus();
            alert('Digite uma quantidade!');
            return false;
        }

        if ( obj.sabor=='' ) {
            wrap.find('select[name="sabor"]').focus();
            alert('Selecione um sabor!');
            return false;
        }

        $.ajax({
            url: '/admin/cart/create',
            data: obj,
            complete: function (obj, status) {

                // mostra msg de erro
                if ( obj.status!=200 )
                    alert(obj.responseText);

                $('.cart-count').text(obj.responseText);
                // add classe active aos itens que foram adicionados ao carrinho
                wrap.addClass('active');
            }
        });
    });
});

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
