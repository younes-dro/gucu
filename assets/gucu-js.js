;(function($){

        $(document).ready(function(){
        $('li.gucu-thumb').on('click', function (){
            
          var postUrl = $(this).find('a').prop('href');
          window.location.href = postUrl;
          
        });
        $('a.parent-cat').on('click' , function(e){
            e.preventDefault();
        });
        $('span.gucu-open').on('click', function(e){
            
            $(this).toggleClass('ion-ios-add-circle-outline');
            $(this).toggleClass('ion-ios-remove-circle-outline');
            $(this).siblings('.gucu-sub-child-cats').toggleClass('open');
        });
    });
})(jQuery);


