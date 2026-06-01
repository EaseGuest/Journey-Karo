<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$pageTitle='Settings'; $activeNav='settings';
if($_SERVER['REQUEST_METHOD']==='POST'){requireCsrfForm();
 foreach($_POST['settings']??[] as $key=>$val){
  $key=clean($key); if(!$key)continue;
  dbQuery("INSERT INTO settings (setting_key,setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)",[$key,clean($val)]);
 }
 adminFlash('success','Settings saved.'); header('Location: '.adminUrl('settings/index.php')); exit;}
$settings=dbFetchAll("SELECT * FROM settings ORDER BY setting_group, setting_key");
$map=[]; foreach($settings as $s)$map[$s['setting_key']]=$s['setting_value'];
require dirname(__DIR__).'/includes/layout-start.php'; adminShowFlash();
?><form method="POST" class="admin-card" style="max-width:560px"><?= csrfField() ?>
<div class="form-group"><label>Tagline</label><input name="settings[site_tagline]" class="form-control" value="<?= e($map['site_tagline']??'') ?>"></div>
<div class="form-group"><label>Phone</label><input name="settings[contact_phone]" class="form-control" value="<?= e($map['contact_phone']??APP_PHONE) ?>"></div>
<div class="form-group"><label>Email</label><input name="settings[contact_email]" class="form-control" value="<?= e($map['contact_email']??APP_EMAIL) ?>"></div>
<div class="form-group"><label>Address</label><textarea name="settings[contact_address]" class="form-control" rows="2"><?= e($map['contact_address']??'') ?></textarea></div>
<div class="form-group"><label>GA4 Measurement ID</label><input name="settings[ga4_measurement_id]" class="form-control" value="<?= e($map['ga4_measurement_id']??'') ?>" placeholder="G-XXXXXXXXXX"></div>
<div class="form-group"><label>Google Search Console verification</label><input name="settings[gsc_verification]" class="form-control" value="<?= e($map['gsc_verification']??'') ?>"></div>
<button class="btn btn-primary">Save settings</button></form><?php require dirname(__DIR__).'/includes/layout-end.php';
