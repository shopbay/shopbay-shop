function selectall(form) 
{
    $('input[name="'+form+'-checkbox"]').each(function()
    {
        this.checked = true;
    });
    gettotal('all',form);
}
function selectnone(form) 
{
    $('input[name="'+form+'-checkbox"]').each(function() {
        this.checked = false;
    });
    gettotal('none',form);
}
function gettotal(scope,form) 
{
    var serialize = '';
    $('input[name="'+form+'-checkbox"]').each(function()
    {
        var id = $(this).attr('value');
        serialize += id+',';
    });

    $.ajax({
      url: '/cart/management/totalget/scope/'+scope+'/k/'+serialize,
      data: '',
      type: 'get',
      contentType: 'application/json',
      datatype: 'json',
      beforeSend:function(){
        $('.page-loader').show();
      },
      success: function(data) {
        if (data.status=='success') {
            $.each(data.subtotals, function(idx,shipping) {
                _updateSubtotal(shipping);
            });
            _updateTotal(data.total);
            _updateTax(data.tax);
        }
        $(".page-loader").hide();   
      },
      error: function(XHR) {
            error(XHR);
      }
    });
}
function removebatch(form) 
{
    var serialize = '';
    var serializeName = '';
    $('input[name="'+form+'-checkbox"]').each(function()
    {
        if ($(this).prop('checked')){
            var id = $(this).attr('value');
            serialize += id+',';
            serializeName += '* '+$(this).parent().parent().find('a').html()+'\r\n';
        }
    });

   if (serialize.length>0) {
        if (confirm('Do you want to remove item(s) below from shopping cart?\r\n'+serializeName) ) {
            removeitem(serialize);
       }
   }
   else 
        alert('You have not selected any item to remove.');
}
/*remove item by ids, can be one or more than one*/
function removeitem(ids){
    $.ajax({
        url: '/cart/management/itemremove/k/'+ids,
        data: '',
        type: 'get',
        contentType: 'application/json',
        datatype: 'json',
        beforeSend:function(){
            $('.page-loader').show();
        },
        success: function(data) {
            $('#flash-bar').html('');
            if (data.status=='full_empty') {
                $('#shopping_cart .main-view .body').html(data.emptyCart);
                $('.offcanvas-cart').html(data.emptyCart);
                _updateCartCounter(0);
                $('#cart_shop_count').remove();
            }
            if (data.status=='shop_empty') {
                _updateCartCounter(data.total_count);
                if ($('#cart_page').length>0){
                    $('#shopping_cart .main-view .body').html(data.emptyCart);
                }
                else {
                    data.shop_count>1?$('#cart_shop_count_value').val(data.shop_count):$('#cart_shop_count').remove();
                    $('.section-'+data.form).remove();
                }
            }
            if (data.status=='half_empty') {
                $('#cart_shop_count_value').val(data.shop_count);
                _updateCartCounter(data.total_count);
                $('#'+data.form).find('.items-counter').html(data.item_count);
                $('#'+data.form).find('.cart-shop').html(data.cart);
                $('.offcanvas-cart .cart-item.'+data.itemkey).remove();
                _updateTotal(data.total);
            }
            refreshform();
            $(".page-loader").hide();   
//            scrolltop();
        },
        error: function(XHR) {
            $(".page-loader").hide(); 
            error(XHR);
        }
    });    
}
function itemcheckout(item) 
{
    $.ajax({
        url: '/cart/management/itemcheckout/k/'+item.val()+'/v/'+item.prop('checked'),
        data: '',
        type: 'get',
        contentType: 'application/json',
        datatype: 'json',
        beforeSend:function(){
           $('.page-loader').show();
        },
        success: function(data) {
            if (data.status == 'success') {
                _updateSubtotal(data.subtotal);
                _updateTotal(data.total);
                _updateTax(data.tax);
                _updateCartCounter(data.count);
            }
            $(".page-loader").hide();   
        },
        error: function(XHR) {
            $(".page-loader").hide();   
            error(XHR);
        }
    });

}
function onpromocode(name)
{
    $('#'+name).bind("change",function(e){setpromocode($(this));});
    $('#'+name).bind("enterKey",function(e){setpromocode($(this));});
    $('#'+name).keyup(function(e){if(e.keyCode == 13) $(this).trigger("enterKey");});
}
function setpromocode(obj)
{
    $.ajax({
        url: '/cart/management/promocodeset/s/'+obj.data('shop')+'/c/'+obj.val().toUpperCase(),
        data: '',
        type: 'get',
        contentType: 'application/json',
        datatype: 'json',
        beforeSend:function(){
           $('.page-loader').show();
        },
        success: function(data) {
            /*reset*/
            $('.cart-promocode .promocode-column input').removeClass('error');
            $('.cart-promocode .promocode-column .found').remove();
            $('.cart-promocode .promocode-column .error').remove();
            $(data.message).insertAfter('.cart-promocode .promocode-column input');
            if (data.status == 'failure') {
                $('.cart-promocode .promocode-column input').addClass('error');
            }
            _updateTotal(data.total);
            _updateTax(data.tax);
            $(".page-loader").hide();   
        },
        error: function(XHR) {
            $(".page-loader").hide();   
            error(XHR);
        }
    });    

}
function _updateCartCounter(value)
{
    if (value==0){
        $('.nav-menuitems').find('.cart.counter').remove();
        $('.mobile-cart').find('.cart.counter').remove();
    }
    else {
        $('.nav-menuitems').find('.cart.counter').text(value);
        $('.mobile-cart').find('.cart.counter').text(value);
    }
    
}
function _updateAffinityItem(itemkey,quantity,subtotal)
{
    $('#item_quantity_'+itemkey).html(quantity);
    $('#subtotal_'+itemkey).html(subtotal);
}
function _updateSubtotal(subtotal)
{
    $.each(subtotal, function(shipping, subtotal) {
        $('#items_subtotal_'+shipping).html(subtotal.price);
        $('#shippingRate_subtotal_'+shipping).html(subtotal.shipping_rate);
        $('#shippingSurcharge_subtotal_'+shipping).html(subtotal.shipping_surcharge);
        $('#weights_subtotal_'+shipping).html(subtotal.weight);
        if (subtotal.weight>0)
            $('#weights_subtotal').show();
    });
}
function _updatePromocodeTotal(data)
{
    $('#promocode_total_'+data.campaign.shop).parent().removeClass('hidden');
    $('#promocode_total_'+data.campaign.shop).html(data.discount_text);
    $('#promocode_total_'+data.campaign.shop).parent().find('.promocode-tooltip-content').html(data.discount_tip);
    $('#promocode_total_'+data.campaign.shop).parent().find('.promocode-label').html(data.campaign.text[data.locale]);
}
function _updatePromocodeFreeShipping(data)
{
    $('#shippingFee_discount_'+data.campaign.shop).parent().find('.promocode-tooltip-content').html(data.discount_tip);
}
function _updateTotal(total)
{
    $.each(total, function(shop, total) {
        $('#items_total_'+shop).html(total.price);
        /* update sale discount */
        if (total.has_sale){
            $('#discount_total_'+shop).parent().removeClass('hidden');
            $('#discount_total_'+shop).html(total.sale_data.discount_text);
        }
        else
            $('#discount_total_'+shop).parent().addClass('hidden');
        
        /* update promocode discount */
        if (total.has_promo){
            $('#promocode_total_'+shop).parent().removeClass('hidden');
             _updatePromocodeTotal(total.promo_data);
        }
        else
            $('#promocode_total_'+shop).parent().addClass('hidden');
        
        $('#shippingFee_total_'+shop).html(total.shipping_rate);
        /* update promocode free shipping */
        if (total.free_shipping){
            $('#shippingFee_discount_'+shop).parent().removeClass('hidden');
            $('#shippingFee_discount_'+shop).html('-'+total.shipping_rate);
            if (total.shipping_data.by_sale)
                _updatePromocodeFreeShipping(total.sale_data);
            if (total.shipping_data.by_promo)
                _updatePromocodeFreeShipping(total.promo_data);
        }
        else
            $('#shippingFee_discount_'+shop).parent().addClass('hidden');
        
        $('#grand_total_'+shop).html(total.grand_total);
    });
}
function _updateTax(tax)
{
    $.each(tax, function(shop_tax, total) {
        $('#tax_total_'+shop_tax).html(total);
    });
}
function validateqty(item) 
{
    $.ajax({
        url: '/cart/management/itemvalidate/f/'+item.attr('data-name')+'/v/'+item.val(),
        data: '',
        type: 'get',
        contentType: 'application/json',
        datatype: 'json',
        beforeSend:function(){
            $('.page-loader').show();
        },
        success: function(data) {
            if (data.status == 'failure') {
                $('#flash-bar').html(data.flash);
            }
            if (data.status == 'success') {
                $('#flash-bar').html('');
                item.parent().parent().parent().find('#subtotal').html(data.item_subtotal);
                $('#'+data.form).find('.items-counter').html(data.item_count);
                _updateAffinityItem(data.item_key,data.item_affinity_quantity,data.item_affinity_subtotal);
                _updateSubtotal(data.subtotal);
                _updateTotal(data.total);
                _updateTax(data.tax);
                _updateCartCounter(data.count);
            }
            $(".page-loader").hide();
        },
        error: function(XHR) {
            error(XHR);
        }
    });


}

