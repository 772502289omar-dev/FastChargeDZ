<?php


$sim = -1003530119370; #الخاصه (الرسمية)
$Pay = -1003990109653; #التفعيلات

#=========={اسامي الالعاب}==========#
function EngYass($array){
file_put_contents('data/EngYASNl.json', json_encode($array,64|128|256));
}
$EngYASNl = json_decode(file_get_contents('data/EngYASNl.json'),true);
$BUYGAMES = json_decode(file_get_contents("EMILS/$EM/YAS_Nl.json"),true);
$yassenmy = count($BUYGAMES[YAS_Nl]);

$GAMEpubg = "• 🚀 ببجي موبايل - 𝗣𝗨𝗣𝗚 𝗠𝗢𝗕𝗜𝗟𝗘 🕹 •";
$GAMEfreefire = "• 🚀 فري فاير - 𝗙𝗥𝗘𝗘 𝗙𝗜𝗥𝗘 🕹 •";
$GAMEylalodo = "يالا لودو -  َِ𝗟َِ𝘂َِ𝗱َِ𝗼 . •";
$GAMEjoaker = "جواكر -  َِ𝗝َِ𝗮َِ𝘄َِ𝗮َِ𝗸َِ𝗲َِ𝗿 . •";
$GAMEclashofclanes = "• 🚀 كـلاش - Clash of clans 🕹 •";
#=========={بداية القسم}==========#
$BUYGAMES = json_decode(file_get_contents("EMILS/$EM/YAS_Nl.json"),1);
if($data == "EngYASNl0"){
        bot('EditMessageText',[
        'chat_id'=>$chat_id,
        'message_id'=>$message_id,
    'text'=>"
<b> 🧌: يمكنك شحن ألعابك وبرامجك والتطوير من شخصياتك وحساباتك.</b> 
——————————————————•
<u>🏎 إختر اللعبة او الخدمة المطلوبة من الاسفل •
🏎 أرخص الاسعار وتنفيذ فوري في الشحن •    </u>
",
            'parse_mode'=>"html",
        'reply_markup'=>json_encode([
                        'inline_keyboard'=>[
            [['text'=>"$GAMEpubg",'callback_data'=>"Ji2-1-1"]],
                    [['text'=>"$GAMEfreefire",'callback_data'=>"Ji2-1-2"]],
                   [['text'=>"$GAMEclashofclanes",'callback_data'=>"Ji2-1-5"]],
                [['text'=>'- رجوع 🔜','callback_data'=>"back"]]
            ]
        ])
    ]);
unlink("data/id/$id/step.txt");
}


