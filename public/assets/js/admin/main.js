$(document).ready(function (){
    Main.init();
});


// ----------
window.Main = {
    // nextBoxIndex: 0,
    // $slider: null,

    // ----------
    init: function() {
        var self = this;

        if (!window.JSON) {
          alert("Seu navegador não suporta JSON!");
          return;
        }

        //this.initDatepicker();
        this.initMasks();
        this.initBasics();
    },

    initMasks: function() {

        $('body').delegate('.numeric', 'focusin', function() {
            $(this).numeric();
        });

        $('body').delegate('.uf', 'focusin', function() {
            $(this).mask('aa');
        });

        $('body').delegate('.date', 'focusin', function() {
            $(this).mask('99/99/9999');
        });

        $('body').delegate('.cep', 'focusin', function() {
            $(this).mask('99.999-999');
        });

        $('body').delegate('.datehour', 'focusin', function() {
            $(this).mask('99/99/9999 99:99');
        });

        $('body').delegate('.date-cc', 'focusin', function() {
            $(this).mask("99/99");
        });

        $('body').delegate('.hour', 'focusin', function() {
            $(this).mask('99:99');
        });

        $('body').delegate('.2digit', 'focusin', function() {
            $(this).mask('9?9');
        });

        $('body').delegate('.phone-full', 'focusin', function() {
            $(this).mask("+99 (99) 9999-9999?9");
        });

        $('body').delegate('.phone', 'focusin', function() {
            $(this).mask("(99) 9999-9999?9");
        });

        $('body').delegate('.cpf', 'focusin', function() {
            $(this).mask("999.999.999-99");
        });

        $('body').delegate('.cnpj', 'focusin', function() {
            $(this).mask('99.999.999/9999-99');
        });

        $('body').delegate('.cc, .credit-card', 'focusin', function() {
            $(this).mask('9999-9999-9999-9999');
        });

        //price format
        $('body').delegate('.money, .price, .decimal', 'focusin', function() {
            $(this).priceFormat({
                prefix: '',
                centsSeparator: ',',
                thousandsSeparator: '.'
            });
        });

        $('body').delegate('.price3cents', 'focusin', function() {
            $(this).priceFormat({
                prefix: '',
                centsSeparator: ',',
                thousandsSeparator: '.',
                centsLimit: 3
            });
        });

        $('body').delegate('.brl', 'focusin', function() {
            $(this).priceFormat({
                prefix: 'R$ ',
                centsSeparator: ',',
                thousandsSeparator: '.'
            });
        });

    },

    initDatepicker: function() {
        //datepicker
        $('body').delegate(".date, .datepicker", 'focusin', function() {

            $(this).datepicker({
                //defaultDate: "+1w",
                format: 'dd/mm/yyyy',
                showOtherMonths: false,
                beforeShow: function(input, instance) {
                  if( !$('.tt-skin').length) {
                        $('#ui-datepicker-div').wrap('<div class="tt-skin"></div>');
                  }
                }
            });
        });

        $('body').delegate(".dateFrom", 'focusin', function() {
          $( this ).datepicker({
            format: 'dd/mm/yyyy',
            //defaultDate: "+1w",
            changeMonth: true,
            //numberOfMonths: 2,
            beforeShow: function(input, instance) {

              if( $('.dateTo').length )
                $( this ).datepicker( "option", "maxDate", $('.dateTo').val() );

              if( !$('.tt-skin').length) {
                    $('#ui-datepicker-div').wrap('<div class="tt-skin"></div>');
              }
            }
          });
        });

        $('body').delegate(".dateTo", 'focusin', function() {
          $( this ).datepicker({
            format: 'dd/mm/yyyy',
            defaultDate: "+1w",
            changeMonth: true,
            //numberOfMonths: 2,
            beforeShow: function(input, instance) {

              if( $('.dateFrom').length )
                $( this ).datepicker( "option", "minDate", $('.dateFrom').val() );

              if( !$('.tt-skin').length) {
                $('#ui-datepicker-div').wrap('<div class="tt-skin"></div>');
              }
            }
          });
        });
    },

    // ----------
    initLayout: function() {
        var self = this;

        // esconde o bg aguarde, estamos carregando algo incrível
        $('#onLoad').fadeOut(1000);

        //resize content
        this.funcSizeContent();
        $(window).resize(this.funcSizeContent);

        //nav expande e contrai
        this.funcNavToggle();
    },

    // ----------
    funcNavToggle: function() {
        var self = this;

        // accordion do menu sidebar
        $("#menu > li, #menu > li > a").click(function(){
            // if(false == $(this).next().is(':visible')) {}
            $('#menu > ul.navlist').addClass('hide');
            $('#menu > ul').slideUp(300);
            if ($(this).next('ul').length && false == $(this).next('ul').is(':visible')) {
                var id = $(this).next().attr('id');
                $('li.'+id).removeClass('hide');
                $(this).next().slideDown(300).removeClass('hide');
            }

            $('#menu li').removeClass('active');
            $(this).addClass('active');

            self.funcSizeContent(); //resize if needed
        });

        //active dos subitens da sidebar
        $(".navlist > li").click(function(){
            $('.navlist > li').removeClass('active');
            $(this).addClass('active');
        });

    },

    // ----------
    funcSizeContent: function(options) {
        var docHeight = $(document).height(),
        docWidth = $(document).width(),
        navWidth = $('nav').width(),
        navHeight = $('nav').height(),
        visibleNavUlMenu = $('ul.navlist:visible > li').length*47,
        headerHeight = $('header').height(),
        footerHeight = $('footer').height(),
        contentWidth = docWidth,
        contentHeightDoc = docHeight-(headerHeight+footerHeight),
        contentHeightOld = $('section.tabs-container')[0].scrollHeight;

        if (options!='hidden')
            contentWidth = docWidth-navWidth;

        if ($('.navlist:visible'))
            var navHeight =  navHeight+visibleNavUlMenu;

        var contentHeight = Math.max(navHeight, contentHeightOld);

        $('section.tabs-container').height('100%');
        $('section.tabs-container').width(contentWidth+'px');
        $('nav').height(contentHeight+'px');
        // $('section.tabs-container').height(contentHeight+'px');
    },

    // ----------
    initTabs: function() {
        // movido para o inicio da pagina para que possa compartilhar as funcoes e variaveis com outros js's
    },

    // ----------
    initBasics: function() {
        var self = this;

        //external links always  new tab: _blank
        //$("a[href^='http://']").attr("target", "_blank");

        $('body').delegate('input.checkAll', 'change', function() {
            var target = $(this).data('target');

            //$(target).attr('checked', $(this).is(':checked'));
            $(target).trigger('click');
        });

    },

    // ----------
    clearAll: function() {
        $(".box").remove();
        localStorage.clear();
    },

    // ----------
    saveBox: function($box) {
        var position = $box.position();
        localStorage[$box.attr("id")] = JSON.stringify({
          left: position.left,
          top: position.top,
          hue: $box.data("hue")
        });
    },

    // ----------
    removeBox: function($box) {
        $box.remove();
        localStorage.removeItem($box.attr("id"));
    },

};

