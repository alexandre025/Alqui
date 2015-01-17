<?php

$f3=require('lib/base.php');
$f3->set('DEBUG',1);
$f3->set('UI','templates/');

$f3->route('GET /',function($f3){
  // $view=new View();
  //   echo $view->render('main.html');
  echo View::instance()->render('main.html');
});

$f3->run();
?>