if($exdata[0] == "Ji2"){
$nums=$exdata[1];
$type=$exdata[2];
$Z1X2C3 = str_replace(["1"], ["𝐆𝐀𝐌𝐄𝐒"], $nums);
$B4N5M6 = str_replace(["1"], ["الالعاب"], $nums);
$Q7W8E9R0 = str_replace(
    ["1", "2", "3", "4", "5"],
    [
        "$GAMEpubg", 
        "$GAMEfreefire",   
        "$GAMEylalodo",    
        "$GAMEjoaker",     
        "$GAMEclashofclanes" 
    ],
    $type 
);
$F0G1H2I3 = "$B4N5M6 $Z1X2C3";
$A1B2C3D4 = array_merge([$F0G1H2I3], [$Q7W8E9R0]);
$E5F6G7H8 = implode(" - ", $A1B2C3D4);
$finalOutput = strrev($E5F6G7H8); 
$key     = [];
$key['inline_keyboard'][] = [['text'=>'💲 ⌯ السعر.','callback_data'=>'no'],['text'=>'🃏 ⌯ الفئه.','callback_data'=>'no']];
foreach($EngYASNl["Yassen@YAS_Nl"] as $zero=>$num){
if($num[num] == $nums and $num[type] == $type){
$name=$num[name];
$price=$num[price];
$quality=$num[quality];
$key['inline_keyboard'][] = [['text'=>"$price .",'callback_data'=>"Sl-$zero"],['text'=>"$quality ⪼",'callback_data'=>"Sl-$zero"]];
}
}
$key['inline_keyboard'][] = [['text'=>'- رجوع 🔜','callback_data'=>"EngYASNl0"],['text'=>'- الصفحة الرئيسية 🔙','callback_data'=>"back"]];
$keyboad      = json_encode($key);
if($name == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"- 🙋🏻‍♀️ ⌯ عذرا عزيزي لاتوجد خدمات مضافة هنا.",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"*
✅︙يرجى إختيار الفئة من الأسفل ⏬.

☑️︙القسم : - $F0G1H2I3
🎮︙الخدمة: $Q7W8E9R0 
💸︙رصيدك : $Balance
*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
if($exdata[0] == "Sl"){
$zero=$exdata[1];
$num=$EngYASNl["Yassen@YAS_Nl"][$zero][num];
$type=$EngYASNl["Yassen@YAS_Nl"][$zero][type];
$Type=$EngYASNl["Yassen@YAS_Nl"][$zero][Type];
$name=$EngYASNl["Yassen@YAS_Nl"][$zero][name];
$minimum=$EngYASNl["Yassen@YAS_Nl"][$zero][minimum];
$maximum=$EngYASNl["Yassen@YAS_Nl"][$zero][maximum];
$explained=$EngYASNl["Yassen@YAS_Nl"][$zero][explained];
$speed=$EngYASNl["Yassen@YAS_Nl"][$zero][speed];
$quality=$EngYASNl["Yassen@YAS_Nl"][$zero][quality];
$get_off=$EngYASNl["Yassen@YAS_Nl"][$zero][get_off];
$start_time=$EngYASNl["Yassen@YAS_Nl"][$zero][start_time];
$price=$EngYASNl["Yassen@YAS_Nl"][$zero][price];
$exn = explode(".", $price);
if($exn[1] > 0){
$price="$price"."0";
}else{
$price="$price.00";
}
$pri=$EngYASNl["Yassen@YAS_Nl"][$zero];
if($pri==null){
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
⤵️ ⌯ معلومات الخدمة : $name

💸︙السعر :  $price 
☑️︙المطلوب : $explained

*- إرسل الان المطلوب كما هو موضح في معلومات الخدمة ⤵️.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$speed",'callback_data'=>":"],['text'=>"- الفئة 🌐",'callback_data'=>"::"]],
[['text'=>"$price روبل",'callback_data'=>":"],['text'=>"- السعر:💰",'callback_data'=>"::"]],
[['text'=>"$get_off",'callback_data'=>":"],['text'=>"- نوع الشحن 🌟",'callback_data'=>"::"]],
[['text'=>"خلال $start_time",'callback_data'=>":"],['text'=>"- وقت الشحن : ⏰",'callback_data'=>"::"]],
[['text'=>"- رجوع 🔜",'callback_data'=>"Ji2-$num-$type"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","Sl1|$zero");
exit;
}
if($text && $text != '/start' && $exstep[0] == "Sl1"){
$zero=$exstep[1];
$num=$EngYASNl["Yassen@YAS_Nl"][$zero][num];
$type=$EngYASNl["Yassen@YAS_Nl"][$zero][type];
$minimum=$EngYASNl["Yassen@YAS_Nl"][$zero][minimum];
$maximum=$EngYASNl["Yassen@YAS_Nl"][$zero][maximum];
$price=$EngYASNl["Yassen@YAS_Nl"][$zero][price];
$pri=$EngYASNl["Yassen@YAS_Nl"][$zero];
if($pri==null){
exit;
}
if($num==1){
$x=str_replace(["1","2","3","4","5"],["الايدي","الايدي","الايدي","الايدي","الايدي"],$type);
$urls="$text";
}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ ⌯ المدخلات : $urls

*🙋🏻‍♀️ ⌯ الان قم بارسال العدد  $minimum لتاكيد طلبك 🔥.*
",
'reply_to_message_id'=>$message_id,
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- الغاء عملية الشحن ⛔️",'callback_data'=>"Ji2-$num-$type"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","Sl2|$zero|$urls");
exit;
}
if($text && $text != '/start' && $exstep[0] == "Sl2"){
$zero=$exstep[1];
$urls=$exstep[2];
$as="points";
$array = substr(str_shuffle("0123456789"),1-10);
$num=$EngYASNl["Yassen@YAS_Nl"][$zero][num];
$type=$EngYASNl["Yassen@YAS_Nl"][$zero][type];
$name=$EngYASNl["Yassen@YAS_Nl"][$zero][name];
$minimum=$EngYASNl["Yassen@YAS_Nl"][$zero][minimum];
$maximum=$EngYASNl["Yassen@YAS_Nl"][$zero][maximum];
$price=$EngYASNl["Yassen@YAS_Nl"][$zero][price];
$c=$Balance-$price;
if($price > $Balance){
$v="❌ *- للاسف لايوجد لديك رصيد كافي* 🥹";
$vv=null;
}else{
$v="- بمجرد موافقتك سيتم تقديم الطلب وخصم المبلغ ، ولن تستطيع إلغاء الطلب في أسوأ الحالات ( تأكد من المدخلات جيداً ) ⚠️.

⤵️︙هل أنت موافق على تقديم هذا الطلب !؟";
$vv="- تقديم الطلب ✅";
}
$pri=$EngYASNl["Yassen@YAS_Nl"][$zero];
if($pri==null){
exit;
}
if($num==1){
$x=str_replace(["1","2","3","4","5"],["الايدي","الايدي","الايدي","الايدي","الايدي"],$type);
}
if(preg_match("/(\D)/",$text)){
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*- يجب ان يكون الايدي على شكل ارقام 🙄.*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
exit;
}
if($text > $maximum or $text < $minimum){
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
❌ *- نعتذر عزيزي يرجى كتابة $minimum لتاكيد طلبك ☺️*.
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
exit;
}
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
📋 *- خلاصة العملية: 👇

👨🏻‍💻 - الخدمة* : $name
🔗 *- $x* : [$urls] ⚜.

💰 *- السعر* : $price 💵
💳 *- رصيدك الآن* : $Balance .
🏆 *- رصيدك بعد الخصم* : $c

$v
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$vv",'callback_data'=>"c-$array"]],
[['text'=>'- الغاء','callback_data'=>"Ji2-$num-$type"]]
]
])
]);
file_put_contents("data/id/$id/$array.txt","$zero|$urls|$text|$EM");
unlink("data/id/$id/step.txt");
exit;
}
if($exdata[0] == "c"){
$array=$exdata[1];
$idrb=file_get_contents("data/id/$id/$array.txt");
$ex = explode("|", $idrb);
$zero=$ex[0];
$urls=$ex[1];
$idurls=substr($urls, 0,-3)."•••";
$number=$ex[2];
$emil=$ex[3];
$idSend=$orderall;
$ID=$EngYASNl["Yassen@YAS_Nl"][$zero][ID];
$num=$EngYASNl["Yassen@YAS_Nl"][$zero][num];
$type=$EngYASNl["Yassen@YAS_Nl"][$zero][type];
$Type=$EngYASNl["Yassen@YAS_Nl"][$zero][Type];
$name=$EngYASNl["Yassen@YAS_Nl"][$zero][name];
$price=$EngYASNl["Yassen@YAS_Nl"][$zero][price];
$add_site=$EngYASNl["Yassen@YAS_Nl"][$zero][add];
$Location=str_replace(["1","2","3"],["smmxstar.com","yemendamkom.com","Smmstone.com"],$add_site);
$ex=explode(".", $Location);
$api_key=file_get_contents("data/api/$ex[0].txt");
$keys = ["1"];
$values = [
    "شحن العاب"
];
$APP = str_replace($keys, $values, $num);
if($num==1){
$x=str_replace(["1","2","3","4","5"],["الايدي","الايدي","الايدي","الايدي","الايدي"],$type);
}
if($price > $Balance){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"ليس لديك رصيد كافي",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
unlink("data/id/$id/$array.txt");
exit;
}
$pri=$EngYASNl["Yassen@YAS_Nl"][$zero];
if($idrb and ($EM != $emil or $pri==null)){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"حدث خطأ ما أعد المحاولة من جديد 1
$Location
$api_key
$ID
$urls
$number",
'show_alert'=>true
]);
file_put_contents('data/EngYASNl.json', json_encode($EngYASNl,64|128|256));
unlink("data/id/$id/step.txt");
unlink("data/id/$id/$array.txt");
exit;
}
if($idrb and ($array != null or $zero != null or $urls != null or $number != null or $emil != null)){
$api=json_decode(file_get_contents("https://$Location/api/v2?key=$api_key&action=add&service=$ID&link=$urls&quantity=$number"),1);
file_put_contents("test.txt",json_encode($api));
$order=$api["order"];
$error=$api["error"];
if($order == null or $error){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"حدث خطأ ما أعد المحاولة من جديد 2
$error",
'show_alert'=>true
]);
file_put_contents('data/EngYASNl.json', json_encode($EngYASNl,64|128|256));
unlink("data/id/$id/step.txt");
unlink("data/id/$id/$array.txt");
exit;
exit;
}else{
unlink("data/id/$id/step.txt");
unlink("data/id/$id/$array.txt");
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
✅ *-تم تقديم طلب الشحن* ✔️

📡 *- المدخلات :* [$urls]
⚜ *- نوع الخدمة * : $name
💲 ⪼ السعر :  [ $price روبل ₽] 
⏰️ ⪼ تم الطلب في : $DAY3 .

⚜ *- نتمنى لكم تجربه ممتعة* ❤️‍🔥
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- تحديث ✅','callback_data'=>"Zb-$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$Pay,
'text'=>"
<blockquote>تم تقديم طلبية شحن جديدة ✅️</blockquote>

<b>📌 - الخدمة : <tg-spoiler> $name </tg-spoiler> .</b>
<b>🌍 - رقم الطلب : <code>$idSend</code> 🆔️• .</b>
<b>📲 - المنصة : شحن الالعاب</b>
<b>💰  النوع  : $Type •</b>

<b>💲 السعر : $price ₽ </b>
<b>⏰ تاريخ الشراء : $DAY3 .</b>

<blockquote>✅ - الحالة : تم الاكتمال </blockquote>
",
'parse_mode'=>"html",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"↩️ - اضغط هنا لدخول الى البوت 🚀",'url'=>"t.me/$me"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$sim,
'text'=>"
⚜ - طلبية شحن جديدة:

💸 - رصيده: *$Balance*
🌀 - النوع: *$name*
♻️ - رقم الخدمة: *$ID*
🅿️ - أيدي العملية: *$order*
📮 - عنوان الشحن: *$urls*
🤸‍♂ - الحساب: *$EM*
💰 - السعر: *$price*
🎗 - الموقع: *$Location*
",
'parse_mode'=>"MarkDown",
]);
$points = file_get_contents("EMILS/$EM/points.txt");
$as = $points - $price;
file_put_contents("EMILS/$EM/points.txt",$as);
$yassenmy = rand(1234567,9999999);
$BUYGAMES[YAS_Nl][$yassenmy][idSend] = $idSend;
$BUYGAMES[YAS_Nl][$yassenmy][order] = $order;
$BUYGAMES[YAS_Nl][$yassenmy][price] = $price;
$BUYGAMES[YAS_Nl][$yassenmy][status] = 1;
$BUYGAMES[YAS_Nl][$yassenmy][number] = $number;
$BUYGAMES[YAS_Nl][$yassenmy][zero] = $zero;
$BUYGAMES[YAS_Nl][$yassenmy][add] = $add_site;
$BUYGAMES[YAS_Nl][$yassenmy][urls] = $urls;
$BUYGAMES[YAS_Nl][$yassenmy][name] = $name;
$BUYGAMES[YAS_Nl][$yassenmy][num] = $num;
$BUYGAMES[YAS_Nl][$yassenmy][type] = $type;
$BUYGAMES[YAS_Nl][$yassenmy]["chat-id"] = $id;
$BUYGAMES[YAS_Nl][$yassenmy][DAY] = $DAY;
file_put_contents("EMILS/$EM/YAS_Nl.json", json_encode($BUYGAMES,64|128|256));
$ORDERALL[$idSend][account] = $EM;
$ORDERALL[$idSend][order] = $yassenmy;
OrdAll($ORDERALL);
$STORAGEALL[YAS_Nl] +=1;
$STORAGEALL[ruble] +=$price;
StoAll($STORAGEALL);
unlink("data/id/$id/$array.txt");
unlink("data/id/$id/step.txt");
exit;
}
}else{
unlink("data/id/$id/$array.txt");
unlink("data/id/$id/step.txt");
exit;
}
}
if($exdata[0] == "Zb"){
$idSend=$exdata[1];
$emil=$ORDERALL[$idSend][account];
$order=$ORDERALL[$idSend][order];
$number = $BUYGAMES[YAS_Nl][$order][number];
$zero = $BUYGAMES[YAS_Nl][$order][zero];
$urls = $BUYGAMES[YAS_Nl][$order][urls];
$orders = $BUYGAMES[YAS_Nl][$order][order];
$num=$BUYGAMES[YAS_Nl][$order][num];
$type=$BUYGAMES[YAS_Nl][$order][type];
$name=$BUYGAMES[YAS_Nl][$order][name];
$price=$BUYGAMES[YAS_Nl][$order][price];
$add_site=$BUYGAMES[YAS_Nl][$order][add];
$Location=str_replace(["1","2","3"],["smmxstar.com","yemendamkom.com","Smmstone.com"],$add_site);
$ex=explode(".", $Location);
$api_key=file_get_contents("data/api/$ex[0].txt");
$api=json_decode(file_get_contents("https://$Location/api/v2?key=$api_key&action=status&order=$orders"),1);
$error = $api["error"];
$status = $api["status"];
$arstat = str_replace(["Pending","In progress","Completed","Partial","Processing","Canceled"],["قيد الانتظار","في تَقَدم","مكتمل","جزئي","يعالج","ألغيت"],$status);
if($status == "Completed"){
$status = 2;
}elseif($status == "Canceled"){
$status = -1;
}else{
$status = 1;
}
$start_count = $api["start_count"];
if($start_count == null){
$start_count=0;
}
$remains = $api["remains"];
if($error != null or $EM != $emil){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"☑️ - قم بتحديث حالة الطلب لاحقا🙋🏻
$error .
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
if($status == 2){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
✅ *- تم اكتمال طلب الخدمة بنجاح* ✔️

📡 *- المدخلات :* [$urls]
⚜ *- نوع الخدمة * : $name
🌀 *-حالة الطلب* : $arstat

⚜ *- نتمنى لكم تجربه ممتعة* ❤️‍🔥
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع 🔜",'callback_data'=>"EngYASNl0"]]
]
])
]);
$BUYGAMES[YAS_Nl][$order][status] = 3;
file_put_contents("EMILS/$EM/YAS_Nl.json", json_encode($BUYGAMES,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
if($status == -1){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
❌ *- تم الغاء طلب الخدمة المقدم*

📡 *- المدخلات :* [$urls]
⚜ *- نوع الخدم  * : $name
🌀 *- حالة الطلب* : $arstat
💰 *- قيمة العملية* : $price

⚜ *- تم ارجاء بقية الروبل تلقائي الى حسابك*
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- رجوع 🔜",'callback_data'=>"EngYASNl0"]]
]
])
]);
$BUYGAMES[YAS_Nl][$order][status] = -1;
file_put_contents("EMILS/$EM/YAS_Nl.json", json_encode($BUYGAMES,64|128|256));
$points = file_get_contents("EMILS/$EM/points.txt");
$as = $points + $price;
file_put_contents("EMILS/$EM/points.txt",$as);
unlink("data/id/$id/step.txt");
exit;
}
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"تم التحديث ✅",
'show_alert'=>false
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
✅ *- يتم الان تحديث طلبك*

