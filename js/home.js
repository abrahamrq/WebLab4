$(function(){
	$.ajax({
    type: 'POST',
    url: 'data/applicationLayer.php',
    data: {'action': 'GET_POSTS', 'last_post': 0},
    dataType: 'json',
    success: function(jsonData){
      if (jsonData.status == 'OK'){
        posts = jsonData.posts;
        addPostsToDisplay(posts);
      } else {
        alert(jsonData.message);
      }
    },
    error: function(error){
      alert('Something went wrong when trying to load the items');
    }
  });
});