function precheckout(form)
{
    var chk = 0;
    $('input[name="'+form+'-checkbox"]').each(function(){
        if ($(this).prop('checked')==true){
           chk++;
        }
    }); 
    //verify at least one item must be checked out
    if (chk==0) {
        alert('Pleae select at least one item for checkout');
        return false;
    }    
    else {
        return true;
    }
}

function checkoutfromfacebook(form,url)
{
    if (precheckout(form)==true) {
        var serialize = '';
        $('input[name="'+form+'-checkbox"]').each(function()
        {
            if ($(this).prop('checked')){
                var id = $(this).attr('value');
                serialize += id+',';
            }
        });
        var carturl = url+'?items='+serialize;
        window.open(carturl);
    }
}

function offcanvascheckout(path)
{
    var serialize = '';
    $('.offcanvas-cart .cart-item').each(function(){
        serialize += $(this).data('itemKey')+',';
    });

    $.get(path+'&items='+serialize,function(data) {

        $('.page-loader').hide();
        closeoffcanvascartmenu();
        if (data.status=='loginrequired'){
            window[data.loginMethod](data.checkout);
        }
        else {
            //when a user is login, each checkout will be a POST request
            document.open();
            document.write(data);
            document.close();
        }
    })
    .error(function(XHR) { error(XHR); });    
}

