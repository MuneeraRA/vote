<?php 

// This method for creating a session [session id]  :
// requireed token & user_id :
// responce session id 
$app->post('/submit_answer', function() use ($app) {
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
            // fetch new session data 
            $sql3 = "select id from vote_record where user_id='".$user_id."' and session_id='".$session_id."'";
            $stmt3 = $db->query($sql3); 
            if ($stmt3->rowCount()>0){

            $info=$stmt3->fetchAll();
            $recored_id=$info[0][0];
            $question_id=$_POST['questionId'];
            $answer_id=$_POST['answerId'];

            $sql2 = "insert into vote_answer_record VALUES ('', '$recored_id', '$question_id','$answer_id')";
            $stmt2 = $db->query($sql2); 
                if ($stmt2->rowCount()>0){
               $result = new stdClass();
               $result->status=1;
               $result->error="insert seccesfully";}
                

                else {
                 // session not found 
                $result = new stdClass();
                $result->status=0;
                 $result->error="error not complete";}
                 }
	else {   $result = new stdClass();
        $result->status=0;
        $result->error="mshakel";}
        
                 }


    else {
        $result = new stdClass();
        $result->status=0;
        $result->error="invalied token";}


    if (isset($result)){
    header('Content-type: application/json');
    echo json_encode($result);}
	
});