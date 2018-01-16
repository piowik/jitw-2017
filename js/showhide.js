$(function(){
	var showHide = $('.expandable-content');
	var form = showHide.find('form');
    showHide.on('click', 'h2', function(e){
        
        if(form.is(':visible')) {
            formClose();
        }
        else {
            formOpen();
        }
        
    });

    function formOpen(){
        if(form.is(':visible')) return;
        form.slideDown();
    }

    function formClose(){
        if(!form.is(':visible')) return;
        form.slideUp();
    }


});