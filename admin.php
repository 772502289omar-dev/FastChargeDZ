<?php

// ⚡ دالة مساعدة: البحث عن عضو بالـ user_id في emils.json
function findEmilKey($uid, $EMILS) {
    $uid = (string)$uid;
    // 1. البحث المباشر بالمفتاح (string)
    if (isset($EMILS['emils'][$uid]['emil'])) return $uid;
    // 2. البحث بالمفتاح كـ integer (للأعضاء القدامى)
    foreach ($EMILS['emils'] as $k => $v) {
        if ((string)$k === $uid) return $k;
    }
    // 3. البحث داخل حقل id
    foreach ($EMILS['emils'] as $k => $v) {
        if ((string)($v['id'] ?? '') === $uid) return $k;
    }
    // 4. البحث داخل حقل emil (للأعضاء بدون id)
    foreach ($EMILS['emils'] as $k => $v) {
        if ((string)($v['emil'] ?? '') === $uid) return $k;
    }
    return null;
}

#=========={دوال المواقع المخصصة - Custom Sites Functions}==========#
if(!function_exists('cs_load')){
function cs_load(){
    $f='data/api/custom_sites.json';
    if(!file_exists($f)) file_put_contents($f,'[]');
    $d=@json_decode(@file_get_contents($f),true);
    return is_array($d)?$d:[];
}
function cs_save(array $d){
    file_put_contents('data/api/custom_sites.json',json_encode($d,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), LOCK_EX);
}
function cs_balance(string $url,string $key):?string{
    $url=rtrim($url,'/');
    $r=@json_decode(@file_get_contents("$url/api?api_key=$key&action=getBalance"),true);
    if(isset($r['ok'],$r['balance'])&&$r['ok']===true) return (string)$r['balance'];
    $t=@file_get_contents("$url/stubs/handler_api.php?api_key=$key&action=getBalance");
    if($t&&strpos($t,'ACCESS_BALANCE:')!==false){$p=explode(':',$t);return trim($p[1]??'');}
    $r=@json_decode(@file_get_contents("$url/api.php?api_key=$key&method=get_balance"),true);
    if(isset($r['data']['balance'])) return (string)$r['data']['balance'];
    $r=@json_decode(@file_get_contents("$url/api/getBalance?apikey=$key"),true);
    if(isset($r['balance'])) return (string)$r['balance'];
    $r=@json_decode(@file_get_contents("$url/users/balance?token=$key"),true);
    if(isset($r['data']['balance'])) return (string)$r['data']['balance'];
    return null;
}
function cs_detect_try(string $url,string $key):?array{
    $url=rtrim($url,'/');
    $r=@json_decode(@file_get_contents("$url/api?api_key=$key&action=getBalance"),true);
    if(isset($r['ok'],$r['balance'])&&$r['ok']===true) return['balance'=>(string)$r['balance'],'format'=>'smsbower','base'=>$url];
    $t=@file_get_contents("$url/stubs/handler_api.php?api_key=$key&action=getBalance");
    if($t&&strpos($t,'ACCESS_BALANCE:')!==false){$p=explode(':',$t);return['balance'=>trim($p[1]??''),'format'=>'standard','base'=>$url];}
    $r=@json_decode(@file_get_contents("$url/api.php?api_key=$key&method=get_balance"),true);
    if(isset($r['data']['balance'])) return['balance'=>(string)$r['data']['balance'],'format'=>'standard','base'=>$url];
    $r=@json_decode(@file_get_contents("$url/api/getBalance?apikey=$key"),true);
    if(isset($r['balance'])) return['balance'=>(string)$r['balance'],'format'=>'standard','base'=>$url];
    $r=@json_decode(@file_get_contents("$url/users/balance?token=$key"),true);
    if(isset($r['data']['balance'])) return['balance'=>(string)$r['data']['balance'],'format'=>'json_token','base'=>$url];
    return null;
}
function cs_detect(string $url,string $key):?array{
    $url=rtrim(trim($url),'/');
    // قائمة النطاقات المرشحة: الأصلي + إضافة api. + إزالة www
    $cands=[$url];
    if(preg_match('#^(https?://)(.+)$#',$url,$m)){
        $scheme=$m[1]; $host=$m[2];
        // إذا لا يبدأ بـ api. أضف نسخة api.
        if(strpos($host,'api.')!==0){ $cands[]=$scheme.'api.'.$host; }
        // إذا يبدأ بـ www. جرب بدونها + api.
        if(strpos($host,'www.')===0){ $bare=substr($host,4); $cands[]=$scheme.$bare; $cands[]=$scheme.'api.'.$bare; }
    }
    foreach($cands as $cand){
        $res=cs_detect_try($cand,$key);
        if($res!==null) return $res;
    }
    return null;
}
function cs_list_keyboard(array $cs):array{
    $kb=[];
    foreach($cs as $sid=>$site){
        $icon=!empty($site['active'])?'✅':'❌';
        $nm=$site['name']??'?';
        $kb[]=[
            ['text'=>"🗑 $nm",'callback_data'=>"ns_del-$sid"],
            ['text'=>'💰','callback_data'=>"ns_bal-$sid"],
            ['text'=>'🔗','callback_data'=>"ns_chk-$sid"]
        ];
    }
    return $kb;
}
}
if (!isset($_co)) include("country.php"); // ⚡ فقط لو غير محمّل
#=========={النفايات}==========#
 
