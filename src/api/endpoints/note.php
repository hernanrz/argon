<?php
/**
* Note endpoint Alpha
*/

/**
* Gets the uid, title and content from the note
*/
function get_note_data(Note $note) {
	return [
		'UID' => $note->UID,
		'title' => $note->title,
		'content' => $note->content,
		'private' => (bool)$note->private
	];
}

$note = new Note($pdo_link);
//we dont pass the uid to the constructor to avoid fetching data.
$note->UID = array_shift($path);

$key = array_shift($path);


if(!$note->UID) {
	set_status(403, "Missing UID parameter");
}


/* Flippling switches */
switch($method) {
	//Create a new note
	case "POST":
	
		 if(!expect_parameters("title", "content", "private")) {
			 
			 set_status(403, "Missing a required parameter");
			 
		 }else {
			 
			 $note->title = $data["title"];
			 $note->content = $data["content"];
			 $note->private = (bool)$data["private"];
			 
			 $key = $note->create();
			 
			 set_status(201, "Note created");
			 $response["note"] = get_note_data($note);
			 $response["note"]["key"] = $key;
			 
		 }
		 
	break;
	//Fetch a note
	case "GET":
	
		if($note->fetch_data()) {
			
			$response["note"] = get_note_data($note);
			
		}else {
			
			set_status(404, "Note not found");	
			
		}
		
	break;
	//Delete note from database
	case "DELETE":
		if (($result = $note->delete($key)) === Note::E_INVALID_PKEY) {
			set_status(403, "Invalid key");
		}elseif(!$result){
			set_status(500, "Could not delete note");	
		}else{
			set_status(200, "Note deleted");
		}
	break;
	//Modify note data
	case "PUT":

		$note->fetch_data(); //We need to fetch data before updating to prevent any losses
		//We define which fields the user is permitted to modify
		$allowed_params = [
			'title',
			'content',
			'private'
		]; 
		
		foreach($data as $param=>$val){
			if(property_exists($note, $param) && in_array($param, $allowed_params)){
				$note->$param = $val;
			}	
		}
		
		$result = $note->save_changes($key);
		
		if($result === Note::E_INVALID_PKEY) {
			set_status(403, "Invalid key");
		} elseif (!$result){ 
			set_status(500, "An error occurred");
		} else {					
			$response["note"] = get_note_data($note);
	 		set_status(200, "Changes saved");	
		}

	break;
	
	default:
		set_status(501, "Method not supported");
	break;
}


?>