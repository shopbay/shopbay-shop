/*enable contact form */
function contactus(page){
    var container = $('.form.'+page);
    $.ajax({
        type: 'POST',
        url: container.data('url')+'?'+container.find('#csrf_form').serialize(),
        data: container.find('#contact_form').serialize(),
        dataType: "json",
        success: function(data){
            container.find('#contact_form').replaceWith(data.form);
            $('.form #contact_form_send').click(function(){contactus(page);});
        },
        error: function(XHR) {
            error(XHR);
        }
    });
}
/* for guest checkout*/
function shoplogin(checkout,hostinfo) {
    if (checkout.guest){
        console.log('guest checkout');
        closesmodal('#page_modal');
        $('body').css('overflow', 'hidden');   
        var route = 'shops/login/form?returnUrl='+checkout.returnUrl;
        openmodalbyjsonp(getdomain(hostinfo)+route,{"container":"#page_modal","action":"login"});
        $("html, body").animate({scrollTop: 0}, 100);
    }
    else
        signin(hostinfo,'account/authenticate/loginform?returnUrl='+checkout.returnUrl);
}
/*this is used in facebook shop */
function addcartnewwindow(form){
    $.ajax({
        type: 'POST',
        url: $('#'+form).attr('action'),
        data: $('#'+form).serialize(),
        dataType: "json",
        success: function(data){
            if (data.status=='success')
                newwindowpage(data.url);
            else if (data.status=='loginrequired')
                signin();
            else {
                $('.flash-message').html(data.flash);
            }
        },
        error: function(XHR) {
            error(XHR);
        }
    });
}
/* this is used in shop*/
function addcart(form) {
    $('#modal_loader').show();
    $.ajax({
        type: 'POST',
        url: $('#'+form).attr('action'),
        data: $('#'+form).serialize(),
        beforeSend:function(){
            //nothing
        },
        success:function(data){
            if (data.status=='success'){
                closeoffcanvascartmenu();
                /* add to cart at off canvas*/
                $('#offcanvas_cart_menu .menu-content').html(data.cart_quickview);
                openoffcanvascartmenu();
                $('.nav-menuitems').find('.cart.counter').text(data.cart_count);
                $('.mobile-cart').find('.cart.counter').text(data.cart_count);
                /* add to cart and redirect to cart page */
                /* window.location.href = data.url; */
            }
            else if (data.status=='loginrequired')
                signin();
            else {
                $('.flash-message').html(data.flash);
            }
            $('#modal_loader').hide();
        },
        error: function(XHR) {
            error(XHR);
            $('#modal_loader').hide();
        }
    });
}
function newwindowpage(url,name){
    if (name==undefined)
        window.open(url,name);
    else
        window.open(url,'_blank');
}

function pageredirect(url){
    window.location.href = url;
}

