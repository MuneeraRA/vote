<?php 

// This method for open my session [session object (info & questions)]  :
// requireed token & user_id & session_id :
// responce session id 
$app->post('/votting_result', function() use ($app) {
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

        $sql2 = "select title , pin_number from session where id='".$session_id."'";
        $stmt2 = $db->query($sql2);
          if ($stmt2->rowCount()>0){
        $sessi= $stmt2->fetchAll();
        $session_opject=new stdClass();
        $session_opject->title=$sessi[0]['title'];
        $session_opject->pin_number=$sessi[0]['pin_number'];
            // count number of voters :
            $sql3="SELECT Count(id) AS NumberOfVoters from vote_record where session_id='".$session_id."'";
            $stmt3 = $db->query($sql3); 
            $row_3 = $stmt3->fetchAll();
            $tem=$row_3[0];
            $session_opject->session_num_of_voters=$tem[0]['NumberOfVoters'];

            $sql4 = "select * from question where session_id='".$session_id."'";
            $stmt4 = $db->query($sql4); 
            if ($stmt4->rowCount()>0){
            $questions= $stmt4->fetchAll();
                foreach ($questions as $row) {
                $question_object=new stdClass();
                $question_object->question_id=$row['id'];
                $question_object->question_=$row['question'];
                $question_id_=$row['id'];

                // find number of voters for specific question :

                    $sql5="SELECT Count(id) AS Voters from vote_answer_record where question_id='".$question_id_."'";
                    $stmt5 = $db->query($sql5); 
                    $row_5 = $stmt5->fetchAll();
                    $tem2=$row_5[0];
                    $question_object->question_num_of_voters=$tem2[0]["Voters"];
        
                $sql6 = "select * from answer where question_id='".$question_id_."'";
                $stmt6 = $db->query($sql6); 
                  if ($stmt6->rowCount()>0){
                     $answers= $stmt6->fetchAll();
                      foreach ($answers as $row2) {
                        $answer_object=new stdClass();
                        $answer_object->answer_id=$row2['id'];
                        $answer_object->answer=$row2['answer'];
                        $ans_id=$row2['id'];

                // find number of voters for each answer :

                    $sql7="SELECT Count(id) AS Voters from vote_answer_record where answer_id='".$ans_id."'";
                    $stmt7 = $db->query($sql7); 
                    $row_7 = $stmt7->fetchAll();
                    $tem7=$row_7[0];
                    $answer_object->question_num_of_voters=$tem7[0]["Voters"];
                    $answers_array[$an_count]=$answer_object;
                        $an_count++;}

                     $question_object->answers_result=$answers_array;}
                 else {$question_object->answers_result=null; }
                $answers_array= Array();
                $an_count=0; 
                $questions_array[$q_count]=$question_object;
                $q_count++;}

            $session_opject->question_result=$questions_array;
            
            }
            else {
// no question :
            $session_opject-> question_result =null;}
           
            $result = new stdClass();
            $result->status=1;
            $result->session_result=$session_opject;

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