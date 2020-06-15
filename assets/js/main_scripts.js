jQuery( function ( $ ) {
    if($('.video_upload_form').length>0){
        $('.video_upload_form').submit(function (e) { 
            e.preventDefault();
            
        });
    }
});