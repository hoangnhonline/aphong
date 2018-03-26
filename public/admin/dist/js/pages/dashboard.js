/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/
var h = screen.height;
var w = screen.width;
var left = (screen.width/2)-((w-300)/2);
var top = (screen.height/2)-((h-100)/2);

function singleUpload(obj) {
    window.KCFinder = {};
    window.KCFinder.callBack = function(url) {
       $('#' + obj.data('set')).val(url);
       $('#' + obj.data('image')).attr('src', $('#app_url').val() + url);
        window.KCFinder = null;
    };
    window.open($('#url_open_kc_finder').val(), 'kcfinder_single','scrollbars=1,menubar=no,width='+ (w-300) +',height=' + (h-300) +',top=' + top+',left=' + left);
}


$(document).ready(function(){

  "use strict";

  $('.btnUploadSingle').click(function(){        
    singleUpload($(this));
  });

  $('#dataForm #name').change(function(){
       var name = $.trim( $(this).val() );         
        $.ajax({
          url: $('#route_get_slug').val(),
          type: "POST",
          async: false,      
          data: {
            str : name
          },              
          success: function (response) {
            if( response.str ){                  
              $('#dataForm #slug').val( response.str );
            }                
          }
        });         
    });

});
var toolbar = [
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: ['Image', 'Bold', 'Italic', 'Underline', 'Subscript', 'Superscript', 'NumberedList', 'BulletedList', 'Link', 'Unlink' ] },                             
    { name: 'styles', items: [ 'Format' ] },
    { name: 'tools', items: [ 'Maximize' ] },                      
];