if($data == 'delPHP'){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>'✅ • تم تنضيف البوت من النفايات 🗑',
'show_alert'=>true
]);
unlink("PHPXX.json");
unlink("data/id/$id/step.txt");
}
#_________________تعديل معلوماتك في ملف JIMIPHP________________#
// ⚡ name.php محمّل من index.php
#=========={قفل وفتح الأقسام}==========#
$wrod = $openandlock['wrod_msg'] ?? null; // ⚡ من openandlock
if($wrod != null){
$wrods = "فتح العروض ✅";
$datawr = "open1";
}else{
$wrods = "قفل العروض ❌";
$datawr = "lock1";
}
if($openandlock['bot']['lock'] == "ok"){
$openbot = "فتح البوت ✅";
$lockbot = "bot-ok";
}else{
$openbot = "قفل البوت ❌";
$lockbot = "bot-no";
}
if($openandlock['eight']['lock'] == "ok"){
$buyGH = "فتح سيرفر واتساب ✅";
$dataGH = "eight-ok";
}else{
$buyGH = "قفل سيرفر واتساب ❌";
$dataGH = "eight-no";
}
if($openandlock['nine']['lock'] == "ok"){
$buyNE = "فتح سيرفر تيليجرام ✅";
$dataNE = "nine-ok";
}else{
$buyNE = "قفل سيرفر تيليجرام ❌";
$dataNE = "nine-no";
}
if($openandlock['Grace']['lock'] != "ok"){
$openGrace = "فتح السماح ✅";
$lockGrace = "Grace-no";
}else{
$openGrace = "قفل السماح ❌";
$lockGrace = "Grace-ok";
}
if($data == "opclo"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
عبر هذا الأزرار تستطيع التحكم بجميع الاقسام واقفالها وفتحها ♻️
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$openbot",'callback_data'=>"ameopclo-$lockbot"]],
[['text'=>"$wrods",'callback_data'=>"$datawr"],['text'=>"$openGrace",'callback_data'=>"ameopclo-$lockGrace"]],
[['text'=>"$buyGH",'callback_data'=>"ameopclo-$dataGH"],['text'=>"$buyNE",'callback_data'=>"ameopclo-$dataNE"]],
[['text'=>'رجوع','callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
}
if($data == "open1"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
تم بنجاح فتح قسم العروض للبوت ✅
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'رجوع','callback_data'=>'opclo']]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("data/txt/wrod.txt");
}
if($data == "lock1"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
الآن قم ب إرسال الرسالة التي تريدها أن تضهر عند الضغط على زر العروض ♻️
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'رجوع','callback_data'=>'opclo']]
]
])
]);
file_put_contents("data/id/$id/step.txt","lo");
}
if($text && $text != '/start' && $step == 'lo'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
تم بنجاح قفل قسم العروض ✅
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'رجوع','callback_data'=>'opclo']]
]
])
]);
$openandlock['wrod_msg'] = $text; Ands($openandlock); // ⚡ في openandlock
unlink("data/id/$id/step.txt");
}
if($exdata[0] == "ameopclo"){
$code = $exdata[1];
$quantit = $exdata[2];
if($quantit == 'ok'){
$qu = "no";
}else{
$qu = "ok";
}
if($code == "bot"){
if($qu == 'ok'){
$openbot = "فتح البوت ✅";
$lockbot = "bot-ok";
$bb = "قفل البوت ❌";
}else{
$openbot = "قفل البوت ❌";
$lockbot = "bot-no";
$bb = "فتح البوت ✅";
}
}
if($code == "eight"){
if($qu == 'ok'){
$buyGH = "فتح سيرفر واتساب ✅";
$dataGH = "eight-ok";
$bb = "قفل سيرفر واتساب ❌";
}else{
$buyGH = "قفل سيرفر واتساب ❌";
$dataGH = "eight-no";
$bb = "فتح سيرفر وتساب ✅";
}
}
if($code == "nine"){
if($qu == 'ok'){
$buyNE = "فتح سيرفر تيليجرام ✅";
$dataNE = "nine-ok";
$bb = "قفل سيرفر تيليجرام ❌";
}else{
$buyNE = "قفل سيرفر تيليجرام ❌";
$dataNE = "nine-no";
$bb = "فتح سيرفر تيليجرام ✅";
}
}
if($code == "Grace"){
if($qu != 'ok'){
$openGrace = "فتح السماح ✅";
$lockGrace = "Grace-no";
$bb = "قفل السماح ❌";
}else{
$openGrace = "قفل السماح ❌";
$lockGrace = "Grace-ok";
$bb = "فتح السماح ✅";
}
}
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"تم $bb",
'show_alert'=>folse
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
عبر هذا الأزرار تستطيع التحكم بجميع الاقسام واقفالها وفتحها ♻️
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$openbot",'callback_data'=>"ameopclo-$lockbot"]],
[['text'=>"$wrods",'callback_data'=>"$datawr"],['text'=>"$openGrace",'callback_data'=>"ameopclo-$lockGrace"]],
[['text'=>"$buyGH",'callback_data'=>"ameopclo-$dataGH"],['text'=>"$buyNE",'callback_data'=>"ameopclo-$dataNE"]],
[['text'=>'رجوع','callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
$openandlock[$code]['lock'] = $qu;
Ands($openandlock);
}
 
#=========={التطبيقات}==========#
// ⚡ 87 شرط تعمل للأدمن فقط
$_adminPfx = ['opclo','ameopclo','addnumber','delnumber','adminadd','Balancesms',
              'Balancesms2','Balancesms3','counapi','addapi','cop','delPHP','Balapi'];
$_isAdminReq = ($id==$sudo)
    || in_array($data,$_adminPfx)
    || (isset($exdata[0]) && in_array($exdata[0],['ameopclo','adminadd','rate','Balapi','ns']))
    || in_array($text,['نسبة','نسبه','رصيدي']);
if ($_isAdminReq) {
if($addblusdel['ot']['add'] == "ok"){
$addot = "- السيرفر العام ✅";
$ot = "- السيرفر العام";
$delot = "ot-ok";
}else{
$addot = "- السيرفر العام ❌";
$ot = "";
$delot = "ot-no";
}
if($addblusdel['wa']['add'] == "ok"){
$addwa = "- واتسأب ✅";
$wa = "- واتسأب";
$delwa = "wa-ok";
}else{
$addwa = "- واتسأب ❌";
$wa = "";
$delwa = "wa-no";
}
if($addblusdel['tg']['add'] == "ok"){
$addtg = "- تيليجرام ✅";
$tg = "- تيليجرام";
$deltg = "tg-ok";
}else{
$addtg = "- تيليجرام ❌";
$tg = "";
$deltg = "tg-no";
}
if($addblusdel['fb']['add'] == "ok"){
$addfb = "- فيسبوك ✅";
$fb = "- فيسبوك";
$delfb = "fb-ok";
}else{
$addfb = "- فيسبوك ❌";
$fb = "";
$delfb = "fb-no";
}
if($addblusdel['ig']['add'] == "ok"){
$addig = "- إنستقرام ✅";
$ig = "- إنستقرام";
$delig = "ig-ok";
}else{
$addig = "- إنستقرام ❌";
$ig = "";
$delig = "ig-no";
}
if($addblusdel['tw']['add'] == "ok"){
$addtw = "- تويتر ✅";
$tw = "- تويتر";
$deltw = "tw-ok";
}else{
$addtw = "- تويتر ❌";
$tw = "";
$deltw = "tw-no";
}
if($addblusdel['lf']['add'] == "ok"){
$addlf = "- تيكتوك ✅";
$lf = "- تيكتوك";
$dellf = "lf-ok";
}else{
$addlf = "- تيكتوك ❌";
$lf = "";
$dellf = "lf-no";
}
if($addblusdel['go']['add'] == "ok"){
$addgo = "- قوقل ✅";
$go = "- قوقل";
$delgo = "go-ok";
}else{
$addgo = "- قوقل ❌";
$go = "";
$delgo = "go-no";
}
if($addblusdel['im']['add'] == "ok"){
$addim = "- إيمو ✅";
$im = "- إيمو";
$delim = "im-ok";
}else{
$addim = "- إيمو ❌";
$im = "";
$delim = "im-no";
}
if($addblusdel['vi']['add'] == "ok"){
$addvi = "- فايبر ✅";
$vi = "- فايبر";
$delvi = "vi-ok";
}else{
$addvi = "- فايبر ❌";
$vi = "";
$delvi = "vi-no";
}
if($addblusdel['fu']['add'] == "ok"){
$addfu = "- سناب شات ✅";
$fu = "- سناب شات";
$delfu = "fu-ok";
}else{
$addfu = "- سناب شات ❌";
$fu = "";
$delfu = "fu-no";
}
if($addblusdel['nf']['add'] == "ok"){
$addnf = "- نيتفلكس ✅";
$nf = "- نيتفلكس";
$delnf = "nf-ok";
}else{
$addnf = "- نيتفلكس ❌";
$nf = "";
$delnf = "nf-no";
}
if($addblusdel['au']['add'] == "ok"){
$addau = "- حراج ✅";
$au = "- حراج";
$delau = "au-ok";
}else{
$addau = "- حراج ❌";
$au = "";
$delau = "au-no";
}
 
#=========={المواقع}==========#
if($addblusdel['5sim']['add'] == "ok"){
$add5sim = "5sim.biz 🌐 ✅";
$sim5 = "5sim.biz 🌐";
$del5sim = "5sim-ok";
$rate5sim = "5sim";
$plus1="➕";
$minus1="➖";
$rupl1=$addblusdel['5sim']['rupl'];
$rrupl1="{".$rupl1."}";
}else{
$add5sim = "5sim.biz 🌐 ❌";
$sim5 = "";
$del5sim = "5sim-no";
}
if($addblusdel['smslive']['add'] == "ok"){
$addsmslive = "smslive.pro 🌐 ✅";
$smslive = "smslive.pro 🌐";
$delsmslive = "smslive-ok";
$ratesmslive = "smslive";
$plus2="➕";
$minus2="➖";
$rupl2=$addblusdel['smslive']['rupl'];
$rrupl2="{".$rupl2."}";
}else{
$addsmslive = "smslive.pro 🌐 ❌";
$smslive = "";
$delsmslive = "smslive-no";
}
if($addblusdel['man']['add'] == "ok"){
$addman = "sms-man.ru 🌐 ✅";
$man = "sms-man.ru 🌐";
$delman = "man-ok";
$rateman = "man";
$plus3="➕";
$minus3="➖";
$rupl3=$addblusdel['man']['rupl'];
$rrupl3="{".$rupl3."}";
}else{
$addman = "sms-man.ru 🌐 ❌";
$man = "";
$delman = "man-no";
}
if($addblusdel['vak']['add'] == "ok"){
$addvak = "Vak-sms.com 🌐 ✅";
$vak = "Vak-sms.com 🌐";
$delvak = "vak-ok";
$ratevak = "vak";
$plus4="➕";
$minus4="➖";
$rupl4=$addblusdel['vak']['rupl'];
$rrupl4="{".$rupl4."}";
}else{
$addvak = "Vak-sms.com 🌐 ❌";
$vak = "";
$delvak = "vak-no";
}
if($addblusdel['acktiwator']['add'] == "ok"){
$addacktiwator = "sms-acktiwator.ru 🌐 ✅";
$acktiwator = "sms-acktiwator.ru 🌐";
$delacktiwator = "acktiwator-ok";
}else{
$addacktiwator = "sms-acktiwator.ru 🌐 ❌";
$acktiwator = "";
$delacktiwator = "acktiwator-no";
}
if($addblusdel['pvapins']['add'] == "ok"){
$addpvapins = "pvapins.com 🌐 ✅";
$pvapins = "pvapins.com 🌐";
$delpvapins = "pvapins-ok";
}else{
$addpvapins = "pvapins.com 🌐 ❌";
$pvapins = "";
$delpvapins = "pvapins-no";
}
if($addblusdel['botsms']['add'] == "ok"){
$addbotsms = "bot-sms.shop 🌐 ✅";
$botsms = "bot-sms.shop 🌐";
$delbotsms = "botsms-ok";
}else{
$addbotsms = "bot-sms.shop 🌐 ❌";
$botsms = "";
$delbotsms = "botsms-no";
}
if($addblusdel['onlinesim']['add'] == "ok"){
$addonlinesim = "onlinesim.io 🌐 ✅";
$onlinesim = "onlinesim.io 🌐";
$delonlinesim = "onlinesim-ok";
$rateonlinesim = "onlinesim";
$plus8="➕";
$minus8="➖";
$rupl8=$addblusdel['onlinesim']['rupl'];
$rrupl8="{".$rupl8."}";
}else{
$addonlinesim = "onlinesim.io 🌐 ❌";
$onlinesim = "";
$delonlinesim = "onlinesim-no";
}
if($addblusdel['supersmstech']['add'] == "ok"){
$addsupersmstech = "supersmstech.com 🌐 ✅";
$supersmstech = "supersmstech.com 🌐";
$delsupersmstech = "supersmstech-ok";
$ratesupersmstech = "supersmstech";
$plus9="➕";
$minus9="➖";
$rupl9=$addblusdel['supersmstech']['rupl'];
$rrupl9="{".$rupl9."}";
}else{
$addsupersmstech = "supersmstech.com 🌐 ❌";
$supersmstech = "";
$delsupersmstech = "supersmstech-no";
}
if($addblusdel['viotp']['add'] == "ok"){
$addviotp = "viotp.com 🌐 ✅";
$viotp = "viotp.com 🌐";
$delviotp = "viotp-ok";
}else{
$addviotp = "viotp.com 🌐 ❌";
$viotp = "";
$delviotp = "viotp-no";
}
if($addblusdel['simsms']['add'] == "ok"){
$addsimsms = "simsms.org 🌐 ✅";
$simsms = "simsms.org 🌐";
$delsimsms = "simsms-ok";
$ratesimsms = "simsms";
$plusb="➕";
$minusb="➖";
$ruplb=$addblusdel['simsms']['rupl'];
$rruplb="{".$ruplb."}";
}else{
$addsimsms = "simsms.org 🌐 ❌";
$simsms = "";
$delsimsms = "simsms-no";
}
if($addblusdel['grizzly']['add'] == "ok"){
$addgrizzly = "grizzlysms.com 🌐 ✅";
$grizzly = "grizzlysms.com 🌐";
$delgrizzly = "grizzly-ok";
$rategrizzly = "grizzly";
$plusc="➕";
$minusc="➖";
$ruplc=$addblusdel['grizzly']['rupl'];
$rruplc="{".$ruplc."}";
}else{
$addgrizzly = "grizzlysms.com 🌐 ❌";
$grizzly = "";
$delgrizzly = "grizzly-no";
}
if($addblusdel['smscode']['add'] == "ok"){
$addsmscode = "sms-code.ru 🌐 ✅";
$smscode = "sms-code.ru 🌐";
$delsmscode = "smscode-ok";
$ratesmscode = "smscode";
$plusd="➕";
$minusd="➖";
$rupld=$addblusdel['smscode']['rupl'];
$rrupld="{".$rupld."}";
}else{
$addsmscode = "sms-code.ru 🌐 ❌";
$smscode = "";
$delsmscode = "smscode-no";
}
if($addblusdel['tiger']['add'] == "ok"){
$addtiger = "tiger-sms.com 🌐 ✅";
$tiger = "tiger-sms.com 🌐";
$deltiger = "tiger-ok";
$ratetiger = "tiger";
$pluse="➕";
$minuse="➖";
$ruple=$addblusdel['tiger']['rupl'];
$rruple="{".$ruple."}";
}else{
$addtiger = "tiger-sms.com 🌐 ❌";
$tiger = "";
$deltiger = "tiger-no";
}
if($addblusdel['2ndline']['add'] == "ok"){
$addnd2line = "2ndline.io 🌐 ✅";
$nd2line = "2ndline.io 🌐";
$delnd2line = "2ndline-ok";
}else{
$addnd2line = "2ndline.io 🌐 ❌";
$nd2line = "";
$delnd2line = "2ndline-no";
}
if($addblusdel['store']['add'] == "ok"){
$addstore = "receivesms.store 🌐 ✅";
$store = "receivesms.store 🌐";
$delstore = "store-ok";
}else{
$addstore = "receivesms.store 🌐 ❌";
$store = "";
$delstore = "store-no";
}
if($addblusdel['fastpva']['add'] == "ok"){
$addfastpva = "sms.fastpva.com 🌐 ✅";
$fastpva = "sms.fastpva.com 🌐";
$delfastpva = "fastpva-ok";
}else{
$addfastpva = "sms.fastpva.com 🌐 ❌";
$fastpva = "";
$delfastpva = "fastpva-no";
}
if($addblusdel['dropsms']['add'] == "ok"){
$adddropsms = "dropsms.ru 🌐 ✅";
$dropsms = "dropsms.ru 🌐";
$deldropsms = "dropsms-ok";
$ratedropsms = "dropsms";
$plusj="➕";
$minusj="➖";
$ruplj=$addblusdel['dropsms']['rupl'];
$rruplj="{".$ruplj."}";
}else{
$adddropsms = "dropsms.ru 🌐 ❌";
$dropsms = "";
$deldropsms = "dropsms-no";
}
if($addblusdel['24sms7']['add'] == "ok"){
$add24sms7 = "24sms7.com 🌐 ✅";
$sms7 = "24sms7.com 🌐";
$del24sms7 = "24sms7-ok";
$rate24sms7 = "24sms7";
$plusk="➕";
$minusk="➖";
$ruplk=$addblusdel['24sms7']['rupl'];
$rruplk="{".$ruplk."}";
}else{
$add24sms7 = "24sms7.com 🌐 ❌";
$sms7 = "";
$del24sms7 = "24sms7-no";
}
if($addblusdel['sellotp']['add'] == "ok"){
$addsellotp = "sellotp.com 🌐 ✅";
$sellotp = "sellotp.com 🌐";
$delsellotp = "sellotp-ok";
}else{
$addsellotp = "sellotp.com 🌐 ❌";
$sellotp = "";
$delsellotp = "sellotp-no";
}
if($addblusdel['duraincloud']['add'] == "ok"){
$addduraincloud = "mm.duraincloud.com 🌐 ✅";
$duraincloud = "mm.duraincloud.com 🌐";
$delduraincloud = "duraincloud-ok";
}else{
$addduraincloud = "mm.duraincloud.com 🌐 ❌";
$duraincloud = "";
$delduraincloud = "duraincloud-no";
}
#=========={النسبة}==========#
 
if($addblusdel['System'] == "direct"){
if($text == 'نسبة' or $text == 'نسبه' or $data == "نسبة"){
$rupl=1;
if($text==null){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - حدد نسبة الربح للمواقع التي لديك
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🔄 تحديث 🔄",'callback_data'=>'نسبة']],
[['text'=>"$plus1",'callback_data'=>"rate-$rupl-plus-5sim"],['text'=>"$rate5sim$rrupl1",'callback_data'=>"..."],['text'=>"$minus1",'callback_data'=>"rate-$rupl-minus-5sim"]],
[['text'=>"$plus2",'callback_data'=>"rate-$rupl-plus-smslive"],['text'=>"$ratesmslive$rrupl2",'callback_data'=>"..."],['text'=>"$minus2",'callback_data'=>"rate-$rupl-minus-smslive"]],
[['text'=>"$plus3",'callback_data'=>"rate-$rupl-plus-man"],['text'=>"$rateman$rrupl3",'callback_data'=>"..."],['text'=>"$minus3",'callback_data'=>"rate-$rupl-minus-man"]],
[['text'=>"$plus4",'callback_data'=>"rate-$rupl-plus-vak"],['text'=>"$ratevak$rrupl4",'callback_data'=>"..."],['text'=>"$minus4",'callback_data'=>"rate-$rupl-minus-vak"]],
[['text'=>"$plus8",'callback_data'=>"rate-$rupl-plus-onlinesim"],['text'=>"$rateonlinesim$rrupl8",'callback_data'=>"..."],['text'=>"$minus8",'callback_data'=>"rate-$rupl-minus-onlinesim"]],
[['text'=>"$plus9",'callback_data'=>"rate-$rupl-plus-supersmstech"],['text'=>"$ratesupersmstech$rrupl9",'callback_data'=>"..."],['text'=>"$minus9",'callback_data'=>"rate-$rupl-minus-supersmstech"]],
[['text'=>"$plusb",'callback_data'=>"rate-$rupl-plus-simsms"],['text'=>"$ratesimsms$rruplb",'callback_data'=>"..."],['text'=>"$minusb",'callback_data'=>"rate-$rupl-minus-simsms"]],
[['text'=>"$plusc",'callback_data'=>"rate-$rupl-plus-grizzly"],['text'=>"$rategrizzly$rruplc",'callback_data'=>"..."],['text'=>"$minusc",'callback_data'=>"rate-$rupl-minus-grizzly"]],
[['text'=>"$plusd",'callback_data'=>"rate-$rupl-plus-smscode"],['text'=>"$ratesmscode$rrupld",'callback_data'=>"..."],['text'=>"$minusd",'callback_data'=>"rate-$rupl-minus-smscode"]],
[['text'=>"$pluse",'callback_data'=>"rate-$rupl-plus-tiger"],['text'=>"$ratetiger$rruple",'callback_data'=>"..."],['text'=>"$minuse",'callback_data'=>"rate-$rupl-minus-tiger"]],
[['text'=>"$plusj",'callback_data'=>"rate-$rupl-plus-dropsms"],['text'=>"$ratedropsms$rruplj",'callback_data'=>"..."],['text'=>"$minusj",'callback_data'=>"rate-$rupl-minus-dropsms"]],
[['text'=>"$plusk",'callback_data'=>"rate-$rupl-plus-24sms7"],['text'=>"$rate24sms7$rruplk",'callback_data'=>"..."],['text'=>"$minusk",'callback_data'=>"rate-$rupl-minus-24sms7"]],
[['text'=>"رجوع",'callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
exit;
}
if($text!=null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ - حدد نسبة الربح للمواقع التي لديك
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🔄 تحديث 🔄",'callback_data'=>'نسبة']],
[['text'=>"$plus1",'callback_data'=>"rate-$rupl-plus-5sim"],['text'=>"$rate5sim$rrupl1",'callback_data'=>"..."],['text'=>"$minus1",'callback_data'=>"rate-$rupl-minus-5sim"]],
[['text'=>"$plus2",'callback_data'=>"rate-$rupl-plus-smslive"],['text'=>"$ratesmslive$rrupl2",'callback_data'=>"..."],['text'=>"$minus2",'callback_data'=>"rate-$rupl-minus-smslive"]],
[['text'=>"$plus3",'callback_data'=>"rate-$rupl-plus-man"],['text'=>"$rateman$rrupl3",'callback_data'=>"..."],['text'=>"$minus3",'callback_data'=>"rate-$rupl-minus-man"]],
[['text'=>"$plus4",'callback_data'=>"rate-$rupl-plus-vak"],['text'=>"$ratevak$rrupl4",'callback_data'=>"..."],['text'=>"$minus4",'callback_data'=>"rate-$rupl-minus-vak"]],
[['text'=>"$plus8",'callback_data'=>"rate-$rupl-plus-onlinesim"],['text'=>"$rateonlinesim$rrupl8",'callback_data'=>"..."],['text'=>"$minus8",'callback_data'=>"rate-$rupl-minus-onlinesim"]],
[['text'=>"$plus9",'callback_data'=>"rate-$rupl-plus-supersmstech"],['text'=>"$ratesupersmstech$rrupl9",'callback_data'=>"..."],['text'=>"$minus9",'callback_data'=>"rate-$rupl-minus-supersmstech"]],
[['text'=>"$plusb",'callback_data'=>"rate-$rupl-plus-simsms"],['text'=>"$ratesimsms$rruplb",'callback_data'=>"..."],['text'=>"$minusb",'callback_data'=>"rate-$rupl-minus-simsms"]],
[['text'=>"$plusc",'callback_data'=>"rate-$rupl-plus-grizzly"],['text'=>"$rategrizzly$rruplc",'callback_data'=>"..."],['text'=>"$minusc",'callback_data'=>"rate-$rupl-minus-grizzly"]],
[['text'=>"$plusd",'callback_data'=>"rate-$rupl-plus-smscode"],['text'=>"$ratesmscode$rrupld",'callback_data'=>"..."],['text'=>"$minusd",'callback_data'=>"rate-$rupl-minus-smscode"]],
[['text'=>"$pluse",'callback_data'=>"rate-$rupl-plus-tiger"],['text'=>"$ratetiger$rruple",'callback_data'=>"..."],['text'=>"$minuse",'callback_data'=>"rate-$rupl-minus-tiger"]],
[['text'=>"$plusj",'callback_data'=>"rate-$rupl-plus-dropsms"],['text'=>"$ratedropsms$rruplj",'callback_data'=>"..."],['text'=>"$minusj",'callback_data'=>"rate-$rupl-minus-dropsms"]],
[['text'=>"$plusk",'callback_data'=>"rate-$rupl-plus-24sms7"],['text'=>"$rate24sms7$rruplk",'callback_data'=>"..."],['text'=>"$minusk",'callback_data'=>"rate-$rupl-minus-24sms7"]],
[['text'=>"رجوع",'callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
exit;
}
}
if($exdata[0] == "rate"){
$rupl=$exdata[1];
$wer=$exdata[2];
$code=$exdata[3];
$pri=$addblusdel[$code]['rupl'];
if($wer == "plus"){
$pric=$pri+$rupl;
if($code == "5sim"){
$rupl1=$rupl1+$rupl;
$rrupl1="{".$rupl1."}";
}
if($code == "smslive"){
$rupl2=$rupl2+$rupl;
$rrupl2="{".$rupl2."}";
}
if($code == "man"){
$rupl3=$rupl3+$rupl;
$rrupl3="{".$rupl3."}";
}
if($code == "vak"){
$rupl4=$rupl4+$rupl;
$rrupl4="{".$rupl4."}";
}
if($code == "onlinesim"){
$rupl8=$rupl8+$rupl;
$rrupl8="{".$rupl8."}";
}
if($code == "supersmstech"){
$rupl9=$rupl9+$rupl;
$rrupl9="{".$rupl9."}";
}
if($code == "simsms"){
$ruplb=$ruplb+$rupl;
$rruplb="{".$ruplb."}";
}
if($code == "grizzly"){
$ruplc=$ruplc+$rupl;
$rruplc="{".$ruplc."}";
}
if($code == "smscode"){
$rupld=$rupld+$rupl;
$rrupld="{".$rupld."}";
}
if($code == "tiger"){
$ruple=$ruple+$rupl;
$rruple="{".$ruple."}";
}
if($code == "dropsms"){
$ruplj=$ruplj+$rupl;
$rruplj="{".$ruplj."}";
}
if($code == "24sms7"){
$ruplk=$ruplk+$rupl;
$rruplk="{".$ruplk."}";
}
}
if($wer == "minus"){
$pric=$pri-$rupl;
if($code == "5sim"){
$rupl1=$rupl1-$rupl;
$rrupl1="{".$rupl1."}";
}
if($code == "smslive"){
$rupl2=$rupl2-$rupl;
$rrupl2="{".$rupl2."}";
}
if($code == "man"){
$rupl3=$rupl3-$rupl;
$rrupl3="{".$rupl3."}";
}
if($code == "vak"){
$rupl4=$rupl4-$rupl;
$rrupl4="{".$rupl4."}";
}
if($code == "onlinesim"){
$rupl8=$rupl8-$rupl;
$rrupl8="{".$rupl8."}";
}
if($code == "supersmstech"){
$rupl9=$rupl9-$rupl;
$rrupl9="{".$rupl9."}";
}
if($code == "simsms"){
$ruplb=$ruplb-$rupl;
$rruplb="{".$ruplb."}";
}
if($code == "grizzly"){
$ruplc=$ruplc-$rupl;
$rruplc="{".$ruplc."}";
}
if($code == "smscode"){
$rupld=$rupld-$rupl;
$rrupld="{".$rupld."}";
}
if($code == "tiger"){
$ruple=$ruple-$rupl;
$rruple="{".$ruple."}";
}
if($code == "dropsms"){
$ruplj=$ruplj-$rupl;
$rruplj="{".$ruplj."}";
}
if($code == "24sms7"){
$ruplk=$ruplk-$rupl;
$rruplk="{".$ruplk."}";
}
}
if($pric < 0){
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - حدد نسبة الربح للمواقع التي لديك
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🔄 تحديث 🔄",'callback_data'=>'نسبة']],
[['text'=>"$plus1",'callback_data'=>"rate-$rupl-plus-5sim"],['text'=>"$rate5sim$rrupl1",'callback_data'=>"..."],['text'=>"$minus1",'callback_data'=>"rate-$rupl-minus-5sim"]],
[['text'=>"$plus2",'callback_data'=>"rate-$rupl-plus-smslive"],['text'=>"$ratesmslive$rrupl2",'callback_data'=>"..."],['text'=>"$minus2",'callback_data'=>"rate-$rupl-minus-smslive"]],
[['text'=>"$plus3",'callback_data'=>"rate-$rupl-plus-man"],['text'=>"$rateman$rrupl3",'callback_data'=>"..."],['text'=>"$minus3",'callback_data'=>"rate-$rupl-minus-man"]],
[['text'=>"$plus4",'callback_data'=>"rate-$rupl-plus-vak"],['text'=>"$ratevak$rrupl4",'callback_data'=>"..."],['text'=>"$minus4",'callback_data'=>"rate-$rupl-minus-vak"]],
[['text'=>"$plus8",'callback_data'=>"rate-$rupl-plus-onlinesim"],['text'=>"$rateonlinesim$rrupl8",'callback_data'=>"..."],['text'=>"$minus8",'callback_data'=>"rate-$rupl-minus-onlinesim"]],
[['text'=>"$plus9",'callback_data'=>"rate-$rupl-plus-supersmstech"],['text'=>"$ratesupersmstech$rrupl9",'callback_data'=>"..."],['text'=>"$minus9",'callback_data'=>"rate-$rupl-minus-supersmstech"]],
[['text'=>"$plusb",'callback_data'=>"rate-$rupl-plus-simsms"],['text'=>"$ratesimsms$rruplb",'callback_data'=>"..."],['text'=>"$minusb",'callback_data'=>"rate-$rupl-minus-simsms"]],
[['text'=>"$plusc",'callback_data'=>"rate-$rupl-plus-grizzly"],['text'=>"$rategrizzly$rruplc",'callback_data'=>"..."],['text'=>"$minusc",'callback_data'=>"rate-$rupl-minus-grizzly"]],
[['text'=>"$plusd",'callback_data'=>"rate-$rupl-plus-smscode"],['text'=>"$ratesmscode$rrupld",'callback_data'=>"..."],['text'=>"$minusd",'callback_data'=>"rate-$rupl-minus-smscode"]],
[['text'=>"$pluse",'callback_data'=>"rate-$rupl-plus-tiger"],['text'=>"$ratetiger$rruple",'callback_data'=>"..."],['text'=>"$minuse",'callback_data'=>"rate-$rupl-minus-tiger"]],
[['text'=>"$plusj",'callback_data'=>"rate-$rupl-plus-dropsms"],['text'=>"$ratedropsms$rruplj",'callback_data'=>"..."],['text'=>"$minusj",'callback_data'=>"rate-$rupl-minus-dropsms"]],
[['text'=>"$plusk",'callback_data'=>"rate-$rupl-plus-24sms7"],['text'=>"$rate24sms7$rruplk",'callback_data'=>"..."],['text'=>"$minusk",'callback_data'=>"rate-$rupl-minus-24sms7"]],
[['text'=>"رجوع",'callback_data'=>'c']]
]
])
]);
if($wer == "plus"){
$addblusdel[$code]['rupl'] += 1;
Bnds($addblusdel);
}
if($wer == "minus"){
$addblusdel[$code]['rupl'] -= 1;
Bnds($addblusdel);
}
unlink("data/id/$id/step.txt");
unlink("zzz.json");
exit;
}
}
} // ⚡ نهاية كتلة $_isAdminReq

#=========={إضافة الدول}==========#
if($data == "addnumber"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- قم ب إختار الموقع الذي تود إضافة الدولة إلى البوت 🎗
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$sim5",'callback_data'=>"Bj-Ai"]],
[['text'=>"$smslive",'callback_data'=>"Bj-Bi"],['text'=>"$man",'callback_data'=>"Bj-Ci"]],
[['text'=>"$vak",'callback_data'=>"Bj-Di"]],
[['text'=>"$acktiwator",'callback_data'=>"Bj-Ei"],['text'=>"$pvapins",'callback_data'=>"Bj-Fi"]],
[['text'=>"$botsms",'callback_data'=>"Bj-Gi"]],
[['text'=>"$onlinesim",'callback_data'=>"Bj-Hi"],['text'=>"$supersmstech",'callback_data'=>"Bj-Ji"]],
[['text'=>"$viotp",'callback_data'=>"Bj-Ki"]],
[['text'=>"$simsms",'callback_data'=>"Bj-Li"],['text'=>"$grizzly",'callback_data'=>"Bj-Mi"]],
[['text'=>"$smscode",'callback_data'=>"Bj-Ni"]],
[['text'=>"$tiger",'callback_data'=>"Bj-Oi"],['text'=>"$nd2line",'callback_data'=>"Bj-Pi"]],
[['text'=>"$store",'callback_data'=>"Bj-Qi"]],
[['text'=>"$fastpva",'callback_data'=>"Bj-Ri"],['text'=>"$dropsms",'callback_data'=>"Bj-Si"]],
[['text'=>"$sms7",'callback_data'=>"Bj-Ti"]],
[['text'=>"$sellotp",'callback_data'=>"Bj-Ui"],['text'=>"$duraincloud",'callback_data'=>"Bj-Vi"]],
[['text'=>"رجوع",'callback_data'=>'c']]
]
])
]);
// ─── إضافة المواقع المخصصة ───
$_cs_all = cs_load();
if(!empty($_cs_all)){
    foreach($_cs_all as $_cs_id => $_cs_site){
        if(empty($_cs_site['active'])) continue;
        $_cs_nm = $_cs_site['name'] ?? $_cs_id;
        bot('EditMessageText',[
            'chat_id'=>$chat_id,'message_id'=>$message_id,
            'text'=>"
- قم ب إختار الموقع الذي تود إضافة الدولة إلى البوت 🎗
",'parse_mode'=>'Markdown',
            'reply_markup'=>json_encode(['inline_keyboard'=>array_merge(
                $adm_cs_keyboard ?? [],
                [[['text'=>"🆕 $_cs_nm",'callback_data'=>"Bjcs-$_cs_id"]]],
                [[['text'=>"رجوع",'callback_data'=>'c']]]
            )])
        ]);
        break; // reset each time - rebuild properly below
    }
    // Rebuild properly
    $_kb_sites = [
        [['text'=>"$sim5",'callback_data'=>"Bj-Ai"]],
        [['text'=>"$smslive",'callback_data'=>"Bj-Bi"],['text'=>"$man",'callback_data'=>"Bj-Ci"]],
        [['text'=>"$vak",'callback_data'=>"Bj-Di"]],
        [['text'=>"$acktiwator",'callback_data'=>"Bj-Ei"],['text'=>"$pvapins",'callback_data'=>"Bj-Fi"]],
        [['text'=>"$botsms",'callback_data'=>"Bj-Gi"]],
        [['text'=>"$onlinesim",'callback_data'=>"Bj-Hi"],['text'=>"$supersmstech",'callback_data'=>"Bj-Ji"]],
        [['text'=>"$viotp",'callback_data'=>"Bj-Ki"]],
        [['text'=>"$simsms",'callback_data'=>"Bj-Li"],['text'=>"$grizzly",'callback_data'=>"Bj-Mi"]],
        [['text'=>"$smscode",'callback_data'=>"Bj-Ni"]],
        [['text'=>"$tiger",'callback_data'=>"Bj-Oi"],['text'=>"$nd2line",'callback_data'=>"Bj-Pi"]],
        [['text'=>"$store",'callback_data'=>"Bj-Qi"]],
        [['text'=>"$fastpva",'callback_data'=>"Bj-Ri"],['text'=>"$dropsms",'callback_data'=>"Bj-Si"]],
        [['text'=>"$sms7",'callback_data'=>"Bj-Ti"]],
        [['text'=>"$sellotp",'callback_data'=>"Bj-Ui"],['text'=>"$duraincloud",'callback_data'=>"Bj-Vi"]],
    ];
    foreach($_cs_all as $_cs_id => $_cs_site){
        if(empty($_cs_site['active'])) continue;
        $_cs_nm = $_cs_site['name'] ?? $_cs_id;
        $_kb_sites[] = [['text'=>"🆕 $_cs_nm",'callback_data'=>"Bjcs-$_cs_id"]];
    }
    $_kb_sites[] = [['text'=>"رجوع",'callback_data'=>'c']];
    bot('EditMessageText',[
        'chat_id'=>$chat_id,'message_id'=>$message_id,
        'text'=>"
- قم ب إختار الموقع الذي تود إضافة الدولة إلى البوت 🎗
",'parse_mode'=>'Markdown',
        'reply_markup'=>json_encode(['inline_keyboard'=>$_kb_sites])
    ]);
}
unlink("data/id/$id/step.txt");
unlink("zzz.json");
}
#=========={المواقع}==========#
if($text == "المواقع"){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
- قم ب إختار الموقع الذي تود إضافة الدولة إلى البوت 🎗
",
'parse_mode'=>'Markdown',
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$add5sim",'callback_data'=>"adminadd-$del5sim"]],
[['text'=>"$addsmslive",'callback_data'=>"adminadd-$delsmslive"],['text'=>"$addman",'callback_data'=>"adminadd-$delman"]],
[['text'=>"$addvak",'callback_data'=>"adminadd-$delvak"]],
[['text'=>"$addacktiwator",'callback_data'=>"adminadd-$delacktiwator"],['text'=>"$addpvapins",'callback_data'=>"adminadd-$delpvapins"]],
[['text'=>"$addbotsms",'callback_data'=>"adminadd-$delbotsms"]],
[['text'=>"$addonlinesim",'callback_data'=>"adminadd-$delonlinesim"],['text'=>"$addsupersmstech",'callback_data'=>"adminadd-$delsupersmstech"]],
[['text'=>"$addviotp",'callback_data'=>"adminadd-$delviotp"]],
[['text'=>"$addsimsms",'callback_data'=>"adminadd-$delsimsms"],['text'=>"$addgrizzly",'callback_data'=>"adminadd-$delgrizzly"]],
[['text'=>"$addsmscode",'callback_data'=>"adminadd-$delsmscode"]],
[['text'=>"$addtiger",'callback_data'=>"adminadd-$deltiger"],['text'=>"$addnd2line",'callback_data'=>"adminadd-$delnd2line"]],
[['text'=>"$addstore",'callback_data'=>"adminadd-$delstore"]],
[['text'=>"$addfastpva",'callback_data'=>"adminadd-$delfastpva"],['text'=>"$adddropsms",'callback_data'=>"adminadd-$deldropsms"]],
[['text'=>"$add24sms7",'callback_data'=>"adminadd-$del24sms7"]],
[['text'=>"$addsellotp",'callback_data'=>"adminadd-$delsellotp"],['text'=>"$addduraincloud",'callback_data'=>"adminadd-$delduraincloud"]],
[['text'=>"رجوع",'callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
}
#=========={التطبيقات}==========#
 
if($text == "التطبيقات"){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
- قم ب إختار التطبيق الذي تود إضافة الدولة إلى البوت 🎗
",
'parse_mode'=>'Markdown',
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$addot",'callback_data'=>"adminappadd-$delot"]],
[['text'=>"$addtg",'callback_data'=>"adminappadd-$deltg"],['text'=>"$addwa",'callback_data'=>"adminappadd-$delwa"]],
[['text'=>"$addig",'callback_data'=>"adminappadd-$delig"],['text'=>"$addfb",'callback_data'=>"adminappadd-$delfb"]],
[['text'=>"$addlf",'callback_data'=>"adminappadd-$dellf"],['text'=>"$addtw",'callback_data'=>"adminappadd-$deltw"]],
[['text'=>"$addim",'callback_data'=>"adminappadd-$delim"],['text'=>"$addgo",'callback_data'=>"adminappadd-$delgo"]],
[['text'=>"$addfu",'callback_data'=>"adminappadd-$delfu"],['text'=>"$addvi",'callback_data'=>"adminappadd-$delvi"]],
[['text'=>"$addau",'callback_data'=>"adminappadd-$delau"],['text'=>"$addnf",'callback_data'=>"adminappadd-$delnf"]],
[['text'=>"رجوع",'callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
}
#=========={النظام}==========#
 
if($text == "النظام"){
if($addblusdel['System'] == "direct"){
$System_direct = "- نظام تلقائي ✅";
$System_not_directly = "- نظام يدوي ❌";
}elseif($addblusdel['System'] == "not_directly"){
$System_direct = "- نظام تلقائي ❌";
$System_not_directly = "- نظام يدوي ✅";
}else{
$System_direct = "- نظام تلقائي ❌";
$System_not_directly = "- نظام يدوي ❌";
}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
- هل تريد جعل نظام البوت تلقائي او يدوي ☑️
",
'parse_mode'=>'Markdown',
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$System_direct",'callback_data'=>"adminSystem-direct"]],
[['text'=>"$System_not_directly",'callback_data'=>"adminSystem-not_directly"]],
[['text'=>"رجوع",'callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
}
if($exdata[0] == "adminSystem"){
$code = $exdata[1];
if($code == "direct"){
$System_direct = "- نظام تلقائي ✅";
$System_not_directly = "- نظام يدوي ❌";
$bb="التلقائي";
}elseif($code == "not_directly"){
$System_direct = "- نظام تلقائي ❌";
$System_not_directly = "- نظام يدوي ✅";
$bb="اليدوي";
}
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"تم تغيير النظام إلى $bb ✅",
'show_alert'=>folse
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- هل تريد جعل نظام البوت تلقائي او يدوي ☑️
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$System_direct",'callback_data'=>"adminSystem-direct"]],
[['text'=>"$System_not_directly",'callback_data'=>"adminSystem-not_directly"]],
[['text'=>"رجوع",'callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
$addblusdel['System'] = $code;
Bnds($addblusdel);
}
 
#=========={المواقع}==========#
if($exdata[0] == "adminadd"){
$code = $exdata[1];
$quantit = $exdata[2];
if($quantit == 'ok'){
$qu = "no";
}else{
$qu = "ok";
}
if($code == "5sim"){
if($qu == 'ok'){
$add5sim = "5sim.biz 🌐 ✅";
$del5sim = "5sim-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$add5sim = "5sim.biz 🌐 ❌";
$del5sim = "5sim-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "smslive"){
if($qu == 'ok'){
$addsmslive = "smslive.pro 🌐 ✅";
$delsmslive = "smslive-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addsmslive = "smslive.pro 🌐 ❌";
$delsmslive = "smslive-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "man"){
if($qu == 'ok'){
$addman = "sms-man.ru 🌐 ✅";
$delman = "man-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addman = "sms-man.ru 🌐 ❌";
$delman = "man-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "vak"){
if($qu == 'ok'){
$addvak = "Vak-sms.com 🌐 ✅";
$delvak = "vak-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addvak = "Vak-sms.com 🌐 ❌";
$delvak = "vak-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "acktiwator"){
if($qu == 'ok'){
$addacktiwator = "sms-acktiwator.ru 🌐 ✅";
$delacktiwator = "acktiwator-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addacktiwator = "sms-acktiwator.ru 🌐 ❌";
$delacktiwator = "acktiwator-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "pvapins"){
if($qu == 'ok'){
$addpvapins = "pvapins.com 🌐 ✅";
$delpvapins = "pvapins-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addpvapins = "pvapins.com 🌐 ❌";
$delpvapins = "pvapins-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "botsms"){
if($qu == 'ok'){
$addbotsms = "bot-sms.shop 🌐 ✅";
$delbotsms = "botsms-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addbotsms = "bot-sms.shop  ❌";
$delbotsms = "botsms-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "onlinesim"){
if($qu == 'ok'){
$addonlinesim = "onlinesim.io 🌐 ✅";
$delonlinesim = "onlinesim-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addonlinesim = "onlinesim.io 🌐 ❌";
$delonlinesim = "onlinesim-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "supersmstech"){
if($qu == 'ok'){
$addsupersmstech = "supersmstech.com 🌐 ✅";
$delsupersmstech = "supersmstech-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addsupersmstech = "supersmstech.com 🌐 ❌";
$delsupersmstech = "supersmstech-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "viotp"){
if($qu == 'ok'){
$addviotp = "viotp.com 🌐 ✅";
$delviotp = "viotp-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addviotp = "viotp.com 🌐 ❌";
$delviotp = "viotp-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "simsms"){
if($qu == 'ok'){
$addsimsms = "simsms.org 🌐 ✅";
$delsimsms = "simsms-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addsimsms = "simsms.org 🌐 ❌";
$delsimsms = "simsms-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "grizzly"){
if($qu == 'ok'){
$addgrizzly = "grizzlysms.com 🌐 ✅";
$delgrizzly = "grizzly-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addgrizzly = "grizzlysms.com 🌐 ❌";
$delgrizzly = "grizzly-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "smscode"){
if($qu == 'ok'){
$addsmscode = "sms-code.ru 🌐 ✅";
$delsmscode = "smscode-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addsmscode = "sms-code.ru 🌐 ❌";
$delsmscode = "smscode-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "tiger"){
if($qu == 'ok'){
$addtiger = "tiger-sms.com 🌐 ✅";
$deltiger = "tiger-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addtiger = "tiger-sms.com 🌐 ❌";
$deltiger = "tiger-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "2ndline"){
if($qu == 'ok'){
$addnd2line = "2ndline.io 🌐 ✅";
$delnd2line = "2ndline-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addnd2line = "2ndline.io 🌐 ❌";
$delnd2line = "2ndline-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "store"){
if($qu == 'ok'){
$addstore = "receivesms.store 🌐 ✅";
$delstore = "store-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addstore = "receivesms.store 🌐 ❌";
$delstore = "store-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "fastpva"){
if($qu == 'ok'){
$addfastpva = "sms.fastpva.com 🌐 ✅";
$delfastpva = "fastpva-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addfastpva = "sms.fastpva.com 🌐 ❌";
$delfastpva = "fastpva-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "dropsms"){
if($qu == 'ok'){
$adddropsms = "dropsms.ru 🌐 ✅";
$deldropsms = "dropsms-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$adddropsms = "dropsms.ru 🌐 ❌";
$deldropsms = "dropsms-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "24sms7"){
if($qu == 'ok'){
$add24sms7 = "24sms7.com 🌐 ✅";
$del24sms7 = "24sms7-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$add24sms7 = "24sms7.com 🌐 ❌";
$del24sms7 = "24sms7-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "sellotp"){
if($qu == 'ok'){
$addsellotp = "sellotp.com 🌐 ✅";
$delsellotp = "sellotp-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addsellotp = "sellotp.com 🌐 ❌";
$delsellotp = "sellotp-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
if($code == "duraincloud"){
if($qu == 'ok'){
$addduraincloud = "mm.duraincloud.com 🌐 ✅";
$delduraincloud = "duraincloud-ok";
$bb="تم إضافة المورد بنجاح ✅";
}else{
$addduraincloud = "mm.duraincloud.com 🌐 ❌";
$delduraincloud = "duraincloud-no";
$bb="تم حذف المورد بنجاح ✅";
}
}
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"$bb",
'show_alert'=>folse
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- قم ب إختار الموقع الذي تود إضافة الدولة إلى البوت 🎗
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$add5sim",'callback_data'=>"adminadd-$del5sim"]],
[['text'=>"$addsmslive",'callback_data'=>"adminadd-$delsmslive"],['text'=>"$addman",'callback_data'=>"adminadd-$delman"]],
[['text'=>"$addvak",'callback_data'=>"adminadd-$delvak"]],
[['text'=>"$addacktiwator",'callback_data'=>"adminadd-$delacktiwator"],['text'=>"$addpvapins",'callback_data'=>"adminadd-$delpvapins"]],
[['text'=>"$addbotsms",'callback_data'=>"adminadd-$delbotsms"]],
[['text'=>"$addonlinesim",'callback_data'=>"adminadd-$delonlinesim"],['text'=>"$addsupersmstech",'callback_data'=>"adminadd-$delsupersmstech"]],
[['text'=>"$addviotp",'callback_data'=>"adminadd-$delviotp"]],
[['text'=>"$addsimsms",'callback_data'=>"adminadd-$delsimsms"],['text'=>"$addgrizzly",'callback_data'=>"adminadd-$delgrizzly"]],
[['text'=>"$addsmscode",'callback_data'=>"adminadd-$delsmscode"]],
[['text'=>"$addtiger",'callback_data'=>"adminadd-$deltiger"],['text'=>"$addnd2line",'callback_data'=>"adminadd-$delnd2line"]],
[['text'=>"$addstore",'callback_data'=>"adminadd-$delstore"]],
[['text'=>"$addfastpva",'callback_data'=>"adminadd-$delfastpva"],['text'=>"$adddropsms",'callback_data'=>"adminadd-$deldropsms"]],
[['text'=>"$add24sms7",'callback_data'=>"adminadd-$del24sms7"]],
[['text'=>"$addsellotp",'callback_data'=>"adminadd-$delsellotp"],['text'=>"$addduraincloud",'callback_data'=>"adminadd-$delduraincloud"]],
[['text'=>'رجوع','callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
$addblusdel[$code]['add'] = $qu;
Bnds($addblusdel);
}
 
#=========={التطبيقات}==========#
if($exdata[0] == "adminappadd"){
$code = $exdata[1];
$quantit = $exdata[2];
if($quantit == 'ok'){
$qu = "no";
}else{
$qu = "ok";
}
if($code == "ot"){
if($qu == 'ok'){
$addot = "- السيرفر العام ✅";
$delot = "ot-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addot = "- السيرفر العام ❌";
$delot = "ot-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "wa"){
if($qu == 'ok'){
$addwa = "- واتسأب ✅";
$delwa = "wa-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addwa = "- واتسأب ❌";
$delwa = "wa-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "tg"){
if($qu == 'ok'){
$addtg = "- تيليجرام ✅";
$deltg = "tg-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addtg = "- تيليجرام ❌";
$deltg = "tg-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "fb"){
if($qu == 'ok'){
$addfb = "- فيسبوك ✅";
$delfb = "fb-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addfb = "- فيسبوك ❌";
$delfb = "fb-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "ig"){
if($qu == 'ok'){
$addig = "- إنستقرام ✅";
$delig = "ig-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addig = "- إنستقرام ❌";
$delig = "ig-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "tw"){
if($qu == 'ok'){
$addtw = "- تويتر ✅";
$deltw = "tw-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addtw = "- تويتر ❌";
$deltw = "tw-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "lf"){
if($qu == 'ok'){
$addlf = "- تيكتوك ✅";
$dellf = "lf-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addlf = "- تيكتوك ❌";
$dellf = "lf-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "go"){
if($qu == 'ok'){
$addgo = "- قوقل ✅";
$delgo = "go-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addgo = "- قوقل ❌";
$delgo = "go-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "im"){
if($qu == 'ok'){
$addim = "- إيمو ✅";
$delim = "im-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addim = "- إيمو ❌";
$delim = "im-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "vi"){
if($qu == 'ok'){
$addvi = "- فايبر ✅";
$delvi = "vi-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addvi = "- فايبر ❌";
$delvi = "vi-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "fu"){
if($qu == 'ok'){
$addfu = "- سناب شات ✅";
$delfu = "fu-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addfu = "- سناب شات ❌";
$delfu = "fu-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "nf"){
if($qu == 'ok'){
$addnf = "- نيتفلكس ✅";
$delnf = "nf-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addnf = "- نيتفلكس ❌";
$delnf = "nf-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
if($code == "au"){
if($qu == 'ok'){
$addau = "- حراج ✅";
$delau = "au-ok";
$bb="تم إضافة التطبيق بنجاح ✅";
}else{
$addau = "- حراج ❌";
$delau = "au-no";
$bb="تم حذف التطبيق بنجاح ✅";
}
}
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"$bb",
'show_alert'=>folse
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- قم ب إختار التطبيق الذي تود إضافة الدولة إلى البوت 🎗
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$addot",'callback_data'=>"adminappadd-$delot"]],
[['text'=>"$addtg",'callback_data'=>"adminappadd-$deltg"],['text'=>"$addwa",'callback_data'=>"adminappadd-$delwa"]],
[['text'=>"$addig",'callback_data'=>"adminappadd-$delig"],['text'=>"$addfb",'callback_data'=>"adminappadd-$delfb"]],
[['text'=>"$addlf",'callback_data'=>"adminappadd-$dellf"],['text'=>"$addtw",'callback_data'=>"adminappadd-$deltw"]],
[['text'=>"$addim",'callback_data'=>"adminappadd-$delim"],['text'=>"$addgo",'callback_data'=>"adminappadd-$delgo"]],
[['text'=>"$addfu",'callback_data'=>"adminappadd-$delfu"],['text'=>"$addvi",'callback_data'=>"adminappadd-$delvi"]],
[['text'=>"$addau",'callback_data'=>"adminappadd-$delau"],['text'=>"$addnf",'callback_data'=>"adminappadd-$delnf"]],
[['text'=>"رجوع",'callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
$addblusdel[$code]['add'] = $qu;
Bnds($addblusdel);
}
 
#=========={التطبيقات}==========#
if($exdata[0] == "Bj"){
$api = $exdata[1];
$API = str_replace(["Ai","Bi","Ci","Di","Ei","Fi","Gi","Hi","Ji","Ki","Li","Mi","Ni","Oi","Pi","Qi","Ri","Si","Ti","Ui","Vi"],["5sim.biz","smslive.pro","sms-man.ru","Vak-sms.com","sms-acktiwator.ru","pvapins.com","bot-sms.shop","onlinesim.io","supersmstech.com","viotp.com","simsms.org","grizzlysms.com","sms-code.ru","tiger-sms.com","2ndline.io","receivesms.store","sms.fastpva.com","dropsms.ru","24sms7.com","sellotp.com","mm.duraincloud.com"],$api);
$keybo=str_replace(["Ai","Bi","Ci","Di","Ei","Fi","Gi","Hi","Ji","Ki","Li","Mi","Ni","Oi","Pi","Qi","Ri","Si","Ti","Ui","Vi"],["iA","iA","iA","iB","iC","iD","iE","iF","iG","iE","iH","iJ","iK","iL","iM","iN","iO","iP","iQ","iE","iR"],$api);
$array=["Ai","Bi","Ci","Di","Hi","Ji","Li","Mi","Ni","Oi","Si","Ti"];
if(in_array($api,$array) and $addblusdel['System'] == "direct"){
$keybo = "iV";
}else{
$keybo = $keybo;
}
if($addblusdel['System'] == "direct"){
$System="تلقائي";
}elseif($addblusdel['System'] == "not_directly"){
$System="يدوي";
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*➖️ الموقع: $API 🌐•*
*➖️ نظام البوت: $System 🤖•*

*➖️ قم بإختيار التطبيق المراد إضافة الرقم اليه ✅️•*
*➖️ من الكيبورد أدناه 👇•*
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$ot",'callback_data'=>"$keybo-ot-14-$api"]],
[['text'=>"$tg",'callback_data'=>"Fx-3-$api"],['text'=>"$wa",'callback_data'=>"Fx-2-$api"]],
[['text'=>"$ig",'callback_data'=>"$keybo-ig-5-$api"],['text'=>"$fb",'callback_data'=>"$keybo-fb-4-$api"]],
[['text'=>"$lf",'callback_data'=>"$keybo-lf-7-$api"],['text'=>"$tw",'callback_data'=>"$keybo-tw-6-$api"]],
[['text'=>"$im",'callback_data'=>"$keybo-im-9-$api"],['text'=>"$go",'callback_data'=>"$keybo-go-8-$api"]],
[['text'=>"$fu",'callback_data'=>"$keybo-fu-11-$api"],['text'=>"$vi",'callback_data'=>"$keybo-vi-10-$api"]],
[['text'=>"$au",'callback_data'=>"$keybo-au-13-$api"],['text'=>"$nf",'callback_data'=>"$keybo-nf-12-$api"]],
[['text'=>'رجوع','callback_data'=>'addnumber']]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
}
 
#=========={واتساب+تليجرام}==========#
if($exdata[0] == "Fx"){
$add = $exdata[1];
$api = $exdata[2];
$API = str_replace(["Ai","Bi","Ci","Di","Ei","Fi","Gi","Hi","Ji","Ki","Li","Mi","Ni","Oi","Pi","Qi","Ri","Si","Ti","Ui","Vi"],["5sim.biz","smslive.pro","sms-man.ru","Vak-sms.com","sms-acktiwator.ru","pvapins.com","bot-sms.shop","onlinesim.io","supersmstech.com","viotp.com","simsms.org","grizzlysms.com","sms-code.ru","tiger-sms.com","2ndline.io","receivesms.store","sms.fastpva.com","dropsms.ru","24sms7.com","sellotp.com","mm.duraincloud.com"],$api);
$keybo=str_replace(["Ai","Bi","Ci","Di","Ei","Fi","Gi","Hi","Ji","Ki","Li","Mi","Ni","Oi","Pi","Qi","Ri","Si","Ti","Ui","Vi"],["iA","iA","iA","iB","iC","iD","iE","iF","iG","iE","iH","iJ","iK","iL","iM","iN","iO","iP","iQ","iE","iR"],$api);
$array=["Ai","Bi","Ci","Di","Hi","Ji","Li","Mi","Ni","Oi","Si","Ti"];
if(in_array($api,$array) and $addblusdel['System'] == "direct"){
$keybo = "iV";
}else{
$keybo = $keybo;
}
if($addblusdel['System'] == "direct"){
$System="تلقائي";
}elseif($addblusdel['System'] == "not_directly"){
$System="يدوي";
}
if($add == 2){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*➖️ الموقع: $API 🌐•*
*➖️ نظام البوت: $System 🤖•*
➖️➖️➖️⚜️➖️➖️➖️
*➖️ الآن قم بإختيار القسم الذي تريد إضافتها لتطبيق واتساب 👇•*
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ كل القارات 🧩 ٭','callback_data'=>"$keybo-wa-2-$api"]],
[['text'=>'٭قسم العروض✥','callback_data'=>"$keybo-wa-1-$api"]],
[['text'=>'خدمة الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>"$keybo-wa-31-$api"]],
[['text'=>'رجوع','callback_data'=>"Bj-$api"]]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
}
if($add == 3){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*➖️ الموقع: $API 🌐•*
*➖️ نظام البوت: $System 🤖•*
➖️➖️➖️⚜️➖️➖️➖️
*➖️ الآن قم بإختيار القسم الذي تريد إضافتها لتطبيق تيليجرام 👇•*
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ كل القارات 🧩 ٭','callback_data'=>"$keybo-tg-3-$api"]],
[['text'=>'٭قسم العروض✥','callback_data'=>"$keybo-tg-36-$api"]],
[['text'=>'خدمة الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>"$keybo-wa-31-$api"]],
[['text'=>'رجوع','callback_data'=>"Bj-$api"]]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("zzz.json");
}
}
 
#=========={country[1+2+3]}==========#
if($exdata[0] == "iA"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($api == "Bi" or $api == "Ci"){
if($app=="ot"){
$app="NO";
}
}
if($con == 1) { $continent     = $AdminONE;  }
if($con == 2) { $continent     = $AdminTOW;  }
if($con == 3) { $continent     = $AdminTHREE;  }
if($con == 4) { $continent     = $AdminFOUR;  }
if($con == 5) { $continent     = $AdminFIVE;  }
if($con == 6) { $continent     = $AdminSIX;  }
if($con == 7) { $continent     = $AdminSEVEN;  }
if($con == 8) { $continent     = $AdminEIGHT;  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con >= 8){
$u = $con-1;
}elseif($con >= 1){
$o = $con+1;
$u = $con-1;
}
if($con == 1){
$t = "التالي. ⬅️";
$s = null;
$v = "الأخير. 🔀";
}elseif($con == 2 or $con == 3 or $con == 4 or $con == 5 or $con == 6 or $con == 7){
$t = "التالي. ⬅️";
$s = "➡️ السابق.";
$v = null;
$n = null;
}elseif($con == 8){
$t = null;
$s = "➡️ السابق.";
$n = "الأول. ⏭";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $ar=>$zero){
$API=str_replace(["Ai","Bi","Ci"],["5sim.biz","smslive.pro","sms-man.ru"],$api);
if($api == "Ai"){
$b="addservice-$app-$add-$api-$zero";
}else{
$b="addprice-$app-$add-$api-$zero-any";
}
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"$b"];
if($e%3 == 0) $i++;
}
$key['inline_keyboard'][0] = [['text'=>'☑️ - جميع الدول في الأسفل ⬇️','callback_data'=>"no"]];
$key['inline_keyboard'][] = [['text'=>"$t",'callback_data'=>"iA-$app-$add-$api-$o"],['text'=>"$s",'callback_data'=>"iA-$app-$add-$api-$u"],['text'=>"$v",'callback_data'=>"iA-$app-$add-$api-8"],['text'=>"$n",'callback_data'=>"iA-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❇️ هذة جميع الدول المتوفرة في الموقع $API
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[4]}==========#
 
if($exdata[0] == "iB"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
if($api == "Di"){
if($app=="im" or $app=="vi" or $app=="fu" or $app=="au" or $app=="ot"){
$app="NO";
}
}
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}else{
$a=0;//keyboard
$b=0;//count
foreach ($_co[country3] as $zero=>$ar){
$API=str_replace(["Di"],["Vak-sms.com"],$api);
$b++;
if($b%3!=0){
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
}else{
$a++;//لنزول سطر
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
}
}
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❇️ هذة جميع الدول المتوفرة في الموقع $API
",
reply_markup=>json_encode($key)
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
 
#=========={country[5]}==========#
if($exdata[0] == "iC"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
if($api == "Ei"){
if($app=="au" or $app=="ot"){
$app="NO";
}
}
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}else{
$a=0;//keyboard
$b=0;//count
foreach ($_co[country4] as $zero=>$ar){
$API=str_replace(["Ei"],["sms-acktiwator.ru"],$api);
$b++;
if($b%3!=0){
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
}else{
$a++;//لنزول سطر
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
}
}
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❇️ هذة جميع الدول المتوفرة في الموقع $API
",
reply_markup=>json_encode($key)
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[6]}==========#
 
if($exdata[0] == "iD"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($api == "Fi"){
if($app=="fu" or $app=="au" or $app=="ot"){
$app="NO";
}
}
if($con == 1) { $continent     = $_co[country5];  }
if($con == 2) { $continent     = $_co[country5_2];  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con == 1){
$t = "التالي. ⬅️";
}elseif($con == 2){
$s = "➡️ السابق.";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $zero=>$ar){
$API=str_replace(["Fi"],["pvapins.com"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
if($e%3 == 0) $i++;
}
$key[inline_keyboard][]=[['text'=>"$t",'callback_data'=>"iD-$app-$add-$api-2"],['text'=>"$s",'callback_data'=>"iD-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❇️ هذة جميع الدول المتوفرة في الموقع $API
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[7+10+20]}==========#
 
if($exdata[0] == "iE"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
if($api == "Gi"){
if($app=="ot"){
$app="NO";
}
}
if($api == "Ki"){
if($app=="fu" or $app=="au"){
$app="NO";
}
}
if($api == "Ui"){
if($app=="fu" or $app=="au"){
$app="NO";
}
}
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}else{
$a=0;//keyboard
$b=0;//count
foreach ($_co[country8] as $zero=>$ar){
$API=str_replace(["Gi","Ki","Ui"],["bot-sms.shop","viotp.com","sellotp.com"],$api);
$b++;
if($b%3!=0){
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addservice2-$app-$add-$api-$zero"];
}else{
$a++;//لنزول سطر
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addservice2-$app-$add-$api-$zero"];
}
}
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❇️ هذة جميع الدول المتوفرة في الموقع $API
",
reply_markup=>json_encode($key)
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
 
#=========={country[8]}==========#
if($exdata[0] == "iF"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
if($api == "Hi"){
if($app=="ot"){
$app="NO";
}
}
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}else{
$a=0;//keyboard
$b=0;//count
foreach ($_co[country6] as $zero=>$ar){
$API=str_replace(["Hi"],["onlinesim.io"],$api);
$b++;
if($b%3!=0){
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
}else{
$a++;//لنزول سطر
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
}
}
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❇️ هذة جميع الدول المتوفرة في الموقع $API
",
reply_markup=>json_encode($key)
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[9]}==========#
if($exdata[0] == "iG"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($api == "Ji"){
if($app=="au" or $app=="ot"){
$app="NO";
}
}
if($con == 1) { $continent     = $_co[country7];  }
if($con == 2) { $continent     = $_co[country7_2];  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con == 1){
$t = "التالي. ⬅️";
}elseif($con == 2){
$s = "➡️ السابق.";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $zero=>$ar){
$API=str_replace(["Ji"],["supersmstech.com"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"addservice3-$app-$add-$api-$zero"];
if($e%3 == 0) $i++;
}
$key[inline_keyboard][]=[['text'=>"$t",'callback_data'=>"iG-$app-$add-$api-2"],['text'=>"$s",'callback_data'=>"iG-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❇️ هذة جميع الدول المتوفرة في الموقع $API
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[11]}==========#
if($exdata[0] == "iH"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
if($api == "Li"){
if($app=="au" or $app=="ot"){
$app="NO";
}
}
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}else{
$a=0;//keyboard
$b=0;//count
foreach ($_co[country9] as $zero=>$ar){
$API=str_replace(["Li"],["simsms.org"],$api);
$b++;
if($b%3!=0){
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
}else{
$a++;//لنزول سطر
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
}
}
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️  هذة جميع الدول المتوفرة في الموقع $API ♻️•
",
reply_markup=>json_encode($key)
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[12]}==========#
if($exdata[0] == "iJ"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($api == "Mi"){
if($app=="fu" or $app=="au" or $app=="ot"){
$app="NO";
}
}
if($con == 1) { $continent     = $_co[country10];  }
if($con == 2) { $continent     = $_co[country10_2];  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con == 1){
$t = "التالي. ⬅️";
}elseif($con == 2){
$s = "➡️ السابق.";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $zero=>$ar){
$API=str_replace(["Mi"],["grizzlysms.com"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
if($e%3 == 0) $i++;
}
$key[inline_keyboard][]=[['text'=>"$t",'callback_data'=>"iJ-$app-$add-$api-2"],['text'=>"$s",'callback_data'=>"iJ-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️  هذة جميع الدول المتوفرة في الموقع $API ♻️•
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[13]}==========#
if($exdata[0] == "iK"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($api == "Ni"){
if($app=="au"){
$app="NO";
}
}
if($con == 1) { $continent     = $_co[country11];  }
if($con == 2) { $continent     = $_co[country11_2];  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con == 1){
$t = "التالي. ⬅️";
}elseif($con == 2){
$s = "➡️ السابق.";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $zero=>$ar){
$API=str_replace(["Ni"],["sms-code.ru"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
if($e%3 == 0) $i++;
}
$key[inline_keyboard][]=[['text'=>"$t",'callback_data'=>"iK-$app-$add-$api-2"],['text'=>"$s",'callback_data'=>"iK-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❇️ هذة جميع الدول المتوفرة في الموقع $API
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[14]}==========#
if($exdata[0] == "iL"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($api == "Oi"){
if($app=="au"){
$app="NO";
}
}
if($con == 1) { $continent     = $_co[country12];  }
if($con == 2) { $continent     = $_co[country12_2];  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con == 1){
$t = "التالي. ⬅️";
}elseif($con == 2){
$s = "➡️ السابق.";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $zero=>$ar){
$API=str_replace(["Oi"],["tiger-sms.com"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
if($e%3 == 0) $i++;
}
$key[inline_keyboard][]=[['text'=>"$t",'callback_data'=>"iL-$app-$add-$api-2"],['text'=>"$s",'callback_data'=>"iL-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️  هذة جميع الدول المتوفرة في الموقع $API ♻️•
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[15]}==========#
if($exdata[0] == "iM"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($api == "Pi"){
if($app=="im" or $app=="au"){
$app="NO";
}
}
if($con == 1) { $continent     = $_co[country13];  }
if($con == 2) { $continent     = $_co[country13_2];  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con == 1){
$t = "التالي. ⬅️";
}elseif($con == 2){
$s = "➡️ السابق.";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $zero=>$ar){
$API=str_replace(["Pi"],["2ndline.io"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"addservice2-$app-$add-$api-$zero"];
if($e%3 == 0) $i++;
}
$key[inline_keyboard][]=[['text'=>"$t",'callback_data'=>"iM-$app-$add-$api-2"],['text'=>"$s",'callback_data'=>"iM-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❇️ هذة جميع الدول المتوفرة في الموقع $API
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[16]}==========#
if($exdata[0] == "iN"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($api == "Qi"){
if($app=="lf" or $app=="im" or $app=="fu" or $app=="au" or $app=="ot"){
$app="NO";
}
}
if($con == 1) { $continent     = $_co[country14];  }
if($con == 2) { $continent     = $_co[country14_2];  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con == 1){
$t = "التالي. ⬅️";
}elseif($con == 2){
$s = "➡️ السابق.";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $zero=>$ar){
$API=str_replace(["Qi"],["receivesms.store"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
if($e%3 == 0) $i++;
}
$key[inline_keyboard][]=[['text'=>"$t",'callback_data'=>"iN-$app-$add-$api-2"],['text'=>"$s",'callback_data'=>"iN-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️  هذة جميع الدول المتوفرة في الموقع $API ♻️•
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[17]}==========#
if($exdata[0] == "iO"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($api == "Ri"){
if($app=="ot"){
$app="NO";
}
}
if($con == 1) { $continent     = $_co[country15];  }
if($con == 2) { $continent     = $_co[country15_2];  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con == 1){
$t = "التالي. ⬅️";
}elseif($con == 2){
$s = "➡️ السابق.";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $zero=>$ar){
$API=str_replace(["Ri"],["sms.fastpva.com"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"addservice4-$app-$add-$api-$zero"];
if($e%3 == 0) $i++;
}
$key[inline_keyboard][]=[['text'=>"$t",'callback_data'=>"iO-$app-$add-$api-2"],['text'=>"$s",'callback_data'=>"iO-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️  هذة جميع الدول المتوفرة في الموقع $API ♻️•
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[18]}==========#
if($exdata[0] == "iP"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
if($api == "Si"){
if($app=="tg" or $app=="tw" or $app=="lf" or $app=="go" or $app=="im" or $app=="vi" or $app=="fu" or $app=="nf" or $app=="au" or $app=="ot"){
$app="NO";
}
}
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}else{
$a=0;//keyboard
$b=0;//count
foreach ($_co[country16] as $zero=>$ar){
$API=str_replace(["Si"],["dropsms.ru"],$api);
$b++;
if($b%2!=0){
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
}else{
$a++;//لنزول سطر
$key[inline_keyboard][$a][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
}
}
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️  هذة جميع الدول المتوفرة في الموقع $API ♻️•
",
reply_markup=>json_encode($key)
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[19]}==========#
if($exdata[0] == "iQ"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($con == 1) { $continent     = $_co[country17];  }
if($con == 2) { $continent     = $_co[country17_2];  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con == 1){
$t = "التالي. ⬅️";
}elseif($con == 2){
$s = "➡️ السابق.";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $zero=>$ar){
$API=str_replace(["Ti"],["24sms7.com"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
if($e%3 == 0) $i++;
}
$key[inline_keyboard][]=[['text'=>"$t",'callback_data'=>"iQ-$app-$add-$api-2"],['text'=>"$s",'callback_data'=>"iQ-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️  هذة جميع الدول المتوفرة في الموقع $API ♻️•
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={country[21]}==========#
if($exdata[0] == "iR"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($api == "Vi"){
if($app=="ot"){
$app="NO";
}
}
if($con == 1) { $continent     = $_co[country18];  }
if($con == 2) { $continent     = $_co[country18_2];  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con == 1){
$t = "التالي. ⬅️";
}elseif($con == 2){
$s = "➡️ السابق.";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $zero=>$ar){
$API=str_replace(["Vi"],["mm.duraincloud.com.com"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"addprice-$app-$add-$api-$zero-any"];
if($e%3 == 0) $i++;
}
$key[inline_keyboard][]=[['text'=>"$t",'callback_data'=>"iR-$app-$add-$api-2"],['text'=>"$s",'callback_data'=>"iR-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❇️ هذة جميع الدول المتوفرة في الموقع $API
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={countryall}==========#
if($exdata[0] == "iV"){
$app = $exdata[1];
$add = $exdata[2];
$api = $exdata[3];
$con=$exdata[4];
if($con==null){
$con=1;
}
if($con == 1) { $continent     = $AdminONE;  }
if($con == 2) { $continent     = $AdminTOW;  }
if($con == 3) { $continent     = $AdminTHREE;  }
if($con == 4) { $continent     = $AdminFOUR;  }
if($con == 5) { $continent     = $AdminFIVE;  }
if($con == 6) { $continent     = $AdminSIX;  }
if($con == 7) { $continent     = $AdminSEVEN;  }
if($con == 8) { $continent     = $AdminEIGHT;  }
if($app == 'NO'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
- عذرا مطوري هذا التطبيق غير متوفر للوقع المطلوب ❌
",
'show_alert'=>false
]);
unlink("data/id/$id/step.txt");
}elseif($continent == null){
unlink("data/id/$id/step.txt");
}else{
if($con >= 8){
$u = $con-1;
}elseif($con >= 1){
$o = $con+1;
$u = $con-1;
}
if($con == 1){
$t = "التالي. ⬅️";
$s = null;
$v = "الأخير. 🔀";
}elseif($con == 2 or $con == 3 or $con == 4 or $con == 5 or $con == 6 or $con == 7){
$t = "التالي. ⬅️";
$s = "➡️ السابق.";
$v = null;
$n = null;
}elseif($con == 8){
$t = null;
$s = "➡️ السابق.";
$n = "الأول. ⏭";
}
$i = 0;
$e = 0;
$key     = [];
foreach ($continent as $ar=>$zero){
$API = str_replace(["Ai","Bi","Ci","Di","Hi","Ji","Li","Mi","Ni","Si"],["5sim.biz","smslive.pro","sms-man.ru","Vak-sms.com","onlinesim.io","supersmstech.com","simsms.org","grizzlysms.com","sms-code.ru","dropsms.ru"],$api);
$e++;
$key[inline_keyboard][$i][]=[text=>"$ar",callback_data=>"allservice-$app-$add-$api-$zero"];
if($e%3 == 0) $i++;
}
$key['inline_keyboard'][0] = [['text'=>'☑️ - جميع الدول في الأسفل ⬇️','callback_data'=>"no"]];
$key['inline_keyboard'][] = [['text'=>"$t",'callback_data'=>"iV-$app-$add-$api-$o"],['text'=>"$s",'callback_data'=>"iV-$app-$add-$api-$u"],['text'=>"$v",'callback_data'=>"iV-$app-$add-$api-8"],['text'=>"$n",'callback_data'=>"iV-$app-$add-$api-1"]];
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"Bj-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️  هذة جميع الدول المتوفرة في الموقع $API ♻️•
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
}
#=========={servicall}==========#
if($exdata[0] == "allservice" and $addblusdel['System'] == "direct"){
$app=$exdata[1];
$add=$exdata[2];
$api=$exdata[3];
$country=$exdata[4];
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
$apps = str_replace(["B","C","D","E","F","G","H","I","J","K","L","M","N"],["whatsapp","telegram","facebook","instagram","twitter","tiktok","google","imo","viber","snapchat","netflix","haraj","other"],$status);
$APP = str_replace(["B","C","D","E","F","G","H","I","J","K","L","M","N"],["واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام"],$status);
$app_5sim = str_replace(["B","C","D","E","F","G","H","I","J","K","L","M","N"],["whatsapp","telegram","facebook","instagram","twitter","tiktok","google","imo","viber","snapchat","netflix","haraj","other"],$status);#5sim
$APP_S = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["WhatsApp","Telegram","Facebook","Instagram","Twitter","Tiktok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);#APP
$name = $_co['country'][$country];
$zx = $o_co['country'][$country];
$all=json_decode(file_get_contents("https://5sim.biz/v1/guest/prices?country=$country&product=$app_5sim"),1);
$api="Ai";
$site="5sim";
$rupl=$addblusdel[$site]['rupl'];
$opt = "019";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
$api_key = $APPS[$site][api_key];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=94;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "activ";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=95;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "altel";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=96;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "beeline";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=97;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "claro";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=98;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "ee";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=99;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "globe";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=100;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "kcell";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=101;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "lycamobile";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=102;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "matrix";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=103;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "megafon";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=104;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "mts";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=105;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "orange";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=106;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "pildyk";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=107;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "play";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=108;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "redbullmobile";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=109;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "rostelecom";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=110;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "smart";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=111;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "sun";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=112;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "tele2";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=113;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "three";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=114;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "tigo";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=115;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "tmobile";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=116;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "tnt";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=117;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virginmobile";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=118;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual2";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=119;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual4";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=120;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual5";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=121;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual7";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=122;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual8";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=123;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual12";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=124;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual15";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=125;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual16";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=126;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual17";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=127;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual18";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=128;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual19";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=129;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual20";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=130;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual21";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=131;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual22";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=132;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual23";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=133;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual24";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=134;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual25";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=135;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual26";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=136;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual27";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=137;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual28";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=138;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual29";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=139;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual30";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=140;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual31";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=141;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual32";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=142;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual33";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=143;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual34";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=144;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual35";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=145;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual36";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=146;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "virtual37";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=147;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "vodafone";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=148;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "yota";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=149;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$opt = "zz";
$adds=$all[$zx][$app_5sim][$opt][count];
$price=$all[$zx][$app_5sim][$opt][cost];
if($api_key != null and $adds >= 3 and $price> 0 and $addblusdel['5sim']['add'] == "ok"){
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$operator=150;
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Bi";
$site="smslive";
$opt = "any";
$api_key = $APPS[$site][api_key];
$operator=1;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $adds >= 5 and $price > 0 and $addblusdel['smslive']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Ci";
$site="man";
$opt = "any";
$api_key = $APPS[$site][api_key];
$operator=2;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $adds >= 10 and $price > 0 and $addblusdel['man']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
$buy['number'][$code]['app'] = $app;
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Di";
$site="vak";
$opt = "any";
$api_key = $APPS[$site][api_key];
$operator=3;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $adds >= 10 and $price > 0 and $o_co['country3'][$country] != null and $addblusdel['vak']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Hi";
$site="onlinesim";
$opt = "any";
$api_key = $APPS[$site][api_key];
$operator=6;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $adds >= 10 and $price > 0 and $o_co['country6'][$country] != null and $addblusdel['onlinesim']['add'] == "ok"){
$price=$price*$Exchange;
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Ji";
$site="supersmstechtech";
$opt = "1";
$api_key = $APPS[$site][api_key];
$operator=48;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $price > 0 and $o_co['country7'][$country] != null and $addblusdel['supersmstech']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Ji";
$site="supersmstechtech";
$opt = "3";
$api_key = $APPS[$site][api_key];
$operator=49;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $price > 0 and $o_co['country7'][$country] != null and $addblusdel['supersmstech']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Ji";
$site="supersmstechtech";
$opt = "4";
$api_key = $APPS[$site][api_key];
$operator=50;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $price > 0 and $o_co['country7'][$country] != null and $addblusdel['supersmstech']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Ji";
$site="supersmstechtech";
$opt = "5";
$api_key = $APPS[$site][api_key];
$operator=51;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $price > 0 and $o_co['country7'][$country] != null and $addblusdel['supersmstech']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Ji";
$site="supersmstechtech";
$opt = "7";
$api_key = $APPS[$site][api_key];
$operator=52;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $price > 0 and $o_co['country7'][$country] != null and $addblusdel['supersmstech']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Li";
$site="simsms";
$opt = "any";
$api_key = $APPS[$site][api_key];
$operator=7;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $price > 0 and $o_co['country9'][$country] != null and $addblusdel['simsms']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Mi";
$site="grizzly";
$opt = "any";
$api_key = $APPS[$site][api_key];
$operator=8;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $price > 0 and $o_co['country10'][$country] != null and $addblusdel['grizzly']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Ni";
$site="smscode";
$opt = "any";
$api_key = $APPS[$site][api_key];
$operator=9;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $price > 0 and $o_co['country11'][$country] != null and $addblusdel['smscode']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Oi";
$site="tiger";
$opt = "any";
$api_key = $APPS[$site][api_key];
$operator=10;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
$nams=$_co['country12'][$country];
if($nams == null){
$nams = $_co['country12_2'][$country];
}
if($api_key != null and $price > 0 and $nams != null and $addblusdel['tiger']['add'] == "ok"){
$price=$price*$Exchange;
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Si";
$site="dropsms";
$opt = "any";
$api_key = $APPS[$site][api_key];
$operator=12;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
if($api_key != null and $price > 0 and $addblusdel['dropsms']['add'] == "ok"){
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$price="$price.00";
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
$api="Ti";
$site="24sms7";
$opt = "any";
$api_key = $APPS[$site][api_key];
$zx = $o_co['country17'][$country];
$operator=13;
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
$price=$api_price[price];
$adds=$api_price[add];
if($api_key != null and $adds >= 5 and $price > 0 and $o_co['country17'][$country] != null and $addblusdel['24sms7']['add'] == "ok"){
$price=$price*$Exchange;
$rupl=$addblusdel[$site]['rupl'];
$price=$rupl+$price;
$exn=explode(".", $price);
if($exn[1] > 0){
$price="$exn[0].5";
}else{
$price="$exn[0].00";
}
$code = "$country$app$operator$add";
$code = md5($code);
unset($buy['number'][$code]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "direct";
$buy['number'][$code]['price'] = $price;
if($buy['number'][$code] == null){
$buy['country_app']["$add-$country"] += 1;
}
$i++;
Addserver($buy);
}
if(count($buy['country_app']["$add-$country"]) >= 1){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
➖️ تم تحديث الدولة بنجاح 😇•
➖️ الدولة: $name •
➖️ التطبيق: $APP 🌐•
➖️ عدد السيرفرات: $i ♻️
              🔰

➖️➖️➖️⚜️➖️➖️➖️
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}else{
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
➖️ تم اضافة الدولة بنجاح 😇•
➖️ الدولة: $name •
➖️ التطبيق: $APP 🌐•
➖️ عدد السيرفرات: $i ♻️
              🔰

➖️➖️➖️⚜️➖️➖️➖️
",
'show_alert'=>true
]);
exit;
}
}
#=========={bot-sms.shop & viotp.com & 2ndline.io and sellotp.com}==========#
if($exdata[0] == "addservice2"){
$app=$exdata[1];
$add=$exdata[2];
$api=$exdata[3];
$country=$exdata[4];
$API = str_replace(["Gi","Ki","Pi","Ui"],["bot-sms.shop","viotp.com","2ndline.io","sellotp.com"],$api);
if($add >=1 and $add <=14){
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
}else{
$status = $add;
}
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز"],$status);
$name = $_co['country'][$country];
if($api == "Gi"){
$key[inline_keyboard][]=[['text'=>"any",'callback_data'=>"addprice-$app-$add-$api-$country-any"]];
$key[inline_keyboard][]=[['text'=>"mobifone",'callback_data'=>"addprice-$app-$add-$api-$country-mobifone"]];
$key[inline_keyboard][]=[['text'=>"vietnamobile",'callback_data'=>"addprice-$app-$add-$api-$country-vietnamobile"]];
$key[inline_keyboard][]=[['text'=>"viettel",'callback_data'=>"addprice-$app-$add-$api-$country-viettel"]];
$key[inline_keyboard][]=[['text'=>"vinaphone",'callback_data'=>"addprice-$app-$add-$api-$country-vinaphone"]];
}elseif($api == "Ki"){
$key[inline_keyboard][]=[['text'=>"MOBIFONE",'callback_data'=>"addprice-$app-$add-$api-$country-MOBIFONE"]];
$key[inline_keyboard][]=[['text'=>"VINAPHONE",'callback_data'=>"addprice-$app-$add-$api-$country-VINAPHONE"]];
$key[inline_keyboard][]=[['text'=>"VIETTEL",'callback_data'=>"addprice-$app-$add-$api-$country-VIETTEL"]];
$key[inline_keyboard][]=[['text'=>"VIETNAMOBILE",'callback_data'=>"addprice-$app-$add-$api-$country-VIETNAMOBILE"]];
$key[inline_keyboard][]=[['text'=>"ITELECOM",'callback_data'=>"addprice-$app-$add-$api-$country-ITELECOM"]];
}elseif($api == "Pi"){
$key[inline_keyboard][]=[['text'=>"Operator",'callback_data'=>"addprice-$app-$add-$api-$country-0"]];
$key[inline_keyboard][]=[['text'=>"Viettel Group",'callback_data'=>"addprice-$app-$add-$api-$country-1"]];
$key[inline_keyboard][]=[['text'=>"MobiFone",'callback_data'=>"addprice-$app-$add-$api-$country-2"]];
$key[inline_keyboard][]=[['text'=>"VinaPhone",'callback_data'=>"addprice-$app-$add-$api-$country-3"]];
$key[inline_keyboard][]=[['text'=>"Vietnamobile",'callback_data'=>"addprice-$app-$add-$api-$country-4"]];
$key[inline_keyboard][]=[['text'=>"Itelecom",'callback_data'=>"addprice-$app-$add-$api-$country-5"]];
$key[inline_keyboard][]=[['text'=>"Three",'callback_data'=>"addprice-$app-$add-$api-$country-6"]];
$key[inline_keyboard][]=[['text'=>"Indosat Ooredoo",'callback_data'=>"addprice-$app-$add-$api-$country-8"]];
$key[inline_keyboard][]=[['text'=>"Telkomsel",'callback_data'=>"addprice-$app-$add-$api-$country-9"]];
$key[inline_keyboard][]=[['text'=>"Axis",'callback_data'=>"addprice-$app-$add-$api-$country-10"]];
$key[inline_keyboard][]=[['text'=>"Lebara",'callback_data'=>"addprice-$app-$add-$api-$country-11"]];
$key[inline_keyboard][]=[['text'=>"Vodafone Group",'callback_data'=>"addprice-$app-$add-$api-$country-12"]];
$key[inline_keyboard][]=[['text'=>"Smart Telecom",'callback_data'=>"addprice-$app-$add-$api-$country-13"]];
$key[inline_keyboard][]=[['text'=>"DTAC",'callback_data'=>"addprice-$app-$add-$api-$country-14"]];
$key[inline_keyboard][]=[['text'=>"TrueMove H",'callback_data'=>"addprice-$app-$add-$api-$country-15"]];
$key[inline_keyboard][]=[['text'=>"ETL Lao",'callback_data'=>"addprice-$app-$add-$api-$country-16"]];
$key[inline_keyboard][]=[['text'=>"Lao Tel",'callback_data'=>"addprice-$app-$add-$api-$country-17"]];
}elseif($api == "Ui"){
$key[inline_keyboard][]=[['text'=>"MOBIFONE",'callback_data'=>"addprice-$app-$add-$api-$country-MOBIFONE"]];
$key[inline_keyboard][]=[['text'=>"VINAPHONE",'callback_data'=>"addprice-$app-$add-$api-$country-VINAPHONE"]];
$key[inline_keyboard][]=[['text'=>"VIETTEL",'callback_data'=>"addprice-$app-$add-$api-$country-VIETTEL"]];
$key[inline_keyboard][]=[['text'=>"VIETNAMOBILE",'callback_data'=>"addprice-$app-$add-$api-$country-VIETNAMOBILE"]];
$key[inline_keyboard][]=[['text'=>"ITELECOM",'callback_data'=>"addprice-$app-$add-$api-$country-ITELECOM"]];
}
$key[inline_keyboard][]=[['text'=>'رجوع','callback_data'=>"iE-$app-$add-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ يتم الان اضافه المعلومات 🛎•
➖ الدولة : $name •
➖ التطبيق :  $APP 🌐•
➖ الموقع :  $API ♻️•

        🔰    
➖➖➖➖➖➖
🅿️ - قم ب إختيار السيرفر لهذه الدولة ⏬
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
exit;
}
#=========={servicsuper}==========#
if($exdata[0] == "addservice3"){
$app=$exdata[1];
$add=$exdata[2];
$api=$exdata[3];
$country=$exdata[4];
$API = str_replace(["Ji"],["supersmstech.com"],$api);
if($add >=1 and $add <=14){
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
}else{
$status = $add;
}
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز"],$status);
$name = $_co['country'][$country];
if($app != null){
$serv1 = "Super Channel (Global)";
}
if($app != null){
$serv2 = "Channel 3 (Global)";
}
if($app == "wa" or $app == "tg" or $app == "lf" or $app == "go" or $app == "im" or $app == "vi"){
$serv3 = "Channel 4 (ChatGPT, TG)";
}
if($app == "tg" or $app == "fb" or $app == "ig" or $app == "tw" or $app == "go" or $app == "vi" or $app == "nf"){
$serv4 = "Channel 5 (Global)";
}
if($app != null){
$serv5 = "Channel 7 (Global)";
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ يتم الان اضافه المعلومات 🛎•
➖ الدولة : $name •
➖ التطبيق :  $APP 🌐•
➖ الموقع :  $API ♻️•

        🔰    
➖➖➖➖➖➖
🅿️ - قم ب إختيار السيرفر لهذه الدولة ⏬
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$serv1",'callback_data'=>"addprice-$app-$add-$api-$country-1"]],
[['text'=>"$serv2",'callback_data'=>"addprice-$app-$add-$api-$country-3"]],
[['text'=>"$serv3",'callback_data'=>"addprice-$app-$add-$api-$country-4"]],
[['text'=>"$serv4",'callback_data'=>"addprice-$app-$add-$api-$country-5"]],
[['text'=>"$serv5",'callback_data'=>"addprice-$app-$add-$api-$country-7"]],
[['text'=>'رجوع','callback_data'=>"iG-$app-$add-$api"]]
]
])
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
exit;
}

#=========={fastpva}==========#
if($exdata[0] == "addservice4"){
$app=$exdata[1];
$add=$exdata[2];
$api=$exdata[3];
$country=$exdata[4];
$API = str_replace(["Ri"],["sms.fastpva.com"],$api);
if($add >=1 and $add <=14){
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
}else{
$status = $add;
}
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز"],$status);
$name = $_co['country'][$country];
if($app == "wa"){
$key[inline_keyboard][]=[['text'=>"Whatsapp-C6",'callback_data'=>"addprice-$app-$add-$api-$country-3819"]];
$key[inline_keyboard][]=[['text'=>"Whatsapp-C3",'callback_data'=>"addprice-$app-$add-$api-$country-3543"]];
}
if($app == "tg"){
$key[inline_keyboard][]=[['text'=>"Telegram Login-C2",'callback_data'=>"addprice-$app-$add-$api-$country-3528"]];
$key[inline_keyboard][]=[['text'=>"Telegram-C3",'callback_data'=>"addprice-$app-$add-$api-$country-3328"]];
$key[inline_keyboard][]=[['text'=>"Telegram-C2",'callback_data'=>"addprice-$app-$add-$api-$country-2001"]];
}
if($app == "fb"){
$key[inline_keyboard][]=[['text'=>"facebook-C6",'callback_data'=>"addprice-$app-$add-$api-$country-3825"]];
$key[inline_keyboard][]=[['text'=>"Facebook Reg-C2",'callback_data'=>"addprice-$app-$add-$api-$country-3521"]];
$key[inline_keyboard][]=[['text'=>"Facebook-C3",'callback_data'=>"addprice-$app-$add-$api-$country-3326"]];
$key[inline_keyboard][]=[['text'=>"Facebook1-C3",'callback_data'=>"addprice-$app-$add-$api-$country-2940"]];
$key[inline_keyboard][]=[['text'=>"Facebook新-C3",'callback_data'=>"addprice-$app-$add-$api-$country-2919"]];
}
if($app == "gi"){
$key[inline_keyboard][]=[['text'=>"Instagram-C6",'callback_data'=>"addprice-$app-$add-$api-$country-3832"]];
$key[inline_keyboard][]=[['text'=>"Instagram Reg-C4",'callback_data'=>"addprice-$app-$add-$api-$country-3744"]];
$key[inline_keyboard][]=[['text'=>"Instagram Reg-C2",'callback_data'=>"addprice-$app-$add-$api-$country-3375"]];
$key[inline_keyboard][]=[['text'=>"Instagram-C3",'callback_data'=>"addprice-$app-$add-$api-$country-3262"]];
$key[inline_keyboard][]=[['text'=>"Instagram-C3",'callback_data'=>"addprice-$app-$add-$api-$country-3128"]];
}
if($app == "tw"){
$key[inline_keyboard][]=[['text'=>"Twitter-C6",'callback_data'=>"addprice-$app-$add-$api-$country-3826"]];
$key[inline_keyboard][]=[['text'=>"Twitter Reg-C4",'callback_data'=>"addprice-$app-$add-$api-$country-3749"]];
$key[inline_keyboard][]=[['text'=>"Twitter Login-C2",'callback_data'=>"addprice-$app-$add-$api-$country-3484"]];
$key[inline_keyboard][]=[['text'=>"Twitter-C3",'callback_data'=>"addprice-$app-$add-$api-$country-3333"]];
$key[inline_keyboard][]=[['text'=>"Twitter -C3",'callback_data'=>"addprice-$app-$add-$api-$country-3302"]];
$key[inline_keyboard][]=[['text'=>"Twitter-C2",'callback_data'=>"addprice-$app-$add-$api-$country-2003"]];
}
if($app == "lf"){
$key[inline_keyboard][]=[['text'=>"Tiktok-C2",'callback_data'=>"addprice-$app-$add-$api-$country-3475"]];
}
if($app == "go"){
$key[inline_keyboard][]=[['text'=>"Gmail/Google Reg-C2",'callback_data'=>"addprice-$app-$add-$api-$country-3391"]];
$key[inline_keyboard][]=[['text'=>"Google/Gmail-C3",'callback_data'=>"addprice-$app-$add-$api-$country-3335"]];
$key[inline_keyboard][]=[['text'=>"Google Pay-C3",'callback_data'=>"addprice-$app-$add-$api-$country-2012"]];
}
if($app == "im"){
$key[inline_keyboard][]=[['text'=>"Imo-C6",'callback_data'=>"addprice-$app-$add-$api-$country-3862"]];
$key[inline_keyboard][]=[['text'=>"Imo-C2",'callback_data'=>"addprice-$app-$add-$api-$country-3385"]];
$key[inline_keyboard][]=[['text'=>"Imo-C3",'callback_data'=>"addprice-$app-$add-$api-$country-2624"]];
}
if($app == "vi"){
$key[inline_keyboard][]=[['text'=>"Viber-C6",'callback_data'=>"addprice-$app-$add-$api-$country-3820"]];
$key[inline_keyboard][]=[['text'=>"Viber Reg-C2",'callback_data'=>"addprice-$app-$add-$api-$country-3497"]];
$key[inline_keyboard][]=[['text'=>"Viber-C3",'callback_data'=>"addprice-$app-$add-$api-$country-3312"]];
}
if($app == "fu"){
$key[inline_keyboard][]=[['text'=>"Snapchat-C6",'callback_data'=>"addprice-$app-$add-$api-$country-3890"]];
$key[inline_keyboard][]=[['text'=>"Snapchat Reg-C2",'callback_data'=>"addprice-$app-$add-$api-$country-3392"]];
$key[inline_keyboard][]=[['text'=>"Snapchat-C3",'callback_data'=>"addprice-$app-$add-$api-$country-2866"]];
}
if($app == "nf"){
$key[inline_keyboard][]=[['text'=>"Netflix-C6",'callback_data'=>"addprice-$app-$add-$api-$country-3858"]];
$key[inline_keyboard][]=[['text'=>"Netflix Reg-C2",'callback_data'=>"addprice-$app-$add-$api-$country-3515"]];
$key[inline_keyboard][]=[['text'=>"Netflix-C3",'callback_data'=>"addprice-$app-$add-$api-$country-3191"]];
$key[inline_keyboard][]=[['text'=>"Netflix-C3",'callback_data'=>"addprice-$app-$add-$api-$country-3189"]];
}
if($app == "au"){
$key[inline_keyboard][]=[['text'=>"Haraj-C6",'callback_data'=>"addprice-$app-$add-$api-$country-3999"]];
$key[inline_keyboard][]=[['text'=>"Haraj-C3",'callback_data'=>"addprice-$app-$add-$api-$country-2929"]];
}
$key[inline_keyboard][]=[['text'=>"رجوع",'callback_data'=>"iO-$app-$add-$api"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ يتم الان اضافه المعلومات 🛎•
➖ الدولة : $name •
➖ التطبيق :  $APP 🌐•
➖ الموقع :  $API ♻️•

        🔰    
➖➖➖➖➖➖
🅿️ - قم ب إختيار السيرفر لهذه الدولة ⏬
",
'reply_markup'=>($keyboad),
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
exit;
}
   
#=========={servic5sim}==========#
if($exdata[0] == "addservice"){
$app=$exdata[1];
$add=$exdata[2];
$api=$exdata[3];
$country=$exdata[4];
$API = str_replace(["Ai"],["5sim.biz"],$api);
if($add >=1 and $add <=14){
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
}else{
$status = $add;
}
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز"],$status);
$name = $_co['country'][$country];
$zx = $o_co['country'][$country];
$app_5sim = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["whatsapp","telegram","facebook","instagram","twitter","tiktok","google","imo","viber","snapchat","netflix","haraj","other"],$app);
$all=json_decode(file_get_contents("https://5sim.biz/v1/guest/prices?country=$country&product=$app_5sim"),1);
$adds=$all[$zx][$app_5sim]["019"][count];
$price=$all[$zx][$app_5sim]["019"][cost];
if($price != null){
$bee019 = "019";
$bee0191 = "$price ₽| $adds";
}else{
$bee019 = null;
$bee0191 = null;
}
$adds=$all[$zx][$app_5sim]["activ"][count];
$price=$all[$zx][$app_5sim]["activ"][cost];
if($price != null){
$activ = "activ";
$activ1 = "$price ₽| $adds";
}else{
$activ = null;
$activ1 = null;
}
$adds=$all[$zx][$app_5sim]["altel"][count];
$price=$all[$zx][$app_5sim]["altel"][cost];
if($price != null){
$altel = "altel";
$altel1 = "$price ₽| $adds";
}else{
$altel = null;
$altel1 = null;
}
$adds=$all[$zx][$app_5sim]["beeline"][count];
$price=$all[$zx][$app_5sim]["beeline"][cost];
if($price != null){
$beeline = "beeline";
$beeline1 = "$price ₽| $adds";
}else{
$beeline = null;
$beeline1 = null;
}
$adds=$all[$zx][$app_5sim]["claro"][count];
$price=$all[$zx][$app_5sim]["claro"][cost];
if($price != null){
$claro = "claro";
$claro1 = "$price ₽| $adds";
}else{
$claro = null;
$claro1 = null;
}
$adds=$all[$zx][$app_5sim]["ee"][count];
$price=$all[$zx][$app_5sim]["ee"][cost];
if($price != null){
$ee = "ee";
$ee1 = "$price ₽| $adds";
}else{
$ee = null;
$ee1 = null;
}
$adds=$all[$zx][$app_5sim]["globe"][count];
$price=$all[$zx][$app_5sim]["globe"][cost];
if($price != null){
$globe = "globe";
$globe1 = "$price ₽| $adds";
}else{
$globe = null;
$globe1 = null;
}
$adds=$all[$zx][$app_5sim]["kcell"][count];
$price=$all[$zx][$app_5sim]["kcell"][cost];
if($price != null){
$kcell = "kcell";
$kcell1 = "$price ₽| $adds";
}else{
$kcell = null;
$kcell1 = null;
}
$adds=$all[$zx][$app_5sim]["lycamobile"][count];
$price=$all[$zx][$app_5sim]["lycamobile"][cost];
if($price != null){
$lycamobile = "lycamobile";
$lycamobile1 = "$price ₽| $adds";
}else{
$lycamobile = null;
$lycamobile1 = null;
}
$adds=$all[$zx][$app_5sim]["matrix"][count];
$price=$all[$zx][$app_5sim]["matrix"][cost];
if($price != null){
$matrix = "matrix";
$matrix1 = "$price ₽| $adds";
}else{
$matrix = null;
$matrix1 = null;
}
$adds=$all[$zx][$app_5sim]["megafon"][count];
$price=$all[$zx][$app_5sim]["megafon"][cost];
if($price != null){
$megafon = "megafon";
$megafon1 = "$price ₽| $adds";
}else{
$megafon = null;
$megafon1 = null;
}
$adds=$all[$zx][$app_5sim]["mts"][count];
$price=$all[$zx][$app_5sim]["mts"][cost];
if($price != null){
$mts = "mts";
$mts1 = "$price ₽| $adds";
}else{
$mts = null;
$mts1 = null;
}
$adds=$all[$zx][$app_5sim]["orange"][count];
$price=$all[$zx][$app_5sim]["orange"][cost];
if($price != null){
$orange = "orange";
$orange1 = "$price ₽| $adds";
}else{
$orange = null;
$orange1 = null;
}
$adds=$all[$zx][$app_5sim]["pildyk"][count];
$price=$all[$zx][$app_5sim]["pildyk"][cost];
if($price != null){
$pildyk = "pildyk";
$pildyk1 = "$price ₽| $adds";
}else{
$pildyk = null;
$pildyk1 = null;
}
$adds=$all[$zx][$app_5sim]["play"][count];
$price=$all[$zx][$app_5sim]["play"][cost];
if($price != null){
$play = "play";
$play1 = "$price ₽| $adds";
}else{
$play = null;
$play1 = null;
}
$adds=$all[$zx][$app_5sim]["redbullmobile"][count];
$price=$all[$zx][$app_5sim]["redbullmobile"][cost];
if($price != null){
$redbullmobile = "redbullmobile";
$redbullmobile1 = "$price ₽| $adds";
}else{
$redbullmobile = null;
$redbullmobile1 = null;
}
$adds=$all[$zx][$app_5sim]["rostelecom"][count];
$price=$all[$zx][$app_5sim]["rostelecom"][cost];
if($price != null){
$rostelecom = "rostelecom";
$rostelecom1 = "$price ₽| $adds";
}else{
$rostelecom = null;
$rostelecom1 = null;
}
$adds=$all[$zx][$app_5sim]["smart"][count];
$price=$all[$zx][$app_5sim]["smart"][cost];
if($price != null){
$smart = "smart";
$smart1 = "$price ₽| $adds";
}else{
$smart = null;
$smart1 = null;
}
$adds=$all[$zx][$app_5sim]["sun"][count];
$price=$all[$zx][$app_5sim]["sun"][cost];
if($price != null){
$sun = "sun";
$sun1 = "$price ₽| $adds";
}else{
$sun = null;
$sun1 = null;
}
$adds=$all[$zx][$app_5sim]["tele2"][count];
$price=$all[$zx][$app_5sim]["tele2"][cost];
if($price != null){
$tele2 = "tele2";
$tele21 = "$price ₽| $adds";
}else{
$tele2 = null;
$tele21 = null;
}
$adds=$all[$zx][$app_5sim]["three"][count];
$price=$all[$zx][$app_5sim]["three"][cost];
if($price != null){
$three = "three";
$three1 = "$price ₽| $adds";
}else{
$three = null;
$three1 = null;
}
$adds=$all[$zx][$app_5sim]["tigo"][count];
$price=$all[$zx][$app_5sim]["tigo"][cost];
if($price != null){
$tigo = "tigo";
$tigo1 = "$price ₽| $adds";
}else{
$tigo = null;
$tigo1 = null;
}
$adds=$all[$zx][$app_5sim]["tmobile"][count];
$price=$all[$zx][$app_5sim]["tmobile"][cost];
if($price != null){
$tmobile = "tmobile";
$tmobile1 = "$price ₽| $adds";
}else{
$tmobile = null;
$tmobile1 = null;
}
$adds=$all[$zx][$app_5sim]["tnt"][count];
$price=$all[$zx][$app_5sim]["tnt"][cost];
if($price != null){
$tnt = "tnt";
$tnt1 = "$price ₽| $adds";
}else{
$tnt = null;
$tnt1 = null;
}
$adds=$all[$zx][$app_5sim]["virginmobile"][count];
$price=$all[$zx][$app_5sim]["virginmobile"][cost];
if($price != null){
$virginmobile = "virginmobile";
$virginmobile1 = "$price ₽| $adds";
}else{
$virginmobile = null;
$virginmobile1 = null;
}
$adds=$all[$zx][$app_5sim]["virtual2"][count];
$price=$all[$zx][$app_5sim]["virtual2"][cost];
if($price != null){
$virtual2 = "virtual2";
$virtual21 = "$price ₽| $adds";
}else{
$virtual2 = null;
$virtual21 = null;
}
$adds=$all[$zx][$app_5sim]["virtual4"][count];
$price=$all[$zx][$app_5sim]["virtual4"][cost];
if($price != null){
$virtual4 = "virtual4";
$virtual41 = "$price ₽| $adds";
}else{
$virtual4 = null;
$virtual41 = null;
}
$adds=$all[$zx][$app_5sim]["virtual5"][count];
$price=$all[$zx][$app_5sim]["virtual5"][cost];
if($price != null){
$virtual5 = "virtual5";
$virtual51 = "$price ₽| $adds";
}else{
$virtual5 = null;
$virtual51 = null;
}
$adds=$all[$zx][$app_5sim]["virtual7"][count];
$price=$all[$zx][$app_5sim]["virtual7"][cost];
if($price != null){
$virtual7 = "virtual7";
$virtual71 = "$price ₽| $adds";
}else{
$virtual7 = null;
$virtual71 = null;
}
$adds=$all[$zx][$app_5sim]["virtual8"][count];
$price=$all[$zx][$app_5sim]["virtual8"][cost];
if($price != null){
$virtual8 = "virtual8";
$virtual81 = "$price ₽| $adds";
}else{
$virtual8 = null;
$virtual81 = null;
}
$adds=$all[$zx][$app_5sim]["virtual12"][count];
$price=$all[$zx][$app_5sim]["virtual12"][cost];
if($price != null){
$virtual12 = "virtual12";
$virtual121 = "$price ₽| $adds";
}else{
$virtual12 = null;
$virtual121 = null;
}
$adds=$all[$zx][$app_5sim]["virtual15"][count];
$price=$all[$zx][$app_5sim]["virtual15"][cost];
if($price != null){
$virtual15 = "virtual15";
$virtual151 = "$price ₽| $adds";
}else{
$virtual15 = null;
$virtual151 = null;
}
$adds=$all[$zx][$app_5sim]["virtual16"][count];
$price=$all[$zx][$app_5sim]["virtual16"][cost];
if($price != null){
$virtual16 = "virtual16";
$virtual161 = "$price ₽| $adds";
}else{
$virtual16 = null;
$virtual161 = null;
}
$adds=$all[$zx][$app_5sim]["virtual17"][count];
$price=$all[$zx][$app_5sim]["virtual17"][cost];
if($price != null){
$virtual17 = "virtual17";
$virtual171 = "$price ₽| $adds";
}else{
$virtual17 = null;
$virtual171 = null;
}
$adds=$all[$zx][$app_5sim]["virtual18"][count];
$price=$all[$zx][$app_5sim]["virtual18"][cost];
if($price != null){
$virtual18 = "virtual18";
$virtual181 = "$price ₽| $adds";
}else{
$virtual18 = null;
$virtual181 = null;
}
$adds=$all[$zx][$app_5sim]["virtual19"][count];
$price=$all[$zx][$app_5sim]["virtual19"][cost];
if($price != null){
$virtual19 = "virtual19";
$virtual191 = "$price ₽| $adds";
}else{
$virtual19 = null;
$virtual191 = null;
}
$adds=$all[$zx][$app_5sim]["virtual20"][count];
$price=$all[$zx][$app_5sim]["virtual20"][cost];
if($price != null){
$virtual20 = "virtual20";
$virtual201 = "$price ₽| $adds";
}else{
$virtual20 = null;
$virtual201 = null;
}
$adds=$all[$zx][$app_5sim]["virtual21"][count];
$price=$all[$zx][$app_5sim]["virtual21"][cost];
if($price != null){
$virtual21a = "virtual21";
$virtual211 = "$price ₽| $adds";
}else{
$virtual21a = null;
$virtual211 = null;
}
$adds=$all[$zx][$app_5sim]["virtual22"][count];
$price=$all[$zx][$app_5sim]["virtual22"][cost];
if($price != null){
$virtual22 = "virtual22";
$virtual221 = "$price ₽| $adds";
}else{
$virtual22 = null;
$virtual221 = null;
}
$adds=$all[$zx][$app_5sim]["virtual23"][count];
$price=$all[$zx][$app_5sim]["virtual23"][cost];
if($price != null){
$virtual23 = "virtual23";
$virtual231 = "$price ₽| $adds";
}else{
$virtual23 = null;
$virtual231 = null;
}
$adds=$all[$zx][$app_5sim]["virtual24"][count];
$price=$all[$zx][$app_5sim]["virtual24"][cost];
if($price != null){
$virtual24 = "virtual24";
$virtual241 = "$price ₽| $adds";
}else{
$virtual24 = null;
$virtual241 = null;
}
$adds=$all[$zx][$app_5sim]["virtual25"][count];
$price=$all[$zx][$app_5sim]["virtual25"][cost];
if($price != null){
$virtual25 = "virtual25";
$virtual251 = "$price ₽| $adds";
}else{
$virtual25 = null;
$virtual251 = null;
}
$adds=$all[$zx][$app_5sim]["virtual26"][count];
$price=$all[$zx][$app_5sim]["virtual26"][cost];
if($price != null){
$virtual26 = "virtual26";
$virtual261 = "$price ₽| $adds";
}else{
$virtual26 = null;
$virtual261 = null;
}
$adds=$all[$zx][$app_5sim]["virtual27"][count];
$price=$all[$zx][$app_5sim]["virtual27"][cost];
if($price != null){
$virtual27 = "virtual27";
$virtual271 = "$price ₽| $adds";
}else{
$virtual27 = null;
$virtual271 = null;
}
$adds=$all[$zx][$app_5sim]["virtual28"][count];
$price=$all[$zx][$app_5sim]["virtual28"][cost];
if($price != null){
$virtual28 = "virtual28";
$virtual281 = "$price ₽| $adds";
}else{
$virtual28 = null;
$virtual281 = null;
}
$adds=$all[$zx][$app_5sim]["virtual29"][count];
$price=$all[$zx][$app_5sim]["virtual29"][cost];
if($price != null){
$virtual29 = "virtual29";
$virtual291 = "$price ₽| $adds";
}else{
$virtual29 = null;
$virtual291 = null;
}
$adds=$all[$zx][$app_5sim]["virtual30"][count];
$price=$all[$zx][$app_5sim]["virtual30"][cost];
if($price != null){
$virtual30 = "virtual30";
$virtual301 = "$price ₽| $adds";
}else{
$virtual30 = null;
$virtual301 = null;
}
$adds=$all[$zx][$app_5sim]["virtual31"][count];
$price=$all[$zx][$app_5sim]["virtual31"][cost];
if($price != null){
$virtual31 = "virtual31";
$virtual311 = "$price ₽| $adds";
}else{
$virtual31 = null;
$virtual311 = null;
}
$adds=$all[$zx][$app_5sim]["virtual32"][count];
$price=$all[$zx][$app_5sim]["virtual32"][cost];
if($price != null){
$virtual32 = "virtual32";
$virtual321 = "$price ₽| $adds";
}else{
$virtual32 = null;
$virtual321 = null;
}
$adds=$all[$zx][$app_5sim]["virtual33"][count];
$price=$all[$zx][$app_5sim]["virtual33"][cost];
if($price != null){
$virtual33 = "virtual33";
$virtual331 = "$price ₽| $adds";
}else{
$virtual33 = null;
$virtual331 = null;
}
$adds=$all[$zx][$app_5sim]["virtual34"][count];
$price=$all[$zx][$app_5sim]["virtual34"][cost];
if($price != null){
$virtual34 = "virtual34";
$virtual341 = "$price ₽| $adds";
}else{
$virtual34 = null;
$virtual341 = null;
}
$adds=$all[$zx][$app_5sim]["virtual35"][count];
$price=$all[$zx][$app_5sim]["virtual35"][cost];
if($price != null){
$virtual35 = "virtual35";
$virtual351 = "$price ₽| $adds";
}else{
$virtual35 = null;
$virtual351 = null;
}
$adds=$all[$zx][$app_5sim]["virtual36"][count];
$price=$all[$zx][$app_5sim]["virtual36"][cost];
if($price != null){
$virtual36 = "virtual36";
$virtual361 = "$price ₽| $adds";
}else{
$virtual36 = null;
$virtual361 = null;
}
$adds=$all[$zx][$app_5sim]["virtual37"][count];
$price=$all[$zx][$app_5sim]["virtual37"][cost];
if($price != null){
$virtual37 = "virtual37";
$virtual371 = "$price ₽| $adds";
}else{
$virtual37 = null;
$virtual371 = null;
}
$adds=$all[$zx][$app_5sim]["vodafone"][count];
$price=$all[$zx][$app_5sim]["vodafone"][cost];
if($price != null){
$vodafone = "vodafone";
$vodafone1 = "$price ₽| $adds";
}else{
$vodafone = null;
$vodafone1 = null;
}
$adds=$all[$zx][$app_5sim]["yota"][count];
$price=$all[$zx][$app_5sim]["yota"][cost];
if($price != null){
$yota = "yota";
$yota1 = "$price ₽| $adds";
}else{
$yota = null;
$yota1 = null;
}
$adds=$all[$zx][$app_5sim]["zz"][count];
$price=$all[$zx][$app_5sim]["zz"][cost];
if($price != null){
$zz = "zz";
$zz1 = "$price ₽| $adds";
}else{
$zz = null;
$zz1 = null;
}
if($zz == null and $yota == null and $vodafone == null and $virtual32 == null and $virtual31 == null and $virtual30 == null and $virtual29 == null and $virtual28 == null and $virtual27 == null and $virtual26 == null and $virtual25 == null and $virtual24 == null and $virtual23 == null and $virtual22 == null and $virtual21a == null and $virtual20 == null and $virtual19 == null and $virtual18 == null and $virtual17 == null and $virtual16 == null and $virtual15 == null and $virtual12 == null and $virtual8 == null and $virtual7 == null and $virtual5 == null and $virtual4 == null and $virtual2 == null and $virginmobile == null and $tnt == null and $tmobile == null and $tigo == null and $three == null and $tele2 == null and $sun == null and $smart == null and $rostelecom == null and $redbullmobile == null and $play == null and $pildyk == null and $orange == null and $mts == null and $megafon == null and $matrix == null and $lycamobile == null and $kcell == null and $globe == null and $ee == null and $claro == null and $beeline == null and $altel == null and $activ == null and $bee019 == null){
$adds=json_decode(file_get_contents("https://5sim.biz/v1/guest/products/".$zx."/".any))->{$app_5sim}->Qty;
$price=json_decode(file_get_contents("https://5sim.biz/v1/guest/products/".$zx."/".any))->{$app_5sim}->Price;
if($price != null){
$any = "any";
$any1 = "$price ₽| $adds";
}else{
$any = null;
$any1 = null;
}
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ يتم الان اضافه المعلومات 🛎•
➖ الدولة : $name •
➖ التطبيق :  $APP 🌐•
➖ الموقع :  $API ♻️•

        🔰    
➖➖➖➖➖➖
🅿️ - قم ب إختيار السيرفر لهذه الدولة ⏬
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$any1",'callback_data'=>"addprice-$app-$add-$api-$country-$any"],['text'=>"$any",'callback_data'=>"addprice-$app-$add-$api-$country-$any"]],
[['text'=>"$bee0191",'callback_data'=>"addprice-$app-$add-$api-$country-$bee019"],['text'=>"$bee019",'callback_data'=>"addprice-$app-$add-$api-$country-$bee019"]],
[['text'=>"$activ1",'callback_data'=>"addprice-$app-$add-$api-$country-$activ"],['text'=>"$activ",'callback_data'=>"addprice-$app-$add-$api-$country-$activ"]],
[['text'=>"$altel1",'callback_data'=>"addprice-$app-$add-$api-$country-$altel"],['text'=>"$altel",'callback_data'=>"addprice-$app-$add-$api-$country-$altel"]],
[['text'=>"$beeline1",'callback_data'=>"addprice-$app-$add-$api-$country-$beeline"],['text'=>"$beeline",'callback_data'=>"addprice-$app-$add-$api-$country-$beeline"]],
[['text'=>"$claro1",'callback_data'=>"addprice-$app-$add-$api-$country-$claro"],['text'=>"$claro",'callback_data'=>"addprice-$app-$add-$api-$country-$claro"]],
[['text'=>"$ee1",'callback_data'=>"addprice-$app-$add-$api-$country-$ee"],['text'=>"$ee",'callback_data'=>"addprice-$app-$add-$api-$country-$ee"]],
[['text'=>"$globe1",'callback_data'=>"addprice-$app-$add-$api-$country-$globe"],['text'=>"$globe",'callback_data'=>"addprice-$app-$add-$api-$country-$globe"]],
[['text'=>"$kcell1",'callback_data'=>"addprice-$app-$add-$api-$country-$kcell"],['text'=>"$kcell",'callback_data'=>"addprice-$app-$add-$api-$country-$kcell"]],
[['text'=>"$lycamobile1",'callback_data'=>"addprice-$app-$add-$api-$country-$lycamobile"],['text'=>"$lycamobile",'callback_data'=>"addprice-$app-$add-$api-$country-$lycamobile"]],
[['text'=>"$matrix1",'callback_data'=>"addprice-$app-$add-$api-$country-$matrix"],['text'=>"$matrix",'callback_data'=>"addprice-$app-$add-$api-$country-$matrix"]],
[['text'=>"$megafon1",'callback_data'=>"addprice-$app-$add-$api-$country-$megafon"],['text'=>"$megafon",'callback_data'=>"addprice-$app-$add-$api-$country-$megafon"]],
[['text'=>"$mts1",'callback_data'=>"addprice-$app-$add-$api-$country-$mts"],['text'=>"$mts",'callback_data'=>"addprice-$app-$add-$api-$country-$mts"]],
[['text'=>"$orange1",'callback_data'=>"addprice-$app-$add-$api-$country-$orange"],['text'=>"$orange",'callback_data'=>"addprice-$app-$add-$api-$country-$orange"]],
[['text'=>"$pildyk1",'callback_data'=>"addprice-$app-$add-$api-$country-$pildyk"],['text'=>"$pildyk",'callback_data'=>"addprice-$app-$add-$api-$country-$pildyk"]],
[['text'=>"$play1",'callback_data'=>"addprice-$app-$add-$api-$country-$play"],['text'=>"$play",'callback_data'=>"addprice-$app-$add-$api-$country-$play"]],
[['text'=>"$redbullmobile1",'callback_data'=>"addprice-$app-$add-$api-$country-$redbullmobile"],['text'=>"$redbullmobile",'callback_data'=>"addprice-$app-$add-$api-$country-$redbullmobile"]],
[['text'=>"$rostelecom1",'callback_data'=>"addprice-$app-$add-$api-$country-$rostelecom"],['text'=>"$rostelecom",'callback_data'=>"addprice-$app-$add-$api-$country-$rostelecom"]],
[['text'=>"$smart1",'callback_data'=>"addprice-$app-$add-$api-$country-$smart"],['text'=>"$smart",'callback_data'=>"addprice-$app-$add-$api-$country-$smart"]],
[['text'=>"$sun1",'callback_data'=>"addprice-$app-$add-$api-$country-$sun"],['text'=>"$sun",'callback_data'=>"addprice-$app-$add-$api-$country-$sun"]],
[['text'=>"$tele21",'callback_data'=>"addprice-$app-$add-$api-$country-$tele2"],['text'=>"$tele2",'callback_data'=>"addprice-$app-$add-$api-$country-$tele2"]],
[['text'=>"$three1",'callback_data'=>"addprice-$app-$add-$api-$country-$three"],['text'=>"$three",'callback_data'=>"addprice-$app-$add-$api-$country-$three"]],
[['text'=>"$tigo1",'callback_data'=>"addprice-$app-$add-$api-$country-$tigo"],['text'=>"$tigo",'callback_data'=>"addprice-$app-$add-$api-$country-$tigo"]],
[['text'=>"$tmobile1",'callback_data'=>"addprice-$app-$add-$api-$country-$tmobile"],['text'=>"$tmobile",'callback_data'=>"addprice-$app-$add-$api-$country-$tmobile"]],
[['text'=>"$tnt1",'callback_data'=>"addprice-$app-$add-$api-$country-$tnt"],['text'=>"$tnt",'callback_data'=>"addprice-$app-$add-$api-$country-$tnt"]],
[['text'=>"$virginmobile1",'callback_data'=>"addprice-$app-$add-$api-$country-$virginmobile"],['text'=>"$virginmobile",'callback_data'=>"addprice-$app-$add-$api-$country-$virginmobile"]],
[['text'=>"$virtual21",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual2"],['text'=>"$virtual2",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual2"]],
[['text'=>"$virtual41",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual4"],['text'=>"$virtual4",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual4"]],
[['text'=>"$virtual51",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual5"],['text'=>"$virtual5",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual5"]],
[['text'=>"$virtual71",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual7"],['text'=>"$virtual7",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual7"]],
[['text'=>"$virtual81",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual8"],['text'=>"$virtual8",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual8"]],
[['text'=>"$virtual121",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual12"],['text'=>"$virtual12",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual12"]],
[['text'=>"$virtual151",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual15"],['text'=>"$virtual15",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual15"]],
[['text'=>"$virtual161",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual16"],['text'=>"$virtual16",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual16"]],
[['text'=>"$virtual171",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual17"],['text'=>"$virtual17",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual17"]],
[['text'=>"$virtual181",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual18"],['text'=>"$virtual18",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual18"]],
[['text'=>"$virtual191",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual19"],['text'=>"$virtual19",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual19"]],
[['text'=>"$virtual201",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual20"],['text'=>"$virtual20",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual20"]],
[['text'=>"$virtual211",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual21a"],['text'=>"$virtual21a",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual21a"]],
[['text'=>"$virtual221",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual22"],['text'=>"$virtual22",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual22"]],
[['text'=>"$virtual231",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual23"],['text'=>"$virtual23",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual23"]],
[['text'=>"$virtual241",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual24"],['text'=>"$virtual24",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual24"]],
[['text'=>"$virtual251",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual25"],['text'=>"$virtual25",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual25"]],
[['text'=>"$virtual261",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual26"],['text'=>"$virtual26",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual26"]],
[['text'=>"$virtual271",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual27"],['text'=>"$virtual27",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual27"]],
[['text'=>"$virtual281",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual28"],['text'=>"$virtual28",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual28"]],
[['text'=>"$virtual291",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual29"],['text'=>"$virtual29",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual29"]],
[['text'=>"$virtual301",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual30"],['text'=>"$virtual30",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual30"]],
[['text'=>"$virtual311",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual31"],['text'=>"$virtual31",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual31"]],
[['text'=>"$virtual321",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual32"],['text'=>"$virtual32",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual32"]],
[['text'=>"$virtual331",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual33"],['text'=>"$virtual33",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual33"]],
[['text'=>"$virtual341",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual34"],['text'=>"$virtual34",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual34"]],
[['text'=>"$virtual351",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual35"],['text'=>"$virtual35",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual35"]],
[['text'=>"$virtual361",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual36"],['text'=>"$virtual36",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual36"]],
[['text'=>"$virtual371",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual37"],['text'=>"$virtual37",'callback_data'=>"addprice-$app-$add-$api-$country-$virtual37"]],
[['text'=>"$vodafone1",'callback_data'=>"addprice-$app-$add-$api-$country-$vodafone"],['text'=>"$vodafone",'callback_data'=>"addprice-$app-$add-$api-$country-$vodafone"]],
[['text'=>"$yota1",'callback_data'=>"addprice-$app-$add-$api-$country-$yota"],['text'=>"$yota",'callback_data'=>"addprice-$app-$add-$api-$country-$yota"]],
[['text'=>"$zz1",'callback_data'=>"addprice-$app-$add-$api-$country-$zz"],['text'=>"$zz",'callback_data'=>"addprice-$app-$add-$api-$country-$zz"]],
[['text'=>'رجوع','callback_data'=>"iA-$app-$add-$api"]]
]
])
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}
#=========={servicPrice}==========#
if($exdata[0] == "addprice"){
$app=$exdata[1];
$add=$exdata[2];
$api=$exdata[3];
$country=$exdata[4];
$operator=$exdata[5];
$array=["019","three","virtual2"];
if($api == "Ai" and in_array($operator,$array)){
$operator = str_replace(["019","three","virtual2"],["94","114","119"],$operator);
}else{
$operator = str_replace(["any","activ","altel","beeline","claro","ee","globe","kcell","lycamobile","matrix","megafon","mts","orange","pildyk","play","redbullmobile","rostelecom","smart","sun","tele2","tigo","tmobile","tnt","virginmobile","virtual4","virtual5","virtual7","virtual8","virtual12","virtual15","virtual16","virtual17","virtual18","virtual19","virtual20","virtual21","virtual22","virtual23","virtual24","virtual25","virtual26","virtual27","virtual28","virtual29","virtual30","virtual31","virtual32","virtual33","virtual34","virtual35","virtual36","virtual37","vodafone","yota","zz"],["93","95","96","97","98","99","100","101","102","103","104","105","106","107","108","109","110","111","112","113","115","116","117","118","120","121","122","123","124","125","126","127","128","129","130","131","132","133","134","135","136","137","138","139","140","141","142","143","144","145","146","147","148","149","150"],$operator);
}
if($api == "Bi" or $api == "Ci" or $api == "Di" or $api == "Ei" or $api == "Fi" or $api == "Hi" or $api == "Li" or $api == "Mi" or $api == "Ni" or $api == "Oi" or $api == "Qi" or $api == "Si" or $api == "Ti" or $api == "Vi" or $api == "Wi" or $api == "Xi"){
$operator = str_replace(["Bi","Ci","Di","Ei","Fi","Hi","Li","Mi","Ni","Oi","Qi","Si","Ti","Vi"],["1","2","3","4","5","6","7","8","9","10","11","12","13","14"],$api);
}
if($api == "Gi"){
$operator = str_replace(["any","mobifone","vietnamobile","viettel","vinaphone"],["16","17","18","19","20"],$operator);
}
if($api == "Ji"){
$operator = str_replace(["1","3","4","5","7"],["A","B","C","D","E"],$operator);
$operator = str_replace(["A","B","C","D","E"],["48","49","50","51","52"],$operator);
}
if($api == "Ki"){
$operator = str_replace(["MOBIFONE","VINAPHONE","VIETTEL","VIETNAMOBILE","ITELECOM"],["21","22","23","24","25"],$operator);
}
if($api == "Pi"){
$operator = str_replace(["10","11","12","13","14","15","16","17","0","1","2","3","4","5","6","8","9"],["J","K","L","M","N","O","P","Q","A","B","C","D","E","F","G","H","I"],$operator);
$operator = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q"],["26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42"],$operator);
}
if($api == "Ri"){
$operator = str_replace(["3819","3543","3528","3328","2001","3825","3521","3326","2940","2919","3832","3744","3375","3262","3128","3826","3749","3484","3333","3302","2003","3475","3391","3335","2012","3862","3385","2624","3820","3497","3312","3890","3392","2866","3858","3515","3191","3189","3999","2929"],["53","54","55","56","57","58","59","60","61","62","63","64","65","66","67","68","69","70","71","72","73","74","75","76","77","78","79","80","81","82","83","84","85","86","87","88","89","90","91","92"],$operator);
}
if($api == "Ui"){
$operator = str_replace(["MOBIFONE","VINAPHONE","VIETTEL","VIETNAMOBILE","ITELECOM"],["43","44","45","46","47"],$operator);
}
$site = str_replace(["Ai","Bi","Ci","Di","Ei","Fi","Gi","Hi","Ji","Ki","Li","Mi","Ni","Oi","Pi","Qi","Ri","Si","Ti","Ui","Vi"],["5sim","smslive","man","vak","acktiwator","pvapins","botsms","onlinesim","supersmstech","viotp","simsms","grizzly","smscode","tiger","2ndline","store","fastpva","dropsms","24sms7","sellotp","duraincloud"],$api);
$API = str_replace(["Ai","Bi","Ci","Di","Ei","Fi","Gi","Hi","Ji","Ki","Li","Mi","Ni","Oi","Pi","Qi","Ri","Si","Ti","Ui","Vi"],["5sim.biz","smslive.pro","sms-man.ru","Vak-sms.com","sms-acktiwator.ru","pvapins.com","bot-sms.shop","onlinesim.io","supersmstech.com","viotp.com","simsms.org","grizzlysms.com","sms-code.ru","tiger-sms.com","2ndline.io","receivesms.store","sms.fastpva.com","dropsms.ru","24sms7.com","sellotp.com","mm.duraincloud.com"],$api);
if($add >=1 and $add <=14){
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
}else{
$status = $add;
}
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز"],$status);
$name = $_co['country'][$country];
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
if($status=="200"){
$price=$api_price[price];
}elseif($status=="0"){
$price = "❌";
}
$keybo=str_replace(["Ai","Bi","Ci","Di","Ei","Fi","Gi","Hi","Ji","Ki","Li","Mi","Ni","Oi","Pi","Qi","Ri","Si","Ti","Ui","Vi"],["iA","iA","iA","iB","iC","iD","iE","iF","iG","iE","iH","iJ","iK","iL","iM","iN","iO","iP","iQ","iE","iR"],$api);
$code = "$country$app$operator$add";
$code = md5($code);
if($price == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
⛔️ - عذرا هذه الدولة ليست متوفر في الموقع ♻️
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($buy['number'][$code] != null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
⛔️ - عذرا هذه الدولة قد قمت ب إضافتها من قبل في البوت
♻️ - لاتستطيع إضافة نفس الدولة إلى البوت
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ يتم الان اضافه المعلومات 🛎•
➖ الدولة : $name •
➖ التطبيق :  $APP 🌐•
➖سعر الرقم بالموقع : $price ☎️•
➖ الموقع :  $API ♻️•
➖قم بإرسال السعر الان 💙•

        🔰
        
➖➖➖➖➖➖
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'رجوع','callback_data'=>"$keybo-$app-$add-$api"]]
]
])
]);
unlink("zzz.json");
unlink("data/id/$id/step.txt");
$zzz[mode][$chat_id]="addprice";
$zzz[api]=$api;
$zzz[add]=$add;
$zzz[app]=$app;
$zzz[country]=$country;
$zzz[site]=$site;
$zzz[API]=$API;
$zzz[operator]=$operator;
zzz();
}
}
if($text != '/start' && $text && $zzz[mode][$chat_id]=="addprice"){
if($text>0){
$app=$zzz[app];
$add=$zzz[add];
$api=$zzz[api];
$country=$zzz[country];
$site=$zzz[site];
$API=$zzz[API];
$exn=explode(".", $text);
if($exn[1] > 0){
$price="$text"."0";
}else{
$price="$exn[0].00";
}
$operator=$zzz[operator];
if($add >=1 and $add <=14){
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
}else{
$status = $add;
}
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز"],$status);
$APP_S = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["WhatsApp","Telegram","Facebook","Instagram","Twitter","Tiktok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);#APP
$name = $_co['country'][$country];
$i=$random[$num]['add'];
if($i==null){
$i=0;
}
$keybo=str_replace(["Ai","Bi","Ci","Di","Ei","Fi","Gi","Hi","Ji","Ki","Li","Mi","Ni","Oi","Pi","Qi","Ri","Si","Ti","Ui","Vi"],["iA","iA","iA","iB","iC","iD","iE","iF","iG","iE","iH","iJ","iK","iL","iM","iN","iO","iP","iQ","iE","iR"],$api);
$code = "$country$app$operator$add";
$code = md5($code);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
➖ تم اضافه المعلومات بنجاح 🛎•
➖️التفاصيل ↙️•
➖ الدولة : $name •
➖ التطبيق :  $APP 🌐•
➖ سعر الرقم في بوتك : $price ☎️•
➖ الموقع :  $API ♻️•

        🔰
        
➖➖➖➖➖➖
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'رجوع','callback_data'=>"$keybo-$app-$add-$api"]]
]
])
]);
$buy['number'][$code]['country'] = $country;
$buy['number'][$code]['app'] = $app;
$buy['number'][$code]['operator'] = $operator;
$buy['number'][$code]['api'] = $api;
$buy['number'][$code]['site'] = $site;
$buy['number'][$code]['add'] = $add;
$buy['number'][$code]['name'] = $name;
$buy['number'][$code]['APP'] = $APP_S;
$buy['number'][$code]['Type'] = "not_directly";
$buy['number'][$code]['price'] = $price;
$buy['country_app']["$add-$country"] += 1;
Addserver($buy);
if($add >= 21 and $add <= 30){
$random[$add]['zero'][$i] = $code;
$random[$add]['idd'][$code] = $i;
$random[$add]['add'] += 1;
Save($random);
}
unlink("zzz.json");
unlink("data/id/$id/step.txt");
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
عذرا قم بإرسال رقم أكبر من الصفر ❌
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'رجوع','callback_data'=>'addnumber']]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={حذف الدول}==========#
if($data == "delnumber"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🎬 - قم ب إختار التطبيق الذي تود حذف الدولة من البوت ❌
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- السيرفر العام','callback_data'=>"delcon-14"]],
[['text'=>'- تيليجرام','callback_data'=>"delcon-3"],['text'=>'- واتسأب','callback_data'=>"delcon-2"]],
[['text'=>'- إنستقرام','callback_data'=>"delcon-5"],['text'=>'- فيسبوك','callback_data'=>"delcon-4"]],
[['text'=>'- تيكتوك','callback_data'=>"delcon-7"],['text'=>'- تويتر','callback_data'=>"delcon-6"]],
[['text'=>'- إيمو','callback_data'=>"delcon-9"],['text'=>'- قوقل','callback_data'=>"delcon-8"]],
[['text'=>'- سناب','callback_data'=>"delcon-11"],['text'=>'- فايبر','callback_data'=>"delcon-10"]],
[['text'=>'- حراج','callback_data'=>"delcon-13"],['text'=>'- نيتفلكس','callback_data'=>"delcon-12"]],
[['text'=>'♻️ سيرفر عشوائي [ WhatsApp and Telegram ]','callback_data'=>"Wdele"]],
[['text'=>'قسم عروض تيليجرام 🖇','callback_data'=>"Cdelw"]],
[['text'=>'- قسم عروض واتساب ','callback_data'=>"delcon-1"]],
[['text'=>'رجوع','callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={الدول}==========#
if($exdata[0] == "delcon"){
$add=$exdata[1];
$con=$exdata[2];
if($con==null){
$con=1;
}
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام"],$status);
if($con == 1) { $continent     = $AdminONE;  }
if($con == 2) { $continent     = $AdminTOW;  }
if($con == 3) { $continent     = $AdminTHREE;  }
if($con == 4) { $continent     = $AdminFOUR;  }
if($con == 5) { $continent     = $AdminFIVE;  }
if($con == 6) { $continent     = $AdminSIX;  }
if($con == 7) { $continent     = $AdminSEVEN;  }
if($con == 8) { $continent     = $AdminEIGHT;  }
if($continent == null){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🎬 - قم ب إختار التطبيق الذي تود حذف الدولة من البوت ❌
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- السيرفر العام','callback_data'=>"delcon-14"]],
[['text'=>'- تيليجرام','callback_data'=>"delcon-3"],['text'=>'- واتسأب','callback_data'=>"delcon-2"]],
[['text'=>'- إنستقرام','callback_data'=>"delcon-5"],['text'=>'- فيسبوك','callback_data'=>"delcon-4"]],
[['text'=>'- تيكتوك','callback_data'=>"delcon-7"],['text'=>'- تويتر','callback_data'=>"delcon-6"]],
[['text'=>'- إيمو','callback_data'=>"delcon-9"],['text'=>'- قوقل','callback_data'=>"delcon-8"]],
[['text'=>'- سناب','callback_data'=>"delcon-11"],['text'=>'- فايبر','callback_data'=>"delcon-10"]],
[['text'=>'- حراج','callback_data'=>"delcon-13"],['text'=>'- نيتفلكس','callback_data'=>"delcon-12"]],
[['text'=>'♻️ سيرفر عشوائي [ WhatsApp and Telegram ]','callback_data'=>"Wdele"]],
[['text'=>'🐬 - سيرفر واتسأب + تليجرام المُـمـيز','callback_data'=>"Cdelw"]],
[['text'=>'- قسم العروض','callback_data'=>"delcon-1"]],
[['text'=>'رجوع','callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
if($con >= 8){
$u = $con-1;
}elseif($con >= 1){
$o = $con+1;
$u = $con-1;
}
if($con == 1){
$t = "التالي. ⬅️";
$s = null;
$v = "الأخير. 🔀";
}elseif($con == 2 or $con == 3 or $con == 4 or $con == 5 or $con == 6 or $con == 7){
$t = "التالي. ⬅️";
$s = "➡️ السابق.";
$v = null;
$n = null;
}elseif($con == 8){
$t = null;
$s = "➡️ السابق.";
$n = "الأول. ⏭";
}
$i = 0;
$e = 0;
$key     = [];
foreach($continent as $country=>$val){
$co = $val;
$name      = $country;
$e++;
$key['inline_keyboard'][$i][]  =   ["text"=>$name,"callback_data"=>"delenu-$add-$co-$con"];
if($e%3 == 0) $i++;
}
$key['inline_keyboard'][0] = [['text'=>'☑️ - جميع الدول في الأسفل ⬇️','callback_data'=>"no"]];
$key['inline_keyboard'][] = [['text'=>"$t",'callback_data'=>"delcon-$add-$o"],['text'=>"$s",'callback_data'=>"delcon-$add-$u"],['text'=>"$v",'callback_data'=>"delcon-$add-8"],['text'=>"$n",'callback_data'=>"delcon-$add-1"]];
$key['inline_keyboard'][] = [['text'=>'رجوع','callback_data'=>"delnumber"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
✳️ - إختر الدولة التي تريد حذفها من البوت ❌
☑️ -في الكيبورد أدناه 👇
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
}
#=========={حذف الدول}==========#
if($exdata[0] == "delenu"){
$add=$exdata[1];
$co=$exdata[2];
$con=$exdata[3];
$APP = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","العروض","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$key     = [];
$key['inline_keyboard'][] = [['text'=>'🌐 الموقع','callback_data'=>'no'],['text'=>'💰 السعر','callback_data'=>'no'],['text'=>'⛔️','callback_data'=>'no']];
foreach($buy['number'] as $zero=>$num){
if($num['add'] == $add and $num['country'] == $co){
$price=$num['price'];
$name = $_co['country'][$co];
$app = $num['app'];
$operator = $num['operator'];
$site = $num['site'];
$code = $zero;
$key['inline_keyboard'][] = [['text'=>"$site",'callback_data'=>"look-$code"],['text'=>"$price ₽",'callback_data'=>"priamence-$code"],['text'=>"🗑",'callback_data'=>"GODelsnum-$code"]];
}
}
$key['inline_keyboard'][] = [['text'=>'رجوع','callback_data'=>"delcon-$add-$con"]];
$keyboad      = json_encode($key);
if($site == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
انت لم تقم ب إضافة سيرفرات لتطبيق $APP في الوقت الحالي 😅
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️ الدولة: $name •
➖️ التطبيق: $APP ♾•

➖️ إضغط على اسم الموقع لرؤية نوع السيرفر ✅️•
➖️ اضغك على السعر لتغيير سعر الدولة ♻️•
➖️ إضغط على [🗑] لحذف السيرفر 🔰•

➖️➖️➖️⚜️➖️➖️➖️
",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
}
#=========={السيرفرات العشوائية}==========#
if($data == "Wdele"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - إختر القسم الذي تود الحذف منه.
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'♻️ سيرفر عشوائي [ WhatsApp ] رقم 1','callback_data'=>"Wdelse-21"]],
[['text'=>'♻️ سيرفر عشوائي [ WhatsApp ] رقم 2','callback_data'=>"Wdelse-22"]],
[['text'=>'♻️ سيرفر عشوائي [ Telegram ] رقم 1','callback_data'=>"Wdelse-26"]],
[['text'=>'♻️ سيرفر عشوائي [ Telegram ] رقم 2','callback_data'=>"Wdelse-27"]],
[['text'=>'رجوع','callback_data'=>"delnumber"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={السيرفرات العشوائية}==========#
if($exdata[0] == "Wdelse"){
$add=$exdata[1];
$APP = str_replace(["21","22","23","24","25","26","27","28","29","30"],["واتسأب 1","واتساب 2","واتساب 3","واتساب 4","واتساب 5","تيليجرام 1","تيليجرام 2","تيليجرام 3","تيليجرام 4","تيليجرام 5"],$add);
$key     = [];
$key['inline_keyboard'][] = [['text'=>'🖌','callback_data'=>'no'],['text'=>'?? الموقع','callback_data'=>'no'],['text'=>'☑️ الدولة','callback_data'=>'no'],['text'=>'💰 السعر','callback_data'=>'no'],['text'=>'🖌','callback_data'=>'no']];
foreach($buy['number'] as $zero=>$num){
if($num['add'] == $add){
$oop++;
if($oop >= 15){
break;
}
$country = $num['country'];
if(15 < count($buy[country_app]["$add-$country"])){
$to="⬅️ التالي ⬅️";
}
$i = $random[$add]['idd'][$zero];
$price=$num['price'];
$country = $num['country'];
$name = $_co['country'][$country];
$site = $num['site'];
$code=$zero;
$key['inline_keyboard'][] = [['text'=>"➖ $i",'callback_data'=>"idameni2-$code-del"],['text'=>"$site",'callback_data'=>"look-$code"],['text'=>"$name",'callback_data'=>"GODelsnum-$code-$idd"],['text'=>"$price ₽",'callback_data'=>"priamence-$code"],['text'=>"➕ $i",'callback_data'=>"idameni2-$code-add"]];
}
}
$key['inline_keyboard'][] = [['text'=>"$to",'callback_data'=>"Wd2else-$add-2"]];
$key['inline_keyboard'][] = [['text'=>'تحديث ♻️','callback_data'=>"$data"]];
$key['inline_keyboard'][] = [['text'=>'رجوع','callback_data'=>"Wdele"]];
$keyboad      = json_encode($key);
if($site == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
انت لم تقم ب إضافة سيرفرات عشوائي لل$APP في الوقت الحالي 😅
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🌐 - إختر الدولة الذي تريد حذفها من السيرفر عشوائي ل$APP ♻️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
}
if($exdata[0] == "Wd2else"){
$add=$exdata[1];
$num=$exdata[2];
$jk = $num * 15;
 $oj = $num - 1;
 $fj = $oj * 15;
 $kb = $num + 1;
 $jj = $jk - 15;
 $ze='0';
$APP = str_replace(["21","22","23","24","25","26","27","28","29","30"],["واتسأب 1","واتساب 2","واتساب 3","واتساب 4","واتساب 5","تيليجرام 1","تيليجرام 2","تيليجرام 3","تيليجرام 4","تيليجرام 5"],$add);
 if($ze >= $jk){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- حدث خطأ برمجي قم ب الرجوع.
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'رجوع','callback_data'=>"Wdele"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
$key     = [];
$key['inline_keyboard'][] = [['text'=>'🖌','callback_data'=>'no'],['text'=>'🌐 الموقع','callback_data'=>'no'],['text'=>'☑️ الدولة','callback_data'=>'no'],['text'=>'💰 السعر','callback_data'=>'no'],['text'=>'🖌','callback_data'=>'no']];
foreach($buy['number'] as $zero=>$num){
$country = $num['country'];
if($jj > count($buy[country_app]["$add-$country"])){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
❌ - لاتوجد صفحة أخرى حالياً 🙄
",
'show_alert'=>true,
]);
unlink("data/id/$id/step.txt");
exit;
}
if($num['add'] == $add){
$oop++;
if($oop >= $jk){
break;
}
if($oop >= $fj){
if($oop > 15){
$ba="➡️ السابق ➡️";
}
if($jk < count($buy[country_app]["$add-$country"])){
$to="⬅️ التالي ⬅️";
}
$i = $random[$add]['idd'][$zero];
$price=$num['price'];
$country = $num['country'];
$name = $_co['country'][$country];
$site = $num['site'];
$code=$zero;
$key['inline_keyboard'][] = [['text'=>"➖ $i",'callback_data'=>"idameni2-$code-del"],['text'=>"$site",'callback_data'=>"look-$code"],['text'=>"$name",'callback_data'=>"GODelsnum-$code-$idd"],['text'=>"$price ₽",'callback_data'=>"priamence-$code"],['text'=>"➕ $i",'callback_data'=>"idameni2-$code-add"]];
}
}
}
$key['inline_keyboard'][] = [['text'=>"$to",'callback_data'=>"Wd2else-$add-$kb"],['text'=>"$ba",'callback_data'=>"Wd2else-$add-$oj"]];
$key['inline_keyboard'][] = [['text'=>'تحديث ♻️','callback_data'=>"$data"]];
$key['inline_keyboard'][] = [['text'=>'رجوع','callback_data'=>"Wdele"]];
$keyboad      = json_encode($key);
if($site == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
انت لم تقم ب إضافة سيرفرات عشوائي لل$APP في الوقت الحالي 😅
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🌐 - إختر الدولة الذي تريد حذفها من السيرفر عشوائي ل$APP ♻️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
}
#=========={تعديل السيرفرات}==========#
if($exdata[0] == "idameni2"){
$zero = $exdata[1];
$type = $exdata[2];
$zero = md5($zero);
$add = $buy['number'][$zero]['add'];
$idd=$random[$add]['idd'][$zero];
if($type == "add"){
$idd=$idd+1;
$random[$add]['idd'][$zero] += 1;
$random[$add]['zero'][$idd] = $zero;
Save($random);
}elseif($type == "del"){
$idd=$idd-1;
$random[$add]['idd'][$zero] -= 1;
$random[$add]['zero'][$idd] = $zero;
Save($random);
}
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
☑️ - تم تعديل رقم السيرفر إلى $idd
♻️ - قم بعمل للصفحة تحديث كي يتم تعديل رقم السيرفر
",
'show_alert'=>true
]);
exit;
}
#=========={سيرفرات المميز}==========#
if($data == "Cdelw"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️ قسم عروض تيليجرام 📮
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'➖️ عروض تيليجرام ⭐️','callback_data'=>"Cdelsw-36"]],
[['text'=>'رجوع','callback_data'=>"delnumber"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={سيرفرات المميز}==========#
if($exdata[0] == "Cdelsw"){
$add=$exdata[1];
$APP = str_replace(["31","32","33","34","35","36","37","38","39","40"],["واتسأب 1","واتساب 2","واتساب 3","واتساب 4","واتساب 5","تيليجرام 1","تيليجرام 2","تيليجرام 3","تيليجرام 4","تيليجرام 5"],$add);
$key     = [];
$oop = 0;
$key['inline_keyboard'][] = [['text'=>'🌐 الموقع','callback_data'=>'no'],['text'=>'☑️ الدولة','callback_data'=>'no'],['text'=>'💰 السعر','callback_data'=>'no']];
foreach($buy['number'] as $zero=>$num){
if($num['add'] == $add){
$oop++;

if($oop >= 15){
$to="⬅️ التالي ⬅️";
break;
}
$country = $num['country'];



$price=$num['price'];
$country = $num['country'];
$app = $num['app'];
$operator = $num['operator'];
$name = $_co['country'][$country];
$site = $num['site'];
$code=$zero;
$key['inline_keyboard'][] = [['text'=>"$site",'callback_data'=>"look-$code"],['text'=>"$name",'callback_data'=>"GODelsnum-$code"],['text'=>"$price ₽",'callback_data'=>"priamence-$code"]];
}
}
$key['inline_keyboard'][] = [['text'=>"$to",'callback_data'=>"Cd2elsw-$add-2"]];
$key['inline_keyboard'][] = [['text'=>'رجوع','callback_data'=>"Cdelw"]];
$keyboad      = json_encode($key);
if($site == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
انت لم تقم ب إضافة سيرفرات لتطبيق $APP في الوقت الحالي 😅
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🌐 - إختر الدولة الذي تريد حذفها من سيرفر $APP المميز ♻️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
}
if($exdata[0] == "Cd2elsw"){
$add=$exdata[1];
$num=$exdata[2];
$jk = $num * 15; #30
 $oj = $num - 1; #1
 $fj = $oj * 15; #15
 $kb = $num + 1; #3
 $jj = $jk - 15; #15
 $ze='0';
$APP = str_replace(["31","32","33","34","35","36","37","38","39","40"],["واتسأب 1","واتساب 2","واتساب 3","واتساب 4","واتساب 5","تيليجرام 1","تيليجرام 2","تيليجرام 3","تيليجرام 4","تيليجرام 5"],$add);
 if($ze >= $jk){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- حدث خطأ برمجي قم ب الرجوع.
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'رجوع','callback_data'=>"Cdelw"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
$key     = [];
$Aaaymn = 0;
$key['inline_keyboard'][] = [['text'=>'🌐 الموقع','callback_data'=>'no'],['text'=>'☑️ الدولة','callback_data'=>'no'],['text'=>'💰 السعر','callback_data'=>'no']];
foreach($buy['number'] as $zero=>$num){

if($num['add'] == $add){
$country = $num['country']; 
$Aaaymn +=1;
$oop++;
if($oop >= $jk){
$to="⬅️ التالي ⬅️";
break;
}
if($oop >= $fj){
if($oop > 15){
$ba="➡️ السابق ➡️";
}

if($jj > $Aaaymn){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
❌ - لاتوجد صفحة أخرى حالياً 🙄
",
'show_alert'=>true,
]);
unlink("data/id/$id/step.txt");
exit;
}

$price=$num['price'];
$country = $num['country'];
$name = $_co['country'][$country];
$site = $num['site'];
$operator = $num['operator'];
$app = $num['app'];
$code=$zero;
$key['inline_keyboard'][] = [['text'=>"$site",'callback_data'=>"look-$code"],['text'=>"$name",'callback_data'=>"GODelsnum-$code"],['text'=>"$price ₽",'callback_data'=>"priamence-$code"]];
}
}
}
$key['inline_keyboard'][] = [['text'=>"$to",'callback_data'=>"Cd2elsw-$add-$kb"],['text'=>"$ba",'callback_data'=>"Cd2elsw-$add-$oj"]];
$key['inline_keyboard'][] = [['text'=>'رجوع','callback_data'=>"Cdelw"]];
$keyboad      = json_encode($key);
if($site == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
انت لم تقم ب إضافة سيرفرات عشوائي لل$APP في الوقت الحالي 😅
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🌐 - إختر الدولة الذي تريد حذفها من سيرفر $APP المميز ♻️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
}
#=========={تغيير السعر}==========#
if($exdata[0] == "priamence"){
$zero = $exdata[1];
if(!(strlen($zero) == 32 && ctype_xdigit($zero) && isset($buy['number'][$zero]))){
$zero = md5($zero);
}
$price = $buy['number'][$zero]['price'];
$country = $buy['number'][$zero]['country'];
$add = $buy['number'][$zero]['add'];
if($add >=1 and $add <=14){
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
}else{
$status = $add;
}
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز"],$status);
$ary1=["1","2","3","4","5","6","7","8","9","10","11","12","13","14"];
$ary2=["21","22","23","24","25","26","27","28","29","30"];
$ary3=["31","32","33","34","35","36","37","38","39","40"];
if(in_array($add,$ary1)){
$back="delenu-$add-$country";
}elseif(in_array($add,$ary2)){
$back="Wdelse-$add";
}elseif(in_array($add,$ary3)){
$back="Cdelsw-$add";
}
$name = $_co['country'][$country];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️ الدولة : $name •
➖️ التطبيق : $APP ✅️•
➖️ سعر الدولة الحالي هو : $price ⚠️•
➖️ أرسل السعر الجديد لتغييرة 📮•
               🔰
➖️➖️➖️⚜️➖️➖️➖️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"$back"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","priamence|$zero");
}
if($text && $text != '/start' && $exstep[0] == 'priamence'){
$zero = $exstep[1];
$price = $buy['number'][$zero]['price'];
$country = $buy['number'][$zero]['country'];
$app = $buy['number'][$zero]['app'];
$site = $buy['number'][$zero]['site'];
$add = $buy['number'][$zero]['add'];
if($add >=1 and $add <=14){
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
}else{
$status = $add;
}
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز"],$status);
$ary1=["1","2","3","4","5","6","7","8","9","10","11","12","13","14"];
$ary2=["21","22","23","24","25","26","27","28","29","30"];
$ary3=["31","32","33","34","35","36","37","38","39","40"];
if(in_array($add,$ary1)){
$back="delenu-$add-$country";
}elseif(in_array($add,$ary2)){
$back="Wdelse-$add";
}elseif(in_array($add,$ary3)){
$back="Cdelsw-$add";
}
$name = $_co['country'][$country];
if($text > 0){
$exn=explode(".", $text);
if($exn[1] > 0){
$text="$text"."0";
}else{
$text="$exn[0].00";
}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
➖️ تم تغيير سعر الدوله بنجاح 😍•
➖️ الدولة : $name •
➖️ التطبيق : $APP ✅️•
➖️ سعر الدولة القديم هو : $price ⚠️•
➖️ والسعر الجديد هو : $text 📮•
  •          🔰     •
➖️➖️➖️⚜️➖️➖️➖️
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"$back"]]
]
])
]);
$buy['number'][$zero]['price'] = $text;
Addserver($buy);
unlink("data/id/$id/step.txt");
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚠️ - يرجى إرسال سعر صحيح
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
}
}
#=========={تفاصيل عن الدولة}==========#
if($exdata[0] == "look"){
$zero = $exdata[1];
if(!(strlen($zero) == 32 && ctype_xdigit($zero) && isset($buy['number'][$zero]))){
$zero = md5($zero);
}
$app = $buy['number'][$zero]['app'];
$country = $buy['number'][$zero]['country'];
$site = $buy['number'][$zero]['site'];
$operator = $buy['number'][$zero]['operator'];
$api_price=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$site&country=$country&app=$app&operator=$operator"),1);
$status=$api_price[status];
if($status=="200"){
$price=$api_price[price];
}elseif($status=="0"){
$price = "❌";
}
$operator=$api_price[operator];
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
♻️ - السيرفر: $operator
✅ - السعر في الموقع: $price
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}
#=========={حذف الدولة}==========#
if($exdata[0] == "GODelsnum"){
$zero = $exdata[1];
$idd = $exdata[2];
$code=$zero;
if(!(strlen($zero) == 32 && ctype_xdigit($zero) && isset($buy['number'][$zero]))){
$zero = md5($zero);
}
$price = $buy['number'][$zero]['price'];
$app = $buy['number'][$zero]['app'];
$country = $buy['number'][$zero]['country'];
$add = $buy['number'][$zero]['add'];
$api = $buy['number'][$zero]['api'];
$API = str_replace(["Ai","Bi","Ci","Di","Ei","Fi","Gi","Hi","Ji","Ki","Li","Mi","Ni","Oi","Pi","Qi","Ri","Si","Ti","Ui","Vi"],["5sim.biz","smslive.pro","sms-man.ru","Vak-sms.com","sms-acktiwator.ru","pvapins.com","bot-sms.shop","onlinesim.io","supersmstech.com","viotp.com","simsms.org","grizzlysms.com","sms-code.ru","tiger-sms.com","2ndline.io","receivesms.store","sms.fastpva.com","dropsms.ru","24sms7.com","sellotp.com","mm.duraincloud.com"],$api);
if($add >=1 and $add <=14){
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
}else{
$status = $add;
}
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز"],$status);
$ary1=["1","2","3","4","5","6","7","8","9","10","11","12","13","14"];
$ary2=["21","22","23","24","25","26","27","28","29","30"];
$ary3=["31","32","33","34","35","36","37","38","39","40"];
if(in_array($add,$ary1)){
$back="delenu-$add-$country";
}elseif(in_array($add,$ary2)){
$back="Wdelse-$add";
}elseif(in_array($add,$ary3)){
$back="Cdelsw-$add";
}
$name = $_co['country'][$country];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*➖️ هل انت متأكد بأنك تريد حذف دوله $name ⁉️•

➖️ السعر: ₽ $price 💬•
➖️ الموقع: $API ✅️•
➖️ التطبيق: $APP 👩🏻‍💻*•

➖️➖️➖️⚜️➖️➖️➖️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"☑️ متأكد",'callback_data'=>"Mez-$code-$idd"]],
[['text'=>"رجوع",'callback_data'=>"$back"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
#=========={تأكيد حذف الدولة}==========#
if($exdata[0] == "Mez"){
$zero = $exdata[1];
$idd = $exdata[2];
if(!(strlen($zero) == 32 && ctype_xdigit($zero) && isset($buy['number'][$zero]))){
$zero = md5($zero);
}
$price = $buy['number'][$zero]['price'];
$app = $buy['number'][$zero]['app'];
$country = $buy['number'][$zero]['country'];
$add = $buy['number'][$zero]['add'];
$api = $buy['number'][$zero]['api'];
$API = str_replace(["Ai","Bi","Ci","Di","Ei","Fi","Gi","Hi","Ji","Ki","Li","Mi","Ni","Oi","Pi","Qi","Ri","Si","Ti","Ui","Vi"],["5sim.biz","smslive.pro","sms-man.ru","Vak-sms.com","sms-acktiwator.ru","pvapins.com","bot-sms.shop","onlinesim.io","supersmstech.com","viotp.com","simsms.org","grizzlysms.com","sms-code.ru","tiger-sms.com","2ndline.io","receivesms.store","sms.fastpva.com","dropsms.ru","24sms7.com","sellotp.com","mm.duraincloud.com"],$api);
if($add >=1 and $add <=14){
$status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
}else{
$status = $add;
}
$APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي واتسأب","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","عشوائي تيليجرام","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","واتسأب المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز","تيليجرام المميز"],$status);
$ary1=["1","2","3","4","5","6","7","8","9","10","11","12","13","14"];
$ary2=["21","22","23","24","25","26","27","28","29","30"];
$ary3=["31","32","33","34","35","36","37","38","39","40"];
if(in_array($add,$ary1)){
$back="delenu-$add-$country";
}elseif(in_array($add,$ary2)){
$back="Wdelse-$add";
}elseif(in_array($add,$ary3)){
$back="Cdelsw-$add";
}
$name = $_co['country'][$country];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*تم حذف الدولة هذه بنجاح ✅*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"$back"]]
]
])
]);
unset($buy['number'][$zero]);
Addserver($buy);
$buy['country_app']["$add-$country"] -= 1;
Addserver($buy);
if(in_array($add,$ary2)){
unset($random[$add]['zero'][$idd]);
unset($random[$add]['idd'][$zero]);
$random[$add]['add'] -= 1;
Save($random);
}
unlink("data/id/$id/step.txt");
}
#=========={API}==========#
if($data == 'counapi'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🔰 تستطيع عبر هذه الأزرار رفع وحذف اي API لاي موقع تريده ☑️
",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>'➕ رفع API لموقع معين','callback_data'=>'addapi']],
[['text'=>'✖️ حذف API لموقع معين','callback_data'=>'delapi']],
[['text'=>'رجوع','callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
}
#=========={رفع API}==========#
if($addblusdel['5sim']['add'] == "ok"){
$sim5 = "5sim.biz 🌐";
}else{
$sim5 = "";
}
if($addblusdel['smslive']['add'] == "ok"){
$smslive = "smslive.pro 🌐";
}else{
$smslive = "";
}
if($addblusdel['man']['add'] == "ok"){
$man = "sms-man.ru 🌐";
}else{
$man = "";
}
if($addblusdel['vak']['add'] == "ok"){
$vak = "Vak-sms.com 🌐";
}else{
$vak = "";
}
if($addblusdel['acktiwator']['add'] == "ok"){
$acktiwator = "sms-acktiwator.ru 🌐";
}else{
$acktiwator = "";
}
if($addblusdel['pvapins']['add'] == "ok"){
$pvapins = "pvapins.com 🌐";
}else{
$pvapins = "";
}
if($addblusdel['botsms']['add'] == "ok"){
$botsms = "bot-sms.shop 🌐";
}else{
$botsms = "";
}
if($addblusdel['onlinesim']['add'] == "ok"){
$onlinesim = "onlinesim.io 🌐";
}else{
$onlinesim = "";
}
if($addblusdel['supersmstech']['add'] == "ok"){
$supersmstech = "supersmstech.com 🌐";
}else{
$supersmstech = "";
}
if($addblusdel['viotp']['add'] == "ok"){
$viotp = "viotp.com 🌐";
}else{
$viotp = "";
}
if($addblusdel['simsms']['add'] == "ok"){
$simsms = "simsms.org 🌐";
}else{
$simsms = "";
}
if($addblusdel['grizzly']['add'] == "ok"){
$grizzly = "grizzlysms.com 🌐";
}else{
$grizzly = "";
}
if($addblusdel['smscode']['add'] == "ok"){
$smscode = "sms-code.ru 🌐";
}else{
$smscode = "";
}
if($addblusdel['tiger']['add'] == "ok"){
$tiger = "tiger-sms.com 🌐";
}else{
$tiger = "";
}
if($addblusdel['2ndline']['add'] == "ok"){
$nd2lind = "2ndline.io 🌐";
}else{
$nd2lind = "";
}
if($addblusdel['store']['add'] == "ok"){
$store = "receivesms.store 🌐";
}else{
$store = "";
}
if($addblusdel['fastpva']['add'] == "ok"){
$fastpva = "sms.fastpva.com 🌐";
}else{
$fastpva = "";
}
if($addblusdel['dropsms']['add'] == "ok"){
$dropsms = "dropsms.ru 🌐";
}else{
$dropsms = "";
}
if($addblusdel['24sms7']['add'] == "ok"){
$sms7 = "24sms7.com 🌐";
}else{
$sms7 = "";
}
if($addblusdel['sellotp']['add'] == "ok"){
$sellotp = "sellotp.com 🌐";
}else{
$sellotp = "";
}
if($addblusdel['duraincloud']['add'] == "ok"){
$duraincloud = "mm.duraincloud.com 🌐";
}else{
$duraincloud = "";
}
if($data == 'addapi'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- قم بإختيار الموقع الذي تريد رفع كود ال API إلى البوت*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"$sim5",'callback_data'=>"add5sim"]],
[['text'=>"$smslive",'callback_data'=>"addsmslive"],['text'=>"$man",'callback_data'=>"addman"]],
[['text'=>"$vak",'callback_data'=>"addvaksms"]],
[['text'=>"$acktiwator",'callback_data'=>"addacktiwator"],['text'=>"$pvapins",'callback_data'=>"addpvapins"]],
[['text'=>"$botsms",'callback_data'=>"addbotsms"]],
[['text'=>"$onlinesim",'callback_data'=>"addonlinesim"],['text'=>"$supersmstech",'callback_data'=>"addsupersmstech"]],
[['text'=>"$viotp",'callback_data'=>"addviotp"]],
[['text'=>"$simsms",'callback_data'=>"addsimsms"],['text'=>"$grizzly",'callback_data'=>"addgrizzly"]],
[['text'=>"$smscode",'callback_data'=>"addsmscode"]],
[['text'=>"$tiger",'callback_data'=>"addtiger"],['text'=>"$nd2lind",'callback_data'=>"add2ndline"]],
[['text'=>"$store",'callback_data'=>"addstore"]],
[['text'=>"$fastpva",'callback_data'=>"addfastpva"],['text'=>"$dropsms",'callback_data'=>"adddropsms"]],
[['text'=>"$sms7",'callback_data'=>"add24sms7"]],
[['text'=>"$sellotp",'callback_data'=>"addsellotp"],['text'=>"$duraincloud",'callback_data'=>"addduraincloud"]],
[['text'=>'رجوع','callback_data'=>'counapi']]
]
])
]);
unlink("data/id/$id/step.txt");
}
#=========={5sim.biz}==========#
if($data == 'add5sim'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع 5sim.biz*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","5sim");
}
if($text && $text != '/start' && $step == '5sim'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع 5sim.biz بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS['5sim'][api_key] = $text;
Apps($APPS);
unlink("data/id/$id/step.txt");
}
#=========={smslive.pro}==========#
if($data == 'addsmslive'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع smslive.pro*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","smslive");
}
if($text && $text != '/start' && $step == 'smslive'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع smslive.pro بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[smslive][api_key] = $text;
Apps($APPS);
}
#=========={sms-man.ru}==========#
if($data == 'addman'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع sms-man.ru*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","man");
}
if($text && $text != '/start' && $step == 'man'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع sms-man.ru بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[man][api_key] = $text;
Apps($APPS);
}
#=========={Vak-sms.com}==========#
if($data == 'addvaksms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع vak-sms.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","vak");
}
if($text && $text != '/start' && $step == 'vak'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع vak-sms.com بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[vak][api_key] = $text;
Apps($APPS);
}
#=========={sms-acktiwator.ru}==========#
if($data == 'addacktiwator'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع sms-acktiwator.ru*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","acktiwator");
}
if($text && $text != '/start' && $step == 'acktiwator'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع sms-acktiwator.ru بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[acktiwator][api_key] = $text;
Apps($APPS);
}
#=========={pvapins.com}==========#
if($data == 'addpvapins'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع pvapins.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","pvapins");
}
if($text && $text != '/start' && $step == 'pvapins'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع pvapins.com بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[pvapins][api_key] = $text;
Apps($APPS);
}
#=========={bot-sms.shop}==========#
if($data == 'addbotsms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع bot-sms.shop*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","botsms");
}
if($text && $text != '/start' && $step == 'botsms'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع bot-sms.shop بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[botsms][api_key] = $text;
Apps($APPS);
}
#=========={onlinesim.io}==========#
if($data == 'addonlinesim'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع onlinesim.io*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","onlinesim");
}
if($text && $text != '/start' && $step == 'onlinesim'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع onlinesim.io بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[onlinesim][api_key] = $text;
Apps($APPS);
}
#=========={supersmstech.com}==========#
if($data == 'addsupersmstech'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع supersmstech.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","supersmstech");
}
if($text && $text != '/start' && $step == 'supersmstech'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع supersmstech.com بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[supersmstech][api_key] = $text;
Apps($APPS);
}
#=========={viotp.com}==========#
if($data == 'addviotp'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع viotp.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","viotp");
}
if($text && $text != '/start' && $step == 'viotp'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع viotp.com بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[viotp][api_key] = $text;
Apps($APPS);
}
#=========={simsms.org}==========#
if($data == 'addsimsms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع simsms.org*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","simsms");
}
if($text && $text != '/start' && $step == 'simsms'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع simsms.org بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[simsms][api_key] = $text;
Apps($APPS);
}
#=========={grizzlysms.com}==========#
if($data == 'addgrizzly'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع grizzlysms.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","grizzly");
}
if($text && $text != '/start' && $step == 'grizzly'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع grizzlysms.com بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[grizzly][api_key] = $text;
Apps($APPS);
}
#=========={sms-code.ru}==========#
if($data == 'addsmscode'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع sms-code.ru*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","smscode");
}
if($text && $text != '/start' && $step == 'smscode'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع sms-code.ru بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[smscode][api_key] = $text;
Apps($APPS);
}
#=========={tiger-sms.com}==========#
if($data == 'addtiger'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع tiger-sms.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","tiger");
}
if($text && $text != '/start' && $step == 'tiger'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع tiger-sms.com بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[tiger][api_key] = $text;
Apps($APPS);
}
#=========={2ndline.io}==========#
if($data == 'add2ndline'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع 2ndline.io*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","2ndline");
}
if($text && $text != '/start' && $step == '2ndline'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع 2ndline.io بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS['2ndline'][api_key] = $text;
Apps($APPS);
}
#=========={receivesms.store}==========#
if($data == 'addstore'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع receivesms.store*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","store");
}
if($text && $text != '/start' && $step == 'store'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع receivesms.store بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[store][api_key] = $text;
Apps($APPS);
}
#=========={sms.fastpva.com}==========#
if($data == 'addfastpva'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع sms.fastpva.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","fastpva");
}
if($text && $text != '/start' && $step == 'fastpva'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع sms.fastpva.com بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[fastpva][api_key] = $text;
Apps($APPS);
}
#=========={dropsms.ru}==========#
if($data == 'adddropsms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع dropsms.ru*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","dropsms");
}
if($text && $text != '/start' && $step == 'dropsms'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع dropsms.ru بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[dropsms][api_key] = $text;
Apps($APPS);
}
#=========={24sms7.com}==========#
if($data == 'add24sms7'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع 24sms7.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","24sms7");
}
if($text && $text != '/start' && $step == '24sms7'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع 24sms7.com بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS['24sms7'][api_key] = $text;
Apps($APPS);
}
#=========={sellotp.com}==========#
if($data == 'addsellotp'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارسل رمز ال API للموقع sellotp.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","sellotp");
}
if($text && $text != '/start' && $step == 'sellotp'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع sellotp.com بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[sellotp][api_key] = $text;
Apps($APPS);
}
#=========={mm.duraincloud.com}==========#
if($data == 'addduraincloud'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*➖️ ارسل معلومات ال API للموقع mm.duraincloud.com* 🌐•

➖️ اليوزر 👤•
➖️ الباسورد 🔐•
➖️ رمز ال api 🖇•

➖️➖️➖️⚜️➖️➖️➖️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"addapi"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","duraincloud");
}
if($text && $text != '/start' && $step == 'duraincloud'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*☔️ - تم رفع كود ال API للموقع mm.duraincloud.com بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
$APPS[duraincloud][Username] = $extext[0];
$APPS[duraincloud][Password] = $extext[1];
$APPS[duraincloud][api_key] = $extext[2];
Apps($APPS);
}
#=========={حذف API}==========#
if($data == 'delapi'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- قم بإختيار الموقع الذي تريد حذف كود ال API من البوت*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"$sim5",'callback_data'=>"del5sim"]],
[['text'=>"$smslive",'callback_data'=>"delsmslive"],['text'=>"$man",'callback_data'=>"delman"]],
[['text'=>"$vak",'callback_data'=>"delvaksms"]],
[['text'=>"$acktiwator",'callback_data'=>"delacktiwator"],['text'=>"$pvapins",'callback_data'=>"delpvapins"]],
[['text'=>"$botsms",'callback_data'=>"delbotsms"]],
[['text'=>"$onlinesim",'callback_data'=>"delonlinesim"],['text'=>"$supersmstech",'callback_data'=>"delsupersmstech"]],
[['text'=>"$viotp",'callback_data'=>"delviotp"]],
[['text'=>"$simsms",'callback_data'=>"delsimsms"],['text'=>"$grizzly",'callback_data'=>"delgrizzly"]],
[['text'=>"$smscode",'callback_data'=>"delsmscode"]],
[['text'=>"$tiger",'callback_data'=>"deltiger"],['text'=>"$nd2line",'callback_data'=>"del2ndline"]],
[['text'=>"$store",'callback_data'=>"delstore"]],
[['text'=>"$fastpva",'callback_data'=>"delfastpva"],['text'=>"$dropsms",'callback_data'=>"deldropsms"]],
[['text'=>"$sms7",'callback_data'=>"del24sms7"]],
[['text'=>"$sellotp",'callback_data'=>"delsellotp"],['text'=>"$duraincloud",'callback_data'=>"delduraincloud"]],
[['text'=>'رجوع','callback_data'=>'counapi']]
]
])
]);
unlink("data/id/$id/step.txt");
}
#=========={5sim.biz}==========#
if($data == 'del5sim'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع 5sim.biz*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdel5sim"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdel5sim'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع 5sim.biz من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS['5sim']);
Apps($APPS);
}
#=========={smslive.pro}==========#
if($data == 'delsmslive'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع smslive.pro*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelsmslive"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelsmslive'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع smslive.pro من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[smslive]);
Apps($APPS);
}
#=========={sms-man.ru}==========#
if($data == 'delman'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع sms-man.ru*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelman"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelman'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع للموقع sms-man.ru من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[man]);
Apps($APPS);
}
#=========={Vak-sms.com}==========#
if($data == 'delvaksms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع vak-sms.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelvak"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelvak'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع vak-sms.com من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[vaksms]);
Apps($APPS);
}
#=========={sms-acktiwator.ru}==========#
if($data == 'delacktiwator'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع sms-acktiwator.ru*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelacktiwator"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelacktiwator'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع sms-acktiwator.ru من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[acktiwator]);
Apps($APPS);
}
#=========={pvapins.com}==========#
if($data == 'delpvapins'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع pvapins.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelpvapins"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelpvapins'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع pvapins.com من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[pvapins]);
Apps($APPS);
}
#=========={bot-sms.shop}==========#
if($data == 'delbotsms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع bot-sms.shop*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelbotsms"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelbotsms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع bot-sms.shop من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[botsms]);
Apps($APPS);
}
#=========={onlinesim.io}==========#
if($data == 'delonlinesim'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع onlinesim.io*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelonlinesim"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelonlinesim'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع onlinesim.io من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[onlinesim]);
Apps($APPS);
}
#=========={supersmstech.com}==========#
if($data == 'delsupersmstech'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع supersmstech.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelsupersmstech"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelsupersmstech'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع supersmstech.com من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[supersmstech]);
Apps($APPS);
}
#=========={viotp.com}==========#
if($data == 'delviotp'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع viotp.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelviotp"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelviotp'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع viotp.com من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[viotp]);
Apps($APPS);
}
#=========={simsms.org}==========#
if($data == 'delsimsms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع simsms.org*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelsimsms"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelsimsms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع simsms.org من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[simsms]);
Apps($APPS);
}
#=========={grizzlysms.com}==========#
if($data == 'delgrizzly'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع grizzlysms.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelgrizzly"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelgrizzly'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع grizzlysms.com من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[grizzly]);
Apps($APPS);
}
#=========={sms-code.ru}==========#
if($data == 'delsmscode'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع sms-code.ru*
",
'parse_mode'=>"MarkDown",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelsmscode"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelsmscode'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع sms-code.ru من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[smscode]);
Apps($APPS);
}
#=========={tiger-sms.com}==========#
if($data == 'deltiger'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع tiger-sms.com*
",
'parse_mode'=>"MarkDown",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdeltiger"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdeltiger'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع tiger-sms.com من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[tiger]);
Apps($APPS);
}
#=========={2ndline.io}==========#
if($data == 'del2ndline'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع 2ndline.io*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdel2ndline"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdel2ndline'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع 2ndline.io من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS['2ndline']);
Apps($APPS);
}
#=========={receivesms.store}==========#
if($data == 'delstore'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع receivesms.store*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelstore"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelstore'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع receivesms.store من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[store]);
Apps($APPS);
}
#=========={sms.fastpva.com}==========#
if($data == 'delfastpva'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع sms.fastpva.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelfastpva"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelfastpva'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع sms.fastpva.com من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[fastpva]);
Apps($APPS);
}
#=========={dropsms.ru}==========#
if($data == 'deldropsms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع dropsms.ru*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdeldropsms"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdeldropsms'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع dropsms.ru من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[dropsms]);
Apps($APPS);
}
#=========={24sms7.com}==========#
if($data == 'del24sms7'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع 24sms7.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdel24sms7"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdel24sms7'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع 24sms7.com من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS['24sms7']);
Apps($APPS);
}
#=========={sellotp.com}==========#
if($data == 'delsellotp'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع sellotp.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelsellotp"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelsellotp'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع sellotp.com من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[sellotp]);
Apps($APPS);
}
#=========={mm.duraincloud.com}==========#
if($data == 'delduraincloud'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هل انت متأكد بأنك تريد حذف كود ال API للموقع mm.duraincloud.com*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"نعم متأكد ☑️",'callback_data'=>"yesdelduraincloud"]],
[['text'=>"رجوع",'callback_data'=>"delapi"]]
]
])
]);
}
if($data == 'yesdelduraincloud'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*☔️ - تم حذف كود ال API للموقع mm.duraincloud.com من التخزين بنجاح ☑️*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"counapi"]]
]
])
]);
unset($APPS[duraincloud]);
Apps($APPS);
}
#=========={صنع كروت}==========#
if($data == 'card'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>'
 💯 حسنا مطوري
 قم بإرسال سعر الكرت الآن ( ارقام فقط )
',
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","card");
}
if($text && $step == 'card'){
if(!preg_match("/(\D)/",$text)){
$CARD = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ12345689807'),1,16);
$cardbot2=$cardbot+1;
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
➖️ تم صنع كرت بنجاح 😇○
➖️ الكرت : 💳•
`$CARD`
➖️ القيمة : $text روبل 💸•
       🔰

➖️➖️➖️⚜️➖️➖️➖️
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$eer,
'text'=>"
كرت جديد تم صنعة من قبل
🅰 الوكيل : [$first](tg://user?id=$id)
💢 الكرت :
`$CARD`
💰 القيمة : $text روبل
",
'parse_mode'=>"MarkDown",
]);
unlink("data/id/$id/step.txt");
$sool['card'][$CARD][amount] = $text;
$sool['card'][$CARD]['idcar'] = "ok";
$sool['card'][$CARD]['num'] = null;
$sool['card'][$CARD]['name'] = $first;
$sool['card'][$CARD]['id'] = $id;
$sool['card'][$CARD]['idcard'] = $cardbot2;
$sool['card'][$CARD]['DAY'] = $DAY;
Sool($sool);
$ORDERALL[card] +=1;
OrdAll($ORDERALL);
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
🔰 - يرجى إرسال أرقام فقط ❌
",
'reply_to_message_id'=>$message_id,
'reply_markup' => json_encode([
'inline_keyboard' => [
[['text'=>'- رجوع.','callback_data'=>"c"]]
]
])
]);
}
}

#=========={شحن رصيد}==========#
if($data == 'addcoin'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
⚜️ - أرسل ايدي الحساب (يشترط يكون مسجل بالبوت) الذي تريد شحن روبل لحسابه في البوت 💙
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","addcoin");
}
if($text && $text != '/start' && $step == 'addcoin'){
// ⚡ البحث بـ user_id مباشرة (المفتاح = user_id)
$_targetEM = isset($EMILS['emils'][$text]['emil']) ? $text : null;
// إذا لم يوجد: ابحث داخل البيانات بـ id
if ($_targetEM === null) {
    foreach ($EMILS['emils'] as $_k => $_v) {
        if ((string)($_v['id'] ?? '') === (string)$text) {
            $_targetEM = $_k;
            break;
        }
    }
}
// ⚡ عميل قديم (قبل الكابتشا): موجود في emil.json وليس في emils.json — انقله تلقائياً
if ($_targetEM === null) {
    $_oldEMIL = jRead('EMIL/emil.json');
    if (!empty($_oldEMIL[$text]['emil'])) {
        $_targetEM = (string)$text;
        $EMILS['emils'][$_targetEM]['emil']         = $_targetEM;
        $EMILS['emils'][$_targetEM]['pass']         = $_oldEMIL[$text]['pass'] ?? '';
        $EMILS['emils'][$_targetEM]['Date_created'] = $_oldEMIL[$text]['Date_created'] ?? "$D/$M/$Y";
        $EMILS['emils'][$_targetEM]['id']           = (int)$text;
        Bemil($EMILS);
        if (!is_dir("EMILS/$_targetEM")) { @mkdir("EMILS/$_targetEM", 0755, true); }
        if (!file_exists("EMILS/$_targetEM/points.txt")) file_put_contents("EMILS/$_targetEM/points.txt", '0');
        if (!file_exists("EMILS/$_targetEM/rubles.txt")) file_put_contents("EMILS/$_targetEM/rubles.txt", '0');
        @mkdir("data/id/$text", 0755, true);
        if (!file_exists("data/id/$text/captcha_done.txt")) {
            file_put_contents("data/id/$text/captcha_done.txt", (string)time());
        }
    }
}
if($_targetEM === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا العضو لم يسجّل في البوت بعد 🔸️
📌 يجب أن يفتح العضو البوت ويكمل التسجيل أولاً.
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
♻️ - الان أرسل عدد الروبل التي تريد إضافتة للحساب [$text] 💎
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","addcoin2|$_targetEM");
}
}
if($text && $text != '/start' && $exstep[0] == 'addcoin2'){
$emile = $exstep[1];
$reo = $EMILS['emils'][$emile]['id'];
$prices = file_get_contents("EMILS/$emile/points.txt");
$pricet = $prices + $text;
$idSend = rand(1234567,9999999);
bot('sendMessage',[
'chat_id'=>$reo,
'text'=>"
➖ تم شحن $text  روبل الى حسابك ✅.
➖ تم الشحن من قبل [$first](tg://user?id=$id) ♻️.
➖ تم اضافه الروبل رصيدك الحالي ₽ $pricet

➖ 💙[  قناة البوت  ](http://t.me/$usrch1/01850)💙
➖ 💜[  قناة التفعيلات  ](http://t.me/$usrch2/0850)💜
➖ 📞[  الأرقام المتوفرة حالياً  ](http://t.me/$usrch3/0850)🇸🇦🇻🇳🇪🇬
[ شرح استخدام البوت  💡](http://t.me/$usrch4/11)

𓆩•|ــــــــــ( $yasein )ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
]);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
➖ تم شحن $text  روبل الى حسابك ✅.
➖ تم الشحن من قبل [$first](tg://user?id=$id) ♻️.
➖ تم اضافه الروبل رصيدك الحالي ₽ $pricet

➖ 💙[  قناة البوت  ](http://t.me/$usrch1/01850)💙
➖ 💜[  قناة التفعيلات  ](http://t.me/$usrch2/0850)💜
➖ 📞[  الأرقام المتوفرة حالياً  ](http://t.me/$usrch3/0850)🇸🇦🇻🇳🇪🇬
[ شرح استخدام البوت  💡](http://t.me/$usrch4/11)

𓆩•|ــــــــــ( $yasein )ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ حساب العميل↻ ٭",'url'=>"tg://openmessage?user_id=$reo"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
$points = file_get_contents("EMILS/$emile/points.txt");
$as = $points + $text;
file_put_contents("EMILS/$emile/points.txt",$as);
$rubles = file_get_contents("EMILS/$emile/rubles.txt");
$ds = $rubles + $text;
file_put_contents("EMILS/$emile/rubles.txt",$ds);
$rubleall = file_get_contents("data/txt/rubleall.txt");
$dlls = $rubleall + $text;
file_put_contents("data/txt/rubleall.txt",$dlls);
$BUYSPRIC = jRead("EMILS/$emile/price.json");
$idd = count($BUYSPRIC);
$BUYSPRIC[$idd][id] = $idSend;
$BUYSPRIC[$idd][price] = $text;
$BUYSPRIC[$idd][status] = 2;
$BUYSPRIC[$idd][via] = 1;
$BUYSPRIC[$idd]["chat-id"] = $reo;
$BUYSPRIC[$idd]["user_chat-id"] = $id;
$BUYSPRIC[$idd][emil] = $emile;
$BUYSPRIC[$idd][user_emil] = $EM;
$BUYSPRIC[$idd][user_name] = $first;
$BUYSPRIC[$idd][DAY] = $DAY;
PricBuys($BUYSPRIC,$emile);
$ORDERALL[add] +=1;
OrdAll($ORDERALL);
unlink("data/id/$id/step.txt");
}
if($extext[0] == 'اضف'){
$emile = $extext[1];
$taxt = $extext[2];
$reo = $EMILS['emils'][$emile]['id'];
$prices = file_get_contents("EMILS/$emile/points.txt");
$pricet = $prices + $taxt;
$idSend = rand(1234567,9999999);
if($taxt == null){
exit;
}
if(findEmilKey($emile, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"cop"]]
]
])
]);
exit;
}
bot('sendMessage',[
'chat_id'=>$reo,
'text'=>"
➖ تم شحن $text  روبل الى حسابك ✅.
➖ تم الشحن من قبل [$first](tg://user?id=$id) ♻️.
➖ تم اضافه الروبل رصيدك الحالي ₽ $pricet

➖ 💙[  قناة البوت  ](http://t.me/$usrch1/01850)💙
➖ 💜[  قناة التفعيلات  ](http://t.me/$usrch2/0850)💜
➖ 📞[  الأرقام المتوفرة حالياً  ](http://t.me/$usrch3/0850)🇸🇦🇻🇳🇪🇬
[ شرح استخدام البوت  💡](http://t.me/$usrch4/11)

𓆩•|ــــــــــ( $yasein )ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
]);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
➖ تم شحن $text  روبل الى حسابك ✅.
➖ تم الشحن من قبل [$first](tg://user?id=$id) ♻️.
➖ تم اضافه الروبل رصيدك الحالي ₽ $pricet

➖ 💙[  قناة البوت  ](http://t.me/$usrch1/01850)💙
➖ 💜[  قناة التفعيلات  ](http://t.me/$usrch2/0850)💜
➖ 📞[  الأرقام المتوفرة حالياً  ](http://t.me/$usrch3/0850)🇸🇦🇻🇳🇪🇬
[ شرح استخدام البوت  💡](http://t.me/$usrch4/11)

𓆩•|ــــــــــ( $yasein )ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ حساب العميل↻ ٭",'url'=>"tg://openmessage?user_id=$reo"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
$points = file_get_contents("EMILS/$emile/points.txt");
$as = $points + $taxt;
file_put_contents("EMILS/$emile/points.txt",$as);
$rubles = file_get_contents("EMILS/$emile/rubles.txt");
$ds = $rubles + $taxt;
file_put_contents("EMILS/$emile/rubles.txt",$ds);
$rubleall = file_get_contents("data/txt/rubleall.txt");
$dlls = $rubleall + $taxt;
file_put_contents("data/txt/rubleall.txt",$dlls);
$BUYSPRIC = jRead("EMILS/$emile/price.json");
$idd = count($BUYSPRIC);
$BUYSPRIC[$idd][id] = $idSend;
$BUYSPRIC[$idd][price] = $taxt;
$BUYSPRIC[$idd][status] = 2;
$BUYSPRIC[$idd][via] = 1;
$BUYSPRIC[$idd]["chat-id"] = $reo;
$BUYSPRIC[$idd]["user_chat-id"] = $id;
$BUYSPRIC[$idd][emil] = $emile;
$BUYSPRIC[$idd][user_emil] = $EM;
$BUYSPRIC[$idd][user_name] = $first;
$BUYSPRIC[$idd][DAY] = $DAY;
PricBuys($BUYSPRIC,$emile);
$ORDERALL[add] +=1;
OrdAll($ORDERALL);
unlink("data/id/$id/step.txt");
}
# شحن روبل بعد شراء الرقم 
#=========={شحن رصيد}==========#
if($data == 'Shippingrublenumber'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
⚜️ - أرسل ايدي الحساب (يشترط يكون مسجل بالبوت) الذي تريد شحن روبل لحسابه في البوت 💙
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","Shippingrublenumber");
}
if($text && $text != '/start' && $step == 'Shippingrublenumber'){
if(findEmilKey($text, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
♻️ - الان أرسل عدد الروبل التي تريد إضافتة للحساب [$text] 💎
مطور الملف : @YAS_Nl
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
$__emKey = findEmilKey($text,$EMILS)??$text;
file_put_contents("data/id/$id/step.txt","Shippingrublenumber2|$__emKey");
}
}
if($text && $text != '/start' && $exstep[0] == 'Shippingrublenumber2'){
$emile = $exstep[1];
$reo = $EMILS['emils'][$emile]['id'];
$prices = file_get_contents("EMILS/$emile/points.txt");
$pricet = $prices + $text;
$idSend = rand(1234567,9999999);
bot('sendMessage',[
'chat_id'=>$reo,
'text'=>"
*➖ تم شحن $text  روبل الى حسابك ✅.*
*➖ شكرأ لاستخدامك بوتنا ❤*
",
'parse_mode'=>"MarkDown",
]);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*➖ تم شحن $text  روبل الى حسابك ✅.*
*•
➖ شكرأ لاستخدامك بوتنا ❤*
➖ رصيد المستخدم الحالي $pricet روبل 🏦.
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ حساب العميل↻ ٭",'url'=>"tg://openmessage?user_id=$reo"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
$points = file_get_contents("EMILS/$emile/points.txt");
$as = $points + $text;
file_put_contents("EMILS/$emile/points.txt",$as);
$rubles = file_get_contents("EMILS/$emile/rubles.txt");
$ds = $rubles + $text;
file_put_contents("EMILS/$emile/rubles.txt",$ds);
$rubleall = file_get_contents("data/txt/rubleall.txt");
$dlls = $rubleall + $text;
file_put_contents("data/txt/rubleall.txt",$dlls);
$BUYSPRIC = jRead("EMILS/$emile/price.json");
$idd = count($BUYSPRIC);
$BUYSPRIC[$idd][id] = $idSend;
$BUYSPRIC[$idd][price] = $text;
$BUYSPRIC[$idd][status] = 2;
$BUYSPRIC[$idd][via] = 1;
$BUYSPRIC[$idd]["chat-id"] = $reo;
$BUYSPRIC[$idd]["user_chat-id"] = $id;
$BUYSPRIC[$idd][emil] = $emile;
$BUYSPRIC[$idd][user_emil] = $EM;
$BUYSPRIC[$idd][user_name] = $first;
$BUYSPRIC[$idd][DAY] = $DAY;
PricBuys($BUYSPRIC,$emile);
$ORDERALL[add] +=1;
OrdAll($ORDERALL);
unlink("data/id/$id/step.txt");
}
if($extext[0] == 'اضف'){
$emile = $extext[1];
$taxt = $extext[2];
$reo = $EMILS['emils'][$emile]['id'];
$prices = file_get_contents("EMILS/$emile/points.txt");
$pricet = $prices + $taxt;
$idSend = rand(1234567,9999999);
if($taxt == null){
exit;
}
if(findEmilKey($emile, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
مطور الملف : @YAS_Nl
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"cop"]]
]
])
]);
exit;
}
bot('sendMessage',[
'chat_id'=>$reo,
'text'=>"
*➖ تم شحن $text  روبل الى حسابك ✅.*
*➖ شكرأ لاستخدامك بوتنا ❤*
",
'parse_mode'=>"MarkDown",
]);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*➖ تم شحن $text  روبل الى حسابك ✅.*
*➖ شكرأ لاستخدامك بوتنا ❤*
➖ رصيد المستخدم الحالي $pricet روبل 🏦.
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ حساب العميل↻ ٭",'url'=>"tg://openmessage?user_id=$reo"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
$points = file_get_contents("EMILS/$emile/points.txt");
$as = $points + $taxt;
file_put_contents("EMILS/$emile/points.txt",$as);
$rubles = file_get_contents("EMILS/$emile/rubles.txt");
$ds = $rubles + $taxt;
file_put_contents("EMILS/$emile/rubles.txt",$ds);
$rubleall = file_get_contents("data/txt/rubleall.txt");
$dlls = $rubleall + $taxt;
file_put_contents("data/txt/rubleall.txt",$dlls);
$BUYSPRIC = jRead("EMILS/$emile/price.json");
$idd = count($BUYSPRIC);
$BUYSPRIC[$idd][id] = $idSend;
$BUYSPRIC[$idd][price] = $taxt;
$BUYSPRIC[$idd][status] = 2;
$BUYSPRIC[$idd][via] = 1;
$BUYSPRIC[$idd]["chat-id"] = $reo;
$BUYSPRIC[$idd]["user_chat-id"] = $id;
$BUYSPRIC[$idd][emil] = $emile;
$BUYSPRIC[$idd][user_emil] = $EM;
$BUYSPRIC[$idd][user_name] = $first;
$BUYSPRIC[$idd][DAY] = $DAY;
PricBuys($BUYSPRIC,$emile);
$ORDERALL[add] +=1;
OrdAll($ORDERALL);
unlink("data/id/$id/step.txt");
}
#شحن دولارت
if($data == 'dollarh1h'){
    bot('EditMessageText',[
        'chat_id'=>$chat_id,
        'message_id'=>$message_id,
        'text'=>"
        ⚜️ - أرسل ايدي الحساب (يشترط يكون مسجل بالبوت) الذي تريد شحن دولار لحسابه في البوت 💙
        ",
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"- رجوع.",'callback_data'=>"c"]]
            ]
        ])
    ]);
    file_put_contents("data/id/$id/step.txt","dollarh1h");
}

if($text && $text != '/start' && $step == 'dollarh1h'){
    if(findEmilKey($text, $EMILS) === null){
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"
            ⛔️ - عذرا، هذا الايدي محذوف أو غير صحيح 🔸️
            ",
            'reply_to_message_id'=>$message_id,
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    [['text'=>"- رجوع.",'callback_data'=>"c"]]
                ]
            ])
        ]);
    } else {
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"
            ♻️ - الآن أرسل عدد الدولارات التي تريد إضافتها لحساب [$text] 💎
            ",
            'reply_to_message_id'=>$message_id,
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    [['text'=>"- رجوع.",'callback_data'=>"c"]]
                ]
            ])
        ]);
        $__emKey = findEmilKey($text,$EMILS)??$text;
file_put_contents("data/id/$id/step.txt","dollarh1h2|$__emKey");
    }
}

if($text && $text != '/start' && $exstep[0] == 'dollarh1h2'){
    $emile = $exstep[1];
    $amount = intval($text); // تحويل القيمة إلى رقم صحيح
    $reo = $EMILS['emils'][$emile]['id'];

    if($amount <= 0){ 
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"
            ⛔️ - المبلغ غير صالح. يرجى إدخال رقم صحيح 🔸️
            ",
            'reply_to_message_id'=>$message_id,
        ]);
        exit;
    }

    $current_balance = file_get_contents("EMILS/$emile/dollar.txt");
    $dollar = $current_balance + $amount;

    file_put_contents("EMILS/$emile/dollar.txt", $dollar);

    bot('sendMessage',[
        'chat_id'=>$reo,
        'text'=>"
➖ تم شحن $amount دولار إلى حسابك ✅.
➖ رصيدك الحالي $dollar دولار 🏦.
        ",
        'parse_mode'=>"MarkDown",
    ]);

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"
        ✅ تم شحن $amount دولار إلى الحساب [$emile] بنجاح.
        ",
        'reply_to_message_id'=>$message_id,
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"✥ حساب العميل ↻",'url'=>"tg://openmessage?user_id=$reo"]],
                [['text'=>"- رجوع.",'callback_data'=>"c"]]
            ]
        ])
    ]);

    // تحديث سجلات المشتريات
    $rubleall = file_get_contents("data/txt/rubleall.txt");
    $updated_total = $rubleall + $amount;
    file_put_contents("data/txt/rubleall.txt", $updated_total);

    unlink("data/id/$id/step.txt");
}
#=========={خصم رصيد}==========#
if($data == 'delcoin'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
⚜️ - أرسل ايدي الحساب (يشترط يكون مسجل بالبوت) الذي تريد *خصم* روبل لحسابه في البوت 💙
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","delcoin");
}
if($text && $text != '/start' && $step == 'delcoin'){
if(findEmilKey($text, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
♻️ - الان أرسل عدد الروبل التي تريد خصمها من الحساب [$text] 💎
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
$__emKey = findEmilKey($text,$EMILS)??$text;
file_put_contents("data/id/$id/step.txt","delcoin2|$__emKey");
}
}
if($text && $text != '/start' && $exstep[0] == 'delcoin2'){
$emile = $exstep[1];
$reo = $EMILS['emils'][$emile]['id'];
$prices = file_get_contents("EMILS/$emile/points.txt");
$pricet = $prices - $text;
if($text > $prices){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - لايمكنك خصم رصيد اكثر من رصيد المستخدم😂
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚜ - تم خصم *$amount* روبل بنجاح ✅

🌐 - الحساب: *$emile*
♻️ - رصيدة الآن: *$dollar* 💰
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ حساب العميل↻ ٭",'url'=>"tg://openmessage?user_id=$reo"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
$points = file_get_contents("EMILS/$emile/points.txt");
$as = $points - $text;
file_put_contents("EMILS/$emile/points.txt",$as);
$rubles = file_get_contents("EMILS/$emile/rubles.txt");
$ds = $rubles - $text;
file_put_contents("EMILS/$emile/rubles.txt",$ds);
$rubleall = file_get_contents("data/txt/rubleall.txt");
$dlls = $rubleall - $text;
file_put_contents("data/txt/rubleall.txt",$dlls);
}
}
if($extext[0] == 'خصم'){
$emile = $extext[1];
$taxt = $extext[2];
$reo = $EMILS['emils'][$emile]['id'];
$prices = file_get_contents("EMILS/$emile/points.txt");
$pricet = $prices - $taxt;
if(findEmilKey($emile, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
}elseif($taxt > $prices){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - لايمكنك خصم رصيد اكثر من رصيد المستخدم😂
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚜ - تم خصم *$taxt* روبل بنجاح ✅

🌐 - الحساب: *$emile*
♻️ - رصيدة الآن: *$pricet* 💰
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ حساب العميل↻ ٭",'url'=>"tg://openmessage?user_id=$reo"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
$points = file_get_contents("EMILS/$emile/points.txt");
$as = $points - $taxt;
file_put_contents("EMILS/$emile/points.txt",$as);
$rubles = file_get_contents("EMILS/$emile/rubles.txt");
$ds = $rubles - $taxt;
file_put_contents("EMILS/$emile/rubles.txt",$ds);
$rubleall = file_get_contents("data/txt/rubleall.txt");
$dlls = $rubleall - $taxt;
file_put_contents("data/txt/rubleall.txt",$dlls);
}
}
#=========={خصم رصيد بالدولار}==========#
if($data == 'dollar11'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
⚜️ - أرسل ايدي الحساب (يشترط يكون مسجل بالبوت) الذي تريد *خصم* دولار لحسابه في البوت 💙
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","dollar11");
}
if($text && $text != '/start' && $step == 'dollar11'){
if(findEmilKey($text, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
♻️ - الان أرسل عدد الدولارات التي تريد خصمها من الحساب [$text] 💎
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
$__emKey = findEmilKey($text,$EMILS)??$text;
file_put_contents("data/id/$id/step.txt","dollar112|$__emKey");
}
}
if($text && $text != '/start' && $exstep[0] == 'dollar112'){
$emile = $exstep[1];
$reo = $EMILS['emils'][$emile]['id'];
$prices = file_get_contents("EMILS/$emile/dollars.txt");
$dollar = $prices - $text;
if($text > $prices){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - لايمكنك خصم رصيد اكثر من رصيد المستخدم😂
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚜ - تم خصم *$amount* روبل بنجاح ✅

🌐 - الحساب : *$emile*
♻️ - رصيدة الآن : *$dollar* 💰
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ حساب العميل↻ ٭",'url'=>"tg://openmessage?user_id=$reo"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
$points = file_get_contents("EMILS/$emile/dollars.txt");
$as = $points - $text;
file_put_contents("EMILS/$emile/dollars.txt",$as);
$dollarall = file_get_contents("data/txt/dollarall.txt");
$dlls = $dollarall - $text;
file_put_contents("data/txt/dollarall.txt",$dlls);
}
}
#=========={إضافة وكيل}==========#
if($data == 'addagent'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
⚜️ - أرسل ايدي الحساب (يشترط يكون مسجل بالبوت) الذي تريد رفعة ك وكيل في البوت 💙
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","addagent");
}
if($text && $text != '/start' && $step == 'addagent'){
$idEM = $EMILS['emils'][$text]['id'];
$api = json_decode(file_get_contents("http://api.telegram.org/bot".API_KEY."/getChat?chat_id=".$idEM.""));
$name =$api->result->first_name;
$users =$api->result->username;
$txusers="@$users";
if($users == null){
$txusers="لا يوجد ♻️";
}
if(findEmilKey($text, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}elseif($agents['gents'][$text] != null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري مالك هذا الايدي وكيل من قبل
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('sendMessage',[
'chat_id'=>$idEM,
'text'=>"
☑️ *- تم إضافتك في البوت ك وكيل رسمي
⚠️ - يمنع بيع سعر الروبل ب أغلى من سعر المالك وإلا سيتم حذفك من قائمة الوكلاء.* 📆
",
'parse_mode'=>"MarkDown",
]);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ - تم إضافة العميل ك وكيل بنجاح
💠 - الحساب: *[$text]*
🌀 - يوزر الوكيل: *$txusers*
♻️ - إسم الوكيل: *$name*
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ الوكيل↻ ٭",'url'=>"tg://openmessage?user_id=$idEM"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
$agents['gents'][$text] = $idEM;
Agent($agents);
unlink("data/id/$id/step.txt");
}
}
#=========={حذف وكيل}==========#
if($data == 'delagent'){
$key     = [];
$key['inline_keyboard'][] = [['text'=>"👮🏻 الوكيل.",'callback_data'=>"no"],['text'=>"🛒 الرابط.",'callback_data'=>"no"]];
foreach($agents['gents'] as $zero=>$idgents){
$api = json_decode(file_get_contents("http://api.telegram.org/bot".API_KEY."/getChat?chat_id=".$idgents.""));
$name =$api->result->first_name;
$users =$api->result->username;
$key['inline_keyboard'][] = [['text'=>"$name",'callback_data'=>"delagents-$zero"],['text'=>"إضغط 🖱",'url'=>"tg://openmessage?user_id=$idgents"]];
}
$key['inline_keyboard'][] = [['text'=>'- رجوع.','callback_data'=>"c"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- إضغط على إسم الوكيل لحذفة ☑️
",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
}
if($exdata[0] == "delagents"){
$zero=$exdata[1];
$idgents=$agents['gents'][$zero];
$api = json_decode(file_get_contents("http://api.telegram.org/bot".API_KEY."/getChat?chat_id=".$idgents.""));
$name =$api->result->first_name;
$users =$api->result->username;
$txusers="@$users";
if($users == null){
$txusers="لا يوجد ♻️";
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
👮🏻 - الوكيل : *$name*
🌐 - حساب الوكيل : *$zero*
🌀 - يوزر الوكيل : *$txusers*

*☑️ - هل توافق على حذف هذا الوكيل 🔰*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط الوكيل ↖️",'url'=>"tg://openmessage?user_id=$idgents"]],
[['text'=>"موافق ☑️",'callback_data'=>"YSdelagent-$zero"]],
[['text'=>"- رجوع.",'callback_data'=>"delagent"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
if($exdata[0] == "YSdelagent"){
$zero=$exdata[1];
$idgents=$agents['gents'][$zero];
$api = json_decode(file_get_contents("http://api.telegram.org/bot".API_KEY."/getChat?chat_id=".$idgents.""));
$name =$api->result->first_name;
$users =$api->result->username;
$txusers="@$users";
if($users == null){
$txusers="لا يوجد ♻️";
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *تم حذف الوكيل بنجاح.*

👮🏻 - الوكيل : *$name*
🌐 - حساب الوكيل : *$zero*
🌀 - يوزر الوكيل : *$txusers*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط الوكيل ↖️",'url'=>"tg://openmessage?user_id=$idgents"]],
[['text'=>"- رجوع.",'callback_data'=>"delagent"]]
]
])
]);
unset($agents['gents'][$zero]);
Agent($agents);
unlink("data/id/$id/step.txt");
}
#=========={الكشف}==========#
if($data == 'cop'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
اهلا وسهلا مطوري $first ، 🖤

الان قم بإختيار موضوع كشف رصيد عضو او رصيد موقع الناقل ❄️
",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>'رصيد مستخدم ☑️','callback_data'=>'copchat']],
[['text'=>'رصيد موقع الارقام 🗃','callback_data'=>'simcop']],
[['text'=>'رصيد حساباتي 💸','callback_data'=>'Balancesms']],
[['text'=>'- رجوع.','callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/step.txt");
}
#=========={كشف ارصده الزبون}==========#
if($data == 'copchat'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
♻️ - أرسل الأيدي أو الحساب الذي تريد الكشف عن معلوماتة
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"cop"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","copchat");
}
if($text && $text != '/start' && $step == 'copchat'){
$emils = $EMIL[$text]['emil'];
if($emils == null){
$emils = $EMILS['emils'][$text]['emil'];
}
$ero = $EMILS['emils'][$emils]['id'];
$pricet = file_get_contents("EMILS/$emils/points.txt");
if($pricet == null){
$pricet = 0;
}
if(findEmilKey($emils, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"cop"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
🔰 - قم بالتسجيل للحساب للكشف عنة أكثر ✅

🌐 - الحساب: $emils
♻️ - رصيدة: $pricet 💰
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$emils",'callback_data'=>"emils-$emils"]],
[['text'=>"✥ حساب العميل↻ ٭",'url'=>"tg://openmessage?user_id=$ero"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
if($extext[0] == 'كشف'){
$emils = $EMIL[$extext[1]]['emil'];
if($emils == null){
$emils = $EMILS['emils'][$extext[1]]['emil'];
}
$ero = $EMILS['emils'][$emils]['id'];
$pricet = file_get_contents("EMILS/$emils/points.txt");
if($pricet == null){
$pricet = 0;
}
if($extext[1] == null){
exit;
}
if(findEmilKey($emils, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"cop"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
🔰 - قم بالتسجيل للحساب للكشف عنة أكثر ✅

🌐 - الحساب: $emils
♻️ - رصيدة: $pricet 💰
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$emils",'callback_data'=>"emils-$emils"]],
[['text'=>"✥ حساب العميل↻ ٭",'url'=>"tg://openmessage?user_id=$ero"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={كشف رصيد للمواقع}==========#
if($data == 'simcop'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🔰 - أرسل api الموقع الذي تريد معرفة رصيدة!
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"cop"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","simcop");
}
if($text && $text != '/start' && $step == 'simcop'){
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://5sim.biz/v1/user/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Authorization: Bearer ' . $text;
$headers[] = 'Accept: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
$api=json_decode($result,1);
$price = $api[balance];
$email = $api[email];
if($email != null){
$tax="💬 - الإيميل : $email";
}
if($price == null){
$api = file_get_contents("https://activation.pw/stubs/handler_api.php?api_key=$text&action=getBalance");
$api = explode(":", $api);
$price = $api[1];
}
if($price == null){
$api = file_get_contents("http://api.sms-man.ru/stubs/handler_api.php?action=getBalance&api_key=$text");
$api = explode(":", $api);
$price = $api[1];
}
if($price == null){
$api = file_get_contents("https://vak-sms.com/stubs/handler_api.php?api_key=$text&action=getBalance");
$api = explode(":", $api);
$price = $api[1];
}
if($price == null){
$api = file_get_contents("https://sms-acktiwator.ru/stubs/handler_api.php?api_key=$text&action=getBalance");
$api = explode(":", $api);
$price = $api[1];
}
if($price == null){
$api = json_decode(file_get_contents("https://answs1234.darksidehost.com/jj/tele/GetBalance.php?key=6971786233a35a9b68643d2cb9619997d1193a1f05f7f8f2&from_id=6996492261"),1);
$price = $api['coin'];
}
if($price == null){
$api = json_decode(file_get_contents("https://onlinesim.io/api/getBalance.php?apikey=$text"),1);
$price = $api[balance];
}
if($price == null){
$api = json_decode(file_get_contents("https://api.viotp.com/users/balance?token=$text"),1);
$price = $api[data][balance];
}
if($price == null){
$api = json_decode(file_get_contents("http://simsms.org/priemnik.php?metod=get_balance&apikey=$text"),1);
$price = $api[balance];
}
if($price == null){
$api = file_get_contents("https://api.grizzlysms.com/stubs/handler_api.php?api_key=$text&action=getBalance");
$api = explode(":", $api);
$price = $api[1];
}
if($price == null){
$api = json_decode(file_get_contents("https://sms-code.ru/api.php?api_key=$text&method=get_balance"),1);
$price = $api[data][balance];
}
if($price == null){
$api = file_get_contents("https://tiger-sms.com/stubs/handler_api.php?api_key=$text&action=getBalance");
$api = explode(":", $api);
$price = $api[1];
}
if($price == null){
$api = json_decode(file_get_contents("https://2ndline.io/apiv1/getbalance?apikey=$text"),1);
$price = $api[balance];
}
if($price == null){
$api = file_get_contents("https://receivesms.store/stubs/handler_api.php?api_key=$text&action=getBalance");
$api = explode(":", $api);
$price = $api[1];
}
if($price == null){
$api = json_decode(file_get_contents("http://api.fastpva.com/pvapublic/user/info?apikey=$text"),1);
$price = $api[data][balance];
$email = $api[data][email];
if($email != null){
$tax="💬 - الإيميل : $email";
}
}
if($price == null){
$api = file_get_contents("https://api.dropsms.cc/stubs/handler_api.php?action=getBalance&api_key=$text");
$api = explode(":", $api);
$price = $api[1];
}
if($price == null){
$api = file_get_contents("https://24sms7.com/stubs/handler_api.php?api_key=$text&action=getBalance");
$api = explode(":", $api);
$price = $api[1];
}
if($price == null){
$api = json_decode(file_get_contents("https://api.sellotp.com/users/balance?token=$text&lang=en"),1);
$price = $api[data][balance];
}
if($price == null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*🔰 - ال API الذي أرسلتة غير صحيح ⛔️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"cop"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*🔰 - تم الكشف عن الرصيد الذي في الموقع ☑️
💰 - الرصيد : ₽ $price
$tax*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع",'callback_data'=>"cop"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={عدد المشتركين}==========#
if($data == 'members'){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
💯 عدد المشتركين هو $count مشترك
",
'show_alert'=>true,
]);
}
#=========={ارصدة المستخدمين}==========#
#=========={فك تقييد رقم}==========#
if($data == 'unnum'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>'
🆔 - ارسل ايدي العضو الذي تريد فك تقييده عن الشراء ♻️
',
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","unnum");
}
if($text && $text != '/start' && $step == 'unnum'){
$Detector = file_get_contents("data/id/$text/restriction.txt");
if($Detector == null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚜ - هذا العضو ليس مقيد من سابق ✅
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚜ - تم فك تقييد العضو [$text](tg://user?id=$text) والسماح لة ب الشراء بنجاح ✅
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$text,
'text'=>"
 🔓 *- تم فك تقييدك الآن تستطيع ان تقوم ب الشراء بنجاح* ♻️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"back"]]
]
])
]);
unlink("data/id/$text/restriction.txt");
unlink("data/id/$id/step.txt");
}
}
if($extext[0] == 'فك'){
$Detector = file_get_contents("data/id/$extext[1]/restriction.txt");
if($Detector == null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚜ - هذا العضو ليس مقيد من سابق ✅
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚜ - تم فك تقييد العضو [$extext[1]](tg://user?id=$extext[1]) والسماح لة ب الشراء بنجاح ✅
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$extext[1],
'text'=>"
 🔓 *- تم فك تقييدك الآن تستطيع ان تقوم ب الشراء بنجاح* ♻️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"back"]]
]
])
]);
unlink("data/id/$extext[1]/restriction.txt");
unlink("data/id/$id/step.txt");
}
}
#=========={التقييد}==========#
if($data == 'res'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>'
☑️ - ارسل الحساب (الإيميل) الذي تريد تقييده في البوت ♻️
',
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","rees");
}
if($text && $text != '/start' && $step == 'rees'){
$t=$admins[$text]["end"];
$c=$admins[$text]["time"];
$v=$t-(time() - $c);
if($admins[$text]["check"] != null and time() - $c < $t){
$Before = "☑️ - مالك هذا الحساب تم تقييدة من سابق لمده *$t ثانية* ✅
⏰ - متبقى له: *$v ثانية*
";
}
$second=3600;
if(findEmilKey($text, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"cop"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ *- أكتب الوقت الذي تريد تقييد* مالك الحساب *[$text]* ⏰
$Before
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"☑️ - تقييد لمده ($second ثانية)",'callback_data'=>"Sd-$text-$second"]],
[['text'=>"➖ سنة ➖",'callback_data'=>"rees-$text-0"],['text'=>"➕ سنة ➕",'callback_data'=>"rees-$text-31107600"]],
[['text'=>"➖ شهر ➖",'callback_data'=>"rees-$text-0"],['text'=>"➕ شهر ➕",'callback_data'=>"rees-$text-2595600"]],
[['text'=>"➖ يوم ➖",'callback_data'=>"rees-$text-0"],['text'=>"➕ يوم ➕",'callback_data'=>"rees-$text-90000"]],
[['text'=>"➖ ساعة ➖",'callback_data'=>"rees-$text-0"],['text'=>"➕ ساعة ➕",'callback_data'=>"rees-$text-7200"]],
[['text'=>"➖ دقيقة ➖",'callback_data'=>"rees-$text-3540"],['text'=>"➕ دقيقة ➕",'callback_data'=>"rees-$text-3660"]],
[['text'=>"➖ ثانية ➖",'callback_data'=>"rees-$text-3599"],['text'=>"➕ ثانية ➕",'callback_data'=>"rees-$text-3601"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
if($exdata[0] == "rees"){
$idEM=$exdata[1];
$reo = $EMILS['emils'][$idEM]['id'];
$second=$exdata[2];
if($second <= 0 or $second == null or $second == 0 or $second === 0){
unlink("data/id/$id/step.txt");
exit;
}
$t=$admins[$idEM]["end"];
$c=$admins[$idEM]["time"];
$v=$t-(time() - $c);
if($admins[$idEM]["check"] != null and time() - $c < $t){
$Before = "☑️ - مالك هذا الحساب تم تقييدة من سابق لمده *$t ثانية* ✅
⏰ - متبقى له: *$v ثانية*
";
}
$y=$second+1;
$i=$second-1;
$h=$second+60;
$k=$second-60;
$u=$second+3600;
$s=$second-3600;
$a=$second+86400;
$b=$second-86400;
$r=$second+2592000;
$w=$second-2592000;
$q=$second+31104000;
$p=$second-31104000;
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- أكتب الوقت الذي تريد تقييد* مالك الحساب *[$idEM]* ⏰
$Before
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"☑️ - تقييد لمده ($second ثانية)",'callback_data'=>"Sd-$idEM-$second"]],
[['text'=>"➖ سنة ➖",'callback_data'=>"rees-$idEM-$p"],['text'=>"➕ سنة ➕",'callback_data'=>"rees-$idEM-$q"]],
[['text'=>"➖ شهر ➖",'callback_data'=>"rees-$idEM-$w"],['text'=>"➕ شهر ➕",'callback_data'=>"rees-$idEM-$r"]],
[['text'=>"➖ يوم ➖",'callback_data'=>"rees-$idEM-$b"],['text'=>"➕ يوم ➕",'callback_data'=>"rees-$idEM-$a"]],
[['text'=>"➖ ساعة ➖",'callback_data'=>"rees-$idEM-$s"],['text'=>"➕ ساعة ➕",'callback_data'=>"rees-$idEM-$u"]],
[['text'=>"➖ دقيقة ➖",'callback_data'=>"rees-$idEM-$k"],['text'=>"➕ دقيقة ➕",'callback_data'=>"rees-$idEM-$h"]],
[['text'=>"➖ ثانية ➖",'callback_data'=>"rees-$idEM-$i"],['text'=>"➕ ثانية ➕",'callback_data'=>"rees-$idEM-$y"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
if($exdata[0] == "Sd"){
$idEM=$exdata[1];
$reo = $EMILS['emils'][$idEM]['id'];
$second=$exdata[2];
if($idEM == null or $second <= 0){
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - تم تقييد الحساب *[$idEM]* في البوت بنجاح 🌟
⏰ *- المده: $second ثانية*
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$reo,
'text'=>"
☑️ *- قام المالك بتقييد حسابك في البوت لمده $second ثانية* ⏰
⚠️ *- اذا تظن أنه تم تقييدك ب الخطأ تواصل مع الدعم الأنلاين* ⬇️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>'🚀 تواصل مع الدعم 🚀','callback_data'=>"super"]],
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
$admins[$idEM]["check"] = 'ok';
$admins[$idEM]["end"] = $second;
$admins[$idEM]["time"] = time();
Admins($admins);
unlink("data/id/$id/step.txt");
}
#=========={فك التقييد}==========#
if($data == 'unres'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>'
👨‍💻️ - ارسل الحساب (الايدي) الذي تريد فك تقييده في البوت ♻️
',
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","unres");
}
if($text && $text != '/start' && $step == 'unres'){
$reo = $EMILS['emils'][$text]['id'];
if(findEmilKey($text, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 🔸️
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"cop"]]
]
])
]);
}elseif($admins[$text]["check"] == null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ *- مالك هذا الحساب ليس مقيد من قبل* 🤷‍♂
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ - تم فك تقييد الحساب *[$text]* في البوت بنجاح 🌟
  ",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$reo,
'text'=>"
☑️ *- قام المالك بفك تقييدك عن البوت* 🌟
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"back"]]
]
])
]);
unset($admins[$text]);
Admins($admins);
unlink("data/id/$id/step.txt");
}
}
#=========={zero}==========#
if($text == 'تصفير'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'
⚠️ - أرسل حساب الزبون الذي تريد تصفير حسابه 🔰
',
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","zero");
}
if($text && $text != '/start' && $step == 'zero'){
$reo = $EMILS['emils'][$text]['id'];
$pri=file_get_contents("EMILS/$text/points.txt");
if(findEmilKey($text, $EMILS) === null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⛔️ - عذرا مطوري هذا الايدي محذوف أو ليس صحيح 💚
",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ - تم تصفير حساب العضو بنحاح
🌐 - الحساب : $text
🆔 - ايدية : [$reo](tg://user?id=$reo)
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>"- رجوع.",'callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
unlink("EMILS/$text/points.txt");
$rubles = file_get_contents("EMILS/$text/rubles.txt");
$ds = $rubles - $pri;
file_put_contents("EMILS/$text/rubles.txt",$ds);
$rubleall = file_get_contents("data/txt/rubleall.txt");
$dlls = $rubleall - $pri;
file_put_contents("data/txt/rubleall.txt",$dlls);
}
}

#=========={إضافة مواقع جديدة - v3}==========#
if(!function_exists('cs_load')){
function cs_load(){
    $f='data/api/custom_sites.json';
    if(!file_exists($f)) file_put_contents($f,'[]');
    $d=@json_decode(@file_get_contents($f),true);
    return is_array($d)?$d:[];
}
function cs_save(array $d){
    file_put_contents('data/api/custom_sites.json',json_encode($d,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), LOCK_EX);
}
function cs_balance(string $url,string $key):?string{
    $url=rtrim($url,'/');
    // smsbower format
    $r=@json_decode(@file_get_contents("$url/api?api_key=$key&action=getBalance"),true);
    if(isset($r['ok'],$r['balance'])&&$r['ok']===true) return (string)$r['balance'];
    // standard text
    $t=@file_get_contents("$url/stubs/handler_api.php?api_key=$key&action=getBalance");
    if($t&&strpos($t,'ACCESS_BALANCE:')!==false){$p=explode(':',$t);return trim($p[1]??'');}
    // json data.balance
    $r=@json_decode(@file_get_contents("$url/api.php?api_key=$key&method=get_balance"),true);
    if(isset($r['data']['balance'])) return (string)$r['data']['balance'];
    // json balance direct
    $r=@json_decode(@file_get_contents("$url/api/getBalance?apikey=$key"),true);
    if(isset($r['balance'])) return (string)$r['balance'];
    // token
    $r=@json_decode(@file_get_contents("$url/users/balance?token=$key"),true);
    if(isset($r['data']['balance'])) return (string)$r['data']['balance'];
    return null;
}
function cs_detect(string $url,string $key):?array{
    $url=rtrim($url,'/');
    $r=@json_decode(@file_get_contents("$url/api?api_key=$key&action=getBalance"),true);
    if(isset($r['ok'],$r['balance'])&&$r['ok']===true)
        return ['balance'=>(string)$r['balance'],'format'=>'smsbower'];
    $t=@file_get_contents("$url/stubs/handler_api.php?api_key=$key&action=getBalance");
    if($t&&strpos($t,'ACCESS_BALANCE:')!==false){$p=explode(':',$t);return['balance'=>trim($p[1]??''),'format'=>'standard'];}
    $r=@json_decode(@file_get_contents("$url/api.php?api_key=$key&method=get_balance"),true);
    if(isset($r['data']['balance'])) return['balance'=>(string)$r['data']['balance'],'format'=>'standard'];
    $r=@json_decode(@file_get_contents("$url/api/getBalance?apikey=$key"),true);
    if(isset($r['balance'])) return['balance'=>(string)$r['balance'],'format'=>'standard'];
    $r=@json_decode(@file_get_contents("$url/users/balance?token=$key"),true);
    if(isset($r['data']['balance'])) return['balance'=>(string)$r['data']['balance'],'format'=>'json_token'];
    return null;
}
function cs_list_keyboard(array $cs):array{
    $kb=[];
    foreach($cs as $sid=>$site){
        $icon=!empty($site['active'])?'✅':'❌';
        $nm=$site['name']??'?';
        $kb[]=[
            ['text'=>"🗑 $nm",'callback_data'=>"ns_del-$sid"],
            ['text'=>'💰','callback_data'=>"ns_bal-$sid"],
            ['text'=>'🔗','callback_data'=>"ns_chk-$sid"]
        ];
    }
    return $kb;
}
}
#===[ لوحة المواقع الجديدة ]===
if($data=='newsite_panel'){
    $cs=cs_load();
    $total=count($cs);
    $active=0; foreach($cs as $s){if(!empty($s['active']))$active++;}
    bot('EditMessageText',[
        'chat_id'=>$chat_id,'message_id'=>$message_id,
        'text'=>"🆕 *- لوحة المواقع الجديدة*\n\n📊 إجمالي: *$total* | ✅ متصل: *$active* | ❌ غير متصل: *".($total-$active)."*\n\nاختر من القائمة ⬇️",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode(['inline_keyboard'=>[
            [['text'=>'➕ إضافة موقع جديد','callback_data'=>'ns_add']],
            [['text'=>'📋 قائمة المواقع','callback_data'=>'ns_list']],
            [['text'=>'🔗 فحص حالة الاتصال','callback_data'=>'ns_checkall']],
            [['text'=>'💰 أرصدة المواقع','callback_data'=>'ns_balall']],
            [['text'=>'↩ رجوع','callback_data'=>'c']]
        ]])
    ]);
    @unlink("data/id/$id/step.txt");
}
#===[ خطوة 1 - اسم الموقع ]===
if($data=='ns_add'){
    bot('EditMessageText',[
        'chat_id'=>$chat_id,'message_id'=>$message_id,
        'text'=>"➕ *الخطوة 1/3*\n\n📝 أرسل اسم أو دومين الموقع\nمثال: `smsbower.app`",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode(['inline_keyboard'=>[[['text'=>'✖️ إلغاء','callback_data'=>'newsite_panel']]]])
    ]);
    file_put_contents("data/id/$id/step.txt","ns_name");
}
if($text&&$text!='/start'&&$step=='ns_name'){
    $nm=preg_replace('/[^a-zA-Z0-9._\-]/','',trim($text));
    if(empty($nm))$nm='site_'.time();
    file_put_contents("data/id/$id/ns_nm.txt",$nm);
    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"➕ *الخطوة 2/3*\n\n✅ الاسم: `$nm`\n\n🌐 أرسل رابط الـ API\nمثال: `https://smsbower.app`\n_(بدون / في النهاية)_",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode(['inline_keyboard'=>[[['text'=>'✖️ إلغاء','callback_data'=>'newsite_panel']]]])
    ]);
    file_put_contents("data/id/$id/step.txt","ns_url");
}
if($text&&$text!='/start'&&$step=='ns_url'){
    $url=rtrim(trim($text),'/');
    if(!preg_match('/^https?:\/\/.+/',$url)){
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"⛔️ *رابط غير صحيح!*\nيجب أن يبدأ بـ `https://`\n\nأرسل الرابط مرة أخرى:",
            'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>[[['text'=>'✖️ إلغاء','callback_data'=>'newsite_panel']]]])
        ]);
    }else{
        file_put_contents("data/id/$id/ns_ur.txt",$url);
        $nm=trim((string)@file_get_contents("data/id/$id/ns_nm.txt"));
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"➕ *الخطوة 3/3*\n\n✅ الاسم: `$nm`\n✅ الرابط: `$url`\n\n🔑 أرسل مفتاح الـ API",
            'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>[[['text'=>'✖️ إلغاء','callback_data'=>'newsite_panel']]]])
        ]);
        file_put_contents("data/id/$id/step.txt","ns_key");
    }
}
if($text&&$text!='/start'&&$step=='ns_key'){
    $key=trim($text);
    $nm=trim((string)@file_get_contents("data/id/$id/ns_nm.txt")?:'site_'.time());
    $url=trim((string)@file_get_contents("data/id/$id/ns_ur.txt")?:'');
    $wait=bot('sendMessage',['chat_id'=>$chat_id,'text'=>"⏳ *جاري الاتصال وكشف نوع API...*",'parse_mode'=>'MarkDown']);
    $wid=isset($wait->result->message_id)?$wait->result->message_id:null;
    $det=cs_detect($url,$key);
    $fmt=$det?$det['format']:'unknown';
    $bal=$det?$det['balance']:null;
    $real_url=($det && !empty($det['base']))?$det['base']:$url;
    $cs=cs_load();
    $sid='cs_'.uniqid('');
    $cs[$sid]=['name'=>$nm,'base_url'=>$real_url,'api_key'=>$key,'format'=>$fmt,'active'=>($bal!==null),'added'=>date('Y-m-d H:i')];
    cs_save($cs);
    // تسجيل الموقع في addblusdel وapps ليظهر للمستخدمين
    $addblusdel[$sid]['add'] = 'ok';
    Bnds($addblusdel);
    $APPS[$sid]['api_key'] = $key;
    Apps($APPS);
    $txt=$bal!==null?
        "✅ *تم إضافة الموقع بنجاح!*\n\n🌐 `$nm`\n🔗 `$real_url`\n📡 نوع API: `$fmt`\n🔌 الحالة: *متصل ✅*\n💰 الرصيد: *$bal*":
        "⚠️ *تم الحفظ ولكن تعذر الاتصال*\n\n🌐 `$nm`\n🔗 `$url`\n🔌 الحالة: *❌ غير متصل*\n\nتأكد من صحة الرابط والـ API";
    $kb=json_encode(['inline_keyboard'=>[
        [['text'=>'📋 قائمة المواقع','callback_data'=>'ns_list']],
        [['text'=>'➕ إضافة موقع آخر','callback_data'=>'ns_add']],
        [['text'=>'↩ رجوع','callback_data'=>'newsite_panel']]
    ]]);
    if($wid){bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$wid,'text'=>$txt,'parse_mode'=>'MarkDown','reply_markup'=>$kb]);}
    else{bot('sendMessage',['chat_id'=>$chat_id,'text'=>$txt,'parse_mode'=>'MarkDown','reply_markup'=>$kb]);}
    @unlink("data/id/$id/ns_nm.txt");
    @unlink("data/id/$id/ns_ur.txt");
    @unlink("data/id/$id/step.txt");
}
#===[ قائمة المواقع ]===
if($data=='ns_list'){
    $cs=cs_load();
    if(count($cs)==0){
        bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,
            'text'=>"📭 *لا توجد مواقع مضافة بعد*\n\nاضغط ➕ لإضافة موقعك الأول!",'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>[
                [['text'=>'➕ إضافة موقع جديد','callback_data'=>'ns_add']],
                [['text'=>'↩ رجوع','callback_data'=>'newsite_panel']]
            ]])
        ]);
    }else{
        $txt="📋 *قائمة المواقع (".count($cs).")*\n\n";
        foreach($cs as $s){
            $ic=!empty($s['active'])?'✅':'❌';
            $txt.="$ic `{$s['name']}` — `{$s['format']}` — {$s['added']}\n";
        }
        $kb=cs_list_keyboard($cs);
        $kb[]=[['text'=>'➕ إضافة موقع جديد','callback_data'=>'ns_add']];
        $kb[]=[['text'=>'↩ رجوع','callback_data'=>'newsite_panel']];
        bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,
            'text'=>$txt,'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>$kb])
        ]);
    }
    @unlink("data/id/$id/step.txt");
}
#===[ رصيد موقع واحد ]===
if($exdata[0]=='ns_bal'){
    $sid=$exdata[1]??'';
    $cs=cs_load();
    if(!isset($cs[$sid])){
        bot('answercallbackquery',['callback_query_id'=>$update->callback_query->id,'text'=>'❌ الموقع غير موجود','show_alert'=>true]);
    }else{
        $site=$cs[$sid];
        $bal=cs_balance($site['base_url']??'',$site['api_key']??'');
        $cs[$sid]['active']=($bal!==null);
        cs_save($cs);
        $st=$bal!==null?'✅ *متصل*':'❌ *غير متصل*';
        $bal=$bal??'تعذر الجلب';
        bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,
            'text'=>"💰 *رصيد الموقع*\n\n🌐 `{$site['name']}`\n🔗 `{$site['base_url']}`\n📡 `{$site['format']}`\n🔌 $st\n💰 *$bal*\n📅 {$site['added']}",
            'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>[
                [['text'=>'🔄 تحديث','callback_data'=>"ns_bal-$sid"]],
                [['text'=>'🗑 حذف','callback_data'=>"ns_del-$sid"]],
                [['text'=>'↩ رجوع','callback_data'=>'ns_list']]
            ]])
        ]);
    }
}
#===[ فحص اتصال موقع واحد ]===
if($exdata[0]=='ns_chk'){
    $sid=$exdata[1]??'';
    $cs=cs_load();
    if(!isset($cs[$sid])){
        bot('answercallbackquery',['callback_query_id'=>$update->callback_query->id,'text'=>'❌ الموقع غير موجود','show_alert'=>true]);
    }else{
        $site=$cs[$sid];
        $bal=cs_balance($site['base_url']??'',$site['api_key']??'');
        $cs[$sid]['active']=($bal!==null);
        cs_save($cs);
        $alert=$bal!==null?"✅ {$site['name']} — متصل | 💰 $bal":"❌ {$site['name']} — غير متصل";
        bot('answercallbackquery',['callback_query_id'=>$update->callback_query->id,'text'=>$alert,'show_alert'=>true]);
    }
}
#===[ حذف موقع ]===
if($exdata[0]=='ns_del'){
    $sid=$exdata[1]??'';
    $cs=cs_load();
    $nm=$cs[$sid]['name']??'الموقع';
    unset($cs[$sid]);
    cs_save($cs);
    bot('answercallbackquery',['callback_query_id'=>$update->callback_query->id,'text'=>"✅ تم حذف $nm",'show_alert'=>true]);
    $txt=count($cs)?"📋 *قائمة المواقع (".count($cs).")*\n\n":"📭 *لا توجد مواقع مضافة بعد*";
    $kb=cs_list_keyboard($cs);
    foreach($cs as $s){$ic=!empty($s['active'])?'✅':'❌';$txt.="$ic `{$s['name']}` — {$s['added']}\n";}
    $kb[]=[['text'=>'➕ إضافة','callback_data'=>'ns_add']];
    $kb[]=[['text'=>'↩ رجوع','callback_data'=>'newsite_panel']];
    bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,
        'text'=>$txt,'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode(['inline_keyboard'=>$kb])
    ]);
}
#===[ فحص اتصال جميع المواقع ]===
if($data=='ns_checkall'){
    $cs=cs_load();
    if(count($cs)==0){
        bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,
            'text'=>"📭 *لا توجد مواقع مضافة*",'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>[[['text'=>'↩ رجوع','callback_data'=>'newsite_panel']]]])
        ]);
    }else{
        bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,'text'=>"⏳ جاري فحص ".count($cs)." مواقع..."]);
        $txt="🔗 *نتائج فحص الاتصال*\n\n";
        $ok=0;
        foreach($cs as $sid=>$site){
            $bal=cs_balance($site['base_url']??'',$site['api_key']??'');
            $cs[$sid]['active']=($bal!==null);
            if($bal!==null){$txt.="✅ `{$site['name']}` — متصل | 💰 $bal\n";$ok++;}
            else{$txt.="❌ `{$site['name']}` — غير متصل\n";}
        }
        cs_save($cs);
        $total=count($cs);
        $txt.="\n📊 متصل: *$ok* | غير متصل: *".($total-$ok)."*";
        bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,
            'text'=>$txt,'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>[
                [['text'=>'🔄 إعادة الفحص','callback_data'=>'ns_checkall']],
                [['text'=>'↩ رجوع','callback_data'=>'newsite_panel']]
            ]])
        ]);
    }
    @unlink("data/id/$id/step.txt");
}
#===[ أرصدة جميع المواقع ]===
if($data=='ns_balall'){
    $cs=cs_load();
    if(count($cs)==0){
        bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,
            'text'=>"📭 *لا توجد مواقع مضافة*",'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>[[['text'=>'↩ رجوع','callback_data'=>'newsite_panel']]]])
        ]);
    }else{
        bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,'text'=>"⏳ جاري جلب الأرصدة من ".count($cs)." مواقع..."]);
        $txt="💰 *أرصدة المواقع المضافة*\n\n";
        $kb=[];
        foreach($cs as $sid=>$site){
            $bal=cs_balance($site['base_url']??'',$site['api_key']??'');
            $cs[$sid]['active']=($bal!==null);
            if($bal!==null){$txt.="✅ `{$site['name']}`: *$bal*\n";$kb[]=[['text'=>"✅ {$site['name']} — $bal",'callback_data'=>"ns_bal-$sid"]];}
            else{$txt.="❌ `{$site['name']}`: تعذر\n";$kb[]=[['text'=>"❌ {$site['name']}",'callback_data'=>"ns_chk-$sid"]];}
        }
        cs_save($cs);
        $kb[]=[['text'=>'🔄 تحديث','callback_data'=>'ns_balall']];
        $kb[]=[['text'=>'↩ رجوع','callback_data'=>'newsite_panel']];
        bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,
            'text'=>$txt,'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>$kb])
        ]);
    }
    @unlink("data/id/$id/step.txt");
}



