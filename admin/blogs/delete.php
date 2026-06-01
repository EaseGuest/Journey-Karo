<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$id=(int)($_GET['id']??0); if($id)dbSoftDelete('blogs',$id);
adminFlash('success','Blog deleted.'); header('Location: '.adminUrl('blogs/index.php')); exit;
