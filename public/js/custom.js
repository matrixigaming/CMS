$(document).ready(function () {
    
    $('.msg-popup-modal').click(function(e) { 
        e.preventDefault();
        $('#modal-response-msg').html('');
        var this_id = $(this).attr('data-id');
        var this_action = $(this).attr('data-action');
        var this_controller = $(this).attr('data-controller');

        var url = base_url + '/' + this_controller + '/type/' + this_action + '/' + this_id;
        //alert(url);
        var jqxhr = $.get(url, function(data) { //console.log(data.status);
            $('#myModal .load_modal').html(data);
            $('#myModal').modal();
        });
        jqxhr.always(function(data) {
            console.log(jqxhr.status);
            if(jqxhr.status != '200'){
                window.location.reload(true);
            }
});
        $('#myModal').on('shown.bs.modal', function () { 
            CKEDITOR.replace('editor');
        });
                 
    });
// used where is image/file upload in popup - 
$('body').on('click', ".pop-up-modal-form-submit-with-image", function (e) { 
        $(this).attr('disabled', true);
        var form_id = $(this).closest("form").attr('id'); //$(this).attr('data-form-id');
        var form = $("#"+form_id).get(0); 
        var url = $(this).closest("form").attr('action');
        e.preventDefault(); //keeps the form from behaving like a normal (non-ajax) html form
        //var url = base_url + action_url;
        for ( instance in CKEDITOR.instances )
            CKEDITOR.instances[instance].updateElement();
        $.ajax({
            url: url,
            type: 'POST',
            data: new FormData(form),
            dataType: 'json',
            mimeType: 'multipart/form-data',
            processData: false,
            contentType: false,
            success: function (response) {
                $(this).removeAttr('disabled');
                console.log(response);
                 $('#modal-response-msg').html(response.msg);
                setTimeout(function(){
                       window.location.reload(true);
                       }, 2000);
                    
            },
            error: function (data) {
                if(data.status == '502' || data.status == '500'){ 
                        $('#modal-response-msg').html('<span style="color:red;">Your session expired, Please login first.</span>');
                        setTimeout(function(){
                           window.location.reload(true);
                           }, 1000);
                    }
                var errors = $.parseJSON(data.responseText);
                //console.log(errors);
                var errorMsg = '';
                $.each(errors, function (index, value) {
                    errorMsg += value + '<br />';
                });
                $('#modal-response-msg').html('<span style="color:red;">' + errorMsg + '</span>');
                $('.pop-up-modal-form-submit-with-image').removeAttr('disabled');
            }
        });
    });
    
    $('body').on('click', '.sweepstakesState', function (e) { 
      if($('input[name="sweepstakes"]').is(':checked')){
           $('.sweepstakesStateField').css('display', 'block');
       }else{
           $('.sweepstakesStateField').css('display', 'none');
       }
    });
    // common function to submit data of popup modal
    $('body').on('click', ".pop-up-modal-form-submit", function (e) { 
       $(this).attr("disabled", true);
        //$(this).prop("disabled", true);
        var form_id = $(this).closest("form").attr('id'); //$(this).attr('data-form-id');
        var form = $("#"+form_id); 
        var url = $(this).closest("form").attr('action');
        //var action_url = $(this).attr('data-action-url');
        e.preventDefault(); //keeps the form from behaving like a normal (non-ajax) html form
        //var url = base_url + action_url;
        for ( instance in CKEDITOR.instances )
            CKEDITOR.instances[instance].updateElement();
        
        var formData = form.serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                $(this).removeAttr('disabled');
                if(response.status){ 
                    $('#modal-response-msg').html(response.msg);
                    //$('#myModal').modal('hide');
                }else{
                    $('#modal-response-msg').html('<span style="color:red;">' + response.msg + '</span>');
                }
                
                setTimeout(function(){
                       window.location.reload(true);
                       }, 2000);
                
            },
            error: function (data) {
                if(data.status == '502' || data.status == '500'){ 
                        $('#modal-response-msg').html('<span style="color:red;">Your session expired, Please login first.</span>');
                        setTimeout(function(){
                           window.location.reload(true);
                           }, 1000);
                    }
                var errors = $.parseJSON(data.responseText);                
                var errorMsg = '';
                $.each(errors, function (index, value) {
                    errorMsg += value + '<br />';
                });
                $('#modal-response-msg').html('<span style="color:red;">' + errorMsg + '</span>');
                $('.pop-up-modal-form-submit').removeAttr('disabled');
            }
        });
    });
    
    // common function to submit data of popup modal
    $('body').on('click', ".pop-up-modal-form-balance-update", function (e) { 
        var cust_transaction_type = $('#cust_transaction_type').val();
        var cust_win_amount = $('#cust_win_amount').val();
        var cust_balance = $('#cust_balance').val();
        var paidAmount = $('#cust_reverse_amount').val();
        
        if(confirm('Are you sure to pay amount('+paidAmount+') to customer?')){
            $(this).attr('disabled', true);
            var form_id = $(this).closest("form").attr('id'); //$(this).attr('data-form-id');
            var form = $("#"+form_id); 
            var url = $(this).closest("form").attr('action');
            //var action_url = $(this).attr('data-action-url');
            e.preventDefault(); //keeps the form from behaving like a normal (non-ajax) html form
        //var url = base_url + action_url;
        

            var formData = form.serialize();
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    $(this).removeAttr('disabled');                
                    if(response.status){ 
                        $('#modal-response-msg').html(response.msg);
                        setTimeout(function(){
                           window.location.reload(true);
                           }, 2000);
                    }else{
                        $('#modal-response-msg').html('<span style="color:red;">' + response.msg + '</span>');
                    }
                },
                error: function (data) {
                    if(data.status == '502' || data.status == '500'){ 
                        $('#modal-response-msg').html('<span style="color:red;">Your session expired, Please login first.</span>');
                        setTimeout(function(){
                           window.location.reload(true);
                           }, 1000);
                    }
                    var errors = $.parseJSON(data.responseText);
                    //console.log(errors);
                    var errorMsg = '';
                    $.each(errors, function (index, value) {
                        errorMsg += value + '<br />';
                    });
                    $('#modal-response-msg').html('<span style="color:red;">' + errorMsg + '</span>');
                }
            }); 
        }
        
    });
    
    $('body').on('change', ".shop-list", function (e) {
        var id = $(this).val();
        e.preventDefault(); //keeps the form from behaving like a normal (non-ajax) html form
        var url = base_url + '/shop/' + id;
        if(id){
           $.ajax({
                url: url,
                type: 'GET',
                //dataType: 'json',
                success: function (response) {
                    //console.log(response);
                    $('.customer-list').html(response);
                }
            }); 
        }else{
            $('.customer-list').html('<option value="">Select Customer</option>');
        }
        
    });
    
    // common function to delete all element
    $('body').on('click', ".img-delete-all", function (e) {
        if(confirm('Are you sure to delete all items?') == true){
            var this_id = $(this).attr('data-id');
            var this_action = $(this).attr('data-action');
            var this_controller = $(this).attr('data-controller');
            var this_delete_from = $(this).attr('data-delete-from');
            
            var url = base_url + '/' + this_controller + '/delete_data/' + this_action + '/' + this_id;
            
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if(response.status){
                        $('#image-preview > #sortable').html('');
                    }else{
                        alert(response.msg);
                        return false;
                    }
                    
                },
                error: function (data) {
                    //var errors = $.parseJSON(data.responseText);
                    console.log(data); return;
                    var errorMsg = '';
                    $.each(errors, function (index, value) {
                        errorMsg += value + '<br />';
                    });
                    $('#page-response-msg').html('<span style="color:red;">' + errorMsg + '</span>');
                }
            });
        }
    });
    
    // common function to delete an element
    $('body').on('click', ".img-delete-a1", function (e) {
        if(confirm('Are you sure to delete this?') == true){
            var this_id = $(this).attr('data-id');
            var this_action = $(this).attr('data-action');
            var this_controller = $(this).attr('data-controller');
            var this_delete_from = $(this).attr('data-delete-from');
            if(this_delete_from == 'session'){
                var url = base_url + '/common' + '/' + this_action + '/' + this_controller+ '/' + this_id;
            }else{
                var url = base_url + '/' + this_controller + '/delete_data/' + this_action + '/' + this_id;
            }
            
            if(this_action=='delete_image'){
                var delId = 'image_'+this_id;
            }else if(this_action=='delete_vedio'){
                var delId = 'video_'+this_id;
            }else if(this_action=='delete_file'){
                var delId = 'file_'+this_id;
            }else if(this_action=='delete_openhouse'){
                var delId = 'openhouse_'+this_id;
            }else if(this_action=='delete_socialmedia' || this_action=='delete_social_media'){
                var delId = 'social_'+this_id;
            }
            //alert(url); return;
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if(response.status){
                        $('#'+delId).remove();
                    }else{
                        alert(response.msg);
                        return false;
                    }
                    
                },
                error: function (data) {
                    //var errors = $.parseJSON(data.responseText);
                    console.log(data); return;
                    var errorMsg = '';
                    $.each(errors, function (index, value) {
                        errorMsg += value + '<br />';
                    });
                    $('#page-response-msg').html('<span style="color:red;">' + errorMsg + '</span>');
                }
            });
        }
    });
    
    
    // common function to delete an element
    $('body').on('click', ".pagination a", function (e) {
        //alert($(this).closest('div').attr('id'));
        var page = $(this).attr('href').split('page=')[1];
        $.ajax({
                url : '?page=' + page,
                dataType: 'html',
                success: function (data) {
                    $('.ajax-response-div').html(data);
                    location.hash = page;
                },
                error: function (data) { 
                    alert('notifications could not be loaded.');
                }
            });
         e.preventDefault();
    });
    
    $('body').on('change', ".shoprtp, .gamertp", function (e) {
        var shopId = $('.shoprtp').val();
        var gameId = $('.gamertp').val();
        e.preventDefault(); //keeps the form from behaving like a normal (non-ajax) html form

       
        var url = base_url + '/games/checkrtp/' + shopId+'/'+gameId;
        if(shopId !='' && gameId !=''){
           $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    //console.log('aaa = '+response.rtp);
                    if(response.status){ 
                        $('.game-rtpval').val(response.rtp);
                        $('.game-rtp-setting').val(response.rtpsettingid);
                    }else{
                        $('.game-rtpval').val('');
                        $('.game-rtp-setting').val('');
                    }
                    //console.log(response);
                    //$('.destination-list').html(response);
                }
            }); 
        }else{
           // $('.destination-list').html('<option value="">Select City</option>');
        }
        
    });

    $('body').on('change', ".shops-video", function (e) {
        var id = $(this).val();
        e.preventDefault(); //keeps the form from behaving like a normal (non-ajax) html form
        var url = base_url + '/shop_video/getShopVideo/' + id;
        if(id){
           $.ajax({
                url: url,
                type: 'GET',
                //dataType: 'json',
                success: function (response) {//console.log(response); console.log(response.id);
                    if(response.status){
                        var vidUrl = base_url+'/'+response.video_name;
                        var vidMsg = 'A video is already uploaded for this shop. Please <a href="'+vidUrl+'" target="_blank"> Click Here</a> to view.<br /> If you want to update it, please continue.';
                        $('.shopVideoDisplaySection').html(vidMsg);
                        $('.shop-tv-video-setting').val(response.id);
                    }else{
                        $('.shopVideoDisplaySection').html('');
                        $('.shop-tv-video-setting').val('');
                    }
                }
            }); 
        }else{
            $('.shopVideoDisplaySection').html('');
            $('.shop-tv-video-setting').val('');
        }
        
    });
    
        $('body').on('change', ".distributor-list", function (e) {
        var id = $(this).val();
        e.preventDefault(); //keeps the form from behaving like a normal (non-ajax) html form
        var url = base_url + '/distributor/shops/' + id;
        if(id){
           $.ajax({
                url: url,
                type: 'GET',
                //dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('.shop-list').html(response);
                }
            }); 
        }else{
            $('.shop-list').html('<option value="">Select Shop</option>');
        }
        
    });
    
    $('body').on('change', ".state-list", function (e) {
        var id = $(this).val();
        e.preventDefault(); //keeps the form from behaving like a normal (non-ajax) html form
        var url = base_url + '/state/cities/' + id;
        if(id){
           $.ajax({
                url: url,
                type: 'GET',
                //dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('.destination-list').html(response);
                }
            }); 
        }else{
            $('.destination-list').html('<option value="">Select City</option>');
        }
        
    });
    


    $("body").on("keypress", ".onlyNumeric", function (e) {
        if (e.which != 8 && e.which != 45 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //$(".e_telephone").html("Input should be numaric").show().fadeOut(1000);
            return false;
        }
    });

    $("body").on('change', '.list-update', function (e){
       e.preventDefault();
       
       var params = {
            value: $(this).val(),
            listing_id: $(this).data('listing-id'),
            field: $(this).data('field'),
            _token: $(this).data('token'),
            controller: $(this).data('controller')
        };
        
        $.ajax({
           type: 'POST',
           url: '/' + 'common' + '/update',
           data: params,
           dataType: 'JSON',
           success: function(response){
               $('.alert').remove();
               
               var html = '';
               if(response['success']){
                    html += '<div class="alert alert-success alert-dismissible">';
                    html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                    html += '<i class="icon fa fa-check"></i> '+ response['message'];
                    html += '</div>';
               }
               
               if(!response['success']){
                    html += '<div class="alert alert-warning alert-dismissible">';
                    html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                    html += '<i class="icon fa fa-check"></i> '+ response['message'];
                    html += '</div>';
               }
               
               $('.content > .row').before(html);
           }
        });
    });
});
