function preview(img, selection) {
    var scaleX = 300 / selection.width;
    var scaleY = 300 / selection.height;

    $('#preview_thumbnail').css({
        width: Math.round(scaleX * $('#preview_image').width()) + 'px',
        height: Math.round(scaleY * $('#preview_image').height()) + 'px',
        marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
        marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
    });
    $('#x1').val(selection.x1);
    $('#y1').val(selection.y1);
    $('#x2').val(selection.x2);
    $('#y2').val(selection.y2);
    $('#w').val(selection.width);
    $('#h').val(selection.height);
    $('#w_img').val($('#preview_image').width());
    $('#h_img').val($('#preview_image').height());
}

function toggleDelete() {
    var topTarget = $('.sorting-type').offset().top;
    var cnt = $('.delete-overflow .counter span').html();
    cnt = parseInt(cnt);

    if( cnt > 0 ) {
        if ($(window).scrollTop() > topTarget ) {
            $('.delete-overflow').show();
        } else {
            $('.delete-overflow').hide();
        }
    }
}

var daterangepicker = function( obj ){
    if( typeof obj == 'undefined' ) {
        obj = $('.date-range');
    }

    if( obj.length > 0 ) {
        obj.daterangepicker({
            format: 'DD/MM/YYYY',
        }, function(start, end) {
            var dataEvent = obj.attr('data-event');
            var dataForm = obj.parents('form');
            
            if(dataEvent == 'submit'){
                dataForm.submit();
            }
        });
        $('.icon-picker').click(function(e) {
            obj.trigger('click');
        });
    }
}

    $('body').on('click', '.daterange-dasboard-custom', function(e){
        e.preventDefault();

        var self = $(this);
        var trigger_element = self.attr('trigger-element');
        $('.'+trigger_element).toggle();
    });



var formatNumber = function( number, decimals, dec_point, thousands_sep ){
    // Set the default values here, instead so we can use them in the replace below.
    thousands_sep   = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
    dec_point       = (typeof dec_point === 'undefined') ? '.' : dec_point;
    decimals        = !isFinite(+decimals) ? 0 : Math.abs(decimals);

    // Work out the unicode representation for the decimal place.   
    var u_dec = ('\\u'+('0000'+(dec_point.charCodeAt(0).toString(16))).slice(-4));

    // Fix the number, so that it's an actual number.
    number = (number + '')
        .replace(new RegExp(u_dec,'g'),'.')
        .replace(new RegExp('[^0-9+\-Ee.]','g'),'');

    var n = !isFinite(+number) ? 0 : +number,
        s = '',
        toFixedFix = function (n, decimals) {
            var k = Math.pow(10, decimals);
            return '' + Math.round(n * k) / k;
        };

    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (decimals ? toFixedFix(n, decimals) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, thousands_sep);
    }
    if ((s[1] || '').length < decimals) {
        s[1] = s[1] || '';
        s[1] += new Array(decimals - s[1].length + 1).join('0');
    }
    return s.join(dec_point);
}

var convert_number = function ( num, type ) {
    if( typeof num != 'undefined' ) {
        num = num.replace(/,/gi, "").replace(/ /gi, "").replace(/IDR/gi, "").replace(/Rp/gi, "");

        if( typeof type == 'undefined' ) {
            type = 'int';
        }

        if( type == 'int' ) {
            num = num*1;
        } else if( type == 'float' ) {
            num = parseFloat(num);
        }

        if( isNaN(num) ) {
            num = 0;
        }
    }

    return num;
}

var calcKPR = function() {
    var property_price = $('#PropertyPrice').val();
    var persen_loan = $('#PersenLoan').val();
    var loan_price = $('#LoanPrice').val();

    property_price = convert_number(property_price);
    persen_loan = convert_number(persen_loan);

    var loan_price = property_price * (persen_loan / 100 );
    loan_price = property_price - loan_price;

    $('#LoanPrice').val(formatNumber(loan_price, 0));
}

