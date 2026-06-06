<?php
@ini_set('default_socket_timeout', 8); // ⚡ حد أقصى 8 ثواني

// ===================================
//  دوال قراءة/كتابة JSON (إصلاح: كانت غير معرفة وتسبب توقف البوت)
// ===================================
if (!function_exists('jRead')) {
    function jRead($path, $assoc = true) {
        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') { return []; }
        $data = json_decode($raw, $assoc);
        return ($data === null) ? [] : $data;
    }
}
if (!function_exists('jWrite')) {
    function jWrite($path, $data) {
        $dir = dirname($path);
        if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
        return @file_put_contents($path, json_encode($data, 64 | 128 | 256));
    }
}
// إصلاح: inviteGate كانت تُستدعى دون تعريف وتوقف قسم الخدمات المجانية.
// هذا تعريف افتراضي يسمح بالمرور؛ إذا توفّر تعريف حقيقي في ملف آخر فلن يتم استبداله.
if (!function_exists('inviteGate')) {
    function inviteGate($id, $chat_id = null, $message_id = null) {
        return true;
    }
}

// ===================================
//  إعدادات القنوات
// ===================================
$sim = -1003990109653; // ايدي قناة التفعيلات
$PAY = -1003914667696; // ايدي قناة الاشعارات

// ===================================
//  تحقق صلاحيات الأدمن (رئيسي + مضافين)
// ===================================
$_admins_list = jRead('data/id/admin.json');
$_admins_list = is_array($_admins_list) ? $_admins_list : [];
$is_admin = ($id == $iDadmin || in_array($id, $_admins_list));

// ===================================
//  تحميل بيانات الرشق
// ===================================
$BUYSSPIT = !empty($EM) ? (jRead("EMILS/$EM/spit.json", true) ?: []) : [];

// ===================================
//  دوال مساعدة مركزية
// ===================================

/**
 * نظام Anti-Spam الاحترافي
 *
 * يمنع:
 *  - تكرار نفس الخدمة قبل $cooldown ثانية
 *  - أكثر من $per_minute طلبات في الدقيقة
 *  - أكثر من $per_hour طلبات في الساعة
 *
 * التخزين: data/rate_limit.json (ملف واحد خفيف)
 *
 * @param  int|string $user_id    معرف المستخدم
 * @param  string     $action     نوع العملية (مثال: "order")
 * @param  int        $cooldown   ثواني الانتظار بين كل طلبين
 * @param  int        $per_minute أقصى عدد طلبات في الدقيقة
 * @param  int        $per_hour   أقصى عدد طلبات في الساعة
 * @return array ['allowed' => bool, 'reason' => string]
 */
function antiSpamPro($user_id, $action, $cooldown = 90, $per_minute = 3, $per_hour = 20) {
    $file = "data/rate_limit.json";
    $now  = time();
    $key  = (string)$user_id . "_" . $action;

    // فتح الملف مع قفل حصري لمنع التعارض
    $fp = @fopen($file, 'c+');
    if (!$fp) {
        return ['allowed' => true, 'reason' => ''];
    }
    flock($fp, LOCK_EX);

    $size = filesize($file);
    $raw  = ($size > 0) ? fread($fp, $size) : '{}';
    $data = json_decode($raw, true);
    if (!is_array($data)) $data = [];

    $entry = $data[$key] ?? ['last' => 0, 'times' => []];
    if (!is_array($entry['times'])) $entry['times'] = [];

    // تنظيف السجلات الأقدم من ساعة (يُخفف الحجم تلقائياً)
    $entry['times'] = array_values(array_filter(
        $entry['times'],
        fn($t) => ($now - $t) < 3600
    ));

    // ── فحص 1: Cooldown بين طلبين ──────────────────────────
    if ($entry['last'] > 0 && ($now - $entry['last']) < $cooldown) {
        $remaining = $cooldown - ($now - $entry['last']);
        flock($fp, LOCK_UN);
        fclose($fp);
        return [
            'allowed' => false,
            'reason'  => "⏳ يرجى الانتظار *{$remaining}* ثانية قبل تقديم طلب جديد.",
        ];
    }

    // ── فحص 2: حد الدقيقة ──────────────────────────────────
    $inLastMinute = count(array_filter($entry['times'], fn($t) => ($now - $t) < 60));
    if ($inLastMinute >= $per_minute) {
        flock($fp, LOCK_UN);
        fclose($fp);
        return [
            'allowed' => false,
            'reason'  => "🚫 تجاوزت الحد المسموح ({$per_minute} طلبات في الدقيقة). حاول بعد قليل.",
        ];
    }

    // ── فحص 3: حد الساعة ───────────────────────────────────
    if (count($entry['times']) >= $per_hour) {
        flock($fp, LOCK_UN);
        fclose($fp);
        return [
            'allowed' => false,
            'reason'  => "🔒 تجاوزت الحد المسموح ({$per_hour} طلباً في الساعة). حاول لاحقاً.",
        ];
    }

    // ── تسجيل الطلب ────────────────────────────────────────
    $entry['last']    = $now;
    $entry['times'][] = $now;
    $data[$key]        = $entry;

    // تنظيف المستخدمين غير النشطين (أكثر من ساعة) لإبقاء الملف خفيفاً
    foreach ($data as $k => $v) {
        if (empty($v['times']) || ($now - max($v['times'])) > 3600) {
            unset($data[$k]);
        }
    }

    // حفظ البيانات
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE));
    flock($fp, LOCK_UN);
    fclose($fp);

    return ['allowed' => true, 'reason' => ''];
}

/**
 * اسم التطبيق من رقمه (مركزي - يُغني عن str_replace المتكررة)
 */
function getAppName($num) {
    $names = [
        "0"  => "سناب شات",
        "1"  => "تيليجرام",
        "2"  => "انستجرام",
        "3"  => "تيك توك",
        "4"  => "فيسبوك",
        "5"  => "تويتر",
        "6"  => "وتساب",
        "7"  => "يوتيوب",
        "8"  => "خدمات مجانية",
        "9"  => "سناب شات",
        "10" => "كواي",
    ];
    return $names[(string)$num] ?? "منصة";
}

/**
 * تحويل رقم الموقع إلى اسمه - يدعم المواقع الافتراضية والمضافة
 */
function getSiteByNum($add_site) {
    $defaults = [
        "1" => "smmtigers.com",
        "2" => "smmxstar.com",
        "3" => "smmparty.com",
        "4" => "fast70.com",
    ];
    if (isset($defaults[(string)$add_site])) {
        return $defaults[(string)$add_site];
    }
    $custom = jRead("data/rashq_sites.json") ?? [];
    return $custom[(string)$add_site] ?? null; // null = موقع غير موجود → سيظهر خطأ واضح
}

/**
 * تسمية الرابط المناسبة حسب القسم والنوع
 * (تُغني عن if/elseif/str_replace المتكررة في كل مكان)
 */
function getLinkLabel($num, $type) {
    $map = [
        1  => ["1" => "القناة",  "2" => "الرابط", "3" => "الرابط"],
        2  => ["1" => "الحساب", "2" => "الرابط", "3" => "الرابط"],
        3  => ["1" => "الحساب", "2" => "الرابط", "3" => "الرابط"],
        4  => ["1" => "القناة",  "2" => "الرابط", "3" => "الرابط"],
        5  => ["1" => "الحساب", "2" => "الرابط", "3" => "الرابط"],
        6  => ["1" => "الرابط", "2" => "الرابط", "3" => "الرابط", "4" => "الرابط"],
        7  => ["1" => "الرابط", "2" => "الرابط", "3" => "الرابط"],
        8  => ["1" => "الرابط", "2" => "الرابط", "3" => "الرابط"],
        9  => ["1" => "الرابط", "2" => "الرابط", "3" => "الرابط"],
        10 => ["1" => "الرابط", "2" => "الرابط", "3" => "الرابط"],
    ];
    $labels = $map[(int)$num] ?? ["1" => "الرابط", "2" => "الرابط", "3" => "الرابط", "4" => "الرابط"];
    return $labels[(string)$type] ?? "الرابط";
}

/**
 * رسالة الطلب المُقبل للخدمة (مركزية - مكررة في الكود الأصلي 3 مرات)
 */
function sendOrderConfirmMsg($chat_id, $message_id, $x, $urls, $number, $price, $idSend, $name = '', $smm_order = '') {
    $confirm_text  = "✅ *تم استلام طلبك بنجاح!*\n\n";
    $confirm_text .= "🛍 الخدمة ┊ {$name}\n";
    $confirm_text .= "📦 الكمية ┊ {$number}\n";
    $confirm_text .= "💸 السعر الكلي ┊ ₽ {$price}\n";
    $confirm_text .= "🧾 رقم الطلب ┊ {$idSend}\n";
    $confirm_text .= "🆔 معرف التنفيذ ┊ {$smm_order}\n";
    $confirm_text .= "🔗 الرابط ┊ {$urls}\n\n";
    $confirm_text .= "📊 *حالة الطلب*\n";
    $confirm_text .= "• المطلوب ┊ {$number}\n";
    $confirm_text .= "• المكتمل ┊ 0\n";
    $confirm_text .= "• المتبقي ┊ {$number}\n";
    $confirm_text .= "• الحالة ┊ في الانتظار ⏳\n\n";
    $confirm_text .= "_اضغط زر ♻️ التحديث بالأسفل لمتابعة حالة طلبك._";

    bot('EditMessageText', [
        'chat_id'                  => $chat_id,
        'message_id'               => $message_id,
        'text'                     => $confirm_text,
        'parse_mode'               => "MarkDown",
        'disable_web_page_preview' => true,
        'reply_markup'             => json_encode([
            'inline_keyboard' => [
                [['text' => '- تحديث ✅', 'callback_data' => "Bz-$idSend", 'style' => 'primary']],
            ],
        ]),
    ]);
}

/**
 * إرسال إشعار قناة التفعيلات (مركزية - مكررة 3 مرات)
 * $seq = رقم الطلب التسلسلي (يُمرَّر من المستدعي)
 */
function notifySimChannel($sim, $name, $ID, $APP, $Type, $number, $nid, $price, $idurls, $me, $seq = null, $zero = '', $add_site = '') {
    if ($seq === null) {
        $seq = (int)(@file_get_contents("data/pay_order_seq.txt") ?: 0);
    }
    bot('sendMessage', [
        'chat_id'                  => $sim,
        'text'                     => "
<b><blockquote>✅  تم اكتمال طلب رشق جديد  ✅</blockquote>

📌 : الخدمة : $name
🌍 : رقم الطلب : $seq
📲 : المنصة : $APP
🛍️︙ النوع : $Type
👥︙ العدد : $number
🕹︙ العميل : $nid
💰︙ السعر : ₽ $price
🖇︙ الرابط : <tg-spoiler>$idurls</tg-spoiler>

<blockquote>✅ - الحالة : تم الاكتمال ✅</blockquote></b>",
        'parse_mode'               => "html",
        'disable_web_page_preview' => true,
        'reply_markup'             => json_encode([
            'inline_keyboard' => [[['text' => "↩️ - طلب نفس الرفع من السيرفر 🚀", 'url' => "t.me/$me?start=SPIT_{$zero}_{$add_site}_{$number}"]]],
        ]),
    ]);
}

/**
 * إرسال إشعار قناة الاشعارات (مركزية - مكررة 3 مرات)
 */
function notifyPayChannel($PAY, $APP, $number, $Balance, $name, $seq_num, $order, $urls, $EM, $price, $Location) {
    bot('sendMessage', [
        'chat_id'    => $PAY,
        'text'       => "
⚜ - طلبية رشق جديدة:

📲 - لتطبيق : *$APP*
☑️ - الكمية المطلوبة : *$number*
💸 - رصيده : *$Balance*
🌀 - النوع : *$name*
♻️ - رقم الطلب : *$seq_num*
🅿️ - أيدي العملية : *$order*
📮 - الرابط : *$urls*
🤸‍♂ - الحساب : *$EM*
💰 - السعر : *₽ $price*
🎗 - الموقع : *$Location*
",
        'parse_mode' => "MarkDown",
    ]);
}

// ===================================
//  القائمة الرئيسية للرشق
// ===================================

// دالة مساعدة: تضيف icon_custom_emoji_id لزر بناءً على مفتاح الملصق
function spitBtn($text, $cb, $style, $ekey) {
    global $buttonEmojis;
    $b = ['text' => $text, 'callback_data' => $cb, 'style' => $style];
    if (!empty($buttonEmojis[$ekey])) {
        $b['icon_custom_emoji_id'] = $buttonEmojis[$ekey];
    }
    return $b;
}

function StoAll($array) {
    file_put_contents('data/storage.json', json_encode($array, 64|128|256), LOCK_EX);
}

// ===================================
//  تحميل الملفات المشتركة مرة واحدة
// ===================================
$_sub_types_shared  = jRead("data/sub_types.json") ?? [];
$_rashq_secs_shared = jRead("data/rashq_sections.json") ?? [];

