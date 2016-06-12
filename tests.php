<?php
include 'db.class.php';
$scanned_directory = array_diff(scandir('orm'), array('..', '.'));
while($file = array_pop($scanned_directory)) include 'orm/'.$file;

$scanned_directory = array_diff(scandir('lib'), array('..', '.'));
while($file = array_pop($scanned_directory)) include 'lib/'.$file;

include 'fakeuser.class.php';
include 'fakethread.class.php';
include 'fakepost.class.php';


function genword($len=6){
    $a = array('q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m');
    $word = '';
    for($i=0;$i<=$len;$i++) $word.=$a[rand(0,count($a))];    
    return $word;
}



$Forum = new Forum();
$a_forums = $Forum->getNodeIdAndTitle();
$node_id = $a_forums[1]['node_id'];

$FakeUser = new FakeUser();
$FakeUser->username   = genword();
$FakeUser->location   = genword();
$FakeUser->signature  = genword(10).' '.genword(10).' '.genword(10);
$user_id = $FakeUser->create();

$FakeUser->src_avatar = 'pikachu2.jpg';
$FakeUser->loadAvatar();

$FakeThread = new FakeThread();
$FakeThread->node_id = $node_id;
$FakeThread->user_id = $user_id;
$FakeThread->title = "THis is bot message title ".time();
$FakeThread->message = "Hey! I am bot! ".time();
$FakeThread->post_date = time();
$FakeThread->create();
$thread_id = $FakeThread->thread_id;

print_r($FakeThread->thread_id);

$FakePost = new FakePost();
$FakePost->thread_id = $thread_id;
$FakePost->user_id = $user_id;
$FakePost->message = "robots are going ".time();
$FakePost->post_date = time();
$FakePost->create();

print_r($FakePost->post_id);

?>