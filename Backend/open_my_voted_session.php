<?php 

// This method for open my session [session object (info & questions)]  :
// requireed token & user_id & session_id :
// responce session id 
$app->post('/view_session', function() use ($app) {
	$db =getDB();
    $json = $app->request->getBody();
	$data = json_decode($json, true);
    $user_id=$_POST['userId'];
    $token=$_POST['token'];
    $session_id=$_POST['sessionId'];
    $questions_array= Array();
    $answers_array= Array();
    $q_count=0;
    $an_count=0;
    // First layer for authontication : if user is exist and the token is correct :
    $sql = "select id  from user  where token='".$token."' and id='".$user_id."'";
	$stmt = $db->query($sql); 
    if ($stmt->rowCount()>0){
        // fetch my session info data 
        
        $sql2 = "select title , description , pin_number,state from session where id='".$session_id."'";
        $stmt2 = $db->query($sql2);
          if ($stmt2->rowCount()>0){
        $sessi= $stmt2->fetchAll();
$session_opject=new stdClass();
       $session_opject->title=$sessi[0]['title'];
        $session_opject->description=$sessi[0]['description'];
        $session_opject->pin_number=$sessi[0]['pin_number'];
        $session_opject->state = $sessi[0]['state'];

        $sql3 = "select * from question where session_id='".$session_id."'";
         $stmt3 = $db->query($sql3); 
            if ($stmt3->rowCount()>0){
            $questions= $stmt3->fetchAll();
                foreach ($questions as $row) {
                $question_object=new stdClass();
                $question_object->question_id=$row['id'];
                $question_object->question_=$row['question'];
                $question_id_=$row['id'];

                $sql32 = "select * from vote_record where user_id='".$user_id."' and session_id='".$session_id."'";
                $stmt32 = $db->query($sql32);
                $info1=$stmt32->fetchAll();
                $recored_id=$info1[0][0];
               

                $sql33 = "select id from vote_answer_record where question_id='".$question_id_."' and voted_id='".$recored_id."'";
                $stmt33 = $db->query($sql33);
                
			if ($stmt33->rowCount()>0)
				$question_object->status=1;
			else $question_object->status=0;

                
                $sql4 = "select * from answer where question_id='".$question_id_."'";
                $stmt4 = $db->query($sql4); 
                  if ($stmt4->rowCount()>0){
                     $answers= $stmt4->fetchAll();
                      foreach ($answers as $row2) {
                        $answer_object=new stdClass();
                        $answer_object->answer_id=$row2['id'];
                        $answer_object->answer=$row2['answer'];
                        $answers_array[$an_count]=$answer_object;
                        $an_count++;}
                     $question_object->answers=$answers_array;}
                 else {$question_object->answers=null; }
                $answers_array= Array();
                $an_count=0; 
                $questions_array[$q_count]=$question_object;
                $q_count++;}

            $session_opject->question=$questions_array;
            
            }
            else {
// no question :
            $session_opject->question=null;}
           
            $result = new stdClass();
            $result->status=1;
            $result->session=$session_opject;

            }

          else {
// no sesstion :

            $result = new stdClass();
            $result->status=0;
            $result->error="session id not found";}
    }
     
    else {
        $result = new stdClass();
        $result->status=0;
        $result->error="invalied token";}

    if (isset($result)){
    header('Content-type: application/json');
    echo json_encode($result);}
	

});