function checkout(method,form,path) 
{
    $('#page_modal .page-loader').show(); 
    if (precheckout(form)==false) {
        $('.page-loader').hide(); 
        return false;
    }
    
    if (method=='POST'){
         $.post(path,$('#'+form).serialize(), function(data) {
            if (data.redirect==true){
                window.location.href = data.url;//redirect
            }
            else {
                refreshcheckoutpage(data);
                $('.page-loader').hide(); 
                scrolltop();
            }
          })
          .error(function(XHR) { error(XHR); });
    }
    else {

        var serialize = '';
        $('input[name="'+form+'-checkbox"]').each(function()
        {
            if ($(this).prop('checked')){
                var id = $(this).attr('value');
                serialize += id+',';
            }
        });
        
        $.get(path+'&items='+serialize,function(data) {

            $('.page-loader').hide(); 
            if (data.status=='loginrequired'){
                window[data.loginMethod](data.checkout);
            }
            else {
                //when a user is login, each checkout will be a POST request
                document.open();
                document.write(data);
                document.close();
            }
        })
        .error(function(XHR) { error(XHR); });
    }
}
function refreshcheckoutpage(data)
{
    if (data.redirect){
        window.location.href = data.redirect;
        return;
    }
    
    if ($('#cart_page').length>0)
        $('#cart_page').html(data.html);
    else if ($('#cart_template').length>0)
        $('#cart_template').html(data.html);
    else
        $('.page-container').html(data.html);
    
    if (data.noncheckout_count==0)
        $('#navmenu-cart-counter').remove();
    else
        $('#navmenu-cart-counter').html(data.noncheckout_count);
    $('.page-loader').hide(); 
    scrolltop();
    refreshform();
}
function like(form) 
{
    var serialize = '';
    var serializeName = '';
    $('input[name="'+form+'-checkbox"]').each(function()
    {
        if ($(this).prop('checked')){
            var id = $(this).attr('pid');
            serialize += id+',';
            serializeName += '* '+$(this).parent().parent().find('a').html()+'\r\n';
        }
    });
 
    if (serialize.length>0) {
        $('#page_modal .page-loader').show(); 
        $.ajax({
            url: '/likes/management/batch/ids/'+serialize,
            data: '',
            type: 'get',
            contentType: 'application/json',
            datatype: 'json',
            success: function(data) {
                $('#flash-bar').html(data.flash);
                $('.page-loader').hide(); 
                scrolltop();
            },
            error: function(XHR) {
                  error(XHR);
            }
        });
    }
    else 
        alert('You have not selected any item to like');
}

