/*
 * jQuery File Upload Plugin JS Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        //url: baseurl+'/common/uploadimages',
        singleFileUploads: true
    });

     $('#fileupload').bind('fileuploaddone', function (e, data) { alert(3);
            var data = "modulename="+$('#modulename').val()+"&id="+$('#id').val();
            jQuery.ajax({
            type: "POST",
            url:baseurl+'/common/getimages',
            data: data,
            success: function(msg){
                if(msg == "sessionexpires") {
                    window.location.href = "/";
                } else {
                    $('#formboxes',parent.document).html(msg);
                    self.parent.tb_init('a.thickbox, area.thickbox, input.thickbox');
                    self.parent.tb_remove();
                }
            }
            });
     })

     $('#fileupload').bind('fileuploadadd', function (e, data) {
         $('#btnAddFiles').hide();
         $('#btnStartUpload').show();
     });
     

});
