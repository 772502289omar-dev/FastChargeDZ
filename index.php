<?php
/// برمجه وتصميم @QPlusAdmin

ob_start();
mkdir('data');
mkdir('EMIL');
mkdir('EMILS');
mkdir('BUY');
mkdir('assignment');
mkdir('data/id');
mkdir('data/txt');
mkdir('data/api');
$API_KEY= '8932870207:AAEnzyW9UksAEvleSPUjAlK6g93F-9sisCM';//توكن بوتك
$vfcash_user_id = "PKTFIiMv3gW6gsUMl9QD4te88mh2"; // هنا معرف حسابك فى التطبيق
$vfcash_panel_id = "-OKW_RTT2_POND8hFsze"; // هنا معرف حسابك فى التطبيق
define('API_KEY',$API_KEY);
echo file_get_contents("https://api.telegram.org/bot" . API_KEY . "/setwebhook?url=" . $_SERVER['SERVER_NAME'] . "" . $_SERVER['SCRIPT_NAME']);
function bot($method,$datas=[]){
$amrakl = http_build_query($datas);
$url = "https://api.telegram.org/bot".API_KEY."/".$method."?$amrakl";
$amrakl = file_get_contents($url);
return json_decode($amrakl);
}
include("AutoCash.php");
$cash = new AutoCash($vfcash_user_id,$vfcash_panel_id);

$update = json_decode(file_get_contents('php://input'));

$message = $update->message;
$chat_id = $message->chat->id;
$text = $message->text;
$message_id = $message->message_id;
$id = $message->from->id;
if($update->callback_query){
$id                                   = $update->callback_query->message->chat->id;
}else{
$id           						= $update->message->chat->id;
}
$user = $message->from->username;
$first = $message->from->first_name;
if(isset($update->callback_query)){
$chat_id = $update->callback_query->message->chat->id;
$message_id = $update->callback_query->message->message_id;
$data = $update->callback_query->data;
$user = $update->callback_query->from->username;
$first = $update->callback_query->from->first_name;
}
#=========={التخزينات}==========#
function Aemil($array){
file_put_contents('EMIL/emil.json', json_encode($array));
}
function Bemil($array){
file_put_contents('EMIL/emils.json', json_encode($array));
}
function Now($array){
file_put_contents('EMIL/emilnow.json', json_encode($array));
}
function OrdAll($array){
file_put_contents('BUY/Orderall.json', json_encode($array,64|128|256));
}
function NumbBuys($array,$account){
file_put_contents("EMILS/$account/number.json", json_encode($array,64|128|256));
}
function SendBuys($array,$account){
file_put_contents("EMILS/$account/send.json", json_encode($array,64|128|256));
}
function CardBuys($array,$account){
file_put_contents("EMILS/$account/card.json", json_encode($array,64|128|256));
}
function PricBuys($array,$account){
file_put_contents("EMILS/$account/price.json", json_encode($array,64|128|256));
}
function Ands($array){
file_put_contents('data/openlock.json', json_encode($array));
}
function Bnds($array){
file_put_contents('data/addblusdel.json', json_encode($array));
}
function Agent($array){
file_put_contents('data/txt/agent.json', json_encode($array));
}
function Addserver($array){
file_put_contents('data/country.json', json_encode($array,64|128|256));
}
function Sool($array){
file_put_contents('data/txt/sool.json', json_encode($array));
}
function Ready($array){
file_put_contents('data/storenumber.json', json_encode($array));
}
function Admins($array){
file_put_contents('data/id/admin.json', json_encode($array));
}
function Save($array){
file_put_contents('data/txt/random.json', json_encode($array));
}
function Ssai($array){
file_put_contents("assignment/addem.json",json_encode($array));
}
function Dsai($array){
file_put_contents("assignment/addid.json",json_encode($array));
}
function Apps($array){
file_put_contents('data/api/apps.json', json_encode($array,64|128|256));
}
function Sts($array){
file_put_contents('data/increase.json', json_encode($array,64|128|256));
}
$zzz = json_decode(file_get_contents("zzz.json"),1);
function zzz(){
global $zzz;
file_put_contents("zzz.json",json_encode($zzz));
}
$EMIL = json_decode(file_get_contents('EMIL/emil.json'),true);
$EMILS = json_decode(file_get_contents('EMIL/emils.json'),true);
$EMILNow = json_decode(file_get_contents('EMIL/emilnow.json'),true);
$ORDERALL = json_decode(file_get_contents('BUY/Orderall.json'),true); #تخزين ايدي عمليات جميع الشراء
$openandlock = json_decode(file_get_contents('data/openlock.json'),true);
$addblusdel = json_decode(file_get_contents('data/addblusdel.json'),true);
$agents = json_decode(file_get_contents('data/txt/agent.json'),true);
$buy = json_decode(file_get_contents('data/country.json'),true);
$sool = json_decode(file_get_contents('data/txt/sool.json'),true);
$storenumber = json_decode(file_get_contents('data/storenumber.json'),true);
$admins = json_decode(file_get_contents('data/id/admin.json'),true);
$random = json_decode(file_get_contents('data/txt/random.json'),true);
$assignment = json_decode(file_get_contents('assignment/addem.json'),true);
$assignment2 = json_decode(file_get_contents('assignment/addid.json'),true);
$APPS = json_decode(file_get_contents('data/api/apps.json'),true);
$increase = json_decode(file_get_contents('data/increase.json'),true);
#============={أوامر إضافية}===========#
$me = bot('getme',['bot'])->result->username;
$bot="@QST_O";  #اسم الملف الي مسويه بالاستضافه
$get = file_get_contents('data/txt/file.txt');
$exxx = explode("\n",$get);
$count = count($exxx);
if($user != null){
$User_link ="☑️ - رابط العضو ↖️";
}
#=========={حساب الإحصائيات}=========#
#=========الملف تابع  @}=========#
$numbot = $ORDERALL['number']; #عدد الأرقام المكتملة#
$readybot = $ORDERALL['ready']; #عدد الأرقام الجاهزة المشترى#
$numbots=count($ORDERALL); #العدد الكلي لكمية الأرقام المستخرجة#
$numbote = $numbots-$numbot; #عدد الأرقام المحضور#
$buysall = $numbots; #العدد الكلي للأرقام المباعة#
$Buybot = $numbot+$readybot; #العدد الكلي للأرقام المدفوعة#
$cardbot = $ORDERALL['card']; #عدد الكروت المباعة#
$sendbot = $ORDERALL['send']; #عدد عمليات التحويل#
$money2 = file_get_contents("data/txt/rubleall.txt"); #الروبل اللكلي#
$poi_money = file_get_contents("data/txt/pointall.txt"); #الروبل المتبقي#
$money = $money2 - $poi_money; #الروبل المستهلك#
$allcharges = $ORDERALL['add']; #عدد الشحن ب المرات#
$assignru=0.2; #نسبة ربح رابط الدعوة#
$Exchange=52; #سعر الدولار#
#________________
$EM = $EMILNow['emil'][$chat_id];
$passewo = $EMILNow['password'][$chat_id];
if($EM==null){
$EM=$EMIL[$chat_id]['emil'];
$passewo = $EMIL[$chat_id]['pass'];
}
$perrewo = $EMILS['emils'][$EM]['pass'];
if(!is_dir("EMILS/$EM")){
mkdir("EMILS/$EM");
}
if(!is_dir("data/id/$id")){
mkdir("data/id/$id");
}
$BUYSNUM = json_decode(file_get_contents("EMILS/$EM/number.json"),true);
$BUYSSEND = json_decode(file_get_contents("EMILS/$EM/send.json"),true);
$BUYSCARD = json_decode(file_get_contents("EMILS/$EM/card.json"),true);
$BUYSPRIC = json_decode(file_get_contents("EMILS/$EM/price.json"),true);
#_________________
$Detector = file_get_contents("data/id/$id/restriction.txt");
$step = file_get_contents("data/id/$id/step.txt");
$twas = file_get_contents("data/id/$id/twas.txt");
$buynumber = file_get_contents("data/id/$id/number.txt");
$exstep=explode("|", $step);
$extext = explode("\n", $text);
$ex_text=explode(" ", $text);
$ex__text=explode("-", $text);
$exdata=explode("-", $data);
$ex_data=explode("#", $data);
$ordermy = count($BUYSNUM[number]); #عدد الأرقام المشترى#
$numbuy = $BUYSNUM[number_my]; #عدد الأرقام المشترى#
$readymy = $BUYSNUM[ready_my]; #عدد الأرقام الجاهزة#
$orderall = count($ORDERALL)+1; #عدد مشتريات الاعضاء#
$idnums = count($ORDERALL)+999999999; #عدد مشتريات الاعضاء#
$cardmy = count($BUYSCARD); #عدد الكروت المشترى#
$sendmy = count($BUYSSEND); #عدد عمليات تحويل الروبل#
$pricmy = count($BUYSPRIC); #عدد عمليات شحن الحساب#
$buymy = $BUYSNUM['number_my']; #عدد الأرقام المكتملة#
$rubles=file_get_contents("EMILS/$EM/rubles.txt"); #الرصيد اللكلي#
$Balance = file_get_contents("EMILS/$EM/points.txt"); #رصيد العضو#
$consumers = $rubles-$Balance; #عدد الروبل المستهلك#
#_________________
if($counmyru==null){
$counmyru=0;
}
if($numbuy==null){
$numbuy=0;
}
if($buymy==null){
$buymy=0;
}
if($readymy==null){
$readymy=0;
}
if($cardmy==null){
$cardmy=0;
}
if($sendmy==null){
$sendmy=0;
}
if($pricmy==null){
$pricmy=0;
}
if($rubles==null){
$rubles=0;
}
if($counmy==null){
$counmy=0;
}
if($Balance==null){
$Balance=0;
}
if($consumers==null){
$consumers=0;
}
if($numbot==null){
$numbot=0;
}
if($readybot==null){
$readybot=0;
}
if($numbote==null){
$numbote=0;
}
if($numbots==null){
$numbots=0;
}
if($Buybot==null){
$Buybot=0;
}
if($cardbot==null){
$cardbot=0;
}
if($sendbot==null){
$sendbot=0;
}
if($money==null){
$money=0;
}
if($poi_money==null){
$poi_money=0;
}
if($allcharges==null){
$allcharges=0;
}
#_________________
date_default_timezone_set('Asia/Baghdad');
$tim = date('h'.'i'.'s');
$tim1 = date('h:i:s');
$aa = date('a');
$a=str_replace(["am","pm"],["AM","PM"],$aa);
$e=str_replace(["am","pm"],["•","•"],$aa);
$time = "$tim$a";
$D = date('j'); // الايام
$Y = date('Y'); // السنة
$M = date('n'); // الشهر
if($M<10){
$M=str_replace(["1","2","3","4","5","6","7","8","9"],["01","02","03","04","05","06","07","08","09"],$M);
}
if($D<10){
$D=str_replace(["1","2","3","4","5","6","7","8","9"],["01","02","03","04","05","06","07","08","09"],$D);
}
function day_name(){
$ds = array('', '', '', '', '', '', '');
return $ds[Date('w')];
}
$DY = day_name();
function month_name(){
$month_n = array('فارغ','1', '2', '3', '4', '5', '6', '7','8','9','10','11','12');
return $month_n[date('n')];
}
$MH = month_name();
$DAY="$Y$M$D$time";
$DAY2="$D/$MH/$Y $tim1 $e";
$DAY3="$D-$M-$Y | $tim1 $e";
#_________________تعديل معلوماتك في ملف JIMIPHP________________#
include("name.php");
#=========={الإشتراك الإجباري}==========#
if(!in_array($id,$exxx) and $ex_text[1] != null){
$cod=$ex_text[1];
$EEM=$assignment["emils"][$cod];
if($assignment2['my'][$id] == null and $EMILS['emils'][$EEM]['emil'] != null and $EEM != $EM){
file_put_contents("data/id/$id/lift.txt","$EEM");
}
}
$status = bot('getChatMember',['chat_id'=>$sim,'user_id'=>$chat_id])->result->status;
$status = bot('getChatMember',['chat_id'=>$sim1,'user_id'=>$chat_id])->result->status;
$status = bot('getChatMember',['chat_id'=>$sim5,'user_id'=>$chat_id])->result->status;
$status = bot('getChatMember',['chat_id'=>$sim6,'user_id'=>$chat_id])->result->status;
$status = bot('getChatMember',['chat_id'=>$sim7,'user_id'=>$chat_id])->result->status;
if($data == "verification"){
if($status == 'left'){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"💎 قم بالانضمام اولا ",
'parse_mode'=>"MarkDown",
]);
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🤍* يرجى الإنتظار لحظة * ⏳️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[ 
[['text'=>"✔️دخول🔚",'callback_data'=>"startup"]]
]
])
]);
exit;
}
if($data == null and $status == 'left'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
عذرا عزيزي $first يجب الإشتراك في قنوات البوت الرسمية حتى تتمكن من إستخدام البوت...🙋‍♂ 
 إشترك هنا ⏬⏬ ثم إضغط $zozamr 👉

Sorry, dear $first You must subscribe to the official bot channels in order to be able to use the bot...🙋‍♂ 
Subscribe here ⏬⏬ then press $zozamr 👉
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[ 
[['text'=>"🤖 اضغط هنا لدخول قناه الرسمية 🤖", 'url'=>"http://t.me/$usrch1"]],
[['text'=>"🤖 اضغط هنا لدخول قناه التفعيلات 🤖", 'url'=>"http://t.me/$usrch2"]],
[['text'=>"🤖 اضغط هنا لدخول قناه المتوفر 🤖", 'url'=>"http://t.me/$usrch3"]],
[['text'=>"🤖 اضغط هنا لدخول قناه شروحات 🤖", 'url'=>"http://t.me/$yasso"]],
[['text'=>"🤖 اضغط هنا لدخول قناه المطور 🤖", 'url'=>"http://t.me/$usrch4"]],
]
])
]);
exit;
}
if($data != null and $status == 'left'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
عذرا عزيزي $first يجب الإشتراك في قنوات البوت الرسمية حتى تتمكن من إستخدام البوت...🙋‍♂ 
 إشترك هنا ⏬⏬ ثم إضغط $zozamr 👉

Sorry, dear $first You must subscribe to the official bot channels in order to be able to use the bot...🙋‍♂ 
Subscribe here ⏬⏬ then press $zozamr 👉
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[ 
[['text'=>"🤖 اضغط هنا لدخول قناه الرسمية 🤖", 'url'=>"http://t.me/$usrch1"]],
[['text'=>"🤖 اضغط هنا لدخول قناه التفعيلات 🤖", 'url'=>"http://t.me/$usrch2"]],
[['text'=>"🤖 اضغط هنا لدخول قناه المتوفر 🤖", 'url'=>"http://t.me/$usrch3"]], 
[['text'=>"🤖 اضغط هنا لدخول قناه شروحات 🤖", 'url'=>"http://t.me/$yasso"]],
[['text'=>"🤖 اضغط هنا لدخول قناه المطور 🤖", 'url'=>"http://t.me/$usrch4"]],
]
])
]);
exit;
}
#=========={قفل البوت}==========#
if($openandlock['bot']['lock'] == "ok" and $id != $sudo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*البوت تحت الصيانة حاليا , سوف يتم إشعاركم في قناة البوت عند الإكتمال , ونشكر انتظاركم 💙🙂*
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[ 
[['text'=>"☑️ l― قـنـاة الـرسـمـيـة 𓄂 💙",'url'=>"t.me/$usrch1"]]
]
])
]);
exit;
}
#=========={رسالة /start}==========#
$liftchal=file_get_contents("data/id/$id/lift.txt");
if($liftchal != null and $assignment2['my'][$id] == null and $EMILS['emils'][$liftchal]['emil'] != null and $liftchal != $EM){
$EMID=$EMILS['emils'][$liftchal]['id'];
bot('sendMessage',[
'chat_id'=>$EMID,
'text'=>"
قام شخص -: بالدخول إلى فريقك  وحصلت على $assignru 💛 .
عدد أعضاء فريقك الحالي : $counmy 💙
أجمالي ربحك من الأحالة : ₽ $counmyru 💚
𓆩•|ــــــــــ($namebot)ـــــــــ|•𓆪
➖ إسم الشخص : [$first](tg://user?id=$chat_id) 📝.•
",
'parse_mode'=>"MarkDown",
]);
$assignment2['my'][$id] = $liftchal;
Dsai($assignment2);
$points = file_get_contents("EMILS/$liftchal/points.txt");
$as = $points + $assignru;
file_put_contents("EMILS/$liftchal/points.txt",$as);
$rubles = file_get_contents("EMILS/$liftchal/rubles.txt");
$ds = $rubles - $assignru;
file_put_contents("EMILS/$liftchal/rubles.txt",$ds);
$pointall = file_get_contents("data/txt/pointall.txt");
$alls = $pointall + $assignru;
file_put_contents("data/txt/pointall.txt",$alls);
$rubleall = file_get_contents("data/txt/rubleall.txt");
$dlls = $rubleall + $assignru;
file_put_contents("data/txt/rubleall.txt",$dlls);
unlink("data/id/$id/lift.txt");
}
if($ex_text[0] == '/start' and $ex_text[1] != 'ONE' and strpos($ex_text[1],"ID")=== false and $id !== $sudo){
$cod=$ex_text[1];
$EEM=$assignment["emils"][$cod];
$EMID=$EMILS['emils'][$EEM]['id'];
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*القائمة الرئيسية 🏡
      💙 $first 💙*
      
🆔️ : `$chat_id` •
💷 : $Balance ₽•
💵 : $dollar $ •

💙 [ قـــنـــاة البـوت ](http://t.me/$usrch1) 💙
🩷 [ قـنــاة التفعـــيـلات ](http://t.me/$usrch2) 💜
➖ [ من الدول المتوفرة حالياً  ](https://t.me/$usrch3/99) 🇾🇪🇻🇳🇮🇩🇸🇦
➖ [ شرح استخدام البوت  ](http://t.me/$usrch4/11) 💡

           *𓆩•|ــــــــــ( قحطان)ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• شـراء رقم افتـراضي💎 •','callback_data'=>'Buynum']],
[['text'=>'  ✥ عـروTelegramـض 🎯٭','callback_data'=>"Cwt-36"],['text'=>' •  ✥ عـروWhatsAppـض 🎯٭','callback_data'=>"Wo"]],
[['text'=>'• الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>'u0u']],
[['text'=>'• الأكثر توفرآ  🎲 •','callback_data'=>"Available"],['text'=>' • أشحن رصيدك  🎳 •','callback_data'=>'Payment']], 
[['text'=>'• الرشـ%ـق وشحن الألعاب والبرامج 🔭 •','callback_data'=>"Throwing_games"]],
[['text'=>'• ربح الروبل مجانآ 💎 •','callback_data'=>"assignment"]],
[['text'=>' • تحويل الرصيد ↻ •','callback_data'=>"SendCoin"],['text'=>'• الوكلاء  🎲 •','callback_data'=>"gents"]],
[['text'=>'• العـربية / English / فارسى •','callback_data'=>"saavmotamy"]],
[['text'=>'• معلومات حسابك ⚙ •','callback_data'=>"Record"]],
]
])
]);
if(!in_array($id,$exxx) and $assignment2['my'][$id] == null and $cod != null and $EMILS['emils'][$EEM]['emil'] != null and $EEM != $EM){
bot('sendMessage',[
'chat_id'=>$EMID,
'text'=>"
قام شخص -: بالدخول إلى فريقك  وحصلت على $assignru 💛 .
عدد أعضاء فريقك الحالي : $orderall 💙
أجمالي ربحك من الأحالة : ₽ $counmyru 💚
𓆩•|ــــــــــ($namebot)ـــــــــ|•𓆪*
➖ إسم الشخص : [$first](tg://user?id=$chat_id) 📝.•
",
'parse_mode'=>"MarkDown",
]);
$assignment2['my'][$id] = $EEM;
Dsai($assignment2);
$points = file_get_contents("EMILS/$EEM/points.txt");
$as = $points + $assignru;
file_put_contents("EMILS/$EEM/points.txt",$as);
$rubles = file_get_contents("EMILS/$EEM/rubles.txt");
$ds = $rubles - $assignru;
file_put_contents("EMILS/$EEM/rubles.txt",$ds);
$pointall = file_get_contents("data/txt/pointall.txt");
$alls = $pointall + $assignru;
file_put_contents("data/txt/pointall.txt",$alls);
$rubleall = file_get_contents("data/txt/rubleall.txt");
$dlls = $rubleall + $assignru;
file_put_contents("data/txt/rubleall.txt",$dlls);
}
if(!in_array($id,$exxx)){
file_put_contents('data/txt/file.txt',"\n" . $id, FILE_APPEND);
}
unlink("data/id/$id/step.txt");
exit;
}
#=========={لوحة التحكم}==========#
if($data == "whsy"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*?? - عزيزي  $first  💙
اولا :
*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*.
ثانيا :
📮 - تسهيل استخدام البوت للعملاء الجدد ✅️.
ثالثا :
📤 - مساعدتنا في حفظ معلوماتك ومعلومات جميع العملاء بقواعد بياناتنا 💚.
رابعا :
🌐 - لاتستخدم البوت في اشياء تضر الناس بها او تغضب الله ، نبري ذمتنا من اي شخص يستخدم البوت بشكل مضرر او خاطئ ، تذكر انك مراقب من الله عزوجل💙.
😇 - نتمنى لكم إستخداماً ممتعاً 🤍.
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={الحماية}==========#
if($Detector != null){
if($exdata[0] == "Ii" or $exdata[0] == "Xi" or $exdata[0] == "Wi" or $ex_data[0] == "readdd" or $exdata[0] == "Vi" or $data == "YESSend" or $exdata[0] == "YSb" or $data == "login" or $data == "login_2" or $data == "logout" or $data == "sign_in"){
$site = $BUYSNUM[number][$Detector][site];
$number = $BUYSNUM[number][$Detector][phone];
$idnumber = $BUYSNUM[number][$Detector][idnumber];
$finish = $BUYSNUM[number][$Detector][finish];
$times = $BUYSNUM[number][$Detector][times];
$idSend = $BUYSNUM[number][$Detector][idSend];
$status = $BUYSNUM[number][$Detector][status];
$app = $BUYSNUM[number][$Detector][app];
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getStatus&site=$site&app=$app&idnumber=$idnumber&number=$number"),1);
$status = $api[status];
$code = $api[code];
$agen = $api[agen];
$Location = $api[Location];
$api2=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=addBlack&site=$site&app=$app&idnumber=$idnumber&number=$number"),1);
$status2 = $api2[status];
if($user == null){
$uss = "لايوجد ❌";
}else{
$uss = "[@$user]";
}
if($code != null and $status == 1){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>'⚠️ - لايمكنك أن تقوم بشراء أي شيء لأن لديك عملية شراء لم تقم ب إكمالها 🙂',
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}elseif(time() - $times < 120 and $status == 1 and $site == "onlinesim"){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"❌ - لا يمكنك إلغاء الرقم لأن طلبك يحتاج لمرور دقيقتين ♻️",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($code == null and $status == 1){
$BUYSNUM[number][$Detector][status] = -1;
NumbBuys($BUYSNUM,$EM);
$ORDERALL[$idSend][status] = -1;
OrdAll($ORDERALL);
unlink("data/id/$id/restriction.txt");
}
}
}

#=========={تسجيل الدخول}==========#
if($data == "login"){
$emile = $EMIL[$chat_id]['emil'];
$password = $EMILS['emils'][$emile]['pass'];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*القائمة الرئيسية 🏡
      💙 $first 💙*
      
🆔️ : `$chat_id` •
💷 : $Balance ₽•
💵 : $dollar $ •

💙 [ قـــنـــاة البـوت ](http://t.me/$usrch1) 💙
🩷 [ قـنــاة التفعـــيـلات ](http://t.me/$usrch2) 💜
➖ [ من الدول المتوفرة حالياً  ](https://t.me/$usrch3/99) 🇾🇪🇻🇳🇮🇩🇸🇦
➖ [ شرح استخدام البوت  ](http://t.me/$usrch4/11) 💡

           *𓆩•|ــــــــــ( قحطان)ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• شـراء رقم افتـراضي💎 •','callback_data'=>'Buynum']],
[['text'=>'  ✥ عـروTelegramـض 🎯٭','callback_data'=>"Cwt-36"],['text'=>' •  ✥ عـروWhatsAppـض 🎯٭','callback_data'=>"Wo"]],
[['text'=>'• الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>'u0u']],
[['text'=>'• الأكثر توفرآ  🎲 •','callback_data'=>"Available"],['text'=>' • أشحن رصيدك  🎳 •','callback_data'=>'Payment']], 
[['text'=>'• الرشـ%ـق وشحن الألعاب والبرامج 🔭 •','callback_data'=>"Throwing_games"]],
[['text'=>'• ربح الروبل مجانآ 💎 •','callback_data'=>"assignment"]],
[['text'=>' • تحويل الرصيد ↻ •','callback_data'=>"SendCoin"],['text'=>'• الوكلاء  🎲 •','callback_data'=>"gents"]],
[['text'=>'• العـربية / English / فارسى •','callback_data'=>"saavmotamy"]],
[['text'=>'• معلومات حسابك ⚙ •','callback_data'=>"Record"]],
]
])
]);
file_put_contents("data/id/$id/step.txt","login");
exit;
}
if($text != '/start' && $text != null && $step == 'login'){
$pass=$EMILS['emils'][$text]['pass'];
$IDem=$EMILS['emils'][$text]['id'];
if($EMILS['emils'][$text]['emil'] == null){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚠️ *- لم يتم إنشاء حساب ب هذا الإيميل في البوت!*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"↪️ إنشاء حساب جديد ☑️",'callback_data'=>"sign_in"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"startup"]]
]
])
]);
exit;
}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
🖤 *- يرجى الإنتظار يتم فحص الحساب 🎀...*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
sleep(1);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
🔐 *- أرسل كلمة مرور حسابك الأن* ☑️
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"⚠️ - طلب المساعدة",'callback_data'=>"super"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"startup"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","pasword|$text");
exit;
}
if($text != '/start' && $text != null && $exstep[0] == 'pasword'){
$emile = $exstep[1];
$passe = $EMILS['emils'][$emile]['pass'];
if($text !== $passe){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*♻️ - كلمة المرور ليست صحيحة ⛔️*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"startup"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
$get=bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
♻️ *- تم التحقق بنجاح، انتظر قليلا ....*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
sleep(1);
$get=$get->result->message_id;
bot('deletemessage',[
'chat_id'=>$chat_id, 
'message_id'=>$get,
]);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⤵️ *- يمكنك الآن الضغط على الإيميل بالأسفل للتسجيل* ☑️
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$emile",'callback_data'=>"emils-$emile-$passe"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
# قسم الخدمات
if($data == "kadamat"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>مرحبا بك عزيزي : 💙 $first 💙</b> 🏠
<b>▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱ </b>
 <blockquote>✅ قم باختيار الخدمة التي تريد الطلب منها ❤️‍🩹</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'📨 - بريد الكتروني مؤقت  - 📨','callback_data'=>'get_email']],
[['text'=>'• المـنبة والمراقب الذكي 🦑 •','callback_data'=>"observer"]],
[['text'=>'• إحصـ%ـائيات الشـراء الناجح 📠 •','callback_data'=>'Download1']],
[['text'=>'• القنوات والصفحات التابعة للبوت📑 •','callback_data'=>'Follow_my_channels']],
[['text'=>'✅ - قسم المساعده ومميزات البوت - ✅','callback_data'=>'yaqs']],
[['text'=>'⭐️ - تقييم البوت - ⭐️','callback_data'=>'mmmmmm1']],
[['text'=>'- رجوع 🔜','callback_data'=>"back"]], 
]
])
]);
unlink("data/id/$id/step.txt");
}
#
if($data == "Throwing_games"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b> الرشـ%ـق وشحن الألعاب والبرامج 🔭 •</b>
💣 ————————————•
➖<u> زيادة متابعين وتفاعلات ومشاهدات •
➖ سيفرات متعدده وجودة واسعار ممتازة •
➖ شحن الألعاب المختلفة والبرامج •</u>
⇣️ –––––––––––––––––––––•
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• شحن الالعـ 🧨ـاب والبرامج 🚁-','callback_data'=>"EngYASNl0"]],
[['text'=>'• الرشـ%ـق وزيادة المتابعين 🧩 •','callback_data'=>"spit"]],
[['text'=>'- عودة 🔜','callback_data'=>"back"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
#

if($data == "c"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🙋🏻‍♂️ ⌯ أهلاً بك عزيزي المطور في لوحة التحكم الخاصة بك $first 💙.

🏆 - عملة البوت : [ روبل ₽ ].

♻️︙لتغيير نظام البوت ارسل النظام .

✔️︙لربط المواقع ارسل المواقع.
🎰︙لربط التطبيقات ارسل التطبيقات .

✖️︙لتصفير رصيد عضو ارسل تصفير ⛔.
",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                [['text'=>'قسم الرشق ','callback_data'=>"Splash_settings"],['text'=>'قسم شحن الالعاب ','callback_data'=>"Splash_playing"]],
                    [['text'=>'اقسام الخدمات','callback_data'=>"yase"]],
                    [['text'=>"⛔️︙حذف دولة 🔸️.",'callback_data'=>"delnumber"],['text'=>"🌍︙اضافة دولة🔹️.",'callback_data'=>"addnumber"]],
                    [['text'=>"🧲︙خصم روبل₽ 🔸️.",'callback_data'=>"delcoin"],['text'=>"💸︙اضافة روبل₽ 🔹️.",'callback_data'=>"addcoin"]],
                    [['text'=>"💸 اضافه روبل بعد شراء الرقم",'callback_data'=>"Shippingrublenumber"]],
                    [['text'=>"🚫︙تقييد عضو🔸️.",'callback_data'=>"res"],['text'=>"💎︙الغاء تقييد عضو🔹️.",'callback_data'=>"unnum"]],
                    [['text'=>'⚜️︙ربط المواقع بـAPI︙⚜️.','callback_data'=>'counapi']],
                    [['text'=>"📢︙إرسال رسالة 🔸️.",'callback_data'=>"set"],['text'=>"💠︙فتح وقفل الأقسام 🔹️.",'callback_data'=>"opclo"]],
                    [['text'=>"🤖︙إحصائيات البوت🔸️.",'callback_data'=>"baluser"],['text'=>"👥️️︙عدد المشتركين🔹️.",'callback_data'=>"Subscribersy"]],
                    [['text'=>'📱︙كشف الرصيد🔸️.','callback_data'=>'cop'],['text'=>'🃏︙صنع كرت🔹️.','callback_data'=>'card']],
                    [['text'=>"📑︙تسجيلات الدخول", 'url'=>"$ch1"],['text'=>"📌 ︙الارقام الملغية.", 'url'=>"$ch2"]],
                    [['text'=>$auto_available_m,'callback_data'=>"matat"],['text'=>"تعديل رسالة المتوفر ☑️",'callback_data'=>"motawfer"]],
                    [['text'=>"🔔︙الارقام المكتملة.", 'url'=>"$ch3"]],
                    [['text'=>'حذف وكيل ︙⛔️','callback_data'=>'delagent'],['text'=>'إضافة وكيل ︙🧑‍✈️','callback_data'=>'addagent']], 
]
])
]);
unlink("data/id/$id/step.txt");
}

