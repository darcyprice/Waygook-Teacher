<?php
// set DOCUMENT_ROOT variable
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "\Waygook-Teacher");

require_once("src/views/head.php");


// AJAX REQUEST
$otherID = $_POST['otherUserID'];
$otherRow = $user->getOtherUser($otherUserID);

echo json_encode($otherRow);