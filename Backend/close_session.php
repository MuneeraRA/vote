<?php 

// This method for open my session [session object (info & questions)]  :
// requireed token & user_id & session_id :
// responce session id 
$app->post('/close_session', function() use ($app) {
	$db =getDB();
    $json = $app->request->getBody();
	$data = json_decode($json, true);
    $user_id=$_POST['userId'];
    $token=$_POST['token'];
    $session_id=$_POST['sessionId'];
    
    // First layer for authontication : if user is exist and the token is correct :
    $sql = "select id  from user  where token='".$token."' and id='".$user_id."'";
	$stmt = $db->query($sql); 
    if ($stmt->rowCount()>0){
        $sql2="update session SET state='0' WHERE id='".$session_id."'";
        $stmt2 = $db->query($sql2); 

        if ($stmt2->rowCount()>0){
            $result = new stdClass();
            $result->status=1;
        }
        else {
            $result = new stdClass();
            $result->status=0;
        }


    }
     
    else {
        $result = new stdClass();
        $result->status=0;
        $result->error="invalied token";}

    if (isset($result)){
    header('Content-type: application/json');
    echo json_encode($result);}
	

});