#تغيير ثيمات
# حفظ الثيم المحدد للمستخدم
function saveTheme($userId, $theme) {
    $dataFile = "data/themes.json"; // مسار ملف التخزين
    $themes = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
    $themes[$userId] = $theme;
    file_put_contents($dataFile, json_encode($themes));
}

# استرجاع الثيم الحالي للمستخدم
function getTheme($userId) {
$dataFile = "data/themes.json"; // مسار ملف التخزين
$themes = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
    return isset($themes[$userId]) ? $themes[$userId] : "Theme_rave"; // الافتراضي
}

# استدعاء الثيم المحدد عند عرض البوت
$currentTheme = getTheme($chat_id);

# عرض القائمة الرئيسية
if($data == "Change_themes") {
bot('EditMessageText', [
'chat_id' => $chat_id,
'message_id' => $message_id,
'text' => "
<b>مرحبا بك عزيزي : 💙 $first 💙</b>
<blockquote>✅ هذا قسم ثيمات بوتات مختلفه</blockquote>
<b>⇣️ اختار الثيم الذي تريده من الاسفل ⇣️</b>
",
'parse_mode' => "html",
'reply_markup' => json_encode([
'inline_keyboard' => [
[['text' => '• ثيم بوت الاصلي •', 'callback_data' => "Theme_rave"]],
[['text' => '• ثيم بوت نمبر •', 'callback_data' => "Theme_number"]],
[['text' => '• ثيم بوت بلاص •', 'callback_data' => "Theme_plus"]],
[['text' => '• ثيم بوت البيرو •', 'callback_data' => "Theme_Peru"]],
[['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back']]
]
])
]);
}

# تنفيذ اختيار الثيم وتخزينه
if (in_array($data, ["Theme_rave", "Theme_number", "Theme_plus", "Theme_Peru"])) {
saveTheme($chat_id, $data); // حفظ اختيار المستخدم
bot('answerCallbackQuery', [
'callback_query_id' => $update->callback_query->id,
'text' => '✅ تم تغيير الثيم بنجاح!',
'show_alert' => true
]);
}

# استخدام الثيم المحدد عند الحاجة
if ($currentTheme == "Theme_rave") {
    # كود عرض ثيم Theme_rave
} elseif ($currentTheme == "Theme_number") {
    # كود عرض ثيم Theme_number
} elseif ($currentTheme == "Theme_plus") {
    # كود عرض ثيم Theme_plus
} elseif ($currentTheme == "Theme_Peru") {
    # كود عرض ثيم Theme_Peru
}
#ثيم بوت 𝐑𝐀𝐕𝐄 𝐍𝐔𝐌𝐁𝐄𝐑
if($data == "Theme_rave") {
bot('EditMessageText', [
'chat_id' => $chat_id,
'message_id' => $message_id,
'text' =>"
*القائمة الرئيسية 🏡
      💙 $first 💙*
      
🆔️ : `$chat_id` •
💷 : $Balance ₽•
💵 : $dollar $ •

💙 [ قـــنـــاة البـوت ](http://t.me/$usrch1) 💙
🩷 [ قـنــاة التفعـــيـلات ](http://t.me/$usrch2) 💜
➖ [ من الدول المتوفرة حالياً  ](https://t.me/$usrch3/99) 🇾🇪🇻🇳🇮🇩🇸🇦
➖ [ شرح استخدام البوت  ](http://t.me/$usrch4/11) 💡

           *𓆩•|ــــــــــ( قحطان)ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• شـراء رقم افتـراضي💎 •','callback_data'=>'Buynum']],
[['text'=>'  ✥ عـروTelegramـض 🎯٭','callback_data'=>"Cwt-36"],['text'=>' •  ✥ عـروWhatsAppـض 🎯٭','callback_data'=>"Wo"]],
[['text'=>'• الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>'u0u']],
[['text'=>'• الأكثر توفرآ  🎲 •','callback_data'=>"Available"],['text'=>' • أشحن رصيدك  🎳 •','callback_data'=>'Payment']], 
[['text'=>'• الرشـ%ـق وشحن الألعاب والبرامج 🔭 •','callback_data'=>"Throwing_games"]],
[['text'=>'• ربح الروبل مجانآ 💎 •','callback_data'=>"assignment"]],
[['text'=>' • تحويل الرصيد ↻ •','callback_data'=>"SendCoin"],['text'=>'• الوكلاء  🎲 •','callback_data'=>"gents"]],
[['text'=>'• العـربية / English / فارسى •','callback_data'=>"saavmotamy"]],
[['text'=>'• معلومات حسابك ⚙ •','callback_data'=>"Record"]],
]
])
]);
unlink("data/id/$id/step.txt");
} 
#ثيم بوت 𝐍𝐔𝐌𝐁𝐄𝐑
if($data == "Theme_number") {
bot('EditMessageText', [
'chat_id' => $chat_id,
'message_id' => $message_id,
'text' =>"
*القائمة الرئيسية 🏡
      💙 $first 💙*
🆔️ : `$chat_id` •
💷 : $Balance ₽•
💵 : $dollar $ •

💙 [ قـــنـــاة البـوت ](http://t.me/$usrch1/99999999) 💙
🩷 [ قـنــاة التفعـــيـلات ](http://t.me/$usrch2/99999)𓄂 💜
➖ [ من الدول المتوفرة حالياً  ](https://t.me/$usrch3/9999) 🇾🇪🇻🇳🇮🇩🇸🇦
➖ [ شرح استخدام البوت  ](http://t.me/$usrch1/11) 💡

      *𓆩•|ــــــــــ( $namebot )ـــــــــ|•𓆪
➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>' ✥ شراء رقم  💎٭','callback_data'=>'Buynum']],
[['text'=>'☎️شُــراء رقمـ آفترآضـي☎️','callback_data'=>'Buynum']],
[['text'=>'🎮 - عروض تيليجرام - 🎮','callback_data'=>"Cwt-36"],['text'=>'🎮 - عروض واتساب - 🎮','callback_data'=>"Wo"]],
[['text'=>'خدمة الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>'u0u']],
[['text'=>'⎙ - الأكثر توفرآ - ⎙','callback_data'=>"Available"],['text'=>'🤖 - اشحن رصيدك - 🤖','callback_data'=>'Payment']], 
[['text'=>'• الرشـ%ـق وشحن الألعاب والبرامج 🔭 •','callback_data'=>"Throwing_games"]],
[['text'=>'➕ - ربح الروبل مجانآ - ➕','callback_data'=>"RubleMatana"]],
[['text'=>'⏪ - تحويل الرصيد - ⏩','callback_data'=>"YESSend"],['text'=>'🔥 - الـوكـلاء - 🔥','callback_data'=>"gents"]],
[['text'=>'• العـربية / English / فارسى •','callback_data'=>"saavmotamy"]],
[['text'=>'مــعــلومات حــســابك⚙','callback_data'=>"Record"]],
[['text'=>'• خدمات ومميزات أخرى 🛸 •','callback_data'=>"kadamat"]],
]
])
]);
unlink("data/id/$id/step.txt");
} 
#ثيم بو بلاص
if($data == "Theme_plus") {
bot('EditMessageText', [
'chat_id' => $chat_id,
'message_id' => $message_id,
'text' =>"
*القائمة الرئيسية 🏡
    💙  $first 💙

🆔️* : `$chat_id` •
💲 : $Balance ₽•

➖ 💙[  قناة البوت  ](http://t.me/$usrch1/1850)💙
➖ 💜[  قناة التفعيلات  ](http://t.me/$usrch2/850)💜
➖ 📞[  الأرقام المتوفرة حالياً  ](http://t.me/$usrch3/850)🇸🇦🇻🇳🇪🇬


*       𓆩•|ــــــــــ($namebot)ـــــــــ|•𓆪⇣️
➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>' ✥ شراء رقم  💎٭','callback_data'=>'Buynum']],
[['text'=>' ✥ عروض التليجرام  🎯٭','callback_data'=>"Cw-36"],['text'=>' ✥ عروض الوتس اب  🎯٭','callback_data'=>"Wo"]],[['text'=>' ✥ تحويل الرصيد   ↻٭','callback_data'=>"YESSend"],['text'=>' ✥ أشحن رصيدك  🎳٭','callback_data'=>'Payment']],
[['text'=>'✥ ربح الروبل مجانآ 💎٭','callback_data'=>"assignment"]],
[['text'=>'✥ البـحث السـريـع 🧩٭','callback_data'=>"research-$add"]],
[['text'=>'✥ متجر الكروت  🪗٭','callback_data'=>"readycard-10"],['text'=>'✥ الوكلاء  🎲٭','callback_data'=>"gents"]],
[['text'=>'العربية/English','callback_data'=>"saavmotamy"]],
[['text'=>' ✥ حسابي  👑٭','callback_data'=>"Record"]],
[['text'=>' ✥ المســاعـدة  💡٭','callback_data'=>"super"]], 
[['text'=>'• تغيير ثيم البوت •','callback_data'=>"omlat"]],
]
])
]);
unlink("data/id/$id/step.txt");
} 
# ثيم بوت البيرو
if($data == "Theme_Peru") {
bot('EditMessageText', [
'chat_id' => $chat_id,
'message_id' => $message_id,
'text' =>"
*☑️ - مرحبا بك في القائمة الرئيسية 🏰 
☔️ - لدى بوت $yasein العالمي ⚜*
👮🏻 - اهلا بك عزيزي :  $first 🔥 🤍

*📮 - حسابك : *`$chat_id`  ☑️
*💰 - رصيد حسابك الان *: $Balance ₽  💰
*♟️ -  اخر الاخبار ⇣️*
[اسعار الشحن في البوت انقر للعرض 😍❤️](http://t.me/$usrch1/1850)
*↩️ - تحكم الان بضغط على الازرار بلاسفل ⇣*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• ☎️ شراء أرقام وهمية ☎️ •','callback_data'=>'Buynum']],
[['text'=>'• 💰 شحن حسابك 👨‍💻 •','callback_data'=>'Payment'],['text'=>'• 👤 زيادة المتابعين ➕ •','callback_data'=>'spit']],
[['text'=>'• 🗄 سجل الحساب ☑️ •','callback_data'=>"Record"],['text'=>'• 📍 تليجرام جاهزه 💡 •','callback_data'=>"tg_buttons"]],
[['text'=>'• 🌀 مشاركة رابط الدعوة الخاص بك 💰 •','callback_data'=>"assignment"]],
[['text'=>'• 🔥 عروض تليجرام 🚀 •','callback_data'=>"Cw-36"],['text'=>'• 🛍 قسم العروض 🚀 •','callback_data'=>"Wo"]],
[['text'=>'• 👨‍✈️ قسم طلب المساعده ⚠️ •','callback_data'=>"super"]], 
[['text'=>'• 💥 خدمات ومميزات جديدة 🌟 •','callback_data'=>"kadamat"]], 
]
])
]);
unlink("data/id/$id/step.txt");
} 
#
$admin_id = 6996492261; // ضع هنا ال user_id الخاص بالأدمن

if($data == "yase"){
    $user_id = $update->callback_query->from->id; // استخراج معرف المستخدم من الكول باك
    
    if($user_id == $admin_id){ // التحقق من أنه الأدمن
        bot('EditMessageText',[
        'chat_id'=>$chat_id,
        'message_id'=>$message_id,
        'text'=>"
<b>اليك لوحه التحكم الخاصه بالاقسام عزيزي : 💙 $first 💙</b> 🏠
<b>▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱ </b>
 <blockquote>✅ اختار القسم الذي تريد اضافه له خدمات ❤️‍🩹</blockquote>
",
        'parse_mode'=>"html",
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [['text'=>'قسم توثيق منصات','callback_data'=>"khdma_settings"]],
        [['text'=>'تفعيلات لتحويل العمله','callback_data'=>"thwlat"]],
        [['text'=>'تفعيلات لرشق والالعاب','callback_data'=>"01021"]],
        [['text'=>'تفعيلات حسب طلبك','callback_data'=>"Activations1"]],
        [['text'=>'لرجوع للوحه التحكم 🔙','callback_data'=>'c']]
        ]
        ])
        ]);
        unlink("data/id/$id/step.txt");
    } else {
        // ليس الأدمن، رسالة تنبيه
        bot('answerCallbackQuery',[
        'callback_query_id'=>$update->callback_query->id,
        'text'=>"❌ هذه الخاصية مخصصة للأدمن فقط.",
        'show_alert'=>true
        ]);
    }
}

#الشىراء الذكي @YAS_Nl
if ($data == "observer") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "
<b>خدمة المـنبة والمراقب الذكي 🦑 • </b>
———————————————•
<u><b>| 🐬 ما هذا وما فائدته? 🐬 | </b></u>

<u>هذه الخدمة تم ربطها بقناة تفعيلات البوت الناجحة بحيث تقوم بمراقبتها على مدار 24 ساعة وبشكل تلقائي. يمكنك إدخال رمز الدولة ثم اختيار التطبيق الذي تريد مراقبته، وعند وجود تفعيل جديد يتم إشعارك فورًا.</u> 
<b>⤵▱▱▱▱▱▱▱↷▱▱▱▱▱▱▱▱⤵</b> 
<blockquote> 👮‍♀ يرجى اختيار التطبيق الذي تريد مراقبة تفعيلات ه:</blockquote>
",
        'parse_mode' => "html",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => 'مراقبة تفعيلات 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣', 'callback_data' => 'wati9']],
                [['text' => 'مراقبة تفعيلات 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠', 'callback_data' => 'tili7']],
                [['text' => 'مراقبة تفعيلات G𝗼𝗼𝗴𝗹𝗲', 'callback_data' => 'goog6']],
                [['text' => 'مراقبة تفعيلات 𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞', 'callback_data' => 'fais7']],
                [['text' => 'مراقبة تفعيلات 𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧', 'callback_data' => 'snap5']],
                [['text' => 'مراقبة تفعيلات 𝗛𝗔𝗥𝗔𝗝 app', 'callback_data' => 'hara8']],
                [['text' => 'مراقبة تفعيلات 𝗧𝗜𝗞𝗧𝗢𝗞', 'callback_data' => 'tlk4']],
                [['text' => 'مراقبة تفعيلات 𝗜𝗠𝗢 app', 'callback_data' => 'lmo1']],
                [['text' => 'مراقبة تفعيلات 𝗧𝗪𝗜𝗧𝗧𝗘𝗥', 'callback_data' => 'twlt4']],
                [['text' => '❗️حذف دولة من المنبه❗️', 'callback_data' => 'delete_country']],
                [['text' => 'عودة 🔙', 'callback_data' => "back"]]
            ]
        ])
    ]);
    file_put_contents("data/id/$id/step.txt", "select_app");
}

// اختيار التطبيق
if (in_array($data, ['wati9', 'tili7', 'goog6', 'fais7', 'snap5', 'hara8', 'tlk4', 'lmo1', 'twlt4'])) {
    $apps = [
        "wati9" => "𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣",
        "tili7" => "𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠",
        "goog6" => "𝗚𝗼𝗼𝗴𝗹𝗲",
        "fais7" => "𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞",
        "snap5" => "𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧",
        "hara8" => "𝗛𝗔𝗥𝗔𝗝 app",
        "tlk4" => "𝗧𝗜𝗞𝗧𝗢𝗞",
        "lmo1" => "𝗜𝗠𝗢 app",
        "twlt4" => "𝗧𝗪𝗜𝗧𝗧𝗘𝗥"
    ];
    $selected_app = $apps[$data];
    file_put_contents("data/id/$id/app.txt", $selected_app);
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "✅ تم اختيار التطبيق: $selected_app.\n\nأرسل الآن رمز الدولة (مثال : +20 ) :",
    ]);
    file_put_contents("data/id/$id/step.txt", "enter_country");
}

