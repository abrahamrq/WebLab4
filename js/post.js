$(function(){
  $('form#poster').submit(function(e){
    e.preventDefault();
    var post = $('#post-textarea').val();
    if (post == ""){
      swal({
        title: 'Error',
        text: 'You are not posting anything',
        type: 'error',
        html: true
      });
    } else {
      var place = $('#post-place').val();
      var jsonObject = {
        'place' : place,
        'created_at' : moment()._d,
        'content' : post,
        'user_id' : session_id,
        'action' : 'CREATE_POST'
      };
      $.ajax({
        type: 'POST',
        url: 'data/applicationLayer.php',
        dataType: 'json',
        data: jsonObject,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        success: function(jsonData) {
          $('#post-textarea').val('');
          $('#post-place').val('');
          var quantity = $('#posts-quantity').data('quantity') + 1;
          $('#posts-quantity').html(quantity);
          $('#posts-quantity').data('quantity', quantity);
          displayPost(post, place, moment(), session_user_name);
        },
        error: function(errorMsg){
          swal('Error', errorMsg.statusText, 'error');
        }
      });
    }
  });
});

function displayPost(post, place, time, user_name){
  var post_html = '<div class="post row">';
  post_html += '<hr>';
  post_html += '<div class="col-md-1 hidden-xs">';
  post_html += '<img src="images/fett.jpg" class="img-responsive">'
  post_html += '</div>'
  post_html += '<div class="col-md-11">'
  post_html += '<div class="post-owner">' + user_name + '</div>'
  post_html += '<div class="post-message">'+ post + '</div>'
  post_html += '<div class="post-timestamp">'
  var formatted_time = moment(time).format('MM/DD/YYYY') + ' at ' + moment(time).format('h:ma');
  post_html += '<span class="time">'+ formatted_time + '</span>'
  if (place != ""){
    post_html += '<span class="place"> from '+ place +'</span>';
  }
  post_html += '</div>'
  post_html += '</div>'
  post_html += '</div>'
  $('#posts').prepend(post_html);
}

function addPostsToDisplay(posts){
  $.each(posts, function(index, post){
    displayPost(post.content, post.place, moment(post.created_at), post.user_name);
  })
}