#=========={المواقع المخصصة - Bjcs handler}==========#
if($exdata[0] == "Bjcs"){
    $cs_id = $exdata[1] ?? '';
    $cs_all = cs_load();
    $cs_site = $cs_all[$cs_id] ?? null;
    if(!$cs_site){
        bot('answercallbackquery',['callback_query_id'=>$update->callback_query->id,'text'=>'❌ الموقع غير موجود','show_alert'=>true]);
    } else {
        $cs_nm = $cs_site['name'] ?? $cs_id;
        if($addblusdel['System'] == "direct"){$System="تلقائي";}
        elseif($addblusdel['System'] == "not_directly"){$System="يدوي";}
        else{$System="يدوي";}
        bot('EditMessageText',[
            'chat_id'=>$chat_id,'message_id'=>$message_id,
            'text'=>"
*➖️ الموقع: $cs_nm 🌐•*
*➖️ نظام البوت: $System 🤖•*

*➖️ قم بإختيار التطبيق المراد إضافة الرقم اليه ✅️•*
*➖️ من الكيبورد أدناه 👇•*
",'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>[
                [['text'=>"$ot",'callback_data'=>"iVcs-ot-14-$cs_id-1"]],
                [['text'=>"$tg",'callback_data'=>"iVcs-tg-3-$cs_id-1"],['text'=>"$wa",'callback_data'=>"iVcs-wa-2-$cs_id-1"]],
                [['text'=>"🟢 عروض واتساب",'callback_data'=>"iVcs-wa-1-$cs_id-1"],['text'=>"🔵 عروض تيليجرام",'callback_data'=>"iVcs-tg-36-$cs_id-1"]],
                [['text'=>"$ig",'callback_data'=>"iVcs-ig-5-$cs_id-1"],['text'=>"$fb",'callback_data'=>"iVcs-fb-4-$cs_id-1"]],
                [['text'=>"$lf",'callback_data'=>"iVcs-lf-7-$cs_id-1"],['text'=>"$tw",'callback_data'=>"iVcs-tw-6-$cs_id-1"]],
                [['text'=>"$im",'callback_data'=>"iVcs-im-9-$cs_id-1"],['text'=>"$go",'callback_data'=>"iVcs-go-8-$cs_id-1"]],
                [['text'=>"$fu",'callback_data'=>"iVcs-fu-11-$cs_id-1"],['text'=>"$vi",'callback_data'=>"iVcs-vi-10-$cs_id-1"]],
                [['text'=>"$au",'callback_data'=>"iVcs-au-13-$cs_id-1"],['text'=>"$nf",'callback_data'=>"iVcs-nf-12-$cs_id-1"]],
                [['text'=>'رجوع','callback_data'=>'addnumber']]
            ]])
        ]);
    }
    unlink("data/id/$id/step.txt");
}

