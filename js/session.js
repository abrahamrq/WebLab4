$(function(){
	$.ajax({
    type: 'POST',
    url: 'data/applicationLayer.php',
    dataType: 'json',
    data: {'action':'GET_SESSION'},
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    success: function(data){
      session_user_name = data.user_name;
      session_email = data.email;
      session_side = data.side; 
      session_id = data.id;
      setCurrentUserData(session_user_name, session_side);
    },
    error: function(errorMsg){
      console.log(errorMsg.statusText);
      window.location.replace('login.html');
    }
  });
  $('#logout').click(function(){
  	$.ajax({
      type: 'POST',
      url: 'data/applicationLayer.php',
      dataType: 'json',
      data: {'action':'END_SESSION'},
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      success: function(jsonData) {
        window.location.replace('login.html');
      },
      error: function(errorMsg){
        alert(errorMsg.statusText);
        window.location.replace('login.html');
      }
    });
  });
});