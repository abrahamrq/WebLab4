function getUserPosts(user_id){
	$.ajax({
    type: 'POST',
    url: 'data/applicationLayer.php',
    data: {'action': 'GET_USER_POSTS', 'user_id': user_id},
    dataType: 'json',
    success: function(jsonData){
      if (jsonData.status == 'OK'){
        posts = jsonData.posts;
        addPostsToDisplay(posts);
        $('#posts-quantity').html(posts.length);
        $('#posts-quantity').data('quantity', posts.length);
      } else {
        alert(jsonData.message);
      }
    },
    error: function(error){
      alert('Something went wrong when trying to load the items');
    }
  });
}

function setCurrentUserData(user_name, side){
  $('.username-label').html(user_name);
  $('#side-label').html(side + " Side");
}

function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
  function(m,key,value) {
    vars[key] = value;
  });
  return vars;
}