if ($data == "spit") {
    $spit_menu_btns = [
        [spitBtn('• تيلجرام - 𝚃𝙴𝙻𝙴𝙶𝚁𝙰𝙼 • ',     'Telegram',  'primary', 'spit_telegram')],
        [spitBtn('• انستغرام - 𝙸𝙽𝚂𝚃𝙰𝙶𝚁𝙰𝙼 •',    'Instagram', 'primary', 'spit_instagram')],
        [spitBtn('• تيك توك - 𝚃𝙸𝙺 𝚃𝙾𝙺 •',           'TikTok',    'primary', 'spit_tiktok')],
        [spitBtn('• فيسبوك - 𝙵𝙰𝙲𝙴𝙱𝙾𝙾𝙺 ',         'FaceBook',  'primary', 'spit_facebook')],
        [spitBtn('• تويتر - 𝚃𝚆𝙸𝚃𝚃𝙴𝚁 • ',           'Twitter',   'primary', 'spit_twitter')],
        [spitBtn('• وتساب - 𝚆𝙷𝙰𝚃𝚂𝙰𝙿𝙿 • ',           'Whats',     'primary', 'spit_whatsapp')],
        [spitBtn('• يوتيوب - 𝚈𝙾𝚄𝚃𝚄𝙱𝙴 • ',           'YouTube',   'primary', 'spit_youtube')],
        [spitBtn('• كواي •',                           'Kwai',      'primary', 'spit_kwai')],
        [spitBtn('• سناب شات •',                       'snapchat',  'primary', 'spit_snapchat')],
        [spitBtn('• خدمات مجانيه - 𝙵𝚛𝚎𝚎 𝚜𝚎𝚛𝚟𝚒𝚌𝚎𝚜 •', 'f',         'success', 'spit_free')],
    ];

    // إضافة الأقسام المخصصة تلقائياً إذا كانت تحتوي خدمات
    $_custom_secs = $_rashq_secs_shared;
    foreach ($_custom_secs as $_sc) {
        $_has = false;
        foreach ($increase["idplus"] as $_sv) {
            if ($_sv['num'] == $_sc['num']) { $_has = true; break; }
        }
        if ($_has) {
            $spit_menu_btns[] = [['text' => "{$_sc['emoji']} {$_sc['name']}", 'callback_data' => "custom_sec-{$_sc['num']}", 'style' => 'primary']];
        }
    }
    $spit_menu_btns[] = [['text' => '- عوده 🔙', 'callback_data' => 'Throwing_games', 'style' => 'danger']];

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
<b>🥷 خدمة الرفع المتطورة لجميع منصات التواصل الاجتماعي ✓</b>
🌵 —————————•
<u>🦥  تساعدك في زيـادة متابعين وتفاعلات صفحتك •
🐙 توفر خدمات متـابعين وإعجابات ومشاهدات •
🐣 اسعار مناسبة تتفاوت من حيث الجودة والسرعة •
🦕 يـمكنك شحن الالـعاب وشـراء عروضـها •</u>
🌵 –––––––––—–––––––––—–•
<b>🧛‍♂ الـرجاء إختيار البرنامج المراد الرفع فية :</b>
",
        'parse_mode'   => "html",
        'reply_markup' => json_encode(['inline_keyboard' => $spit_menu_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

// ===================================
//  أقسام المنصات الرئيسية
// ===================================

if ($data == "Telegram") {
    $_stypes_tg = $_sub_types_shared;
    $_tg_extra  = $_stypes_tg["1"] ?? [];
    $_tg_btns   = [
        [spitBtn("• أعضاء / متابعين - 𝙼𝙴𝙼𝙱𝙴𝚁𝚂  • ", "Rb2-1-1", 'primary', 'spit_members')],
        [spitBtn(" • مشاهدات - 𝚅𝙸𝙴𝚆𝚂  • ", "Rb2-1-2", 'primary', 'spit_views')],
        [spitBtn("• لايكات - 𝙻𝙸𝙺𝙴𝚂  • ", "Rb2-1-3", 'primary', 'spit_likes')],
        [spitBtn('• بريميوم / نجوم  • ', "Rb2-1-4", 'primary', 'spit_premium')],
        [spitBtn("• تعليقات - 𝙲𝙾𝙼𝙼𝙴𝙽𝚃𝚂  • ", "Rb2-1-7", 'primary', 'spit_comments')],
        [spitBtn("• رياكشنات - 𝚁𝙴𝙰𝙲𝚃𝙸𝙾𝙽𝚂  • ", "Rb2-1-8", 'primary', 'spit_reactions')],
        [spitBtn("• مشاركات / تحويل - 𝙵𝙾𝚁𝚆𝙰𝚁𝙳𝚂  • ", "Rb2-1-9", 'primary', 'spit_shares')],
    ];
    foreach ($_tg_extra as $_st) {
        $_tg_btns[] = [spitBtn("{['emoji']} {['btn_text']}", "Rb2-1-{$_st['type']}", 'primary', '')];
    }
    $_tg_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "spit", 'style' => 'danger'], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back', 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
<u>☑️ - اهلا بك عزيزي العميل بقسم الرشق الخاص بالتليجرام </u>

<blockquote>↩️ - اختر الخدمة التي ترغب برشقها عبر الازرار بالاسفل </blockquote>
",
        'parse_mode'   => "html",
        'reply_markup' => json_encode(['inline_keyboard' => $_tg_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

if ($data == "Instagram") {
    $_stypes_ig = $_sub_types_shared;
    $_ig_extra  = $_stypes_ig["2"] ?? [];
    $_ig_btns   = [
        [spitBtn("• متابعين - 𝙵𝙾𝙻𝙻𝙾𝚆𝙴𝚁𝚂  • ", "Rb2-2-1", 'primary', 'spit_members')],
        [spitBtn(" • مشاهدات - 𝚅𝙸𝙴𝚆𝚂 • ", "Rb2-2-2", 'primary', 'spit_views')],
        [spitBtn("• لايكات - 𝙻𝙸𝙺𝙴𝚂 • ", "Rb2-2-3", 'primary', 'spit_likes')],
        [spitBtn("• تعليقات - 𝙲𝙾𝙼𝙼𝙴𝙽𝚃𝚂  • ", "Rb2-2-7", 'primary', 'spit_comments')],
        [spitBtn("• مشاركات - 𝚂𝙷𝙰𝚁𝙴𝚂 • ", "Rb2-2-9", 'primary', 'spit_shares')],
        [spitBtn("• حفظ - 𝚂𝙰𝚅𝙴𝚂  • ", "Rb2-2-13", 'primary', 'spit_saves')],
        [spitBtn("• تفاعلات - 𝚁𝙴𝙰𝙲𝚃𝙸𝙾𝙽𝚂 • ", "Rb2-2-8", 'primary', 'spit_reactions')],
    ];
    foreach ($_ig_extra as $_st) {
        $_ig_btns[] = [spitBtn("{['emoji']} {['btn_text']}", "Rb2-2-{$_st['type']}", 'primary', '')];
    }
    $_ig_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "spit", 'style' => 'danger'], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back', 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
<u>☑️ - اهلا بك عزيزي العميل بقسم الرشق الخاص بانستغرام </u>

<blockquote>↩️ - اختر الخدمة التي ترغب برشقها عبر الازرار بالاسفل </blockquote>
",
        'parse_mode'   => "html",
        'reply_markup' => json_encode(['inline_keyboard' => $_ig_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

if ($data == "TikTok") {
    $_stypes_tt = $_sub_types_shared;
    $_tt_extra  = $_stypes_tt["3"] ?? [];
    $_tt_btns   = [
        [spitBtn("• متابعين - 𝙵𝙾𝙻𝙻𝙾𝚆𝙴𝚁𝚂  • ", "Rb2-3-1", 'primary', 'spit_members')],
        [spitBtn(" • مشاهدات - 𝚅𝙸𝙴𝚆𝚂  • ", "Rb2-3-2", 'primary', 'spit_views')],
        [spitBtn("• لايكات - 𝙻𝙸𝙺𝙴𝚂  • ", "Rb2-3-3", 'primary', 'spit_likes')],
        [spitBtn("• تعليقات - 𝙲𝙾𝙼𝙼𝙴𝙽𝚃𝚂  • ", "Rb2-3-7", 'primary', 'spit_comments')],
        [spitBtn("• مشاركات - 𝚂𝙷𝙰𝚁𝙴𝚂  • ", "Rb2-3-9", 'primary', 'spit_shares')],
        [spitBtn("• حفظ - 𝚂𝙰𝚅𝙴𝚂  • ", "Rb2-3-13", 'primary', 'spit_saves')],
        [spitBtn("• شحن عملات تك توك  • ", "Rb2-3-5", 'primary', 'spit_coins')],
    ];
    foreach ($_tt_extra as $_st) {
        $_tt_btns[] = [spitBtn("{['emoji']} {['btn_text']}", "Rb2-3-{$_st['type']}", 'primary', '')];
    }
    $_tt_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "spit", 'style' => 'danger'], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back', 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
<u>☑️ - اهلا بك عزيزي العميل بقسم الرشق الخاص بالتيك توك </u>

<blockquote>↩️ - اختر الخدمة التي ترغب برشقها عبر الازرار بالاسفل </blockquote>
",
        'parse_mode'   => "html",
        'reply_markup' => json_encode(['inline_keyboard' => $_tt_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

if ($data == "FaceBook") {
    $_stypes_fb = $_sub_types_shared;
    $_fb_extra  = $_stypes_fb["4"] ?? [];
    $_fb_btns   = [
        [spitBtn("• متابعين - 𝙵𝙾𝙻𝙻𝙾𝚆𝙴𝚁𝚂  • ", "Rb2-4-1", 'primary', 'spit_members')],
        [spitBtn(" • مشاهدات - 𝚅𝙸𝙴𝚆𝚂  • ", "Rb2-4-2", 'primary', 'spit_views')],
        [spitBtn("• لايكات - 𝙻𝙸𝙺𝙴𝚂  • ", "Rb2-4-3", 'primary', 'spit_likes')],
        [spitBtn("• تعليقات - 𝙲𝙾𝙼𝙼𝙴𝙽𝚃𝚂  • ", "Rb2-4-7", 'primary', 'spit_comments')],
        [spitBtn("• مشاركات - 𝚂𝙷𝙰𝚁𝙴𝚂  • ", "Rb2-4-9", 'primary', 'spit_shares')],
        [spitBtn("• تفاعلات - 𝚁𝙴𝙰𝙲𝚃𝙸𝙾𝙽𝚂  • ", "Rb2-4-8", 'primary', 'spit_reactions')],
    ];
    foreach ($_fb_extra as $_st) {
        $_fb_btns[] = [spitBtn("{['emoji']} {['btn_text']}", "Rb2-4-{$_st['type']}", 'primary', '')];
    }
    $_fb_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "spit", 'style' => 'danger'], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back', 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
<u>☑️ - اهلا بك عزيزي العميل بقسم الرشق الخاص بالفيسبوك </u>

<blockquote>↩️ - اختر الخدمة التي ترغب برشقها عبر الازرار بالاسفل </blockquote>
",
        'parse_mode'   => "html",
        'reply_markup' => json_encode(['inline_keyboard' => $_fb_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

if ($data == "Twitter") {
    $_stypes_tw = $_sub_types_shared;
    $_tw_extra  = $_stypes_tw["5"] ?? [];
    $_tw_btns   = [
        [spitBtn("• متابعين - 𝙵𝙾𝙻𝙻𝙾𝚆𝙴𝚁𝚂  • ", "Rb2-5-1", 'primary', 'spit_members')],
        [spitBtn(" • مشاهدات - 𝚅𝙸𝙴𝚆𝚂  • ", "Rb2-5-2", 'primary', 'spit_views')],
        [spitBtn("• لايكات - 𝙻𝙸𝙺𝙴𝚂  • ", "Rb2-5-3", 'primary', 'spit_likes')],
        [spitBtn("• ريتويت / مشاركات  • ", "Rb2-5-9", 'primary', 'spit_shares')],
    ];
    foreach ($_tw_extra as $_st) {
        $_tw_btns[] = [spitBtn("{['emoji']} {['btn_text']}", "Rb2-5-{$_st['type']}", 'primary', '')];
    }
    $_tw_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "spit", 'style' => 'danger'], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back', 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
<u>☑️ - اهلا بك عزيزي العميل بقسم الرشق الخاص بتويتر </u>

<blockquote>↩️ - اختر الخدمة التي ترغب برشقها عبر الازرار بالاسفل </blockquote>
",
        'parse_mode'   => "html",
        'reply_markup' => json_encode(['inline_keyboard' => $_tw_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

if ($data == "Whats") {
    $_stypes_wa = $_sub_types_shared;
    $_wa_extra  = $_stypes_wa["6"] ?? [];
    $_wa_btns   = [
        [spitBtn("• متابعين - 𝙵𝙾𝙻𝙻𝙾𝚆𝙴𝚁𝚂  • ", "Rb2-6-1", 'primary', 'spit_members')],
        [spitBtn(" • مشاهدات - 𝚅𝙸𝙴𝚆𝚂  • ", "Rb2-6-2", 'primary', 'spit_views')],
        [spitBtn("• لايكات - 𝙻𝙸𝙺𝙴𝚂  • ", "Rb2-6-3", 'primary', 'spit_likes')],
        [spitBtn(" • • استطلاع راي - 𝚂𝚄𝚁𝚅𝙴𝚈 📊 • ", "Rb2-6-6", 'primary', 'spit_survey')],
    ];
    foreach ($_wa_extra as $_st) {
        $_wa_btns[] = [spitBtn("{['emoji']} {['btn_text']}", "Rb2-6-{$_st['type']}", 'primary', '')];
    }
    $_wa_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "spit", 'style' => 'danger'], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back', 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
<u>☑️ - اهلا بك عزيزي العميل بقسم الرشق الخاص بالواتس اب </u>

<blockquote>↩️ - اختر الخدمة التي ترغب بها عبر الازرار بالاسفل </blockquote>
",
        'parse_mode'   => "html",
        'reply_markup' => json_encode(['inline_keyboard' => $_wa_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

if ($data == "YouTube") {
    $_stypes_yt = $_sub_types_shared;
    $_yt_extra  = $_stypes_yt["7"] ?? [];
    $_yt_btns   = [
        [spitBtn("• مشتركين - 𝚂𝚄𝙱𝚂𝙲𝚁𝙸𝙱𝙴𝚁𝚂 • ", "Rb2-7-1", 'primary', 'spit_members')],
        [spitBtn(" • مشاهدات - 𝚅𝙸𝙴𝚆𝚂  • ", "Rb2-7-2", 'primary', 'spit_views')],
        [spitBtn("• لايكات - 𝙻𝙸𝙺𝙴𝚂  • ", "Rb2-7-3", 'primary', 'spit_likes')],
        [spitBtn("• تعليقات - 𝙲𝙾𝙼𝙼𝙴𝙽𝚃𝚂  • ", "Rb2-7-7", 'primary', 'spit_comments')],
    ];
    foreach ($_yt_extra as $_st) {
        $_yt_btns[] = [spitBtn("{['emoji']} {['btn_text']}", "Rb2-7-{$_st['type']}", 'primary', '')];
    }
    $_yt_btns[] = [['text' => '- عوده 🔜', 'callback_data' => "spit", 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "*👩‍🌾 يبـدو انك اخترت Youtube إليـك خدمـاتة :*",
        'parse_mode'   => "MarkDown",
        'reply_markup' => json_encode(['inline_keyboard' => $_yt_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

if ($data == "Kwai") {
    $_stypes_kw = $_sub_types_shared;
    $_kw_extra  = $_stypes_kw["10"] ?? [];
    $_kw_btns   = [
        [spitBtn("• متابعين - 𝙵𝙾𝙻𝙻𝙾𝚆𝙴𝚁𝚂 • ", "Rb2-10-1", 'primary', 'spit_members')],
        [spitBtn(" • مشاهدات - 𝚅𝙸𝙴𝚆𝚂 • ", "Rb2-10-2", 'primary', 'spit_views')],
        [spitBtn("• لايكات - 𝙻𝙸𝙺𝙴𝚂 • ", "Rb2-10-3", 'primary', 'spit_likes')],
    ];
    foreach ($_kw_extra as $_st) {
        $_kw_btns[] = [spitBtn("{['emoji']} {['btn_text']}", "Rb2-10-{$_st['type']}", 'primary', '')];
    }
    $_kw_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "spit", 'style' => 'danger'], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back', 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "*مرحبا بك عزيزي*❤️‍🩹\n\n*هذا قسم خدمات كواي اختر الخدمه التي تريدها.*☑️",
        'parse_mode'   => "MarkDown",
        'reply_markup' => json_encode(['inline_keyboard' => $_kw_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

if ($data == "snapchat") {
    $_stypes_sc = $_sub_types_shared;
    $_sc_extra  = $_stypes_sc["0"] ?? [];
    $_sc_btns   = [
        [spitBtn("• متابعين - 𝙵𝙾𝙻𝙻𝙾𝚆𝙴𝚁𝚂 • ", "Rb2-9-1", 'primary', 'spit_members')],
        [spitBtn(" • مشاهدات - 𝚅𝙸𝙴𝚆𝚂 • ", "Rb2-9-2", 'primary', 'spit_views')],
        [spitBtn("• لايكات - 𝙻𝙸𝙺𝙴𝚂 • ", "Rb2-9-3", 'primary', 'spit_likes')],
    ];
    foreach ($_sc_extra as $_st) {
        $_sc_btns[] = [spitBtn("{['emoji']} {['btn_text']}", "Rb2-0-{$_st['type']}", 'primary', '')];
    }
    $_sc_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "spit", 'style' => 'danger'], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back', 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "*مرحبا بك عزيزي*❤️‍🩹\n\n*هذا قسم خدمات سناب شات اختر الخدمه التي تريدها.*☑️",
        'parse_mode'   => "MarkDown",
        'reply_markup' => json_encode(['inline_keyboard' => $_sc_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

if ($data == "f") {

    // ─── حماية نظام الدعوات ───────────────────────────────────
    if (!inviteGate($id, $chat_id, $message_id)) exit;
    // ─────────────────────────────────────────────────────────

    $_stypes_fr = $_sub_types_shared;
    $_fr_extra  = $_stypes_fr["8"] ?? [];
    $_fr_btns   = [
        [spitBtn("• متابعين - 𝙵𝙾𝙻𝙻𝙾𝚆𝙴𝚁𝚂 •", "Rb2-8-1", 'primary', 'spit_members')],
        [spitBtn("• مشاهدات - 𝚅𝙸𝙴𝚆𝚂 • ", "Rb2-8-2", 'primary', 'spit_views')],
        [spitBtn("• لايكات - 𝙻𝙸𝙺𝙴𝚂 • ", "Rb2-8-3", 'primary', 'spit_likes')],
    ];
    foreach ($_fr_extra as $_st) {
        $_fr_btns[] = [spitBtn("{['emoji']} {['btn_text']}", "Rb2-8-{$_st['type']}", 'primary', '')];
    }
    $_fr_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "spit", 'style' => 'danger'], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => 'back', 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "*مرحبا بك عزيزي*❤️‍🩹\n\n*هذا قسم الخدمات المجانيه اختر الخدمه التي تريدها.*☑️",
        'parse_mode'   => "MarkDown",
        'reply_markup' => json_encode(['inline_keyboard' => $_fr_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
}

// ===================================
//  الأقسام المخصصة
// ===================================
if ($exdata[0] == "custom_sec") {
    $csec_num     = $exdata[1];
    $_custom_secs = $_rashq_secs_shared;
    $csec_name    = "قسم مخصص";
    $csec_emoji   = "📂";
    foreach ($_custom_secs as $_sc) {
        if ($_sc['num'] == $csec_num) { $csec_name = $_sc['name']; $csec_emoji = $_sc['emoji']; break; }
    }
    $_type_names = ["1" => "متابعين", "2" => "مشاهدات", "3" => "لايكات", "4" => "بريميوم", "5" => "عملات", "6" => "استطلاع", "7" => "تعليقات", "8" => "تفاعلات", "9" => "مشاركات", "10" => "مشتركين", "11" => "نقرات", "12" => "حزم"];
    $_available_types = [];
    foreach ($increase["idplus"] as $_sv) {
        if ($_sv['num'] == $csec_num) { $_available_types[$_sv['type']] = true; }
    }
    if (empty($_available_types)) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "لا توجد خدمات في هذا القسم بعد",
            'show_alert'        => true,
        ]);
        exit;
    }
    $ckey = [];
    foreach ($_available_types as $_t => $_v) {
        $_tlabel = $_type_names[$_t] ?? "خدمة";
        $ckey[]  = [['text' => "• $_tlabel •", 'callback_data' => "Rb2-$csec_num-$_t", 'style' => 'primary']];
    }
    $ckey[] = [['text' => '- رجوع 🔜', 'callback_data' => "spit"], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => "back", 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "<b>$csec_emoji اهلا بك في قسم $csec_name</b>\n\n<blockquote>↩️ - اختر الخدمة التي ترغب بها من الأزرار بالأسفل</blockquote>",
        'parse_mode'   => "html",
        'reply_markup' => json_encode(['inline_keyboard' => $ckey]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// ===================================
//  عرض قائمة السيرفرات للخدمة
// ===================================
if ($exdata[0] == "Rb2") {
    $nums  = $exdata[1];
    $type  = $exdata[2];
    $APP   = getAppName($nums);

    // خريطة أسماء الأنواع الموسعة
    $_typeNamesMap = [
        "1"  => ["display" => "متابعين",  "single" => "متابع"],
        "2"  => ["display" => "مشاهدات",  "single" => "مشاهدة"],
        "3"  => ["display" => "لايكات",   "single" => "لايك"],
        "4"  => ["display" => "بريميوم",  "single" => "بريميوم"],
        "5"  => ["display" => "عملات",    "single" => "عملة"],
        "6"  => ["display" => "استطلاع",  "single" => "تصويت"],
        "7"  => ["display" => "تعليقات",  "single" => "تعليق"],
        "8"  => ["display" => "تفاعلات",  "single" => "تفاعل"],
        "9"  => ["display" => "مشاركات",  "single" => "مشاركة"],
        "10" => ["display" => "مشتركين",  "single" => "مشترك"],
        "11" => ["display" => "نقرات",    "single" => "نقرة"],
        "12" => ["display" => "حزم",      "single" => "حزمة"],
        "13" => ["display" => "حفظ",      "single" => "حفظ"],
    ];
    // دعم الأنواع المخصصة من sub_types.json
    $_sub_types_all = $_sub_types_shared;
    foreach ($_sub_types_all as $_sec_stypes) {
        foreach ($_sec_stypes as $_st) {
            if (isset($_st['type']) && isset($_st['name'])) {
                $_typeNamesMap[(string)$_st['type']] = ["display" => $_st['name'], "single" => $_st['name']];
            }
        }
    }
    $Type  = $_typeNamesMap[$type]['display'] ?? "خدمة $type";
    $Type2 = $_typeNamesMap[$type]['single']  ?? "خدمة";

    // دعم الأقسام المخصصة (90+)
    if (is_numeric($nums) && (int)$nums >= 90) {
        $_csecs_rb2 = $_rashq_secs_shared;
        foreach ($_csecs_rb2 as $_csc) {
            if ($_csc['num'] == $nums) { $APP = $_csc['name']; break; }
        }
    }

    if ($openandlock['saliv']['lock'] == "ok") {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "♻️ - قسم الرشق مقفل حاليا",
            'show_alert'        => true,
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    $key = ['inline_keyboard' => [[['text' => '☑️ نوع السيرفر وسعر العضو الواحد⬇️', 'callback_data' => 'no', 'style' => 'primary']]]];
    $name = null;
    foreach ($increase["idplus"] as $zero => $num) {
        if ($num['num'] == $nums && $num['type'] == $type) {
            $name    = $num['name'];
            $price   = round($num['price'] / 1000, 4);
            $key['inline_keyboard'][] = [['text' => "$name > ( ₽ $price )", 'callback_data' => "Ls-$zero", 'style' => 'primary']];
        }
    }
    $key['inline_keyboard'][] = [['text' => '- رجوع 🔜', 'callback_data' => "spit"], ['text' => '- الصفحة الرئيسية 🔙', 'callback_data' => "back", 'style' => 'danger']];

    if ($name === null) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "- لم تتم الإضافة لهذا السيرفر بعد",
            'show_alert'        => true,
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "🙋‍♂️ *- رشق $Type $APP . 🫂❤️‍🔥\n\n🎬-يرجى إختيار نوع الرشق من الأسفل.* 👇",
        'parse_mode'   => "MarkDown",
        'reply_markup' => json_encode($key),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// ===================================
//  تفاصيل الخدمة المختارة
// ===================================
if ($exdata[0] == "Ls") {
    $zero       = $exdata[1];
    $pri        = $increase["idplus"][$zero] ?? null;
    if ($pri === null) exit;

    $num        = $pri['num'];
    $type       = $pri['type'];
    $Type       = $pri['Type'];
    $name       = $pri['name'];
    $minimum    = $pri['minimum'];
    $maximum    = $pri['maximum'];
    $explained  = $pri['explained'];
    $speed      = $pri['speed'];
    $quality    = $pri['quality'];
    $get_off    = $pri['get_off'];
    $start_time = $pri['start_time'];
    $price_raw  = $pri['price'];

    // تنسيق السعر للعرض (السعر بالروبل لكل 1000)
    $price = is_numeric($price_raw) ? round((float)$price_raw, 4) : $price_raw;
    // حذف الأصفار الزائدة في النهاية
    $price = rtrim(rtrim((string)$price, '0'), '.');

    // رسالة الإرشاد حسب القسم والنوع
    $instruction_map = [
        "1-1" => "🙋🏻 - يرجى إرسال رابط القناة التي تريد رشقها.",
        "1-2" => "🙋🏻 - يرجى إرسال رابط الذي تريد رشق مشاهدات له.",
        "1-3" => "🙋🏻 - يرجى إرسال رابط الرسالة الذي تريد رشق اللايكات له.",
        "2-1" => "🙋🏻 - يرجى إرسال يوزر حسابك متبوعاً ب @.\nEx: @username",
    ];
    $x   = $instruction_map["$num-$type"] ?? "🙋🏻 - يرجى إرسال الرابط الذي تريد رشقه.";
    $n   = ($num == 2 && $type == 1) ? "⚠️ - ملاحظة : يجب ان يكون الحساب المراد رشق متابعين له *عام* وليس *خاص*" : "";

    $ls_kb = [
        [['text' => "$Type",              'callback_data' => ":", 'style' => 'primary'], ['text' => "- النوع 🌐",    'callback_data' => "::", 'style' => 'primary']],
        [['text' => "₽ $price",          'callback_data' => ":", 'style' => 'primary'], ['text' => "- السعر 1k:💰",  'callback_data' => "::", 'style' => 'primary']],
        [['text' => "$speed",             'callback_data' => ":", 'style' => 'primary'], ['text' => "- السرعة : 🚀", 'callback_data' => "::", 'style' => 'primary']],
        [['text' => "$quality",           'callback_data' => ":", 'style' => 'primary'], ['text' => "- الجودة : 🏆", 'callback_data' => "::", 'style' => 'primary']],
        [['text' => "%$get_off",          'callback_data' => ":", 'style' => 'primary'], ['text' => "- النزول : 📊", 'callback_data' => "::", 'style' => 'primary']],
        [['text' => "خلال $start_time",  'callback_data' => ":", 'style' => 'primary'], ['text' => "- الوقت : ⏰",  'callback_data' => "::", 'style' => 'primary']],
        [['text' => "- رجوع 🔜",         'callback_data' => "Rb2-$num-$type", 'style' => 'primary']],
    ];
    if ($is_admin) {
        $ls_kb[] = [['text' => "🗑 حذف الخدمة", 'callback_data' => "del_svc-$zero", 'style' => 'primary']];
    }

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "*🎬- نوع الرشق* : $name\n\n$explained\n\n*$x*\n$n",
        'parse_mode'   => "MarkDown",
        'reply_markup' => json_encode(['inline_keyboard' => $ls_kb]),
    ]);
    file_put_contents("data/id/$id/step.txt", "Ls1|$zero");
    exit;
}

// ===================================
//  حذف خدمة من شاشة التفاصيل (أدمن)
// ===================================
if ($exdata[0] == "del_svc" && $is_admin) {
    $zero = $exdata[1];
    if (!isset($increase["idplus"][$zero])) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "⚠️ الخدمة غير موجودة.", 'show_alert' => true]);
        exit;
    }
    $svc_name  = $increase["idplus"][$zero]['name']  ?? 'خدمة';
    $svc_price = $increase["idplus"][$zero]['price'] ?? '0';
    $svc_num   = $increase["idplus"][$zero]['num']   ?? '0';
    $APP       = getAppName($svc_num);

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "🗑 *تأكيد حذف الخدمة*\n\n📝 الاسم: *$svc_name*\n💰 السعر/1000: *₽ $svc_price*\n📱 القسم: *$APP*\n\n⚠️ هل أنت متأكد من الحذف؟",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '✅ نعم، احذف', 'callback_data' => "del_svc_confirm-$zero"], ['text' => '❌ لا، رجوع', 'callback_data' => "Ls-$zero", 'style' => 'primary']],
            ],
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

if ($exdata[0] == "del_svc_confirm" && $is_admin) {
    $zero = $exdata[1];
    if (!isset($increase["idplus"][$zero])) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "⚠️ الخدمة غير موجودة أو تم حذفها مسبقاً.", 'show_alert' => true]);
        exit;
    }
    $svc_name = $increase["idplus"][$zero]['name'] ?? 'خدمة';
    $svc_num  = $increase["idplus"][$zero]['num']  ?? '0';
    $type     = $increase["idplus"][$zero]['type'] ?? '1';
    $APP      = getAppName($svc_num);

    unset($increase["idplus"][$zero]);
    file_put_contents('data/increase.json', json_encode($increase, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "✅ *تم حذف الخدمة بنجاح*\n\n📝 الاسم: *$svc_name*\n📱 القسم: *$APP*",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '- عرض خدمات القسم 🔜', 'callback_data' => "Rb2-$svc_num-$type", 'style' => 'primary']],
                [['text' => '- لوحة التحكم 🔜',       'callback_data' => 'Splash_settings', 'style' => 'danger']],
            ],
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// ===================================
//  الخطوة 1: استقبال الرابط
// ===================================
if ($text && $text != '/start' && $exstep[0] == "Ls1") {
    $zero    = $exstep[1];
    $pri     = $increase["idplus"][$zero] ?? null;
    if ($pri === null) exit;

    $num     = $pri['num'];
    $type    = $pri['type'];
    $minimum = $pri['minimum'];
    $maximum = $pri['maximum'];
    $price   = round((float)$pri['price'] / 1000, 6); // سعر العضو الواحد بالروبل
    $c       = $price > 0 ? floor($Balance / $price) : 0;
    $x       = getLinkLabel($num, $type);
    $urls    = $text;

    bot('sendMessage', [
        'chat_id'             => $chat_id,
        'text'                => "📡 *- $x:* [$urls]\n\n⚜ *- يرجى إرسال* عدد الأعضاء، تذكر *أقل عدد للطلب $minimum ، وأقصى عدد للطلب $maximum* 👤\n\n👤 *- سعر العضو الواحد:* ₽ $price 💰\n\n🏆 *- يمكنك رشق $c* 🫂",
        'reply_to_message_id' => $message_id,
        'parse_mode'          => "MarkDown",
        'reply_markup'        => json_encode([
            'inline_keyboard' => [[['text' => "- رجوع 🔜", 'callback_data' => "Rb2-$num-$type", 'style' => 'primary']]],
        ]),
    ]);
    file_put_contents("data/id/$id/step.txt", "Ls2|$zero|$urls");
    exit;
}

// ===================================
//  الخطوة 2: استقبال العدد
// ===================================
if ($text && $text != '/start' && $exstep[0] == "Ls2") {
    $zero    = $exstep[1];
    // implode لإعادة بناء الرابط حتى لو احتوى على "|"
    $urls    = implode("|", array_slice($exstep, 2));
    $array   = substr(str_shuffle("0123456789"), 0, 10);
    $pri     = $increase["idplus"][$zero] ?? null;
    if ($pri === null) exit;

    $num     = $pri['num'];
    $type    = $pri['type'];
    $name    = $pri['name'];
    $minimum = $pri['minimum'];
    $maximum = $pri['maximum'];
    $price   = round($pri['price'] / 1000, 6) * $text;
    $c       = $Balance - $price;
    $x       = getLinkLabel($num, $type);

    // التحقق من أن المدخل أرقام فقط
    if (preg_match("/(\D)/", $text)) {
        bot('SendMessage', [
            'chat_id'             => $chat_id,
            'text'                => "*- يرجى إرسال أرقام فقط.*",
            'parse_mode'          => "MarkDown",
            'reply_to_message_id' => $message_id,
        ]);
        exit;
    }

    // التحقق من الحد الأدنى والأقصى
    if ($text > $maximum || $text < $minimum) {
        bot('SendMessage', [
            'chat_id'             => $chat_id,
            'text'                => "❌ *- نعتذر عزيزي يرجى إرسال عدد أكبر من $minimum وأصغر من $maximum.*",
            'parse_mode'          => "MarkDown",
            'reply_to_message_id' => $message_id,
        ]);
        exit;
    }

    if ($price > $Balance) {
        $v  = "❌ *- للاسف لايوجد لديك رصيد كافي* 🥹";
        $vv = null;
    } else {
        $v  = "☑️ *- هل تريد اكمال الطلب* ⚜";
        $vv = "- موافقه ✅";
    }

    // بناء الكيبورد - لا يُرسل زر الموافقة لو الرصيد غير كافٍ (text فارغ يرفضه تيليجرام)
    $keyboard_rows = [];
    if ($vv !== null) {
        $keyboard_rows[] = [['text' => "$vv", 'callback_data' => "As-$array", 'style' => 'primary']];
    }
    $keyboard_rows[] = [['text' => '- الغاء', 'callback_data' => "Rb2-$num-$type", 'style' => 'primary']];

    bot('SendMessage', [
        'chat_id'                  => $chat_id,
        'text'                     => "📋 *- خلاصة العملية: 👇\n\n👨🏻‍💻 - الرشق* : $name\n🔗 *- $x* : [$urls] ⚜.\n🔰 *- العدد المطلوب : [$text]* ✅.\n💰 *- السعر* : ₽ $price\n💳 *- رصيدك الآن* : $Balance .\n🏆 *- رصيدك بعد الخصم* : $c\n\n$v",
        'parse_mode'               => "MarkDown",
        'disable_web_page_preview' => true,
        'reply_to_message_id'      => $message_id,
        'reply_markup'             => json_encode([
            'inline_keyboard' => $keyboard_rows,
        ]),
    ]);
    file_put_contents("data/id/$id/$array.txt", "$zero|$urls|$text|$EM");
    @unlink("data/id/$id/step.txt");
    exit;
}

// ===================================
//  تنفيذ الطلب (مع فحص Anti-Spam)
// ===================================
if ($exdata[0] == "As") {
    $array = $exdata[1];
    $idrb  = @file_get_contents("data/id/$id/$array.txt");
    if (!$idrb) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "حدث خطأ، أعد المحاولة.", 'show_alert' => true]);
        exit;
    }

    // ── فحص Anti-Spam قبل تنفيذ الطلب ─────────────────────
    $spam = antiSpamPro($id, "spit_order", 60, 3, 20);
    if (!$spam['allowed']) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => $spam['reason'],
            'show_alert'        => true,
        ]);
        exit;
    }
    // ────────────────────────────────────────────────────────

    // استخدام limit=4 لحماية URLs التي قد تحتوي على "|"
    $ex     = explode("|", $idrb, 4);
    $zero   = trim($ex[0] ?? '');
    $urls   = trim($ex[1] ?? '');
    // إخفاء 70% من الرابط - يظهر 15% من البداية و15% من النهاية فقط
    $url_len = mb_strlen($urls, 'UTF-8');
    if ($url_len <= 8) {
        // رابط قصير جداً: أخفِ النصف الأوسط فقط
        $show_each = max(1, (int)($url_len * 0.3));
        $mask_len  = max(1, $url_len - ($show_each * 2));
        $idurls    = mb_substr($urls, 0, $show_each, 'UTF-8')
                   . str_repeat('•', $mask_len)
                   . mb_substr($urls, $url_len - $show_each, null, 'UTF-8');
    } elseif ($url_len == 0) {
        $idurls = '•••';
    } else {
        $show_each = max(3, (int)($url_len * 0.15));
        // تأكد أن show_each لا يتجاوز نصف الطول لتجنب التكرار
        $show_each = min($show_each, (int)($url_len / 3));
        $mask_len  = max(3, $url_len - ($show_each * 2));
        $idurls    = mb_substr($urls, 0, $show_each, 'UTF-8')
                   . str_repeat('•', $mask_len)
                   . mb_substr($urls, $url_len - $show_each, null, 'UTF-8');
    }
    $number = trim($ex[2] ?? '');
    $emil   = trim($ex[3] ?? ''); // trim لإزالة أي newline/space زائد
    $idSend = $orderall;

    // التحقق من أن بيانات الملف المؤقت مكتملة
    if ($zero === '' || $urls === '' || $number === '' || $emil === '') {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "بيانات الطلب غير مكتملة، أعد المحاولة.", 'show_alert' => true]);
        @unlink("data/id/$id/$array.txt");
        @unlink("data/id/$id/step.txt");
        exit;
    }

    $pri = $increase["idplus"][$zero] ?? null;
    if ($pri === null) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "الخدمة غير موجودة، أعد الاختيار.", 'show_alert' => true]);
        @unlink("data/id/$id/$array.txt");
        @unlink("data/id/$id/step.txt");
        exit;
    }

    $ID        = $pri['ID'];
    $num       = $pri['num'];
    $type      = $pri['type'];
    $Type      = $pri['Type'];
    $name      = $pri['name'];
    $price     = round($pri['price'] / 1000, 6) * $number;
    $add_site  = $pri['add'];
    // نستخدم 'site' المحفوظ مباشرة إن وُجد (أكثر موثوقية)، ثم getSiteByNum كـ fallback
    $Location  = !empty($pri['site']) ? $pri['site'] : getSiteByNum($add_site);
    if (empty($Location)) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "⚠️ الموقع المرتبط بهذه الخدمة غير موجود، أعد إضافة الخدمة.", 'show_alert' => true]);
        @unlink("data/id/$id/step.txt");
        @unlink("data/id/$id/$array.txt");
        exit;
    }
    $ex_loc    = explode(".", $Location);
    $api_key   = trim(@file_get_contents("data/api/{$ex_loc[0]}.txt"));
    $APP       = getAppName($num);
    $x         = getLinkLabel($num, $type);

    // فحص مفتاح API قبل الإرسال
    if (empty($api_key)) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "⚠️ مفتاح API للموقع ($Location) غير مضاف، تواصل مع الإدارة.",
            'show_alert'        => true,
        ]);
        @unlink("data/id/$id/step.txt");
        @unlink("data/id/$id/$array.txt");
        exit;
    }

    // قسم الرشق مقفل؟
    if ($openandlock['saliv']['lock'] == "ok") {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "♻️ - قسم الرشق مقفل حاليا", 'show_alert' => true]);
        @unlink("data/id/$id/step.txt");
        @unlink("data/id/$id/$array.txt");
        exit;
    }

    // تحقق من تطابق المستخدم - مع trim() لتجنب مشكلة الـ whitespace
    if (trim($EM) !== trim($emil)) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "انتهت صلاحية الطلب، أعد المحاولة من جديد.", 'show_alert' => true]);
        @unlink("data/id/$id/step.txt");
        @unlink("data/id/$id/$array.txt");
        exit;
    }

    // رصيد غير كافٍ؟
    if ($price > $Balance) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "ليس لديك رصيد كافي", 'show_alert' => true]);
        @unlink("data/id/$id/step.txt");
        @unlink("data/id/$id/$array.txt");
        exit;
    }

    // إرسال الطلب إلى API السيرفر (POST بدلاً من GET لضمان قراءة البيانات صحيحاً)
    $post_fields = "key=" . urlencode($api_key) . "&action=add&service=" . urlencode($ID) . "&link=" . urlencode($urls) . "&quantity=" . urlencode($number);
    $ch = curl_init("https://$Location/api/v2");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
    $api_response_raw = curl_exec($ch);
    $curl_errno = curl_errno($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);
    $api   = $api_response_raw ? json_decode($api_response_raw, true) : null;
    $order = $api["order"] ?? null;
    $error = $api["error"]  ?? null;
    // =FIX= إضافة تفاصيل خطأ curl للتشخيص
    if ($curl_errno && !$error) {
        $error = "خطأ اتصال #{$curl_errno}: {$curl_error}";
    }

    if ($order === null || $error) {
        // ترجمة أشهر أخطاء API إلى عربي
        $error_msg = $error ?? ("فشل الاتصال - رد السيرفر: " . mb_substr($api_response_raw ?: 'لا يوجد رد', 0, 100, 'UTF-8'));
        $error_translations = [
            "not_enough_funds"          => "❌ رصيد المزود غير كافٍ، تواصل مع الإدارة.",
            "neworder.error.not_enough_funds" => "❌ رصيد المزود غير كافٍ، تواصل مع الإدارة.",
            "incorrect_service_id"      => "❌ رقم الخدمة غير صحيح، أعد إضافة الخدمة.",
            "incorrect_link"            => "❌ الرابط المدخل غير صحيح، تأكد منه وأعد المحاولة.",
            "link_not_found"            => "❌ الرابط غير موجود أو خاص، تأكد أن الحساب عام.",
            "invalid_link"              => "❌ الرابط غير صالح، تأكد من صحة الرابط.",
            "service_not_found"         => "❌ الخدمة غير موجودة في السيرفر.",
            "max_quantity"              => "❌ الكمية المطلوبة تتجاوز الحد الأقصى.",
            "min_quantity"              => "❌ الكمية المطلوبة أقل من الحد الأدنى.",
            "ip_not_allowed"            => "❌ عذراً، هذا الطلب غير مسموح من هذا الموقع.",
        ];
        foreach ($error_translations as $en_key => $ar_msg) {
            if ($error && stripos($error, $en_key) !== false) {
                $error_msg = $ar_msg;
                break;
            }
        }
        // إرسال تفاصيل الخطأ للأدمن والقناة للتشخيص
        $debug_text = "🔴 *خطأ API*

"
            . "🌐 الموقع المستخدم: `$Location`
"
            . "📦 add_site: `$add_site` | site في JSON: `" . ($pri['site'] ?? 'غير محفوظ') . "`
"
            . "📁 ملف API: `data/api/{$ex_loc[0]}.txt`
"
            . "🔑 المفتاح: `" . (empty($api_key) ? 'فارغ❌' : mb_substr($api_key,0,8,'UTF-8').'***') . "`
"
            . "🆔 الخدمة: `$ID`
"
            . "🔢 العدد: `$number`

"
            . "📩 رد API:
`" . mb_substr($api_response_raw ?: 'لا يوجد رد', 0, 300, 'UTF-8') . "`";
        bot('sendMessage', ['chat_id' => 6996492261, 'text' => $debug_text, 'parse_mode' => 'Markdown']);
        bot('sendMessage', ['chat_id' => $PAY,       'text' => $debug_text, 'parse_mode' => 'Markdown']);
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "⚠️ فشل الطلب\n$error_msg",
            'show_alert'        => true,
        ]);
        @unlink("data/id/$id/step.txt");
        @unlink("data/id/$id/$array.txt");
        exit;
    }

    // نجح الطلب - حفظ البيانات وإرسال الإشعارات
    @unlink("data/id/$id/step.txt");
    @unlink("data/id/$id/$array.txt");

    // رقم الطلب التسلسلي في قناة الإشعارات
    $seq_file = "data/pay_order_seq.txt";
    $seq_num  = (int)(@file_get_contents($seq_file) ?: 0) + 1;
    file_put_contents($seq_file, $seq_num, LOCK_EX);

    sendOrderConfirmMsg($chat_id, $message_id, $x, $urls, $number, $price, $idSend, $name, $order);
    notifySimChannel($sim, $name, $ID, $APP, $Type, $number, $nid, $price, $idurls, $me, $seq_num, $zero, $add_site);
    notifyPayChannel($PAY, $APP, $number, $Balance, $name, $seq_num, $order, $urls, $EM, $price, $Location);

    // خصم الرصيد
    $points = (float)(@file_get_contents("EMILS/$EM/points.txt") ?: 0);
    file_put_contents("EMILS/$EM/points.txt", $points - $price, LOCK_EX);

    // حفظ الطلب في ملف المستخدم
    $spitmy = rand(1234567, 9999999);
    $BUYSSPIT['spit'][$spitmy] = [
        'idSend'  => $idSend,
        'order'   => $order,
        'price'   => $price,
        'status'  => 1,
        'number'  => $number,
        'zero'    => $zero,
        'add'     => $add_site,
        'urls'    => $urls,
        'name'    => $name,
        'num'     => $num,
        'type'    => $type,
        'chat-id' => $id,
        'DAY'     => $DAY,
    ];
    file_put_contents("EMILS/$EM/spit.json", json_encode($BUYSSPIT, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);

    $ORDERALL[$idSend]['account'] = $EM;
    $ORDERALL[$idSend]['order']   = $spitmy;
    OrdAll($ORDERALL);

    $STORAGEALL = jRead('data/storage.json') ?: ['spit' => 0, 'ruble' => 0];
    $STORAGEALL['spit']  = ($STORAGEALL['spit']  ?? 0) + 1;
    $STORAGEALL['ruble'] = ($STORAGEALL['ruble'] ?? 0) + $price;
    StoAll($STORAGEALL);
    exit;
}

