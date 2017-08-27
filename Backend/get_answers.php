<?php 

// This method for open my session [session object (info & questions)]  :
// requireed token & user_id & session_id :
// responce session id 
$app->post('/answers', function() use ($app) {
	
    $db =getDB();
    $json = $app->request->getBody();
	$data = json_decode($json, true);
    $user_id=$_POST['userId'];
    $token=$_POST['token'];
    $answers_array= Array();
    $an_count=0;
    // First layer for authontication : if user is exist and the token is correct :
    $sql = "select id  from user  where token='".$token."' and id='".$user_id."'";
	$stmt = $db->query($sql); 
    if ($stmt->rowCount()>0){
        // fetch my question info data 
        $question_id=$_POST['questionId'];
        $sql2 = "select * from answer where question_id='".$question_id."'";
        $stmt2 = $db->query($sql2); 
            if ($stmt2->rowCount()>0){
            $answers= $stmt2->fetchAll();
            foreach ($answers as $row2) {
                            $answer_object=new stdClass();
                            $answer_object->answer_id=$row2['id'];
                            $answer_object->answer=$row2['answer'];
                            $answers_array[$an_count]=$answer_object;
                            $an_count++;}

                        $result = new stdClass();
                $result->status=1;
                 $result->answers=$answers_array;}
            else {
                $result = new stdClass();
                $result->status=1;
                 $result->answers=null; }
               

            
    }
       

     
    else {
        $result = new stdClass();
        $result->status=0;
        $result->error="invalied token";}

    if (isset($result)){
    header('Content-type: application/json');
    echo json_encode($result);}
	

});