// إدخال رمز الدولة
if ($step == "enter_country" && isset($text)) {
    $country_code = trim($text);
    $countries = [
        "+1" => "أمريكا 🇺🇸",
        "+509" => "هايتي 🇭🇹",
        "+218" => "ليبيا 🇱🇾",
        "+20" => "مصر 🇪🇬",
        // يمكن إضافة المزيد هنا
    ];
    $country_name = $countries[$country_code] ?? "غير معروف";

    if ($country_name == "غير معروف") {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "❌ رمز الدولة غير معروف. حاول مرة أخرى."
        ]);
    } else {
        $selected_app = file_get_contents("data/id/$id/app.txt");
        $monitored_data = @file_get_contents("data/id/$id/monitored.txt") ?: "";
        if (strpos($monitored_data, "$selected_app|$country_code") !== false) {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "❌ الدولة $country_name ($country_code) مضافة مسبقًا إلى التطبيق: $selected_app."
            ]);
        } else {
            file_put_contents("data/id/$id/monitored.txt", "$selected_app|$country_code|$country_name\n", FILE_APPEND);
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "
              📝 تم تسجيل الدولة في ذاكرة النظام إذا تم تفعيل رقم لهذة الدولة سيصلك إشعار علا الفور.
➖ الدولة : $country_name
➖ برنامج : $selected_app
➖ تبدا المراقبة : $DAY3
"
            ]);
        }
        unlink("data/id/$id/step.txt");
    }
}

// حذف دولة
if ($data == "delete_country") {
    $monitored_data = @file_get_contents("data/id/$id/monitored.txt") ?: "لا توجد دول مضافة.";
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "✅ قائمة الدول المضافة:\n$monitored_data\n\nأرسل اسم الدولة ورمزها (مثال : +20 مصر 🇪🇬 ) لحذفها.",
    ]);
    file_put_contents("data/id/$id/step.txt", "delete_entry");
}

if ($step == "delete_entry" && isset($text)) {
    $monitored_data = @file("data/id/$id/monitored.txt") ?: [];
    $new_data = [];
    $found = false;

    foreach ($monitored_data as $line) {
        if (strpos($line, $text) === false) {
            $new_data[] = $line;
        } else {
            $found = true;
        }
    }

    if ($found) {
        file_put_contents("data/id/$id/monitored.txt", implode("", $new_data));
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "✅ تم حذف الدولة: $text بنجاح."
        ]);
    } else {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "❌ لم يتم العثور على الدولة: $text."
        ]);
    }
    unlink("data/id/$id/step.txt");
}
#ربح الروبل مجانا @YAS_Nl
if($data == "RubleMatana"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>مرحبا : 💙 $first 💙</b>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱</b>
<blockquote>هذه قسم ربح الروبل مجانا ✅</blockquote> 
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱</b>
<blockquote>⇣️اختار الذر الذي تريد ان تربح الروبل من خلاله ⇣️</blockquote> 
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'🎁 ربط الدعوه الخاص بك 🎁','callback_data'=>'assignment']],
[['text'=>'💰 البحث عن الكنز 💰','callback_data'=>'GuidanceDepartment']],
[['text'=>'- رجوع 🔜','callback_data'=>"back"]]
]
])
]);
unlink("data/id/$id/step.txt");
} 

#تلي جاهز
if($data == "tg_uttons"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>• بيع وشراء حسابات تلجـTelegramـرام جاهزة 🎮</b>
————–––––––––––––––––––———
<b>يمكنك عبر هذا القسم شراء حسابات جاهزه وبيع حسابات تلجرام جاهزة ايضا بشكل تلقائي وسريـع وأمن 100% ٭</b>

<blockquote> ✅ رصيـدك بعملـة الروبل ₽ = $Balance </blockquote> 
<blockquote> ✅ رصيـدك بعملـة الدولار $ =  0 •</blockquote> 

<b> معلومة : عندما تقوم ببيع حسابات للبوت سيتم الدفع لك بعمله الدولار بعدها يمكنك تحويل رصيدك الدولار الى روبل لشراء الارقام من جميع اقسام البوت او سحبة عبر عده طرق دفع متوفره ✓ •</b>

      <b>𓆩•|ــــــــ( $yasein )ـــــــ|•𓆪
⤵▱▱▱▱▱▱▱↷▱▱▱▱▱▱▱▱⤵</b>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'☎️ - شـراء حسابات Telegram - ☎️','callback_data'=>"tg_buttons"]], 
[['text'=>'☎️ - بيـع حسابات Telegram - ☎️','callback_data'=>"Sellingaccounts"]],
[['text'=>'- عودة 🔜','callback_data'=>"back"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
#البريد
$owaset = "/create_email";
#ايميل وهمي @YAS_Nl
if ($data == "get_email") {
    // إنشاء بريد إلكتروني عشوائي
    $username = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 10);
    $email = "$username@1secmail.com";
    
    // حفظ البريد الإلكتروني للمستخدم
    file_put_contents("data/$callback_chat_id/email.txt", $email);
    
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
<b>تم إنشاء بريد اكتروني مؤقت بنجاح 📨</b>

<b>➖ ألـبريـد :</b> <code>$email</code> • 
<b>➖ ألـمدة : لايوجد (دائـم)  • </b>
<b>➖ السعر : مـجـانـي • </b>
<b>➖ رصيدك : $Balance ₽ • </b>
<b>• إرسل الامر /create_email لجلب بريد اخر ✓ </b>

",
        'parse_mode' => "html",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '📨 صندوق البريد', 'callback_data' => 'check_inbox']]
            ]
        ])
    ]);
    exit;
}

if ($data == "check_inbox") {
    // قراءة البريد الإلكتروني المخزن
    $email = file_get_contents("data/$chat_id/email.txt");
    if (!$email) {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "<b>📭 لا توجد رسائل جديدة.</b>",
            'parse_mode' => "html"
        ]);
        exit;
    }
    
    // جلب الرسائل الواردة
    $parts = explode("@", $email);
    $username = $parts[0];
    $domain = $parts[1];
    $url = "https://www.1secmail.com/api/v1/?action=getMessages&login=$username&domain=$domain";
    $messages = json_decode(file_get_contents($url), true);
    
    if (!empty($messages)) {
        $inboxText = "<b>📬 الرسائل الواردة:</b>\n\n";
        foreach ($messages as $msg) {
            $inboxText .= "— <b>المرسل:</b> {$msg['from']}\n";
            $inboxText .= "— <b>الموضوع:</b> {$msg['subject']}\n";
            $inboxText .= "➖➖➖➖➖\n";
        }
    } else {
        $inboxText = "<b>📭 لا توجد رسائل جديدة.</b>";
    }
    
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => $inboxText,
        'parse_mode' => "html"
    ]);
    exit;
}
#
if ($text == $owaset ) {
    // إنشاء بريد إلكتروني عشوائي
    $username = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 10);
    $email = "$username@1secmail.com";
    
    // حفظ البريد الإلكتروني للمستخدم
    file_put_contents("data/$callback_chat_id/email.txt", $email);
    
    bot('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "
<b>تم إنشاء بريد اكتروني مؤقت بنجاح 📨</b>

<b>➖ ألـبريـد :</b> <code>$email</code> • 
<b>➖ ألـمدة : لايوجد (دائـم)  • </b>
<b>➖ السعر : مـجـانـي • </b>
<b>➖ رصيدك : $Balance ₽ • </b>
<b>• إرسل الامر /create_email لجلب بريد اخر ✓ </b>

",
        'parse_mode' => "html",
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '📨 صندوق البريد', 'callback_data' => 'check_inbox']]
            ]
        ])
    ]);
    exit;
}
#الاكثر توفيرا @YAS_Nl
if($data == "Available"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>مرحبا : 💙 $first 💙</b>

<b>➖ قسم الاكثر توفرا لجميع البرامج 💚 ٭</b>

<b>➖ كل ماعليك هو اختيار البرنامج ومن ثم سيتم نقلك الا عده دول اختر اي دوله وقم بالبحث في سيفراتها المتنوعه  🤍 ٭</b> 

     𓆩•|ــــــــــ($yasein)ـــــــــ|•𓆪⇣️
⤵▱▱▱▱▱▱▱↷▱▱▱▱▱▱▱▱⤵
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ الاكـثر توفرا في 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣 🛍 •','callback_data'=>'Kn2-2-7']],
[['text'=>'✥ الاكـثر توفرا في 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠 🎲 •','callback_data'=>'Kn2-3-7']],
[['text'=>'✥ الاكـثر توفرا في 𝗟𝗡𝗦𝗧𝗔𝗚𝗥𝗔𝗠 🎳 ٭ •','callback_data'=>'Kn2-5-7']],
[['text'=>'✥ الاكـثر توفرا في 𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞 🎯','callback_data'=>'Kn2-4-7']],
[['text'=>'✥ الاكـثر توفرا في 𝗧𝗪𝗜𝗧𝗧𝗘𝗥 🐤','callback_data'=>'Kn2-6-7']],
[['text'=>'✥ الاكـثر توفرا في G𝗼𝗼𝗴𝗹𝗲 ⛱','callback_data'=>'Kn2-8-7']],
[['text'=>'✥ الاكـثر توفرا في 𝗧𝗜𝗞𝗧𝗢𝗞 🎥','callback_data'=>'Kn2-7-7']],
[['text'=>'✥ الاكـثر توفرا في 𝗛𝗔𝗥𝗔j 🪗','callback_data'=>'Kn2-8-7']],
[['text'=>'✥ الاكـثر توفرا في 𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧 ♣️','callback_data'=>'Kn2-11-7']],
[['text'=>'✥ الاكـثر توفرا في  𝗜𝗠𝗢 💎','callback_data'=>'Kn2-9-7']],
[['text'=>'✥ الاكـثر توفرا في السيرفر العام 🤖','callback_data'=>'Kn2-14-7']],
[['text'=>'- رجوع 🔜','callback_data'=>"kadamat"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
#============{بايير تلقائى}=========#
if($data == "payeer"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>💻 الرجاء إرسال المبلغ اللذي تريد شحنة في حسابك ( عدد الدولارات ) للعلم إن أقل مبلغ للشحن 0.1 :</b>
",
'parse_mode'=>"html",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- رجوع.','callback_data'=>'Payment']]
]
])
]);
file_put_contents("data/id/$id/step.txt","payeer");
}
if($text && $text !== "/start" and $step == 'payeer'){
$cb = str_replace("index.php","payment.php","https://" . $_SERVER['SERVER_NAME'] . "" . $_SERVER['SCRIPT_NAME']);
$link = $cash->createPayeerPaymentLink($text,$cb,$chat_id);
$link = $cash->redirect($link);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
<b>✅ تم إنشاء الطلب إنتقل عبر الرابط في الاسفل لدفع.</b>
———————————————•
<u>➖  بعد إتمام سيتم إضافة الرصيد الى حسابك تلقائي 
➖ سوف يتطلب منك معرف العملية
➖ يجب ان يكون التحويل بعملة الدولار فقط </u>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• رابـط الـدفع 💲 •','url'=>$link]],
[['text'=>'- رجوع.','callback_data'=>'Payment']]
]
])
]);
unlink("data/id/$id/step.txt");
}
#=========={إنشاء حساب}=========#
if($data == "sign_in"){
$margin=rand(100000,999999);
if($EMIL[$chat_id]['emil'] != null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
• 👥️️︙عزيزي [ $first ] •.
• 🔎︙ تم التحقق من قبل ويمكنك مواصلة الإستخدام عند إرسال $zozamr 💙•.
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
➖➖➖➖➖➖➖➖➖➖➖➖
•👤 - ليتم التحقق من حسابك قم بالاتي : •

• 1️⃣ - قم بكتابة الرقم الظاهر ادناه `$margin` 🔍 •

• 2️⃣ - ارسل الرقم الذي قمت بكتابة 📝 •
➖➖➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
]
])
]);
file_put_contents("data/id/$id/step.txt","sign_in|$margin");
exit;
}
if($text != '/start' && $text != null && $exstep[0] == 'sign_in'){
$margin = $exstep[1];
$xzz = "$chat_id";
$code = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"),0-7);
$emile = "$chat_id";
$password = substr(str_shuffle("$chat_id"),0-0);
if($user == null){
$uss = "لايوجد ❌";
}else{
$uss = "[@$user]";
}
if($EMILS['emils'][$emile]['emil'] == $emile){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*❌ -  عذرا عزيزي حدث خطأ في البوت أرجوا إعادة المحاولة مرة أخرى 🙃*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭. 🔙','callback_data'=>"startup"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
if($margin != $text){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
➖️*❌ - إجابة غير صحيحة! حاول مجددا.*
تكرار الإجابات الخاطئة قد يؤدي الى حظر حسابك بشكل نهائي. ✅️
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ اعاده التحقق ٭.','callback_data'=>"sign_in"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
➖➖➖➖➖➖➖➖➖➖➖➖
🌚 - رائع! - لقد قمت بكتابة الرقم بشكل صحيح 😗!

📲 } يتم إنشاء حسابك تلقائياً وسيرسل بالاسفل😍 .*
➖➖➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message->message_id,
]);
sleep(1);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*القائمة الرئيسية 🏡
      💙 $first 💙*
      
🆔️ : `$chat_id` •
💷 : $Balance ₽•
💵 : $dollar $ •

💙 [ قـــنـــاة البـوت ](http://t.me/$usrch1) 💙
🩷 [ قـنــاة التفعـــيـلات ](http://t.me/$usrch2) 💜
➖ [ من الدول المتوفرة حالياً  ](https://t.me/$usrch3/99) 🇾🇪🇻🇳🇮🇩🇸🇦
➖ [ شرح استخدام البوت  ](http://t.me/$usrch4/11) 💡

      *𓆩•|ــــــــــ( قحطان)ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• شـراء رقم افتـراضي💎 •','callback_data'=>'Buynum']],
[['text'=>'  ✥ عـروTelegramـض 🎯٭','callback_data'=>"Cwt-36"],['text'=>' •  ✥ عـروWhatsAppـض 🎯٭','callback_data'=>"Wo"]],
[['text'=>'• الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>'u0u']],
[['text'=>'• الأكثر توفرآ  🎲 •','callback_data'=>"Available"],['text'=>' • أشحن رصيدك  🎳 •','callback_data'=>'Payment']], 
[['text'=>'• الرشـ%ـق وشحن الألعاب والبرامج 🔭 •','callback_data'=>"Throwing_games"]],
[['text'=>'• ربح الروبل مجانآ 💎 •','callback_data'=>"assignment"]],
[['text'=>' • تحويل الرصيد ↻ •','callback_data'=>"SendCoin"],['text'=>'• الوكلاء  🎲 •','callback_data'=>"gents"]],
[['text'=>'• العـربية / English / فارسى •','callback_data'=>"saavmotamy"]],
[['text'=>'• معلومات حسابك ⚙ •','callback_data'=>"Record"]],
]
])
]);
bot('sendMessage',[
'chat_id'=>$eer,
'text'=>"
➖ تم دخول شخص جديد الى البوت 👤•
➖ إسم الشخص : [$first](tg://user?id=$chat_id) 📝.•
➖ الحاله : تم التحقق وليس روبوت ✅️ •
➖ ايديه : `$emile` 🆔️•

➖ معرف حسابه : $uss 📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ التحقق من الشخص٭",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
$EMIL[$chat_id]['emil'] = $emile;
$EMIL[$chat_id]['pass'] = $password;
$EMIL[$chat_id]['Date_created'] = "$D/$M/$Y $tims";
Aemil($EMIL);
$EMILS['emils'][$emile]['emil'] = $emile;
$EMILS['emils'][$emile]['pass'] = $password;
$EMILS['emils'][$emile]['Date_created'] = "$D/$M/$Y $tims";
$EMILS['emils'][$emile]['id'] = $chat_id;
Bemil($EMILS);
unlink("data/id/$id/step.txt");
exit;
}

#=========={دخول للبوت}==========#
if($exdata[0] == "emils"){
$emile = $exdata[1];
$password = "$exdata[2]";
$pase = $EMILS['emils'][$emile]['pass'];
$pase = "$pase";
$idEM = $EMILS['emils'][$emile]['id'];
$rubles=file_get_contents("EMILS/$emile/rubles.txt"); #الرصيد اللكلي#
$Balance = file_get_contents("EMILS/$emile/points.txt"); #رصيد العضو#
$consumers = $rubles-$Balance; #عدد الروبل المستهلك#
if($Baalanc==null){
$Baalanc=0;
}
if($consumsre==null){
$consumsre=0;
}
if($user == null){
$uss = "لايوجد ❌";
}else{
$uss = "[@$user]";
}
if($EMILS['emils'][$emile]['emil'] == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
⚠️ - عذرا هذا الحساب قد تم حذفة من البوت بشكل كامل ❎
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($password !== $pase and $sudo != $id){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
⚠️ - عذرا كلمة المرور ل هذا الحساب قد تم تغييرة ☑️
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*القائمة الرئيسية 🏡
      💙 $first 💙*
      
🆔️ : `$chat_id` •
💷 : $Balance ₽•
💵 : $dollar $ •

💙 [ قـــنـــاة البـوت ](http://t.me/$usrch1) 💙
🩷 [ قـنــاة التفعـــيـلات ](http://t.me/$usrch2) 💜
➖ [ من الدول المتوفرة حالياً  ](https://t.me/$usrch3/99) 🇾🇪🇻🇳🇮🇩🇸🇦
➖ [ شرح استخدام البوت  ](http://t.me/$usrch4/11) 💡

           *𓆩•|ــــــــــ( قحطان)ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• شـراء رقم افتـراضي💎 •','callback_data'=>'Buynum']],
[['text'=>'  ✥ عـروTelegramـض 🎯٭','callback_data'=>"Cwt-36"],['text'=>' •  ✥ عـروWhatsAppـض 🎯٭','callback_data'=>"Wo"]],
[['text'=>'• الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>'u0u']],
[['text'=>'• الأكثر توفرآ  🎲 •','callback_data'=>"Available"],['text'=>' • أشحن رصيدك  🎳 •','callback_data'=>'Payment']], 
[['text'=>'• الرشـ%ـق وشحن الألعاب والبرامج 🔭 •','callback_data'=>"Throwing_games"]],
[['text'=>'• ربح الروبل مجانآ 💎 •','callback_data'=>"assignment"]],
[['text'=>' • تحويل الرصيد ↻ •','callback_data'=>"SendCoin"],['text'=>'• الوكلاء  🎲 •','callback_data'=>"gents"]],
[['text'=>'• العـربية / English / فارسى •','callback_data'=>"saavmotamy"]],
[['text'=>'• معلومات حسابك ⚙ •','callback_data'=>"Record"]],
]
])
]);
$EMILNow['emil'][$chat_id] = $emile;
$EMILNow['password'][$chat_id] = $pase;
Now($EMILNow);
unlink("data/id/$id/step.txt");
}
}
#=========={القائمة الرئيسية}==========#
if($data == "back"){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*القائمة الرئيسية 🏡
      💙 $first 💙*
      
🆔️ : `$chat_id` •
💷 : $Balance ₽•
💵 : $dollar $ •

💙 [ قـــنـــاة البـوت ](http://t.me/$usrch1) 💙
🩷 [ قـنــاة التفعـــيـلات ](http://t.me/$usrch2) 💜
➖ [ من الدول المتوفرة حالياً  ](https://t.me/$usrch3/99) 🇾🇪🇻🇳🇮🇩🇸🇦
➖ [ شرح استخدام البوت  ](http://t.me/$usrch4/11) 💡

           *𓆩•|ــــــــــ( قحطان)ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• شـراء رقم افتـراضي💎 •','callback_data'=>'Buynum']],
[['text'=>'  ✥ عـروTelegramـض 🎯٭','callback_data'=>"Cwt-36"],['text'=>' •  ✥ عـروWhatsAppـض 🎯٭','callback_data'=>"Wo"]],
[['text'=>'• الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>'u0u']],
[['text'=>'• الأكثر توفرآ  🎲 •','callback_data'=>"Available"],['text'=>' • أشحن رصيدك  🎳 •','callback_data'=>'Payment']], 
[['text'=>'• الرشـ%ـق وشحن الألعاب والبرامج 🔭 •','callback_data'=>"Throwing_games"]],
[['text'=>'• ربح الروبل مجانآ 💎 •','callback_data'=>"assignment"]],
[['text'=>' • تحويل الرصيد ↻ •','callback_data'=>"SendCoin"],['text'=>'• الوكلاء  🎲 •','callback_data'=>"gents"]],
[['text'=>'• العـربية / English / فارسى •','callback_data'=>"saavmotamy"]],
[['text'=>'• معلومات حسابك ⚙ •','callback_data'=>"Record"]],
]
])
]);
unlink("data/id/$id/step.txt");
unlink("data/id/$id/twas.txt");
exit;
}
}
if($data=="sh"){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"- عذراً عزيزي $first 💙
- لاتوجد سيرفرات حاليا",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={الحماية}==========#
if($text != null and $text != 'my' and $text != '/start ' and $step != null and $twas != 'tw' and $id != $sim and $id != $usrch2number and $id != $PAY and $id != $ess and $id != $eer and $id != $sudo){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*

➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
}
if($ex_text[0] == '/start' and strpos($ex_text[1],"ID")!== false){
$idSend = str_replace('ID','',$ex_text[1]);
$EEM=$ORDERALL[$idSend][account];
$order=$ORDERALL[$idSend][order];
$BUYSNUM_S = json_decode(file_get_contents("EMILS/$EEM/number.json"),true);
$zero=$BUYSNUM_S[number][$order][zero];
$price=$buy['number'][$zero][price];
if($price > $Balance or $Balance < $price or $Balance == 0 or $Balance === 0 or $Balance < 0){
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ *- لايوجد لديك رصيد كافي لشراء هذا الرقم
💸 - رصيدك المتوفر في حسابك >> ₽ $Balance*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message->message_id,
]);
unlink("data/id/$id/step.txt");
exit;
}
}
if($exdata[0] == "Ii" or $exdata[0] == "Xi"){
$zero = $exdata[1];
$zero = md5($zero);
$price=$buy['number'][$zero][price];
if($price > $Balance or $Balance < $price or $Balance == 0 or $Balance === 0 or $Balance < 0){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ رصيدك غير كافي ❤
➖ رصيدك الحالي ₽ $Balance 💙
",
'parse_mode'=>"MarkDown",
[['text'=>' رجوع ','callback_data'=>"back"]]
]);
unlink("data/id/$id/step.txt");
exit;
}
}
if($exdata[0] == "Vi"){
if($exdata[2] > $Balance or $Balance < $exdata[2] or $Balance == 0 or $Balance === 0 or $Balance < 0){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"⚠️ - رصيدك غير كافية لشراء بطاقة الشحن 💰",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
}
if($ex_data[0] == "readdd"){
if($storenumber['ready'][$ex_data[1]]['price'] > $Balance or $Balance < $storenumber['ready'][$ex_data[1]]['price'] or $Balance == 0 or $Balance === 0 or $Balance < 0){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ رصيدك غير كافي ❤
➖ رصيدك الحالي ₽ $Balance 💙
",
'parse_mode'=>"MarkDown",
[['text'=>' رجوع ','callback_data'=>"back"]]
]);
unlink("data/id/$id/step.txt");
exit;
}
}
if($exdata[0] == "YSg" or $exdata[0] == "YSb"){
if($exdata[2] > $Balance or $Balance < $exdata[2] or $Balance == 0 or $Balance === 0 or $Balance < 0){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"⚠️ - رصيدك غير كافية لتحويل رصيد إلى الأصدقاء 💰",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
}
if($data == "YESSend"){
if(0 > $Balance or $Balance < 0 or $Balance == 0 or $Balance === 0 or $Balance < 0){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"🚫- - خطأ | رصيدك غير كافي للتحويل .
📊- رصيدك | $Balance ₽.
- عمولة التحويل : 1%.

📮- الرجاء شحن حسابك والمحاوله لاحقاً.",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
}
if($admins[$EM]["check"] != null and time() - $admins[$EM]["time"] >= $admins[$EM]["end"]){
unset($admins[$EM]);
Admins($admins);
}
if($exdata[0] == "Ii" or $exdata[0] == "Xi" or $exdata[0] == "Wi" or $exdata[0] == "Vi" or $exdata[0] == "YSb" or $exdata[0] == "YSg" or $data == "YESSend" or $ex_data[0] == "readdd"){
$v=$admins[$EM]["end"]-(time() - $admins[$EM]["time"]);
if($admins[$EM]["check"] != null and time() - $admins[$EM]["time"] < $admins[$EM]["end"]){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
⚠️ *- تم تقييدك من البوت لسبب مخالفة مالك البوت ولن تستطيع الإستفاده من البوت ب هذا الشكل
♻️ - إذا تظن أنك مقيد لأسباب خاطئة تواصل مع الدعم الأنلاين في الزر الأسفل
⏰ - الوقت المتبقي للتقييد: $v ثانية*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'🚀 تواصل مع الدعم 🚀','callback_data'=>"super"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
}
#=========={الإحالات}===========#
if($data == "assignment"){
if($assignment["code"][$EM] == null){
$cod=substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), -8);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💙 - مرحبا 💙  $first 💙

💲 إربح روبل مجاناً قم بمشاركة رابط البوت مع أصدقائك 🤵🏻
- سوف تربح $assignru روبل مقابل كل شخص يقوم بالدخول إلى البوت عبر الرابط الخاص بك 🧿

💚 - رابط الدعوة الخاص بك 💚 : https://t.me/$me?start=$cod
👤 - عدد الاشخاص الذين سجلوا الدخول بالبوت عبر رابط احالتك
$counmy 🤍🔚
✔️- إجمالي أرباحك حتى الآن: $counmyru ₽* 💰
رصيد الحالي $Balance ₽ 😮‍💨.
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- رجوع.','callback_data'=>'back']]
]
])
]);
$assignment["emils"][$cod] = $EM;
$assignment["code"][$EM] = $cod;
Ssai($assignment);
unlink("data/id/$id/step.txt");
}
if($assignment["code"][$EM] != null){
$cod=$assignment["code"][$EM];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*مرحبا بك عزيزي : 💙 $first 💙*
☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱☠
*
💲 إربح روبل مجاناً قم بمشاركة رابط البوت مع أصدقائك 🤵🏻
- سوف تربح $assignru روبل مقابل كل شخص يقوم بالدخول إلى البوت عبر الرابط الخاص بك 🧿
*
*💚 - رابط الدعوة الخاص بك 💚* : `https://t.me/$me?start=$cod`
اضغط لنسخ 👆
*☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱☠*
إجمالي أرباحك : $counmyru ₽
رصيد الحالي : $Balance ₽
*▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱*
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- رجوع.','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={شراء كروت}==========#
if($exdata[0] == "readycard"){
$pri=$exdata[1];
$pri="$pri.00";
if($pri < 10 or $pri > 10000){
unlink("data/id/$id/step.txt");
}else{
$price=$pri*0.04+$pri;
$t=$pri+1;
$i=$pri-1;
$v=$pri+10;
$k=$pri-10;
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- عبر هذا القسم تستطيع شراء كرت شحن في البوت وبيعة او إهدائة لأي صديق لك*
✅ - يتم تحديث *السعر تلقائياً عند الضغط على (➕ أو ➖ )* ⇣️

🅿️ - قيمة الكرت: *₽ $pri*
💸 - سعر الكرت: *₽ $price*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ 💳 انتاج كرت شحن قيمة [₽ $pri ]٭",'callback_data'=>"Vi-$pri-$price"]],
[['text'=>"➖ 1 ₽",'callback_data'=>"readycard-$i"],['text'=>"➕ 1 ₽",'callback_data'=>"readycard-$t"]],
[['text'=>"➖ 10 ₽",'callback_data'=>"readycard-$k"],['text'=>"➕ 10 ₽",'callback_data'=>"readycard-$v"]],
[['text'=>' ✥ 📤 شحن رصيد بكرت ٭','callback_data'=>'Card']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
if($exdata[0] == "Vi"){
$point = $exdata[1];
$price = $exdata[2];
$cardbot2=$cardbot+1;
$card = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), -8);
$idcard = substr(str_shuffle('12345689807'),1,9);
$pri = $price - $point;
$Balancee = $Balance - $price;
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارجوا الانتظار ●○
?? - الكرت قيد التجهيز* ♻️
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message->message_id,
]);
sleep(1);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- ارجوا الانتظار ●●○
🧩 - جاري شحن الكرت بقيمة ₽ $points* ♻️
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message->message_id,
]);
sleep(1);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- تم التجهيز بنجاح ●●●
🧩 - تم صنع كرت شحن بقيمة ₽ $points* ✅
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message->message_id,
]);
sleep(1);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- تم شراء الكرت بنجاح* ↘️

