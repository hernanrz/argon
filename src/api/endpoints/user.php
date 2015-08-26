<?php
/*
* Creates and deletes users
* Use the session endpoint for user authentication
*/

switch ($method) {
  //Create a new user
  //How would we prevent spam here?
  case 'POST':
    $user = new User($pdo_link);
    $user->username = $data["username"];
    $user->password = $data["password"];
    $sucess = $user->create();
    
    switch($success) {
      case User::E_USERNAME_EXISTS:
        set_status(403, $STR["username_taken"]);
        break;
      
      case false:
        set_status(403, $STR["register_failed"]);
        break;
      
      default:
        set_status(201, $STR["account_created"]);
        break;
    }
    
    break;
  
  case 'GET':
    $response["debug"] = $AR_SESSION_PAIR;
    if(("notes" == array_shift($path)) && $SESSION_STARTED) {
      $note_list = $CURRENT_USER->fetch_user_notes();
      $response["notes"] = $note_list;
    }
    break;
  
  default:
    set_status(501, unsupported_method);
    break;
}

?>