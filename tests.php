<?php
include 'db.class.php';
$scanned_directory = array_diff(scandir('orm'), array('..', '.'));
while($file = array_pop($scanned_directory)) include 'orm/'.$file;
include 'fakeuser.class.php';
include 'fakethread.class.php';

/*
$User = new User();
$User->username = 'test1';
$User->validateUserName();
$User->email = $User->username.'@google.com';
//$User->insert();
$User->user_id = 14;

$UserAuthenticate = new UserAuthenticate();
$UserAuthenticate->user_id = $User->user_id;
//$UserAuthenticate->insert();

$UserGroupRelation = new UserGroupRelation();
$UserGroupRelation->user_id = $User->user_id;
//$UserGroupRelation->insert();

$UserOption = new UserOption();
$UserOption->user_id = $User->user_id;
//$UserOption->insert();

$UserPrivacy = new UserPrivacy();
$UserPrivacy->user_id = $User->user_id;
//$UserPrivacy->insert();

$UserProfile = new UserProfile();
$UserProfile->location = 'Gonduras';
$UserProfile->signature = 'Smile now - cry later';
$UserProfile->user_id = $User->user_id;
$UserProfile->insert();
*/

/*
$FakeUser = new FakeUser();
$FakeUser->username = 'heyhey';
$FakeUser->location = 'Tagangog';
$FakeUser->signature = 'I ll be back';
$FakeUser->create();
*/


//$User = new User();
//$User->user_id = 4;
//$User->incrementTrophyPoints();

/*
$User = new User();
$User->user_id = 4;
$User->fetch();
print_r($User);
*/

$FakeThread = new FakeThread();
$FakeThread->node_id = 5;
$FakeThread->user_id = 4;
$FakeThread->title = "THis is bot message title ".time();
$FakeThread->message = "Hey! I am bot! ".time();
$FakeThread->post_date = time();
$FakeThread->create();
print_r($FakeThread->thread_id);



//echo sprintf("%02X",255);
//echo sprintf("%04X",65535);
//echo sprintf("%04X",rand(0,65535));
/*
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
*/
?>