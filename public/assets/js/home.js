$(document).ready(function(){
	$('a.addcart-link').click(function() {
	    var product_id = $(this).data('id');
	    add_product_to_cart(product_id);
	});
	$.ajaxSetup({
	      headers: {
	          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	      }
	  }); 
});

function add_product_to_cart(product_id) {
$.ajax({
  url: $('#route-add-cart').val(),
  method: "POST",
  data : {
    id: product_id
  },
  success : function(data){
    location.href = $('#route-cart').val();
  },
  error : function(e) {
    
  }
});
}

window.fbAsyncInit = function() {
  FB.init({
    appId      : $('#fb-app-id').val(),
    cookie     : true,  // enable cookies to allow the server to access
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.7' // use graph api version 2.7
  });
};

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

$(document).ready(function() {
  $('.login-by-facebook-popup').click(function() {
    FB.login(function(response){
      if(response.status == "connected")
      {
         // call ajax to send data to server and do process login
        var token = response.authResponse.accessToken;
        $.ajax({
          url: $('#route-ajax-login-fb').val(),
          method: "POST",
          data : {
            token : token
          },
          success : function(data){
            if(!data.success) {
              location.reload();
            } else {
              location.href = $('#route-cap-nhat-thong-tin').val();
            }
          }
        });

      }
    }, {scope: 'public_profile,email'});
  });  
});
$(document).on('keypress', '#popup-login-email, #popup-login-password', function(e){
if(e.keyCode==13){
  $('#login_popup_submit').click();
}
});
$(document).on('keypress', '#popup-register-email, #popup-register-password, #popup-register-name', function(e){
  if(e.keyCode==13){
    $('#register_popup_submit').click();
  }
});
$(document).ready(function () {
	$('#login_popup_submit_1').click(function() {		
        var $form = $(this).parents('form');        
        var error = [];
        var list_check = ['login_email', 'login_password'];
        var login_email    = $form.find('#login_email').val();
        var login_password = $form.find('#login_password').val();
        if(!login_email) {
          error.push('login_email');
        }

        if(!validateEmail(login_email))
        {
          error.push('login_email');
        }

        if(!login_password) {
          error.push('login_password');
        }

        for(i in list_check) {
          $('#'+list_check[i]).parent().removeClass('has-error');
          $('#'+list_check[i]).next().hide();
        }

        if(error.length) {
          for(i in error) {
            $('#'+error[i]).parent().addClass('has-error');
            $('#'+error[i]).next().show();
          }
          return false;
        }

        if(!error.length)
        {
            $.ajax({
              url: $('#route-auth-login-ajax').val(),
              method: "POST",
              data : {
                email: login_email,
                password: login_password
              },
              success : function(data){
               if(data.error == 1)
               {
                  $('#login-form #error_captcha').html('Email hoặc mật khẩu không đúng.')
               }
               else {
                    location.reload();
               }
              }
            });
        }

    });
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#newsletter_email').bind('keypress', function(e) {
      if(e.keyCode==13){
        $('.btn-get-newsletter').click();
      }
    });
    $('#reg_success').bind('keypress', function(e) {
      if(e.keyCode==13){
        $('#btnRegTin').click();
      }
    }); 
    $('#btnRegTin').click(function() {
        var email = $.trim($('#reg_success').val());        
        if(validateEmail(email)) {
            $.ajax({
              url: $('#route-register-newsletter').val(),
              method: "POST",
              data : {
                email: email,
              },
              success : function(data){
                if(+data){
                  swal('', 'Đăng ký nhận bản tin thành công.', 'success');
                }
                else {
                  swal('', 'Địa chỉ email đã được đăng ký trước đó.', 'error');
                }
                $('#reg_success').val("");
              }
            });
        } else {
            swal({ title: '', text: 'Vui lòng nhập địa chỉ email hợp lệ.', type: 'error' });
        }
    });
    $('.btn-get-newsletter').click(function() {
        var email = $.trim($('#newsletter_email').val());
        var $email = $(this).parent().prev();
        if(validateEmail(email)) {
            $.ajax({
              url: $('#route-register-newsletter').val(),
              method: "POST",
              data : {
                email: email,
              },
              success : function(data){
                if(+data){
                  swal('', 'Đăng ký nhận bản tin thành công.', 'success');
                }
                else {
                  swal('', 'Địa chỉ email đã được đăng ký trước đó.', 'error');
                }
                $email.val("");
              }
            });
        } else {
            swal({ title: '', text: 'Vui lòng nhập địa chỉ email hợp lệ.', type: 'error' });
        }
    });

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    $('#login_popup_submit').click(function() {
        var $form = $(this).parents('form');        
        var error = [];
        var list_check = ['popup-login-email', 'popup-login-password'];
        var login_email    = $form.find('#popup-login-email').val();
        var login_password = $form.find('#popup-login-password').val();
        if(!login_email) {
          error.push('popup-login-email');
        }

        if(!validateEmail(login_email))
        {
          error.push('popup-login-email');
        }

        if(!login_password) {
          error.push('popup-login-password');
        }

        for(i in list_check) {
          $('#'+list_check[i]).parent().removeClass('has-error');
          $('#'+list_check[i]).next().hide();
        }

        if(error.length) {
          for(i in error) {
            $('#'+error[i]).parent().addClass('has-error');
            $('#'+error[i]).next().show();
          }
          return false;
        }

        if(!error.length)
        {
            $.ajax({
              url: $('#route-auth-login-ajax').val(),
              method: "POST",
              data : {
                email: login_email,
                password: login_password
              },
              success : function(data){
               if(data.error == 1)
               {
                  $('#login-form #error_captcha').html('Email hoặc mật khẩu không đúng.')
               }
               else {
                    location.reload();
               }
              }
            });
        }

    });
    
    $('#register_popup_submit').click(function(){
        var $form = $(this).parents('form');
        var error = [];
        var list_check = ['popup-register-email', 'popup-register-password', 'popup-register-name'];
        var email = $('#popup-register-email').val();
        var password = $('#popup-register-password').val();
        var fullname = $('#popup-register-name').val();

        if(!email) {
          error.push('popup-register-email');
        }

        if(password.length < 6 || password.length > 32) {
          error.push('popup-register-password');
        }

        if(!fullname) {
          error.push('popup-register-name');
        }

        for(i in list_check) {
          $('#'+list_check[i]).parent().removeClass('has-error');
          $('#'+list_check[i]).parent().find('.help-block').hide();
        }

        if(error.length) {
          for(i in error) {
            $('#'+error[i]).parent().addClass('has-error');
            $('#'+error[i]).next().show();
          }
          return false;
        }

        if(!error.length)
        {
            $.ajax({
              url: $('#route-register-customer-ajax').val(),
              method: "POST",
              data : {
                email: email,
                password: password,
                fullname: fullname
              },
              success : function(data){
                if(data.error == 'email')
                {
                    $('small[er=NOT_VALIDATED]').show();
                    $('small[er=notEmpty]').hide();
                }
                else {
                    location.reload();
                }
              }
            });
        }

    });
    
});