#=========={اختيار الدولة للمواقع المخصصة - iVcs}==========#
if($exdata[0] == "iVcs"){
    $app    = $exdata[1] ?? '';
    $add    = $exdata[2] ?? '';
    $cs_id  = $exdata[3] ?? '';
    $con    = $exdata[4] ?? 1;
    if($con == null || $con == '') $con = 1;
    if($con == 1){$continent=$AdminONE;}
    if($con == 2){$continent=$AdminTOW;}
    if($con == 3){$continent=$AdminTHREE;}
    if($con == 4){$continent=$AdminFOUR;}
    if($con == 5){$continent=$AdminFIVE;}
    if($con == 6){$continent=$AdminSIX;}
    if($con == 7){$continent=$AdminSEVEN;}
    if($con == 8){$continent=$AdminEIGHT;}
    $cs_all  = cs_load();
    $cs_site = $cs_all[$cs_id] ?? null;
    $cs_nm   = $cs_site ? ($cs_site['name'] ?? $cs_id) : $cs_id;
    if($continent == null){unlink("data/id/$id/step.txt");}
    else {
        if($con >= 8){$u=$con-1;}
        elseif($con >= 1){$o=$con+1; $u=$con-1;}
        if($con==1){$t="التالي. ⬅️";$s=null;$v="الأخير. 🔀";}
        elseif($con==8){$t=null;$s="➡️ السابق.";$n="الأول. ⏭";}
        else{$t="التالي. ⬅️";$s="➡️ السابق.";$v=null;$n=null;}
        $i=0;$e=0;$key=[];
        foreach($continent as $ar=>$zero){
            $e++;
            // Use addpricecs callback to distinguish from regular addprice
            $key['inline_keyboard'][$i][]=['text'=>"$ar",'callback_data'=>"addpricecs-$app-$add-$cs_id-$zero-any"];
            if($e%3==0) $i++;
        }
        $key['inline_keyboard'][] = [
            ['text'=>"$t",'callback_data'=>"iVcs-$app-$add-$cs_id-$o"],
            ['text'=>"$s",'callback_data'=>"iVcs-$app-$add-$cs_id-$u"],
            ['text'=>"$v",'callback_data'=>"iVcs-$app-$add-$cs_id-8"],
            ['text'=>"$n",'callback_data'=>"iVcs-$app-$add-$cs_id-1"]
        ];
        $key['inline_keyboard'][] = [['text'=>"رجوع",'callback_data'=>"Bjcs-$cs_id"]];
        bot('EditMessageText',[
            'chat_id'=>$chat_id,'message_id'=>$message_id,
            'text'=>"
➖️  هذة جميع الدول المتوفرة في الموقع $cs_nm ♻️•
",'reply_markup'=>json_encode($key)
        ]);
    }
    unlink("data/id/$id/step.txt");
}