// ===================================
//  📦 نظام "طلباتي" + إعادة التعبئة التلقائية
// ===================================

// دالة مساعدة: استرجاع كل طلبات المستخدم الحالي
function getUserSpitOrders($id) {
    global $ORDERALL;
    $orders = [];
    if (!is_array($ORDERALL)) return $orders;

    // كاش الملفات: كل حساب يُقرأ من الديسك مرة واحدة فقط (تسريع كبير)
    $cache = [];

    foreach ($ORDERALL as $idSend => $entry) {
        if (!is_array($entry) || !isset($entry['account'], $entry['order'])) continue;
        $acc  = $entry['account'];
        $ordK = $entry['order'];

        // قراءة ملف الحساب مرة واحدة فقط
        if (!array_key_exists($acc, $cache)) {
            $file = "EMILS/{$acc}/spit.json";
            $cache[$acc] = file_exists($file)
                ? (json_decode(@file_get_contents($file), true) ?: [])
                : [];
        }
        $data = $cache[$acc];

        if (!isset($data['spit'][$ordK])) continue;
        $o = $data['spit'][$ordK];
        if (($o['chat-id'] ?? null) != $id) continue;

        $o['_idSend']  = $idSend;
        $o['_account'] = $acc;
        $o['_orderK']  = $ordK;
        $orders[$idSend] = $o;
    }
    return $orders;
}

