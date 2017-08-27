<?php
// This method for registered a user in the application [user id & token]  :
// requireed uniqe username & password :
// responce session id & token 
$app->post('/register', function() use ($app) {
$db =getDB();
$json = $app->request->getBody();
$data = json_decode($json, true);

$username = $_POST['username'];
$qry= "select * from user where username ='". $username."'";
$stmt = $db->query($qry); 
if ($stmt->rowCount()>0){
$result = new stdClass();
$result->status=0;
$result->error=" the user is already registered ";
}
else {
$password =hash('sha256',$_POST['password']);
$token=hash('sha256',uniqid(rand(),true)) ;
$insert_req="INSERT INTO user VALUES ('', '$username', '$password','$token')";
$stmt2 = $db->query($insert_req); 

if ($stmt2) {
$result = new stdClass();
$result->status=1;
$result->token=$token;
$result->id=$db->lastInsertId('id');
$result->username=$username;}

else {$result = new stdClass();
$result->status=0;
$result->error=mysql_error();
}}

if (isset($result)){
header('Content-type: application/json');
echo json_encode($result);}

});