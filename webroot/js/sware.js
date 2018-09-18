var WEB_PATH_URL = '';
var authWindow;
var isAdmin = false;
var $modal;
var dataTable;
(function ($) {
    $.sware = {
        settings: [],
        pagUrl: null,
        ajaxlock: false,
        init: function (s) {
            $.sware.settings = s;
            WEB_PATH_URL = s.baseUrl;
            CURRENT_ACTION_NAME = s.actionName

            if (s.admin)
                isAdmin = s.admin;

            $modal = $('#ajax-modal');

            $modal.on('click', 'input[type="submit"]', function () {
                $modal.modal('loading');
            });
            
            $(document).ready(function (){
                $("[class^=avatar-view]").css({"cursor":"pointer"});
                getStarRating();
            });
            
            $(document).on('click','.popuplink',function (){
                var url = $(this).attr('href');
                var disabledBackground = $(this).attr('data-backdrop');
                if(typeof disabledBackground !== "undefined"){
                    $modal.modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                }
                $modal.load(url, '', function () {
                    $modal.removeClass("hide");
                    $modal.modal();
                });

                return false;
            });

            $('.goback').on('click', function () {
                window.history.back();
            });

            $(document).on('click','.change_status',function (){
                var url = $(this).attr('href');
                $.ajax({
                    type: 'POST',
                    url: url,
                    success: function (data) {
                        try{
                            var x = JSON.parse(data);
                            if(typeof x.error !== "undefined" && x.error == 0){
                                $("#list").dataTable().fnReloadAjax(PATH_GRID);
                            } else {
                                alert('Error : '+x.msg);
                            }
                        }catch(e){
                            alert('Failure : '+e);
                        }
                    },
                });
                return false;
            });
            
            $(document).on('click','.abuse-review-link',function (){
                
                if($(this).hasClass('disabled')) {
                    alert('Error ! You are already post abuse report for this review.');
                    return false;
                }
                
                if(confirm('Are you sure you want to send abuse report for this review ?')) {
                    
                    var a_tag = $(this);
                    
                    var url = a_tag.attr('href');
                    var ref_id = a_tag.attr('ref_id');
                    
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data:({ref_id:ref_id}),
                        success: function (response) {
                            
                            try{
                                var x = JSON.parse(response);
                                if(typeof x.status !== "undefined") {
                                    var subText = x.status == 1 ? 'Success' : 'Error';
                                    alert(subText+' ! '+x.msg);
                                    
                                    if(x.status == 1 || x.status == 2) {
                                        a_tag.html('Already Abused');
                                        a_tag.addClass('disabled');
                                    }
                                    
                                } else {
                                    alert('Error ! ');
                                }
                            }catch(e){
                                alert('Failure : '+e);
                            }
                        },
                    });
                }
                
                return false;
            });
            
            $(document).on('submit', 'form.ajaxSubmit', function () {
                var frm = this;
                var containerdiv = $(this).parent('div');
                var pdata = new FormData($(this)[0]);
                
                $.ajax({
                    type: 'POST',
                    url: $(frm).attr("action"),
                    data: pdata,
                    enctype: 'multipart/form-data',
                    //async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        var loading_text = $(frm).find('button[type="submit"]').attr('data-loading');
                        if (typeof loading_text !== typeof undefined && loading_text !== false) {
                            $(frm).find('button[type="submit"]').html(loading_text);
                        } else {
                            $(frm).find('button[type="submit"]').html('Saving ... ');
                        }
                        $(frm).find('button[type="submit"]').attr('disabled', 'disabled');
                    },
                    success: function(data){
                        
                        if (data == 1) {
                            containerdiv.hide("fast");
                            window.location.reload();
                        } else {
                            try{
                                var x = JSON.parse(data);
                                if(typeof x.status !== "undefined"){
                                
                                } else {
                                    containerdiv.html(data);
                                }
                            } catch(e) {
                                //console.log(e);
                                containerdiv.html(data);
                            }
                        }
                    }
                });
                return false;
            });
            
            $(document).on('submit', 'form.frontAjaxSubmit', function () {
                var frm = this;
                var containerdiv = $(this).parent('div');
                var pdata = new FormData($(this)[0]);
                
                $.ajax({
                    type: 'POST',
                    url: $(frm).attr("action"),
                    data: pdata,
                    enctype: 'multipart/form-data',
                    //async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        var loading_text = $(frm).find('button[type="submit"]').attr('data-loading');
                        if (typeof loading_text !== typeof undefined && loading_text !== false) {
                            $(frm).find('button[type="submit"]').html(loading_text);
                        } else {
                            $(frm).find('button[type="submit"]').html('Saving ... ');
                        }
                        $(frm).find('button[type="submit"]').attr('disabled', 'disabled');
                    },
                    success: function(response){
                        
                        if (response == 1) {
                            containerdiv.hide("fast");
                            window.location.reload();
                        } else {
                            try{
                                var x = JSON.parse(response);
                                if(typeof x.status !== "undefined"){
                                    if(x.status == 1) {
                                        // status 1 for login 
                                        if(x.redirect == 'edit-profile') {
                                            window.location = WEB_PATH_URL+'users/edit-profile';
                                        } else {
                                            window.location = WEB_PATH_URL+'stores/add';
                                        }
                                    }
                                    else if(x.status == 2) {
                                        // product review section
                                        alert(x.msg);
                                        //alert($('.productReviewRatingLink a').length);
                                        $('.productReviewRatingLink a').click();
                                    }
                                } else {
                                    containerdiv.html(response);
                                }
                            }catch(e){
                                //console.log(e);
                                containerdiv.html(response);
                            }
                        }
                    }
                });
                return false;
            });
            
            $(document).on('submit', 'form.ajax-search-form', function () {
                var frm = this;
                var containerdiv = $('.show-ajax-data');
                var pdata = new FormData($(this)[0]);
                
                $.ajax({
                    type: 'POST',
                    url: $(frm).attr("action"),
                    data: pdata,
                    enctype: 'multipart/form-data',
                    //async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        var loading_text = $(frm).find('button[type="submit"]').attr('data-loading');
                        if (typeof loading_text !== typeof undefined && loading_text !== false) {
                            $(frm).find('button[type="submit"]').html(loading_text);
                        } else {
                            $(frm).find('button[type="submit"]').html('Saving ... ');
                        }
                        $(frm).find('button[type="submit"]').attr('disabled', 'disabled');
                    },
                    complete: function () {
                        
                        var button_text = $(frm).find('button[type="submit"]').attr('data-button-text');
                        if (typeof button_text !== typeof undefined && button_text !== false) {
                            $(frm).find('button[type="submit"]').html(button_text);
                        } else {
                            $(frm).find('button[type="submit"]').html('Submit');
                        }
                        $(frm).find('button[type="submit"]').removeAttr('disabled');
                    },
                    success: function(response){
                        containerdiv.html(response);
                    }
                });
                return false;
            });
            
            $(document).on('change','#user-country-id',function (){
                var c_id = $(this).val();
                getStateData(c_id);
                return false;
            });
            
            $(document).on('change','#user-pro-country-id',function (){
                
                var old_country = $(this).attr('old-country');
                var c_id = $(this).val();
                var text = $(this).attr('data-confirm-text');
                var is_change = $(this).attr('data-is-change');
                
                if((old_country != c_id) && c_id != '' ) {
                    if(confirm(text) && is_change == 1) {  
                        getStateData(c_id);
                    } else {
                        $(this).val(old_country);
                    }
                } else {
                    getStateData(c_id);
                }
                
                return false;
            });
            
            $(document).on('click','.copyToClipboard',function() {
        
                var element = $(this).attr('copy-ele');
                var $temp = $("<input>")
                $("body").append($temp);
                $temp.val($(element).text()).select();
                document.execCommand("copy");
                $temp.remove();
            });
            
            $(document).on('click','.captcha-reload',function() {
                var mySrc = $(this).prev().attr('src');
                var glue = '?';
                if(mySrc.indexOf('?')!=-1)  {
                    glue = '&';
                }
                $(this).prev().attr('src', mySrc + glue + new Date().getTime());
                return false;
            });
            
            $(document).on('click','.addTeamCategory',function() {
                
                var instance = $('#lazy').jstree(true);
                var selected = $("#lazy").jstree("get_selected");

                if(selected.toString()) {
                    var cat_id = $("#lazy").jstree("get_selected").toString();
                    var sel_node = instance.get_node(cat_id,false);
                    
                    if($('.cat_'+cat_id+'_breadcrumb').length == 0) {
                        $.ajax({
                            url: WEB_PATH_URL + 'teams/haschild',
                            dataType: 'json',
                            type: 'POST',
                            data: ({'cat_id':cat_id,'level_no':sel_node.original['level']}),
                            success: function (res) {
                                if(res == 1) {
                                    var cat_text = instance.get_text(selected);
                                    var catArr = [];
                                    catArr.push(cat_text);
                                    var text = getParentCatText($("#lazy").jstree("get_selected"),catArr);

                                    text = text.reverse();
                                    text = text.join(' > ');
                                    
                                    var html = '<div class="col-md-12 cat_'+cat_id+'_breadcrumb category-right-box">';
                                        html += text;
                                        html += '<input type="hidden" value="'+cat_id+'" name="category_id[]">';
                                        html += '<span class="pull-right"><i onclick="removeCatBreadcrumb('+cat_id+')" class="removeicon"></i></span></div>';
                                    $('.selectedCategoryRow').append(html);

                                    return false;
                                } else {
                                    alert('This category has child category.');
                                }
                            }
                        });
                    } else {
                        alert('You are already added this category.');
                        return false;
                    }
                } 
                else {
                    alert('Please select category');
                }
                
                
//                var sel_cat_id = $('.select_cat').attr('data-cat-id');
//                
//                if(typeof sel_cat_id === 'undefined') {
//                    alert('This category has child category.');
//                    return false;
//                } else {
//                    if($('.cat_'+sel_cat_id+'_breadcrumb').length == 0) {
//                        var catArr = []; 
//                        var text = getParentCatText(sel_cat_id,catArr);
//                        text = text.reverse();
//                        text = text.join(' > ');
//
//                        var html = '<div class="col-md-12 cat_'+sel_cat_id+'_breadcrumb"><p>';
//                            html += text;
//                            html += '<input type="hidden" value="'+sel_cat_id+'" name="category_id[]">';
//                            html += '<span class="pull-right"><i class="fa fa-close" onclick="removeCatBreadcrumb('+sel_cat_id+')"></i></span></p></div>';
//                        $('.selectedCategoryRow').append(html);
//                    } else {
//                        alert('You are already added this category.');
//                        return false;
//                    }
//                }
            });
            
            $(document).on('click','.inviteBtn',function() {
                
                var cust_id = $('#search_customer').val();
                var team_id = $(this).attr('data-team-id');
                
                if(cust_id) {
                    
                    $.ajax({
                        url: WEB_PATH_URL + 'teams/invite',
                        type: 'POST',
                        async:false,
                        data : ({cust_id:cust_id,team_id:team_id}),
                        success: function(response) {
                            
                            try{
                                var x = JSON.parse(response);
                                if(typeof x.status !== "undefined") {
                                    var subText = x.status == 1 ? 'Success' : 'Error';
                                    alert(subText+' ! '+x.msg);
                                    
                                    if($('.invitedMemberLink').hasClass('active') && x.status == 1) {
                                        $('.invitedMemberLink a').click();
                                    }
                                    
                                } else {
                                    alert('Error ! ');
                                }
                            } catch(e) {
                                console.log('Error ! '+e);
                            }
                        }
                    });
                    
                } else {
                    alert('Please select customer for invitation.');
                    return false;
                }
            });
            
            $(document).on('click','#team_member li a',function() {
            
                $("#team_member li").removeClass('active');
                $(this).parent('li').addClass('active');

                $.ajax({ 
                    url: this.href,
                    success: function(html) {
                        $(".show-ajax-data").empty().append(html);
                    }
                });
                return false;
            });
            
            $(document).on('click','.ajax_pagination li a',function() { 
                
                var data = '';
                if($('Form#AjaxSearchForm').length) {
                    var data = $('Form#AjaxSearchForm').serialize();
                }
                
                if($(this).attr('href') != '' && $(this).attr('href') != '#') {
                    
                    $('#loading-image').fadeIn();
                    $.post( $(this).attr('href'),data,function( res ) {
                        $( ".show-ajax-data" ).html( res );
                        $('#loading-image').fadeOut();
                    });
                    
                    //$(".show-ajax-data").load($(this).attr('href'), data, function () {});
                }
                return false; 
            });
            
            // this is for only product listing start
             $(document).on('click','.product_ajax_pagination li a',function() { 
                
                var data = '';
                if($('Form#ProductAjaxSearchForm').length) {
                    var data = $('Form#ProductAjaxSearchForm').serialize();
                }
                
                if($(this).attr('href') != '' && $(this).attr('href') != '#') {
                    
                    $('#loading-image').fadeIn();
                    $.post( $(this).attr('href'),data,function( res ) {
                        $( ".show-product-ajax-data" ).html( res );
                        $('#loading-image').fadeOut();
                    });
                }
                return false; 
            });
            
            $(document).on('change','.searchProductFormOption',function() { 
                
                if($(this).attr('data-brand')) {
                    var searchText = 'searchBrand';
                    var label = 'checkBoxSearchBrand';
                    var value = $(this).val();
                } else {
                    var searchText = 'searchOption';
                    var label = 'checkBoxSearchOption';
                    var value = $(this).val();
                    value = value.split('-').reverse();
                    value = value[0];
                }
                
                if($(this).is(':checked')) {
                    var html = '<div class="pointer product-listing-prodct-itemfirst '+searchText+value+'"><label for="'+label+value+'">'+$(this).attr('data-label')+' <i class="fa fa-times" aria-hidden="true"></i></label></div>';
                    $('.searchItemLabel').append(html);
                } else {
                    $('.'+searchText+value).remove();
                }
                
                var data = $('Form#ProductAjaxSearchForm').serialize();
                
                $('#loading-image').fadeIn();
                $.post( $('Form#ProductAjaxSearchForm').attr('action'),data,function( res ) {
                    $( ".show-product-ajax-data" ).html( res );
                    $('#loading-image').fadeOut();
                });

                return false; 
            });
            // this is for only product listing end
            
            $(document).on('change','#money_type',function() {
                
                if($(this).val() == 2) {
                    $('.addOption').hide();
                    $('.withdrawalOption').show();
                } else {
                    $('.withdrawalOption').hide();
                    $('.addOption').show();
                }
                
                showHidePaypalDiv($('#withdrawal-pay-type').val());
                
            });
            
            $(document).on('change','#withdrawal-pay-type',function() {
                showHidePaypalDiv($(this).val());
            });
            
            $(document).on('change','.product_variants_option',function (){
                
                $('#selected_option_id').val($(this).attr('data-option-id'));
                $('#selected_option_value_id').val($(this).attr('data-option-value-id'));
                $(this).closest("form").submit();
                return false;
            });
            
            $(document).on('submit', 'form.productVariantForm', function () {
                var frm = this;
                var containerdiv = $('#productTopDetail');
                var pdata = new FormData($(this)[0]);
                
                $.ajax({
                    type: 'POST',
                    url: $(frm).attr("action"),
                    data: pdata,
                    enctype: 'multipart/form-data',
                    //async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#loading-image').fadeIn();
                    },
                    complete: function () {
                        $('#loading-image').fadeOut();
                    },
                    success: function(response){
                        
                        if (response == 1) {
                            containerdiv.hide("fast");
                            window.location.reload();
                        } else {
                            try{
                                var x = JSON.parse(response);
                                if(typeof x.status !== "undefined"){
                                    
                                } else {
                                    containerdiv.html(response);
                                }
                            }catch(e){
                                //console.log(e);
                                containerdiv.html(response);
                            }
                        }
                    }
                });
                return false;
            });
            
            $(document).on('click','.applyCouponCode',function (){
                
                var thisBtn = $(this);
                var product_id = thisBtn.attr('data-product-id');
                var store_id = thisBtn.attr('data-store-id');
                var coupon_code = $('#couponCode').val();
                
                if(coupon_code != '' && product_id != '')  {
                    $.ajax({
                        type: 'POST',
                        url: WEB_PATH_URL + 'products/applycode',
                        data: ({'product_id':product_id,'store_id':store_id,'coupon_code':coupon_code}),
                        beforeSend: function () {
                            $('#loading-image').fadeIn();
                        },
                        complete: function () {
                            $('#loading-image').fadeOut();
                        },
                        success: function(response){
                            try{
                                var x = JSON.parse(response);
                                if(x.error == 0){
                                    //valid code
                                    $('#couponCode').addClass('valid_code');
                                    thisBtn.removeClass('applyCouponCode');
                                    thisBtn.addClass('removeCouponCode');
                                    thisBtn.html('Remove Code');
                                } else {
                                    alert(x.msg);
                                }
                            }catch(e){
                                alert('Error : '+ e);
                            }
                        }
                    });
                } else {
                    if(coupon_code == '') {
                        alert('Coupon Code can not be blank.');
                    } else {
                        alert('Invalid parameter request.');
                    }
                }
                
            });
            
             $(document).on('click','.removeCouponCode',function (){
                
                var thisBtn = $(this);
                $('#couponCode').val('')
                $('#couponCode').removeClass('valid_code');
                thisBtn.removeClass('removeCouponCode');
                thisBtn.addClass('applyCouponCode');
                thisBtn.html('Apply Code');
            });
            
            $(document).on('keyup','#couponCode',function (){
                if($(this).hasClass('valid_code')) {
                    $('#couponCode').removeClass('valid_code');
                }
            });
            
            $(document).on('click','.add-to-cart-button',function (){
                
                var postData = new Array();
                var thisBtn = $(this);
                var product_id = thisBtn.attr('data-product-id');
                var store_id = thisBtn.attr('data-store-id');
                var variant_id = thisBtn.attr('data-variant-id');
                var product_type = thisBtn.attr('data-product-type');
                var coupon_code = $('#couponCode').val();
                
                if(product_type == 2) {
                    var ERROR = 0;
                    $( "form.productVariantForm .optionVariantClass" ).each(function( index ) {
                        if($(this).find('input:radio').is(':checked') == false) {
                            ERROR = 1;
                        }
                    });
                    if(ERROR == 1) {
                        alert('Error : Please select all variant option.');
                        return false;
                    }
                    
                    //var postData = $( "form.productVariantForm" ).serializeArray();
                }
                
                postData.push({name: 'product_id', value: product_id});
                postData.push({name: 'store_id', value: store_id});
                postData.push({name: 'coupon_code', value: coupon_code});
                postData.push({name: 'variant_id', value: variant_id});
                
                if(product_id != '') {
                    $.ajax({
                        type: 'POST',
                        url: WEB_PATH_URL + 'products/addtocart',
                        data: postData,
                        beforeSend: function () {
                            $('#loading-image').fadeIn();
                        },
                        complete: function () {
                            $('#loading-image').fadeOut();
                        },
                        success: function(response){
                            try{
                                var x = JSON.parse(response);
                                if(x.error == 0){
                                     alert(x.msg);
                                     $('#cartCount').html(x.cart_count);
                                } else {
                                    alert(x.msg);
                                }
                            }catch(e){
                                alert('Error : '+ e);
                            }
                        }
                    });
                } else {
                    alert('Error : Invalid parameter request.');
                    return false;
                }
            });
            
        },
        showLoading: function () {
            $('body').modalmanager('loading');
        },
        WINPOPUP: function (e, url) {
            e.preventDefault();
            var output = 'Please Wait..';
            var width = 575,
                    height = 400,
                    left = ($(window).width() - width) / 2,
                    top = ($(window).height() - height) / 2,
                    opts = 'scrollbars=1,resizable=1,status=1' + ',width=' + width + ',height=' + height + ',top=' + top + ',left=' + left;
            authWindow = window.open('about:blank', '', opts);
            authWindow.document.write(output);
            if (url) {
                //if(url.indexOf("http:")<0)
                //url = PathUrl+"/"+url;

                authWindow.location.replace(url);
            }
            return;
        }
    }
})(jQuery);