📡 *- المدخلات :* [$urls]
⚜ *- نوع الخدمة * : $name
🌀 *- حالة الطلب* : $arstat
",
'parse_mode'=>"MarkDown",
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"- تحديث ✅",'callback_data'=>"Zb-$idSend"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={الأوامر الخاصة بالأدمن}==========#
if($id == $sudo){
if($exdata[0] == "Splash_playing"){
$array = $exdata[1];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- اهلا بك مطوري في قسم الخدمات الخاص بك
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🛒 • اضافة خدمه العاب • 🛒",'callback_data'=>"YAS_Nladd"]],
[['text'=>"⛔️ • حذف خدمة العاب • ⛔️",'callback_data'=>"delYAS_Nl"]],
[['text'=>'➕ رفع API لموقع معين','callback_data'=>'addspit']],
[['text'=>'✖️ حذف API لموقع معين','callback_data'=>'delspitapi']],
[['text'=>'🔗 • لوحه تحكم قسم الارقام • 🔗','callback_data'=>'c']]
]
])
]);
unlink("data/id/$id/$array.txt");
unlink("data/id/$id/step.txt");
exit;
}
#=========={المواقع الخدمات}==========#
if($data == "YAS_Nladd"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- قم ب إختار المورد الذي تود إضافة السيرفر منة إلى البوت
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'smmxstar.com','callback_data'=>'Js-1']],
[['text'=>'yemendamkom.com','callback_data'=>'Js-2']],
[['text'=>'Smmstone.com','callback_data'=>'Js-3']],
[['text'=>'- رجوع 🔜','callback_data'=>'Splash_playing']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#====================#
if($exdata[0] == "Js"){
$add = $exdata[1];
$site=str_replace(["1","2","3"],["smmxstar.com","yemendamkom.com","Smmstone.com"],$add);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- الآن قم ب إختيار القسم لاضافة الخدمه  من المورد $site
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"• قسم الالعاب •",'callback_data'=>"Ks-$add-1"]],
[['text'=>'- رجوع 🔜','callback_data'=>'YAS_Nladd']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={اقسام الخدمات}==========#
if($exdata[0] == "Ks"){
$add = $exdata[1];
$num = $exdata[2];
$site=str_replace(["1","2","3"],["smmxstar.com","yemendamkom.com","Smmstone.com"],$add);
$APP = str_replace(["1"],["🕹 شحن الالعاب ⭐️ "],$num);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- الآن قم ب إختيار القسم الذي تريدة

- القسم: $APP
- الموقع: $site
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$GAMEpubg",'callback_data'=>"Gs-$add-$num-1"]],
[['text'=>"$GAMEfreefire",'callback_data'=>"Gs-$add-$num-2"]],
[['text'=>"$GAMEylalodo",'callback_data'=>"Gs-$add-$num-3"]],
[['text'=>"$GAMEjoaker",'callback_data'=>"Gs-$add-$num-4"]],
[['text'=>" $GAMEclashofclanes",'callback_data'=>"Gs-$add-$num-5"]],
[['text'=>'- رجوع 🔜','callback_data'=>'YAS_Nladd']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={ID}==========#
if($exdata[0] == "Gs"){
$add = $exdata[1];
$num = $exdata[2];
$type = $exdata[3];
$site=str_replace(["1","2","3"],["smmxstar.com","yemendamkom.com","Smmstone.com"],$add);
$APP = str_replace(["1"],["🕹 شحن الالعاب ⭐️ "],$num);
function complexReplace($input, $searchArray, $replaceArray) {
    $result = $input;
    foreach ($searchArray as $key => $searchValue) {
        $result = preg_replace('/' . preg_quote($searchValue, '/') . '/', $replaceArray[$key], $result);
    }
    return $result;
}
$searchTerms = ["1", "2", "3", "4", "5"];
$replaceTerms = ["$GAMEpubg", "$GAMEfreefire", "$GAMEylalodo", "$GAMEjoaker", "$GAMEclashofclanes"];
$Type = complexReplace($type, $searchTerms, $replaceTerms);
if (isset($Type) && !empty($Type)) {
    $finalResult = strtoupper(trim($Type)); 
} else {
    $finalResult = "هذه الخدمة ليست متاحة بعد";
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- القسم: $APP
- الموقع: $site
- النوع: $finalResult

⬇️ *- أرسل أيدي القسم الذي تود إضافتة*
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- رجوع 🔜','callback_data'=>"Js-$add"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","Jr1|$add|$num|$type");
exit;
}
if($text && $text != '/start' && $exstep[0] == 'Jr1'){
$add = $exstep[1];
$num = $exstep[2];
$type = $exstep[3];
$ID = $text;
$site=str_replace(["1","2","3"],["smmxstar.com","yemendamkom.com","Smmstone.com"],$add);
function complexReplace($input, $searchArray, $replaceArray) {
    $result = $input;
    foreach ($searchArray as $key => $searchValue) {
        $result = preg_replace('/' . preg_quote($searchValue, '/') . '/', $replaceArray[$key], $result);
    }
    return $result;
}
$searchTerms = ["1", "2", "3", "4", "5"];
$replaceTerms = ["$GAMEpubg", "$GAMEfreefire", "$GAMEylalodo", "$GAMEjoaker", "$GAMEclashofclanes"];
$Type = complexReplace($type, $searchTerms, $replaceTerms);
if (isset($Type) && !empty($Type)) {
    $finalResult = strtoupper(trim($Type)); 
} else {
    $finalResult = "هذه الخدمة ليست متاحة بعد";
}
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
🌐 - الموقع: *$site*
🛎 - النوع: *$Type*
🅿️ - أيدي القسم: *$ID*

⬇️ *- أرسل ب الشكل التالي:
1⃣ - إسم الخدمه. ( ٦٠ شده ببجي موبايل )..
2⃣ - الفئة( ٦٠ شده / ١٢٠ جوهرة ).
3⃣ - النوع ( العاب - تطبيقات ).
4⃣ - نوع الشحن ( يدوي - تلقائي)
5⃣ - المطلوب من العميل*(ايدي - المعرف).
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>'- رجوع 🔜','callback_data'=>"Js-$add"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","Jr2|$add|$num|$type|$ID");
exit;
}
if($text && $text != '/start' && $exstep[0] == 'Jr2'){
$add = $exstep[1];
$num = $exstep[2];
$type = $exstep[3];
$ID = $exstep[4];
$name = $extext[0];
$quality = $extext[1];
$security = $extext[2];
$get_off = $extext[3];
$explained = $extext[4];
$taxt="$name|$quality|$security|$get_off|$explained";
$site=str_replace(["1","2","3"],["smmxstar.com","yemendamkom.com","Smmstone.com"],$add);
function complexReplace($input, $searchArray, $replaceArray) {
    $result = $input;
    foreach ($searchArray as $key => $searchValue) {
        $result = preg_replace('/' . preg_quote($searchValue, '/') . '/', $replaceArray[$key], $result);
    }
    return $result;
}
$searchTerms = ["1", "2", "3", "4", "5"];
$replaceTerms = ["$GAMEpubg", "$GAMEfreefire", "$GAMEylalodo", "$GAMEjoaker", "$GAMEclashofclanes"];
$Type = complexReplace($type, $searchTerms, $replaceTerms);
if (isset($Type) && !empty($Type)) {
    $finalResult = strtoupper(trim($Type)); 
} else {
    $finalResult = "هذه الخدمة ليست متاحة بعد";
}
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
🌐 - الموقع: *$site*
🛎 - النوع: *$Type*
🅿️ - أيدي القسم: *$ID*
💠 - إسم القسم: *$name*

⬇️* - أرسل ب الشكل التالي:
1⃣ - السعر.
2⃣ - - الفئة( ٦٠ شده / ١٢٠ جوهرة ).
3⃣ - الوقت المقدر للشحن. (10 دقائق)...
4⃣ - حد الأدنى.
5⃣ - حد الأعلى.*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>'- رجوع 🔜','callback_data'=>"Js-$add"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","Jr3|$add|$num|$type|$ID|$taxt");
exit;
}
if($text && $text != '/start' && $exstep[0] == 'Jr3'){
$add = $exstep[1];
$num = $exstep[2];
$type = $exstep[3];
$ID = $exstep[4];
$name = $exstep[5];
$quality = $exstep[6];
$security = $exstep[7];
$get_off = $exstep[8];
$explained = $exstep[9];
$price = $extext[0];
$speed = $extext[1];
$start_time = $extext[2];
$minimum = $extext[3];
$maximum = $extext[4];
$taxt="$add|$num|$type|$ID|$name|$quality|$security|$get_off|$explained|$price|$speed|$start_time|$minimum|$maximum";
$array = substr(str_shuffle("0123456789"),0-10);
$site=str_replace(["1","2","3"],["smmxstar.com","yemendamkom.com","Smmstone.com"],$add);
function complexReplace($input, $searchArray, $replaceArray) {
    $result = $input;
    foreach ($searchArray as $key => $searchValue) {
        $result = preg_replace('/' . preg_quote($searchValue, '/') . '/', $replaceArray[$key], $result);
    }
    return $result;
}
$searchTerms = ["1", "2", "3", "4", "5"];
$replaceTerms = ["$GAMEpubg", "$GAMEfreefire", "$GAMEylalodo", "$GAMEjoaker", "$GAMEclashofclanes"];
$Type = complexReplace($type, $searchTerms, $replaceTerms);
if (isset($Type) && !empty($Type)) {
    $finalResult = strtoupper(trim($Type)); 
} else {
    $finalResult = "هذه الخدمة ليست متاحة بعد";
}
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
🌐 - الموقع: *$site*
🛎 - النوع: *$Type*
🅿️ - أيدي القسم: *$ID*

1⃣ - إسم القسم: *$name*
2⃣ - السعر: *$price*
3⃣ - نوع : *$quality*
4⃣ - فئة : *$speed*
5⃣ - نوع الشحن: *$get_off*
➖️➖️➖️✅️➖️➖️➖️
7⃣ - وقت البدء: *$start_time*
8⃣ - حد الأدنى: *$minimum*
9⃣ - حد الأعلى: *$maximum*
🔟 - المطلوب من الزبون عند الشحن: *$explained*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>'☑️ - إضافة الخدمة','callback_data'=>"Kr-$array"]],
[['text'=>'- رجوع 🔜','callback_data'=>"Splash_playing-$array"]]
]
])
]);
file_put_contents("data/id/$id/$array.txt","$taxt");
unlink("data/id/$id/step.txt");
exit;
}
if($exdata[0] == "Kr"){
$array = $exdata[1];
$exp=file_get_contents("data/id/$id/$array.txt");
$exp=explode("|", $exp);
$add = $exp[0];
$num = $exp[1];
$type = $exp[2];
$ID = $exp[3];
$name = $exp[4];
$quality = $exp[5];
$security = $exp[6];
$get_off = $exp[7];
$explained = $exp[8];
$price = $exp[9];
$speed = $exp[10];
$start_time = $exp[11];
$minimum = $exp[12];
$maximum = $exp[13];
$site=str_replace(["1","2","3"],["smmxstar.com","yemendamkom.com","Smmstone.com"],$add);
function complexReplace($input, $searchArray, $replaceArray) {
    $result = $input;
    foreach ($searchArray as $key => $searchValue) {
        $result = preg_replace('/' . preg_quote($searchValue, '/') . '/', $replaceArray[$key], $result);
    }
    return $result;
}
$searchTerms = ["1", "2", "3", "4", "5"];
$replaceTerms = ["$GAMEpubg", "$GAMEfreefire", "$GAMEylalodo", "$GAMEjoaker", "$GAMEclashofclanes"];
$Type = complexReplace($type, $searchTerms, $replaceTerms);
if (isset($Type) && !empty($Type)) {
    $finalResult = strtoupper(trim($Type)); 
} else {
    $finalResult = "هذه الخدمة ليست متاحة بعد";
}
$code = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"),0-14);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ - تم إضافة قسم الخدمة للأيدي *$ID*
🌐 - الموقع: *$site*
🛎 - النوع: *$Type*
💠 - الإسم: *$name*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
[['text'=>'- رجوع 🔜','callback_data'=>"Splash_playing-$array"]]
]
])
]);
$EngYASNl["Yassen@YAS_Nl"][$code][add] = $add;
$EngYASNl["Yassen@YAS_Nl"][$code][num] = $num;
$EngYASNl["Yassen@YAS_Nl"][$code][type] = $type;
$EngYASNl["Yassen@YAS_Nl"][$code][ID] = $ID;
$EngYASNl["Yassen@YAS_Nl"][$code][name] = $name;
$EngYASNl["Yassen@YAS_Nl"][$code][quality] = $quality;
$EngYASNl["Yassen@YAS_Nl"][$code][security] = $security;
$EngYASNl["Yassen@YAS_Nl"][$code][get_off] = $get_off;
$EngYASNl["Yassen@YAS_Nl"][$code][explained] = $explained;
$EngYASNl["Yassen@YAS_Nl"][$code][Type] = $Type;
$EngYASNl["Yassen@YAS_Nl"][$code][price] = $price;
$EngYASNl["Yassen@YAS_Nl"][$code][speed] = $speed;
$EngYASNl["Yassen@YAS_Nl"][$code][start_time] = $start_time;
$EngYASNl["Yassen@YAS_Nl"][$code][minimum] = $minimum;
$EngYASNl["Yassen@YAS_Nl"][$code][maximum] = $maximum;
file_put_contents('data/EngYASNl.json', json_encode($EngYASNl,64|128|256));
unlink("data/id/$id/$array.txt");
unlink("data/id/$id/step.txt");
exit;
}
#=========={حذف الخدمات}==========#
if($data == "delYAS_Nl"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- الآن قم ب إختيار القسم الذي تود حذف السيرفر منه
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"• قسم الالعاب •",'callback_data'=>"Td-1"]],
[['text'=>'- رجوع 🔜','callback_data'=>'Splash_playing']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}

