<?php
require_once dirname(__DIR__) . '/includes/init.php';
requireAdmin();
$id=(int)($_GET['id']??0); if($id)dbSoftDelete('gallery',$id);
header('Location: '.adminUrl('gallery/index.php')); exit;