// دالة مساعدة: تصنيف الطلب (pending/completed)
function spitStatusGroup($status) {
    if ((int)$status === 3) return 'completed';
    if ((int)$status === -1) return 'cancelled';
    return 'pending';
}

// دالة مساعدة: نص الحالة بالعربي مع رمز
function spitStatusLabel($status) {
    $s = (int)$status;
    if ($s === 3)  return 'مكتمل ✅';
    if ($s === -1) return 'ملغي ⛔️';
    return 'قيد التنفيذ ⏳';
}

// عرض صفحة "طلباتي" مع فلترة وترقيم
if ($exdata[0] == "MyOrders" || $exdata[0] == "MOf") {
    // استجابة فورية للمستخدم (يحس البوت رد على الضغطة فوراً)
    if (isset($update->callback_query->id)) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => '⏳ جاري تحميل طلباتك...', 'show_alert' => false]);
    }

    // معامل الفلتر والصفحة من الـcallback
    // MyOrders → all/1   |   MOf-{filter}-{page}
    $filter = 'all';
    $page   = 1;
    if ($exdata[0] == "MOf") {
        $filter = $exdata[1] ?? 'all';
        $page   = max(1, (int)($exdata[2] ?? 1));
    }

    $all_orders = getUserSpitOrders($id);

    // عدّادات
    $total     = count($all_orders);
    $count_p   = 0;
    $count_c   = 0;
    foreach ($all_orders as $o) {
        $g = spitStatusGroup($o['status'] ?? 1);
        if ($g === 'completed') $count_c++;
        elseif ($g === 'pending') $count_p++;
    }

    // فلترة
    $filtered = $all_orders;
    if ($filter === 'pending') {
        $filtered = array_filter($all_orders, function($o){ return spitStatusGroup($o['status'] ?? 1) === 'pending'; });
    } elseif ($filter === 'completed') {
        $filtered = array_filter($all_orders, function($o){ return spitStatusGroup($o['status'] ?? 1) === 'completed'; });
    }

    if ($total === 0) {
        bot('EditMessageText', [
            'chat_id'    => $chat_id,
            'message_id' => $message_id,
            'text'       => "📦 *طلباتي*\n\n_لا توجد لديك طلبات حتى الآن._\n\nابدأ أول طلب لك من قائمة الخدمات 🚀",
            'parse_mode' => 'MarkDown',
            'reply_markup' => json_encode(['inline_keyboard' => [
                [['text' => '↩️ رجوع', 'callback_data' => 'Record', 'style' => 'danger']]
            ]])
        ]);
        exit;
    }

    // ترتيب من الأحدث للأقدم
    krsort($filtered);

    // حساب الترقيم
    $per_page    = 10;
    $filt_total  = count($filtered);
    $total_pages = max(1, (int)ceil($filt_total / $per_page));
    if ($page > $total_pages) $page = $total_pages;
    $offset = ($page - 1) * $per_page;
    $shown  = array_slice($filtered, $offset, $per_page, true);

    $filter_label = $filter === 'pending' ? 'قيد التنفيذ ⏳' : ($filter === 'completed' ? 'المكتملة ✅' : 'الكل 📋');
    $text  = "📦 *طلباتي*\n\n";
    $text .= "📊 الإجمالي ┊ {$total}\n";
    $text .= "⏳ قيد التنفيذ ┊ {$count_p}\n";
    $text .= "✅ مكتملة ┊ {$count_c}\n\n";
    $text .= "_العرض: {$filter_label}_  ┊  _الصفحة {$page}/{$total_pages}_";

    // أزرار الفلترة (تعود للصفحة 1 عند تغيير الفلتر)
    $mk = function($label, $f) use ($filter) {
        return ['text' => ($filter === $f ? "🔘 " : "") . $label, 'callback_data' => "MOf-$f-1"];
    };
    $buttons = [[ $mk("الكل", 'all'), $mk("قيد التنفيذ", 'pending'), $mk("مكتملة", 'completed') ]];

    // قائمة الطلبات للصفحة الحالية
    if (empty($shown)) {
        $buttons[] = [['text' => "— لا طلبات في هذا التصنيف —", 'callback_data' => 'noaction']];
    } else {
        foreach ($shown as $idSend => $o) {
            $g = spitStatusGroup($o['status'] ?? 1);
            $dot = ($g === 'completed') ? '✅' : (($g === 'cancelled') ? '⛔️' : '⏳');
            $sname = $o['name'] ?? 'خدمة';
            if (function_exists('mb_substr') && mb_strlen($sname, 'UTF-8') > 22) {
                $sname = mb_substr($sname, 0, 22, 'UTF-8') . '…';
            }
            $buttons[] = [['text' => "{$dot} #{$idSend} • {$sname}", 'callback_data' => "OrdV-{$idSend}"]];
        }
    }

    // أزرار الترقيم (السابق/التالي) — تظهر فقط لو فيه أكثر من صفحة
    if ($total_pages > 1) {
        $nav = [];
        if ($page > 1) {
            $nav[] = ['text' => '◀️ السابق', 'callback_data' => "MOf-{$filter}-" . ($page - 1)];
        }
        $nav[] = ['text' => "📄 {$page}/{$total_pages}", 'callback_data' => 'noaction'];
        if ($page < $total_pages) {
            $nav[] = ['text' => 'التالي ▶️', 'callback_data' => "MOf-{$filter}-" . ($page + 1)];
        }
        $buttons[] = $nav;
    }

    $buttons[] = [['text' => '↩️ رجوع', 'callback_data' => 'Record', 'style' => 'danger']];

    bot('EditMessageText', [
        'chat_id'    => $chat_id,
        'message_id' => $message_id,
        'text'       => $text,
        'parse_mode' => 'MarkDown',
        'reply_markup' => json_encode(['inline_keyboard' => $buttons])
    ]);
    exit;
}

// عرض طلب واحد من قائمة طلباتي
if ($exdata[0] == "OrdV") {
    $idSend = $exdata[1] ?? '';
    $emil   = $ORDERALL[$idSend]['account'] ?? null;
    $ordK   = $ORDERALL[$idSend]['order']   ?? null;

    if (!$ordK || !$emil) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "❌ الطلب غير موجود.", 'show_alert' => true]);
        exit;
    }
    $sdata = jRead("EMILS/$emil/spit.json", true) ?: [];
    if (!isset($sdata['spit'][$ordK])) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "❌ الطلب غير موجود.", 'show_alert' => true]);
        exit;
    }
    $o = $sdata['spit'][$ordK];

    // التحقق أن الطلب لهذا المستخدم
    if (($o['chat-id'] ?? null) != $id) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "❌ غير مصرح.", 'show_alert' => true]);
        exit;
    }

    $statusLabel = spitStatusLabel($o['status'] ?? 1);
    $price       = $o['price'] ?? 0;
    $number      = $o['number'] ?? 0;
    $urls        = $o['urls'] ?? '';
    $name        = $o['name'] ?? 'خدمة';
    $smm_order   = $o['order'] ?? '—';
    $day         = $o['DAY'] ?? '—';

    $text  = "🧾 *تفاصيل الطلب*\n\n";
    $text .= "🛍 *الخدمة* ┊ {$name}\n";
    $text .= "🧾 *رقم الطلب* ┊ {$idSend}\n";
    $text .= "🆔 *معرف التنفيذ* ┊ {$smm_order}\n";
    $text .= "💰 *السعر* ┊ {$price} روبل\n";
    $text .= "🔗 *الرابط* ┊ {$urls}\n\n";
    $text .= "📊 *حالة الطلب*\n";
    $text .= "• *المطلوب* ┊ {$number}\n";
    $text .= "• *الحالة* ┊ {$statusLabel}\n";
    $text .= "📅 *التاريخ* ┊ {$day}";

    $buttons = [
        [['text' => "♻️ تحديث الطلب", 'callback_data' => "Bz-{$idSend}"]]
    ];
    // زر إعادة التعبئة يظهر فقط بعد اكتمال الطلب (status = 3)
    if ((int)($o['status'] ?? 1) === 3) {
        $buttons[] = [['text' => "🔄 إعادة تعبئة تلقائية", 'callback_data' => "Refill-{$idSend}"]];
    }
    $buttons[] = [['text' => "↩️ رجوع لطلباتي", 'callback_data' => "MyOrders", 'style' => 'danger']];

    bot('EditMessageText', [
        'chat_id'    => $chat_id,
        'message_id' => $message_id,
        'text'       => $text,
        'parse_mode' => 'MarkDown',
        'reply_markup' => json_encode(['inline_keyboard' => $buttons])
    ]);
    exit;
}

