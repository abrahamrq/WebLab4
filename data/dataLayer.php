<?php
  function connect(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "galactic_news";
    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error){
      return null;
    } else {
      return $connection;
    }
  }

  function errors($type){
    $header = "HTTP/1.1 ";
    switch($type){
      case 306: $header .= "306 Wrong Credentials";
            break;
      case 400: $header .= "400 User Not Found";
            break;
      case 404: $header .= "404 Request Not Found";
            break;
      case 409: $header .= "409 Your action was not completed correctly, please try again later";
            break;
      case 412: $header .= "412 Username or email already in use";
            break;
      case 417: $header .= "417 No content set in the cookie/session";
            break;  
      case 500: $header .= "500 Bad connection to Database";
            break;
      default:  $header .= "404 Request Not Found";
    }
    header($header);
    return array('status' => 'ERROR', 'code' => $type);
  }

  function validateUserCredentials($user_name, $password){
    $conn = connect();
		if ($conn != null){
      $sql = "SELECT * FROM users WHERE user_name = '$user_name' AND passwrd = '$password'";
    	$result = $conn->query($sql);
	    if ($result->num_rows > 0){
	      while ($row = $result->fetch_assoc()){
	        $conn->close();
	        return array("status" => "COMPLETE", "user_name" => $row["user_name"], "email" => $row["email"], "side" => $row["side"], 'id' => $row['id']);
	      }
	    } else {
	      $conn->close();
	      return errors(400);
	    }
    } else {
        $conn->close();
        return errors(500);
    }
  }

  function verifyUserAndEmail($user_name, $email){
    $conn = connect();
    if ($conn != null){
      $sql = "SELECT * FROM users WHERE user_name = '$user_name' OR email = '$email'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0){
        $conn->close();
        return errors(412);
      } else {
        $conn->close();
        return array("status" => "COMPLETE");
      }
    } else {
      $conn->close();
      return errors(500);
    }
  }

  function registerNewUser($user_name, $email, $side, $password){
    $conn = connect();
    if ($conn != null){
      $sql = "INSERT INTO users(user_name, email, passwrd, side) VALUES('$user_name', '$email', '$password', '$side')";
    	if (mysqli_query($conn, $sql)){
        $sql = "SELECT id FROM users WHERE user_name = '$user_name'";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
          $id = $row['id'];
        }
      	$conn->close();
        return array("status" => "COMPLETE", 'id' => $id);
    	} else {
        $conn->close();
        return errors(409);
    	}
    } else {
      $conn->close();
      return errors(500);
    }
  }

  function getPosts($last_post){
    $conn = connect();
    $posts = array();
    if ($conn != null){
      $sql = "SELECT * FROM posts INNER JOIN users ON posts.user_id = users.id";
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()){
        array_push($posts, array("content" => $row['content'], "place" => $row['place'], "created_at" => $row['created_at'], 'user_name' => $row['user_name']));
      }
    }
    $conn->close();
    return $posts;
  }

  function getUserPosts($user_id){
    $conn = connect();
    $posts = array();
    if ($conn != null){
      $sql = "SELECT * FROM posts INNER JOIN users ON posts.user_id = users.id WHERE user_id = '$user_id'";
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()){
        array_push($posts, array("content" => $row['content'], "place" => $row['place'], "created_at" => $row['created_at'], 'user_name' => $row['user_name']));
      }
    }
    $conn->close();
    return $posts;
  }

  function insertPostToDB($content, $place, $created_at, $user_id){
    $conn = connect();
    if ($conn != null){
      $sql = "INSERT INTO posts(content, place, user_id, created_at) VALUES('$content', '$place', '$user_id', '$created_at')";
      if (mysqli_query($conn, $sql)){
        $conn->close();
        return array("status" => "COMPLETE");
      } else {
        $conn->close();
        return errors(409);
      }
    } else {
      $conn->close();
      return errors(500);
    }
  }
?>