if($exdata[0] == "Td"){
$num = $exdata[1];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- أختر القسم الذي تود حذف منه سيرفر من تطبيق 
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$GAMEpubg",'callback_data'=>"Gd-$num-1"]],
[['text'=>"$GAMEfreefire",'callback_data'=>"Gd-$num-2"]],
[['text'=>"$GAMEylalodo",'callback_data'=>"Gd-$num-3"]],
[['text'=>"$GAMEjoaker",'callback_data'=>"Gd-$num-4"]],
[['text'=>"$GAMEclashofclanes",'callback_data'=>"Gd-$num-5"]],
[['text'=>'- رجوع 🔜','callback_data'=>'delYAS_Nl']]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={ID}==========#
if($exdata[0] == "Gd"){
$nums = $exdata[1];
$type = $exdata[2];
$key     = [];
$key['inline_keyboard'][] = [['text'=>'✅️ الخدمه مع سعرها ⬇️','callback_data'=>'no']];
foreach($EngYASNl["Yassen@YAS_Nl"] as $zero=>$num){
if($num[num] == $nums and $num[type] == $type){
$name=$num[name];
$price=$num[price];
$key['inline_keyboard'][] = [['text'=>"$name [$price ₽]",'callback_data'=>"Delet-$zero"]];
}
}
$key['inline_keyboard'][] = [['text'=>'- رجوع 🔜','callback_data'=>"delYAS_Nl"]];
$keyboad      = json_encode($key);
if($name == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"- لم تتم الإضافة لهذا السيرفر بعد",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*القسم : 🕹 شحن الالعاب ⭐️ *

🎬 *- يرجى إختيار نوع الخدمة الذي تود حذفة* 👇
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
if($exdata[0] == "Delet"){
$zero = $exdata[1];
$ID = $EngYASNl["Yassen@YAS_Nl"][$zero][ID];
$name = $EngYASNl["Yassen@YAS_Nl"][$zero][name];
$num = $EngYASNl["Yassen@YAS_Nl"][$zero][num];
$add_site=$EngYASNl["Yassen@YAS_Nl"][$zero][add];
$site=str_replace(["1","2","3"],["smmxstar.com","yemendamkom.com","Smmstone.com"],$add_site);
if($EngYASNl["Yassen@YAS_Nl"][$zero] == null){
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
- تم حذف السيرفر من قسم الخدماتٍ بنجاح ✅

- أيدي السيرفر: $ID
- إسم السيرفر: $name
- الموقع: $site
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- رجوع 🔜','callback_data'=>'delYAS_Nl']]
]
])
]);
unset($EngYASNl["Yassen@YAS_Nl"][$zero]);
file_put_contents('data/EngYASNl.json', json_encode($EngYASNl,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
} 