🎛 - أيدي المعاملة: *$idcard*
💰 - سعر الكرت: *₽ $price*
💸 - فئة الكرت: *₽ $point*
🎟 - الكرت: `$card` ⏭

✅ - *إضغط على الكرت ليتم نسخة!*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message->message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ صنع كرت شحن آخر! ↖️",'callback_data'=>'readycard-10']],
[['text'=>"✥ عودة ↩ ٭..",'callback_data'=>"back"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$eer,
'text'=>"
🎟 - تم شراء كرت الآن ⚜

🅿️ - فئة الكرت: *₽ $point*
🛅 - الكرت: *$card*
💰 - رصيده المتبقي: *$Balancee*
🧭 - رقم العملية: *$idcard*
🛃 - سعر الكرت: *₽ $price*
🤸‍♂ - الحساب: *$EM* 🌐
🔎 - رقم العمل: *$cardbot2*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
$sool['card'][$card][amount] = $point;
$sool['card'][$card]['idcar'] = $EM;
$sool['card'][$card]['order'] = $cardmy;
$sool['card'][$card]['name'] = $first;
Sool($sool);
$BUYSCARD[$cardmy][idCard] = $cardbot2;
$BUYSCARD[$cardmy][card] = $card;
$BUYSCARD[$cardmy][price] = $price;
$BUYSCARD[$cardmy][id] = $idcard;
$BUYSCARD[$cardmy][status] = -1;
$BUYSCARD[$cardmy][amount] = $point;
$BUYSCARD[$cardmy]["chat-id"] = $id;
$BUYSCARD[$cardmy][emil] = $EM;
$BUYSCARD[$cardmy][DAY] = $DAY;
CardBuys($BUYSCARD,$EM);
$points = file_get_contents("EMILS/$EM/points.txt");
$as = $points - $price;
file_put_contents("EMILS/$EM/points.txt",$as);
$pointall = file_get_contents("data/txt/pointall.txt");
$alls = $pointall - $price;
file_put_contents("data/txt/pointall.txt",$alls);
$ORDERALL[card] +=1;
OrdAll($ORDERALL);
unlink("data/id/$id/step.txt");
}
#=========={شحن الرصيد}==========#
if($data == "Payment"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>مرحبا بك عزيزي : 💙 $first 💙</b>
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱☠</b>

<blockquote>💰 - تستطيع شحن حسابك الان عبر طرق الدفع الظاهره امامك 📡</blockquote>
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱☠</b>

➖ اسعار الشحن : <a href='https://t.me/c/27242/4'><b>💵 - اضغط هنا - 💵</b></a>\n 
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱☠</b>
",
'parse_mode'=>"html",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>' - شحن رصيدك من الإدارة - ','url'=>"tg://user?id=$sudo"]],
[['text'=>'🏆 - متجر الكروت - 🏆','callback_data'=>"readycard-10"]],
[['text'=>'🔄 - رجوع للخلف - 🔄','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}
if($data == "vfcash"){
$vf_n = $cash->getNumber();
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - *مرحباً بك عزيزي, قم بالتحويل على رقم فودافون كاش التالى * ⇣️
    - `".$vf_n."`
ثم أرسل الرقم الذى قمت بالتحويل منه
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- رجوع.','callback_data'=>'Payment']]
]
])
]);
file_put_contents("data/id/$id/step.txt","vfcash");
}
if($text && $text !== "/start" and $step == 'vfcash'){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ - ارسل المبلغ الذى قمت بتحويلة !

💸 - رقم المحفظة : *$text*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id
]);
file_put_contents("data/id/$id/step.txt","vfcash ".$text);
}
if($text && $text !== "/start" and explode(" ",$step)[0] == 'vfcash' and explode(" ",$step)[1]){
$cb = str_replace("index.php","payment.php","https://" . $_SERVER['SERVER_NAME'] . "" . $_SERVER['SCRIPT_NAME']);
$r = $cash->checkPayment(explode(" ",$step)[1],$text,$cb,$chat_id);
if(isset($r["status"])){
    bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"
    ☑️ - ".$r["message"],
    'parse_mode'=>"MarkDown",
    'reply_to_message_id'=>$message_id
    ]);
}
unlink("data/id/$id/step.txt");
}
#=========={English}==========#
if($data == "eng"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*Main List 🏡 
    💙  $first 💙

 🆔️ :  `$chat_id` •
*💲 : $Balance ₽

➖ 💙[  Bot Channel  ](http://t.me/$usrch1/1850)💙
➖ 💜[  Activation channel  ](http://t.me/$usrch2/850)💜
➖ 📞[  Currently available countries 🇾🇪🇻🇳🇿🇦🇸🇦  ](http://t.me/$usrch3/850)🇸🇦🇻🇳🇪🇬


*                       𓆩•|ــــــــــ($namebot)ـــــــــ|•𓆪⇣️
➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ Buy number 💎٭','callback_data'=>'Buynum']],
[['text'=>'✥ Telegram offers 🎯٭','callback_data'=>"Cw-36"],['text'=>'✥ WhatsApp offers 🎯٭','callback_data'=>"Wo"]],[['text'=>'✥ Balance Transfer ↻٭','callback_data'=>"YESSend"],['text'=>'✥ Recharge your balance 🎳٭','callback_data'=>'Payment']],
[['text'=>'✥ Earn rubles for free 💎٭','callback_data'=>"assignment"]],
[['text'=>'✥ Quick Search 🧩٭','callback_data'=>"research-$add"]],
[['text'=>'✥ Card Shop 🪗٭','callback_data'=>"readycard-10"],['text'=>'✥Agents 🎲٭','callback_data'=>"gents"]],
[['text'=>'العربية/English','callback_data'=>"saavmotamy"]],
[['text'=>'✥ My account 👑٭','callback_data'=>"Record"]],
[['text'=>'✥ Help 💡٭','callback_data'=>"super"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
#=========={France}==========#
if($data == "franc"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*Main List 🏡 
    💙  $first 💙

 🆔️ :  `$chat_id` •
*💲 : $Balance ₽

➖ 💙[  Chaîne de robots  ](http://t.me/$usrch1/1850)💙
➖ 💜[  Canal d'activation  ](http://t.me/$usrch2/850)💜
➖ 📞[  actuellement disponibles  ](http://t.me/$usrch3/850)🇸🇦🇻🇳🇪🇬


*       𓆩•|ــــــــــ($namebot)ـــــــــ|•𓆪⇣️
➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ Acheter le numéro 💎٭‌‌','callback_data'=>'Buynum']],
[['text'=>'✥ Offres Telegram 🎯٭‌‌','callback_data'=>"Cw-36"],['text'=>'✥ Offres WhatsApp 🎯٭‌‌','callback_data'=>"Wo"]],[['text'=>'✥ Transfert de solde ↻٭‌‌','callback_data'=>"YESSend"],['text'=>'✥ Rechargez votre solde 🎳٭‌‌','callback_data'=>'Payment']],
[['text'=>'✥ Gagnez des roubles gratuitement 💎٭‌‌','callback_data'=>"assignment"]],
[['text'=>'✥ Recherche rapide 🧩٭‌‌','callback_data'=>"research-$add"]],
[['text'=>'✥ Boutique de cartes 🪗٭‌‌','callback_data'=>"readycard-10"],['text'=>'✥Agents 🎲٭','callback_data'=>"gents"]],
[['text'=>'العربية/English','callback_data'=>"saavmotamy"]],
[['text'=>'✥ Mon compte 👑٭‌‌','callback_data'=>"Record"]],
[['text'=>'✥ Aide 💡٭‌‌','callback_data'=>"super"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
#============{شحن كرت}=========#
#=========={jimirup}==========#
if($data == "f"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*Main List 🏡 
    💙  $first 💙

 🆔️ :  `$chat_id` •
*💲 : $Balance ₽

➖ 💙[  Chaîne de robots  ](http://t.me/$usrch1/1850)💙
➖ 💜[  Canal d'activation  ](http://t.me/$usrch2/850)💜
➖ 📞[  actuellement disponibles  ](http://t.me/$usrch3/850)🇸🇦🇻🇳🇪🇬


*         𓆩•|ــــــــــ($namebot)ـــــــــ|•𓆪⇣️
➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ Acheter le numéro 💎٭‌‌','callback_data'=>'Buynum']],
[['text'=>'✥ Offres Telegram 🎯٭‌‌','callback_data'=>"Cw-36"],['text'=>'✥ Offres WhatsApp 🎯٭‌‌','callback_data'=>"Wo"]],[['text'=>'✥ Transfert de solde ↻٭‌‌','callback_data'=>"YESSend"],['text'=>'✥ Rechargez votre solde 🎳٭‌‌','callback_data'=>'Payment']],
[['text'=>'✥ Gagnez des roubles gratuitement 💎٭‌‌','callback_data'=>"assignment"]],
[['text'=>'✥ Recherche rapide 🧩٭‌‌','callback_data'=>"research-$add"]],
[['text'=>'✥ Boutique de cartes 🪗٭‌‌','callback_data'=>"readycard-10"],['text'=>'✥Agents 🎲٭','callback_data'=>"gents"]],
[['text'=>'العربية/English','callback_data'=>"saavmotamy"]],
[['text'=>'✥ Mon compte 👑٭‌‌','callback_data'=>"Record"]],
[['text'=>'✥ Aide 💡٭‌‌','callback_data'=>"super"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
#============{شحن كرت}=========#
if($data == "Card"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🙋🏻 - اهلا بك عزيزي المستخدم في قسم تعبئة كروت الشحن.
📤  - قم بإرسال كرت الشحن اللذي قمت بشرائه سابقاً.

◽️في حال كنت تريد شراء كروت قم بطلبها عبر احد الوكلاء .
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'🔄 - رجوع للخلف - 🔄','callback_data'=>'Payment']]
]
])
]);
file_put_contents("data/id/$id/step.txt","po");
}
if($text && $text !== "/start" and $step == 'po'){
if($sool['card'][$text] !== null){
$amount = $sool['card'][$text]['amount'];
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ - انت على وشك شحن كرت روبل في حسابك

💸 - قيمة الكرت: *₽ $amount*
🚀 - الكرت: *$text*
🎛 - هل تريد تحصيل الكرت في حسابك؟!
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'نعم - ☑️','callback_data'=>"YECard-$text"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'Payment']]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
🧩 - عذراً عزيزي, *الكرت خاطئ,* أو *سبقك أحدٌ في تحصيل قيمته!* حاول مجدداً بعد شراء كرت آخر ✅
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'Payment']]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
if($exdata[0] == "YECard"){
$carts=$exdata[1];
if($sool['card'][$carts] !== null and $sool['card'][$carts][amount] !== null){
$amount = $sool['card'][$carts]['amount'];
$emil = $sool['card'][$carts]['idcar'];
$name = $sool['card'][$carts]['name'];
$idcar = $EMILS['emils'][$emil]['id'];
$order = $sool['card'][$carts]['order'];
$BUYSCARD = json_decode(file_get_contents("EMILS/$emil/card.json"),true);
$idcard = $BUYSCARD[$order][idcard];
$points = $Balance + $amount;
$idSend = $BUYSCARD[$order][id];
$idd = $BUYSCARD[$order]["chat-id"];
$emile = $BUYSCARD[$order][emil];
$price = $BUYSCARD[$order][price];
$idcard = $BUYSCARD[$order][idcard];
$DAYS = $BUYSCARD[$order][DAY];
if($emil == "ok"){
$idSend = rand(1234567,9999999);
$idd = $sool['card'][$carts]['id'];
$emile = $EMIL[$idd]['emil'];
$price = $amount;
$idcard = $sool['card'][$carts]['idcard'];
$tims = $sool['card'][$carts]['time'];
$days = $sool['card'][$carts]['day'];
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- تم تحصيل قيمة كرت الشحن وإضافة $amount روبل إلى حسابك بنجاح* ↪️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
bot('sendMessage',[
'chat_id'=>$idcar,
'text'=>"
☑️ *- مرحبا بك عزيزي ؛
🏋‍♂ - لقد قام :* [$first](tg://user?id=$chat_id)

🧩 *- بـ شحن كرت قمت أنت بإنتاجها ✅
🚀 - الكرت :* `$carts`
🅿️ *- قيمة الكرت : ₽ $amount*
",
'parse_mode'=>"MarkDown",
]);
bot('sendMessage',[
'chat_id'=>$eer,
'text'=>"
🎟 - تم شحن كرت في البوت ⚜
🎫 - لصالح: *$emil* 🌐

🅿️ - القيمة: *₽ $amount*
💰 - رصيده: *₽ $points*
🛅 - الكرت: *$carts*
🎗 - الشاحن: *$EM* 🌐
🔎 - رقم العمل: *$idcard*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
$points = file_get_contents("EMILS/$EM/points.txt");
$as = $points + $amount;
file_put_contents("EMILS/$EM/points.txt",$as);
if($emil == "ok"){
$pointall = file_get_contents("data/txt/pointall.txt");
$alls = $pointall + $amount;
file_put_contents("data/txt/pointall.txt",$alls);
$rubleall = file_get_contents("data/txt/rubleall.txt");
$dlls = $rubleall + $amount;
file_put_contents("data/txt/rubleall.txt",$dlls);
}
if($sool['card'][$carts][order] !== null){
$BUYSCARD[$order][status] = 1;
$BUYSCARD[$order]["user_chat-id"] = $id;
$BUYSCARD[$order][user_emil] = $EM;
$BUYSCARD[$order][DAY_shipping] = $DAY;
CardBuys($BUYSCARD,$emil);
}
$BUYSPRIC[$pricmy][id] = $idSend;
$BUYSPRIC[$pricmy][card] = $carts;
$BUYSPRIC[$pricmy][price] = $price;
$BUYSPRIC[$pricmy][status] = 2;
$BUYSPRIC[$pricmy][idcard] = $idcard;
$BUYSPRIC[$pricmy][amount] = $amount;
$BUYSPRIC[$pricmy][via] = 4;
$BUYSPRIC[$pricmy]["chat-id"] = $id;
$BUYSPRIC[$pricmy]["user_chat-id"] = $idd;
$BUYSPRIC[$pricmy][emil] = $EM;
$BUYSPRIC[$pricmy][user_emil] = $emile;
$BUYSPRIC[$pricmy][user_name] = $name;
$BUYSPRIC[$pricmy][DAY] = $DAYS;
$BUYSPRIC[$pricmy][DAY_shipping] = $DAY;
PricBuys($BUYSPRIC,$EM);
if($emil != $EM){
$ORDERALL[add] +=1;
OrdAll($ORDERALL);
$rubles = file_get_contents("EMILS/$EM/rubles.txt");
$ds = $rubles - $amount;
file_put_contents("EMILS/$EM/rubles.txt",$ds);
}
unset($sool['card'][$carts]);
Sool($sool);
unlink("data/id/$id/step.txt");
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🔰 *- عذراً، قد سبقك أحدٌ في تحصيل قيمته!* ✅
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'allcart']]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={القائمه الرئيسية عبر الامر . قم بكتابه الامر هنا بدلا من /jimi}==========#
if($text == $zozamr){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*القائمة الرئيسية 🏡
      💙 $first 💙*
      
🆔️ : `$chat_id` •
💷 : $Balance ₽•
💵 : $dollar $ •

💙 [ قـــنـــاة البـوت ](http://t.me/$usrch1) 💙
🩷 [ قـنــاة التفعـــيـلات ](http://t.me/$usrch2) 💜
➖ [ من الدول المتوفرة حالياً  ](https://t.me/$usrch3/99) 🇾🇪🇻🇳🇮🇩🇸🇦
➖ [ شرح استخدام البوت  ](http://t.me/$usrch4/11) 💡

           *𓆩•|ــــــــــ( قحطان)ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• شـراء رقم افتـراضي💎 •','callback_data'=>'Buynum']],
[['text'=>'  ✥ عـروTelegramـض 🎯٭','callback_data'=>"Cwt-36"],['text'=>' •  ✥ عـروWhatsAppـض 🎯٭','callback_data'=>"Wo"]],
[['text'=>'• الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>'u0u']],
[['text'=>'• الأكثر توفرآ  🎲 •','callback_data'=>"Available"],['text'=>' • أشحن رصيدك  🎳 •','callback_data'=>'Payment']], 
[['text'=>'• الرشـ%ـق وشحن الألعاب والبرامج 🔭 •','callback_data'=>"Throwing_games"]],
[['text'=>'• ربح الروبل مجانآ 💎 •','callback_data'=>"assignment"]],
[['text'=>' • تحويل الرصيد ↻ •','callback_data'=>"SendCoin"],['text'=>'• الوكلاء  🎲 •','callback_data'=>"gents"]],
[['text'=>'• العـربية / English / فارسى •','callback_data'=>"saavmotamy"]],
[['text'=>'• معلومات حسابك ⚙ •','callback_data'=>"Record"]],
]
])
]);
unlink("data/id/$id/step.txt");
}
}
include("tg-numbers.php");
#========={امر ستارت}=========#
if($text == '/start'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*القائمة الرئيسية 🏡
      💙 $first 💙*
      
🆔️ : `$chat_id` •
💷 : $Balance ₽•
💵 : $dollar $ •

💙 [ قـــنـــاة البـوت ](http://t.me/$usrch1) 💙
🩷 [ قـنــاة التفعـــيـلات ](http://t.me/$usrch2) 💜
➖ [ من الدول المتوفرة حالياً  ](https://t.me/$usrch3/99) 🇾🇪🇻🇳🇮🇩🇸🇦
➖ [ شرح استخدام البوت  ](http://t.me/$usrch4/11) 💡

           *𓆩•|ــــــــــ( قحطان)ـــــــــ|•𓆪⬇️
➖➖➖➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• شـراء رقم افتـراضي💎 •','callback_data'=>'Buynum']],
[['text'=>'  ✥ عـروTelegramـض 🎯٭','callback_data'=>"Cwt-36"],['text'=>' •  ✥ عـروWhatsAppـض 🎯٭','callback_data'=>"Wo"]],
[['text'=>'• الشـboyـراء التلـقـ🛰ـائي الذكي •','callback_data'=>'u0u']],
[['text'=>'• الأكثر توفرآ  🎲 •','callback_data'=>"Available"],['text'=>' • أشحن رصيدك  🎳 •','callback_data'=>'Payment']], 
[['text'=>'• الرشـ%ـق وشحن الألعاب والبرامج 🔭 •','callback_data'=>"Throwing_games"]],
[['text'=>'• ربح الروبل مجانآ 💎 •','callback_data'=>"assignment"]],
[['text'=>' • تحويل الرصيد ↻ •','callback_data'=>"SendCoin"],['text'=>'• الوكلاء  🎲 •','callback_data'=>"gents"]],
[['text'=>'• العـربية / English / فارسى •','callback_data'=>"saavmotamy"]],
[['text'=>'• معلومات حسابك ⚙ •','callback_data'=>"Record"]],
]
])
]);
unlink("data/id/$id/step.txt");
}
}

#=========={العروض}==========#
if($text == '/offers'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*➖ مرحبا 💙
➖ قم بأختيار نوع العروض التي تريد الشراء منها  ٭ ( WhatsApp  ~ Telegram  )  ✥*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عروض WhatsApp 💙 ٭','callback_data'=>"Wo"]],
[['text'=>'✥ عروض Telegram 💚 ٭','callback_data'=>"Cw-36"]], 
[['text'=>'- رجوع.','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={امر واتساب}==========#
if($text == '/whatsapp'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق :  ✥ واتس اب -𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣🛍 ٭ .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
*➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-2-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-2-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-2-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-2-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-2-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-2-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-2-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={امر تليجرام}==========#
if($text == '/telegram'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق :  ✥ تليجرام -𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠 🎲 .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-3-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-3-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-3-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-3-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-3-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-3-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-3-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={امر الانستا}==========#
if($text == '/insta'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق :  ✥ انستغرام -𝗟𝗡𝗦𝗧𝗔𝗚𝗥𝗔𝗠🎳 ٭ .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
*➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-5-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-5-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-5-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-5-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-5-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-5-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-5-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={امر فيسبوك}==========#
if($text == '/facebook'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق :  ✥ فيسبوك -𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞🎯 .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
*➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-4-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-4-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-4-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-4-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-4-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-4-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-4-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={امر تويتر}==========#
if($text == '/twi'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق :  ✥ تويتر -𝗧𝗪𝗜𝗧𝗧𝗘𝗥 🐤 .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
*➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-6-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-6-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-6-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-6-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-6-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-6-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-6-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={امر.تيك توك}==========#
if($text == '/tik'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق :  ✥ تيك توك -𝗧𝗜𝗞𝗧𝗢𝗞 🎥 .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
*➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-7-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-7-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-7-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-7-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-7-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-7-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-7-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={امر قوقل}==========#
if($text == '/google'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق :  ✥ جوجل-G𝗼𝗼𝗴𝗹𝗲⛱ .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
*➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-8-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-8-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-8-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-8-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-8-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-8-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-8-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={امر سناب}==========#
if($text == '/snap'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق :  ✥ سناب شات -𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧♣️ ٭ .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
*➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-11-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-11-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-11-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-11-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-11-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-11-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-11-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={امر ايمو}==========#
if($text == '/imo'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق :  ✥ ايمو - 𝗜𝗠𝗢 💎 ٭ .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
*➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-9-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-9-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-9-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-9-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-9-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-9-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-9-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={امر السيرفر العام؟}==========#
if($text == '/other'){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]],
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق :  ✥🧿 السيرفر العام 🤖 .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
*➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-14-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-14-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-14-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-14-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-14-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-14-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-14-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={تحويل روبل}=========#
if($data == "SendCoin"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>مرحبا بك عزيزي : 💙 $first 💙</b>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>

<blockquote>📮 - يمكنك الآن تحويل رصيد من حسابك الى حساب شخص آخر بشكل مباشر وفي مدة لاتتعدى الـ 10 ثواني ✅</blockquote>
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱☠</b>

<b>عمولة التحويل : 5/100</b>
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱☠</b>

<blockquote>⚠️ - أقل مبلغ للتحويل: ₽ 5.00 💸</blockquote>
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱☠</b>

<b>✅ - هل تريد تحويل رصيد الى حساب آخر - ⇣️⇣️⇣️</b>
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱☠</b>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'نعم ☑️','callback_data'=>'YESSend']],
[['text'=>'🔄 - رجوع للخلف - 🔄','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}
if($data == "YESSend"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*💙 - أرسل ايدي الشخص الذي تريد التحويل إليه* ♻️
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- إلغاء العملية ❎','callback_data'=>'SendCoin']]
]
])
]);
file_put_contents("data/id/$id/step.txt","se");
}
if($text !== '/start' and $text !== null and $step == 'se'){
if($EMILS['emils'][$text]['emil'] == null){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
🚫 لايمكن تحويل الرصيد لعضو غير متواجد في البوت قم بدعوته اولا.
➖ الرجاء التأكد من الحساب والمحاولة مجددا 💙
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
exit;
}elseif($EM == $text){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
⚠️ - لايمكن التحويل لنفس الحساب عزيزي العميل 😐
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
}else{
$pri=1;
$price=0/100*$pri+$pri;
$t=$price+1;
$i=$price-1;
$v=$price+10;
$k=$price-10;
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ *- الحساب : [$text]
🔄 - قم ب إنشاء القيمة الذي تريد تحوليها إلى صديقك*
✅ - يتم تحديث *السعر تلقائياً عند الضغط على (➕ أو ➖ )* ⇣️

💸 - رصيدك الموجود في حسابك هو: *₽ $Balance* ✅
🅿️ - القيمة المختارة: *₽ $pri*
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - تأكد تحويل رصيد بقيمة ( ₽ $pri )",'callback_data'=>"YSg-$pri-$price-$text"]],
[['text'=>"➖ 1 ₽",'callback_data'=>"increase-$i-$text"],['text'=>"➕ 1 ₽",'callback_data'=>"increase-$t-$text"]],
[['text'=>"➖ 10 ₽",'callback_data'=>"increase-$k-$text"],['text'=>"➕ 10 ₽",'callback_data'=>"increase-$v-$text"]],
[['text'=>'- إلغاء العملية ❎','callback_data'=>'SendCoin']]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
if($exdata[0] == "increase"){
$pri = $exdata[1];
$emils = $exdata[2];
$pri="$pri.00";
if($pri < 0 or $pri > 10000){
unlink("data/id/$id/step.txt");
}else{
$price=0/100*$pri+$pri;
$t=$price+1;
$i=$price-1;
$v=$price+10;
$k=$price-10;
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- الحساب : [$emils]
🔄 - قم ب إنشاء القيمة الذي تريد تحوليها إلى صديقك*
✅ - يتم تحديث *السعر تلقائياً عند الضغط على (➕ أو ➖ )* ⇣️

💸 - رصيدك الموجود في حسابك هو: *₽ $Balance* ✅
🅿️ - القيمة المختارة: *₽ $pri*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - تأكد تحويل رصيد بقيمة ( ₽ $pri )",'callback_data'=>"YSg-$pri-$price-$emils"]],
[['text'=>"➖ 1 ₽",'callback_data'=>"increase-$i-$emils"],['text'=>"➕ 1 ₽",'callback_data'=>"increase-$t-$emils"]],
[['text'=>"➖ 10 ₽",'callback_data'=>"increase-$k-$emils"],['text'=>"➕ 10 ₽",'callback_data'=>"increase-$v-$emils"]],
[['text'=>'- إلغاء العملية ❎','callback_data'=>'SendCoin']]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
if($exdata[0] == "YSg"){
$price = $exdata[1];
$coun = $exdata[2];
$emils = $exdata[3];
$iddd = $EMILS['emils'][$emils]['id'];
$rubel = $coun - $price;
$points = $Balance - $coun;
    
if ($emils == null) {
bot('EditMessageText', [
'chat_id' => $chat_id,
'message_id' => $message_id,
'text' => "
*- حدث خطأ ما لديك أعد المحاولة مرة أخرى* ♻️
",
'parse_mode' => "MarkDown",
'reply_markup' => json_encode([
'inline_keyboard' => [
[['text' => '✥ عودة ↩ ٭..', 'callback_data' => 'SendCoin']]
                ]
            ])
        ]);
    } elseif ($coun <= $Balance) {
        // تحويل مباشر
bot('EditMessageText', [
'chat_id' => $chat_id,
'message_id' => $message_id,
'text' => "
<blockquote>تم خصم $price ₽ من رصيدك </blockquote>
<blockquote>تم تحويل الروبل الى [$emils] بنجاح ✅</blockquote>
<blockquote>🔄︙رصيدك الحالي : ₽ $points</blockquote>
",
'parse_mode' => "html",
'reply_markup' => json_encode([
'inline_keyboard' => [
[['text' => '✥ عودة ↩ ٭..', 'callback_data' => 'back']]
                ]
            ])
        ]);

        // إشعار المستلم
bot('sendMessage', [
'chat_id' => $iddd,
'text' => "
<blockquote>💵 تم شحن حسابك بـ مبلغ ₽ $price</blockquote>
<blockquote>👤 تم شحن من قبل : $first ✅</blockquote>
<blockquote>👤 يوزر الشخص : @$user ✅</blockquote>
",
'parse_mode' => "html",
        ]);
bot('sendmessage',[
'chat_id'=>$eer,
'text'=>"
✅ - *قام عضو بتحويل روبل في البوت*

💸 - المبلغ المحول : *₽ $price* 💰
🗒 - العمولة : *₽ $rubel* ♻️
♻️ - رصيده المتبقي : *₽ $points* 💸
🗞️ - حساب المرسل : *[$EM]* 🌐
💵 - حساب المستلم : *[$emils]* 🌐
✳️ - سيكون رصيد المستلم : *₽ $to* 💸
☑️ - رقم عملية إستلام الروبل : *$code*
🔎 - رقم العمل : *$sendbot2*
🅿️ - رقم المرجع : *$idSend*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط المرسل ↖️",'url'=>"tg://openmessage?user_id=$id"]],
[['text'=>"☑️ - رابط المستلم ↖️",'url'=>"tg://openmessage?user_id=$emils"]]
]
])
]);
        // تحديث الأرصدة
        file_put_contents("EMILS/$EM/points.txt", $points);
        $receiverBalance = file_get_contents("EMILS/$emils/points.txt");
        $newReceiverBalance = $receiverBalance + $price;
        file_put_contents("EMILS/$emils/points.txt", $newReceiverBalance);
    } else {
        bot('EditMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => "
            ⚠️ *- رصيدك غير كافي لإتمام عملية التحويل*
            ☑️ - تم إلغاء العملية
            ",
            'parse_mode' => "MarkDown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => '✥ عودة ↩ ٭..', 'callback_data' => 'SendCoin']]
                ]
            ])
        ]);
    }
}
if($exdata[0] == "YSb"){
$price = $exdata[1];
$coun = $exdata[2];
$emils = $exdata[3];
$sendbot2=$sendbot+1;
$cel = file_get_contents("EMILS/$emils/points.txt");
$to = $cel + $price;
$rubel = $coun-$price;
$geet = $Balance - $coun;
$idEM = $EMILS['emils'][$emils]['id'];
$idSend = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 8-16);
$code = rand(12345,99999);
$code = "$sendbot$code";
if($coun > $Balance){
exit;
}elseif($coun == null or $price == null or $emils == null or $idEM == null){
unlink("data/id/$id/step.txt");
exit;
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
✅︙تم تحويل ₽ $price الى *[$emils] بنجاح!*
⛔️ *⁞ تم خصم ₽ $coun من رصيدك* ✅
☑️ *⁞ رقم عملية لإستلام الروبل:* `$code`

🅿️︙رقم العملية: *$idSend* 🆔
🔄︙رصيدك الأن: *₽ $geet* 💰
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
bot('sendmessage',[
'chat_id'=>$idEM,
'text'=>"
💙مرحبا 💙
💰 *⁞ إليك تحويل بقيمة : ₽ $price
✅︙من : *[$first](tg://user?id=$chat_id)

☑️ *⁞ لإستلام هذه التحويل ارسل $zozamr ثم انقر زر شحن رصيدك ومن ثم قم بنقر زر إستلام الروبل وأرسل رقم العملية الذي ستستلمها من المحول*💙
",
'parse_mode'=>"MarkDown",
]);
bot('sendmessage',[
'chat_id'=>$eer,
'text'=>"
✅ - *قام عضو بتحويل روبل في البوت*

💸 - المبلغ المحول : *₽ $price* 💰
🗒 - العمولة : *₽ $rubel* ♻️
♻️ - رصيده المتبقي : *₽ $geet* 💸
🗞️ - حساب المرسل : *[$EM]* 🌐
💵 - حساب المستلم : *[$emils]* 🌐
✳️ - سيكون رصيد المستلم : *₽ $to* 💸
☑️ - رقم عملية إستلام الروبل : *$code*
🔎 - رقم العمل : *$sendbot2*
🅿️ - رقم المرجع : *$idSend*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط المرسل ↖️",'url'=>"tg://openmessage?user_id=$id"]],
[['text'=>"☑️ - رابط المستلم ↖️",'url'=>"tg://openmessage?user_id=$idEM"]]
]
])
]);
$points = file_get_contents("EMILS/$EM/points.txt");
$as = $points - $coun;
file_put_contents("EMILS/$EM/points.txt",$as);
$BUYSPRIC = json_decode(file_get_contents("EMILS/$emils/price.json"),true);
$idd = count($BUYSPRIC);
$BUYSSEND[$sendmy][idSend] = $sendbot+1;
$BUYSSEND[$sendmy][id] = $idSend;
$BUYSSEND[$sendmy][price] = $coun;
$BUYSSEND[$sendmy][amount] = $price;
$BUYSSEND[$sendmy][code] = $code;
$BUYSSEND[$sendmy][status] = 1;
$BUYSSEND[$sendmy][idpric] = $idd;
$BUYSSEND[$sendmy]["chat-id"] = $id;
$BUYSSEND[$sendmy]["user_chat-id"] = null;
$BUYSSEND[$sendmy][user_emil] = $emils;
$BUYSSEND[$sendmy][DAY] = $DAY;
SendBuys($BUYSSEND,$EM);
$BUYSPRIC[$idd][id] = $idSend;
$BUYSPRIC[$idd][price] = $price;
$BUYSPRIC[$idd][status] = 1;
$BUYSPRIC[$idd][code] = $code;
$BUYSPRIC[$idd][idsend] = $sendbot+1;
$BUYSPRIC[$idd][via] = 3;
$BUYSPRIC[$idd]["chat-id"] = null;
$BUYSPRIC[$idd]["user_chat-id"] = $id;
$BUYSPRIC[$idd][emil] = $emils;
$BUYSPRIC[$idd][user_emil] = $EM;
$BUYSPRIC[$idd][user_name] = $first;
$BUYSPRIC[$idd][DAY] = $DAY;
PricBuys($BUYSPRIC,$emils);
$ORDERALL[send] +=1;
OrdAll($ORDERALL);
$sool[send][$code][idsen] = $EM;
$sool[send][$code][order] = $sendmy;
Sool($sool);
unlink("data/id/$id/step.txt");
}
}
#========={إستلام الروبل}==========#
if($data == "receiptpri"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- أرسل رقم الحماية الآن*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
file_put_contents("data/id/$id/step.txt","receiptpri");
}
if($text && $text != null && $text != '/start' && $step == "receiptpri"){
$code=$text;
$order = $sool[send][$code][order];
$emil = $sool[send][$code][idsen];
$BUYSSEND = json_decode(file_get_contents("EMILS/$emil/send.json"),true);
$idd=$BUYSSEND[$order]["chat-id"];
$price=$BUYSSEND[$order][amount];
$idSend=$BUYSSEND[$order][id];
if($BUYSSEND[$order][status] == 3){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
♻️ *- رقم العملية هذه مقيده من قبل المشرف، حاول مره أخرى بعد قليل*
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
]);
unlink("data/id/$id/step.txt");
}elseif($sool[send][$code] == null or $BUYSSEND[$order][code] != $text or $BUYSSEND[$order][user_emil] != $EM or $BUYSSEND[$order][status] != 1){
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
❌ *- رقم العملية غير صحيح*
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
]);
}else{
bot('sendmessage',[
'chat_id'=>$idd,
'text'=>"
☑️ *- تم إستلام الحوالة الذي قمت بإرسالها*
🅿️ - رقم العملية : *$idSend*
",
'parse_mode'=>"MarkDown",
]);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ *- تم إستلام حوالتك بقيمة ₽ $price روبل*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=>$eer,
'text'=>"
☑️ *- تم إستلام حوالة روبل على رقم $code*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
$points = file_get_contents("EMILS/$EM/points.txt");
$as = $points + $price;
file_put_contents("EMILS/$EM/points.txt",$as);
$rubles = file_get_contents("EMILS/$EM/rubles.txt");
$ds = $rubles + $price;
file_put_contents("EMILS/$EM/rubles.txt",$ds);
$idd = $BUYSSEND[$order][idpric];
$BUYSSEND[$order][status] = 2;
$BUYSSEND[$order]["user_chat-id"] = $id;
$BUYSSEND[$order][DAY_shipping] = $DAY;
SendBuys($BUYSSEND,$emil);
$BUYSPRIC[$idd][status] = 2;
$BUYSPRIC[$idd]["chat-id"] = $id;
$BUYSPRIC[$idd][DAY_shipping] = $DAY;
PricBuys($BUYSPRIC,$EM);
unset($sool['send'][$BUYSSEND[$order][code]]);
Sool($sool);
unlink("data/id/$id/step.txt");
}
}
#========={إلغاء التحويل}==========#
if($exdata[0] == "CancelTransfer"){
$order=$exdata[1];
$status_send = $exdata[2];
$idSend = $BUYSSEND[$order][id];
$status = $BUYSSEND[$order][status];
if($status != 1 or $status_send != $idSend){
if($sendmy == null){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك  🙋🏻.
➖ هذا الأرشيف الخاص بحسابك 📂.
➖ الإسم |  $first ✏️. 
➖ المُعرِف |  $user 🔖.
 ➖ ID | $chat_id 🆔.

➖ الرصيد| $Balance ₽ 🏦.
➖ عدد عملائك |  $counmy عضواً 📈.
➖ كمية رصيدك المستخدم في البوت | $consumers  ₽ 📮. 
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'⁞ سجل البوت. ☑️','callback_data'=>'statsbot']],
[['text'=>'⁞ سجل حسابك الشخصي. 🗂','callback_data'=>'Record2']],
[['text'=>'⁞ سجل جميع الأرقام المشترى. 📞','callback_data'=>'Download1']],
[['text'=>'⁞ سجل عمليات التحويل. 🔁','callback_data'=>'Download4#1']],
[['text'=>'⁞ سجل كروت الشحن. 🎟','callback_data'=>'Download3#1']],
[['text'=>'⁞ سجل عمليات شحن حسابك. 💸','callback_data'=>'Download5#1']],
[['text'=>'⁞ رجوع.','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}else{
$key     = [];
$key['inline_keyboard'][] = [['text'=>"- رقم العملية 🅿️",'callback_data'=>"no"],['text'=>"- الحالة ☑️",'callback_data'=>"no"]];
foreach($BUYSSEND as $zero=>$num){
$oop++;
if($oop >= 8){
break;
}
if($BUYSSEND[8] != null){
$to="التالي. ⬅️";
}
$statuse = $num[status];
$status=str_replace(["-1","1","2","3"],["🚫","⏰","☑️","⏰"],$statuse);
$idSend = $num[id];
$key['inline_keyboard'][] = [['text'=>"$idSend",'callback_data'=>"sSendBuy#$zero#$idSend#1"],['text'=>"$status",'callback_data'=>"file"]];
}
$key['inline_keyboard'][] = [['text'=>"$to",'callback_data'=>"Download4#2"]];
$key['inline_keyboard'][] = [['text'=>'✥ عودة ↩ ٭..','callback_data'=>'Record']];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - هذه قائمة *جميع عمليات التحويلات للروبل في البوت.*
⚜ - هذه العلامة *< ☑️ > تعني أن العملية تمت بنجاح* ، وهذه العلامة *< 🚫 > تعني أن العملية قد تم إلغائها* ، وهذه العلامة *< ⏰ > تعني أن العملية قيد الإنتظار.*
♻️ - إضغط على *رقم العلامة لإظهار باقي معلومات عن عملية التحويل الذي قمت بتحويلة.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- تم إرسال طلب عملية إلغاء لهذا التحويل برقم $idSend*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"sSendBuy#$order#$status_send#1"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$sudo,
'text'=>"
☑️ *- إشعار عملية إلغاء برقم $idSend*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'☑️ - موافقة الطلب','callback_data'=>"yesTransfer-$order-$EM"]],
[['text'=>'🚫 - رفض الطلب','callback_data'=>"noTransfer-$order-$EM"]]
]
])
]);
$idd = $BUYSSEND[$order][idpric];
$idEM = $BUYSSEND[$order][user_emil];
$BUYSSEND[$order][status] = 3;
SendBuys($BUYSSEND,$EM);
$BUYSPRIC = json_decode(file_get_contents("EMILS/$idEM/price.json"),true);
$BUYSPRIC[$idd][status] = 3;
PricBuys($BUYSPRIC,$idEM);
unlink("data/id/$id/step.txt");
}
}
#========={موافقة الطلب}==========#
if($exdata[0] == "yesTransfer"){
$order=$exdata[1];
$emil=$exdata[2];
$BUYSSEND = json_decode(file_get_contents("EMILS/$emil/send.json"),true);
$status = $BUYSSEND[$order][status];
$idSend = $BUYSSEND[$order][id];
$coun = $BUYSSEND[$order][price];
$idds = $BUYSSEND[$order]["chat-id"];
if($status != 3){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- رقم العملية قد تم تغييرها $idSend*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- تم إلغاء العملية رقم $idSend بنجاح*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"c"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$idds,
'text'=>"
☑️ *- تم الموافقة على طلب إلغاء تحويل روبل برقم $idSend*
💸 - تم إرجاع الرصيد إلى حسابك: *₽ $coun*
",
'parse_mode'=>"MarkDown",
]);
$idd = $BUYSSEND[$order][idpric];
$idEM = $BUYSSEND[$order][user_emil];
$idch = $EMILS['emils'][$idEM]['id'];
$BUYSSEND[$order][status] = -1;
$BUYSSEND[$order]["user_chat-id"] = $idch;
$BUYSSEND[$order][DAY_shipping] = $DAY;
SendBuys($BUYSSEND,$emil);
$BUYSPRIC = json_decode(file_get_contents("EMILS/$idEM/price.json"),true);
$BUYSPRIC[$idd][status] = -1;
$BUYSPRIC[$idd]["chat-id"] = $idch;
$BUYSPRIC[$idd][DAY_shipping] = $DAY;
PricBuys($BUYSPRIC,$idEM);
$points = file_get_contents("EMILS/$emil/points.txt");
$as = $points + $coun;
file_put_contents("EMILS/$emil/points.txt",$as);
unset($sool['send'][$BUYSSEND[$order][code]]);
Sool($sool);
unlink("data/id/$id/step.txt");
}
}
#========={رفض الطلب}==========#
if($exdata[0] == "noTransfer"){
$order=$exdata[1];
$emil=$exdata[2];
$BUYSSEND = json_decode(file_get_contents("EMILS/$emil/send.json"),true);
$status = $BUYSSEND[$order][status];
$idSend = $BUYSSEND[$order][id];
$idds = $BUYSSEND[$order]["chat-id"];
if($status != 3){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- رقم العملية قد تم تغييرها $idSend*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"c"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- تم رفض طلب إلغاء العملية رقم $idSend*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"c"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$idds,
'text'=>"
🚫 *- تم رفض طلب إلغاء تحويل روبل برقم $idSend*
",
'parse_mode'=>"MarkDown",
]);
$idd = $BUYSSEND[$order][idpric];
$idEM = $BUYSSEND[$order][user_emil];
$BUYSSEND[$order][status] = 1;
SendBuys($BUYSSEND,$emil);
$BUYSPRIC = json_decode(file_get_contents("EMILS/$idEM/price.json"),true);
$BUYSPRIC[$idd][status] = 1;
PricBuys($BUYSPRIC,$idEM);
unlink("data/id/$id/step.txt");
}
}
#=========={Other}==========#
if($text == 'my' and $id != $sudo){
if($EM == null or $EMILS['emils'][$EM]['emil'] == null or $passewo != $perrewo){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*👤 - عزيزي  $first  💙

*🤖 - يمكنك استخدام البوت بعد إن نتحقق من أنك شخص حقيقي 

*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*

➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ التحقق٭','callback_data'=>"sign_in"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚙ *- جميع معلومات عن حسابك الشخصي.*

📧 - الحساب: *$EM* ⏩
🔐 - كلمة المرور: *$passewo* 🔑
🆔 - أيدي الحساب: *$id* ®
💸 - رصيدك: *$Balance* 💲
📞 - عدد الأرقام المشترى: *$numbuy* 🔝
☎️ - تم تفعيل: *$buymy رقماً*
♻️ - عمليات التحويل: *$sendmy*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ 📋 - الأرقام اللتي تم شراؤها٭','callback_data'=>'Download1']],
[['text'=>'- سجل عمليات التحويل. 🔁','callback_data'=>'Download4#1']],
[['text'=>'- سجل عمليات شحن حسابك. 💸','callback_data'=>'Download5#1']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
if($data == "Record"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك  🙋🏻.
➖ هذا الأرشيف الخاص بحسابك 📂.
➖ الإسم |  $first ✏️. 
➖ المُعرِف |  $user 🔖.
 ➖ ID | $chat_id 🆔.

➖ الرصيد| $Balance ₽ 🏦.
➖ عدد عملائك |  $counmy عضواً 📈.
➖ كمية رصيدك المستخدم في البوت | $consumers  ₽ 📮. 
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ 🗃 - صندوق مشترياتي ٭','callback_data'=>'Record2']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
if($data == "Record2"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك يـ  $first 🙋🏻.
➖  هذا صندوق المشتريات الخاصه بك 📦.
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ 📋 - الأرقام اللتي تم شراؤها٭','callback_data'=>'Download1']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'Record']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
} 

#بيع حسابات تيليجرام جاهزه
include("Telegram.php");
#البحث عن الكنز
include("gifts.php");
#لا يعمل
include("doesnwork.php");
#=========={تفعيلات لرشق والالعاب}==========#
include("yasei.php");
#=========={شراء استضافات}==========#
include("o.php");
#=========={تفعيلات لتبديل العمله}==========#
include("yoyo.php");
#=========={مميزات البوت }==========#
include("yaqs.php");
#=========={اشتراك البوت}==========#
include("nae.php");
#=========={تقييم البوت}==========#
include("yas.php");
#=========={توثيق منصات}==========#
include("khdm.php");
#=========={شحن تطبيقات}==========#
include("revn.php");
#=========={شحن الالعاب}==========#
include("khdma.php");
#=========={الرشق}==========#
include("spit.php");
#=========={الدول}==========#
include("number.php");
#=========={الأرقام}==========#
if($data == "Download11"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك يـ $first  🙋🏻.
➖ هذا أرشيف الأرقام اللتي قمت بشرائها 📂.
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ ✅ - الشراء الناجح ٭','callback_data'=>'Downloads#3#1']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'Record']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={الأرقام}==========#
if($data == "Download1"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>📝 قائمة افضل 5 دولـة في إرسال الـ Code :</b> 
🗂 ———––––—————–––
<blockquote>🛰 التطبيق : 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣 •</blockquote>
<blockquote>📕 تم البدء بكتـابة الإحصائيات في يـوم أّلجـمعة •</blockquote>
<blockquote>📙 يمكنك رؤية عدد الاكواد المرسلة لكل دولة •</blockquote>
<blockquote>📘 يتم إعادة تعيين الإحصـائيات كل 7 أيـام •</blockquote>
<blockquote>📗 زيادة عدد الاكـواد يدل علا إمتياز الدولة •</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥️ نجـحت بإرسالـ✉️ •','callback_data'=>"ak"],['text'=>'✥️ ألـدولة 🗺 ٭','callback_data'=>"adf"]],
[['text'=>'(0) كود WhatsApp','callback_data'=>"af"],['text'=>'• هايتي 🇭🇹 =>','callback_data'=>"ag"]],
[['text'=>'(0) كود WhatsApp','callback_data'=>"af"],['text'=>'• مصر 🇪🇬 =>','callback_data'=>"ag"]],
[['text'=>'(0) كود WhatsApp','callback_data'=>"af"],['text'=>'• العراق 🇮🇶 =>','callback_data'=>"ag"]],
[['text'=>'(0) كود WhatsApp','callback_data'=>"af"],['text'=>'• عمان 🇴🇲 =>','callback_data'=>"ag"]],
[['text'=>'(0) كود WhatsApp','callback_data'=>"af"],['text'=>'• كينيا 🇰🇪 =>','callback_data'=>"ag"]],
[['text'=>'(70) كود WhatsApp','callback_data'=>"af"],['text'=>'• امريكا 🇺🇸 =>','callback_data'=>"ag"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'u0u']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#
if($data=="adf"){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"هذا الزر فارغ....💚",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
#
if($data=="ag"){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"✉️ الهدف هو مساعدتك في معرفه الدول المتوافرة .
بحيث يتم عرض افضل 5 أو 10 دول في وصول الكود و بيان عدد الاكواد لكل دوله و يتم حذف الاحصائيات كل 7 ايام لتتضمن الدول الجديده او تنحذف الدول المنتهيه .",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
#
if($data=="af"){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"✉️ الهدف هو مساعدتك في معرفه الدول المتوافرة .
بحيث يتم عرض افضل 5 أو 10 دول في وصول الكود و بيان عدد الاكواد لكل دوله و يتم حذف الاحصائيات كل 7 ايام لتتضمن الدول الجديده او تنحذف الدول المنتهيه .",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={الارقام الجاهزة}==========#
if($ex_data[0] == "Download2"){
$con=$ex_data[1];
$jk = $con * 8;
$oj = $con - 1;
$fj = $oj * 8;
$ij = $fj - 7;
$kb = $con + 1;
$jj = $jk - 8;
$ze='0';
if($readymy == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
📎 ⌯ لا يوجد لديك سجل في هذا الحساب!
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($ze >= $jk){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🎲 *⁞ هنا جميع سجلات حسابك وحساب المستخدمين في البوت* 🚀
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'⁞ سجل البوت. 💥','callback_data'=>'statsbot']],
[['text'=>'⁞ سجل حسابك الشخصي. 📥','callback_data'=>'Record2']],
[['text'=>'⁞ سجل جميع الأرقام المشترى. 📎','callback_data'=>'Download1']],
[['text'=>'⁞ سجل عمليات التحويل. 📎','callback_data'=>'Download4#1']],
[['text'=>'⁞ سجل كروت الشحن 📅','callback_data'=>'Download3#1']],
[['text'=>'⁞ سجل عمليات شحن حسابك 💥','callback_data'=>'Download5#1']],
[['text'=>'⁞ رجوع.','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}else{
$key     = [];
$key['inline_keyboard'][] = [['text'=>'⌯ الرقم ☎️','callback_data'=>'no'],['text'=>'⌯ الكود 💭','callback_data'=>'no'],['text'=>'⌯ السعر 💰','callback_data'=>'no']];
foreach($BUYSNUM[number] as $zero=>$num){
if($num[type] == "ready"){
$oop++;
if($oop >= $jk){
break;
}
if($oop >= $fj){
if($BUYSNUM[number][$jk] != null){
$to="التالي. ⬅️";
}
if($BUYSNUM[number][$ij] != null){
$s = "➡️ السابق.";
}
$status = $num[phone];
$number = $num[phone];
if($number != null){
$number = substr($number, 0,+8)."•••";
}
$code = $num[code];
$price = $num[price];
$idSend = $num[idSend];
$key['inline_keyboard'][] = [['text'=>"$number",'callback_data'=>"sReady#$idSend#$status#$con"],['text'=>"$code",'callback_data'=>"sReady#$idSend#$status#$con"],['text'=>"$price",'callback_data'=>"sReady#$idSend#$status#$con"]];
}
}
}
$key['inline_keyboard'][] = [['text'=>"$to",'callback_data'=>"Download2#$kb"],['text'=>"$s",'callback_data'=>"Download2#$oj"]];
$key['inline_keyboard'][] = [['text'=>'⌯ رجوع.','callback_data'=>'Record']];
$keyboad      = json_encode($key);
if($number == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
🚫 ⌯ لاتوجد صفحة أخرى حالياً.
",
'show_alert'=>true,
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
📎 ⌯ هذه قائمة *جميع الأرقام الجاهزة الذي قمت بشرائها*
📎 ⌯ إضغط على *الازرار* لإظهار باقي *معلومات عن الرقم الذي قمت ب شرائة*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
}
#=========={الكروت}==========#
if($ex_data[0] == "Download3"){
$con=$ex_data[1];
$jk = $con * 8;
$oj = $con - 1;
$fj = $oj * 8;
$ij = $fj - 7;
$kb = $con + 1;
$jj = $jk - 8;
$ze='0';
if($cardmy == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
❌ - لا يوجد لديك سجل في هذا الحساب!
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($ze >= $jk){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك  🙋🏻.
➖ هذا الأرشيف الخاص بحسابك 📂.
➖ الإسم |  $first ✏️. 
➖ المُعرِف |  $user 🔖.
 ➖ ID | $chat_id 🆔.

➖ الرصيد| $Balance ₽ 🏦.
➖ عدد عملائك |  $counmy عضواً 📈.
➖ كمية رصيدك المستخدم في البوت | $consumers  ₽ 📮. 
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ 🗃 - صندوق مشترياتي ٭','callback_data'=>'Record2']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}else{
$key     = [];
$key['inline_keyboard'][] = [['text'=>"- رقم الكرت 🅿️",'callback_data'=>"no"],['text'=>"- الحالة ☑️",'callback_data'=>"no"]];
foreach($BUYSCARD as $zero=>$num){
$oop++;
if($oop >= $jk){
break;
}
if($oop >= $fj){
if($BUYSCARD[$jk] != null){
$to="التالي. ⬅️";
}
if($BUYSCARD[$ij] != null){
$s = "➡️ السابق.";
}
$status = $num[status];
$status=str_replace(["-1","1"],["🎫","🎟"],$status);
$card = $num[card];
$idCard = $num[idCard];
$key['inline_keyboard'][] = [['text'=>"$card",'callback_data'=>"sCardBuy#$zero#$card#$con"],['text'=>"$status",'callback_data'=>"file"]];
}
}
$key['inline_keyboard'][] = [['text'=>"$to",'callback_data'=>"Download3#$kb"],['text'=>"$s",'callback_data'=>"Download3#$oj"]];
$key['inline_keyboard'][] = [['text'=>'✥ عودة ↩ ٭..','callback_data'=>'Record']];
$keyboad      = json_encode($key);
if($card == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
⚠️ - لاتوجد صفحة أخرى حالياً.
",
'show_alert'=>true,
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - هذه قائمة *جميع الكروت الذي قمت بشرائها.*
⚜ - هذه العلامة *< 🎫 > تعني أن الكرت لم يتم إستخدامة بعد* ، وهذه العلامة *< 🎟 > تعني أن الكرت قد تم إستعمالة.*
♻️ - إضغط على *رقم العملية* لإظهار باقي *معلومات عن الكرت الذي قمت ب شرائة.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
}
#=========={التحويلات}==========#
if($ex_data[0] == "Download4"){
$con=$ex_data[1];
$jk = $con * 8;
$oj = $con - 1;
$fj = $oj * 8;
$ij = $fj - 7;
$kb = $con + 1;
$jj = $jk - 8;
$ze='0';
if($sendmy == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
❌ - لا يوجد لديك سجل في هذا الحساب!
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($ze >= $jk){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك  🙋🏻.
➖ هذا الأرشيف الخاص بحسابك 📂.
➖ الإسم |  $first ✏️. 
➖ المُعرِف |  $user 🔖.
 ➖ ID | $chat_id 🆔.

➖ الرصيد| $Balance ₽ 🏦.
➖ عدد عملائك |  $counmy عضواً 📈.
➖ كمية رصيدك المستخدم في البوت | $consumers  ₽ 📮. 
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ 🗃 - صندوق مشترياتي ٭','callback_data'=>'Record2']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}else{
$key     = [];
$key['inline_keyboard'][] = [['text'=>"- رقم العملية 🅿️",'callback_data'=>"no"],['text'=>"- الحالة ☑️",'callback_data'=>"no"]];
foreach($BUYSSEND as $zero=>$num){
$oop++;
if($oop >= $jk){
break;
}
if($oop >= $fj){
if($BUYSSEND[$jk] != null){
$to="التالي. ⬅️";
}
if($BUYSSEND[$ij] != null){
$s = "➡️ السابق.";
}
$statuse = $num[status];
$status=str_replace(["-1","1","2","3"],["🚫","⏰","☑️","⏰"],$statuse);
$idSend = $num[id];
$key['inline_keyboard'][] = [['text'=>"$idSend",'callback_data'=>"sSendBuy#$zero#$idSend#$con"],['text'=>"$status",'callback_data'=>"file"]];
}
}
$key['inline_keyboard'][] = [['text'=>"$to",'callback_data'=>"Download4#$kb"],['text'=>"$s",'callback_data'=>"Download4#$oj"]];
$key['inline_keyboard'][] = [['text'=>'✥ عودة ↩ ٭..','callback_data'=>'Record']];
$keyboad      = json_encode($key);
if($idSend == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
⚠️ - لاتوجد صفحة أخرى حالياً.
",
'show_alert'=>true,
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - هذه قائمة *جميع عمليات التحويلات للروبل في البوت.*
⚜ - هذه العلامة *< ☑️ > تعني أن العملية تمت بنجاح* ، وهذه العلامة *< 🚫 > تعني أن العملية قد تم إلغائها* ، وهذه العلامة *< ⏰ > تعني أن العملية قيد الإنتظار.*
♻️ - إضغط على *رقم العلامة لإظهار باقي معلومات عن عملية التحويل الذي قمت بتحويلة.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
}
#=========={عمليات الشحن}==========#
if($ex_data[0] == "Download5"){
$con=$ex_data[1];
$jk = $con * 8;
$oj = $con - 1;
$fj = $oj * 8;
$ij = $fj - 7;
$kb = $con + 1;
$jj = $jk - 8;
$ze='0';
if($pricmy == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
❌ - لا يوجد لديك سجل في هذا الحساب!
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($ze >= $jk){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك  🙋🏻.
➖ هذا الأرشيف الخاص بحسابك 📂.
➖ الإسم |  $first ✏️. 
➖ المُعرِف |  $user 🔖.
 ➖ ID | $chat_id 🆔.

➖ الرصيد| $Balance ₽ 🏦.
➖ عدد عملائك |  $counmy عضواً 📈.
➖ كمية رصيدك المستخدم في البوت | $consumers  ₽ 📮. 
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ 🗃 - صندوق مشترياتي ٭','callback_data'=>'Record2']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}else{
$key     = [];
$key['inline_keyboard'][] = [['text'=>"- المحول ♻️",'callback_data'=>"no"],['text'=>"- المبلغ 💸",'callback_data'=>"no"],['text'=>"- التاريخ 📆",'callback_data'=>"no"]];
foreach($BUYSPRIC as $zero=>$num){
$oop++;
if($oop >= $jk){
break;
}
if($oop >= $fj){
if($BUYSPRIC[$jk] != null){
$to="التالي. ⬅️";
}
if($BUYSPRIC[$ij] != null){
$s = "➡️ السابق.";
}
$idSend = $num[id];
$via = $num[via];
$price = $num[price];
if($via == 4){
$price = $num[amount];
}
$via=str_replace(["1","2","3","4"],["الإدارة","الوكيل","صديق","كرت شحن"],$via);
$DAYS=$num[DAY];
$year = substr("$DAYS", 0,4);
$month = substr("$DAYS", 4,2);
$day = substr("$DAYS", 6,2);
$when = substr("$DAYS", 14,2);
$when=str_replace(["AM","PM"],["ص","م"],$when);
$days = "$day-$month-$year $when";
$key['inline_keyboard'][] = [['text'=>"$via",'callback_data'=>"sSendPric#$zero#$idSend#$con"],['text'=>"$price",'callback_data'=>"sSendPric#$zero#$idSend#$con"],['text'=>"$days",'callback_data'=>"sSendPric#$zero#$idSend#$con"]];
}
}
$key['inline_keyboard'][] = [['text'=>"$to",'callback_data'=>"Download5#$kb"],['text'=>"$s",'callback_data'=>"Download5#$oj"]];
$key['inline_keyboard'][] = [['text'=>'✥ عودة ↩ ٭..','callback_data'=>'Record']];
$keyboad      = json_encode($key);
if($idSend == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
⚠️ - لاتوجد صفحة أخرى حالياً.
",
'show_alert'=>true,
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- هذة جميع عمليات شحن حسابك في البوت.

↪️ - اضغط على التاريخ لعرض جميع التفاصيل.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
}
#=========={الأرقام}==========#
if($ex_data[0] == "sNumber"){
$idSend = $ex_data[1];
$status_num = $ex_data[2];
$adds = $ex_data[3];
$con = $ex_data[4];
$order=$ORDERALL[$idSend][order];
$account=$ORDERALL[$idSend][account];
$number = $BUYSNUM[number][$order][phone];
$idSend = $BUYSNUM[number][$order][idSend];
$code1 = $BUYSNUM[number][$order][sms][0][code];
$code2 = $BUYSNUM[number][$order][sms][1][code];
$code3 = $BUYSNUM[number][$order][sms][2][code];
$code4 = $BUYSNUM[number][$order][sms][3][code];
$code5 = $BUYSNUM[number][$order][sms][4][code];
$code6 = $BUYSNUM[number][$order][sms][5][code];
$code7 = $BUYSNUM[number][$order][sms][6][code];
$code8 = $BUYSNUM[number][$order][sms][7][code];
$code9 = $BUYSNUM[number][$order][sms][8][code];
$allcod = count($BUYSNUM[number][$order][sms]);
$statuse = $BUYSNUM[number][$order][status];
$finish = $BUYSNUM[number][$order][finish];
$times = $BUYSNUM[number][$order][times];
$add = $BUYSNUM[number][$order][add];
$app = $BUYSNUM[number][$order][app];
$operator = $BUYSNUM[number][$order][operator];
$idnumber = $BUYSNUM[number][$order][idnumber];
$site = $BUYSNUM[number][$order][site];
$country = $BUYSNUM[number][$order][country];
$zero = $BUYSNUM[number][$order][zero];
$price = $BUYSNUM[number][$order][price];
$BALANCE = $Balance - $price;
$idnum = $BUYSNUM[number][$order][id];
$DAYS = $BUYSNUM[number][$order][DAY];
$year = substr("$DAYS", 0,4);
$month = substr("$DAYS", 4,2);
$day = substr("$DAYS", 6,2);
$hors = substr("$DAYS", 8,2);
$minute = substr("$DAYS", 10,2);
$second = substr("$DAYS", 12,2);
$when = substr("$DAYS", 14,2);
$day="$day-$month-$year $when";
$when=str_replace(["AM","PM"],["ص","م"],$when);
$tims="$hors:$minute:$second $when";
include("country.php");
$name = $_co['country'][$country];
$server = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);
$APP = str_replace(["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],["واتسأب","تيليجرام","فيسبوك","انستقرام","تويتر","تيك توك","قوقل","إيمو","فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام"],$server);
$senco1 = "💭 - الكود : *$code1* 1⃣";
$senco2 = "💭 - الكود : *$code2* 2⃣";
$senco3 = "💭 - الكود : *$code3* 3⃣";
$senco4 = "💭 - الكود : *$code4* 4⃣";
$senco5 = "💭 - الكود : *$code5* 5⃣";
$senco6 = "💭 - الكود : *$code6* 6⃣";
$senco7 = "💭 - الكود : *$code7* 7⃣";
$senco8 = "💭 - الكود : *$code8* 8⃣";
$senco9 = "💭 - الكود : *$code9* 9⃣";
if($allcod == 1){
$newcode = "$senco1";
}
if($allcod == 2){
$newcode = "$senco1\n$senco2";
}
if($allcod == 3){
$newcode = "$senco1\n$senco2\n$senco3";
}
if($allcod == 4){
$newcode = "$senco1\n$senco2\n$senco3\n$senco4";
}
if($allcod == 5){
$newcode = "$senco1\n$senco2\n$senco3\n$senco4\n$senco5";
}
if($allcod == 6){
$newcode = "$senco1\n$senco2\n$senco3\n$senco4\n$senco5\n$senco6";
}
if($allcod == 7){
$newcode = "$senco1\n$senco2\n$senco3\n$senco4\n$senco5\n$senco6\n$senco7";
}
if($allcod == 8){
$newcode = "$senco1\n$senco2\n$senco3\n$senco4\n$senco5\n$senco6\n$senco7\n$senco8";
}
if($allcod == 9){
$newcode = "$senco1\n$senco2\n$senco3\n$senco4\n$senco5\n$senco6\n$senco7\n$senco8\n$senco9";
}
if($code1 == null){
$newcode = "💭 - الكود : *------*";
}
if($statuse == 1){
$status = "قيد الإنتظار ... ⏳";
}elseif($statuse == 2){
$status = "تم الإيصال ✅";
}elseif($statuse == 3){
$status = "تم التفعيل ☑️";
}elseif($statuse == -1){
$status = "تم الحظر ⛔️";
}elseif($statuse == -2){
$status = "إنتهى الوقت ⌛️";
}
if($code1 == null){ 
$api=json_decode(file_get_contents("https://mega-ye.net/API/api-sites.php?action=getStatus&bot=$bot&site=$site&idnumber=$idnumber&number=$number&app=$app"),1);
$status_api = $api[status];
$statuscode = $api[code];
$agen = $api[agen];
$Location = $api[Location];
if($agen == "200"){
$agen = "- طلب الكود مرة أخرى🔂";
}else{
$agen = null;
}
}
if($statuse == 2 and time() - $times < $finish){
$end = "- إنهاء الحالة ☑️";
}
if($statuse == 1 and $statuscode == null and time() - $times >= $finish){
$status = "إنتهى الوقت ⌛️";
$statuse = -2;
$BUYSNUM[number][$order][status] = -2;
NumbBuys($BUYSNUM,$EM);
$ORDERALL[$idSend][status] = -2;
OrdAll($ORDERALL);
}
if($statuse == 2 and time() - $times >= $finish){
$status = "تم التفعيل ☑️";
$statuse = 3;
$BUYSNUM[number][$order][status] = 3;
NumbBuys($BUYSNUM,$EM);
$ORDERALL[$idSend][status] = 3;
OrdAll($ORDERALL);
}
if($statuse == 1 and time() - $times < $finish){
$cod = "- تحديث 🚥";
$ban = "- إلغاء الرقم 🚨";
}
if($statuse != 1 and $statuse != 2 and $buy['number'][$zero] != null){
$Repzero = "- شراء نفس الدولة لتطبيق $APP ☑️";
if($site=="5sim" and $country==72){
$Repurchase="- شراء نفس الرقم لتطبيق $APP ✅";
}
if($add >= 21 and $add <= 25){
$add=21;
}elseif($add >= 26 and $add <= 30){
$add=26;
}
if($add == 1){
$back = "Wo";
$MS="➡️ قائمة سيرفرات العروض ✔️";
}elseif($add >= 21 and $add <= 25){
$back = "saavmotamy";
$MS="➡️ قائمة سيرفرات $APP ✔️";
}elseif($add >= 26 and $add <= 30){
$back = "worldwide";
$MS="➡️ قائمة سيرفرات ال$APP ✔️";
}else{
$back = "Ms-$add-$country";
$MS="➡️ قائمة سيرفرات $APP ✔️";
}
$cods = "$country$app$operator$add";
if($add == 21 or $add ==26){
$BUYING="Wi-$add";
}else{
$BUYING="Ii-$cods";
}
}
if($statuscode != null and $code1 == null and $sudo == $id and time() - $times >= $finish){
$admin="☑️ - جلب الكود وفك تقييد العضو";
}
if($ordermy == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
❌ - لا يوجد لديك سجل في هذا الحساب!
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($account != $EM or $status_num != $number){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك يـ $first  🙋🏻.
➖ هذا أرشيف الأرقام اللتي قمت بشرائها 📂.
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ ✅ - الشراء الناجح ٭','callback_data'=>'Downloads#3#1']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'Record']]
]
])
]);
unlink("data/id/$id/step.txt");
}elseif($statuscode != null and mb_strlen("$statuscode") >= 3 and $statuse == 1 and $code1 == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"☑️ - تم وصول الكود بنجاح! رصيدك: ₽ $BALANCE",
'show_alert'=>false,
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - جميع معلومات عن عملية الشراء.

☎️ - الرقم : *$number*
💭 - الكود : *$statuscode*
✅ - الحالة : *تم الإيصال ✅*
🎬 - التطبيق : *$APP*
💸 - السعر : *$price*
🏋‍♂ - رقم العملية : *$idnum*
⏰ - الوقت : *$tims*
📆 - التاريخ : *$day*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- إنهاء الحالة ☑️",'callback_data'=>"ending-$idSend"]],
[['text'=>"$agen",'callback_data'=>"AgeCod-$idSend-1"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Downloads#$adds#$con"]]
]
])
]);
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
✅ 𝗡𝗨𝗺𝗕𝗘𝗥 : `$number`
💬 𝗖𝗢𝗗𝗘 : `$statuscode`

☑️ - إضغط على الكود او الرقم للنسخ 🙂🖤𖠛
[  ➖ 💚  تعليمات لسلامة رقمك   💡  ](http://t.me/$usrch1/14833)
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
bot('sendMessage',[
'chat_id'=>$PAY,
'text'=>"
⚜ تم وصول كود الرقم:

☎️ - الرقم : *$number*
🚸 - الكود : *$statuscode*
🧩 - الدولة : *$name*
🎯 - رمز الدولة : *$country*
🎬 - التطبيق : *$APP*
🎟 - الرصيد: *$BALANCE*
🏋 - الايدي: *$idnumber*
🔥 - السعر : *₽ $price*
🤸‍♂ - الحساب : *$EM*
🔎 - رقم العمل: *$idSend*
🎗 - الموقع: *$Location & $operator*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$id"]],
[['text'=>"تسجيل الدخول.",'callback_data'=>"emilead-$EM"]],
[['text'=>'دفع المبلغ.','callback_data'=>"Pay_am-$EM-$price"]]
]
])
]);
$BUYSNUM[number][$order][sms][0][code] = $statuscode;
$BUYSNUM[number][$order][status] = 2;
$BUYSNUM[number_my] += 1;
NumbBuys($BUYSNUM,$EM);
$ORDERALL[$idSend][status] = 2;
$ORDERALL[number] +=1;
$ORDERALL[ruble] +=$price;
OrdAll($ORDERALL);
$points = file_get_contents("EMILS/$EM/points.txt");
$as = $points - $price;
file_put_contents("EMILS/$EM/points.txt",$as);
unlink("data/id/$id/restriction.txt");
unlink("data/id/$id/step.txt");
}else{
$key     = [];
$key['inline_keyboard'][] = [['text'=>"$admin",'callback_data'=>"gets-$EM-$statuscode-$idSend"]];
$key['inline_keyboard'][] = [['text'=>"$Repurchase",'callback_data'=>"Ii-$cods-$order"]];
$key['inline_keyboard'][] = [['text'=>"$end",'callback_data'=>"ending-$idSend"]];
$key['inline_keyboard'][] = [['text'=>"$agen",'callback_data'=>"AgeCod-$idSend-$allcod"]];
$key['inline_keyboard'][] = [['text'=>"$cod",'callback_data'=>"Code-$idSend"]];
$key['inline_keyboard'][] = [['text'=>"$ban",'callback_data'=>"Ban-$idSend"]];
if($Repzero!=null){
$key['inline_keyboard'][] = [['text'=>"- شراء نفس الدولة لتطبيق $APP ☑️",'callback_data'=>"$BUYING"]];
}
$key['inline_keyboard'][] = [['text'=>"$MS",'callback_data'=>"$back-"]];
$key['inline_keyboard'][] = [['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Downloads#$adds#$con"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - جميع معلومات عن عملية الشراء.

🚦 - الدولة : *$name*
☎️ - الرقم : *$number*
$newcode
✅ - الحالة : *$status*
🎬 - التطبيق : *$APP*
💸 - السعر : *₽ $price*
🏋‍♂ - رقم العملية : *$idnum*
⏰ - الوقت : *$tims*
📆 - التاريخ : *$day*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
}
}
if($exdata[0] == "gets"){
$EEM = $exdata[1];
$code = $exdata[2];
$idSend = $exdata[3];
$order=$ORDERALL[$idSend][order];
$account=$ORDERALL[$idSend][account];
$idd=$EMILS['emils'][$EEM]['id'];
$BUYSNUM = json_decode(file_get_contents("EMILS/$account/number.json"),true);
if($BUYSNUM[number][$order][sms][0][code] == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"☑️ - تم إضافة الكود وفك تقييد العضو بنجاح",
'show_alert'=>true
]);
$BUYSNUM[number][$order][sms][0][code] = $code;
$BUYSNUM[number][$order][status] = 2;
$BUYSNUM[number_my] += 1;
NumbBuys($BUYSNUM,$account);
$ORDERALL[$idSend][status] = 2;
$ORDERALL[number] +=1;
$ORDERALL[ruble] +=$price;
OrdAll($ORDERALL);
unlink("data/id/$idd/restriction.txt");
unlink("data/id/$id/step.txt");
}
}
#=========={الأرقام الحاهزة}==========#
if($ex_data[0] == "sReady"){
$idSend = $ex_data[1];
$status_num = $ex_data[2];
$con = $ex_data[3];
$order=$ORDERALL[$idSend][order];
$account=$ORDERALL[$idSend][account];
$name = $BUYSNUM[number][$order][name];
$app = $BUYSNUM[number][$order][app];
$number = $BUYSNUM[number][$order][phone];
$code = $BUYSNUM[number][$order][code];
$price = $BUYSNUM[number][$order][price];
$idnumber = $BUYSNUM[number][$order][id];
$DAYS = $BUYSNUM[number][$order][DAY];
$year = substr("$DAYS", 0,4);
$month = substr("$DAYS", 4,2);
$day = substr("$DAYS", 6,2);
$hors = substr("$DAYS", 8,2);
$minute = substr("$DAYS", 10,2);
$second = substr("$DAYS", 12,2);
$when = substr("$DAYS", 14,2);
$day="$day-$month-$year $when";
$when=str_replace(["AM","PM"],["ص","م"],$when);
$tims="$hors:$minute:$second $when";
$APP = str_replace(["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj"],["واتسأب","تيليجرام","فيسبوك","انستقرام","تويتر","تيك توك","قوقل","إيمو","فايبر","سناب شات","نيتفلكس","حراج"],$app);
if($readymy == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
❌ - لا يوجد لديك سجل في هذا الحساب!
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($status_num != $number){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك  🙋🏻.
➖ هذا الأرشيف الخاص بحسابك 📂.
➖ الإسم |  $first ✏️. 
➖ المُعرِف |  $user 🔖.
 ➖ ID | $chat_id 🆔.

➖ الرصيد| $Balance ₽ 🏦.
➖ عدد عملائك |  $counmy عضواً 📈.
➖ كمية رصيدك المستخدم في البوت | $consumers  ₽ 📮. 
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ 🗃 - صندوق مشترياتي ٭','callback_data'=>'Record2']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - جميع معلومات عن عملية الشراء للرقم الجاهز.

🚦 - الدولة : *$name*
☎️ - الرقم : *$number*
💭 - الكود : *$code*
🎬 - التطبيق : *$APP*
💸 - السعر : *₽ $price*
🏋‍♂ - رقم العملية : *$idnumber*
⏰ - الوقت : *$tims*
📆 - التاريخ : *$day*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Download2#$con"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={الكروت}==========#
if($ex_data[0] == "sCardBuy"){
$order = $ex_data[1];
$status_card = $ex_data[2];
$con = $ex_data[3];
$card = $BUYSCARD[$order][card];
$price = $BUYSCARD[$order][price];
$idcard = $BUYSCARD[$order][id];
$status = $BUYSCARD[$order][status];
$idd = $BUYSCARD[$order]["user_chat-id"];
$api = json_decode(file_get_contents("http://api.telegram.org/bot".API_KEY."/getChat?chat_id=".$idd.""));
$name =$api->result->first_name;
$iduser =$api->result->username;
if($status == 1){
$statues = "✅ - الحالة : *تم شحن الكرت* 🎟
💠 - الشاحن : *$name*";
$yes="☑️ - رابط العضو ↖️";
}else{
$statues = "✅ - الحالة : *لم يتم شحن الكرت* 🎫";
}
$amount = $BUYSCARD[$order][amount];
$DAYS = $BUYSCARD[$order][DAY];
$year = substr("$DAYS", 0,4);
$month = substr("$DAYS", 4,2);
$day = substr("$DAYS", 6,2);
$hors = substr("$DAYS", 8,2);
$minute = substr("$DAYS", 10,2);
$second = substr("$DAYS", 12,2);
$when = substr("$DAYS", 14,2);
$day="$day-$month-$year $when";
$when=str_replace(["AM","PM"],["ص","م"],$when);
$tims="$hors:$minute:$second $when";
if($cardmy == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
❌ - لا يوجد لديك سجل في هذا الحساب!
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($status_card != $card){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك  🙋🏻.
➖ هذا الأرشيف الخاص بحسابك 📂.
➖ الإسم |  $first ✏️. 
➖ المُعرِف |  $user 🔖.
 ➖ ID | $chat_id 🆔.

➖ الرصيد| $Balance ₽ 🏦.
➖ عدد عملائك |  $counmy عضواً 📈.
➖ كمية رصيدك المستخدم في البوت | $consumers  ₽ 📮. 
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ 🗃 - صندوق مشترياتي ٭','callback_data'=>'Record2']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - جميع معلومات عن الكرت الذي قمت بشرائة.

💬 - الكرت : *$card*
💸 - السعر : *₽ $price*
🏋‍♂ - رقم العملية : *$idcard*
$statues
💸 - قيمة الكرت : *₽ $amount*
⏰ - الوقت : *$tims*
📆 - التاريخ : *$day*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$yes",'url'=>"tg://openmessage?user_id=$idd"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Download3#$con"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={عمليات التحويل}==========#
if($ex_data[0] == "sSendBuy"){
$order = $ex_data[1];
$status_send = $ex_data[2];
$con = $ex_data[3];
$idSend = $BUYSSEND[$order][id];
$price = $BUYSSEND[$order][amount];
$coun = $BUYSSEND[$order][price];
$rubel = $coun-$price;
$code = $BUYSSEND[$order][code];
$status = $BUYSSEND[$order][status];
$emils = $BUYSSEND[$order][user_emil];
$idd = $BUYSSEND[$order]["user_chat-id"];
$api = json_decode(file_get_contents("http://api.telegram.org/bot".API_KEY."/getChat?chat_id=".$idd.""));
$name =$api->result->first_name;
$iduser =$api->result->username;
if($status == 2){
$statues = "✅ - الحالة : *تم الإستلام* ☑️
💠 - الشاحن : *$name*";
$yes="☑️ - رابط العضو ↖️";
}elseif($status == 1){
$statues = "✅ - الحالة : *جاري الإنتظار* ⏰";
$no="🚫 - إلغاء العملية";
}elseif($status == 3){
$statues = "✅ - الحالة : *جاري إنتظار إلغاء الطلب* ⏰";
}else{
$statues = "✅ - الحالة : *تم الإلغاء* 🚫";
}
$DAYS = $BUYSSEND[$order][DAY];
$year = substr("$DAYS", 0,4);
$month = substr("$DAYS", 4,2);
$day = substr("$DAYS", 6,2);
$hors = substr("$DAYS", 8,2);
$minute = substr("$DAYS", 10,2);
$second = substr("$DAYS", 12,2);
$when = substr("$DAYS", 14,2);
$day="$day-$month-$year $when";
$when=str_replace(["AM","PM"],["ص","م"],$when);
$tims="$hors:$minute:$second $when";
if($sendmy == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
❌ - لا يوجد لديك سجل في هذا الحساب!
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($status_send != $idSend){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك  🙋🏻.
➖ هذا الأرشيف الخاص بحسابك 📂.
➖ الإسم |  $first ✏️. 
➖ المُعرِف |  $user 🔖.
 ➖ ID | $chat_id 🆔.

➖ الرصيد| $Balance ₽ 🏦.
➖ عدد عملائك |  $counmy عضواً 📈.
➖ كمية رصيدك المستخدم في البوت | $consumers  ₽ 📮. 
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ 🗃 - صندوق مشترياتي ٭','callback_data'=>'Record2']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - جميع معلومات عن عملية التحويل.

💬 - رقم العملية : *$idSend*
💸 - الكمية : *₽ $price*
⛔️ - العمولة : *₽ $rubel*
🏋‍♂ - كود التحقق : `$code`
$statues
📋 - المستلم : *$emils*
⏰ - الوقت : *$tims*
📆 - التاريخ : *$day*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$yes",'url'=>"tg://openmessage?user_id=$idd"]],
[['text'=>"$no",'callback_data'=>"CancelTransfer-$order-$idSend"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Download4#$con"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={عمليات الشحن}==========#
if($ex_data[0] == "sSendPric"){
$order = $ex_data[1];
$status_send = $ex_data[2];
$con = $ex_data[3];
$idSend = $BUYSPRIC[$order][id];
$card = $BUYSPRIC[$order][card];
$amount = $BUYSPRIC[$order][amount];
$price = $BUYSPRIC[$order][price];
$status = $BUYSPRIC[$order][status];
$via = $BUYSPRIC[$order][via];
$emils = $BUYSPRIC[$order][user_emil];
$idd = $BUYSPRIC[$order]["user_chat-id"];
$api = json_decode(file_get_contents("http://api.telegram.org/bot".API_KEY."/getChat?chat_id=".$idd.""));
$name =$api->result->first_name;
if($name == null){
$name = $BUYSPRIC[$order][user_name];
}
$iduser =$api->result->username;
if($status == 2){
$statues = "تم الإستلام ☑️";
$code = $BUYSPRIC[$order][code];
$code = "*$code*";
}elseif($status == 1){
$statues = "العملية تحت الإنتظار ⏰";
$code = "[********]";
}elseif($status == 3){
$statues = "جاري إلغاء العملية ⏳";
$code = "[********]";
}else{
$statues = "العملية ملغية 🚫";
$code = $BUYSPRIC[$order][code];
$code = "*$code*";
}
$DAYS = $BUYSPRIC[$order][DAY];
$year = substr("$DAYS", 0,4);
$month = substr("$DAYS", 4,2);
$day = substr("$DAYS", 6,2);
$hors = substr("$DAYS", 8,2);
$minute = substr("$DAYS", 10,2);
$second = substr("$DAYS", 12,2);
$when = substr("$DAYS", 14,2);
$day="$day-$month-$year $when";
$when=str_replace(["AM","PM"],["ص","م"],$when);
$tims="$hors:$minute:$second $when";
if($via == 1){
$txt="
☑️ -  اليك جميع تفاصيل شحن حسابك.

🚀 - المحول : *$name*
🔗 - رابط المحول : [$idd](tg://openmessage?user_id=$idd)
💸 - المبلغ : *₽ $price*
💬 - رقم المرجع : *$idSend*
⏰ - الوقت : *$tims*
📆 - التاريخ : *$day*
";
}elseif($via == 3){
$txt="
☑️ -  اليك جميع تفاصيل شحن حسابك.

🚀 - المحول : *$name*
🔗 - رابط المحول : [$idd](tg://openmessage?user_id=$idd)
🕹 - حسابة : *$emils*
💸 - المبلغ : *₽ $price*
⭐️ - الحالة : *$statues*
🏋‍♂ - كود التحقق : $code
💬 - رقم المرجع : *$idSend*
⏰ - الوقت : *$tims*
📆 - التاريخ : *$day*
";
}elseif($via == 4){
$txt="
☑️ -  اليك جميع تفاصيل شحن حسابك.

🚀 - مالك الكرت : *$name*
🔗 - رابطة الخاص : [$idd](tg://openmessage?user_id=$idd)
🕹 - حسابة : *$emils*
🎟 - الكرت : *$card*
💸 - فئة الكرت : *₽ $amount*
⛔️ - سعر الكرت : *₽ $price*
💬 - رقم المرجع : *$idSend*
⏰ - الوقت : *$tims*
📆 - التاريخ : *$day*
";
}
if($pricmy == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
❌ - لا يوجد لديك سجل في هذا الحساب!
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}elseif($status_send != $idSend){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ مرحباً بك  🙋🏻.
➖ هذا الأرشيف الخاص بحسابك 📂.
➖ الإسم |  $first ✏️. 
➖ المُعرِف |  $user 🔖.
 ➖ ID | $chat_id 🆔.

➖ الرصيد| $Balance ₽ 🏦.
➖ عدد عملائك |  $counmy عضواً 📈.
➖ كمية رصيدك المستخدم في البوت | $consumers  ₽ 📮. 
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ 🗃 - صندوق مشترياتي ٭','callback_data'=>'Record2']],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
$txt
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Download5#$con"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#=========={الوكلاء}==========#
if($data == "gents"){
if(count($agents['gents']) < 1){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
❌ - عذرا عزيزي العميل لايوجد وكلاء حاليا
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
$key     = [];
$key['inline_keyboard'][] = [['text'=>"👮‍♂️ أسم الوكيل 👮‍♂️",'callback_data'=>"no"],['text'=>"📩⇣️ الرابط ⇣️",'callback_data'=>"no"]];
foreach($agents['gents'] as $zero=>$idgents){
$api = json_decode(file_get_contents("http://api.telegram.org/bot".API_KEY."/getChat?chat_id=".$idgents.""));
$name =$api->result->first_name;
$key['inline_keyboard'][] = [['text'=>"$name", 'url' => "tg://user?id=$idgents"],['text'=>" أضغظ هنا...⇣️",'url'=>"tg://openmessage?user_id=$idgents"]];
}
$key['inline_keyboard'][] = [['text'=>'🔄 - رجوع للخلف - 🔄','callback_data'=>'back']];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*مرحبا بك عزيزي : 💙 $first 💙*
*☠▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱☠*
*
هذه قسم الوكلاء يمكنك شحن رصيدك من الوكلاء الذي في الاسفل
*
*☠▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱☠*
𓆩•|ــــــــــ( $namebot )ـــــــــ|•𓆪
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
}
if($exdata[0] == "loggents"){
$zero=$exdata[1];
$idgents=$agents['gents'][$zero];
$emil=$zero;
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
🧑‍✈️ - الوكيل : *$name*
🛒 - يوزر الوكيل : *$txusers*
📩 - حسابه : *$emil*

☑️ *- وكيل معتمد من إدارة البوت.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رابط الوكيل 🧑‍✈️",'url'=>"tg://openmessage?user_id=$idgents"]],
[['text'=>"- الإبلاغ عنه! ⚠️",'callback_data'=>"super"]],
[['text'=>"🔄 - رجوع للخلف - 🔄",'callback_data'=>"gents"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
#=========={no}==========#
if($data == "no"){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>'هذا الزر فارغ...🌚',
'show_alert'=>true
]);
}
#=========={ why  }==========#
if($data == "whsy"){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>'*👤 - عزيزي  $first  💙
اولا :
*🔐 - تم اتخاذ هذا الإجراء بسبب الحسابات الوهمية*.
ثانيا :
📮 - تسهيل استخدام البوت للعملاء الجدد ✅️.
ثالثا :
📤 - مساعدتنا في حفظ معلوماتك ومعلومات جميع العملاء بقواعد بياناتنا 💚.
رابعا :
🌐 - لاتستخدم البوت في اشياء تضر الناس بها او تغضب الله ، نبري ذمتنا من اي شخص يستخدم البوت بشكل مضرر او خاطئ ، تذكر انك مراقب من الله عزوجل💙.
😇 - نتمنى لكم إستخداماً ممتعاً 🤍.
➖➖➖➖➖➖➖➖➖➖',
'show_alert'=>true
]);
}
#=========={الدعم}==========#
include("Coins.php");
#=========={بايننس تلقائي النويرة}==========#
$binance_api_url = "https://ramzi2.online/binance/binance.php";
// ========== دوال تخزين ==========
function setUserStep($user_id, $step) {
    file_put_contents("data/id/$user_id/step.txt", $step);
}
function getUserStep($user_id) {
    $file = "data/id/$user_id/step.txt";
    return file_exists($file) ? file_get_contents($file) : null;
}
function setUserData($user_id, $key, $value) {
    $file = "data/id/$user_id/data.json";
    $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $data[$key] = $value;
    file_put_contents($file, json_encode($data));
}
function getUserData($user_id, $key) {
    $file = "data/id/$user_id/data.json";
    if (!file_exists($file)) return null;
    $data = json_decode(file_get_contents($file), true);
    return $data[$key] ?? null;
}

// ========== تنفيذ الدفع ==========
$step = getUserStep($id);

// زر الدفع
if (isset($update->callback_query) && $data === "pay_binance") {
    bot('EditMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => "💳 <b>أدخل المبلغ الذي ستُحوّله بالدقة (مثال: 15.50)</b>",
        'parse_mode' => 'HTML',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => "🔙 رجوع", 'callback_data' => "back"]],
            ]
        ])
    ]);
    setUserStep($id, "waiting_for_amount");
    exit;
}

// استقبال المبلغ
if ($step === "waiting_for_amount" && isset($text) && $text !== '') {
    if (is_numeric($text) && floatval($text) >= 0.5) {
        $amount = floatval($text);
        setUserData($id, 'pay_amount', $amount);
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "✅ <b>تم تحديد المبلغ: $amount USDT</b>\n\n💳 <b>قم بتحويل المبلغ إلى حساب Binance التالي:</b>\n\n<code>1031361878</code>\n\n<b>بعد التحويل، أرسل رقم الطلب (Order ID) من Binance.</b>",
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "🔙 رجوع", 'callback_data' => "back"]],
                ]
            ])
        ]);
        setUserStep($id, "waiting_for_order");
    } else {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "🚫 <b>أقل مبلغ للتحويل هو 0.5 USDT</b>",
            'parse_mode' => 'HTML'
        ]);
    }
    exit;
}
if ($step === "waiting_for_order" && isset($text) && $text !== '') {
    $order_id = trim($text);
    $amount = getUserData($id, 'pay_amount');

    $used_orders_file = "data/used_orders.json";
    $used_orders = file_exists($used_orders_file) ? json_decode(file_get_contents($used_orders_file), true) : [];
    if (in_array($order_id, $used_orders)) {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "🚫 <b>تم شحن هذه العملية مسبقاً.</b>",
            'parse_mode' => 'HTML'
        ]);
        setUserStep($id, null);
        exit;
    }
    $verify_url = "$binance_api_url?order_id=$order_id&amount=$amount&api_key=$api_key&api_secret=$api_secret";
    $response = file_get_contents($verify_url);
    $data = json_decode($response, true);

    if (!$data || !isset($data['status'])) {
        bot('sendMessage', ['chat_id' => $chat_id, 'text' => "🚫 <b>خطأ في الاتصال بخادم التحقق.</b>", 'parse_mode' => 'HTML']);
        setUserStep($id, null);
        exit;
    }

    if ($data['status'] === "not_found") {
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "🚫 <b>لم يتم العثور على معاملة مطابقة برقم الطلب والمبلغ.</b>",
            'parse_mode' => 'HTML'
        ]);
        setUserStep($id, null);
        exit;
    }

    if ($data['status'] === "success") {
        $tx_time = $data['timestamp'];
        $txn_id = $data['txn_id'];

        if ($tx_time < $cutoff_timestamp) {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "🚫 <b>هذا التحويل قديم جداً وغير مقبول.\nيرجى تحويل المبلغ بعد التاريخ التالي:</b>\n$date_string",
                'parse_mode' => 'HTML'
            ]);
            setUserStep($id, null);
            exit;
        }

        $now = round(microtime(true) * 1000);
        if (($now - $tx_time) > 3 * 60 * 1000) {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "✅ <b>التحويل موجود ولكن مرّت أكثر من 3 دقائق على العملية.</b>\n\nيرجى التواصل مع الإدارة عبر الزر أدناه:",
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [['text' => "📩 تواصل مع الدعم", 'url' => "https://t.me/ho_5l"]],
                    ]
                ])
            ]);
            setUserStep($id, null);
            exit;
        }
        $rubles_to_add = $amount * $Exchange;
        $EM = $EMILNow['emil'][$id] ?? $EMIL[$id]['emil'] ?? null;
        if (!$EM) {
            bot('sendMessage', [
                'chat_id' => $chat_id,
                'text' => "🚫 <b>تعذر استرجاع حسابك، حاول لاحقاً.</b>",
                'parse_mode' => 'HTML'
            ]);
            setUserStep($id, null);
            exit;
        }

        $balance_file = "EMILS/$EM/points.txt";
        $current_balance = is_file($balance_file) ? (float)file_get_contents($balance_file) : 0;
        $new_balance = $current_balance + $rubles_to_add;
        file_put_contents($balance_file, $new_balance);

        $used_orders[] = $order_id;
        file_put_contents($used_orders_file, json_encode($used_orders));

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "🎉 <b>تم شحن حسابك بنجاح!</b>\n\n💵 المبلغ: <b>$amount USDT</b>\n💰 ما يعادل: <b>$rubles_to_add روبل</b>\n\n💼 <b>رصيدك الآن:</b> <code>$new_balance روبل</code>",
            'parse_mode' => 'HTML'
        ]);

        $admin_msg = "📥 <b>تحويل جديد تم شحنه:</b>\n\n👤 المستخدم: @$user\n💵 <b>$amount USDT</b>\n💰 <b>$rubles_to_add روبل</b>\n🕰️ <code>" . date('Y-m-d H:i:s') . "</code>\n🔢 orderId: <code>$order_id</code>";
        bot('sendMessage', ['chat_id' => $admin_channel, 'text' => $admin_msg, 'parse_mode' => 'HTML']);
    }

    setUserStep($id, null);
    exit;
}
#=========={unban}==========#
$data = explode("#", $update->callback_query->data);
$ex = $data;

if($ex[0] == "unban"){
    $idSend = $ex[1];
    $phone = $ex[2];

    file_get_contents("http://ramzi2.online/alnwerah.php?phone=" . urlencode($phone));

    bot('answerCallbackQuery', [
        'callback_query_id' => $update->callback_query->id,
        'text' => "✅︙تم إرسال طلب فك الحظر للرقم:\n$phone",
        'show_alert' => true,
    ]);
}
#=========={الدعم}==========#
$message_id           = $update->message->message_id;
$text = $update->message->text;
$chat_id = $update->message->chat->id;
$admin = $iDadmin;// Your Id 
if(isset($update->callback_query)){
$data = $update->callback_query->data;
$chat_id = $update->callback_query->message->chat->id;
$message_id = $update->callback_query->message->message_id;
$first = $update->callback_query->message->chat->first_name;
$user = $update->callback_query->message->chat->username;
$tc = $update->callback_query->message->chat->type;
}
$re = $update->message->reply_to_message;
$re_id = $update->message->reply_to_message->from->id;
$photo = $message->photo;
$video = $message->video;
$sticker = $message->sticker;
$file = $message->document;
$audio = $message->audio;
$voice = $message->voice;
$caption = $message->caption;
$photo_id = $message->photo[0]->file_id;
$video_id= $message->video->file_id;
$sticker_id = $message->sticker->file_id;
$file_id = $message->document->file_id;
$music_id = $message->audio->file_id;
$voice_id = $message->voice->file_id;
$video_note = $message->video_note;
$video_note_id = $video_note->file_id;
$tc = $message->chat->type;
$PHPXX = json_decode(file_get_contents("PHPXX.json"),true); 
if($data == "super"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💬 *- قسم التواصل مع الدعم أونلاين*

⚜ - هنا يمكنك *التواصل معنا* لحل جميع *المشكلات التي قد تواجهك* في البوت. 💛
☑️ - أرسل *رسالتك* الآن وسوف يتم *إيصالها الى الإدارة* مباشرة.

🕰 - متواجدون طوال اليوم. ❄️
❎ - لاترسل الألفاظ البذيئة فضلا. ☺️🖤
➖ [  الإدارة   ](t.me/$usradmin)
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- الغاء التواصل ⛔️",'callback_data'=>"back"]]
]])
]);
file_put_contents("data/id/$id/twas.txt","tw");
file_put_contents("PHPXX.json",json_encode($PHPXX));
}
if($text == "/start" and $twas == 'tw'){
unlink("data/id/$id/step.txt");
unlink("data/id/$id/twas.txt");
exit;
}
if($data == "back" and $twas == 'tw'){
unlink("data/id/$id/step.txt");
unlink("data/id/$id/twas.txt");
exit;
}
if($text != "/start" and $chat_id != $admin and $message and $tc == "private" and $twas == 'tw'){
if($data){
}
$mes= bot('forwardMessage',[
'chat_id'=>$admin,
'from_chat_id'=>$id,
'message_id'=>$message_id,
]);
$send = $mes->result->message_id;
$PHPXX['tws'][$send]['User'] = $id;
$PHPXX['tws'][$send]['Message'] = $message_id;
file_put_contents("PHPXX.json",json_encode($PHPXX));
bot('sendMessage',[
'chat_id'=>$id,
'text'=>"
☑️ *- تم إرسال رسالتك للمدير بنجاح
♻️ - الرجاء التحلي بالصبر ريثما يتم الرد عليك* 🤙🏻
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- الغاء التواصل ⛔️",'callback_data'=>"back"]]
]])
]);
file_put_contents("PHPXX.json",json_encode($PHPXX));
}
if($chat_id == $admin and $PHPXX['tws'][$message->reply_to_message->message_id] != null and isset($update->message->reply_to_message)){
$messageid = $PHPXX['tws'][$message->reply_to_message->message_id]['Message'];
$Alkhaledi = $PHPXX['tws'][$message->reply_to_message->message_id]['User'];
$Tesaa          = "";
if($text){
bot('sendMessage', [
'chat_id'=>$Alkhaledi,
'text'=>"$Tesaa$text",
'reply_to_message_id'=>$messageid,
]);
}elseif($photo){
bot('sendphoto', [
'chat_id'=>$Alkhaledi,
'photo'=>$photo_id,
'caption'=>$Tesaa.$caption,
'reply_to_message_id'=>$messageid,
]);
}elseif($video){
bot('Sendvideo',[
'chat_id'=>$Alkhaledi,
'video'=>$video_id,
'caption'=>$Tesaa.$caption,
'reply_to_message_id'=>$messageid,
]);
}elseif($video_note){
bot('Sendvideonote',[
'chat_id'=>$Alkhaledi,
'video_note'=>$video_note_id,
]);
}elseif($sticker){
bot('Sendsticker',[
'chat_id'=>$Alkhaledi,
'sticker'=>$sticker_id,
'reply_to_message_id'=>$messageid,
]);
}elseif($file){
bot('SendDocument',[
'chat_id'=>$Alkhaledi,
'document'=>$file_id,
'caption'=>$Tesaa.$caption,
'reply_to_message_id'=>$messageid,
]);
}elseif($music){
bot('Sendaudio',[
'chat_id'=>$Alkhaledi,
'audio'=>$music_id,
'caption'=>$Tesaa.$caption,
'reply_to_message_id'=>$messageid,
]);
}elseif($voice){
bot('Sendvoice',[
'chat_id'=>$Alkhaledi,
'voice'=>$voice_id,
'caption'=>$Tesaa.$caption,
'reply_to_message_id'=>$messageid,
]);
}
bot('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"
☑️ *- تم الرد على المستخدم بنجاح* 🤙🏻
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$Alkhaledi"]]
]
])
]);
}
#=========={الأوامر الخاصة بالأدمن}==========#
if($id == $sudo){
if($text == '/admin'){
$auto_available = json_decode(file_get_contents("auto_available.json"),true);
if(!$auto_available){$auto_available = array("id"=>null,"active"=>false);}
if($auto_available["active"]){$auto_available_m="المتوفر تلقائى يعمل ✔️";}else{$auto_available_m="المتوفر تلقائى لا يعمل ❌️";}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
🙋🏻‍♂️ ⌯ أهلاً بك عزيزي المطور في لوحة التحكم الخاصة بك $first 💙.

🏆 - عملة البوت : [ روبل ₽ ].

♻️︙لتغيير نظام البوت ارسل `النظام` .

✔️︙لربط المواقع ارسل `المواقع`.
🎰︙لربط التطبيقات ارسل `التطبيقات` .

✖️︙لتصفير رصيد عضو ارسل `تصفير` ⛔.
",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>'قسم الرشق ','callback_data'=>"Splash_settings"],['text'=>'قسم شحن الالعاب ','callback_data'=>"Splash_playing"]],
[['text'=>'اقسام الخدمات','callback_data'=>"yase"]],
[['text'=>"⛔️︙حذف دولة 🔸️.",'callback_data'=>"delnumber"],['text'=>"🌍︙اضافة دولة🔹️.",'callback_data'=>"addnumber"]],
[['text'=>"🧲︙خصم روبل₽ 🔸️.",'callback_data'=>"delcoin"],['text'=>"💸︙اضافة روبل₽ 🔹️.",'callback_data'=>"addcoin"]],
[["text"=>"حذف رقم جاهز ⬆️","callback_data"=>"delreadynumber"],["text"=>"أضف رقم جاهز 📞","callback_data"=>"readynumber"]],
[['text'=>"💸 اضافه روبل بعد شراء الرقم",'callback_data'=>"Shippingrublenumber"]],
[['text'=>"🚫︙تقييد عضو🔸️.",'callback_data'=>"res"],['text'=>"💎︙الغاء تقييد عضو🔹️.",'callback_data'=>"unnum"]],
[['text'=>'⚜️︙ربط المواقع بـAPI︙⚜️.','callback_data'=>'counapi']],
[['text'=>"📢︙إرسال رسالة 🔸️.",'callback_data'=>"set"],['text'=>"💠︙فتح وقفل الأقسام 🔹️.",'callback_data'=>"opclo"]],
[['text'=>"🤖︙إحصائيات البوت🔸️.",'callback_data'=>"baluser"],['text'=>"👥️️︙عدد المشتركين🔹️.",'callback_data'=>"Subscribersy"]],
[['text'=>'📱︙كشف الرصيد🔸️.','callback_data'=>'cop'],['text'=>'🃏︙صنع كرت🔹️.','callback_data'=>'card']],
[['text'=>"📑︙تسجيلات الدخول", 'url'=>"$ch1"],['text'=>"📌 ︙الارقام الملغية.", 'url'=>"$ch2"]],
[['text'=>$auto_available_m,'callback_data'=>"matat"],['text'=>"تعديل رسالة المتوفر ☑️",'callback_data'=>"motawfer"]],
[['text'=>"🔔︙الارقام المكتملة.", 'url'=>"$ch3"]],
[['text'=>'حذف وكيل ︙⛔️','callback_data'=>'delagent'],['text'=>'إضافة وكيل ︙🧑‍✈️','callback_data'=>'addagent']]
]
])
]);
unlink("zzz.json");
file_put_contents('data/txt/bc.txt','no');
unlink("data/id/$id/step.txt");
}
#=========={رجوع للقائمة}==========#
if($data == 'c'){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
🙋🏻‍♂️ ⌯ أهلاً بك عزيزي المطور في لوحة التحكم الخاصة بك $first 💙.

🏆 - عملة البوت : [ روبل ₽ ].

♻️︙لتغيير نظام البوت ارسل `النظام` .

✔️︙لربط المواقع ارسل `المواقع`.
🎰︙لربط التطبيقات ارسل `التطبيقات` .

✖️︙لتصفير رصيد عضو ارسل `تصفير` ⛔.
",
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>'قسم الرشق ','callback_data'=>"Splash_settings"],['text'=>'قسم شحن الالعاب ','callback_data'=>"Splash_playing"]],
[['text'=>'اقسام الخدمات','callback_data'=>"yase"]],
[['text'=>"⛔️︙حذف دولة 🔸️.",'callback_data'=>"delnumber"],['text'=>"🌍︙اضافة دولة🔹️.",'callback_data'=>"addnumber"]],
[['text'=>"🧲︙خصم روبل₽ 🔸️.",'callback_data'=>"delcoin"],['text'=>"💸︙اضافة روبل₽ 🔹️.",'callback_data'=>"addcoin"]],
[["text"=>"حذف رقم جاهز ⬆️","callback_data"=>"delreadynumber"],["text"=>"أضف رقم جاهز 📞","callback_data"=>"readynumber"]],
[['text'=>"💸 اضافه روبل بعد شراء الرقم",'callback_data'=>"Shippingrublenumber"]],
[['text'=>"🚫︙تقييد عضو🔸️.",'callback_data'=>"res"],['text'=>"💎︙الغاء تقييد عضو🔹️.",'callback_data'=>"unnum"]],
[['text'=>'⚜️︙ربط المواقع بـAPI︙⚜️.','callback_data'=>'counapi']],
[['text'=>"📢︙إرسال رسالة 🔸️.",'callback_data'=>"set"],['text'=>"💠︙فتح وقفل الأقسام 🔹️.",'callback_data'=>"opclo"]],
[['text'=>"🤖︙إحصائيات البوت🔸️.",'callback_data'=>"baluser"],['text'=>"👥️️︙عدد المشتركين🔹️.",'callback_data'=>"Subscribersy"]],
[['text'=>'📱︙كشف الرصيد🔸️.','callback_data'=>'cop'],['text'=>'🃏︙صنع كرت🔹️.','callback_data'=>'card']],
[['text'=>"📑︙تسجيلات الدخول", 'url'=>"$ch1"],['text'=>"📌 ︙الارقام الملغية.", 'url'=>"$ch2"]],
[['text'=>$auto_available_m,'callback_data'=>"matat"],['text'=>"تعديل رسالة المتوفر ☑️",'callback_data'=>"motawfer"]],
[['text'=>"🔔︙الارقام المكتملة.", 'url'=>"$ch3"]],
[['text'=>'حذف وكيل ︙⛔️','callback_data'=>'delagent'],['text'=>'إضافة وكيل ︙🧑‍✈️','callback_data'=>'addagent']]
]
])
]);
unlink("zzz.json");
file_put_contents('data/txt/bc.txt','no');
unlink("data/id/$id/step.txt");
}

