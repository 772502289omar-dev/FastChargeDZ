<?php


if($data == "yasein"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<blockquote>مرحبا بك عزيزي : 💙 $first 💙 </blockquote>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>

<b>هذا قسم لتوثيق منصات اختر الخدمه التي تريدها ✅</b>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"توثيق تيليجرام - TELEGRAM",'callback_data'=>"hahss99"]],
[['text'=>"توثيق تلوكولر - TRUECALLER",'callback_data'=>"jehsgsvw5"]],
[['text'=>"توثيق فيس بوك - Facebook",'callback_data'=>"nshshd75"]],
[['text'=>"توثيق انستا - INSTA",'callback_data'=>"wiysye826"]],
[['text'=>'- رجوع 🔜','callback_data'=>"kadamat"],['text'=>'- الصفحة الرئيسية 🔙','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}
if($data == "hahss99"){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>'لم يتم اضافه اي سيرفرات',
'show_alert'=>true
]);
}
#
if($data == "jehsgsvw5"){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>'لم يتم اضافه اي سيرفرات',
'show_alert'=>true
]);
}
#
if($data == "nshshd75"){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>'لم يتم اضافه اي سيرفرات',
'show_alert'=>true
]);
}
#
if($data == "wiysye826"){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>'لم يتم اضافه اي سيرفرات',
'show_alert'=>true
]);
}
#