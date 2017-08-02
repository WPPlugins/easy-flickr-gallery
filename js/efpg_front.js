jQuery(document).ready(function(){
    jQuery(function(){
        var $container = jQuery('#result');
        $container.imagesLoaded( function(){
            $container.masonry({
                itemSelector : '.box'
            });
        });
    });
    jQuery('.easy-flickr-photo-gallery').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: ''
        }

    });
});