function showHidePaypalDiv(type_val) {
    if(type_val == 1) {
        $('.withdrawalPaypalDiv').show();
    } else {
        $('.withdrawalPaypalDiv').hide();
    }
}

function getStarRating() {
    if($('.get-rating-star').length > 0) {
        $( ".get-rating-star" ).each(function( index ) {

            var qty = $(this).attr('data-star');
            
            if (typeof $(this).attr('data-color') !== "undefined") {
                var color = $(this).attr('data-color');
            } else {
                var color = 'ff9000';
            }

            var starsHtml = getStar(color,qty);
            $(this).html(starsHtml);
        });
    }
}

function getStar(color,qty) {

    var fullStar = ' <i class="fa fa-star" aria-hidden="true"></i> ';
    var halfStar = ' <i class="fa fa-star-half-o" aria-hidden="true"></i> ';
    var nullStar = ' <i class="fa fa-star-o" aria-hidden="true"></i> ';
    var qtyRound = Math.round(qty);

    var star = '<span style="color:#'+color+' !important">'
    for(var i=1;i<6;i++) {
        if(i<=qty) {
            star+=fullStar;
        } else if(!(i<=qty && i>qty) && i == qtyRound) {
            star+=halfStar;
        } else {
            star+=nullStar;
        }
    }
    star += '</span>';

    return star;
}