function productview(url){
    $('#modal_loader').show();
    pageredirect(url);
}
function productmodalview(url,modalurl,previewmsg){
    $('#page_modal .page-loader').show();
    if (mobiledisplay()){
        window.location.href = url;
    }
    else {
        modalview(modalurl,previewmsg);
    }    
}
function promoview(url,previewmsg){
    if (mobiledisplay()){
        window.location.href = url;
    }
    else {
        modalview(url,previewmsg);
    }
}
/*for product and promotion modal view*/
function modalview(url,previewmsg) {
    var live = false;
    if (previewmsg==undefined)
        live = true;
    
    $('#page_modal .smodal-close').hide();/*clean up previous modal*/
    $('#page_modal .smodal-content').html('');/*clean up previous modal*/
    $.get(url, function( data ) {
        $('#page_modal .smodal-overlay').show();
        $('#page_modal .smodal-content').html(data.modal);
        $('#page_modal .smodal-container').show();
        $(document).ready(function () {
            renderForm();
            renderRating();
            $('.tooltips').tooltip();//render tooltip (bootstrap)
            $('.cartbutton button').button({'disabled':false}).click(function(){addcart($(this).attr('form'));});
            if ($('#product_view').data('inventory')==0){
                $('.cartbutton button').attr({'disabled':true});
                $('.cartbutton button').addClass('ui-state-disabled');
            }
            $('#promo-buttons button').remove();
            $('#promo-buttons').append(data.promotion_buttons);
            $('#promo-buttons button').each(function(idx, obj){
                $(this).button({disabled:false}).click(function(){viewpromo($(this).data('key'));});
            });
            if (live){
                $('.comment-modal-form button').button({'disabled':false}).click(function(){
                    var script = $('.comment-modal-form button').data('script');
                    eval(script);
                });
            }
            else {
                $('.comment-modal-form button').button({'disabled':false}).click(function(){alert(previewmsg);});
            }

            /*loadfancybox('gallery','');//uncomment for use of Yii 1.1.15*/
            loadevelatezoom();/*uncomment for use of Yii 1.1.16*/
            $('body').addClass('modal-view');/*add a class to make identifier to let css set z-index above smodal z-index 1000 to be visible*/
            //closing modal
            $(".page-container").click(function() {
                $('body').removeClass('modal-view');/*this must above smodal z-index 1000 to be visible*/
                if ($('.smodal-container').length>0)
                    unloadevelatezoom();
            });
            
        });
        $('#page_modal .page-loader').hide();
        $("html, body").animate({scrollTop:0}, 0);
    })
    .error(function(XHR) {
        $('#page_modal .page-loader').hide();
        alert(XHR.status+' '+XHR.statusText+'\r\n' + XHR.responseText);
    });
}
function postquestion(form) {
    $('#'+form+' [class^="flash"]').remove();
    $.post('/tasks/question/ask', $('#'+form).serialize(), function(data) {
        if (data.status=='loginrequired'){
            signin();
        }
        else {
            $('#'+form+' .question-form-wrapper').prepend(data.flash);
            if (data.status=='success')
                $('#'+form+' .question-form-wrapper textarea').val('');
        }
    })
    .error(function(XHR) { 
        alert(XHR.status+' '+XHR.statusText+'\r\n' + XHR.responseText);
    });
}
function comment(form) {
    $.post('/comments/management/create', $('#'+form).serialize(), function(data) {
        if (data.status=='loginrequired'){
            signin();
        }
        else {
            $('.'+form+'.postcomment').html(data.form);
            if (data.status=='success')
                $('.'+form+'.comments .items').append(data.comment);
            $('#'+form+'-comment-total').html(data.total);
            $('.'+form+' .comment-total').show();
            $('.summary').show();
            $('.comment-button').button([]).click(function(){comment($(this).attr('form'));});
        }
    })
    .error(function(XHR) { 
        alert(XHR.status+' '+XHR.statusText+'\r\n' + XHR.responseText);
    });
}
function prevdata(model,route) {
    $('.prevlink-'+model+' .page-loader .text').css({background:'white',width:0});
    $('#'+model+'_loader').show();
    $.post(route, $('#prev_'+model+'_form').serialize(), function(data) {
        if (data.prevlink=='lastpage' || data.prevlink=='onepage')
            $('.prevlink-'+model).html('');
        else
            $('.prevlink-'+model).html(data.prevlink);
        $('.prevdata-'+model).prepend(data.prevdata);
        renderRating();
        calibratepageheight();
    })
    .error(function(XHR) { 
        alert(XHR.status+' '+XHR.statusText+'\r\n' + XHR.responseText);
    });
}
function liketoggle(form){
    $.post('/likes/management/toggle', form.serialize(), function(data) {
        if (data.status=='loginrequired'){
            signin();
        }
        else {
            $('.like-'+data.type+'-button-'+data.target).html(data.button);
            $('.like-'+data.type+'-modal-button-'+data.target).html(data.button);
            /* for product */
            if ($('.like-'+data.type+'-modal-button-'+data.target).length>0)
                $('.like-'+data.type+'-total-'+data.target).html(data.total_text);
            else
                $('.like-'+data.type+'-total-'+data.target).html(data.total);
            data.total>0?$('.like-total').show():$('.like-total').hide();            
            /* for shop */
            $('.like-'+data.type+'-total').html(data.total);
            data.total>0?$('.like-'+data.type+'-total').show():$('.like-'+data.type+'-total').hide();            
            $('.summary').show();
        }
    })
    .error(function(XHR) { 
        alert(XHR.status+' '+XHR.statusText+'\r\n' + XHR.responseText);
    });
}
function trendview(url) {
    $('.page-loader').show();
    $.get(url, function( data ) {
        $('.trends-menu li').removeClass('active');
        $('.trends-menu li.'+data.topic).addClass('active');
        $(data.container).html(data.html);
        if ($('#loginbtn-trend').length>0){
            $('#loginbtn-trend').button([]).click(function(){signup();});
        }
        else {
            $('[id^="joinusbtn"]').button([]).click(function(){signup();});
            loadInfiniteScroller('#'+data.topic,'.product');
        }
        var script = $(data.container).data('itemScript');
        if (script!=undefined && script.length>0)
            eval(script);/*load items script*/
        $('.page-loader').hide();
    })
    .error(function(XHR) {
        alert(XHR.status+' '+XHR.statusText+'\r\n' + XHR.responseText);
    });
}
function loadInfiniteScroller(selector,itemselector){
    $.ias({
        history: false,
        triggerPageTreshold: 2,
        trigger: 'Load more',
        onRenderComplete: function(items){hover('.product');},
        container: selector+' > .items',
        item: itemselector,
        pagination: selector+' .spager',
        next: selector+' .next:not(.disabled):not(.hidden) a',
        loader: '<i class="fa fa-circle-o-notch fa-spin"></i>'
    });
}
/**
 * Return product promo page
 * @param {type} c Campaign Id
 * @returns {undefined}
 */
