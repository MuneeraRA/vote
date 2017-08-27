<?php
require 'confing.php';
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
require_once('login.php');
require_once('register.php');
require_once('owner_sessions.php');
require_once('user_voted_session.php');
require_once('create_session.php');
require_once('join.php');
require_once('open_owner_session.php');
require_once('open_my_voted_session.php');
require_once('get_answers.php');
require_once('view.php');
require_once('submit_answer.php');
require_once('add_question.php');
require_once('votting_statisticd.php');
require_once('close_session.php');
$app->run();
?>