function removeCatBreadcrumb(cat_id) {
    if(confirm('Are you sure you want to remove this category ?')) {
        if($('.cat_'+cat_id+'_breadcrumb').length) {
            $('.cat_'+cat_id+'_breadcrumb').remove();
        }
    }
}

function getParentCatText(cat_obj,catArr) {

    var instance = $('#lazy').jstree(true);
    var parent_id = instance.get_parent(cat_obj);

    if(parent_id === '#') {
        return catArr;
    } else {
        var node = instance.get_node(parent_id,false);
        var title = instance.get_text(node);
        catArr.push(title);
        return getParentCatText(node,catArr);
    }
}

function getStateData(c_id,s_id) {
    $("#user-state").find('option').remove();
    if(c_id != '' ) {
        $.ajax({
            type: 'POST',
            url: WEB_PATH_URL + 'users/getState',
            data: {c_id:c_id},
            async: false,
            success: function (data) {
                try {
                    $('<option>').val('').text('Select State').appendTo($("#user-state"));
                    $.each(data, function(key, value) {
                        $('<option>').val(key).text(value).appendTo($("#user-state"));
                    });
                    
                    $("#user-state").val(s_id);
                } catch(e) {
                    alert('Failure : '+e);
                }
            },
        });
    } else {
        $('<option>').val('').text('Select State').appendTo($("#user-state"));
    }
}