function proceed(url)
{
    $('#page_modal .page-loader').show(); 

    $.post(url, $('form').serialize(), function(data) {
        refreshcheckoutpage(data);
    })
    .error(function(XHR) { 
        error(XHR); 
    });
}

function previous(url)
{
    $('#page_modal .page-loader').show(); 

    $('#flash-bar').html('');//clear flash message
    
    $.post(url, $('form').serialize(), function(data) {
        refreshcheckoutpage(data);
    })
    .error(function(XHR) { 
        error(XHR); 
    });
}
/* below are called when payment is selected; see enablebutton() */
function paypalexpresscheckout(shop,override,redirect)
{
    if (override==undefined)
        override = 0;
    if (redirect==undefined)
        redirect = false;
    var url = '/cart/management/paypalexpresscheckout/shop/'+shop+'/override/'+override;
        
    if (redirect){
        window.location.href = url;
        return;
    }
    
    $('#page_modal .page-loader').show(); 
    $.get(url,function(data) {
        $('.page-loader').hide(); 
        if (data.status=='loginrequired'){
            window[data.loginMethod](data.checkout);
        }
        else {
            if (data.redirect){
                window.location.href = data.url;
                return;
            }
        }
    })
    .error(function(XHR) { error(XHR); });    
}

function copyaddress() 
{
    if ($('#shipping_copyaddr').prop('checked') ){
        $('#page_modal .page-loader').show(); 
        $.post('/cart/management/addressget',$('form').serialize(), function(data) {

            if (data.status=='failure'){
                $('#copyAddrErr').html(data.message).css('background','lightpink');
                $('#copyAddrErr').show();
                $('#copyAddrMsg').hide();
            }
            else {
                $('.errorSummary').remove();
                $('.errorMessage').remove();
                $('#copyAddrErr').hide();
                $('#copyAddrMsg').show();
                $('#CartAddressForm_recipient').val(data.recipient);
                $('#CartAddressForm_mobile').val(data.mobile);
                $('#CartAddressForm_address1').val(data.address1);
                $('#CartAddressForm_address2').val(data.address2);
                $('#CartAddressForm_postcode').val(data.postcode);
                $('#CartAddressForm_city').val(data.city);
                $('#CartAddressForm_state').val(data.state);
                $('#CartAddressForm_country').val(data.country);
                if ($('.chzn-select-country').length>0){
                    $('.chzn-select-country').val(data.country);
                    $('.chzn-select-country').trigger("liszt:updated");
                }
                if ($('.chzn-select-state').length>0){
                    updatestatedropdownlist(data.country,data.state);
                }
            }
            $('.page-loader').hide(); 
        })
        .error(function(XHR) { 
            $('.page-loader').hide(); 
            error(XHR); 
        });
    }
    else {
        $('#copyAddrErr').hide();
        $('#copyAddrMsg').show();
        $('#CartAddressForm_recipient').val('');
        $('#CartAddressForm_mobile').val('');
        $('#CartAddressForm_address1').val('');
        $('#CartAddressForm_address2').val('');
        $('#CartAddressForm_postcode').val('');
        $('#CartAddressForm_city').val('');
        $('#CartAddressForm_state').val('');
        if ($('.chzn-select-state').length>0){
            $('.chzn-select-state').val('');
            $('.chzn-select-state').trigger("liszt:updated");
        }
        $('#CartAddressForm_country').val('');
        if ($('.chzn-select-country').length>0){
            $('.chzn-select-country').val('');
            $('.chzn-select-country').trigger("liszt:updated");
        }
    }

}
/* select payment method by payment method id */
function selectmethod(mid,url) 
{
    $('[id^=method-]').hide();
    enablebutton(mid,url);
}