#=========={إضافة سعر للموقع المخصص - addpricecs}==========#
if($exdata[0] == "addpricecs"){
    $app     = $exdata[1] ?? '';
    $add     = $exdata[2] ?? '';
    $cs_id   = $exdata[3] ?? '';
    $country = $exdata[4] ?? '';
    $operator= $exdata[5] ?? 'any';
    $cs_all  = cs_load();
    $cs_site = $cs_all[$cs_id] ?? null;
    if(!$cs_site){
        bot('answercallbackquery',['callback_query_id'=>$update->callback_query->id,'text'=>'❌ الموقع غير موجود','show_alert'=>true]);
    } else {
        $cs_nm  = $cs_site['name'] ?? $cs_id;
        $cs_url = $cs_site['base_url'] ?? '';
        $cs_key = $cs_site['api_key'] ?? '';
        // Map add to status letter
        if($add >=1 && $add <=14){
            $status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$add);
        } else { $status = $add; }
        $APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام"],$status);
        $APP_S = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["WhatsApp","Telegram","Facebook","Instagram","Twitter","Tiktok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);
        $name  = $_co['country'][$country] ?? $country;
        $code  = md5("$country$app$operator$add$cs_id");
        // Check if already added (لنفس الموقع تحديداً)
        if($buy['number'][$code] != null){
            bot('answercallbackquery',['callback_query_id'=>$update->callback_query->id,
                'text'=>"⛔️ هذه الدولة مضافة لهذا الموقع مسبقاً",'show_alert'=>true]);
            unlink("data/id/$id/step.txt");
        } else {
            // Try to get price from API
            $api_price = @json_decode(@file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getPrice&site=$cs_id&country=$country&app=$app&operator=$operator"),true);
            $price_from_api = ($api_price && $api_price['status']=="200") ? $api_price['price'] : null;
            $price_display = $price_from_api ? "سعر الموقع: $price_from_api" : "لا يوجد سعر من الموقع (أدخل سعرك)";
            bot('EditMessageText',[
                'chat_id'=>$chat_id,'message_id'=>$message_id,
                'text'=>"
➖ يتم الان اضافه المعلومات 🛎•
➖ الدولة  : $name •
➖ التطبيق : $APP 🌐•
➖ الموقع  : $cs_nm ♻️•
➖ $price_display ☎️•
➖ قم بإرسال السعر الان 💙•

        🔰
        
➖➖➖➖➖➖
",'parse_mode'=>'MarkDown',
                'reply_markup'=>json_encode(['inline_keyboard'=>[
                    [['text'=>'رجوع','callback_data'=>"iVcs-$app-$add-$cs_id-1"]]
                ]])
            ]);
            // Save context for when admin sends the price
            file_put_contents("data/id/$id/step.txt","addpricecs|$app|$add|$cs_id|$country|$operator|$code|$APP_S|$name");
        }
    }
}

#=========={حفظ سعر الموقع المخصص}==========#
if($text && $text != '/start' && $exstep[0] == 'addpricecs'){
    $s_app     = $exstep[1] ?? '';
    $s_add     = $exstep[2] ?? '';
    $s_cs_id   = $exstep[3] ?? '';
    $s_country = $exstep[4] ?? '';
    $s_oper    = $exstep[5] ?? 'any';
    $s_code    = $exstep[6] ?? '';
    $s_APP_S   = $exstep[7] ?? '';
    $s_name    = $exstep[8] ?? '';
    $price     = trim($text);
    if(!is_numeric($price) || $price <= 0){
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"⛔️ عذراً قم بإرسال رقم أكبر من الصفر ❌",
            'reply_to_message_id'=>$message_id,
            'reply_markup'=>json_encode(['inline_keyboard'=>[[['text'=>'رجوع','callback_data'=>'addnumber']]]])
        ]);
        unlink("data/id/$id/step.txt");
    } else {
        $cs_all  = cs_load();
        $cs_site = $cs_all[$s_cs_id] ?? null;
        $cs_nm   = $cs_site ? ($cs_site['name'] ?? $s_cs_id) : $s_cs_id;
        // Map add to APP string
        if($s_add >=1 && $s_add <=14){
            $s_status = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["J","K","L","M","N","A","B","C","D","E","F","G","H","I"],$s_add);
        } else { $s_status = $s_add; }
        $s_APP = str_replace(["A","B","C","D","E","F","G","H","I","J","K","L","M","N"],["واتسأب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام"],$s_status);
        // Save to country.json
        $buy['number'][$s_code]['country']  = $s_country;
        $buy['number'][$s_code]['app']      = $s_app;
        $buy['number'][$s_code]['operator'] = $s_oper;
        $buy['number'][$s_code]['api']      = $s_cs_id; // custom site ID
        $buy['number'][$s_code]['site']     = $s_cs_id; // same for api-sites.php lookup
        $buy['number'][$s_code]['add']      = $s_add;
        $buy['number'][$s_code]['name']     = $s_name;
        $buy['number'][$s_code]['APP']      = $s_APP_S;
        $buy['number'][$s_code]['Type']     = "not_directly";
        $buy['number'][$s_code]['price']    = $price;
        $buy['country_app']["$s_add-$s_country"] += 1;
        Addserver($buy);
        // ─── تأكيد تسجيل الموقع ليظهر للمستخدمين ───
        if(!isset($addblusdel[$s_cs_id]) || $addblusdel[$s_cs_id]['add'] != 'ok'){
            $addblusdel[$s_cs_id]['add'] = 'ok';
            Bnds($addblusdel);
        }
        if(empty($APPS[$s_cs_id]['api_key']) && $cs_site){
            $APPS[$s_cs_id]['api_key'] = $cs_site['api_key'] ?? '';
            Apps($APPS);
        }
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"
➖ تم اضافه المعلومات بنجاح 🛎•
➖ الدولة  : $s_name •
➖ التطبيق : $s_APP 🌐•
➖ السعر   : $price ₽ ☎️•
➖ الموقع  : $cs_nm ♻️•

        🔰
