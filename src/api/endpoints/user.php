<?php
/*
* Creates and deletes users
* Use the session endpoint for user authentication
*/

switch ($method) {
  //Create a new user
  //How would we prevent spam here?
  case 'POST':
    
    if(!preg_match("/^([A-Za-z0-9]|(-|_|\.))+$/", $data["username"])) {
      set_status(403, $STR["invalid_username"]);
    }else {
      $user = new User($pdo_link);
      $user->username = $data["username"];
      $user->password = $data["password"];
      $success = $user->create();
      $response["debug"] = $success;
      
      
      /**
      * Switch won't work in this situation
      * Thanks php
      */
      if($success === 1056) {
        
        set_status(403, $STR["username_taken"]);
        
      }elseif($success === true) {
        
        $session = new Session($pdo_link);
        $session->user = $user;
        $response["session_token"] = $session->grant_token();
    
        set_status(201, $STR["account_created"]);
        
      }else {
        
        set_status(403, $STR["register_failed"]);
        
      }      
    }
    break;
  
  case 'GET':
    if(("notes" == array_shift($path)) && $SESSION_STARTED) {
      $note_list = $CURRENT_USER->fetch_user_notes();
      $response["notes"] = $note_list;
    }
    break;
  
  default:
    set_status(501, $STR["unsupported_method"]);
    break;
}

?>