// إعادة التعبئة التلقائية (يحترم قيد 24 ساعة)
if ($exdata[0] == "Refill") {
    $idSend = $exdata[1] ?? '';
    $emil   = $ORDERALL[$idSend]['account'] ?? null;
    $ordK   = $ORDERALL[$idSend]['order']   ?? null;

    if (!$ordK || !$emil) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "❌ الطلب غير موجود.", 'show_alert' => true]);
        exit;
    }
    $sdata = jRead("EMILS/$emil/spit.json", true) ?: [];
    if (!isset($sdata['spit'][$ordK])) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "❌ الطلب غير موجود.", 'show_alert' => true]);
        exit;
    }
    $o = $sdata['spit'][$ordK];

    // التحقق أن الطلب لهذا المستخدم
    if (($o['chat-id'] ?? null) != $id) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "❌ غير مصرح.", 'show_alert' => true]);
        exit;
    }

    // ===== لا يُسمح بإعادة التعبئة إلا بعد اكتمال الطلب =====
    $order_status = (int)($o['status'] ?? 1);
    if ($order_status !== 3) {
        $label = function_exists('spitStatusLabel') ? spitStatusLabel($order_status) : 'قيد التنفيذ ⏳';
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⚠️ لا يمكنك إعادة التعبئة إلا بعد اكتمال الطلب.\n\nالحالة الحالية: {$label}\nحدّث الطلب أولاً للتأكد من اكتماله.",
            'show_alert' => true
        ]);
        exit;
    }

    // قيد الـ 24 ساعة — مع عرض الوقت المتبقّي بدقة (ساعات ودقائق)
    $order_time = isset($o['DAY']) ? strtotime($o['DAY']) : 0;
    if ($order_time > 0) {
        $seconds_left = ($order_time + 24 * 3600) - time();
        if ($seconds_left > 0) {
            $h = (int)floor($seconds_left / 3600);
            $m = (int)floor(($seconds_left % 3600) / 60);
            if ($h > 0 && $m > 0)      $remaining_txt = "{$h} ساعة و {$m} دقيقة";
            elseif ($h > 0)            $remaining_txt = "{$h} ساعة";
            else                       $remaining_txt = "{$m} دقيقة";
            bot('answercallbackquery', [
                'callback_query_id' => $update->callback_query->id,
                'text' => "⏳ لا يمكنك إعادة التعبئة إلا بعد مرور 24 ساعة على الطلب.\n\n⏱ المتبقّي على إتاحة الزر: {$remaining_txt}.",
                'show_alert' => true
            ]);
            exit;
        }
    }

    $smm_order_id = $o['order'] ?? null;
    if (empty($smm_order_id)) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "⚠️ هذا الطلب لا يحتوي على معرف صالح لإعادة التعبئة.", 'show_alert' => true]);
        exit;
    }

    // إرسال طلب refill للمزود
    $add_site = $o['add'] ?? null;
    $Location = $add_site !== null ? getSiteByNum($add_site) : null;
    if (!$Location) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "⚠️ تعذّر تحديد المزود.", 'show_alert' => true]);
        exit;
    }
    $ex_loc  = explode('.', $Location);
    $api_key = trim(@file_get_contents("data/api/{$ex_loc[0]}.txt"));

    // ===== هل توجد إعادة تعبئة سابقة لهذا الطلب؟ نتحقق من حالتها أولاً =====
    $existing_refill = $o['refill_id'] ?? null;
    if (!empty($existing_refill)) {
        $ch_s = curl_init("https://$Location/api/v2");
        curl_setopt($ch_s, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_s, CURLOPT_POST, 1);
        curl_setopt($ch_s, CURLOPT_POSTFIELDS, "key=" . urlencode($api_key) . "&action=refill_status&refill=" . urlencode($existing_refill));
        curl_setopt($ch_s, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch_s, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch_s, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch_s, CURLOPT_CONNECTTIMEOUT, 8);
        $raw_s  = curl_exec($ch_s);
        curl_close($ch_s);
        $resp_s = $raw_s ? json_decode($raw_s, true) : null;

        $rstatus = '';
        if (is_array($resp_s) && isset($resp_s['status'])) {
            $rstatus = strtolower(trim((string)$resp_s['status']));
        }

        // إعادة التعبئة مكتملة
        if ($rstatus === 'completed') {
            bot('answercallbackquery', [
                'callback_query_id' => $update->callback_query->id,
                'text' => "✅ تم اكتمال إعادة التعبئة بنجاح!\n\n🧾 الطلب #{$idSend}\n🆔 المعرف: {$existing_refill}",
                'show_alert' => true
            ]);
            exit;
        }

        // إعادة التعبئة مرفوضة/ملغاة فقط → نسمح بإرسال طلب جديد (نكمل للأسفل)
        $rejected_states = ['rejected', 'canceled', 'cancelled', 'error', 'declined', 'refused'];
        if (in_array($rstatus, $rejected_states, true)) {
            // نمسح المعرف القديم حتى لا نعلق عليه ونتابع لإرسال طلب جديد
            $sdata['spit'][$ordK]['refill_id'] = null;
            file_put_contents("EMILS/$emil/spit.json", json_encode($sdata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
        } else {
            // إعادة تعبئة قائمة (قيد التنفيذ/غير معروفة/تعذّر الجلب)
            // نحسب مرور 24 ساعة على آخر إعادة تعبئة:
            //  - إن انقضت 24 ساعة → نسمح بإعادة تعبئة جديدة (نكمل للأسفل ويُفتح الزر)
            //  - إن لم تنقضِ → نعرض الوقت المتبقّي + نسبة التنفيذ
            $last_refill_time = isset($o['refill_requested_at']) ? strtotime($o['refill_requested_at']) : 0;
            $refill_seconds_left = $last_refill_time > 0 ? (($last_refill_time + 24 * 3600) - time()) : 0;

            if ($last_refill_time > 0 && $refill_seconds_left <= 0) {
                // مضت 24 ساعة على آخر إعادة تعبئة → نسمح بطلب جديد
                // (لا exit — نكمل لإرسال طلب refill جديد بالأسفل)
            } else {
                // ما زالت ضمن نافذة الـ 24 ساعة → نعرض الوقت المتبقّي
                $start   = (int)($resp_s['start_count'] ?? 0);
                $remains = (int)($resp_s['remains'] ?? 0);
                $target  = (int)($o['number'] ?? 0);
                $pct = '';
                if ($target > 0 && isset($resp_s['remains']) && $remains >= 0) {
                    $done = max(0, $target - $remains);
                    $p = (int)round(($done / $target) * 100);
                    $p = max(0, min(100, $p));
                    $pct = "\n📊 النسبة: {$p}%";
                }

                // نص الوقت المتبقّي على فتح إعادة تعبئة جديدة
                $wait_txt = '';
                if ($refill_seconds_left > 0) {
                    $h = (int)floor($refill_seconds_left / 3600);
                    $m = (int)floor(($refill_seconds_left % 3600) / 60);
                    if ($h > 0 && $m > 0)      $rem_h = "{$h} ساعة و {$m} دقيقة";
                    elseif ($h > 0)            $rem_h = "{$h} ساعة";
                    else                       $rem_h = "{$m} دقيقة";
                    $wait_txt = "\n⏱ يمكنك طلب إعادة تعبئة جديدة بعد: {$rem_h}";
                }

                bot('answercallbackquery', [
                    'callback_query_id' => $update->callback_query->id,
                    'text' => "⏳ إعادة تعبئتك قيد التنفيذ\n\n🧾 الطلب #{$idSend}\n🆔 المعرف: {$existing_refill}{$pct}{$wait_txt}",
                    'show_alert' => true
                ]);
                exit;
            }
        }
    }

    // ===== إرسال طلب refill جديد للمزود =====
    $ch = curl_init("https://$Location/api/v2");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "key=" . urlencode($api_key) . "&action=refill&order=" . urlencode($smm_order_id));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
    $raw  = curl_exec($ch);
    curl_close($ch);
    $resp = $raw ? json_decode($raw, true) : null;

    // معالجة الرد
    $err_lower = '';
    if (is_array($resp) && isset($resp['error'])) {
        $err_lower = strtolower((string)$resp['error']);
    }

    // أخطاء تدل أن الخدمة لا تدعم إعادة التعبئة
    $no_support_keys = ['no refill', 'not refillable', 'refill not available', 'refill disabled', 'refill is disabled', 'disabled for this service', 'refill is not supported', 'incorrect action', 'unknown action', "service doesn't support", "doesn't support refill", 'refill not supported'];
    foreach ($no_support_keys as $k) {
        if ($err_lower !== '' && strpos($err_lower, $k) !== false) {
            bot('answercallbackquery', [
                'callback_query_id' => $update->callback_query->id,
                'text' => "❌ تعذّر التنفيذ\nهذه الخدمة ليس لديها إعادة تعبئة",
                'show_alert' => true
            ]);
            exit;
        }
    }
    // فحص إضافي: لو الرد عبارة مختصرة تحوي فقط "refill" بدون رقم → الخدمة لا تدعمها
    if ($err_lower !== '' && preg_match('/\brefill\b/i', $err_lower) && !isset($resp['refill'])) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "❌ تعذّر التنفيذ\nهذه الخدمة ليس لديها إعادة تعبئة",
            'show_alert' => true
        ]);
        exit;
    }

    if (is_array($resp) && isset($resp['error'])) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "❌ تعذّر التنفيذ\n" . $resp['error'], 'show_alert' => true]);
        exit;
    }
    if (is_array($resp) && isset($resp['refill'])) {
        $refill_id = $resp['refill'];
        $sdata['spit'][$ordK]['refill_id'] = $refill_id;
        $sdata['spit'][$ordK]['refill_requested_at'] = date('Y-m-d H:i:s');
        file_put_contents("EMILS/$emil/spit.json", json_encode($sdata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "✨ تم إرسال طلب إعادة التعبئة 🎯\n\n🧾 الطلب #{$idSend}\n🆔 المعرف: {$refill_id}",
            'show_alert' => true
        ]);
        exit;
    }
    $msg = $resp['message'] ?? ($resp['status'] ?? null);
    if ($msg) {
        $msg_low = strtolower(is_string($msg) ? $msg : json_encode($msg));
        foreach ($no_support_keys as $k) {
            if (strpos($msg_low, $k) !== false) {
                bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "❌ تعذّر التنفيذ\nهذه الخدمة ليس لديها إعادة تعبئة", 'show_alert' => true]);
                exit;
            }
        }
        // فحص إضافي: نفس المنطق في message
        if (preg_match('/\brefill\b/i', $msg_low)) {
            bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "❌ تعذّر التنفيذ\nهذه الخدمة ليس لديها إعادة تعبئة", 'show_alert' => true]);
            exit;
        }
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "ℹ️ رد المزود:\n" . (is_string($msg) ? $msg : json_encode($msg, JSON_UNESCAPED_UNICODE)), 'show_alert' => true]);
        exit;
    }
    bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "❌ تعذّر التنفيذ\nهذه الخدمة ليس لديها إعادة تعبئة", 'show_alert' => true]);
    exit;
}

// زر "noaction" (للأزرار العنوانية في طلباتي)
if ($exdata[0] == "noaction") {
    bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => '', 'show_alert' => false]);
    exit;
}

