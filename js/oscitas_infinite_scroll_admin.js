function show_source(){
    var val=jQuery('#source').val();
    jQuery('.osc_src_option').css('display','none');
    jQuery('#efpg_'+val).css('display','block');
}
function pagination_option(){
    var val=jQuery('#efpg_gallerystyle').val();
    if(val=='paginate'){
        jQuery('.efpg_paginate').css('display','block');
    } else{
        jQuery('.efpg_paginate').css('display','none');
    }

}
jQuery(document).ready(function(){
    show_source();
    pagination_option();
    jQuery('#source').change(function(){
        show_source();
    })
    jQuery('#efpg_gallerystyle').change(function(){
        pagination_option();
    })
})