var actionInfinitePaginationArray = ['commonsearch']; //list of actions that are using infinite pagination
$(document).ajaxComplete(function (e, res, settings ) {
    
    getStarRating();
    
    if (res.status == 401) {
        $('#ajax-modal').modal('hide');
        alert(res.statusText);
        //window.location = WEB_PATH_URL;
    }
    
    if (res.status == 403) {
        alert(res.statusText);
        window.location = WEB_PATH_URL;
    }
    
    if (res.status == 404 && (!jQuery.inArray( "'"+CURRENT_ACTION_NAME+"'", actionInfinitePaginationArray))) {
        alert(res.statusText);
        window.location = WEB_PATH_URL;
    }
});

/*Function to validate a form that all the textboxes boxes in the form has value and are not empty*/
function formvalidationwithonlyinputs(frm_data){
    var form_data = frm_data.serializeArray();
    var error_free=true;
    $.each(form_data, function(i, field){
        if(field.value.trim() == ''){
            $("input[name='"+field.name+"']").css({'border' : 'solid 1px #F00'}); 
            error_free=false; 
        }else{
            $("input[name='"+field.name+"']").css({'border' : 'solid 1px #cbcbcb'}); 
        }
    });
    if (!error_free){
        return false;
    }else{
        return true;   
    }
}
/*Ajax pagination multiple pagination on single page*/
 $(document).on('click','.ajax_multiple_pagination li a',function() { 
    var data = '';
    if($('Form#AjaxSearchForm').length) {
        var data = $('Form#AjaxSearchForm').serialize();
    }

    if($(this).attr('href') != '' && $(this).attr('href') != '#') {
        var containerdiv = $(this).parent().parent().attr('tab-id');
        $('#loading-image').fadeIn();
        $.post( $(this).attr('href'),data,function( res ) {
            $( "."+containerdiv ).html( res );
            $('#loading-image').fadeOut();
        });
    }
    return false; 
});
 $(document).on('submit', 'form.multiple-pagination-ajax-search-form', function () {
    var frm = this;
    var containerdiv = $('.'+$(this).attr('tab-id'));
    var pdata = new FormData($(this)[0]);
    $.ajax({
        type: 'POST',
        url: $(frm).attr("action"),
        data: pdata,
        enctype: 'multipart/form-data',
        //async: false,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            var loading_text = $(frm).find('button[type="submit"]').attr('data-loading');
            if (typeof loading_text !== typeof undefined && loading_text !== false) {
                $(frm).find('button[type="submit"]').html(loading_text);
            } else {
                $(frm).find('button[type="submit"]').html('Saving ... ');
            }
            $(frm).find('button[type="submit"]').attr('disabled', 'disabled');
            $('#loading-image').fadeIn();
        },
        complete: function () {
            
            var button_text = $(frm).find('button[type="submit"]').attr('data-button-text');
            if (typeof button_text !== typeof undefined && button_text !== false) {
                $(frm).find('button[type="submit"]').html(button_text);
            } else {
                $(frm).find('button[type="submit"]').html('Submit');
            }
            $(frm).find('button[type="submit"]').removeAttr('disabled');
            $('#loading-image').fadeOut();
        },
        success: function(response){
            containerdiv.html(response);
        }
    });
    return false;
});


