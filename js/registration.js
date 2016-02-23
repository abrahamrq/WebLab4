$(function(){
  $('#login-form').submit(function(e){
    e.preventDefault();
    var errors = [];
    if ($('#username').val() == '') errors.push('You must type a username');
    if ($('#email').val() == '') errors.push('You must type a email');
    if ($('#password').val() == '') errors.push('You must type a password');
    if ($('#repeat-password').val() == '') errors.push('You must confirm your password');
    if (errors.length > 0){
      swal({
        title: 'Error',
        text: errors.join('<br>'),
        type: 'error',
        html: true
      });
    } else {
      if ($('#password').val() == $('#repeat-password').val()){
        var jsonObject = {
          'user_name' : $('#username').val(),
          'password' : $('#password').val(),
          'email' : $('#email').val(),
          'side' : $('input[name=side]:checked').val(),
          'action' : 'REGISTER'
        };
        $.ajax({
          type: 'POST',
          url: 'data/applicationLayer.php',
          dataType: 'json',
          data: jsonObject,
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          success: function(jsonData) {
            window.location.replace('home.html');   
          },
          error: function(errorMsg){
            swal('Error', errorMsg.statusText, 'error');
          }
        });
      } else {
        swal({
          title: 'Error',
          text: "Password and confirmation doesn't match.",
          type: 'error',
          html: true
        });
      }
    }
  });
});

