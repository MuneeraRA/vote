<?php 

// This method for creating a session [session id]  :
// requireed token & user_id :
// responce session id 
$app->post('/join', function() use ($app) {
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
            $pin_number=$_POST['pinNumber'];
            $sql2 = "select id , state  from session  where pin_number='".$pin_number."'";
            $stmt2 = $db->query($sql2); 
                if ($stmt2->rowCount()>0){
                    $session_info=$stmt2->fetchAll();
                    // check if the user has joind pefore or not ?
                $session_id=$session_info[0]['id'];
                $session_state=$session_info[0]['state'];

                $sql3 = "select id from vote_record where session_id='".$session_id."' and user_id='".$user_id."'";
                $stmt3 = $db->query($sql3); 
                        if ($stmt3->rowCount()>0){

                        $recored_ifno=$stmt3->fetchAll();

                         // user oredy theier
                        $result = new stdClass();
                        $result->status=1;
                        $result->sesstionId=$session_id;
                        $result->recordId=$recored_ifno[0]['id'];
                        $result->state=$session_state;

                        
                                                }
                        else { 
                            $sql4 = "insert into vote_record VALUES ('', '$user_id','$session_id')";
                                    $stmt4 = $db->query($sql4); 
                                        if ($stmt4->rowCount()>0){
                                        $record_id=$db->lastInsertId();
                                        $result = new stdClass();
                                        $result->status=1;
                                        $result->sesstionId=$session_id;
                                        $result->recordId=$record_id;
                                        $result->state=$session_state;
}
                                                    
                            }
                }
                

            else {
                // session not found 
            $result = new stdClass();
            $result->status=2;
            $result->error="session not found";}}
     

    else {
        $result = new stdClass();
        $result->status=0;
        $result->error="invalied token";}


    if (isset($result)){
    header('Content-type: application/json');
    echo json_encode($result);}
	
});