$(function(){
    $("#min-toggle").click(function(e) {
        e.preventDefault();
        $("#big-wrapper").toggleClass("toggled");
    });
    new WOW().init();

    $('.tracker-radio-id .action').click(function(e){
        var self = $(this);
        var group_id = self.children('input[type="radio"]').val();

        $('.info-radio-id').val(group_id);
    });

    if( $('.tracker-radio-id').length > 0 && $('.info-radio-id').length > 0 ) {
        if( $('.info-radio-id').val() != '' ) {
            var value_radio = $('.info-radio-id').val();
            $('.tracker-radio-id .action input[type="radio"][value="'+value_radio+'"]').parents('.action').addClass('active');
        }
    }

    if( $('#regionId').length > 0 ) {
        $.generateLocation();
    }

    if( $('#gmap-rku').length > 0 ) {
        $.gmapLocation();
    }

    $.rebuildFunction();

    if( $('.add-custom-field').length > 0 ){
        $.addCustomTextField();
    }

    if( $('#autocomplete').length > 0 ) {
        var url = $('#autocomplete').attr('data-url');

        $('#autocomplete').typeahead({
            source: function (query, process) {
                return $.ajax({
                    url: url,
                    type: 'get',
                    data: {query: query},
                    dataType: 'json',
                    success: function(json) {
                        console.log(json);
                        return process(json);
                    }
                });
            },
            minLength: 3,
        });
    }

    if( $('#preview_image').length > 0 ) {
        $('#preview_image').load(function() {
            var default_w = $('#default_width').val();
            var default_h = $('#default_height').val();

            var default_preview_w = $('#wrapper-crop-preview img').width();
            var default_preview_h = $('#wrapper-crop-preview img').height();

            $('#preview_image').imgAreaSelect({ 
                aspectRatio: '1:1', 
                x1: 0, y1: 0, x2: default_w, y2: default_h,
                onSelectChange: preview
            });

            $('#x1').val(0);
            $('#y1').val(0);
            $('#x2').val(default_w);
            $('#y2').val(default_h);
            $('#w').val(default_w);
            $('#h').val(default_h);

            $('#crop_thumbnail img').width(default_preview_w);
            $('#crop_thumbnail img').height(default_preview_h);
            $('#w_img').val(default_preview_w);
            $('#h_img').val(default_preview_h);
        });
    }

    // cities
    $('.triggerChange').change(function(){
        var val = $(this).val();
        var target = $(this).attr('data-target');
        var url = $(this).attr('data-url');

        url += '/'+'bank_id:'+val+'/';

        $.ajaxUpdateElement($(this), $(target), url);
    });

    if( $('.datepicker').length > 0 || $('.to-datepicker').length > 0){
        $.datePicker();
    }


    if( $('.calculate-submit-appraisal').length > 0 ){
        var self = $('.calculate-submit-appraisal');
        $.calculate_submit_appraisal( self );
    }

    $('body').delegate('.KPR-price, .loan_plafond, .persen-loan-id, .down-payment-id, .interest_rate, .periode_fix, .provision_agen, .provision_rku, .credit_fix', 'blur', function(){
        // $(this).trigger('change, click, blur');
        
        var self = $(this);
        var parent = self.closest('form.calculate-submit-appraisal');
        $.calculate_submit_appraisal( parent );
    });
;

    // $.StatusFloating();

    $.add_custom_loan();
    $.select2();
    $.delete_custom_loan();
    $.inputPrice();
    $.inputNumber();
    $.checkAll();
    daterangepicker();
    $.toggle_display();
    $('.carousel').carousel();

    if($('.input_number').length > 0){
        $('.input_number').attr('maxlength', 5);
    }

    if($('.flag-insurance, .flag-appraisal, .flag-administration, .flag-sale_purchase_certificate, .flag-transfer_title_charge, .flag-letter_mortgage, .flag-mortgage, .flag-imposition_act_mortgage, .flag-other_certificate, .flag-credit_agreement').length > 0){
        var obj = $('.flag-insurance, .flag-appraisal, .flag-administration, .flag-sale_purchase_certificate, .flag-transfer_title_charge, .flag-letter_mortgage, .flag-mortgage, .flag-imposition_act_mortgage, .flag-other_certificate, .flag-credit_agreement');
        
        obj.change(function(){
            var self = $(this);
            var value = self.val();
            var class_target = self.attr('data-target');
            var class_hide = self.attr('data-hide');
            var class_group = self.attr('data-group');
            var currency = self.attr('currency');
            var data_target = $(class_target);
            var data_hide = $(class_hide);
            var target_value = data_target.val();
            var text_group = $(class_group);

            switch(value){
                case 'percent':
                    data_hide.show();
                    data_target.removeClass('at-left').removeClass('input_price');
                    data_target.addClass('at-right').addClass('input_number').attr('maxlength', 5);

                    text_group.removeClass('at-left').addClass('at-right').html('%');

                    data_target.off('keyup').off('Keypad').off('keydown');
                    data_target.val(0);
                    $.inputNumber({
                        obj: data_target,
                    });
                    break;
                default :
                    data_hide.hide().val('price');
                    data_target.removeAttr('maxlength').removeClass('at-right').removeClass('input_number');
                    data_target.addClass('input_price').addClass('at-left');

                    text_group.removeClass('at-right').addClass('at-left').html(currency);

                    $.inputPrice({
                        obj: data_target,
                    });
                    break;
            }
        });
    }

    if( $('#reject-kpr-apply').length || $('#agent-kpr-apply').length || $('#company-kpr-apply').length 
        || $('#akad-kpr-apply').length || $('#reject-akad-apply').length ) {

        $('#reject-kpr-apply,#agent-kpr-apply,#company-kpr-apply,#akad-kpr-apply,#reject-akad-apply').click(function(e){
            e.preventDefault();
            var self = $(this);
            var url = self.attr('url');
            var formData = $('#frmKPRApplication').serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    var status = $(response).filter('#status').text();
                    var id = $(response).filter('#status').text();
                    var approval_status = $(response).filter('#approval_status').text();

                    var modal_content = $(response).find('.modal-body').html();
                    
                    if( status == 'success' ){
                            window.location.href = "/admin/kpr/list_user_apply";
                    } else {
                        $("#openModal").find('.modal-body').html(modal_content);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert('Gagal melakukan proses. Silahkan coba beberapa saat lagi.');
                    return false;
                }
            });
        });

    }

    if ( $('.btnLoadMore').length ) {

        $('.btnLoadMore').each(function(){
            var self = $(this);
            var table = self.closest('table');
            var total_row = table.find('tbody tr').length - 1;

            if( total_row > 10 ) {

                $("tbody tr:not('.filterLoadMore'):gt(9)", table).hide();
                
            } else {
                self.closest('tr').remove();   
            }
        });

        $('.btnLoadMore').click(function(e){
            e.preventDefault();

            var self = $(this);
            var table = self.closest('table');
            var tableData = table.find('tbody tr');
            var total_row = tableData.length - 1;

            var greater_than_row = $("tbody tr:not('.filterLoadMore'):visible", table).length + 10;
            $("tbody tr:lt("+greater_than_row+")", table).show();

            if ( !$("tbody tr:not('.filterLoadMore'):hidden", table).length ) {
                self.closest('tr').remove();
            }
        });
    }

    if($('.status_marital, .same_address').length > 0){
        var self = $('.status_marital, .same_address');
        $.showHide( self );
    }

    if($( '.ckeditor' ).length > 0){
        $( '.ckeditor' ).ckeditor({toolbar : [             
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup', 'blocks' ], items: [ 'Bold', 'Italic', 'Underline', 'Blockquote', 'Link' ] },
            { name: 'styles', items: [ 'Format' ] }
        ]});
    }


    $(document).ready(function(){
    //  custom location selector
        if($('#regionId1').length){
            var additionals = $('#regionId1').attr('aditionals');
            $.generateLocation({
                regionSelector  : $('#regionId'+additionals),
                citySelector    : $('#cityId'+additionals),
                subareaSelector : $('#subareaId'+additionals),
                zipSelector     : $('#rku-zip'+additionals),
                additionals     : additionals,
            });
        }
    });

    if($("#kpr-btn-form").length > 0){
        $('.ajukan-kpr-button a').off('click');
        $('.ajukan-kpr-button a').click(function(){
            var theOffset = $("#kpr-btn-form").offset();
            $('html, body').animate({
                scrollTop: theOffset.top - 1200
            }, 2000);
        });
    }

    if( $('.credit_fix').length ) {
        $('.credit_fix').off('change');
        $('.credit_fix').change(function(){
            var parent = $(this).parents('.calculator-kpr-credit');
            calculate_kpr(parent);
        });
    }
    // if( $('.interest_rate').length ) {
    //     $('.interest_rate').off('blur');
    //     $('.interest_rate').blur(function(){
    //         var parent = $(this).parents('.calculator-kpr-credit');
    //         calculate_kpr(parent);
    //     });
    // }

    if( $('[data-toggle="tooltip"]').length ) {
        $('[data-toggle="tooltip"]').tooltip();
    } 

    if( $('.sorting-type').length > 0 ) {
        $(window).scroll(function() {
            toggleDelete();
        });
    }

    if( $('#PropertyPrice').length > 0 ) {
        $('#PropertyPrice,#PersenLoan,#LoanPrice').blur(function(e){
            calcKPR();
        });
    }

    // Show Other Costs KPR
    if($('.show-hide').length > 0){
        $('.show-hide').change(function(){
            var self = $(this);
            var target = self.data('target');
            var checked = self.is(':checked') ;

            if(checked == true){
                $(target).removeClass('hide');
            }else{
                $(target).addClass('hide');
            }
        });
    }
});