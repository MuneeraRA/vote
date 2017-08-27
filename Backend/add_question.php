<?php 

// This method for creating a question [state]  :
// requireed token & user_id & question & answers 
// responce state

$app->post('/add_question', function() use ($app) {
	$db =getDB();
    $json = $app->request->getBody();
	$data = json_decode($json, true);
    $user_id=$_POST['userId'];
    $token=$_POST['token'];
    // First layer for authontication : if user is exist and the token is correct :
    $sql = "select id  from user  where token='".$token."' and id='".$user_id."'";
	$stmt = $db->query($sql); 
    if ($stmt->rowCount()>0){
        // fetch new question data
        $sessionId=$_POST['sessionId']; 
        $question=$_POST['question'];
        $answer1=$_POST['answer1'];
        $answer2=$_POST['answer2'];
        $answer3=$_POST['answer3'];
        $answer4=$_POST['answer4'];

        $sql2 = "insert into question VALUES ('', '$sessionId', '$question')";
        $stmt2 = $db->query($sql2); 
          
          if ($stmt2->rowCount()>0){
            $questionId=$db->lastInsertId();


             
         if (strlen($answer1)>0) { 
          $sql3 = "insert into answer VALUES ('', '$answer1', '$questionId')";
          $stmt3 = $db->query($sql3); }

	if (strlen($answer2)>0) {
          $sql3 = "insert into answer VALUES ('', '$answer2', '$questionId')";
          $stmt3 = $db->query($sql3); }
          
        if (strlen($answer3)>0) {
          $sql3 = "insert into answer VALUES ('', '$answer3', '$questionId')";
          $stmt3 = $db->query($sql3); }

	if (strlen($answer4)>0) {
          $sql3 = "insert into answer VALUES ('', '$answer4', '$questionId')";
          $stmt3 = $db->query($sql3); }

           $result = new stdClass();
           $result->status=1;
           // success 
           


           }

          else {
            $result = new stdClass();
            $result->status=2;
            // "insartion not complete";
            }
    }
     
    else {
        $result = new stdClass();
        $result->status=0;
       //"invalied token";
        }

    if (isset($result)){
    header('Content-type: application/json');
    echo json_encode($result);}
	
});