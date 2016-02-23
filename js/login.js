$(function(){
  $('#login-form').submit(function(e){
    e.preventDefault();
    var errors = [];
    var fakeuser = "bobafett";
    var password = "slave2"
    if ($('#username').val() == '') errors.push('You must type a username');
    if ($('#password').val() == '') errors.push('You must type a password');
    if (errors.length > 0){
      swal({
        title: 'Error',
        text: errors.join('<br>'),
        type: 'error',
        html: true
      });
    } else {
      var jsonObject = {
        'user_name' : $('#username').val(),
        'user_password' : $('#password').val(),
        'remember_data' : $('#rememberme').is(':checked'),
        'action' : 'LOGIN'
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
    }
  });
});