function timer(futureDate,created,downCounter,timerArray,auction_type){
	
	downCounter++;
	
	var future = new Date(futureDate);
	
	var now = new Date(created);

	var difference = (Math.floor((future.getTime() - now.getTime()) / 1000))-downCounter;

    var seconds = fixIntegers(difference % 60);
	difference = Math.floor(difference / 60);
	
	var minutes = fixIntegers(difference % 60);
	difference = Math.floor(difference / 60);
	
	var hours = fixIntegers(difference % 24);
	difference = Math.floor(difference / 24);
    
    var days = fixIntegers(difference);
    
    if(days == 00 && hours == 00 && minutes == 00 && seconds == 00) {
        $('.productBidForm').html('Bids Close');
    }
    
    $("#"+timerArray[0]).html(days);
    $("#"+timerArray[1]).html(hours);
    $("#"+timerArray[2]).html(minutes);
    $("#"+timerArray[3]).html(seconds);
		
	setTimeout(function() { timer(futureDate,created,downCounter,timerArray,auction_type); }, 1000);
}

function fixIntegers(integer){
	
    if (integer < 0)
        integer = 0;
    if (integer < 10)
        return "0" + integer;
    return "" + integer;
}

function updateURL(url) {
    var newurl = url;
    window.history.pushState({path:newurl},'',newurl);
}

$(document).ready(function(){
    $('.cart-header').popover({
        html: true,
        trigger: 'manual',
        placement: 'auto bottom',
        container: '#mycontainerCart',
        template: '<div class="popover my_ace_class_name"><div class="arrow"></div><div class="popover-content"></div></div>',
        content: function () {
            return '<div id="cart-shopping-div" class="myaddtocartouter"><p class="loadingSpan">Loading...</p></div>';
        }
    }).click(function (e) {
        e.preventDefault();
        if($('#cart-shopping-div').length == 0) {
            getMessage();
        }
        $(this).popover('toggle');
        // return false;
    });
});

function getMessage() {
    $.ajax({
        url: WEB_PATH_URL + 'products/cartdetail/1',
        dataType: 'html',
        async: true,
        success: function (data) {
            if ($.trim(data) != '')
                $('div#cart-shopping-div').html(data);
        }
    });
}

function hcalc(sel){
    var h = $(sel).innerHeight();
    if(h<350){
        return h;
    }else{
        return 350;  
    }
}