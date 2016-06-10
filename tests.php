<?php
include 'orm/user_option.class.php';
include 'orm/user.class.php';
include 'orm/user_authenticate.class.php';
include 'orm/user_profile.class.php';
include 'orm/fakeuser.class.php';

$fakeuser = new fakeuser();
$fakeuser->username = 'user333';
$fakeuser->city = 'Oslo';
$fakeuser->signature = "I'll be back";
$fakeuser->src_avatar = 'avatar.jpg';
$user_id = $fakeuser->create();

?>