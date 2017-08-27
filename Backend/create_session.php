<?php 

// This method for creating a session [session id]  :
// requireed token & user_id :
// responce session id 
$app->post('/create_session', function() use ($app) {
	$db =getDB();
    $json = $app->request->getBody();
	$data = json_decode($json, true);
    $user_id=$_POST['userId'];
    $token=$_POST['token'];
    // First layer for authontication : if user is exist and the token is correct :
    $sql = "select id  from user  where token='".$token."' and id='".$user_id."'";
	$stmt = $db->query($sql); 
    if ($stmt->rowCount()>0){
        // fetch new session data 
        $title=$_POST['title'];
        $description=$_POST['description'];
        $pin_number= sprintf("%04d", mt_rand(1, 9999));;
       // $pin_number=''+$pin_number

       // while (strlen($pin_number)<4)
       // $pin_number="0"+$pin_number;

        $date = date("m.d.y");
        $sql2 = "insert into session VALUES ('', '$user_id', '$pin_number','$title','$description','$date',1)";
        $stmt2 = $db->query($sql2); 
          
          if ($stmt2->rowCount()>0){
            $sessionId=$db->lastInsertId();
           $result = new stdClass();
           $result->status=1;
           $result->sesstionId=$sessionId;}

          else {
            $result = new stdClass();
            $result->status=0;
            $result->error="insartion not complete";}
    }
     
    else {
        $result = new stdClass();
        $result->status=0;
        $result->error="invalied token";}

    if (isset($result)){
    header('Content-type: application/json');
    echo json_encode($result);}
	
});