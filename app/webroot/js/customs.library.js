(function ( $ ) {
    var gmapRku = $('#gmap-rku');
    var rkuAddress = $('#rku-address');
    var rkuAddress2 = $('#rku-address2');
    var rkuAddressNo = $('#rku-no-address');
    var rkuZip = $('#rku-zip');
    var rkuLatitude = $('#rku-latitude');
    var rkuLongitude = $('#rku-longitude');
    var rkuLocation = $('#rku-location');

    $.checkUndefined = function (value, _default) {
        if(typeof value == 'undefined' ) {
            value = _default;
        }

        return value;
    }

    $.convertNumber = function(num, type, empty){
        if( typeof empty == 'undefined' ) {
            empty = 0;
        }

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

            if( type == 'int' || type == 'float' ) {
                if( isNaN(num) ) {
                    num = 0;
                }
            }
        } else {
            num = empty;
        }

        return num;
    }

    if( gmapRku.length > 0 ) {
        var iconMarker = new google.maps.MarkerImage('https://s3-ap-southeast-1.amazonaws.com/rmhstatic/images/icons/icon_map.png',
            new google.maps.Size(26,32),
            new google.maps.Point(0,0),
            new google.maps.Point(13,32)
        );
        var shadow = new google.maps.MarkerImage('https://s3-ap-southeast-1.amazonaws.com/rmhstatic/images/icons/icon_map_shadow.png',
            new google.maps.Size(46,32),
            new google.maps.Point(0,0),
            new google.maps.Point(13,32)
        );
        var shape = {
            coord: [25,0,25,1,25,2,25,3,25,4,25,5,25,6,25,7,25,8,25,9,25,10,25,11,25,12,25,13,25,14,25,15,25,16,25,17,25,18,25,19,25,20,25,21,25,22,25,23,25,24,25,25,25,26,25,27,25,28,21,29,20,30,19,31,6,31,5,30,4,29,0,28,0,27,0,26,0,25,0,24,0,23,0,22,0,21,0,20,0,19,0,18,0,17,0,16,0,15,0,14,0,13,0,12,0,11,0,10,0,9,0,8,0,7,0,6,0,5,0,4,0,3,0,2,0,1,0,0,25,0],
            type: 'poly'
        };
        var propertyMarker;
    }

    $.oauthpopup = function (options) {
        options.windowName = options.windowName || 'ConnectWithOAuth';
        options.windowOptions = options.windowOptions || 'location=0,status=0,width='+options.width+',height='+options.height+',scrollbars=1';
        options.callback = options.callback || function () {
            window.location.reload();
        };
        var that = this;
        that._oauthWindow = window.open(options.path, options.windowName, options.windowOptions);
        that._oauthInterval = window.setInterval(function () {
            if (that._oauthWindow.closed) {
                window.clearInterval(that._oauthInterval);
                options.callback();
            }
        }, 1000);
    };

    $.select2 = function(options){
        if($('select[data-multiple=multiple]').length > 0){
            $('select[data-multiple=multiple]').select2();
        }
    }

    $.toggle_display = function( options ) {
        if($('.toggle-display').length > 0){
            $('.toggle-display').off('click');
            $('.toggle-display').click(function(e){
                e.preventDefault();
                var self = $(this);
                var divShow = self.attr('data-display');
                var type = self.attr('data-type');
                var arrow = $.checkUndefined(self.attr('data-arrow'), false);
                var label = $.checkUndefined(self.attr('label-detail'), false);

                if( type == 'slide' ) {
                    if( arrow == 'true' ) {
                        var objArrow = self.find('i').removeAttr('class');
                        var visible = $(divShow).is(':visible');

                        if (visible) {
                            if(label){
                                self.html('Buka Detail <i></i>');
                            }
                            objArrow.addClass('rv4-angle-down');
                            
                        } else {
                            if(label){
                                self.html('Tutup Detail <i></i>');
                            }
                            objArrow.addClass('rv4-angle-up');
                        }
                    }
                    
                    $(divShow).slideToggle();
                } else {
                    $(divShow).toggle();
                }
            });
        }
    }

    $.ajaxForm = function( options ) {
        var settings = $.extend({
            obj: $('.ajax-form'),
        }, options );

        settings.obj.submit(function(){
            var self = $(this);

            getAjaxForm ( self );

            return false;
        });

        function getAjaxForm ( self ) {
            var url = self.attr('action');
            var type = $.checkUndefined(self.attr('data-type'), 'content');
            var flag_alert = self.attr('data-alert');
            var data_ajax_type = self.attr('data-ajax-type');
            var formData = self.serialize(); 
            var data_wrapper_write = self.attr('data-wrapper-write');
            var data_wrapper_success = self.attr('data-wrapper-success');
            var data_pushstate = self.attr('data-pushstate');
            var data_url_pushstate = self.attr('data-url-pushstate');
            var data_reload = self.attr('data-reload');
            var data_reload_url = self.attr('data-reload-url');
            var data_close_modal = self.attr('data-close-modal');

            if( flag_alert != null ) {
                if ( !confirm(flag_alert) ) { 
                    return false;
                }
            }

            if(typeof data_ajax_type == 'undefined' ) {
                data_ajax_type = 'html';
            }

            if(typeof data_wrapper_write == 'undefined' ) {
                data_wrapper_write = '#wrapper-write';
            }

            if(typeof data_pushstate == 'undefined' ) {
                data_pushstate = false;
            }

            if(typeof data_url_pushstate != 'undefined' ) {
                data_url_pushstate = url;
            }

            $.ajax({
                url: url,
                type: 'POST',
                dataType: data_ajax_type,
                data: formData,
                success: function(result) {
                    if( type == 'content' ) {
                        var content = result;
                        var status = $(content).find('#msg-status').html();
                        var msg = $(content).find('#msg-text').html();
                        var contentHtml = $(content).filter(data_wrapper_write).html();

                        if(typeof contentHtml == 'undefined' ) {
                            contentHtml = $(content).find(data_wrapper_write).html();
                        }

                        // UNDER DEVELOPMENT
                        // if( status == 'success' && data_reload == 'true' ) {
                        if( ( status != 'error' && status != 'undefined' ) && data_reload == 'true' ) {
                            if(typeof data_reload_url == 'undefined' ) {
                                window.location.reload();
                            } else {
                                location.href = data_reload_url;
                            }
                        } else if( $(data_wrapper_write).length > 0 ) {
                             if(status == 'success' && typeof data_wrapper_success != 'undefined' && $(data_wrapper_success).length > 0 ) {
                                contentHtml = $(content).filter(data_wrapper_success).html();

                                if(typeof contentHtml == 'undefined' ) {
                                    contentHtml = $(content).find(data_wrapper_success).html();
                                }

                                $(data_wrapper_success).html(contentHtml);
                                $.rebuildFunctionAjax( $(data_wrapper_success) );

                                if( data_pushstate != false ) {
                                    window.history.pushState('data', '', data_url_pushstate);
                                }

                                if( data_close_modal == 'true' ) {
                                    $('#myModal .close.btn').trigger("click");
                                }
                            } else {
                                $(data_wrapper_write).html(contentHtml);
                                $.rebuildFunctionAjax( $(data_wrapper_write) );
                            }
                        }
                    }

                    return false;
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert('Gagal melakukan proses. Silahkan coba beberapa saat lagi.');
                    return false;
                }
            });

            return false;
        }
    }


    $.directAjaxModal = function(options){

        var settings = $.extend({
            obj: $('.ajaxModal'),
            objId: $('#myModal'),
        }, options );

        var vthis = settings.obj;
        var url = vthis.attr('href');
        var alert_msg = vthis.attr('alert');
        var title = vthis.attr('title');
        var subtitle = $.checkUndefined(vthis.attr('data-subtitle'), '');
        var data_location = $.checkUndefined(vthis.attr('data-location'), false);
        var data_color = $.checkUndefined(vthis.attr('data-color'), 'green');
        var modalSize = $.checkUndefined(vthis.attr('data-size'), '');
        $('.modal-body').html('');

        if( alert_msg != null ) {
            if ( !confirm(alert_msg) ) { 
                return false;
            }
        }

        var params_form = false;
        var dataRequest = false;

        if( $('.form-target').length ) {
            params_form = [];

            $.each($('.form-target').serializeArray(), function(index) {
                var cur = this;
                if( cur.name != '_method' && cur.name.indexOf('checkbox_all') == -1 ) {
                    params_form.push(cur);
                }
            });

            dataRequest = { 'params' : params_form };
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: dataRequest,
            success: function(response, status) {
                if( title !== undefined ) {
                    settings.objId.find('.modal-title').html(title).show();
                } else {
                    settings.objId.find('.modal-title').hide();
                }

                if( subtitle != '' ) {
                    subtitle = '<p>'+subtitle+'</p>';
                }

                settings.objId.find('.modal-header').removeClass('green').removeClass('red').addClass(data_color);
                settings.objId.find('.modal-subheader').removeClass('green').removeClass('red').addClass(data_color).html(subtitle);

                if( modalSize != '' ) {
                    settings.objId.find('.modal-dialog').removeClass('modal-sm').removeClass('modal-lg').removeClass('modal-xs').removeClass('modal-md');
                    settings.objId.find('.modal-dialog').addClass(modalSize);
                }

                settings.objId.find('.modal-body').html(response);
                settings.objId.modal({
                    show: true,
                });
                $.rebuildFunctionAjax( settings.objId );

                if( data_location == 'true' ) {
                    $.generateLocation();
                }

                return false;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('Gagal melakukan proses. Silahkan coba beberapa saat lagi.');
                return false;
            }
        });
    }

    $.ajaxModal = function(options){

        var settings = $.extend({
            obj: $('.ajaxModal'),
            objId: $('#myModal'),
        }, options );

        if( settings.obj.length > 0 ) {
            settings.obj.off('click');
            settings.obj.click(function(msg) {
                var self = $(this);
                var data_reload = self.attr('data-close');

                $.directAjaxModal({
                    obj: self,
                    objId: settings.objId,
                });

                if( data_reload == 'reload' ) {
                    settings.objId.on('hidden.bs.modal', function () {
                        window.location.reload();
                    });
                }

                return false;
            });

            $('.close-modal').off('click');
            $('.close-modal').click(function(){
                $('#myModal').modal('hide');
                return false;
            });
        }
    }

    var calculate_kpr = function( parent ){
            var obj_interest_rate = parent.find('.interest_rate');
            var obj_loan_amount = parent.find('.loan-amount');
            var obj_credit_fix = parent.find('.credit_fix');

            var interest_rate = $.checkUndefined(obj_interest_rate.val(), '');
            
            if( interest_rate == '' ){
                alert('Bunga KPR Harap diisi');
            } else {
                var target = parent.attr('data-target');
                var obj_target = parent.find(target);
                var property_price = parent.attr('data-price');
                var loan_amount = obj_loan_amount.html();
                var credit_fix = $.checkUndefined(obj_credit_fix.val(), 0);

                loan_amount = $.numberToString(loan_amount, 0);
                property_price = $.numberToString(property_price, 0);

                $.ajax({
                    url: '/ajax/get_kpr_installment_payment/'+property_price+'/'+loan_amount+'/'+credit_fix+'/'+interest_rate+'/',
                    type: 'GET',
                    success: function(response) {
                        if( response != '' ) {
                            obj_target.replaceWith(response);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert('Gagal melakukan proses. Silahkan coba beberapa saat lagi.');
                        return false;
                    }
                });
            }
        }

    function update_down_payment(parent, action){
            var price_text = parent.find('.KPR-price').val();
            var obj_loan_price = parent.find('.loan-price-id');
            var obj_persen_loan = parent.find('.persen-loan-id');
            var obj_down_payment = parent.find('.down-payment-id');

            var down_payment = obj_down_payment.val();
            down_payment = parseInt(down_payment.replace(/,/g, ''));
            if(isNaN(down_payment)){
                down_payment = 0;
            }

            var property_price = price_text.replace(/,/g, '');
            if(isNaN(property_price)){
                property_price = 0;
            }
            
            var persen_loan = obj_persen_loan.val();
            if(isNaN(persen_loan)){
                persen_loan = 0;
            }

            var loan_price = $.numberToString(obj_loan_price.val(), 0);
            var for_persen = persen_loan / 100;
            var total_down_payment;
            var total_percent_down_payment;
            var total_loan;

            if(action == 'find_percent'){
                total_percent_down_payment = count_percent_down_payment(property_price, down_payment);
                if(total_percent_down_payment > 100 || total_percent_down_payment < 0){
                    alert('Uang muka tidak boleh lebih dari 100% dan kurang dari 0%');
                    if( property_price == 0 ) {
                        uang_muka = 0;
                    } else {
                        uang_muka = 20;
                    }
                    obj_loan_price.val($.formatNumber( uang_muka, 0 ));              
                    obj_down_payment.val(0);               
                    return false;
                }else{
                    obj_persen_loan.val($.formatNumber( total_percent_down_payment, 2));

                    total_loan = count_loan_price(property_price, down_payment);
                    obj_loan_price.val($.formatNumber( total_loan, 0 ));             
                }
            }else if(action == 'find_down_payment'){
                total_down_payment = count_down_payment(property_price, for_persen);
                obj_down_payment.val($.formatNumber( total_down_payment, 0 ));

                total_loan = count_loan_price(property_price, total_down_payment);
                format_loan_price = $.formatNumber( total_loan, 0 );

                // if ( obj_loan_price.is( 'div' ) || obj_loan_price.is( 'span' ) ) {
                    obj_loan_price.html(format_loan_price).val(format_loan_price);
                // } else {
                    // obj_loan_price.val(format_loan_price);
                // }
            }else if(action == 'find_loan'){
                var loan = obj_loan_price.val();
                loan = loan.replace(/,/g, '');

                if(isNaN(loan)){
                    loan = 0;
                } else {
                    loan = parseInt(loan);
                }
                
                if(loan > property_price){
                    alert('Jumlah pinjaman tidak boleh lebih besar dari harga properti');
                    update_down_payment(parent, 'find_percent');
                    update_down_payment(parent, 'find_down_payment');
                }else{
                    total_percent_down_payment = countManualPercentDownPayment(property_price, loan);
                    if(isNaN(total_percent_down_payment)){
                        total_percent_down_payment = 0;
                    }
                    obj_persen_loan.val($.formatNumber( total_percent_down_payment, 2));

                    total_down_payment = count_down_payment(property_price, (total_percent_down_payment / 100));                
                    obj_down_payment.val($.formatNumber( total_down_payment, 0 ));
                }
            }
        }

    // data dropdown regions, cities, areas
    $.generateLocation = function( options ){
        var settings = $.extend({
            city_empty: '<option value="">Pilih Kota</option>',
            area_empty: '<option value="">Pilih Area</option>',
            currentRegionID: $('#currRegionID').val(),
            currentCityID: $('#currCityID').val(),
            regionSelector: $('#regionId'),
            citySelector: $('#cityId'),
            subareaSelector: $('#subareaId'),
            zipSelector: rkuZip,
            addr: rkuAddress,
            addr2: rkuAddress2,
            no: rkuAddressNo,
        }, options );

        // REBUILD IF EXTRA LOCATION
        var additionals = $.checkUndefined(settings.additionals, false);
        if(additionals){
            settings.currentRegionID = $('#currRegionID'+additionals).val();
            settings.currentCityID = $('#currCityID'+additionals).val();
            settings.regionSelector = $('#regionId'+additionals);
            settings.citySelector = $('#cityId'+additionals);
            settings.subareaSelector = $('#subareaId'+additionals);
        }
        //

        var currentRegionFlag = false;
        var currentCityFlag = false;

        var resetLocation = function(param){
            if( param == 'region' ){
                settings.citySelector.empty().append(settings.city_empty);
                settings.citySelector.trigger('change');
            } else if( param == 'city' ){
                settings.subareaSelector.empty().append(settings.area_empty);
                settings.subareaSelector.trigger('change');
            } else if( param == 'subarea' ){
                settings.zipSelector.val('');
            }
        }

        // regions
        settings.regionSelector.change(function(){

            var value = $("option:selected", this).val();
            resetLocation('region');

            var filtered_cities = cities[value];
            var option_cities = '';
            for (var i = 0; i < filtered_cities.length; i++) {
                if ( settings.currentCityID == filtered_cities[i][0] ) {
                    currentCityFlag = true;
                }
                option_cities += '<option value="'+ filtered_cities[i][0] +'">' + filtered_cities[i][1] + '</option>';
            }
            settings.citySelector.empty().append(settings.city_empty + option_cities);

            if( value != '' ) {
                $.updateGMap({
                    map: gmapRku,
                    addr: $.getAddress(),
                });
            }
        });

        // cities
        settings.citySelector.change(function(){
            var value = $("option:selected", this).val();

            if( value == '' ){
                resetLocation('city');
            } else {
                $.ajaxUpdateElement($(this), settings.subareaSelector, '/ajax/get_subareas/0/'+settings.regionSelector.val()+'/'+settings.citySelector.val()+'/', function() {
                    // settings.subareaSelector.trigger('change');
                    settings.zipSelector.val('');
                });

                $.updateGMap({
                    map: gmapRku,
                    addr: $.getAddress(),
                });
            }
        });

        // subareas
        settings.subareaSelector.change(function(){
            var value = $("option:selected", this).val();

            if( value == '' ){
                resetLocation('subarea');
            } else {
                $.ajaxUpdateElement($(this), settings.zipSelector, '/ajax/get_zip/'+value+'/');

                $.updateGMap({
                    map: gmapRku,
                    addr: $.getAddress(),
                });
            }
        });
        
        var optionRegion = '';
        for (var i = 0; i < regions.length; i++) {
            if ( settings.currentRegionID == regions[i][0] ) {
                currentRegionFlag = true;
            }
            optionRegion += '<option value="'+ regions[i][0] +'">' + regions[i][1] + '</option>';
        }
        settings.regionSelector.append(optionRegion);

        if ( currentRegionFlag ) {
            settings.regionSelector.val(settings.currentRegionID);

            var value = $("option:selected", settings.regionSelector).val();
            var filtered_cities = cities[value];
            var option_cities = '';
            for (var i = 0; i < filtered_cities.length; i++) {
                if ( settings.currentCityID == filtered_cities[i][0] ) {
                    currentCityFlag = true;
                }
                option_cities += '<option value="'+ filtered_cities[i][0] +'">' + filtered_cities[i][1] + '</option>';
            }
            settings.citySelector.empty().append(settings.city_empty + option_cities);
            
            if ( currentCityFlag ) {
                settings.citySelector.val(settings.currentCityID);
            }
        }

        if( settings.addr.length > 0 ) {
            settings.addr.blur(function(){
                $.updateGMap({
                    map: gmapRku,
                    addr: $.getAddress(),
                });
            });
            settings.addr2.blur(function(){
                $.updateGMap({
                    map: gmapRku,
                    addr: $.getAddress(),
                });
            });
            settings.no.blur(function(){
                $.updateGMap({
                    map: gmapRku,
                    addr: $.getAddress(),
                });
            });
            settings.zipSelector.blur(function(){
                $.updateGMap({
                    map: gmapRku,
                    addr: $.getAddress(),
                });
            });
        }
    }

    $.ajaxUpdateElement = function(el, target, url, callback) {
        $.post(url, function(data) {
            if(target.is("select")) {
                target.html($.trim(data));
            } else {
                target.val($.trim(data));
            }
            if (typeof callback == "function") {
                callback();
            }
        });
    };

    $.gmapLocation = function( options ) {
        var settings = $.extend({
            mapZoom: 16,
            gmap: gmapRku,
            latitude: rkuLatitude.val(),
            longitude: rkuLongitude.val(),
            locations: rkuLocation.val(),
        }, options );

        if( settings.locations != '' ) {
            settings.gmap.gmap3({
                action:'init',
                options:{
                    center: [settings.latitude, settings.longitude],
                    zoom: settings.mapZoom,
                    scrollwheel: false,
                },
                callback: function(results) {
                    $.addGMapMarker({
                        map: gmapRku,
                        locations: [settings.latitude, settings.longitude],
                    });
                }
            });
        } else { 
            settings.gmap.gmap3({
                action:'init',
                options:{
                    zoom: settings.mapZoom,
                    scrollwheel: false,
                },
            });
        }
    };

    $.updateLocationData = function( options ) {
        var settings = $.extend({
            marker: '',
            latitude: rkuLatitude,
            longitude: rkuLongitude,
            locations: rkuLocation,
        }, options );

        if(settings.marker) {
            point = settings.marker.getPosition();
            settings.latitude.val(point.lat());
            settings.longitude.val(point.lng());
            settings.locations.val(point.lat() + ', ' + point.lng());
        }
    };

    $.addGMapMarker = function( options ) {
        var settings = $.extend({
            map: gmapRku,
            locations: '',
            infowindow: '',
            dragendPoin: $('.rku-dragend'),
            mapZoom: 16,
        }, options );
        var content = '<div id="mapwin_title">'+settings.infowindow+'</div>';
        var markerGMap = settings.map.gmap3({
            action:'get', 
            name:'marker',
            first: true
        });

        if(!markerGMap) {
            settings.map.gmap3({
                action: 'addMarker',
                latLng: settings.locations,
                map: {
                    center: true,
                    zoom: settings.mapZoom
                },
                scrollwheel: false,
                marker: {
                    options: {
                        draggable: true,
                        icon: iconMarker,
                        shadow: shadow,
                        shape: shape
                    },
                    events: {
                        dragend: function(marker, event, data){
                            $.updateLocationData({
                                marker: marker, 
                            });

                            settings.dragendPoin.val(1);
                        },
                        click: function(marker, event){
                            if( settings.infowindow != '' && typeof settings.infowindow != "undefined" ) {
                               $(this).gmap3({
                                action: 'addinfowindow',
                                anchor: marker,
                                options: {
                                  content: content
                                }
                              });
                            }
                        },
                    },
                    callback: function(marker) {
                        propertyMarker = marker;

                        $.updateLocationData({
                            marker: marker, 
                        });
                    }
                }
            });
        } else {
            updateGmapMarker({
                map: settings.map,
                marker: markerGMap,
                locations: settings.locations,
            });
        }
    };

    $.datePicker = function(options){
        var settings = $.extend({
            obj: $('.datepicker'),
            objRange: $('.to-datepicker'),
            objTime: $('.timepicker'),
            up: 'rv4-angle-up',
            down: 'rv4-angle-down',
            next: 'rv4-angle-right',
            previous: 'rv4-angle-left',
            clear: 'rv4-trash',
            close: 'rv4-cross',
        }, options );

        if( settings.obj.length > 0 ) {
            settings.obj.datetimepicker({
                format: 'DD/MM/YYYY',
                icons: {
                    up: settings.up,
                    down: settings.down,
                    previous: settings.previous,
                    next: settings.next,
                    clear: settings.clear,
                    close: settings.close
                },
            });
        }

        if( settings.objRange.length > 0 ) {
            settings.objRange.datetimepicker({
                format: 'DD/MM/YYYY',
                icons: {
                    up: 'rv4-angle-up',
                    down: 'rv4-angle-down',
                    previous: 'rv4-angle-left',
                    next: 'rv4-angle-right',
                    clear: 'rv4-trash',
                    close: 'rv4-cross'
                },
                useCurrent: false,
            });
            settings.obj.on("dp.change", function (e) {
                settings.objRange.data("DateTimePicker").minDate(e.date);

                var self = $(this);
                var currentDate = $.getDates(self.closest('div.row').find('.to-datepicker').val());
                var startDate = e.date.format("YYYY-MM-DD");
                var setDate = e.date.format("DD/MM/YYYY");

                if( startDate > currentDate ) {
                    self.closest('div.row').find('.to-datepicker').val(setDate);
                }
            });
            // settings.objRange.on("dp.change", function (e) {
            //     settings.obj.data("DateTimePicker").maxDate(e.date);
            // });
        }

        if( settings.objTime.length > 0 ) {
            settings.objTime.datetimepicker({
                format: 'HH:mm',
                icons: {
                    up: settings.up,
                    down: settings.down,
                    previous: settings.previous,
                    next: settings.next,
                    clear: settings.clear,
                    close: settings.close
                },
            });
        }
    }

    $.updateGmapMarker = function(options) {
        var settings = $.extend({
            map: gmapRku,
            marker: '',
            locations: '',
        }, options );

        settings.marker.setPosition(settings.locations);
        settings.map.gmap3({
            action:'panTo', 
            args:[settings.locations]
        });
        $.updateLocationData({
            marker: settings.marker, 
        });
    }

    $.updateGMap = function( options ) {
        var settings = $.extend({
            map: gmapRku,
            addr: '',
        }, options );

        if( settings.map.length > 0 ) {
            settings.map.gmap3({
                action: 'getlatlng',
                address: settings.addr,
                callback: function (results) {
                    if (results){
                        var location = results[0].geometry.location;

                        $(this).gmap3({
                            action: 'setCenter', 
                            args:[ location ],
                        });

                        if(!propertyMarker) {
                            $.addGMapMarker({
                                map: $(this), 
                                locations: location,
                            });
                        } else {
                            $.updateGmapMarker({
                                map: $(this), 
                                marker: propertyMarker,
                                locations: location,
                            });
                        }
                    }
                }
            });
        }
    };


    $.getAddress = function( options ) {
        var settings = $.extend({
            subarea: $('#subareaId option:selected'),
            city: $('#cityId option:selected'),
            region: $('#regionId option:selected'),
            country: $('#countryId option:selected'),
            zip: rkuZip,
            addr: rkuAddress,
            addr2: rkuAddress2,
            no: rkuAddressNo,
        }, options );

        var locations = '';
        var address = '';

        if( settings.subarea.val() != '' ) {
            locations = settings.subarea.text();
        }
        if( settings.city.val() != '' ) {
            if( locations != '' ) {
                locations += ', ';
            }
            locations += settings.city.text();
        }
        if( settings.region.val() != '' ) {
            if( locations != '' ) {
                locations += ', ';
            }
            locations += settings.region.text();
        }
        if( typeof settings.country.val() != 'undefined' && settings.country.val() != '' ) {
            if( locations != '' ) {
                locations += ', ';
            }
            locations += settings.country.text();
        }

        if(settings.addr.val()) {
            address = settings.addr.val();

            if( typeof settings.addr2.val() != 'undefined' && settings.addr2.val() != '' ) {
                address += ', ' + settings.addr2.val();
            }

            if( typeof settings.no.val() != 'undefined' && settings.no.val() != '' ) {
                address += ' No.' + settings.no.val();
            }

            if( locations != '' ) {
                address += ', ';
            }

            locations = address + locations;
        }

        if(typeof settings.zip.val() != 'undefined' && settings.zip.val()) {
            locations += ' ' + settings.zip.val();
        }

        return locations;
    }

    $.ajaxLink = function( options ) {
        var settings = $.extend({
            obj: $('.ajax-link'),
        }, options );

        settings.obj.click(function(){
            var self = $(this);
            
            getAjaxLink ( self );

            return false;
        });

        function getAjaxLink ( self ) {
            var url = self.attr('href');
            var parents = self.parents('.ajax-parent');
            var type = self.attr('data-type');
            var flag_alert = self.attr('data-alert');
            var data_ajax_type = self.attr('data-ajax-type');
            var data_wrapper_write = self.attr('data-wrapper-write');
            var data_action = self.attr('data-action');
            var data_pushstate = self.attr('data-pushstate');
            var data_url_pushstate = self.attr('data-url-pushstate');

            if( flag_alert != null ) {
                if ( !confirm(flag_alert) ) { 
                    return false;
                }
            }

            if(typeof data_ajax_type == 'undefined' ) {
                data_ajax_type = 'html';
            }

            if(typeof data_wrapper_write == 'undefined' ) {
                data_wrapper_write = '#wrapper-write';
            }

            if(typeof data_pushstate == 'undefined' ) {
                data_pushstate = false;
            }

            if(typeof data_url_pushstate == 'undefined' ) {
                data_url_pushstate = url;
            }

            $.ajax({
                url: url,
                type: 'POST',
                dataType: data_ajax_type,
                success: function(result) {
                    var msg = result.msg;
                    var status = result.status;

                    if( type == 'remove' ) {
                        parents.remove();
                    } else if( type == 'media-delete' ) {
                        if( status == 'success' ) {
                            parents.remove();
                        } else {
                            alert(msg);
                        }
                    } else if( type == 'content' ) {
                        var contentHtml = $(result).filter(data_wrapper_write).html();

                        if( data_pushstate != false ) {
                            window.history.pushState('data', '', data_url_pushstate);
                        }

                        if(typeof contentHtml == 'undefined' ) {
                            contentHtml = $(result).find(data_wrapper_write).html();
                        }

                        if( $(data_wrapper_write).length > 0 ) {
                            $(data_wrapper_write).html(contentHtml);
                            $.rebuildFunction();

                            if(data_action == 'messages' ) {
                                var current_active = self.parents('li');
                                var data_active = $('.list-inbox li');

                                data_active.removeClass('active');
                                current_active.addClass('active');
                                current_active.removeClass('unread');
                            }
                        }
                    }

                    return false;
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert('Gagal melakukan proses. Silahkan coba beberapa saat lagi.');
                    return false;
                }
            });
        }
    }

    // $.ajaxForm = function( options ) {
    //     var settings = $.extend({
    //         obj: $('.ajax-form'),
    //     }, options );

    //     settings.obj.submit(function(){
    //         var self = $(this);
            
    //         getAjaxForm ( self );

    //         return false;
    //     });

    //     function getAjaxForm ( self ) {
    //         var url = self.attr('action');
    //         var type = self.attr('data-type');
    //         var flag_alert = self.attr('data-alert');
    //         var data_ajax_type = self.attr('data-ajax-type');
    //         var formData = self.serialize(); 
    //         var data_wrapper_write = self.attr('data-wrapper-write');
    //         var data_wrapper_success = self.attr('data-wrapper-success');
    //         var data_pushstate = self.attr('data-pushstate');
    //         var data_url_pushstate = self.attr('data-url-pushstate');

    //         if( flag_alert != null ) {
    //             if ( !confirm(flag_alert) ) { 
    //                 return false;
    //             }
    //         }

    //         if(typeof data_ajax_type == 'undefined' ) {
    //             data_ajax_type = 'html';
    //         }

    //         if(typeof data_wrapper_write == 'undefined' ) {
    //             data_wrapper_write = '#wrapper-write';
    //         }

    //         if(typeof data_pushstate == 'undefined' ) {
    //             data_pushstate = false;
    //         }

    //         if(typeof data_url_pushstate != 'undefined' ) {
    //             data_url_pushstate = url;
    //         }

    //         $.ajax({
    //             url: url,
    //             type: 'POST',
    //             dataType: data_ajax_type,
    //             data: formData,
    //             success: function(result) {
    //                 if( type == 'content' ) {
    //                     var content = result;
    //                     var status = $(content).find('#msg-status').html();
    //                     var msg = $(content).find('#msg-text').html();
    //                     var contentHtml = $(content).filter(data_wrapper_write).html();

    //                     if(typeof contentHtml == 'undefined' ) {
    //                         contentHtml = $(content).find(data_wrapper_write).html();
    //                     }

    //                     if( $(data_wrapper_write).length > 0 ) {
    //                         if(status == 'success' && typeof data_wrapper_success != 'undefined' && $(data_wrapper_success).length > 0 ) {
    //                             contentHtml = $(content).filter(data_wrapper_success).html();

    //                             if(typeof contentHtml == 'undefined' ) {
    //                                 contentHtml = $(content).find(data_wrapper_success).html();
    //                             }

    //                             $(data_wrapper_success).html(contentHtml);

    //                             if( data_pushstate != false ) {
    //                                 window.history.pushState('data', '', data_url_pushstate);
    //                             }
    //                         } else {
    //                             $(data_wrapper_write).html(contentHtml);
    //                         }
    //                         $.rebuildFunction();
    //                     }
    //                 }

    //                 return false;
    //             },
    //             error: function(XMLHttpRequest, textStatus, errorThrown) {
    //                 alert('Gagal melakukan proses. Silahkan coba beberapa saat lagi.');
    //                 return false;
    //             }
    //         });
    //     }
    // }

    $.closePopover = function( obj ) {
        $('.close-popup').click(function (e) {
            e.preventDefault();

            var self = $(this);
            var target = self.attr('data-target');

            $(target).html('');
        });
    }

    $.rebuildFunction = function() {
        if( $('.jscroll').length > 0 ) {
            $('.jscroll').jscroll();
        }

        if( $('.ajax-link').length > 0 ) {
            $('.ajax-link').off('click');
            $.ajaxLink();
        }

        if( $('.ajax-form').length > 0 ) {
            $('.ajax-form').off('submit');
            $.ajaxForm();
        }
        
        if( $("#read-inbox").length > 0 ) {
            $("#read-inbox").off('animate');
            $("#read-inbox").animate({ scrollTop: $("#read-inbox")[0].scrollHeight}, 500);
        }

        if( $('#single-fileupload').length > 0 ) {
            $('#single-fileupload').fileupload();
        }

        $.checkboxInput();
        $.uploadMedias();
        $.rowAdded();
        $.datePicker();
        $.ajaxModal();
        $.closePopover();
        $.rebuild_toggle_form();

        if($('[data-toggle="popover"]').length > 0){
            $('[data-toggle="popover"]').popover();
        }
    }

    $.rebuildFunctionAjax = function( obj ) {
        $.rebuildFunction();
        $.inputPrice({
            obj: obj.find('.input_price'),
        });
        $.datePicker({
            obj: obj.find('.datepicker'),
        });
    }

    $.delete_custom_loan = function(){
        $('.delete-custom-loan').click(function (e) {
            var action_type = $(this).attr('action_type');
            if( action_type == 'extend-content-loan' ) {
                var idx = $(this).attr('rel');
                $('#idx-extend-'+idx).remove();
                var chk = document.getElementsByClassName('label-slide-content');
                var len = chk.length;
                
                if(len > 0){
                    for(i=0;i<len;i++){
                        chk[i].innerHTML = 'Min. Pinjaman '+(i+1);
                    }
                }
            }
        });
    }

    function replace_integer(value){
        value = value.replace(/,/g,'');
        return value;
    }

    $.calculate_submit_appraisal = function(parent){
        var settings = $.extend({
            obj : $('.KPR-price, .loan_plafond, .persen-loan-id, .down-payment-id, .interest_rate, .periode_fix, .provision_agen, .provision_rku'),
            obj_periode : $('.periode_fix, .credit_fix'),
        },parent);

        if(settings.obj_periode.length > 0){

            settings.obj_periode.off('blur');
            settings.obj_periode.blur(function(){
                var self = $(this);
                var atribute = self.attr('action_type');
                var value = $.convertNumber(self.val(), 'int');
                var default_value = self.attr('data-default');
                var label_name = self.attr('label-name');
                var periode_limit = $.convertNumber(parent.find('.hide_periode').val(), 'int');

                if( value > periode_limit ){
                    alert('Max '+label_name+' melebihi dari setting : '+ periode_limit +' tahun');
                    self.val(default_value);
                }

            });

        }

        if(settings.obj.length > 0){

            settings.obj.off('blur');
            settings.obj.blur(function(e){
                var self = $(this);
                var action_type = self.attr('action_type');

                /*DEKLARASi*/
                var property_price = parent.find('.KPR-price');
                var loan_amount = parent.find('.loan_plafond');
                var interest_rate = parent.find('.interest_rate').val();
                var down_payment = parent.find('.down-payment-id');
                var persen_dp_loan = parent.find('.persen-loan-id');
                var pay_btn = parent.find('.pay-btn');
                var periodeFix = parent.find('.periode_fix');
                var credit_floating  = parent.find('.floating_rate').val();
                
                var provision_agen = parent.find('.provision_agen');
                var provision_rku = parent.find('.provision_rku');

                var periode_fix = parent.find('.periode_fix').val();

                property_price_val = replace_integer(property_price.val());
                var loan_amount_val = replace_integer(loan_amount.val());

                if(action_type == 'loan_price'){
                    var set_down_payment = property_price_val - loan_amount_val;
                    var set_persen_loan = (set_down_payment/property_price_val)*100;
                    set_down_payment = tandaPemisahTitik(set_down_payment);
                    set_persen_loan = (set_persen_loan.toFixed(2));
                    down_payment.val(set_down_payment);
                    persen_dp_loan.val(set_persen_loan);

                }else if(action_type == 'persen_loan'){
                    var persen_dp_loan_val = persen_dp_loan.val();
                    var set_down_payment = (persen_dp_loan_val/100)*property_price_val;
                    loan_amount_val = property_price_val - set_down_payment;

                    set_down_payment = tandaPemisahTitik(set_down_payment);
                    loan_amount_val = tandaPemisahTitik(loan_amount_val);

                    down_payment.val(set_down_payment);
                    loan_amount.val(loan_amount_val);

                }else if(action_type == 'down_payment'){
                    var down_payment_val = down_payment.val();
                    down_payment_val = replace_integer(down_payment_val);
                    var set_persen_loan = (down_payment_val/property_price_val)*100;
                    var loan_amount_val = property_price_val - down_payment_val;

                    loan_amount_val = tandaPemisahTitik(loan_amount_val);
                    set_persen_loan = (set_persen_loan.toFixed(2));
                    persen_dp_loan.val(set_persen_loan);
                    loan_amount.val(loan_amount_val);
                
                }

                if(provision_agen.length > 0){
                    var commission_agen = parent.find('.commission_agen');
                    var provision_agen_val = provision_agen.val();
                    loan_amount_val = replace_integer(loan_amount.val());

                    var komisi_agen = Math.round((provision_agen_val/100)*loan_amount_val);
                    komisi_agen = tandaPemisahTitik(komisi_agen);

                    commission_agen.val(komisi_agen);
                }

                if(provision_rku.length > 0){
                    var commission_rku = parent.find('.commission_rku');
                    var provision_rku_val = provision_rku.val();
                    loan_amount_val = replace_integer(loan_amount.val());

                    var komisi_rku = Math.round((provision_rku_val/100)*loan_amount_val);
                    komisi_rku = tandaPemisahTitik(komisi_rku);

                    commission_rku.val(komisi_rku);
                }
                 
                /*SET cicilan pertama*/
                loan_amount_val = replace_integer(loan_amount_val);
                var first_credit = creditFix(loan_amount_val, interest_rate, periode_fix);
                var first_credit_view   = tandaPemisahTitik(first_credit);
                pay_btn.val(first_credit_view);
            });

            // settings.obj_persen_loan.blur(function(e){

            // });
        }

    }

    function creditFix(amount, rate, year){
        if( rate == 'undefined' ){
            return 0;
        } else {

            if( rate != 0 ) {
                rate = (rate/100)/12;
            }
            var rateYear    = Math.pow((1+rate), (year*12));
            var rateMin     = (Math.pow((1+rate), (year*12))-1);

            if( rateMin != 0 ) {
                rateYear    = rateYear / rateMin;
            }

            var mortgage    = rateYear * amount * rate; // rumus angsuran fix baru 
            return mortgage;
        }
    }

    function tandaPemisahTitik(b){  
            b = Math.round(b);
            var _minus = false;
            if (b<0) _minus = true;
            b = b.toString();
            b=b.replace(",","");
            b=b.replace("-","");
            c = "";
            panjang = b.length;
            j = 0;
            for (i = panjang; i > 0; i--){
                 j = j + 1;
                 if (((j % 3) == 1) && (j != 1)){
                   c = b.substr(i-1,1) + "," + c;
                 } else {
                   c = b.substr(i-1,1) + c;
                 }
            }
            if (_minus) c = "-" + c ;
            return c;
        }

    $.add_custom_loan = function(options){

        var settings = $.extend({
            obj: $('.add-custom-loan'),
        }, options );

         if( settings.obj.length > 0 ){
            settings.obj.click(function(e){
                e.preventDefault();
                var self = $(this);
                var content = '';
                var action_type = self.attr('action_type');
                if(action_type == 'extend-content-loan'){
                    var length = parseInt($('#addMinLoan .field-content').length);
                    var idx = length;
                    $('#addMinLoan').append(''+
                        '<div class="field-content" id="idx-extend-'+idx+'">'+
                            '<div class="form-group">'+
                                '<div class="row">'+
                                    '<div class="col-xl-3 col-sm-4">'+
                                        '<h2 class="sub-heading label-slide-content">Min. Pinjaman '+(idx+1)+'</h2>'+
                                    '</div>'+
                                    '<div class="col-sm-1 text-left no-pd" style="margin-top:10px;">'+
                                        '<a href="javascript:" class="offset6 delete-custom-loan btn red" action_type="extend-content-loan" rel="'+idx+'"><span class="fa fa-times"></span> Hapus</a>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+

                            '<div class="form-group">'+
                                '<div class="row">'+
                                    '<div class="col-sm-12">'+
                                        '<div class="row">'+
                                            '<div class="col-xl-2 col-sm-3 taright">'+
                                                '<label for="BankCommissionSettingLoan'+idx+'MinLoan" class="control-label">Min. Pinjaman *</label>'+
                                            '</div>'+
                                            '<div class="relative  col-sm-8 col-xl-7">'+
                                                '<input name="data[BankCommissionSettingLoan]['+idx+'][min_loan]" class="input_price form-control" id=BankCommissionSettingLoan'+idx+'MinLoan" type="text">'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+

                            '<div class="form-group">'+
                                '<div class="row">'+
                                    '<div class="col-sm-12">'+
                                        '<div class="row">'+
                                            '<div class="col-xl-2 col-sm-3 taright">'+
                                                '<label for="BankCommissionSettingLoan'+idx+'Rate" class="control-label">Provisi Agen *</label>'+
                                            '</div>'+
                                            '<div class="relative col-sm-4 col-xl-3 input-group">'+
                                                '<input name="data[BankCommissionSettingLoan]['+idx+'][rate]" class=" has-side-control at-right form-control" id="BankCommissionSettingLoan'+idx+'Rate" type="text"><div class="input-group-addon at-right">%</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+

                            '<div class="form-group">'+
                                '<div class="row">'+
                                    '<div class="col-sm-12">'+
                                        '<div class="row">'+
                                            '<div class="col-xl-2 col-sm-3 taright">'+
                                                '<label for="BankCommissionSettingLoan'+idx+'RateCompany" class="control-label">Provisi Rumahku *</label>'+
                                            '</div>'+
                                            '<div class="relative col-sm-4 col-xl-3 input-group">'+
                                                '<input name="data[BankCommissionSettingLoan]['+idx+'][rate_company]" class=" has-side-control at-right form-control" id="BankCommissionSettingLoan'+idx+'Rate" type="text"><div class="input-group-addon at-right">%</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>');

                    $.delete_custom_loan();
                    $.inputPrice();
                }

            });
         }

    }

    // $.StatusFloating = function(options){
    //     var settings = $.extend({
    //         obj: $('#status_floating'),
    //     }, options );

    //     if( settings.obj.length > 0 ){
    //             settings.obj.click(function(e){
    //             var self = $(this);
    //             if($('#PersenLoan').length > 0 ){
    //                 var persenLoan = $('#PersenLoan');
    //                 attrDisabled = persenLoan.attr('disabled');
    //                 if(attrDisabled == 'disabled'){        
    //                    persenLoan.removeProp('disabled');
    //                 }else{
    //                     persenLoan.prop('disabled','disabled');
    //                     persenLoan.val('1');
    //                 }
    //                 // removeAttribute
    //             }

    //         });
            

    //     }

    // }

    $.inputPrice = function(options){
        var settings = $.extend({
            obj: $('.input_price'),
        }, options );

        if( settings.obj.length > 0 ) {
            settings.obj.priceFormat({
                doneFunc: function(obj, val) {
                    currencyVal = val;
                    currencyVal = currencyVal.replace(/,/gi, "")
                    obj.next(".input_hidden").val(currencyVal);
                }
            });
        }
    }

    $.inputNumber = function(options){
        var settings = $.extend({
            obj: $('.input_number'),
        }, options );

        if( settings.obj.length > 0 ) {
            settings.obj.off('keypress');
            settings.obj.keypress(function(event) {

                var charCode = (event.which) ? event.which : event.keyCode;

                if( (this.value.length == 0 && charCode == 46) || charCode == 33 || charCode == 64 || charCode == 35 || charCode == 36 || charCode == 37 || charCode == 94 || charCode == 38 || charCode == 42 || charCode == 40 || charCode == 41
                    ){
                    return false;
                } else {
                    if (
                        charCode == 8 ||  /*backspace*/
                        charCode == 46 || /*point*/
                        charCode == 9 || /*Tab*/
                        charCode == 27 || /*esc*/
                        charCode == 13 || /*enter*/
                        // charCode == 97 || 
                        // Allow: Ctrl+A
                        // (charCode == 65 && event.ctrlKey === true) ||
                        // Allow: home, end, left, right
                        (charCode >= 35 && charCode < 39) || ( charCode >= 48 && charCode <= 57 )
                        ) 
                    {
                        return true;
                    }else if (          
                        (charCode != 46 || ($(this).val().indexOf('.') != -1)) || 
                        (charCode < 48 || charCode > 57)) 
                    {
                        event.preventDefault();
                    }
                }
            });     
        }
    }

    $.showHide = function(self){
        self.change(function(){
            var self = $(this);
            var value = self.is(':checked');
            var display_attributes = self.attr('display-attributes');
            var display_field = $.checkUndefined(self.attr('display-field'), false);
            var class_showHide = $(display_attributes);
            // CUSTOM
            if(display_field == 'status_marital'){
                value = self.val();
                if( value == 'marital' ){
                    value = false;
                }else if( value == 'single'){
                    value = true;
                }
            }
            //
            if(value){
                class_showHide.hide();
            }else{
                class_showHide.show();
            }  
            
        });
    }

   

    $.addCustomTextField = function(){
        $('.add-custom-field').click(function (e) {
            e.preventDefault();

            var self = $(this);
            var action_type = self.attr('action_type');

            if( action_type == 'career' ) {
                var length = parseInt( $('#career-requirement-list > ul > li').length );
                var index = length;
                $('#career-requirement-list').children('ul').append('<li><input name="data[CareerRequirement][name]['+index+']" class="form-control" type="text" id="CareerRequirementName'+index+'"></li>');
            }
        });
    }

    $.checkboxInput = function(options){
        var settings = $.extend({
            obj: $('.checkbox label, .cb-checkmark label'),
        }, options );

        if( settings.obj.length > 0 ) {
            settings.obj.off('click');
            $('.cb-checkmark').removeClass('checked');

            settings.obj.click(function(e){
                var self = $(this);
                var rdLine = self.parents('.rd-line');
                var parent = self.parents('.cb-checkmark');


                if( parent.hasClass('radio') ) {
                    var input = parent.children('input[type="radio"]');
                    rdLine.find('input[type="checkbox"]').attr('checked', false).prop('checked', false);
                    
                    input.attr('checked', true).prop('checked', true);
                } else {
                    var input = parent.children('input[type="checkbox"]');
                }
            });
        }
    }

    $.uploadMedias = function(options){
        var settings = $.extend({
            obj: $('.wrapper-upload-medias .btn.uploads,.user-photo .pick-file'),
            objTrigger: $('.fileupload-buttonbar input[type="file"]'),
        }, options );

        if( settings.obj.length > 0 ) {
            settings.obj.off('click');
            settings.obj.click(function(e){
                settings.objTrigger.trigger("click");
                return false;
            });
        }
    };

    

    $.checkAll = function(options){
        var settings = $.extend({
            obj: $('.checkAll'),
            objTarget: $('.check-option'),
            objTargetCount: $('.check-count-target'),
            objClick: $('.check-multiple-delete'),
            objForm: $('.form-target'),
        }, options );

        var countChecked = function ( objTrgtCheckAll, objTrgtChk, objTrgtCnt, defaults, divider ) {
            var count = 0;
            var count_all = objTrgtChk.length;
            var data_show = '.delete-overflow';

            $.each( objTrgtChk, function( i, val ) {
                var data_val = $(val);
                var parent = data_val.parents('.cb-checkmark');
                var custom_data_show = parent.children('label').attr('data-show');

                if( $(custom_data_show).length > 0 ) {
                    data_show = custom_data_show;
                }

                if( parent.find('input[type="checkbox"]').is(':checked') ) {
                    count += 1;
                }
            });

            var text = defaults;

            if( count > 0 ) {
                if( divider == 'false' ) {
                    text = count;
                } else {
                    text = ' ('+count+')';
                }

                settings.objClick.removeClass('hide');

                if( $(data_show).length > 0 ) {
                    $(data_show).show();
                }
            }else{
                settings.objClick.addClass('hide');
                
                if( $(data_show).length > 0 ) {
                    $(data_show).hide();
                }
            }

            if(count_all == count){
                objTrgtCheckAll.prop('checked', true);
            }else{
                objTrgtCheckAll.prop('checked', false);
            }

            objTrgtCnt.html(text);
            $('.delete-overflow .counter span').html(count);
        }

        settings.obj.off('click');
        settings.obj.click(function(){
            var self = $(this);
            var data_parent = $.checkUndefined(self.attr('data-parent'), false);
            var data_target = $.checkUndefined(self.attr('data-target'), false);
            var data_count = $.checkUndefined(self.attr('data-count'), 'true');
            var data_count_target = $.checkUndefined(self.attr('data-count-target'), false);
            var data_count_divider = $.checkUndefined(self.attr('data-count-divider'), false);
            var data_count_default = $.checkUndefined(self.attr('data-count-default'), '');
            var objTrgtCount = settings.objTargetCount;

            if( data_parent != false ) {
                objTrgt = self.parents(data_parent).find(data_target);
                objTrgtCheck = self.parents(data_parent).find('.check-option');
                objTrgtCheckAll = self.parents(data_parent).find('.checkAll');

                if( data_count_target != false ) {
                    objTrgtCount = self.parents(data_parent).find(data_count_target);
                }
            } else {
                objTrgt = settings.objTarget;
                objTrgtCheck = settings.objTarget;
                objTrgtCheckAll = settings.obj;
            }

            if( $(this).is(':checked') ) {
                objTrgt.prop('checked', true);
            } else {
                objTrgt.prop('checked', false);
            }

            if( data_count == 'true' ) {
                countChecked( objTrgtCheckAll, objTrgtCheck, objTrgtCount, data_count_default, data_count_divider );
            }
        });

        settings.objTarget.off('click');
        settings.objTarget.click(function(){
            var self = $(this);
            var data_parent = $.checkUndefined(self.attr('data-parent'), false);
            var data_count_target = $.checkUndefined(self.attr('data-count-target'), false);
            var data_count_divider = $.checkUndefined(self.attr('data-count-divider'), false);
            var data_count_default = $.checkUndefined(self.attr('data-count-default'), '');

            if( data_parent != false && data_count_target != false ) {
                objTrgt = self.parents(data_parent).find(data_count_target);
                objTrgtCheck = self.parents(data_parent).find('.check-option');
                objTrgtCheckAll = self.parents(data_parent).find('.checkAll');
            } else {
                objTrgt = settings.objTargetCount;
                objTrgtCheck = settings.objTarget;
                objTrgtCheckAll = settings.obj;
            }

            countChecked( objTrgtCheckAll, objTrgtCheck, objTrgt, data_count_default, data_count_divider );
        });

        settings.objClick.off('click');
        settings.objClick.click(function(){
            var self = $(this);
            var url = self.attr('href');
            var msg = self.attr('data-alert');
            var flagChecked = false;

            $.each( settings.objTarget, function( i, val ) {
                var selfEach = $(this);
                if( selfEach.is(':checked') ) {
                    flagChecked = true;
                }
            });

            if( flagChecked == true ) {
                if(typeof msg != 'undefined' ) {
                    if ( !confirm(msg) ) { 
                        return false;
                    }
                }
                settings.objForm.attr('action', url);
                settings.objForm.submit();
            } else {
                alert('Mohon centang salah satu data yang ada di table');
            }

            return false;
        });

        $.ajaxModal();
    }

    $.rowAdded = function(options){
        var settings = $.extend({
            obj: $('.field-added'),
        }, options );

        if( settings.obj.length > 0 ) {
            settings.obj.off('click');
            settings.obj.click(function(e){
                var self = $(this);
                var parentAdded = self.parents('.form-added');
                var parentUl = parentAdded.children('ul');
                var temp = parentAdded.find('.field-copy');
                var value = temp.html();

                parentUl.append( '<li>'+value+'</li>' );

                parentUl.find('li:last-child input[type="text"]').val('');
                parentUl.find('li:last-child .error-message').remove();
                // $.rebuildFunctionAjax( parentUl.find('li:last-child') );
                
                $.rowRemoved();

                return false;
            });

            $.rowRemoved();
        }
    }

    $.rowRemoved = function(options){
        var settings = $.extend({
            obj: $('.form-added .removed'),
        }, options );

        if( settings.obj.length > 0 ) {
            settings.obj.off('click');
            settings.obj.click(function(e){
                var self = $(this);

                var parentLi = self.parents('li');

                parentLi.remove();
                return false;
            });
        }
    }

    $.rebuild_toggle_form = function(options){
        var settings = $.extend({
            obj: $('.toggle-input'),
        }, options );

        if(settings.obj.length > 0){
            settings.obj.bootstrapToggle()
        }
    }
}( jQuery ));