",'reply_to_message_id'=>$message_id,'parse_mode'=>'MarkDown',
            'reply_markup'=>json_encode(['inline_keyboard'=>[
                [['text'=>'رجوع','callback_data'=>"Bjcs-$s_cs_id"]]
            ]])
        ]);
        unlink("data/id/$id/step.txt");
        @unlink("zzz.json");
    }
}


/*#=========={حساباتي}==========#
if($data == 'Balancesms' or $text == "رصيدي"){
// ⚡ جلب الأرصدة بالتوازي — curl_multi بدل الانتظار التسلسلي
$_bs = [];
if($addblusdel['5sim']['add']=="ok")       $_bs['5sim']       =['url'=>'https://5sim.biz/v1/user/profile','hdr'=>['Authorization: Bearer '.$APPS['5sim']['api_key'],'Accept: application/json'],'t'=>'5sim','lbl'=>['</> 5sim.biz','5sim.biz/payment']];
if($addblusdel['smslive']['add']=="ok")    $_bs['smslive']    =['url'=>"https://smslive.pro/stubs/handler_api.php?api_key={$APPS['smslive']['api_key']}&action=getBalance",'t'=>'col','s'=>'₽','lbl'=>['</> smslive.pro','#']];
if($addblusdel['man']['add']=="ok")        $_bs['man']        =['url'=>"http://api.sms-man.ru/stubs/handler_api.php?action=getBalance&api_key={$APPS['man']['api_key']}",'t'=>'col','s'=>'₽','lbl'=>['</> sms-man.ru','sms-man.ru']];
if($addblusdel['vak']['add']=="ok")        $_bs['vak']        =['url'=>"https://vak-sms.com/stubs/handler_api.php?api_key={$APPS['vak']['api_key']}&action=getBalance",'t'=>'col','s'=>'₽','lbl'=>['</> Vak-sms.com','vak-sms.com/pay/']];
if($addblusdel['acktiwator']['add']=="ok") $_bs['acktiwator'] =['url'=>"https://sms-acktiwator.ru/stubs/handler_api.php?api_key={$APPS['acktiwator']['api_key']}&action=getBalance",'t'=>'col','s'=>'₽','lbl'=>['</> sms-acktiwator.ru','sms-acktiwator.ru/invoice/invoice']];
if($addblusdel['pvapins']['add']=="ok")    $_bs['pvapins']    =['url'=>null,'t'=>'static','price'=>'غير معرف.','lbl'=>['</> pvapins.com','pvapins.com/user/ads_orders.php?page=add']];
if($addblusdel['sms3t']['add']=="ok")      $_bs['sms3t']      =['url'=>"http://vps.bot-sms.shop/stubs/handler_api.php?api_key={$APPS['sms3t']['api_key']}&action=getBalance",'t'=>'col','s'=>'$','lbl'=>['</> bot-sms.shop','bot-sms.shop/money']];
if($addblusdel['onlinesim']['add']=="ok")  $_bs['onlinesim']  =['url'=>"https://onlinesim.io/api/getBalance.php?apikey={$APPS['onlinesim']['api_key']}",'t'=>'json','k'=>'balance','s'=>'$','lbl'=>['</> onlinesim.io','onlinesim.io/v2/payment/']];
if($addblusdel['supersmstech']['add']=="ok")$_bs['supersmstech']=['url'=>"https://www.supersmstech.com/api/getbalance?secret_key={$APPS['supersmstech']['api_key']}",'t'=>'json','k'=>'balance','s'=>'₽','lbl'=>['</> supersmstech.com','www.thesupercomm.com/payment']];
if($addblusdel['viotp']['add']=="ok")      $_bs['viotp']      =['url'=>"https://api.viotp.com/users/balance?token={$APPS['viotp']['api_key']}",'t'=>'jn','ks'=>['data','balance'],'s'=>'¥','lbl'=>['</> viotp.com','viotp.com/Transaction/Deposit']];
$_mh=curl_multi_init(); $_chs=[];
foreach($_bs as $_sid=>$_sc){
    if(empty($_sc['url']))continue;
    $_ch=curl_init($_sc['url']);
    curl_setopt_array($_ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>6,CURLOPT_CONNECTTIMEOUT=>4,CURLOPT_SSL_VERIFYPEER=>false]);
    if(!empty($_sc['hdr']))curl_setopt($_ch,CURLOPT_HTTPHEADER,$_sc['hdr']);
    curl_multi_add_handle($_mh,$_ch); $_chs[$_sid]=$_ch;
}
do{curl_multi_exec($_mh,$_run);curl_multi_select($_mh);}while($_run>0);
$key=['inline_keyboard'=>[]];
foreach($_bs as $_sid=>$_sc){
    $_lbl=$_sc['lbl'];
    if($_sc['t']==='static'){$key['inline_keyboard'][]=[['text'=>$_lbl[0],'callback_data'=>"Balapi-$_sid"],['text'=>$_sc['price'],'url'=>$_lbl[1]]];continue;}
    if(empty($_chs[$_sid]))continue;
    $_r=curl_multi_getcontent($_chs[$_sid]);
    curl_multi_remove_handle($_mh,$_chs[$_sid]);curl_close($_chs[$_sid]);
    $_p='?';
    if($_sc['t']==='col'){$_x=explode(':',$_r??'');$_p=trim($_x[1]??'?').' '.($_sc['s']??'');}
    elseif($_sc['t']==='json'){$_d=json_decode($_r??'',true);$_p=(($_d[$_sc['k']]??'?')).' '.($_sc['s']??'');}
    elseif($_sc['t']==='jn'){$_d=json_decode($_r??'',true);$_v=$_d;foreach($_sc['ks'] as $_k)$_v=$_v[$_k]??null;$_p=($_v??'?').' '.($_sc['s']??'');}
    elseif($_sc['t']==='5sim'){$_d=json_decode($_r??'',true);$_p=($_d['balance']??'?').' ₽';}
    $key['inline_keyboard'][]=[['text'=>$_lbl[0],'callback_data'=>"Balapi-$_sid"],['text'=>$_p,'url'=>$_lbl[1]]];
}
curl_multi_close($_mh);
$key['inline_keyboard'][]=[['text'=>"رجوع",'callback_data'=>"cop"],['text'=>"التالي. ⬅️",'callback_data'=>"Balancesms2"]];
$keyboad=json_encode($key);
if($text==null){bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,'text'=>"\nاهلا فيك في قائمه المواقع المضافة في البوت ⬇️\n",'parse_mode'=>"MarkDown",'reply_markup'=>$keyboad]);}
else{bot('SendMessage',['chat_id'=>$chat_id,'text'=>"\nاهلا فيك في قائمه المواقع المضافة في البوت ⬇️\n",'parse_mode'=>"MarkDown",'reply_markup'=>$keyboad]);exit;}
}
if($data == 'Balancesms2'){
// ⚡ curl_multi — صفحة ثانية بالتوازي
$_bs2=[];
if($addblusdel['simsms']['add']=="ok")   $_bs2['simsms']  =['url'=>"http://simsms.org/priemnik.php?metod=get_balance&apikey={$APPS['simsms']['api_key']}",'t'=>'json','k'=>'balance','s'=>'₽','lbl'=>['</> simsms.org','simsms.org/balance.html']];
if($addblusdel['grizzly']['add']=="ok")  $_bs2['grizzly'] =['url'=>"https://api.grizzlysms.com/stubs/handler_api.php?api_key={$APPS['grizzly']['api_key']}&action=getBalance",'t'=>'col','s'=>'₽','lbl'=>['</> grizzlysms.com','grizzlysms.com/profile/pay']];
if($addblusdel['smscode']['add']=="ok")  $_bs2['smscode'] =['url'=>"https://sms-code.ru/api.php?api_key={$APPS['smscode']['api_key']}&method=get_balance",'t'=>'jn','ks'=>['data','balance'],'s'=>'₽','lbl'=>['</> sms-code.ru','sms-code.ru/cabinet/payment']];
if($addblusdel['tiger']['add']=="ok")    $_bs2['tiger']   =['url'=>"https://tiger-sms.com/stubs/handler_api.php?api_key={$APPS['tiger']['api_key']}&action=getBalance",'t'=>'col','s'=>'$','lbl'=>['</> tiger-sms.com','tiger-sms.com/cabinet/payment']];
if($addblusdel['2ndline']['add']=="ok")  $_bs2['2ndline'] =['url'=>"https://2ndline.io/apiv1/getbalance?apikey={$APPS['2ndline']['api_key']}",'t'=>'json','k'=>'balance','s'=>'$','lbl'=>['</> 2ndline.io','2ndline.io/recharge/chose']];
if($addblusdel['store']['add']=="ok")    $_bs2['store']   =['url'=>"https://receivesms.store/stubs/handler_api.php?api_key={$APPS['store']['api_key']}&action=getBalance",'t'=>'col','s'=>'$','lbl'=>['</> receivesms.store','t.me/adsvk']];
if($addblusdel['fastpva']['add']=="ok")  $_bs2['fastpva'] =['url'=>"http://api.fastpva.com/pvapublic/user/info?apikey={$APPS['fastpva']['api_key']}",'t'=>'jn','ks'=>['data','balance'],'s'=>'$','lbl'=>['</> sms.fastpva.com','sms.fastpva.com/#/profile/recharge']];
if($addblusdel['dropsms']['add']=="ok")  $_bs2['dropsms'] =['url'=>"https://api.dropsms.cc/stubs/handler_api.php?action=getBalance&api_key={$APPS['dropsms']['api_key']}",'t'=>'col','s'=>'₽','lbl'=>['</> dropsms.ru','t.me/dropsmsbot']];
if($addblusdel['24sms7']['add']=="ok")   $_bs2['24sms7']  =['url'=>"https://24sms7.com/stubs/handler_api.php?api_key={$APPS['24sms7']['api_key']}&action=getBalance",'t'=>'col','s'=>'₽','lbl'=>['</> 24sms7.com','t.me/iPaiiia']];
if($addblusdel['sellotp']['add']=="ok")  $_bs2['sellotp'] =['url'=>"https://api.sellotp.com/users/balance?token={$APPS['sellotp']['api_key']}&lang=en",'t'=>'jn','ks'=>['data','balance'],'s'=>'¥','lbl'=>['</> sellotp.com','home.sellotp.com/']];
$_mh2=curl_multi_init();$_chs2=[];
foreach($_bs2 as $_sid=>$_sc){
    if(empty($_sc['url']))continue;
    $_ch=curl_init($_sc['url']);
    curl_setopt_array($_ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>6,CURLOPT_CONNECTTIMEOUT=>4,CURLOPT_SSL_VERIFYPEER=>false]);
    curl_multi_add_handle($_mh2,$_ch);$_chs2[$_sid]=$_ch;
}
do{curl_multi_exec($_mh2,$_run2);curl_multi_select($_mh2);}while($_run2>0);
$key=['inline_keyboard'=>[]];
foreach($_bs2 as $_sid=>$_sc){
    $_lbl=$_sc['lbl'];
    if(empty($_chs2[$_sid]))continue;
    $_r=curl_multi_getcontent($_chs2[$_sid]);curl_multi_remove_handle($_mh2,$_chs2[$_sid]);curl_close($_chs2[$_sid]);
    $_p='?';
    if($_sc['t']==='col'){$_x=explode(':',$_r??'');$_p=trim($_x[1]??'?').' '.($_sc['s']??'');}
    elseif($_sc['t']==='json'){$_d=json_decode($_r??'',true);$_p=(($_d[$_sc['k']]??'?')).' '.($_sc['s']??'');}
    elseif($_sc['t']==='jn'){$_d=json_decode($_r??'',true);$_v=$_d;foreach($_sc['ks'] as $_k)$_v=$_v[$_k]??null;$_p=($_v??'?').' '.($_sc['s']??'');}
    $key['inline_keyboard'][]=[['text'=>$_lbl[0],'callback_data'=>"Balapi-{$_sid}"],['text'=>$_p,'url'=>$_lbl[1]]];
}
curl_multi_close($_mh2);
$key['inline_keyboard'][]=[['text'=>"➡️ السابق.",'callback_data'=>"Balancesms"],['text'=>"التالي. ⬅️",'callback_data'=>"Balancesms3"]];
$key['inline_keyboard'][]=[['text'=>"رجوع",'callback_data'=>"cop"]];
$keyboad=json_encode($key);
bot('EditMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,'text'=>"\nاهلا فيك في قائمه المواقع المضافة في البوت ⬇️\n",'parse_mode'=>"MarkDown",'reply_markup'=>$keyboad]);
}
if($data == 'Balancesms3'){
if($addblusdel['duraincloud']['add'] == "ok"){
$api_key = $APPS['duraincloud'][api_key];
$Username = $APPS['duraincloud'][Username];
$Password = $APPS['duraincloud'][Password];
$api = json_decode(file_get_contents("https://api.duraincloud.com/out/ext_api/getUserInfo?name=$Username&pwd=$Password&ApiKey=$api_key"),1);
$price = $api[data][score];
$price = "$price نقطة";
$key[inline_keyboard][]=[['text'=>"</> mm.duraincloud.com",'callback_data'=>"Balapi-duraincloud"],['text'=>"$price",'url'=>"mm.duraincloud.com/"]];
}
$key[inline_keyboard][]=[['text'=>"➡️ السابق.",'callback_data'=>"Balancesms2"],['text'=>"رجوع",'callback_data'=>"cop"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
اهلا فيك في قائمه المواقع المضافة في البوت ⬇️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
}
if($exdata[0] == "Balapi"){
$site=$exdata[1];
$api_key = $APPS[$site][api_key];
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"$api_key",
'show_alert'=>true,
]);
}*/