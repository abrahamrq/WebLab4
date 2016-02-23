<?php
  header('Content-type: application/json');
  require_once __DIR__ . '/dataLayer.php';  
  $action = $_POST['action'];
  switch($action){
    case 'LOGIN': userLogin();
            break;
    case 'REGISTER':registerUser();
            break;
    case 'GET_SESSION':getSession();
            break;
    case 'END_SESSION':endSession();
            break;
    case 'GET_POSTS': loadPosts(0);
            break;
    case 'GET_USER_POSTS': loadPosts($_POST['user_id']);
            break;
    case 'CREATE_POST': createPost();
            break;
  }

  function verifySession(){
    session_start();
    return(isset($_SESSION['user_name']) && isset($_SESSION['email']) && isset($_SESSION['side']) && isset($_SESSION['id']));
  }

  function createPost(){
    if (verifySession()){
      $content = $_POST['content'];
      $user_id = $_POST['user_id'];
      $place = $_POST['place'];
      $result = insertPostToDB($content, $place, date('Y-m-d H:i:s'), $user_id);
      if ($result['status'] == 'COMPLETE'){
        echo json_encode($result);
      } else {
        die(json_encode($result));
      }
    } else {
      die(json_encode(errors(400)));
    }
  }

  function startSession($user_name, $email, $side, $id){
    session_start();
    $_SESSION['user_name'] = $user_name;
    $_SESSION['email'] = $email;
    $_SESSION['side'] = $side;
    $_SESSION['id'] = $id;
  }

  function endSession(){
    if (verifySession()){
      unset($_SESSION['user_name']);
      unset($_SESSION['email']);
      unset($_SESSION['side']);
      unset($_SESSION['id']);
      session_destroy();
      echo json_encode(array('success' => 'Session deleted'));        
    } else {
      die(json_encode(errors(417)));
    }
  }

  function getSession(){
    if (verifySession()){
      echo json_encode(array("user_name" => $_SESSION['user_name'], "email" => $_SESSION['email'], "side" => $_SESSION['side'], 'id' => $_SESSION['id']));
    } else {
      die(json_encode(errors(417)));
    }
  }

  function loadPosts($user_id){
    $last_post = $_POST['last_post'];
    if (verifySession()){
      if ($user_id > 0){
        $posts = getUserPosts($user_id);
      } else {
        $posts = getPosts($last_post);
      }
      $response = array('posts' => $posts, 'status' => 'OK');
    } else {
      $response = array('status' => 'Error', 'message' => 'NO_SESSION');
    }
    echo json_encode($response);
  }

  function userLogin(){
    $user_name = $_POST['user_name'];
    $password = $_POST['user_password'];
    $rememberData = $_POST['remember_data'];
    $result = validateUserCredentials($user_name, $password);
    if ($result['status'] == 'COMPLETE'){
      if ($rememberData){
        setcookie("cookie_user_name", $user_name);
      }
      $response = array("status" => "VERIFIED");  
      startSession($result['user_name'], $result['email'], $result['side'], $result['id']);
      echo json_encode($response);
    } else {
      die(json_encode($result));
    }
  }

  function registerUser(){
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $result = verifyUserAndEmail($user_name, $email);
    if ($result['status'] == 'COMPLETE'){
      $side = $_POST['side'];
      $password = $_POST['password'];
      $result = registerNewUser($user_name, $email, $side, $password);
      if ($result['status'] == 'COMPLETE'){
        startSession($user_name, $email, $side, $result['id']);
        echo json_encode($result);
      } else {
        die(json_encode($result));
      }
    } else {
      die(json_encode($result));
    }
  }
?>