function enablebutton(mid,url) 
{
    $.ajax({
        url: url,
        data: '',
        type: 'get',
        contentType: 'application/json',
        datatype: 'json',
        beforeSend:function(){
            $('#page_modal .page-loader').show();
        },
        success: function(data) {
            $('#CartPaymentMethodForm_method').val(data.buttonMethod);
            if (data.callback){
                overridebuttonbehavior(data.callback.code);
            }
            else {
                $('#method-'+mid).show();
                $('#'+data.buttonName).button({disabled:data.buttonDisable});
                $('#'+data.buttonName).unbind('click');
                $('#'+data.buttonName).attr('onclick', data.buttonClick.code);
                $(".page-loader").hide();
            }
        },
        error: function(XHR) {
            error(XHR);
        }
    });
}
function scrolltoform()
{
    var new_position = $('.form').offset();
    window.scrollTo(new_position.left,new_position.top);
}
function refreshform()
{
    if ($('.cart-item-quantity').length>0){
        $('.cart-item-quantity').on('change',function(){
            validateqty($(this));
        });
    }
    if ($('.chzn-select-country').length>0){
        $('.chzn-select-country').chosen();
    }
    if ($('.chzn-select-state').length>0){
        $('.chzn-select-state').chosen();
        enablestatedropdownlist();
    }
}

function overridebuttonbehavior(callback){
    var f = new Function(callback);
    f();
}

function enablestatedropdownlist()
{
    $('#CartAddressForm_country').change(function(){
        $.post('/cart/management/addressget',$('form').serialize(), function(data) {
            updatestatedropdownlist($('#CartAddressForm_country').val(),data.state);
        })
        .error(function(XHR) { 
            $('.page-loader').hide(); 
            error(XHR); 
        });
    });
}

function updatestatedropdownlist(country,state)
{
    $.get('/cart/management/stateget'+'?country='+country,function(data){
        $.each(data,function(key,value){
            if (key==state)
                $('#CartAddressForm_state').append($("<option selected />").val(key).text(value));
            else
                $('#CartAddressForm_state').append($("<option />").val(key).text(value));
        });
        if(data.length===0){
            $('#CartAddressForm_state').append($("<option />").val('').text(''));
        }
        $('.chzn-select-state').trigger("liszt:updated");
    })
    .error(function(XHR){
        alert(XHR.status+' '+XHR.statusText+': '+XHR.responseText);
    });
}