// ===================================
//  تحديث حالة الطلب
// ===================================
if ($exdata[0] == "Bz") {
    $idSend = $exdata[1];
    $emil   = $ORDERALL[$idSend]['account'] ?? null;
    $order  = $ORDERALL[$idSend]['order']   ?? null;

    if (!$order || !isset($BUYSSPIT['spit'][$order])) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "☑️ - ايدي الطلب غير صحيح 🙋🏻", 'show_alert' => true]);
        exit;
    }

    $spit_entry = $BUYSSPIT['spit'][$order];
    $number     = $spit_entry['number'];
    $zero       = $spit_entry['zero'];
    $urls       = $spit_entry['urls'];
    $orders     = $spit_entry['order'];
    $num        = $spit_entry['num'];
    $type       = $spit_entry['type'];
    $name       = $spit_entry['name'];
    $price      = $spit_entry['price'];
    $add_site   = $spit_entry['add'];
    $Location   = getSiteByNum($add_site);
    $ex_loc     = explode(".", $Location);
    $api_key    = trim(@file_get_contents("data/api/{$ex_loc[0]}.txt"));
    $x          = getLinkLabel($num, $type);

    $ch_s = curl_init("https://$Location/api/v2"); curl_setopt($ch_s, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch_s, CURLOPT_POST, 1); curl_setopt($ch_s, CURLOPT_POSTFIELDS, "key=" . urlencode($api_key) . "&action=status&order=" . urlencode($orders)); curl_setopt($ch_s, CURLOPT_SSL_VERIFYPEER, 0); curl_setopt($ch_s, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch_s, CURLOPT_FOLLOWLOCATION, true); curl_setopt($ch_s, CURLOPT_TIMEOUT, 10); curl_setopt($ch_s, CURLOPT_CONNECTTIMEOUT, 5); $api_s_raw = curl_exec($ch_s); curl_close($ch_s); $api = $api_s_raw ? json_decode($api_s_raw, true) : null;
    $error     = $api["error"]       ?? null;
    $status    = $api["status"]      ?? null;
    $arstat    = str_replace(
        ["Pending","In progress","Completed","Partial","Processing","Canceled"],
        ["قيد الانتظار","في تَقَدم","مكتمل","جزئي","يعالج","ألغيت"],
        $status
    );
    $start_count = $api["start_count"] ?? 0;
    $remains     = $api["remains"]     ?? 0;

    if ($error !== null || $EM != $emil) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "☑️ - ايدي الطلب غير صحيح 🙋🏻\n$error .", 'show_alert' => true]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($status == "Completed") {
        bot('EditMessageText', [
            'chat_id'                  => $chat_id,
            'message_id'               => $message_id,
            'text'                     => "✅ *- تم اكتمال طلب الرشق بنجاح* ✔️\n\n📡 *- $x :* [$urls]\n⚜ *- نوع الرشق * : $name\n⚜ *- عدد الاعضاء* : $number\n🌀 *-حالة الطلب* : $arstat\n\n⚜ *- نتمنى لكم تجربه ممتعة* ❤️‍🔥",
            'parse_mode'               => "MarkDown",
            'disable_web_page_preview' => true,
            'reply_markup'             => json_encode(['inline_keyboard' => [
                [['text' => "🔄 إعادة تعبئة تلقائية", 'callback_data' => "Refill-$idSend"]],
                [['text' => "- رجوع 🔜", 'callback_data' => "spit", 'style' => 'danger']]
            ]]),
        ]);
        $BUYSSPIT['spit'][$order]['status'] = 3;
        file_put_contents("EMILS/$EM/spit.json", json_encode($BUYSSPIT, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($status == "Canceled") {
        bot('EditMessageText', [
            'chat_id'                  => $chat_id,
            'message_id'               => $message_id,
            'text'                     => "❌ *- تم الغاء طلب الرشق المقدم*\n\n📡 *- $x :* [$urls]\n⚜ *- نوع الرشق * : $name\n⚜ *- عدد الاعضاء* : $number\n🌀 *- حالة الطلب* : $arstat\n💰 *- قيمة العملية* : $price\n\n⚜ *- تم ارجاء بقية الروبل تلقائي الى حسابك*",
            'parse_mode'               => "MarkDown",
            'disable_web_page_preview' => true,
            'reply_markup'             => json_encode(['inline_keyboard' => [[['text' => "- رجوع 🔜", 'callback_data' => "spit", 'style' => 'danger']]]]),
        ]);
        $BUYSSPIT['spit'][$order]['status'] = -1;
        file_put_contents("EMILS/$EM/spit.json", json_encode($BUYSSPIT, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
        $points = (float)(@file_get_contents("EMILS/$EM/points.txt") ?: 0);
        file_put_contents("EMILS/$EM/points.txt", $points + $price, LOCK_EX);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "✅ تم تحديث الطلب", 'show_alert' => false]);
    $refresh_text  = "📋 *تفاصيل طلبك*\n\n";
    $refresh_text .= "🛍 الخدمة ┊ {$name}\n";
    $refresh_text .= "🧾 رقم الطلب ┊ {$idSend}\n";
    $refresh_text .= "🆔 معرف التنفيذ ┊ {$orders}\n";
    $refresh_text .= "💸 السعر الكلي ┊ ₽ {$price}\n";
    $refresh_text .= "🔗 الرابط ┊ {$urls}\n\n";
    $refresh_text .= "📊 *حالة الطلب*\n";
    $refresh_text .= "• المطلوب ┊ {$number}\n";
    $refresh_text .= "• المكتمل ┊ {$start_count}\n";
    $refresh_text .= "• المتبقي ┊ {$remains}\n";
    $refresh_text .= "• الحالة ┊ {$arstat}\n\n";
    $refresh_text .= "_اضغط زر ♻️ التحديث بالأسفل لمتابعة الحالة._";

    bot('EditMessageText', [
        'chat_id'                  => $chat_id,
        'message_id'               => $message_id,
        'text'                     => $refresh_text,
        'parse_mode'               => "MarkDown",
        'disable_web_page_preview' => true,
        'reply_markup'             => json_encode(['inline_keyboard' => [
            [['text' => "- تحديث ✅", 'callback_data' => "Bz-$idSend", 'style' => 'primary']],
        ]]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// ===================================
//  لوحة تحكم الأدمن
// ===================================
if ($is_admin) {

    if ($exdata[0] == "Splash_settings") {
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "- اهلا بك مطوري في قسم الرشق الخاص بك",
            'parse_mode'   => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "🛒 • اضافة خدمه رشق يدوي • 🛒",     'callback_data' => "spitadd", 'style' => 'primary']],
                    [['text' => "⛔️ • حذف خدمة رشق • ⛔️",           'callback_data' => "delspit", 'style' => 'primary']],
                    [['text' => '🤖 جلب خدمات تلقائي من API ✨',       'callback_data' => 'rashq_fetch_menu', 'style' => 'primary']],
                    [['text' => '🗂 إدارة أقسام الرشق',                'callback_data' => 'rashq_sections_menu', 'style' => 'primary']],
                    [['text' => '📝 إدارة الأقسام الفرعية',            'callback_data' => 'sub_types_menu', 'style' => 'primary']],
                    [['text' => '➕ رفع API لموقع معين',               'callback_data' => 'addspit', 'style' => 'danger']],
                    [['text' => '✖️ حذف API لموقع معين',              'callback_data' => 'delspitapi', 'style' => 'danger']],
                    [['text' => '🎭︙إدارة ملصقات الأزرار',            'callback_data' => 'emoji_admin', 'style' => 'success']],
                    [['text' => '🔗 • لوحه تحكم قسم الارقام • 🔗',    'callback_data' => 'c', 'style' => 'primary']],
                ],
            ]),
        ]);
        if ($array) @unlink("data/id/$id/$array.txt");
        @unlink("data/id/$id/step.txt");
        exit;
    }

    // ── معالج /start?ID (خاص بخدمة الأرقام المرتبطة) ────────
    if ($ex_text[0] == '/start' && strpos($ex_text[1] ?? '', "ID") !== false) {
        $idSend      = str_replace('ID', '', $ex_text[1]);
        $EEM         = $ORDERALL[$idSend]['account'];
        $order       = $ORDERALL[$idSend]['order'];
        $BUYSNUM_S   = jRead("EMILS/$EEM/spit.json") ?? [];
        $zero        = $BUYSNUM_S['spit'][$order]['zero'];
        $price       = $buy['spit'][$zero]['price'];
        $country     = $buy['spit'][$zero]['country'];
        $add         = $buy['spit'][$zero]['add'];
        $operator    = $buy['spit'][$zero]['operator'];
        $app         = $buy['spit'][$zero]['app'];
        $site        = $buy['spit'][$zero]['site'];
        $maxPrice    = $buy['spit'][$zero]['maxPrice'];
        $status_zero = $buy['spit'][$zero];
        $name        = $_co['country'][$country];
        $idSend      = $orderall;
        if ($add >= 21 && $add <= 25)      { $add = 21; }
        elseif ($add >= 26 && $add <= 30)  { $add = 26; }
        if ($add == 1)                     { $back = "Wo"; }
        elseif ($add >= 21 && $add <= 30)  { $back = "worldwide"; }
        elseif ($add >= 31 && $add <= 40)  { $back = "Buynum"; }
        else                               { $back = "Ms-$add-$country"; }
        if ($app == "wa")      { $wa = "☑️ - رؤية حالة الرقم في واتسأب. ↖️"; }
        elseif ($app == "tg")  { $tg = "☑️ - رؤية حالة الرقم في تيليجرام. ↖️"; }
        if (time() - ($BUYSNUM['spit'][$Detector]['times'] ?? 0) <= 2) {
            @unlink("data/id/$id/step.txt");
            exit;
        }
        $_ctx1 = stream_context_create(['http' => ['timeout' => 8]]);
        $api      = json_decode(@file_get_contents("https://" . $_SERVER['SERVER_NAME'] . "/$bot/api-sites.php?action=getNum&site=$site&country=$country&app=$app&operator=$operator&maxPrice=$maxPrice", false, $_ctx1), true);
        $num2nd   = ($site == "2ndline") ? ($api['num2nd'] ?? null) : null;
        $status   = $api['status']   ?? null;
        $spit_num = $api['spit']     ?? null;
        $idspit   = $api['idspit']   ?? null;
        $time_r   = $api['time']     ?? 0;
        $Location = $api['Location'] ?? null;
        // حساب الوقت
        $z    = $time_r / 60;
        $ex1  = explode(".", $z);
        $z2   = "0." . ($ex1[1] ?? '0');
        $ex2  = explode(".", $z2 * 60);
        $start_time = (mb_strlen($ex2[0]) > 1) ? "$ex1[0]:$ex2[0]" : "$ex1[0]:0$ex2[0]";
        $k = ($ex1[0] == null || $ex1[0] == '') ? "ثانية" : "دقيقة";
        if ($status_zero == null) {
            @unlink("data/id/$id/step.txt");
        } elseif ($status != "200") {
            bot('SendMessage', [
                'chat_id'      => $chat_id,
                'text'         => "☑️ *- عذرا عزيزي ليس متوفر حاليا هذه الدولة.\n💠 - قم بتحربة أي سيرفر آخر.*",
                'parse_mode'   => "MarkDown",
                'reply_markup' => json_encode(['inline_keyboard' => [[['text' => '- رجوع 🔜', 'callback_data' => "$back", 'style' => 'primary']]]]),
            ]);
            @unlink("data/id/$id/step.txt");
            exit;
        } else {
            if ($site == "2ndline" && $num2nd == null) {
                for ($ii = 0; $ii < 10; $ii++) {
                    sleep(1);
                    $_ctx2 = stream_context_create(['http' => ['timeout' => 8]]);
                    $api      = json_decode(@file_get_contents("https://" . $_SERVER['SERVER_NAME'] . "/$bot/api-sites.php?action=getNum2nd&site=$site&country=$country&id=$idds", false, $_ctx2), true);
                    $status   = $api['status']   ?? null;
                    $num2nd   = $api['num2nd']   ?? null;
                    $spit_num = $api['spit']     ?? null;
                    $idspit   = $api['idspit']   ?? null;
                    $Location = $api['Location'] ?? null;
                    if ($num2nd == null && $ii == 9) {
                        bot('EditMessageText', [
                            'chat_id'      => $chat_id,
                            'message_id'   => $message_id,
                            'text'         => "☑️ *- عذرا عزيزي ليس متوفر حاليا هذه الدولة.\n💠 - قم بتحربة أي سيرفر آخر.*",
                            'parse_mode'   => "MarkDown",
                            'reply_markup' => json_encode(['inline_keyboard' => [[['text' => '- رجوع 🔜', 'callback_data' => "$back", 'style' => 'primary']]]]),
                        ]);
                        exit;
                    } elseif ($num2nd == null) {
                        $seq_num_n1 = (int)(@file_get_contents("data/pay_order_seq.txt") ?: 0) + 1; file_put_contents("data/pay_order_seq.txt", $seq_num_n1, LOCK_EX);
                        notifySimChannel($sim, $name, $ID ?? '', $APP ?? '', $Type ?? '', $number ?? 0, $nid, $price, $idurls ?? '', $me, $seq_num_n1);
                        notifyPayChannel($PAY, $APP ?? '', $number ?? 0, $Balance, $name, $seq_num_n1, $order, $urls ?? '', $EM, $price, $Location ?? '');
                        file_put_contents("data/id/$id/restriction.txt", "$ordermy");
                        $times = time();
                        $BUYSNUM['spit'][$ordermy] = [
                            'idSend'  => $idSend, 'phone'  => $spit_num, 'sms'    => [[['code' => null]]],
                            'status'  => 1,       'operator'=> $operator, 'app'    => $app,
                            'add'     => $add,    'price'   => $price,    'id'     => $idnums ?? null,
                            'site'    => $site,   'zero'    => $zero,     'country'=> $country,
                            'idspit'  => $idspit, 'message' => null,      'type'   => "direct",
                            'finish'  => $time_r, 'times'   => $times,    'chat-id'=> $id, 'DAY' => $DAY,
                        ];
                        file_put_contents("EMILS/$EM/spit.json", json_encode($BUYSNUM, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
                        $ORDERALL[$idSend]['account'] = $EM;
                        $ORDERALL[$idSend]['order']   = $ordermy;
                        $ORDERALL[$idSend]['status']  = 1;
                        file_put_contents('BUY/Orderall.json', json_encode($ORDERALL, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
                        @unlink("data/id/$id/step.txt");
                        exit;
                    }
                }
            } else {
                $confirm_text2  = "✅ *تم استلام طلبك بنجاح!*\n\n";
                $confirm_text2 .= "🛍 الخدمة ┊ {$name}\n";
                $confirm_text2 .= "📦 الكمية ┊ {$number}\n";
                $confirm_text2 .= "💸 السعر الكلي ┊ ₽ {$price}\n";
                $confirm_text2 .= "🧾 رقم الطلب ┊ {$idSend}\n";
                $confirm_text2 .= "🆔 معرف التنفيذ ┊ {$order}\n";
                $confirm_text2 .= "🔗 الرابط ┊ {$urls}\n\n";
                $confirm_text2 .= "📊 *حالة الطلب*\n";
                $confirm_text2 .= "• المطلوب ┊ {$number}\n";
                $confirm_text2 .= "• المكتمل ┊ 0\n";
                $confirm_text2 .= "• المتبقي ┊ {$number}\n";
                $confirm_text2 .= "• الحالة ┊ في الانتظار ⏳\n\n";
                $confirm_text2 .= "_اضغط زر ♻️ التحديث بالأسفل لمتابعة حالة طلبك._";
                $get = bot('SendMessage', [
                    'chat_id'                  => $chat_id,
                    'text'                     => $confirm_text2,
                    'parse_mode'               => "MarkDown",
                    'disable_web_page_preview' => true,
                    'reply_markup'             => json_encode(['inline_keyboard' => [
                        [['text' => '- تحديث ✅', 'callback_data' => "Bz-$idSend", 'style' => 'primary']],
                    ]]),
                ]);
                $seq_num_n2 = (int)(@file_get_contents("data/pay_order_seq.txt") ?: 0) + 1; file_put_contents("data/pay_order_seq.txt", $seq_num_n2, LOCK_EX);
                notifySimChannel($sim, $name, $ID ?? '', $APP ?? '', $Type ?? '', $number ?? 0, $nid, $price, $idurls ?? '', $me, $seq_num_n2);
                notifyPayChannel($PAY, $APP ?? '', $number ?? 0, $Balance, $name, $seq_num_n2, $order, $urls ?? '', $EM, $price, $Location ?? '');
                file_put_contents("data/id/$id/restriction.txt", "$ordermy");
                $times   = time();
                $get_mid = $get->result->message_id ?? null;
                $BUYSNUM['spit'][$ordermy] = [
                    'idSend'  => $idSend,   'phone'   => $spit_num, 'sms'     => [[['code' => null]]],
                    'status'  => 1,         'operator'=> $operator,  'app'     => $app,
                    'add'     => $add,      'price'   => $price,     'id'      => $idnums ?? null,
                    'site'    => $site,     'zero'    => $zero,      'country' => $country,
                    'idspit'  => $idspit,   'message' => $get_mid,   'type'    => "direct",
                    'finish'  => $time_r,   'times'   => $times,     'chat-id' => $id, 'DAY' => $DAY,
                ];
                file_put_contents("EMILS/$EM/spit.json", json_encode($BUYSNUM, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
                $ORDERALL[$idSend]['account'] = $EM;
                $ORDERALL[$idSend]['order']   = $ordermy;
                $ORDERALL[$idSend]['status']  = 1;
                file_put_contents('BUY/Orderall.json', json_encode($ORDERALL, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
                @unlink("data/id/$id/step.txt");
                exit;
            }
        }
    }

    // ── إضافة سيرفر يدوي ────────────────────────────────────
    if ($data == "spitadd") {
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "- قم ب إختار المورد الذي تود إضافة السيرفر منة إلى البوت",
            'parse_mode'   => "MarkDown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => 'smmtigers.com', 'callback_data' => 'Sj-1', 'style' => 'danger']],
                    [['text' => 'smmxstar.com',  'callback_data' => 'Sj-2', 'style' => 'danger']],
                    [['text' => 'smmparty.com',  'callback_data' => 'Sj-3', 'style' => 'danger']],
                    [['text' => 'fast70.com',     'callback_data' => 'Sj-4', 'style' => 'danger']],
                    [['text' => '- رجوع 🔜',     'callback_data' => 'Splash_settings', 'style' => 'danger']],
                ],
            ]),
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($exdata[0] == "Sj") {
        $add  = $exdata[1];
        $site = getSiteByNum($add);
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "- الآن قم ب إختيار التطبيق المتوفرة في المورد $site",
            'parse_mode'   => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "• 👥رفع متابعين تليجرام 🔥 •",  'callback_data' => "Sk-$add-1", 'style' => 'primary']],
                    [['text' => "•🏆 رفع متابعين انستغرام ☄•",   'callback_data' => "Sk-$add-2", 'style' => 'primary']],
                    [['text' => "•👥 رفع متابعين تيك توك ⭐️ •", 'callback_data' => "Sk-$add-3", 'style' => 'primary']],
                    [['text' => "•🌀 رفع متابعين فيس☑️•",        'callback_data' => "Sk-$add-4", 'style' => 'primary']],
                    [['text' => "• رفع تطبيق تويتر 💫",          'callback_data' => "Sk-$add-5", 'style' => 'primary']],
                    [['text' => "• رفع تطبيق وتس",               'callback_data' => "Sk-$add-6", 'style' => 'primary']],
                    [['text' => "• رفع تطبيق يوتيوب",            'callback_data' => "Sk-$add-7", 'style' => 'primary']],
                    [['text' => "• رفع تطبيق كواي",              'callback_data' => "Sk-$add-8", 'style' => 'primary']],
                    [['text' => "• رفع تطبيق سناب شات",          'callback_data' => "Sk-$add-9", 'style' => 'primary']],
                    [['text' => "•🎁 رفع خدمات مجانية 🪁",       'callback_data' => "Sk-$add-10", 'style' => 'primary']],
                    [['text' => '- رجوع 🔜',                     'callback_data' => 'spitadd', 'style' => 'success']],
                ],
            ]),
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($exdata[0] == "Sk") {
        $add  = $exdata[1];
        $num  = $exdata[2];
        $site = getSiteByNum($add);
        $APP  = getAppName($num);
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "- الآن قم ب إختيار القسم الذي تريدة\n\n- التطبيق: $APP\n- الموقع: $site",
            'parse_mode'   => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "➕ - متابعين 🫂",          'callback_data' => "Sg-$add-$num-1", 'style' => 'primary']],
                    [['text' => "➕ - مشاهدات 👁️",          'callback_data' => "Sg-$add-$num-2", 'style' => 'primary']],
                    [['text' => "➕ - لايكات 👍🏻",           'callback_data' => "Sg-$add-$num-3", 'style' => 'primary']],
                    [['text' => "➕️ - اشتراك بريميوم 🔧",   'callback_data' => "Sg-$add-$num-4", 'style' => 'primary']],
                    [['text' => '•🚀 شحن عملات تك توك ♻️•', 'callback_data' => "Sg-$add-$num-5", 'style' => 'primary']],
                    [['text' => "➕ - استطلاع راي",          'callback_data' => "Sg-$add-$num-6", 'style' => 'primary']],
                    [['text' => '- رجوع 🔜',                 'callback_data' => 'spitadd', 'style' => 'success']],
                ],
            ]),
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($exdata[0] == "Sg") {
        $add  = $exdata[1];
        $num  = $exdata[2];
        $type = $exdata[3];
        $site = getSiteByNum($add);
        $APP  = getAppName($num);
        $Type = str_replace(["1","2","3"], ["رفع متابعين 👥","رفع مشاهدات 👁","رفع لايكات 👍"], $type);
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "- التطبيق: $APP\n- الموقع: $site\n- النوع: $Type\n\n⬇️ *- أرسل أيدي القسم الذي تود إضافتة*",
            'parse_mode'   => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [[['text' => '- رجوع 🔜', 'callback_data' => "Sj-$add", 'style' => 'primary']]],
            ]),
        ]);
        file_put_contents("data/id/$id/step.txt", "Rj1|$add|$num|$type");
        exit;
    }

    if ($text && $text != '/start ' && $exstep[0] == 'Rj1') {
        $add     = $exstep[1];
        $num     = $exstep[2];
        $type    = $exstep[3];
        $ID      = $text;
        $site = getSiteByNum($add);
        $Section = getAppName($num);
        $Type    = str_replace(["1","2","3"], ["رفع متابعين 👥","رفع مشاهدات 👁","رفع لايكات 👍"], $type);
        bot('SendMessage', [
            'chat_id'             => $chat_id,
            'text'                => "☑️ - القسم: *$Section*\n🌐 - الموقع: *$site*\n🛎 - النوع: *$Type*\n🅿️ - أيدي القسم: *$ID*\n\n⬇️ *- أرسل ب الشكل التالي:\n1⃣ - إسم القسم.\n2⃣ - جودة القسم. (خارقة،سريعة،بطيئة)\n3⃣ - الضمان.\n4⃣ - النزول. (0،10،80،...)\n5⃣ - شرح القسم.*",
            'parse_mode'          => "MarkDown",
            'reply_to_message_id' => $message_id,
            'reply_markup'        => json_encode(['inline_keyboard' => [[['text' => '- رجوع 🔜', 'callback_data' => "Sj-$add", 'style' => 'primary']]]]),
        ]);
        file_put_contents("data/id/$id/step.txt", "Rj2|$add|$num|$type|$ID");
        exit;
    }

    if ($text && $text != '/start ' && $exstep[0] == 'Rj2') {
        $add      = $exstep[1];
        $num      = $exstep[2];
        $type     = $exstep[3];
        $ID       = $exstep[4];
        $name     = $extext[0];
        $quality  = $extext[1];
        $security = $extext[2];
        $get_off  = $extext[3];
        $explained= $extext[4];
        $taxt     = "$name|$quality|$security|$get_off|$explained";
        $site = getSiteByNum($add);
        $Section  = getAppName($num);
        $Type     = str_replace(["1","2","3"], ["رفع متابعين 👥","رفع مشاهدات 👁","رفع لايكات 👍"], $type);
        bot('SendMessage', [
            'chat_id'             => $chat_id,
            'text'                => "☑️ - القسم: *$Section*\n🌐 - الموقع: *$site*\n🛎 - النوع: *$Type*\n🅿️ - أيدي القسم: *$ID*\n💠 - إسم القسم: *$name*\n\n⬇️* - أرسل ب الشكل التالي:\n1⃣ - السعر بالروبل لكل 1000 (₽).\n2⃣ - السرعة. (100k في اليوم)...\n3⃣ - الوقت. (10 دقائق)...\n4⃣ - حد الأدنى.\n5⃣ - حد الأعلى.*",
            'parse_mode'          => "MarkDown",
            'reply_to_message_id' => $message_id,
            'reply_markup'        => json_encode(['inline_keyboard' => [[['text' => '- رجوع 🔜', 'callback_data' => "Sj-$add", 'style' => 'primary']]]]),
        ]);
        file_put_contents("data/id/$id/step.txt", "Rj3|$add|$num|$type|$ID|$taxt");
        exit;
    }

    if ($text && $text != '/start ' && $exstep[0] == 'Rj3') {
        $add        = $exstep[1];
        $num        = $exstep[2];
        $type       = $exstep[3];
        $ID         = $exstep[4];
        $name       = $exstep[5];
        $quality    = $exstep[6];
        $security   = $exstep[7];
        $get_off    = $exstep[8];
        $explained  = $exstep[9];
        $price      = $extext[0];
        $speed      = $extext[1];
        $start_time = $extext[2];
        $minimum    = $extext[3];
        $maximum    = $extext[4];
        // السعر يُحفظ بالروبل - إذا أدخل الأدمن رقماً صغيراً جداً (< 1) يعني دولار، حوّله
        $price_rub = is_numeric($price) ? (float)$price : 0;
        if ($price_rub > 0 && $price_rub < 1) {
            $price_rub = round($price_rub * 30, 4); // تحويل دولار → روبل
        }
        $price_rub = round($price_rub, 4);
        $taxt       = "$add|$num|$type|$ID|$name|$quality|$security|$get_off|$explained|$price_rub|$speed|$start_time|$minimum|$maximum";
        $array      = substr(str_shuffle("0123456789"), 0 - 10);
        $site = getSiteByNum($add);
        $Section    = getAppName($num);
        $Type       = str_replace(["1","2","3"], ["رفع متابعين 👥","رفع مشاهدات 👁","رفع لايكات 👍"], $type);
        bot('SendMessage', [
            'chat_id'             => $chat_id,
            'text'                => "☑️ - القسم: *$Section*\n🌐 - الموقع: *$site*\n🛎 - النوع: *$Type*\n🅿️ - أيدي القسم: *$ID*\n\n1⃣ - إسم القسم: *$name*\n2⃣ - السعر: *₽ $price_rub / 1000*\n3⃣ - جودة القسم: *$quality*\n4⃣ - السرعة: *$speed*\n5⃣ - النزول: *$get_off*\n6⃣ - الضمان: *$security*\n7⃣ - الوقت: *$start_time*\n8⃣ - حد الأدنى: *$minimum*\n9⃣ - حد الأعلى: *$maximum*\n🔟 - شرح القسم: *$explained*",
            'parse_mode'          => "MarkDown",
            'reply_to_message_id' => $message_id,
            'reply_markup'        => json_encode([
                'inline_keyboard' => [
                    [['text' => '☑️ - إضافة القسم', 'callback_data' => "Rk-$array", 'style' => 'primary']],
                    [['text' => '- رجوع 🔜',          'callback_data' => "Splash_settings-$array", 'style' => 'primary']],
                ],
            ]),
        ]);
        file_put_contents("data/id/$id/$array.txt", "$taxt");
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($exdata[0] == "Rk") {
        $array      = $exdata[1];
        $raw_rk     = @file_get_contents("data/id/$id/$array.txt");
        if (!$raw_rk) {
            bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "⚠️ انتهت الجلسة، أعد العملية.", 'show_alert' => true]);
            exit;
        }
        $exp        = explode("|", $raw_rk);
        $add        = $exp[0];
        $num        = $exp[1];
        $type       = $exp[2];
        $ID         = $exp[3];
        $name       = $exp[4];
        $quality    = $exp[5];
        $security   = $exp[6];
        $get_off    = $exp[7];
        $explained  = $exp[8];
        $price      = $exp[9];
        $speed      = $exp[10];
        $start_time = $exp[11];
        $minimum    = $exp[12];
        $maximum    = $exp[13];
        // ضمان أن السعر مخزون بالروبل
        $price = is_numeric($price) ? round((float)$price, 4) : 0;
        $site = getSiteByNum($add);
        $Section    = getAppName($num);
        $Type       = str_replace(["1","2","3"], ["رفع متابعين 👥","رفع مشاهدات 👁","رفع لايكات 👍"], $type);
        $code       = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0 - 14);

        bot('EditMessageText', [
            'chat_id'             => $chat_id,
            'message_id'          => $message_id,
            'text'                => "☑️ - تم إضافة قسم الرفع للأيدي *$ID*\n🌐 - الموقع: *$site*\n🛎 - النوع: *$Type*\n💠 - الإسم: *$name*",
            'parse_mode'          => "MarkDown",
            'reply_to_message_id' => $message_id,
            'reply_markup'        => json_encode([
                'inline_keyboard' => [[['text' => '- رجوع 🔜', 'callback_data' => "Splash_settings-$array", 'style' => 'primary']]],
            ]),
        ]);

        $increase["idplus"][$code] = [
            'add'        => $add,
            'num'        => $num,
            'type'       => $type,
            'ID'         => $ID,
            'name'       => $name,
            'quality'    => $quality,
            'security'   => $security,
            'get_off'    => $get_off,
            'explained'  => $explained,
            'Type'       => $Type,
            'price'      => $price,
            'speed'      => $speed,
            'start_time' => $start_time,
            'minimum'    => $minimum,
            'maximum'    => $maximum,
        ];
        file_put_contents('data/increase.json', json_encode($increase, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
        @unlink("data/id/$id/$array.txt");
        @unlink("data/id/$id/step.txt");
        exit;
    }

    // ── حذف خدمة من قائمة الحذف ────────────────────────────
    if ($data == "delspit") {
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "- الآن قم ب إختيار التطبيق الذي تود حذف السيرفر منه",
            'parse_mode'   => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "•👥 رشق متابعين تليجرام 🔥•",    'callback_data' => "Dt-1", 'style' => 'primary']],
                    [['text' => "•🏆 رشق متابعين انستغرام ☄•",    'callback_data' => "Dt-2", 'style' => 'primary']],
                    [['text' => "•👥 رشق متابعين تيك توك ⭐️ •",  'callback_data' => "Dt-3", 'style' => 'primary']],
                    [['text' => "•🌀 رشق متابعين فيس☑️•",          'callback_data' => "Dt-4", 'style' => 'primary']],
                    [['text' => "•🐥 رشق تطبيق تويتر 💫",         'callback_data' => "Dt-5", 'style' => 'primary']],
                    [['text' => "رشق واتساب انسوس",               'callback_data' => "Dt-6", 'style' => 'primary']],
                    [['text' => "•🎁 رشق يوتيوب ",                 'callback_data' => "Dt-7", 'style' => 'primary']],
                    [['text' => "رشق كواي",                        'callback_data' => "Dt-10", 'style' => 'primary']],
                    [['text' => "رشق سناب شات",                   'callback_data' => "Dt-0", 'style' => 'primary']],
                    [['text' => "•🎁 رشق خدمات مجانية",            'callback_data' => "Dt-8", 'style' => 'primary']],
                    [['text' => '- رجوع 🔜',                      'callback_data' => 'Splash_settings', 'style' => 'danger']],
                ],
            ]),
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($exdata[0] == "Dt") {
        $num  = $exdata[1];
        $APP  = getAppName($num);
        $key  = ['inline_keyboard' => [[['text' => '☑️ اسم الرشق وسعر العضو 💰', 'callback_data' => 'no', 'style' => 'primary']]]];
        $name = null;
        foreach ($increase["idplus"] as $zero => $svc) {
            if ($svc['num'] == $num) {
                $name  = $svc['name'];
                $price = round($svc['price'] / 1000, 4);
                $key['inline_keyboard'][] = [['text' => "$name [$price ₽]", 'callback_data' => "Dg-$num-1", 'style' => 'primary']];
            }
        }
        $key['inline_keyboard'][] = [['text' => '- رجوع 🔜', 'callback_data' => 'delspit', 'style' => 'danger']];
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "- أختر القسم الذي تود حذف منه سيرفر من تطبيق $APP",
            'parse_mode'   => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "➕ - متابعين 🫂",          'callback_data' => "Dg-$num-1", 'style' => 'primary']],
                    [['text' => "➕ - مشاهدات 👁️",          'callback_data' => "Dg-$num-2", 'style' => 'primary']],
                    [['text' => "➕ - لايكات 👍🏻",           'callback_data' => "Dg-$num-3", 'style' => 'primary']],
                    [['text' => "➕ - اشتراك بريميوم 🔧",   'callback_data' => "Dg-$num-4", 'style' => 'primary']],
                    [['text' => '•🚀 شحن عملات تك توك ♻️•', 'callback_data' => "Dg-$num-5", 'style' => 'primary']],
                    [['text' => "➕ - استطلاع راي ",          'callback_data' => "Dg-$num-6", 'style' => 'primary']],
                    [['text' => '- رجوع 🔜',                 'callback_data' => 'delspit', 'style' => 'danger']],
                ],
            ]),
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($exdata[0] == "Dg") {
        $nums = $exdata[1];
        $type = $exdata[2];
        $APP  = getAppName($nums);
        $key  = ['inline_keyboard' => [[['text' => '☑️ اسم الرشق وسعر العضو 💰', 'callback_data' => 'no', 'style' => 'primary']]]];
        $name = null;
        foreach ($increase["idplus"] as $zero => $svc) {
            if ($svc['num'] == $nums && $svc['type'] == $type) {
                $name  = $svc['name'];
                $price = round($svc['price'] / 1000, 4);
                $key['inline_keyboard'][] = [['text' => "$name [$price ₽]", 'callback_data' => "delrb-$zero", 'style' => 'primary']];
            }
        }
        $key['inline_keyboard'][] = [['text' => '- رجوع 🔜', 'callback_data' => "delspit", 'style' => 'primary']];
        if ($name === null) {
            bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "- لم تتم الإضافة لهذا السيرفر بعد", 'show_alert' => true]);
            @unlink("data/id/$id/step.txt");
            exit;
        }
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "*- نوع الرفع: $APP*\n\n🎬 *- يرجى إختيار نوع الرفع الذي تود حذفة* 👇",
            'parse_mode'   => "MarkDown",
            'reply_markup' => json_encode($key),
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($exdata[0] == "delrb") {
        $zero     = $exdata[1];
        $svc      = $increase["idplus"][$zero] ?? null;
        if ($svc === null) { @unlink("data/id/$id/step.txt"); exit; }

        $ID       = $svc['ID'];
        $name     = $svc['name'];
        $num      = $svc['num'];
        $add_site = $svc['add'];
        $site     = getSiteByNum($add_site);
        $APP      = getAppName($num);

        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "- تم حذف السيرفر من قسم الرفع بنجاح ✅\n\n- أيدي السيرفر: $ID\n- نوع السيرفر: $APP\n- إسم السيرفر: $name\n- الموقع: $site",
            'parse_mode'   => 'Markdown',
            'reply_markup' => json_encode(['inline_keyboard' => [[['text' => '- رجوع 🔜', 'callback_data' => 'delspit', 'style' => 'danger']]]]),
        ]);
        unset($increase["idplus"][$zero]);
        file_put_contents('data/increase.json', json_encode($increase, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    // ── إدارة API المواقع ────────────────────────────────────
    if ($data == 'addspit') {
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "اختر الموقع الذي تريد رفع ال api الخاص بة",
            'parse_mode'   => "MarkDown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => 'smmtigers.com', 'callback_data' => 'addspitapi-1', 'style' => 'danger']],
                    [['text' => 'smmxstar.com',  'callback_data' => 'addspitapi-2', 'style' => 'danger']],
                    [['text' => 'smmparty.com',  'callback_data' => 'addspitapi-3', 'style' => 'danger']],
                    [['text' => 'fast70.com',     'callback_data' => 'addspitapi-4', 'style' => 'danger']],
                    [['text' => '- رجوع.',        'callback_data' => 'Splash_settings', 'style' => 'danger']],
                ],
            ]),
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($exdata[0] == 'addspitapi') {
        $add  = $exdata[1];
        $site = getSiteByNum($add);
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "ارسل ايبي الموقع للرفع",
            'parse_mode'   => "MarkDown",
            'reply_markup' => json_encode(['inline_keyboard' => [[['text' => '-رجوع', 'callback_data' => 'Splash_settings', 'style' => 'danger']]]]),
        ]);
        file_put_contents("data/id/$id/step.txt", "addspitapi|$add");
        exit;
    }

    if ($text && $text != '/start ' && $exstep[0] == 'addspitapi') {
        $add  = $exstep[1];
        $site = getSiteByNum($add);
        $ex   = explode(".", $site);
        bot('sendMessage', [
            'chat_id'             => $chat_id,
            'text'                => "*☔️ - تم رفع كود ال API للموقع $site بنجاح ☑️*",
            'parse_mode'          => "MarkDown",
            'reply_to_message_id' => $message_id,
            'reply_markup'        => json_encode(['inline_keyboard' => [[['text' => '-رجوع.', 'callback_data' => 'Splash_settings', 'style' => 'danger']]]]),
        ]);
        file_put_contents("data/api/$ex[0].txt", "$text");
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($data == 'delspitapi') {
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "☑️ *- قم بإختيار الموقع الذي تريد حذفه من البوت*",
            'parse_mode'   => "MarkDown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => 'smmtigers.com', 'callback_data' => 'yaadelspitapi-1', 'style' => 'danger']],
                    [['text' => 'smmxstar.com',  'callback_data' => 'yaadelspitapi-2', 'style' => 'danger']],
                    [['text' => 'smmparty.com',  'callback_data' => 'yaadelspitapi-3', 'style' => 'danger']],
                    [['text' => 'fast70.com',     'callback_data' => 'yaadelspitapi-4', 'style' => 'danger']],
                    [['text' => '- رجوع.',        'callback_data' => 'Splash_settings', 'style' => 'danger']],
                ],
            ]),
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($exdata[0] == 'yaadelspitapi') {
        $add  = $exdata[1];
        $site = getSiteByNum($add);
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "☑️ *- هل انت متأكد بأنك تريد حذف الموقع $site*",
            'parse_mode'   => "MarkDown",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => "نعم متأكد ☑️", 'callback_data' => "yesdelspitapi-$add", 'style' => 'primary']],
                    [['text' => "- رجوع.",        'callback_data' => "delspitapi", 'style' => 'primary']],
                ],
            ]),
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    if ($exdata[0] == 'yesdelspitapi') {
        $add  = $exdata[1];
        $site = getSiteByNum($add);
        $ex   = explode(".", $site);
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "تم حذف ايبي الموقع $site بنجاح",
            'parse_mode'   => "MarkDown",
            'reply_markup' => json_encode(['inline_keyboard' => [[['text' => "- رجوع.", 'callback_data' => "delspitapi", 'style' => 'primary']]]]),
        ]);
        @unlink("data/api/$ex[0].txt");
        @unlink("data/id/$id/step.txt");
        exit;
    }

} // نهاية بلوك الأدمن ($is_admin)