function viewpromo(c){
    $.get('/promoget/c/'+c, function(data) {
        if (mobiledisplay()){
            window.location.href = data.url;
        }
        else {
            modalview(data.url);
        }
    })
    .error(function(XHR){
        error(XHR);
    });
}
/* is this still in used? */
function ask(url,obj){
    var enablebutton = function(){
        $('#'+obj.data('page')+' .question-button').button({'disabled':false}).click(function(){postquestion($(this).attr('form'));});
    };
    createpage(url,enablebutton);
}
/* is this still in used? */
function createpage(url,callback){
    $('#modal_loader').show();
    $.get(url, function( data ) {
        $('.shop-bd').html(data.page);
        $('.shop-hd .navmenu li').removeClass('active');
        scrolltop();
        if (callback!=undefined)
            callback();
        $('#modal_loader').hide();
    })
    .error(function(XHR) {
        error(XHR);
    });    
}

function updateinventory(){
    var view, target;
    if ($('#cart-form').length>0){
        view = 'default';
        target = $('.menuitem .label:first');
    }
    if ($('#cart-modal-form').length>0){
        view = 'modal';
        target = $('.inventory-status');
    }
    var pid = $('#CartItemForm_pkey').val();
    
    var opts = '';
    $.each($('.product-option .option li.active'),function(i,d){ 
        opts += ($(d).find('span').data('key'))+',';
    });
    
    $.get('/cart/management/iteminventoryget/v/'+view+'/pid/'+pid+'/opts/'+opts, function(data) {
        target.html(data.inventory_html);
        $('#buy-button .ui-button-text').html(data.buy_button_text);
        if (data.inventory==0){
            $('.cartbutton button').attr({'disabled':true});
            $('.cartbutton button').css({background:'white',color:'gray'});
        }
        else {
            $('.cartbutton button').attr({'disabled':false});
            $('.cartbutton button').css({background:'#1E90FF',color:'white',padding:'8px 15px'});
        }
    })
    .error(function(XHR){
        error(XHR);
    });
}
function updateprice(obj,updater){
    var cpg = $('#CartItemForm_ckey').val();
    if (cpg=='')
        cpg = 'undefined';
    var p = $('#CartItemForm_pkey').val();
    var qty = obj.val();
    console.log('cpg:'+cpg+', p:'+p+', qty:'+qty);
    $.get('/cart/management/itempriceget/p/'+p+'/qty/'+qty+'/c/'+cpg, function(data) {
        updater.html(data.x_offer_html);
        $('#promo_product_view .info-container.price').html(data.y_offer_html);
        $('.grand_total').html(data.grand_total);
    })
    .error(function(XHR){
        error(XHR);
    });
}
function renderoption(obj,source,showDefault,showInventory){
    var option = obj.find('.key').html();
    var inputElement = $('input[data-source="'+source+option+'"]');
    if (showDefault){
        obj.find('li:first-child').addClass('active');
        inputElement.val( obj.find('li:first-child span').attr('data-key') );
    }
    obj.find('li').click(function(){
        $(this).parent().find('li').removeClass('active');
        $(this).addClass('active');
        inputElement.val( $(this).find('span').attr('data-key') );
        if (showInventory)
            updateinventory();
    });
}
/*Render shippings and set default value */
function renderShippings()
{
    $.each($('div[id^="shipping_"]'), function(idx, obj) {
        renderoption($(this),'shipping_',true,false);
    });
}
/*Render product options and set default value */
function renderProductOptions()
{
    $.each($('div[id^="product_option_"]'), function(idx, obj) {
        renderoption($(this),'product_option_',true,true);
    });
}
/*Render promotional product options and set default value */
function renderPromoProductOptions()
{
    $.each($('div[id^="promo_product_option_"]'), function(idx, obj) {
        renderoption($(this),'promo_product_option_',true,false);
    });
}
function renderQuantity(){
    if ($('#CartItemForm_quantity').length>0){
        $('#CartItemForm_quantity').on('change', function(){
            updateprice($('#CartItemForm_quantity'),$('.price'));
        });
    }
}
function renderRating()
{
    if ($('.rating').length > 0) {//if rating exists
        $('[id^="rating"]').each( function( key, value ){
            $(this).find('> input').rating();
            $(this).find('> input').rating('readOnly',true);
        });
    }
}
function renderForm()
{
    renderQuantity();
    renderShippings();
    renderProductOptions();
    renderPromoProductOptions();
}
$(document).ready(function () {
    renderForm();
});

function calibratepageheight() {
    var height = $('#page_modal .smodal-content .column-left').height();
    $('#shop_page .page').css({height:height+'px'});
}
