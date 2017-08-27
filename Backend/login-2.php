<?php
$app->post('/login', function() use ($app) {
$db =getDB();
$json = $app->request->getBody();
$data = json_decode($json, true);

$username = $_POST['username'];
$password =hash('sha256',$_POST['password']);

$findemail_qry= "select id from user where username ='". $username."'";
$stmt = $db->query($findemail_qry); 

if($stmt->rowCount()>0)
{
$login_qry="select id , token , username from user where username ='". $username."'AND password ='".$password."'";
$login_result =$db->query($login_qry);
if($login_result->rowCount()>0)
{
 
$data =$login_result->fetchAll(PDO::FETCH_OBJ);
   
$result = new stdClass();
$result=$data[0];
$result->status=1;

}

else{$result = new stdClass();
$result->status=0;
$result->error="wrong password";
 }

}

else {$result = new stdClass();
$result->status=0;
$result->error=" this username not registered ";
}


if (isset($result)){
    header('Content-type: application/json');
echo json_encode($result);}


});
