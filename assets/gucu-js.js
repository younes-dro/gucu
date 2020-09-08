/**
 * Created by Younes DRO
 * 
 */
;
(function ($) {


    function getSliderSettings( indexSide ) {
        return {
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            initialSlide: indexSide,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1022,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true
                    }
                },
                {
                    breakpoint: 802,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        };
    }
    
    $(document).ready(function () {
        $('div.gucu-thumb').on('click', function () {

            var postUrl = $(this).find('a').prop('href');
            window.location.href = postUrl;

        });
    });
    var isMobile = false; //initiate as false
    // device detection
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
            || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
        isMobile = true;
    }
    if (!isMobile) {
        $(document).ready(function () {
            $('select.gucu-books-bible').select2({
            });
        });
    }
    $(document).ready(function () {
        $('select.gucu-books-bible').on("select2:open", function (e) { 
//            console.log( this.value); 
            $('div.posts-numbers').addClass('open');
        });
        $('select.gucu-books-bible').on('change focus', function () {
            var book = this.value;
            $.ajax({
                type: 'POST',
                url: gucu_ajax_obj.ajaxurl,
                data: {
                    'action' : 'grid_ajax_request',
                    'book' : book,
                    'nonce': gucu_ajax_obj.nonce
                },
                beforeSend: function(){
                    $('.content-chapters').html('');
                    $('div.posts-numbers').html('');
                    $('div.content-full-post').html('');
                    $('div.posts-numbers').addClass('open');
                    $('div.posts-numbers').addClass('gucu-loader');
                },
                success: function( data){
                    $('div.posts-numbers').removeClass('gucu-loader').html( data);                  
                },
                error: function(error){
                    console.log(error);
                }
            });
        });
        
        $(document).on('click', 'span.close-chapter', function(){
           $('div.posts-numbers').removeClass('open'); 
        });
        $(document).on('click', 'a.chapter-number', function(e){
            e.preventDefault();
            var book =  $(this).data('book-id');
            var post =  $(this).data('post-id');
            var indexSide = $(this).data('slide-index');
            $('span.close-chapter').trigger('click');
            $.ajax({
                type: 'POST',
                url: gucu_ajax_obj.ajaxurl,
                data: {
                    'action': 'gucu_ajax_request',
                    'book': book,
                    'post' : post,
                    'nonce': gucu_ajax_obj.nonce
                },
                beforeSend: function () {
                    $('.content-chapters').html('');
                    $('p.content-chapters').addClass('gucu-loader');
                },
                success: function (data) {
                    $('.content-chapters').removeClass('gucu-loader').html(data);
                    $('.gucu-sub-child-cats').slick(getSliderSettings(indexSide));
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });            
            
        });
        
        $(document).on('click', 'a.read-full-post', function(e){
            e.preventDefault();
            var p = $(this).data('post-id');
            $('div.content-full-post').html('Loading....');
            
                $.ajax({
                type: 'POST',
                url: gucu_ajax_obj.ajaxurl,
                data: {
                    'action': 'full_post_ajax_request',
                    'post' : p,
                    'nonce': gucu_ajax_obj.nonce
                },
                beforeSend: function () {
                    $('div.content-full-post').html('');
                    $('div.content-full-post').addClass('gucu-loader');
                },
                success: function (data) {
                    $('div.content-full-post').removeClass('gucu-loader').html(data);
//                    console.log();
                    $scrollTop = $("#content-full-post").offset().top;
                    
                    $('html,body').animate({
                        scrollTop: $scrollTop - 80 
                    }, 'slow');
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });            
        });
    });
    
})(jQuery);


