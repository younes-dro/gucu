;(function($){

        $(document).ready(function(){
        
        $('span.gucu-open').on('click', function(){
            $(this).toggleClass('ion-ios-add-circle-outline');
            $(this).toggleClass('ion-ios-remove-circle-outline');
            $(this).siblings('.gucu-sub-child-cats').toggleClass('open');
        });
    });
})(jQuery);


