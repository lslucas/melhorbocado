$(function() {

    $('.tip').tooltip();

    if ( $('#phone').length ) {
        $('#verPhone').on('click', function() {
            $('#phone').slideToggle();
        });
    }

    if ( $('.split-list').length ) {
        numCols = Math.floor($('.split-list li').length/10);
        if ( numCols<1 )
            numCols=1;
        else if ( numCols>6 )
            numCols=6;

        var num_cols = numCols,
        container = $('.split-list'),
        listItem = 'li',
        listClass = 'sub-list';

        container.each(function() {
            var items_per_col = new Array(),
            items = $(this).find(listItem),
            min_items_per_col = Math.floor(items.length / num_cols),
            difference = items.length - (min_items_per_col * num_cols);
            for (var i = 0; i < num_cols; i++) {
                if (i < difference) {
                    items_per_col[i] = min_items_per_col + 1;
                } else {
                    items_per_col[i] = min_items_per_col;
                }
            }
            for (var i = 0; i < num_cols; i++) {
                $(this).append($('<ul ></ul>').addClass(listClass));
                for (var j = 0; j < items_per_col[i]; j++) {
                    var pointer = 0;
                    for (var k = 0; k < i; k++) {
                        pointer += items_per_col[k];
                    }
                    $(this).find('.' + listClass).last().append(items[j + pointer]);
                }
            }
        });
    }

});


  function checkMail(mail)
  {
  	var er = new RegExp(/^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9])?/);

  	if (typeof(mail) == "string")
  	{
  		if (er.test(mail)) { return true; }
  	}
  	else if (typeof(mail) == "object")
  	{
  		if (er.test(mail.value))
  		{
  			return true;
  		}
  	}
  	else
  	{
  		return false;
  	}
  }
