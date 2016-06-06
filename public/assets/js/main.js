// Create a modal instance.
var $m = $('body').modal(),

// Access an instance API
vnr_modal = $m.data('modal');
window.$m = $m;

$(".fancybox").fancybox({
    openEffect	: 'none',
    closeEffect	: 'none'
});

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

// Bind a click event to copy a hidden elements content into the modal window
$(document).on('click', '.modal', function(e)
{
    e.preventDefault();
    vnr_modal.open( document.getElementById('vnr-modal').innerHTML );
});

// popover
$('[data-toggle="popover"]').popover();

$('body').delegate('.popover input[type="text"]', 'focusout', function() {
    console.log($(this).val());
    $('input[name="'+$(this).attr('id')+'"]').val($(this).val());
});

$('.input-tags').selectize({
    plugins: ['remove_button', 'restore_on_backspace'],
    delimiter: ',',
    persist: false,
    create: function(input) {
        return {
            value: input,
            text: input
        }
    }
});

// masks
$('body').delegate('.numeric', 'focusin', function() {
    $(this).numeric();
});
$('body').delegate('.moeda, .price, .decimal', 'focusin', function() {
    $(this).priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
});


// ajax modal (forms)
$(document).on('click', '.ajax-modal', function(e, f)
{
    e.preventDefault();

    var formUrl = $(this).data('url') || $(this).data('href'),
    formUrlFromField = $(this).data('url-from-field');
    serialize = $(this).data('serialize-from'),
    title = $(this).attr('title') || $(this).data('title'),
    method = $(this).data('method') || 'get',
    target = $(this).data('target') || false,
    modal = $('#vnr-modal');

    if ( formUrlFromField!=undefined ) {
        formUrl = $(formUrlFromField).val();
        title = $(formUrlFromField+' :selected').text();
        if ( formUrl=='' ) {
            alert('Selecione um valor válido!');
            return;
        }
    }

    if ( title ) {
        $(modal).find('.modal-header h3').html(title);
    }

    serializedData = null;
    if ( serialize ) {
        serializedData = $(serialize).serializeAny();
    }

    if ( target ) {

        content = $(target).children().clone();

        $(modal).find('.modal-body').html( content );

    } else {

        // loading
        $(modal).find('.modal-body').html('<div class="loading-large form-main-loading" id="loading-large"></div>');


        $.ajax({
            url: formUrl,
            data: serializedData,
            type: method,
            success: function(data) {
                $(modal).find('.modal-body').css('height', '100%').html(data);
            }
        });

    }

    $(modal).find('.modal-footer').html('');
    vnr_modal.open( document.getElementById('vnr-modal').innerHTML );

});
