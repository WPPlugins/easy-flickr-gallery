jQuery(document).ready(function(){
    var page = 2;


    var getMore = true;
    jQuery(window).scroll(function () {
        var length=0;
        var res=jQuery('#result div:last').offset().top;
        var height=jQuery('#result div:last').height();
        length=res+height;
        if(getMore && jQuery(window).scrollTop() + jQuery(window).height() > length) {

            jQuery('#more').show();
            getMore = false;
            efpgdata.page_num= page;
            if((page-1) * efpgpage.page_count >= efpgpage.actual_count){
                jQuery('#more').hide();
                jQuery('#no-more').show();
                setTimeout(function(){ jQuery('#no-more').hide()},5000);
            }else{
                jQuery.ajax({
                    type: "POST",
                    url: efg_ajaxurl,
                    data:efpgdata,
                    success: function(res) {
                        if(res!='nodata'){
                            jQuery('#more').hide();
                            jQuery('#no-more').hide();
                            jQuery("#result").append(res);

                            var $container = jQuery('#result');

                            $container.imagesLoaded( function(){
                                $container.masonry('reload');
                            });
                            jQuery('#more').hide();
                            jQuery('#no-more').hide();
                            page++;
                            //console.log(res);
                            getMore = true;
                        } else{
                            getMore = true;
                        }
                    }
                });
            }

        }


    });
})