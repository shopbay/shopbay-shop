function loadsignupform(orderNo)
{
    closesmodal('#page_modal');
    $.ajax({
        url: "/account/signup/customer/order/"+orderNo,
        dataType: 'json',
        cache: false,
        success: function(data) {
            refreshsignupform(data.modal,orderNo);      
        }
    });
}

function registercustomeraccount(orderNo)
{
    if ($('#html_page').length>0){
        console.log('registercustomeraccount for html_page');
        $('#html_page #page_modal .page-loader').show(); 
    }
    else {
        $('#page_modal .page-loader').show(); 
    }
    
    $.post('/signup/customer/order/'+orderNo, $('#signup-customer-form').serialize(), function(data) {
        refreshsignupform(data.modal,orderNo);
        if (data.removeFlash){
            $(data.removeFlash).remove();
        }
    })
    .error(function(XHR) { 
        error(XHR); 
    });
}

function refreshsignupform(data,orderNo)
{
    if ($('#html_page').length>0){
        console.log('refreshsignupform for html_page');
        $('#html_page #page_modal').replaceWith(data);
    }
    else {
        $('#page_modal').replaceWith(data);
    }
    $(document).ready(function () {/*need to call this as smodal.js seems is not triggered*/
        $('.smodal-wrapper .smodal-content').click(function(event) {
            event.stopPropagation();
        });
        $('.smodal-wrapper .smodal-content').css({overflow:'hidden'});
        $('.chzn-select-state').chosen();
        $('.chzn-select-country').chosen();
        $('#signup-button').button([]).click(function(){registercustomeraccount(orderNo);});
    });    
}