// ===================================
//  إدارة الأقسام الفرعية - نظام كامل
// ===================================

// الأقسام الرئيسية مع أرقامها
$_main_sections_list = [
    "1"  => ["name" => "تيليجرام",       "emoji" => "📱"],
    "2"  => ["name" => "انستجرام",       "emoji" => "📸"],
    "3"  => ["name" => "تيك توك",        "emoji" => "🎵"],
    "4"  => ["name" => "فيسبوك",         "emoji" => "📘"],
    "5"  => ["name" => "تويتر",          "emoji" => "🐦"],
    "6"  => ["name" => "واتساب",         "emoji" => "💬"],
    "7"  => ["name" => "يوتيوب",         "emoji" => "▶️"],
    "10" => ["name" => "كواي",           "emoji" => "🎬"],
    "0"  => ["name" => "سناب شات",       "emoji" => "👻"],
    "8"  => ["name" => "خدمات مجانية",  "emoji" => "🎁"],
];

// قائمة إدارة الأقسام الفرعية
if ($data == "sub_types_menu") {
    $_sub_btns = [];
    foreach ($_main_sections_list as $_snum => $_sinfo) {
        $_sub_btns[] = [['text' => "{$_sinfo['emoji']} {$_sinfo['name']}", 'callback_data' => "sub_types_sec-$_snum", 'style' => 'primary']];
    }
    $_cust_secs_sm = $_rashq_secs_shared;
    foreach ($_cust_secs_sm as $_sc) {
        $_sub_btns[] = [['text' => "{$_sc['emoji']} {$_sc['name']}", 'callback_data' => "sub_types_sec-{$_sc['num']}", 'style' => 'primary']];
    }
    $_sub_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => 'Splash_settings', 'style' => 'danger']];
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
📝 *إدارة الأقسام الفرعية*

