<?php
/**
 * Google Analytics 4 + event hooks
 */
$ga4 = getSetting('ga4_measurement_id', '');
$gsc = getSetting('gsc_verification', '');
if ($gsc): ?>
<meta name="google-site-verification" content="<?= e($gsc) ?>">
<?php endif;
if ($ga4): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($ga4) ?>"></script>
<script>
window.dataLayer=window.dataLayer||[];
function gtag(){dataLayer.push(arguments);}
gtag('js',new Date());
gtag('config','<?= e($ga4) ?>');
window.jkTrack=function(eventName,params){gtag('event',eventName,params||{});};
document.addEventListener('DOMContentLoaded',function(){
  document.querySelectorAll('a[href*="wa.me"]').forEach(function(el){
    el.addEventListener('click',function(){jkTrack('whatsapp_click',{link_url:el.href});});
  });
  document.querySelectorAll('#inquiry-form,#contact-form,#custom-tour-form').forEach(function(f){
    f.addEventListener('submit',function(){jkTrack('form_submit',{form_id:f.id||'unknown'});});
  });
});
</script>
<?php endif; ?>
