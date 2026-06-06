<?php

# ملف لتخزين رقم الصندوق الصحيح @E_O_E1
$treasure_file = "data/treasure_boxes.txt";

# إنشاء أو تحديث الصندوق الصحيح يومي @E_O_E1
if(!file_exists($treasure_file) || (time() - filemtime($treasure_file)) > 86400) {
$correct_boxes = [rand(1, 9), rand(1, 9), rand(1, 9)]; # تحديد 3 صناديق صحيحة عشوائية @E_O_E1
file_put_contents($treasure_file, implode(',', $correct_boxes));
}

# استرداد الصناديق الصحيحة @E_O_E1
$correct_boxes = explode(',', file_get_contents($treasure_file));

# عدد المحاولات المسموحة @E_O_E1
$max_attempts = 1;

# إذا دخل المستخدم على قسم "البحث عن الكنز" @E_O_E1
if ($data == "GuidanceDepartment") {
bot('EditMessageText', [
'chat_id' => $chat_id,
'message_id' => $message_id,
'text' =>"
<b>مرحبا بك عزيزي : 💙 $first 💙</b>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱</b>

<blockquote>✅ هذا قسم البحث عن الكنز •</blockquote>
<blockquote>🎉 - سوف تحصل علي روبل هدية عن اختيار المربع الصحيح •</blockquote>

<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱</b>
<b>⬇️ اختار من الزراير الذي في الاسفل ⬇️</b> 
",
'parse_mode' => "html",
'reply_markup' => json_encode([
'inline_keyboard' => [
[['text' => '💰 جرب حظك 💰', 'callback_data' => "Yourluck"]],
[['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}

# إذا دخل المستخدم على "جرب حظك" @E_O_E1
if($data == "Yourluck") {
bot('EditMessageText', [
'chat_id' => $chat_id,
'message_id' => $message_id,
'text' =>"
<blockquote>✅ اختر مربعًا للكشف عن الكنز. لديك 3 محاولات متبقية •</blockquote>
",
'parse_mode' => "html",
'reply_markup' => json_encode([
'inline_keyboard' => [
[['text' => '💰', 'callback_data' => "KenzerNo1"], ['text' => '💰', 'callback_data' => "KenzerNo2"], ['text' => '💰', 'callback_data' => "KenzerNo3"]],
[['text' => '💰', 'callback_data' => "KenzerNo4"], ['text' => '💰', 'callback_data' => "KenzerNo5"], ['text' => '💰', 'callback_data' => "KenzerNo6"]],
[['text' => '💰', 'callback_data' => "KenzerNo7"], ['text' => '💰', 'callback_data' => "KenzerNo8"], ['text' => '💰', 'callback_data' => "KenzerNo9"]],
]
])
]);
file_put_contents("data/id/$id/tries.txt", $max_attempts); # بدء المحاولات @E_O_E1
}

# إذا اختار المستخدم صندوقًا @E_O_E1
if (strpos($data, "KenzerNo") === 0) {
    $chosen_box = intval(str_replace("KenzerNo", "", $data));
    $tries_file = "data/id/$id/tries.txt";

    # استرداد المحاولات المتبقية @E_O_E1
    $remaining_tries = intval(file_get_contents($tries_file));

    if ($remaining_tries > 0) {
        $remaining_tries--;
        file_put_contents($tries_file, $remaining_tries);

        # التحقق من صحة الصندوق @E_O_E1
        if (in_array($chosen_box, $correct_boxes)) {
            $assignru = 0.001; # قيمة الجائزة
            
            # تحديث النقاط والرصيد
            $points_file = "EMILS/$chat_id/points.txt";
            $rubles_file = "EMILS/$chat_id/rubles.txt";
            
            # قراءة الرصيد الحالي
            $points = file_exists($points_file) ? floatval(file_get_contents($points_file)) : 0;
            $rubles = file_exists($rubles_file) ? floatval(file_get_contents($rubles_file)) : 0;

            # تحديث الأرصدة
            $new_points = $points + $assignru;
            $new_rubles = $rubles + $assignru;

            # حفظ الأرصدة الجديدة
            file_put_contents($points_file, $new_points);
            file_put_contents($rubles_file, $new_rubles);

            # إرسال رسالة النجاح
            bot('SendMessage', [
                'chat_id' => $chat_id,
                'text' => "🎉 مبروك! اخترت صندوقًا صحيحًا! تمت إضافة $assignru روبل لحسابك.",
                'parse_mode' => "html"
            ]);
        } else {
            bot('SendMessage', [
                'chat_id' => $chat_id,
                'text' => "😞 لم تختر صندوقًا صحيحًا. لديك $remaining_tries محاولات متبقية.",
                'parse_mode' => "html"
            ]);
        }

        # عند انتهاء المحاولات @E_O_E1
        if ($remaining_tries == 0) {
            bot('SendMessage', [
                'chat_id' => $chat_id,
                'text' => "✅ انتهت محاولاتك لهذا اليوم. حاول مرة أخرى غدًا.",
                'parse_mode' => "html"
            ]);
        }
    } else {
        bot('SendMessage', [
            'chat_id' => $chat_id,
            'text' => "🚫 انتهت محاولاتك لهذا اليوم. حاول مرة أخرى غدًا.",
            'parse_mode' => "html"
        ]);
    }
}