اختر القسم الرئيسي لإدارة أقسامه الفرعية:
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode(['inline_keyboard' => $_sub_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// عرض الأقسام الفرعية لقسم معين
if ($exdata[0] == "sub_types_sec") {
    $_sec_num = $exdata[1];
    $_sec_label = $_main_sections_list[$_sec_num]['name'] ?? "قسم $_sec_num";
    $_sec_emoji = $_main_sections_list[$_sec_num]['emoji'] ?? "📂";
    if (!isset($_main_sections_list[$_sec_num])) {
        $_cust_secs3 = $_rashq_secs_shared;
        foreach ($_cust_secs3 as $_sc3) {
            if ((string)$_sc3['num'] == (string)$_sec_num) {
                $_sec_label = $_sc3['name']; $_sec_emoji = $_sc3['emoji']; break;
            }
        }
    }
    $_sub_types_file = $_sub_types_shared;
    $_this_sub = $_sub_types_file[$_sec_num] ?? [];

    $_defaults_by_sec = [
        "1"  => [["1","أعضاء/متابعين 👥"],["2","مشاهدات 👁"],["3","لايكات 👍"],["4","بريميوم/نجوم ⭐"],["7","تعليقات 💬"],["8","رياكشنات 🔥"],["9","مشاركات 🔁"]],
        "2"  => [["1","متابعين 👥"],["2","مشاهدات 👁"],["3","لايكات 👍"],["7","تعليقات 💬"],["9","مشاركات 🔁"],["13","حفظ 🔖"],["8","تفاعلات 🔥"]],
        "3"  => [["1","متابعين 👥"],["2","مشاهدات 👁"],["3","لايكات 👍"],["7","تعليقات 💬"],["9","مشاركات 🔁"],["13","حفظ 🔖"],["5","عملات 🪙"]],
        "4"  => [["1","متابعين 👥"],["2","مشاهدات 👁"],["3","لايكات 👍"],["7","تعليقات 💬"],["9","مشاركات 🔁"],["8","تفاعلات 🔥"]],
        "5"  => [["1","متابعين 👥"],["2","مشاهدات 👁"],["3","لايكات 👍"],["9","ريتويت 🔁"]],
        "6"  => [["1","متابعين 👥"],["3","لايكات 👍"],["6","استطلاع 📊"]],
        "7"  => [["1","مشتركين 👥"],["2","مشاهدات 👁"],["3","لايكات 👍"],["7","تعليقات 💬"]],
        "8"  => [["1","متابعين 👥"],["2","مشاهدات 👁"],["3","لايكات 👍"]],
        "10" => [["1","متابعين 👥"],["2","مشاهدات 👁"],["3","لايكات 👍"]],
        "0"  => [["1","متابعين 👥"],["2","مشاهدات 👁"],["3","لايكات 👍"]],
    ];

    $_txt = "$_sec_emoji *إدارة أقسام $_sec_label الفرعية*\n\n";
    $_txt .= "🔷 *الأقسام الافتراضية (ثابتة):*\n";
    foreach ($_defaults_by_sec[$_sec_num] ?? [] as $_d) { $_txt .= "• [{$_d[0]}] {$_d[1]}\n"; }
    $_txt .= "\n🔶 *الأقسام المضافة:*\n";

    $_key_btns = [];
    if (!empty($_this_sub)) {
        foreach ($_this_sub as $_idx => $_st) {
            $_txt .= "• [{$_st['type']}] {$_st['emoji']} {$_st['btn_text']}\n";
            $_key_btns[] = [
                ['text' => "✏️ {$_st['btn_text']}", 'callback_data' => "stype_edit-$_sec_num-$_idx", 'style' => 'danger'],
                ['text' => "🗑 حذف",                'callback_data' => "stype_del-$_sec_num-$_idx", 'style' => 'danger'],
            ];
        }
    } else {
        $_txt .= "_لا توجد أقسام فرعية مضافة._";
    }
    $_key_btns[] = [['text' => '➕ إضافة قسم فرعي جديد', 'callback_data' => "stype_add-$_sec_num", 'style' => 'primary']];
    $_key_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => 'sub_types_menu', 'style' => 'primary']];

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => $_txt,
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode(['inline_keyboard' => $_key_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// إضافة قسم فرعي جديد
if ($exdata[0] == "stype_add") {
    $_sec_num  = $exdata[1];
    $_sub_data2 = $_sub_types_shared;
    $_existing2 = $_sub_data2[$_sec_num] ?? [];
    $_used_types2 = [1,2,3,4,5,6,7,8,9,10,11,12];
    foreach ($_existing2 as $_ex2) { $_used_types2[] = (int)$_ex2['type']; }
    $_next_type2 = 13;
    while (in_array($_next_type2, $_used_types2)) $_next_type2++;

    // جلب اسم القسم للعرض
    $_sec_label_add = $_main_sections_list[$_sec_num]['name'] ?? null;
    $_sec_emoji_add = $_main_sections_list[$_sec_num]['emoji'] ?? '📂';
    if (!$_sec_label_add) {
        $_cust_s = $_rashq_secs_shared;
        foreach ($_cust_s as $_cs) {
            if ((string)$_cs['num'] == (string)$_sec_num) {
                $_sec_label_add = $_cs['name']; $_sec_emoji_add = $_cs['emoji']; break;
            }
        }
        $_sec_label_add = $_sec_label_add ?? "قسم $_sec_num";
    }

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
➕ *إضافة قسم فرعي جديد*

$_sec_emoji_add القسم الرئيسي: *$_sec_label_add*
🔢 رقم النوع المقترح: *$_next_type2*

أرسل البيانات (سطر لكل قيمة):
\`\`\`
اسم الزر (مثل: تعليقات - COMMENTS)
إيموجي (مثل: 💬)
رقم النوع (اتركه فارغاً للاستخدام التلقائي)
\`\`\`
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [[['text' => '- رجوع 🔜', 'callback_data' => "sub_types_sec-$_sec_num", 'style' => 'primary']]]
        ]),
    ]);
    file_put_contents("data/id/$id/step.txt", "stype_add_input|$_sec_num|$_next_type2");
    exit;
}

if ($text && $text != '/start' && $exstep[0] == 'stype_add_input') {
    $_sec_num3   = $exstep[1];
    $_next_type3 = (int)$exstep[2];
    $_lines3     = explode("\n", trim($text));
    $_btn_text3  = trim($_lines3[0] ?? '');
    $_emoji3     = trim($_lines3[1] ?? '📌');
    $_type_num3  = isset($_lines3[2]) && is_numeric(trim($_lines3[2])) ? (int)trim($_lines3[2]) : $_next_type3;

    if (mb_strlen($_btn_text3, 'UTF-8') < 2) {
        bot('sendMessage', ['chat_id' => $chat_id, 'text' => "❌ اسم الزر قصير جداً، حاول مرة أخرى.", 'reply_to_message_id' => $message_id]);
        exit;
    }

    $_sub_data3 = jRead("data/sub_types.json") ?? [];
    if (!isset($_sub_data3[$_sec_num3])) $_sub_data3[$_sec_num3] = [];

    foreach ($_sub_data3[$_sec_num3] as $_ex3) {
        if ((int)$_ex3['type'] == $_type_num3) {
            bot('sendMessage', ['chat_id' => $chat_id, 'text' => "⚠️ رقم النوع *$_type_num3* مستخدم بالفعل.", 'parse_mode' => 'Markdown', 'reply_to_message_id' => $message_id]);
            exit;
        }
    }

    $_sub_data3[$_sec_num3][] = ['type' => (string)$_type_num3, 'name' => $_btn_text3, 'btn_text' => $_btn_text3, 'emoji' => $_emoji3];
    file_put_contents("data/sub_types.json", json_encode($_sub_data3, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);

    bot('sendMessage', [
        'chat_id'    => $chat_id,
        'text'       => "✅ *تم إضافة القسم الفرعي!*\n\n$_emoji3 *$_btn_text3*\n🔢 رقم النوع: *$_type_num3*\n\n✅ سيظهر فوراً في قائمة المستخدمين.",
        'parse_mode' => 'Markdown',
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [[['text' => '- عرض الأقسام الفرعية 🔜', 'callback_data' => "sub_types_sec-$_sec_num3", 'style' => 'primary']]]
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// تعديل قسم فرعي
if ($exdata[0] == "stype_edit") {
    $_sec_num4 = $exdata[1];
    $_idx4     = (int)$exdata[2];
    $_sub_data4 = jRead("data/sub_types.json") ?? [];
    $_this_st4  = $_sub_data4[$_sec_num4][$_idx4] ?? null;

    if (!$_this_st4) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "⚠️ القسم غير موجود.", 'show_alert' => true]);
        exit;
    }

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
✏️ *تعديل القسم الفرعي*

الحالي:
• الاسم: *{$_this_st4['btn_text']}*
• الإيموجي: {$_this_st4['emoji']}
• رقم النوع: *{$_this_st4['type']}* (لا يمكن تعديله)

أرسل القيم الجديدة:
\`\`\`
اسم الزر الجديد
إيموجي جديد
\`\`\`
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [[['text' => '- رجوع 🔜', 'callback_data' => "sub_types_sec-$_sec_num4", 'style' => 'primary']]]
        ]),
    ]);
    file_put_contents("data/id/$id/step.txt", "stype_edit_input|$_sec_num4|$_idx4");
    exit;
}

if ($text && $text != '/start' && $exstep[0] == 'stype_edit_input') {
    $_sec_num5 = $exstep[1];
    $_idx5     = (int)$exstep[2];
    $_lines5   = explode("\n", trim($text));
    $_new_btn5 = trim($_lines5[0] ?? '');
    $_new_em5  = trim($_lines5[1] ?? '');

    $_sub_data5 = jRead("data/sub_types.json") ?? [];
    if (!isset($_sub_data5[$_sec_num5][$_idx5])) {
        bot('sendMessage', ['chat_id' => $chat_id, 'text' => "⚠️ القسم الفرعي غير موجود."]);
        @unlink("data/id/$id/step.txt");
        exit;
    }
    if (!empty($_new_btn5)) { $_sub_data5[$_sec_num5][$_idx5]['btn_text'] = $_new_btn5; $_sub_data5[$_sec_num5][$_idx5]['name'] = $_new_btn5; }
    if (!empty($_new_em5))  { $_sub_data5[$_sec_num5][$_idx5]['emoji']    = $_new_em5; }
    file_put_contents("data/sub_types.json", json_encode($_sub_data5, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
    $_saved5 = $_sub_data5[$_sec_num5][$_idx5];

    bot('sendMessage', [
        'chat_id'    => $chat_id,
        'text'       => "✅ *تم التعديل!*\n\n{$_saved5['emoji']} *{$_saved5['btn_text']}*",
        'parse_mode' => 'Markdown',
        'reply_to_message_id' => $message_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => [[['text' => '- عرض الأقسام الفرعية 🔜', 'callback_data' => "sub_types_sec-$_sec_num5", 'style' => 'primary']]]
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// حذف قسم فرعي
if ($exdata[0] == "stype_del") {
    $_sec_num6 = $exdata[1];
    $_idx6     = (int)$exdata[2];
    $_sub_data6 = jRead("data/sub_types.json") ?? [];
    $_this_st6  = $_sub_data6[$_sec_num6][$_idx6] ?? null;

    if (!$_this_st6) {
        bot('answercallbackquery', ['callback_query_id' => $update->callback_query->id, 'text' => "⚠️ القسم غير موجود.", 'show_alert' => true]);
        exit;
    }

    array_splice($_sub_data6[$_sec_num6], $_idx6, 1);
    $_sub_data6[$_sec_num6] = array_values($_sub_data6[$_sec_num6]);
    if (empty($_sub_data6[$_sec_num6])) unset($_sub_data6[$_sec_num6]);
    file_put_contents("data/sub_types.json", json_encode($_sub_data6, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "✅ *تم الحذف:* {$_this_st6['emoji']} {$_this_st6['btn_text']}",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [[['text' => '- رجوع للأقسام الفرعية 🔜', 'callback_data' => "sub_types_sec-$_sec_num6", 'style' => 'primary']]]
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}


// ===================================
//  معالج إعادة الطلب (spit_reorder)
// ===================================
if($text != null && $text != '/start' && $exstep[0] == 'spit_reorder'){
    $spit_zero     = $exstep[1] ?? null;
    $spit_add_site = $exstep[2] ?? null;
    $spit_number   = $exstep[3] ?? null;
    $urls          = trim($text);

    if(!$spit_zero || !$spit_add_site || !$spit_number){
        bot('sendMessage',['chat_id'=>$chat_id,'text'=>"❌ - حدث خطأ، أعد المحاولة.",'parse_mode'=>"MarkDown"]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    $pri = $increase["idplus"][$spit_zero] ?? null;
    if(!$pri){
        bot('sendMessage',['chat_id'=>$chat_id,'text'=>"❌ - الخدمة غير متوفرة.",'parse_mode'=>"MarkDown"]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    $ID        = $pri['ID'];
    $num       = $pri['num'];
    $type      = $pri['type'];
    $Type      = $pri['Type'];
    $name      = $pri['name'];
    $price     = round($pri['price'] / 1000, 6) * $spit_number;
    $Location  = !empty($pri['site']) ? $pri['site'] : getSiteByNum($spit_add_site);
    $APP       = getAppName($num);
    $x         = getLinkLabel($num, $type);

    // فحص الرصيد
    if($price > $Balance){
        bot('sendMessage',['chat_id'=>$chat_id,'text'=>"❌ - رصيدك غير كافٍ.
💰 رصيدك: ₽ $Balance",'parse_mode'=>"MarkDown"]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    // إرسال الطلب للـ API
    $ex_loc  = explode(".", $Location);
    $api_key = trim(@file_get_contents("data/api/{$ex_loc[0]}.txt"));
    if(empty($api_key)){
        bot('sendMessage',['chat_id'=>$chat_id,'text'=>"⚠️ - مفتاح API غير متوفر.",'parse_mode'=>"MarkDown"]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    $post_fields = "key=".urlencode($api_key)."&action=add&service=".urlencode($ID)."&link=".urlencode($urls)."&quantity=".urlencode($spit_number);
    $ch = curl_init("https://$Location/api/v2");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
    $api_raw = curl_exec($ch);
    curl_close($ch);
    $api   = $api_raw ? json_decode($api_raw, true) : null;
    $order = $api["order"] ?? null;
    $error = $api["error"]  ?? null;

    if($error || !$order){
        $msg = $error ?: "فشل الاتصال بالسيرفر";
        bot('sendMessage',['chat_id'=>$chat_id,'text'=>"❌ - $msg",'parse_mode'=>"MarkDown"]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    // خصم الرصيد وحفظ الطلب
    $points = (float)(@file_get_contents("EMILS/$EM/points.txt") ?: 0);
    file_put_contents("EMILS/$EM/points.txt", $points - $price, LOCK_EX);

    $spitmy = rand(1234567, 9999999);
    $BUYSSPIT['spit'][$spitmy] = [
        'order'  => $order, 'price' => $price, 'status' => 1,
        'number' => $spit_number, 'zero' => $spit_zero, 'add' => $spit_add_site,
        'urls'   => $urls, 'name' => $name, 'num' => $num,
        'type'   => $type, 'chat-id' => $id, 'DAY' => $DAY,
    ];
    file_put_contents("EMILS/$EM/spit.json", json_encode($BUYSSPIT, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), LOCK_EX);

    $idSend = count($ORDERALL) + 1;
    $ORDERALL[$idSend]['account'] = $EM;
    $ORDERALL[$idSend]['order']   = $spitmy;
    OrdAll($ORDERALL);

    $seq_file = "data/pay_order_seq.txt";
    $seq_num  = (int)(@file_get_contents($seq_file) ?: 0) + 1;
    file_put_contents($seq_file, $seq_num, LOCK_EX);

    $STORAGEALL = jRead('data/storage.json') ?: ['spit'=>0,'ruble'=>0];
    $STORAGEALL['spit']  = ($STORAGEALL['spit']  ?? 0) + 1;
    $STORAGEALL['ruble'] = ($STORAGEALL['ruble'] ?? 0) + $price;
    StoAll($STORAGEALL);

    notifySimChannel($sim, $name, $ID, $APP, $Type, $spit_number, $id, $price, $urls, $me, $seq_num, $spit_zero, $spit_add_site);
    notifyPayChannel($PAY, $APP, $spit_number, $Balance, $name, $seq_num, $order, $urls, $EM, $price, $Location);

    bot('sendMessage',[
        'chat_id'    => $chat_id,
        'text'       => "✅ *تم إرسال طلبك بنجاح!*

📌 الخدمة: $name
👥 العدد: $spit_number
💰 السعر: ₽ $price
🔢 رقم الطلب: $seq_num",
        'parse_mode' => "MarkDown",
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// ===================================
//  جلب الخدمات التلقائي من API
//  ملاحظة: اسم الملف في سيرفرك قد يكون rashq_fetch-1.php
//  تأكد أن الاسم هنا يطابق اسم الملف الفعلي على السيرفر
// ===================================
include("rashq_fetch.php"); // غيّر للاسم الصحيح إذا لزم
