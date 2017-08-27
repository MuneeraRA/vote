<?php 

// This method for open my voted session [session object (info & questions)]  :<?php 
// This method for retriving all the users voted session [arrayOfUserSessions]  :
// each session should include : session id , sessionPinNum, title , numberOfVoters , state [open/close]
// post function , the user should send the token if user exist rerurn all his session else return error message 
// if user has no ssetion yeat return null
// requireed token & user_id :

$app->post('/votedSession', function() use ($app) {
	$db =getDB();
    $json = $app->request->getBody();
	$data = json_decode($json, true);
    $user_id=$_POST['userId'];
    $token=$_POST['token'];
    // prepatr needed array for store the chances info
    $data =  array ();
    $count=0;
    // First layer for authontication : if user is exist and the token is correct :
    $sql = "select id  from user  where token='".$token."' and id='".$user_id."'";
	$stmt = $db->query($sql); 
    if ($stmt->rowCount()>0){
        // fetch all user's session created by him
        $sql2 = "select  s.title , s.id , s.owner_id , s.pin_number , s.description , s.state from vote_record v , session s where v.user_id='".$user_id."'and v.session_id=s.id order by id DESC";
	        $stmt2 = $db->query($sql2); 
          // user created at least one session
          if ($stmt2->rowCount()>0){
            $sessions= $stmt2->fetchAll();
            foreach ($sessions as $row) {
                $session_object=new stdClass();
                $session_object->session_id=$row['id'];
                $session_object->session_title=$row['title'];
                $session_object->session_state=$row['state'];
                $session_object->session_pin=$row['pin_number'];
                $session_id=$row['id'];
                // calculate number of voters :
                $sql3="SELECT Count(id) AS NumberOfVoters from vote_record where session_id='".$session_id."'";
                $stmt3 = $db->query($sql3); 
                $row_2 = $stmt3->fetchAll();
                $tem=$row_2[0];
                $session_object->session_num_of_voters=$tem["NumberOfVoters"];
                $data[$count]=$session_object;
                $count++;}

           $result = new stdClass();
           $result->status=1;
           $result->sessions=$data;}

          else {
            $result = new stdClass();
            $result->status=1;
            $result->sessions=null;}
    }
     
    else {
        $result = new stdClass();
        $result->status=0;
        $result->error="invalied token";}

    if (isset($result)){
    header('Content-type: application/json');
    echo json_encode($result);}
	
});