$sim2 =-1002653607921;

if($data == "motawfer"){
//made by @A_5_5_B / @JIlJlll
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💬 *- ارسال تطبيقات المتوفر الان*

 - مثال : wa
 - مثال : tg,wa
 
يمكنك ارسال اكتر من تطبيق عن طريق الفاصلة .
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- الغاء الارسال",'callback_data'=>"c"]]
]])
]);
file_put_contents("data/id/$id/twas.txt","tw");
file_put_contents("PHPXX.json",json_encode($PHPXX));
}
if($data == "matat"){
//made by @A_5_5_B / @JIlJlll
$auto_available = json_decode(file_get_contents("auto_available.json"),true);
if(!$auto_available){$auto_available = array("id"=>null,"active"=>false,"token"=>API_KEY,"apps"=>"wa");}
if($auto_available["active"]){
$auto_available["active"] = false;
$t = "تم الغاء عمل خاصية المتوفر";
}else{
$auto_available["active"] = true;
$t = "تم تفعيل خاصية المتوفر";
}
$auto_available["token"] = API_KEY;
file_put_contents("auto_available.json",json_encode($auto_available));
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💬 *- ".$t."*

 *- التطبيقات : *".$auto_available["apps"]."
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع",'callback_data'=>"c"]]
]])
]);
file_put_contents("PHPXX.json",json_encode($PHPXX));
}
if($text == "/start" and $twas == 'tw'){
unlink("data/id/$id/step.txt");
unlink("data/id/$id/twas.txt");
exit;
}
if($data == "back" or $data == "c" and $twas == 'tw'){
unlink("data/id/$id/step.txt");
unlink("data/id/$id/twas.txt");
exit;
}
if($text != "/start" and $chat_id != $sim2 and $message and $tc == "private"){
if($twas == 'tw'){
//made by @A_5_5_B /0@JIlJlll
$auto_available = json_decode(file_get_contents("auto_available.json"),true);
if(!$auto_available){$auto_available = array("id"=>null,"active"=>false,"apps"=>"wa");}
$auto_available["chat"] = $sim2;
$auto_available["apps"] = trim($text," ");
$auto_available["token"] = API_KEY;
file_put_contents("auto_available.json",json_encode($auto_available));
bot('sendMessage',[
'chat_id'=>$id,
'text'=>"
 *- تم حفظ تطبيقات الأكثر توافر* 
 *- التطبيقات : *".$auto_available["apps"]."
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع",'callback_data'=>"c"]]
]])
]);
}
file_put_contents("PHPXX.json",json_encode($PHPXX));
}
#=========={المواقع}==========#
include("admin.php");
#=========={اذاعة ازرار}==========#
$ary = array($sudo);
$id = $message->chat->id;
$admins = in_array($id,$ary);
$data = $update->callback_query->data;
$from_id = $message->from->id;
$chat_id = $message->chat->id;
$chat_id2 = $update->callback_query->message->chat->id;
$cut = explode("\n",file_get_contents("data/txt/file.txt"));
$users = count($cut)-1;
$modee = file_get_contents("data/txt/bc.txt");
#Start code 
if ($update && !in_array($id, $cut)) {
file_put_contents("data/txt/file.txt", $id."\n",FILE_APPEND);
}
if($data == "set"){
bot('EditMessageText',[
'chat_id'=>$chat_id2,
'message_id'=>$update->callback_query->message->message_id,
'text'=>"
💬 - أختر نوع الرسالة الذي تريد ان ترسلها 🔰
",
'reply_to_message_id'=>$message->message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"💬 رسالة عادية",'callback_data'=>"setNormal"]],
[['text'=>"💬 رسالة ماركدون",'callback_data'=>"setMark"]],
[['text'=>"✥ عودة ↩ ٭..",'callback_data'=>"c"]]
]
])
]);
file_put_contents("data/txt/bc.txt","no");
unlink("data/id/$id/step.txt");
}
if($data == "setNormal"){
bot('EditMessageText',[
'chat_id'=>$chat_id2,
'message_id'=>$update->callback_query->message->message_id,
'text'=>"
⚜ - أرسل رسالتك ليتم إرسالها إلى $users مشترك 👥
♻️ - كتابة فقط...🌚
",
'reply_to_message_id'=>$message->message_id,
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ عودة ↩ ٭..",'callback_data'=>"set"]]
]
])
]);
file_put_contents("data/txt/bc.txt","yas1");
}
if($text and $text != '/start' and $modee == "yas1"){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚜ - تم قبول رسالتك!
♻️ - ويتم إرسالها إلى $users مشترك 👥
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ عودة ↩ ٭..",'callback_data'=>"c"]]
]
])
]);
for ($i=0; $i < count($cut); $i++) { 
bot('sendMessage',[
'chat_id'=>$cut[$i],
'text'=>"$text",
'disable_web_page_preview'=>true,
]);
file_put_contents("data/txt/bc.txt","no");
} 
}
if($data == "setMark"){
bot('EditMessageText',[
'chat_id'=>$chat_id2,
'message_id'=>$update->callback_query->message->message_id,
'text'=>"
⚜ - أرسل رسالتك ليتم إرسالها إلى $users مشترك 👥
♻️ - كتابة فقط...🌚
",
'reply_to_message_id'=>$message->message_id,
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ عودة ↩ ٭..",'callback_data'=>"set"]]
]
])
]);
file_put_contents("data/txt/bc.txt","yas2");
}
if($text and $text != '/start' and $modee == "yas2"){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
⚜ - تم قبول رسالتك!
♻️ - ويتم إرسالها إلى $users مشترك 👥
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ عودة ↩ ٭..",'callback_data'=>"c"]]
]
])
]);
for ($i=0; $i < count($cut); $i++) { 
bot('sendMessage',[
'chat_id'=>$cut[$i],
'text'=>"$text",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
]);
file_put_contents("data/txt/bc.txt","no");
} 
}
if($message->reply_to_message and $text=="رفع" and $message->reply_to_message->document){
$a= $message->reply_to_message->document->file_id;
$get=bot("getfile",[
"file_id"=>$a
])->result->file_path; 
$v="teampro.php";
$file=file_put_contents($v, file_get_contents("https://api.telegram.org/file/bot".API_KEY."/".$get));
bot("sendmessage",[
"chat_id"=>$chat_id,
"text"=>"
تم رفع الملف بنجاح ✅
",
'reply_to_message_id'=>$message_id,
]);
}
}
    ?>