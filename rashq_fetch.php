<?php
@error_reporting(E_ERROR | E_PARSE);
@ini_set('display_errors', 0);
@ini_set('default_socket_timeout', 8); // ⚡ حد أقصى 8 ثواني لأي اتصال
//برمجه وتطوير هذا النظام @E_O_E1   
#=========={جلب الخدمات التلقائي من API الرشق}==========#
# المواقع المدعومة (نفس ترقيم spit.php)
# 1smmxstar.com | 3=smmparty.com | 4=fast70.com

// تحميل المواقع ديناميكياً من ملف JSON (يدعم الإضافة والحذف)
$RashqSites_default = [
    "1" => "smmtigers.com",
    "2" => "smmxstar.com",
    "3" => "smmparty.com",
    "4" => "fast70.com",
];
$RashqSites_custom = json_decode(@file_get_contents("data/rashq_sites.json") ?: '{}', true) ?: [];
// =FIX= : + بدل array_merge لحفظ المفاتيح الرقمية كما هي
$RashqSites = $RashqSites_default + $RashqSites_custom;

$RashqApps = [
    "1"  => "تيليجرام",
    "2"  => "انستجرام",
    "3"  => "تيك توك",
    "4"  => "فيسبوك",
    "5"  => "تويتر",
    "6"  => "وتساب",
    "7"  => "يوتيوب",
    "8"  => "كواي",      // رقم الجلب 8 = كواي (spit.php رقم 10)
    "9"  => "سناب شات",  // رقم الجلب 9 = سناب (spit.php رقم 0)
    "10" => "خدمات مجانية", // رقم الجلب 10 = مجانية (spit.php رقم 8)
];

// خريطة تحويل: رقم الجلب (rashq) => رقم القسم في spit.php (num)
// spit.php: 1=تيليجرام, 2=انستا, 3=تيك توك, 4=فيسبوك, 5=تويتر, 6=واتساب, 7=يوتيوب, 8=خدمات مجانية, 10=كواي, 0=سناب
$RashqToSpitNum = [
    "1"  => "1",   // تيليجرام
    "2"  => "2",   // انستجرام
    "3"  => "3",   // تيك توك
    "4"  => "4",   // فيسبوك
    "5"  => "5",   // تويتر
    "6"  => "6",   // وتساب
    "7"  => "7",   // يوتيوب
    "8"  => "10",  // كواي (في spit.php الكواي=10 وليس 8!)
    "9"  => "0",   // سناب شات (في spit.php سناب=0)
    "10" => "8",   // خدمات مجانية (في spit.php مجانية=8)
];

$RashqTypes = [
    "1"  => "متابعين 👥",
    "2"  => "مشاهدات 👁",
    "3"  => "لايكات 👍",
    "4"  => "اشتراك بريميوم 🔧",
    "5"  => "عملات/كوينز 🪙",
    "6"  => "استطلاع راي 📊",
    "7"  => "تعليقات 💬",
    "8"  => "تفاعلات/ريأكشن 🔥",
    "9"  => "مشاركات/ريبوست 🔁",
    "10" => "مشتركين/اشتراكات 📧",
    "11" => "نقرات/ترافيك 👆",
    "12" => "حزمة مجمعة 📦",
    "13" => "حفظ/إضافة للمفضلة 🔖",
];

// دالة مساعدة: جلب اسم الموقع من الرقم
function getRashqSite($add) {
    global $RashqSites;
    return $RashqSites[$add] ?? "غير معروف";
}

/**
 * مطابقة ذكية للكلمات المفتاحية:
 * - الكلمات الطويلة (>3 حروف): strpos عادي
 * - الكلمات القصيرة (2-3 حروف مثل tg, ig, fb, yt):
 *   يشترط أن تكون محاطة بحد كلمة (مسافة، خط، بداية/نهاية النص)
 *   لمنع مطابقة "ig" في "digital" أو "tg" في "marketing"
 */
function matchKeyword($text, $keyword) {
    if (mb_strlen($keyword, 'UTF-8') > 3) {
        return strpos($text, $keyword) !== false;
    }
    $pattern = '/(^|[\s\-_#\/|\.])' . preg_quote($keyword, '/') . '($|[\s\-_#\/|\.0-9])/ui';
    return (bool)preg_match($pattern, $text);
}

// دالة مساعدة: جلب جميع خدمات موقع معين عبر API (POST بـ cURL)
function fetchRashqServices($site, $api_key) {
    // فحص اسم الموقع قبل الاتصال
    if (empty($site) || $site === "غير معروف" || strpos($site, '.') === false) return null;
    $url = "https://$site/api/v2";
    $post_data = "key=" . urlencode($api_key) . "&action=services";
    $ch = curl_init($url);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; SMM-Bot/2.0)');
    $raw = curl_exec($ch);
    $errno = curl_errno($ch);
    $http  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($errno || !$raw) return null;
    if ($http && $http !== 200) return null;
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) return null;
    if (isset($decoded['error'])) return null;  // API أرجع خطأ
    if (isset($decoded['data']) && is_array($decoded['data'])) return $decoded['data'];
    if (isset($decoded[0]) && is_array($decoded[0])) return $decoded;
    if (!empty($decoded)) return $decoded;
    return null;
}

// دالة مساعدة: فلترة الخدمات حسب كلمة مفتاحية
function filterServicesByCategory($services, $keyword) {
    if (!$services) return [];
    $result = [];
    $keyword_lower = mb_strtolower($keyword, 'UTF-8');
    foreach ($services as $svc) {
        $name_lower = mb_strtolower($svc['name'] ?? '', 'UTF-8');
        if (strpos($name_lower, $keyword_lower) !== false) {
            $result[] = $svc;
        }
    }
    return $result;
}

// كلمات البحث لكل تطبيق (موسعة لتغطي أكثر الأنماط في مواقع SMM)
// ⚠️ مهم: الكلمات هنا حصرية لكل منصة - لا تضع كلمات عامة مثل "likes" أو "views" بدون اسم المنصة
$AppKeywords = [
    "1"  => ["telegram", "tg", "telgram", "telegrm", "تيليجرام", "تلغرام", "تلجرام",
             "channel members", "telegram members", "telegram followers", "telegram views",
             "telegram likes", "telegram reactions", "telegram post", "telegram channel",
             "telegram group", "tg members", "tg followers", "tg views", "tg likes",
             "tg reactions", "tg channel", "tg group", "tg post", "telegram premium",
             "telegram stars", "tg premium", "tg stars", "tg boost", "telegram boost",
             "telegram comments", "tg comments", "telegram shares", "tg shares",
             "telegram forwards", "tg forwards"],
    "2"  => ["instagram", "insta", "ig", "انستا", "انستغرام", "انستجرام",
             "instagram followers", "instagram likes", "instagram views", "instagram reels",
             "instagram story", "instagram reel", "ig followers", "ig likes", "ig views",
             "ig reel", "ig story", "instagram comments", "ig comments", "instagram saves",
             "ig saves", "instagram shares", "ig shares", "insta followers", "insta likes",
             "insta views", "insta comments", "instagram profile", "instagram post"],
    "3"  => ["tiktok", "tik tok", "tik-tok", "تيك توك", "تيكتوك", "tiktokers",
             "tiktok followers", "tiktok views", "tiktok likes", "tiktok comments",
             "tiktok shares", "tiktok saves", "tiktok coins", "tt followers", "tt views",
             "tt likes"],
    "4"  => ["facebook", "fb", "face book", "فيسبوك", "فيس بوك",
             "facebook followers", "facebook likes", "facebook views", "facebook page",
             "facebook post", "fb followers", "fb likes", "fb views", "fb page",
             "facebook comments", "facebook shares", "facebook reactions"],
    "5"  => ["twitter", "tweet", "x.com", "تويتر", "x -", "| x",
             "twitter followers", "twitter likes", "twitter retweet", "twitter views",
             "x followers", "x likes", "x views", "x retweet", "twitter comments",
             "twitter shares"],
    "6"  => ["whatsapp", "whats", "واتساب", "واتس اب", "whats app",
             "whatsapp channel", "whatsapp group", "wa channel", "wa group"],
    "7"  => ["youtube", "yt", "you tube", "يوتيوب", "يوتيب",
             "youtube subscribers", "youtube views", "youtube likes", "youtube watch",
             "yt subscribers", "yt views", "yt likes", "youtube comments"],
    "8"  => ["kwai", "كواي", "kwai followers", "kwai views", "kwai likes"],
    "9"  => ["snapchat", "snap", "سناب", "سناب شات", "snapchat followers", "snapchat views",
             "snap followers", "snap views", "snap score"],
    "10" => ["free", "مجان", "trial", "gratis", "free followers", "free likes",
             "مجانية", "تجريبي"],
];

// كلمات الاستثناء: إذا وُجدت في الخدمة فهي تنتمي لمنصة أخرى → نستثنيها
// مثال: عند البحث في تيليجرام، أي خدمة تحتوي على "instagram" أو "ig " أو "tiktok" تُستثنى
$AppExcludeKeywords = [
    "1"  => ["instagram", "insta", " ig ", "ig followers", "ig likes", "tiktok", "tik tok",
             "facebook", " fb ", "twitter", "tweet", "youtube", " yt ", "snapchat", "kwai",
             "انستجرام", "انستا", "تيك توك", "فيسبوك", "تويتر", "يوتيوب", "سناب", "كواي"],
    "2"  => ["telegram", " tg ", "tg followers", "tg likes", "tg members", "tg views",
             "tg reactions", "tiktok", "tik tok", "facebook", " fb ", "twitter", "youtube",
             "snapchat", "kwai", "تيليجرام", "تلغرام", "تلجرام", "تيك توك", "فيسبوك",
             "تويتر", "يوتيوب", "سناب", "كواي"],
    "3"  => ["telegram", " tg ", "instagram", " ig ", "facebook", " fb ", "twitter",
             "youtube", "snapchat", "kwai", "تيليجرام", "انستجرام", "فيسبوك", "تويتر",
             "يوتيوب", "سناب", "كواي"],
    "4"  => ["telegram", " tg ", "instagram", " ig ", "tiktok", "tik tok", "twitter",
             "youtube", "snapchat", "kwai", "تيليجرام", "انستجرام", "تيك توك", "تويتر",
             "يوتيوب", "سناب", "كواي"],
    "5"  => ["telegram", " tg ", "instagram", " ig ", "tiktok", "tik tok", "facebook",
             " fb ", "youtube", "snapchat", "kwai", "تيليجرام", "انستجرام", "تيك توك",
             "فيسبوك", "يوتيوب", "سناب", "كواي"],
    "6"  => ["telegram", " tg ", "instagram", " ig ", "tiktok", "facebook", "twitter",
             "youtube", "snapchat", "kwai"],
    "7"  => ["telegram", " tg ", "instagram", " ig ", "tiktok", "facebook", " fb ",
             "twitter", "snapchat", "kwai"],
    "8"  => ["telegram", "instagram", "tiktok", "facebook", "twitter", "youtube",
             "snapchat"],
    "9"  => ["telegram", "instagram", "tiktok", "facebook", "twitter", "youtube", "kwai"],
    "10" => [],
];

$TypeKeywords = [
    // متابعين
    "1"  => ["follow", "متابع", "subscriber", "member", "followers", "اعضاء", "عضو", "مشترك", "مشتركين", "members", "channel members", "group members", "join"],
    // مشاهدات - أضفنا كلمات أكثر شيوعاً في مواقع SMM
    "2"  => ["view", "views", "مشاهد", "مشاهدات", "watch", "stream", "plays", "impression", "impressions",
             "post views", "story views", "reel views", "video views", "channel views",
             "story view", "instagram views", "tiktok views", "youtube views", "facebook views",
             "snap views", "spotlight views", "video play"],
    // لايكات - بدون "like" المفرد لتجنب false positives
    "3"  => ["likes", "لايك", "لايكات", "post likes", "photo likes", "video likes",
             "post like", "channel likes", "reel likes", "tweet likes", "fb likes",
             "instagram likes", "tiktok likes", "facebook likes", "youtube likes",
             "story likes", "snap likes"],
    // بريميوم ونجوم
    "4"  => ["premium", "بريميوم", "star", "stars", "boost", "نجوم", "stars",
             "subscription", "premium subscription", "telegram premium", "telegram stars",
             "tg premium", "tg stars", "tg boost"],
    // عملات
    "5"  => ["coin", "coins", "عملة", "عملات", "diamond", "gems", "كوين", "دايموند",
             "tiktok coins", "kwai coins", "tiktok coin"],
    // استطلاع
    "6"  => ["poll", "vote", "votes", "استطلاع", "polling", "survey", "تصويت"],
    // تعليقات - أضفنا أنماط أكثر
    "7"  => ["comment", "comments", "تعليق", "تعليقات", "reply", "replies", "ردود", "رد",
             "post comment", "video comment", "photo comment", "reel comment",
             "instagram comment", "tiktok comment", "youtube comment", "facebook comment",
             "random comment", "custom comment", "arab comment", "arabic comment"],
    // تفاعلات - كلمات دقيقة + إيموجي شائعة
    "8"  => [
        "react", "reaction", "reactions", "تفاعل", "تفاعلات", "رياكشن",
        "emoji", "ايموجي", "emojis",
        "post reaction", "channel reaction", "message reaction", "emoji reaction",
        "auto reaction", "random reaction", "custom reaction", "custom reactions",
        "telegram reaction", "tg reaction", "tg reactions",
        "❤", "👍", "🔥", "👏", "😍", "💯", "🎉", "😂", "🥰", "😱",
    ],
    // مشاركات
    "9"  => ["share", "shares", "مشاركة", "مشاركات", "retweet", "repost", "forward",
             "إعادة نشر", "forwards", "reposts", "retweetss"],
    // مشتركين
    "10" => ["subscribe", "subscription", "مشترك", "مشتركين", "اشتراك",
             "newsletter", "channel subscribers", "youtube subscribers"],
    // نقرات
    "11" => ["click", "clicks", "traffic", "نقرة", "نقرات", "ترافيك",
             "visit", "visits", "زيارة", "زوار", "website traffic", "link clicks", "link click"],
    // حزم
    "12" => ["package", "combo", "bundle", "حزمة", "باقة", "مجمع", "mixed", "package deal"],
    // حفظ / مفضلة
    "13" => ["save", "saved", "saves", "حفظ", "bookmark", "bookmarks", "مفضلة", "favorite",
             "favourites", "favorites", "collection", "collections", "add to",
             "instagram save", "tiktok save", "reel save", "post save",
             "story save", "snap save", "saved post"],
];

#=========={لوحة تحكم الأدمن - قسم الرشق مع الجلب التلقائي}==========#

// زر "جلب خدمات تلقائي" في Splash_settings
if ($data == "rashq_fetch_menu") {
    // بناء أزرار المواقع ديناميكياً
    $site_btns = [];
    foreach ($RashqSites as $snum => $sname) {
        $site_btns[] = [['text' => "$sname 🌐", 'callback_data' => "rfetch_site-$snum"]];
    }
    $site_btns[] = [['text' => '➕ إضافة موقع API جديد', 'callback_data' => 'rsite_manage']];
    $site_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => 'Splash_settings']];

    $total_sites = count($RashqSites);
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
🤖 *- جلب خدمات تلقائي من API الرشق*

📊 عدد المواقع المتاحة: *$total_sites*

اختر الموقع الذي تريد جلب الخدمات منه:
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode(['inline_keyboard' => $site_btns])
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={إدارة مواقع API}==========#
if ($data == "rsite_manage") {
    $custom_sites = json_decode(@file_get_contents("data/rashq_sites.json") ?: '{}', true) ?: [];
    $custom_count = count($custom_sites);
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
🌐 *إدارة مواقع API*

📦 المواقع الافتراضية: *4*
➕ المواقع المضافة: *$custom_count*

اختر العملية:
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '➕ إضافة موقع جديد', 'callback_data' => 'rsite_add']],
                [['text' => '📋 عرض جميع المواقع', 'callback_data' => 'rsite_list']],
                [['text' => '🗑 حذف موقع مضاف',   'callback_data' => 'rsite_del_menu']],
                [['text' => '- رجوع 🔜', 'callback_data' => 'rashq_fetch_menu']],
            ]
        ])
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// إضافة موقع جديد - خطوة 1: إدخال اسم الموقع
if ($data == "rsite_add") {
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
➕ *إضافة موقع API جديد*

أرسل رابط الموقع فقط بدون https:// وبدون /api
مثال:
```
mysmmsite.com
```
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '- رجوع 🔜', 'callback_data' => 'rsite_manage']],
            ]
        ])
    ]);
    file_put_contents("data/id/$id/step.txt", "rsite_add_domain");
    exit;
}

// استقبال اسم الموقع
if ($text && $text != '/start' && $exstep[0] == 'rsite_add_domain') {
    $new_domain = trim(strtolower($text));
    // تنظيف الرابط
    $new_domain = preg_replace('#^https?://#', '', $new_domain);
    $new_domain = rtrim($new_domain, '/');

    if (!preg_match('/^[a-z0-9][a-z0-9\-\.]+\.[a-z]{2,}$/', $new_domain)) {
        bot('sendMessage', [
            'chat_id'             => $chat_id,
            'text'                => "❌ *رابط غير صحيح.*
أرسل الرابط بهذا الشكل: `mysmmsite.com`",
            'parse_mode'          => 'Markdown',
            'reply_to_message_id' => $message_id,
        ]);
        exit;
    }

    // فحص التكرار
    if (in_array($new_domain, $RashqSites)) {
        bot('sendMessage', [
            'chat_id'             => $chat_id,
            'text'                => "⚠️ الموقع *$new_domain* موجود بالفعل.",
            'parse_mode'          => 'Markdown',
            'reply_to_message_id' => $message_id,
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }

    bot('sendMessage', [
        'chat_id'    => $chat_id,
        'text'       => "
✅ الموقع: *$new_domain*

الآن أرسل مفتاح API الخاص بهذا الموقع:
",
        'parse_mode'          => 'Markdown',
        'reply_to_message_id' => $message_id,
        'reply_markup'        => json_encode([
            'inline_keyboard' => [
                [['text' => '- رجوع 🔜', 'callback_data' => 'rsite_add']],
            ]
        ])
    ]);
    file_put_contents("data/id/$id/step.txt", "rsite_add_apikey|$new_domain");
    exit;
}

// استقبال مفتاح API
if ($text && $text != '/start' && $exstep[0] == 'rsite_add_apikey') {
    $new_domain = $exstep[1];
    $new_apikey = trim($text);

    if (strlen($new_apikey) < 5) {
        bot('sendMessage', [
            'chat_id'             => $chat_id,
            'text'                => "❌ مفتاح API قصير جداً، أعد الإرسال.",
            'reply_to_message_id' => $message_id,
        ]);
        exit;
    }

    // حفظ الموقع في rashq_sites.json
    $custom_sites = json_decode(@file_get_contents("data/rashq_sites.json") ?: '{}', true) ?: [];
    // رقم جديد (بعد آخر رقم موجود)
    $all_nums = array_merge(array_keys($RashqSites_default), array_keys($custom_sites));
    $new_num  = (string)(max(array_map('intval', $all_nums)) + 1);
    $custom_sites[$new_num] = $new_domain;
    file_put_contents("data/rashq_sites.json", json_encode($custom_sites, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);

    // حفظ مفتاح API
    $api_folder = "data/api";
    if (!is_dir($api_folder)) mkdir($api_folder, 0755, true);
    $api_file = $api_folder . "/" . explode(".", $new_domain)[0] . ".txt";
    file_put_contents($api_file, $new_apikey);

    bot('sendMessage', [
        'chat_id'    => $chat_id,
        'text'       => "
✅ *تم إضافة الموقع بنجاح!*

🌐 الموقع: *$new_domain*
🔑 API: محفوظ ✓
🔢 الرقم: *#$new_num*

سيظهر الموقع الآن في قائمة الجلب.
",
        'parse_mode'          => 'Markdown',
        'reply_to_message_id' => $message_id,
        'reply_markup'        => json_encode([
            'inline_keyboard' => [
                [['text' => '- قائمة الجلب 🔜', 'callback_data' => 'rashq_fetch_menu']],
                [['text' => '- إدارة المواقع 🔜', 'callback_data' => 'rsite_manage']],
            ]
        ])
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// عرض جميع المواقع
if ($data == "rsite_list") {
    $custom_sites = json_decode(@file_get_contents("data/rashq_sites.json") ?: '{}', true) ?: [];
    $RashqSites_default_local = [
        "1" => "smmtigers.com", "2" => "smmxstar.com",
        "3" => "smmparty.com",  "4" => "fast70.com",
    ];

    $txt = "📋 *جميع مواقع API*

🔷 *المواقع الافتراضية:*
";
    foreach ($RashqSites_default_local as $num => $site) {
        $api_file = "data/api/" . explode(".", $site)[0] . ".txt";
        $has_api  = file_exists($api_file) && trim(file_get_contents($api_file)) ? "✅" : "❌";
        $txt .= "• #$num $site $has_api
";
    }
    if (!empty($custom_sites)) {
        $txt .= "
🔶 *المواقع المضافة:*
";
        foreach ($custom_sites as $num => $site) {
            $api_file = "data/api/" . explode(".", $site)[0] . ".txt";
            $has_api  = file_exists($api_file) && trim(file_get_contents($api_file)) ? "✅" : "❌";
            $txt .= "• #$num $site $has_api
";
        }
    } else {
        $txt .= "
_لا توجد مواقع مضافة بعد._";
    }
    $txt .= "
✅ = مفتاح API موجود | ❌ = يحتاج مفتاح API";

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => $txt,
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '- رجوع 🔜', 'callback_data' => 'rsite_manage']],
            ]
        ])
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// حذف موقع مضاف
if ($data == "rsite_del_menu") {
    $custom_sites = json_decode(@file_get_contents("data/rashq_sites.json") ?: '{}', true) ?: [];
    if (empty($custom_sites)) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "لا توجد مواقع مضافة للحذف.
المواقع الافتراضية لا يمكن حذفها.",
            'show_alert'        => true,
        ]);
        exit;
    }
    $del_btns = [];
    foreach ($custom_sites as $num => $site) {
        $del_btns[] = [['text' => "🗑 $site (#$num)", 'callback_data' => "rsite_del-$num"]];
    }
    $del_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => 'rsite_manage']];

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "🗑 *حذف موقع API*

اختر الموقع الذي تريد حذفه:
⚠️ المواقع الافتراضية لا يمكن حذفها.",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode(['inline_keyboard' => $del_btns])
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

if ($exdata[0] == "rsite_del") {
    $del_num     = $exdata[1];
    $custom_sites = json_decode(@file_get_contents("data/rashq_sites.json") ?: '{}', true) ?: [];

    if (!isset($custom_sites[$del_num])) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "⚠️ الموقع غير موجود أو هو موقع افتراضي لا يمكن حذفه.",
            'show_alert'        => true,
        ]);
        exit;
    }

    $del_site = $custom_sites[$del_num];
    unset($custom_sites[$del_num]);
    file_put_contents("data/rashq_sites.json", json_encode($custom_sites, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "✅ *تم حذف الموقع:* $del_site",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '- إدارة المواقع 🔜', 'callback_data' => 'rsite_manage']],
            ]
        ])
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={اختيار التطبيق بعد اختيار الموقع}==========#
if ($exdata[0] == "rfetch_site") {
    $fadd  = $exdata[1] ?? '';
    // =FIX= فحص صحة رقم الموقع أولاً
    if ($fadd === '' || ($fsite = getRashqSite($fadd)) === "غير معروف") {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "⚠️ الموقع غير موجود، يرجى فتح قائمة الجلب من جديد.",
            'show_alert'        => true,
        ]);
        exit;
    }
    // البحث عن ملف API بطريقتين: بالشرطة أو بأول جزء من الاسم
    $api_key_file = "data/api/" . explode(".", $fsite)[0] . ".txt";

    if (!file_exists($api_key_file) || trim(@file_get_contents($api_key_file)) === '') {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "⚠️ لا يوجد API مرفوع لموقع $fsite\nاذهب لـ: رفع API لموقع معين",
            'show_alert'        => true,
        ]);
        exit;
    }

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
🌐 *الموقع:* $fsite

اختر التطبيق الذي تريد جلب خدماته:
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '• تيليجرام 📱',         'callback_data' => "rfetch_app-$fadd-1"]],
                [['text' => '• انستجرام 📸',         'callback_data' => "rfetch_app-$fadd-2"]],
                [['text' => '• تيك توك 🎵',          'callback_data' => "rfetch_app-$fadd-3"]],
                [['text' => '• فيسبوك 📘',           'callback_data' => "rfetch_app-$fadd-4"]],
                [['text' => '• تويتر 🐦',            'callback_data' => "rfetch_app-$fadd-5"]],
                [['text' => '• واتساب 💬',           'callback_data' => "rfetch_app-$fadd-6"]],
                [['text' => '• يوتيوب ▶️',           'callback_data' => "rfetch_app-$fadd-7"]],
                [['text' => '• كواي 🎬',             'callback_data' => "rfetch_app-$fadd-8"]],  // 8 → spit num 10
                [['text' => '• سناب شات 👻',         'callback_data' => "rfetch_app-$fadd-9"]],  // 9 → spit num 0
                [['text' => '• خدمات مجانية 🎁',    'callback_data' => "rfetch_app-$fadd-10"]], // 10 → spit num 8
                [['text' => '- رجوع 🔜', 'callback_data' => 'rashq_fetch_menu']],
            ]
        ])
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={اختيار نوع الخدمة (متابعين، مشاهدات...)}==========#
if ($exdata[0] == "rfetch_app") {
    $fadd  = $exdata[1] ?? '';
    $fnum  = $exdata[2] ?? '';
    // =FIX= فحص صحة رقم الموقع قبل عرض الأزرار
    if ($fadd === '' || ($fsite = getRashqSite($fadd)) === "غير معروف") {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "⚠️ الموقع غير موجود، يرجى فتح قائمة الجلب من جديد.",
            'show_alert'        => true,
        ]);
        exit;
    }
    $fApp  = $RashqApps[$fnum] ?? "تطبيق $fnum";

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
🌐 *الموقع:* $fsite
📱 *التطبيق:* $fApp

اختر نوع الخدمة:
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => array_merge(
                array_map(function($tnum, $tname) use ($fadd, $fnum) {
                    return [['text' => $tname, 'callback_data' => "rfetch_type-$fadd-$fnum-$tnum"]];
                }, array_keys($RashqTypes), array_values($RashqTypes)),
                [[['text' => '- رجوع 🔜', 'callback_data' => "rfetch_site-$fadd"]]]
            )
        ])
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={جلب الخدمات من API وعرضها}==========#
if ($exdata[0] == "rfetch_type") {
    $fadd   = $exdata[1] ?? '';
    $fnum   = $exdata[2] ?? '';
    $ftype  = $exdata[3] ?? '';
    $fApp      = $RashqApps[$fnum]   ?? "تطبيق";
    $fTypeName = $RashqTypes[$ftype] ?? "خدمة";

    // =FIX= فحص صحة رقم الموقع قبل كل شيء
    if ($fadd === '' || ($fsite = getRashqSite($fadd)) === "غير معروف") {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "⚠️ الموقع غير موجود، يرجى فتح قائمة الجلب من جديد.",
            'show_alert'        => true,
        ]);
        exit;
    }

    $api_key_file = "data/api/" . explode(".", $fsite)[0] . ".txt";
    $api_key = trim(@file_get_contents($api_key_file));

    if (!$api_key) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "⚠️ لا يوجد API للموقع $fsite\nاذهب إلى: ➕ رفع API لموقع معين",
            'show_alert'        => true,
        ]);
        exit;
    }

    // إشعار بالتحميل
    bot('answercallbackquery', [
        'callback_query_id' => $update->callback_query->id,
        'text'              => "⏳ يتم جلب الخدمات من $fsite ...",
        'show_alert'        => false,
    ]);

    // جلب جميع الخدمات
    $all_services = fetchRashqServices($fsite, $api_key);

    if (!$all_services) {
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "❌ *فشل الاتصال بـ $fsite*\n\n🔧 *الأسباب المحتملة:*\n• مفتاح API غير صحيح أو منتهي الصلاحية\n• الموقع $fsite لا يعمل حالياً\n• انتهت مهلة الاتصال (25 ثانية)\n\n💡 *الحل:* احذف API القديم ثم أعد رفعه من:\n➕ رفع API لموقع معين",
            'parse_mode'   => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => '- رجوع 🔜', 'callback_data' => "rfetch_app-$fadd-$fnum"]],
                    [['text' => '🔄 فتح قائمة الجلب', 'callback_data' => "rashq_fetch_menu"]],
                ]
            ])
        ]);
        exit;
    }

    // تحديد كلمات البحث للتطبيق والنوع المختار
    $app_keywords  = $AppKeywords[$fnum]   ?? [];
    $type_keywords = $TypeKeywords[$ftype] ?? [];
    $app_exclude   = $AppExcludeKeywords[$fnum] ?? [];

    // ── كلمات دالة على المتابعين بقوة (يُستثنى منها عند البحث عن لايكات/تفاعلات/مشاهدات)
    $follower_exclusion_words = ["member", "follower", "subscriber", "متابع", "عضو", "مشترك", "join members", "group member"];
    // أنواع يجب استثناء خدمات المتابعين منها
    $types_exclude_followers = ["2", "3", "7", "8", "9", "13"];

    $filtered = [];
    $app_only = [];

    // تحويل الكلمات المفتاحية lowercase مرة واحدة خارج الـ loop
    $app_kw_low  = array_map(function($k){ return mb_strtolower($k,'UTF-8'); }, $app_keywords);
    $type_kw_low = array_map(function($k){ return mb_strtolower($k,'UTF-8'); }, $type_keywords);
    $excl_low    = array_map(function($k){ return mb_strtolower($k,'UTF-8'); }, $follower_exclusion_words);
    $app_excl_low = array_map(function($k){ return mb_strtolower($k,'UTF-8'); }, $app_exclude);

    foreach ($all_services as $svc) {
        $name_low = mb_strtolower($svc['name'] ?? '', 'UTF-8');
        $cat_low  = mb_strtolower($svc['category'] ?? '', 'UTF-8');
        $combined = $name_low . ' ' . $cat_low;

        // فحص مطابقة التطبيق
        $app_match = false;
        foreach ($app_kw_low as $kw_low) {
            if (matchKeyword($name_low, $kw_low) || matchKeyword($cat_low, $kw_low)) {
                $app_match = true;
                break;
            }
        }

        if (!$app_match) continue;

        // ── فلتر الاستثناء: إذا كانت الخدمة تحتوي على اسم منصة أخرى → نستثنيها
        // (يمنع ظهور خدمات انستجرام عند البحث في تيليجرام والعكس)
        if (!empty($app_excl_low)) {
            $cross_platform = false;
            foreach ($app_excl_low as $excl_kw) {
                if (matchKeyword($name_low, $excl_kw) || matchKeyword($cat_low, $excl_kw)) {
                    $cross_platform = true;
                    break;
                }
            }
            if ($cross_platform) continue;
        }

        $app_only[] = $svc;

        // إذا كان النوع المطلوب ليس متابعين، نستثني الخدمات ذات الكلمات الدالة على المتابعين
        if (in_array((string)$ftype, $types_exclude_followers)) {
            $is_follower_svc = false;
            foreach ($excl_low as $excl_kw) {
                if (matchKeyword($name_low, $excl_kw)) {
                    $is_follower_svc = true;
                    break;
                }
            }
            if ($is_follower_svc) continue;
        }

        // فحص مطابقة النوع (strpos عادي لأن كلمات النوع طويلة بما يكفي)
        $type_match = false;
        foreach ($type_kw_low as $kw_low) {
            if (strpos($combined, $kw_low) !== false) {
                $type_match = true;
                break;
            }
        }
        if ($type_match) $filtered[] = $svc;
    }

    // إذا لم يوجد تطابق مزدوج للتفاعلات → بحث إضافي بأنماط الإيموجي الشائعة
    if (empty($filtered) && $ftype == "8" && !empty($app_only)) {
        $reaction_extra = ["react", "reaction", "emoji", "tg reaction", "🔥", "❤", "👍", "😍"];
        foreach ($app_only as $svc) {
            $name_low2 = mb_strtolower($svc['name'] ?? '', 'UTF-8');
            $is_follower2 = false;
            foreach ($excl_low as $excl_kw) {
                if (matchKeyword($name_low2, $excl_kw)) { $is_follower2 = true; break; }
            }
            if ($is_follower2) continue;
            if (!empty($app_excl_low)) {
                $cross2 = false;
                foreach ($app_excl_low as $excl_kw2) {
                    if (matchKeyword($name_low2, $excl_kw2)) { $cross2 = true; break; }
                }
                if ($cross2) continue;
            }
            foreach ($reaction_extra as $rk) {
                if (strpos($name_low2, mb_strtolower($rk,'UTF-8')) !== false) {
                    $filtered[] = $svc;
                    break;
                }
            }
        }
    }

    // ── Fallback مُقيَّد: داخل خدمات التطبيق الحالي فقط
    if (empty($filtered) && !empty($app_only)) {
        foreach ($app_only as $svc) {
            $name_low3 = mb_strtolower($svc['name'] ?? '', 'UTF-8');
            $cat_low3  = mb_strtolower($svc['category'] ?? '', 'UTF-8');
            $combined3 = $name_low3 . ' ' . $cat_low3;
            if (in_array((string)$ftype, $types_exclude_followers)) {
                $is_f = false;
                foreach ($excl_low as $ek) { if (matchKeyword($name_low3, $ek)){ $is_f=true; break; } }
                if ($is_f) continue;
            }
            foreach ($type_kw_low as $kw_low3) {
                if (strpos($combined3, $kw_low3) !== false) {
                    $filtered[] = $svc;
                    break;
                }
            }
        }
        // ملاحظة: مقيَّد بـ $app_only → لا خدمات من منصات أخرى
    }

    if (empty($filtered)) {
        bot('EditMessageText', [
            'chat_id'      => $chat_id,
            'message_id'   => $message_id,
            'text'         => "
⚠️ *لا توجد خدمات مطابقة*

📱 التطبيق: $fApp
🔖 النوع: $fTypeName
🌐 الموقع: $fsite

- جرب تطبيق أو نوع آخر.
",
            'parse_mode'   => 'Markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => '- رجوع 🔜', 'callback_data' => "rfetch_app-$fadd-$fnum"]],
                ]
            ])
        ]);
        exit;
    }

    // حفظ قائمة الخدمات مؤقتاً (الاحتفاظ بنسبة الربح إن كانت محفوظة مسبقاً)
    $existing_cache_raw = @file_get_contents("data/id/$id/rfetch_cache.json");
    $existing_profit_pct = 0;
    if ($existing_cache_raw) {
        $existing_cache = json_decode($existing_cache_raw, true);
        if (isset($existing_cache['profit_pct'])) {
            $existing_profit_pct = (float)$existing_cache['profit_pct'];
        }
    }
    $cache_key = "rfetch_{$fadd}_{$fnum}_{$ftype}";
    file_put_contents("data/id/$id/rfetch_cache.json", json_encode([
        'fadd'       => $fadd,
        'fnum'       => $fnum,
        'ftype'      => $ftype,
        'services'   => $filtered,
        'page'       => 0,
        'profit_pct' => $existing_profit_pct,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    // عرض أول صفحة
    $page = 0;
    $per_page = 8;
    $total = count($filtered);
    $total_pages = ceil($total / $per_page);
    $page_svcs = array_slice($filtered, $page * $per_page, $per_page);

    $key = [];
    $key['inline_keyboard'][] = [
        ['text' => "🌐 $fsite", 'callback_data' => 'no'],
        ['text' => "📱 $fApp | $fTypeName", 'callback_data' => 'no'],
    ];
    $key['inline_keyboard'][] = [
        ['text' => $existing_profit_pct > 0 ? "💰 السعر/1000 (+{$existing_profit_pct}% ربح)" : "💰 السعر / 1000", 'callback_data' => 'no'],
        ['text' => "📋 الخدمة", 'callback_data' => 'no'],
    ];

    foreach ($page_svcs as $idx => $svc) {
        $svc_id    = $svc['service'] ?? $svc['id'] ?? '?';
        $svc_name  = mb_substr($svc['name'] ?? 'خدمة', 0, 30, 'UTF-8');
        $svc_rate  = $svc['rate'] ?? '?';
        $svc_rate_rub = is_numeric($svc_rate)
            ? round($svc_rate * 30 * (1 + $existing_profit_pct / 100), 2)
            : $svc_rate;
        $svc_min   = $svc['min']  ?? '?';
        $svc_max   = $svc['max']  ?? '?';
        $real_idx  = ($page * $per_page) + $idx;
        $key['inline_keyboard'][] = [
            ['text' => "₽ $svc_rate_rub", 'callback_data' => "rfetch_view-$real_idx"],
            ['text' => "#$svc_id $svc_name", 'callback_data' => "rfetch_view-$real_idx"],
        ];
    }

    // أزرار الصفحات
    $nav = [];
    if ($page > 0) {
        $nav[] = ['text' => '◀️ السابق', 'callback_data' => "rfetch_page-" . ($page - 1)];
    }
    $nav[] = ['text' => "📄 " . ($page + 1) . " / $total_pages", 'callback_data' => 'no'];
    if (($page + 1) < $total_pages) {
        $nav[] = ['text' => 'التالي ▶️', 'callback_data' => "rfetch_page-" . ($page + 1)];
    }
    if (!empty($nav)) $key['inline_keyboard'][] = $nav;

    $key['inline_keyboard'][] = [
        ['text' => '- رجوع 🔜', 'callback_data' => "rfetch_app-$fadd-$fnum"],
        ['text' => '💹 نسبة الربح', 'callback_data' => "rfetch_profit-$fadd-$fnum-$ftype"],
    ];

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
✅ *تم جلب الخدمات بنجاح*

🌐 الموقع: *$fsite*
📱 التطبيق: *$fApp*
🔖 النوع: *$fTypeName*
📦 عدد الخدمات: *$total*" . ($existing_profit_pct > 0 ? "\n💹 نسبة الربح المفعّلة: *{$existing_profit_pct}%*" : "") . "

اضغط على خدمة لعرض تفاصيلها وإضافتها:
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode($key),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={صفحات القائمة}==========#
if ($exdata[0] == "rfetch_page") {
    $page = (int)$exdata[1];
    $cache_raw = @file_get_contents("data/id/$id/rfetch_cache.json");
    if (!$cache_raw) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "⏱ انتهت الجلسة، أعد الجلب.",
            'show_alert'        => true,
        ]);
        exit;
    }
    $cache    = json_decode($cache_raw, true);
    $filtered = $cache['services'];
    $fadd     = $cache['fadd']; // =FIX= قراءة fadd من الـ cache بدلاً من $fadd_ds الغير معرّف
    $fnum     = $cache['fnum'];
    $ftype    = $cache['ftype'];
    $fsite    = getRashqSite($fadd);
    $fApp     = $RashqApps[$fnum]    ?? "تطبيق";
    $fTypeName= $RashqTypes[$ftype]  ?? "خدمة";

    $per_page   = 8;
    $total      = count($filtered);
    $total_pages= ceil($total / $per_page);
    $page       = max(0, min($page, $total_pages - 1));
    $page_svcs  = array_slice($filtered, $page * $per_page, $per_page);

    // تطبيق نسبة الربح على الأسعار المعروضة
    $profit_pct_pg = (float)($cache['profit_pct'] ?? 0);

    $key = [];
    $key['inline_keyboard'][] = [
        ['text' => "🌐 $fsite", 'callback_data' => 'no'],
        ['text' => "📱 $fApp | $fTypeName", 'callback_data' => 'no'],
    ];
    $key['inline_keyboard'][] = [
        ['text' => $profit_pct_pg > 0 ? "💰 السعر/1000 (+{$profit_pct_pg}% ربح)" : "💰 السعر / 1000", 'callback_data' => 'no'],
        ['text' => "📋 الخدمة", 'callback_data' => 'no'],
    ];

    foreach ($page_svcs as $idx => $svc) {
        $svc_id   = $svc['service'] ?? $svc['id'] ?? '?';
        $svc_name = mb_substr($svc['name'] ?? 'خدمة', 0, 30, 'UTF-8');
        $svc_rate = $svc['rate'] ?? '?';
        $svc_rate_rub = is_numeric($svc_rate)
            ? round($svc_rate * 30 * (1 + $profit_pct_pg / 100), 2)
            : $svc_rate;
        $real_idx = ($page * $per_page) + $idx;
        $key['inline_keyboard'][] = [
            ['text' => "₽ $svc_rate_rub", 'callback_data' => "rfetch_view-$real_idx"],
            ['text' => "#$svc_id $svc_name", 'callback_data' => "rfetch_view-$real_idx"],
        ];
    }

    $nav = [];
    if ($page > 0)
        $nav[] = ['text' => '◀️ السابق', 'callback_data' => "rfetch_page-" . ($page - 1)];
    $nav[] = ['text' => ($page + 1) . "/$total_pages", 'callback_data' => 'no'];
    if (($page + 1) < $total_pages)
        $nav[] = ['text' => 'التالي ▶️', 'callback_data' => "rfetch_page-" . ($page + 1)];
    if (!empty($nav)) $key['inline_keyboard'][] = $nav;

    $key['inline_keyboard'][] = [
        ['text' => '- رجوع 🔜', 'callback_data' => "rfetch_app-$fadd-$fnum"],
        ['text' => '💹 نسبة الربح', 'callback_data' => "rfetch_profit-$fadd-$fnum-$ftype"],
    ];

    $cache['page'] = $page;
    file_put_contents("data/id/$id/rfetch_cache.json", json_encode($cache, JSON_UNESCAPED_UNICODE), LOCK_EX);

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
✅ *قائمة الخدمات* - صفحة " . ($page + 1) . " / $total_pages

🌐 الموقع: *$fsite*
📱 التطبيق: *$fApp*
🔖 النوع: *$fTypeName*

اضغط على خدمة لعرض تفاصيلها:
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode($key),
    ]);
    exit;
}

#=========={عرض تفاصيل خدمة مع زر تعديل وإضافة}==========#
if ($exdata[0] == "rfetch_view") {
    $svc_idx  = (int)$exdata[1];
    $cache_raw = @file_get_contents("data/id/$id/rfetch_cache.json");
    if (!$cache_raw) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⏱ انتهت الجلسة، أعد الجلب.",
            'show_alert' => true,
        ]);
        exit;
    }
    $cache   = json_decode($cache_raw, true);
    $filtered = $cache['services'];
    $fadd    = $cache['fadd'];
    $fnum    = $cache['fnum'];
    $ftype   = $cache['ftype'];
    $fsite   = getRashqSite($fadd);
    $fApp    = $RashqApps[$fnum]   ?? "تطبيق";
    $fTypeName = $RashqTypes[$ftype] ?? "خدمة";
    $cur_page = $cache['page'] ?? 0;

    if (!isset($filtered[$svc_idx])) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⚠️ الخدمة غير موجودة.",
            'show_alert' => true,
        ]);
        exit;
    }

    $svc      = $filtered[$svc_idx];
    $svc_id   = $svc['service'] ?? $svc['id'] ?? '0';
    $svc_name = $svc['name']    ?? 'خدمة';
    $svc_rate = $svc['rate']    ?? '0';
    $svc_min  = $svc['min']     ?? '10';
    $svc_max  = $svc['max']     ?? '10000';
    $svc_cat  = $svc['category'] ?? $fApp;
    $svc_type = $svc['type']    ?? 'Default';

    // تطبيق نسبة الربح على السعر المعروض
    $profit_pct_v  = (float)($cache['profit_pct'] ?? 0);
    $rate_in_rub   = round($svc_rate * 30, 4);
    $rate_with_profit = $profit_pct_v > 0 ? round($rate_in_rub * (1 + $profit_pct_v / 100), 4) : $rate_in_rub;
    $price_per_one = round($rate_with_profit / 1000, 4);
    $profit_label  = $profit_pct_v > 0 ? " _(شامل ربح {$profit_pct_v}%)_" : "";

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
📋 *تفاصيل الخدمة*

🆔 رقم الخدمة: *#$svc_id*
📝 الاسم: *$svc_name*
🗂 القسم: *$svc_cat*
🔖 النوع: *$svc_type*

💰 السعر / 1000: *₽ $rate_with_profit*$profit_label
💵 السعر للعضو: *₽ $price_per_one*
🔖 سعر المورد: _(${svc_rate}$ = ₽$rate_in_rub)_
📉 الحد الأدنى: *$svc_min*
📈 الحد الأعلى: *$svc_max*

🌐 الموقع: *$fsite*
📱 التطبيق: *$fApp*
🔖 نوع الجلب: *$fTypeName*

اضغط *إضافة مباشر* لإضافتها بمعلوماتها الأصلية،
أو *تعديل قبل الإضافة* لتعديل الاسم والسعر والحدود.
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '✅ إضافة مباشر', 'callback_data' => "rfetch_addnow-$svc_idx"],
                    ['text' => '✏️ تعديل قبل الإضافة', 'callback_data' => "rfetch_edit-$svc_idx"],
                ],
                [['text' => '- رجوع للقائمة 🔜', 'callback_data' => "rfetch_page-$cur_page"]],
            ]
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={إضافة مباشر بدون تعديل - مع اختيار القسم}==========#
if ($exdata[0] == "rfetch_addnow") {
    $svc_idx   = (int)$exdata[1];
    $cache_raw = @file_get_contents("data/id/$id/rfetch_cache.json");
    if (!$cache_raw) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⏱ انتهت الجلسة، أعد الجلب.",
            'show_alert' => true,
        ]);
        exit;
    }
    $cache    = json_decode($cache_raw, true);
    $filtered = $cache['services'];
    $fsite    = getRashqSite($cache['fadd']);
    $cur_page = $cache['page'] ?? 0;

    if (!isset($filtered[$svc_idx])) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⚠️ الخدمة غير موجودة.",
            'show_alert' => true,
        ]);
        exit;
    }
    $svc_name = mb_substr($filtered[$svc_idx]['name'] ?? 'خدمة', 0, 35, 'UTF-8');

    // الخطوة 1: اختيار القسم الرئيسي
    $section_btns = [];
    $built_in_sections = [
        "1"  => "📱 تيليجرام",
        "2"  => "📸 انستجرام",
        "3"  => "🎵 تيك توك",
        "4"  => "📘 فيسبوك",
        "5"  => "🐦 تويتر",
        "6"  => "💬 واتساب",
        "7"  => "▶️ يوتيوب",
        "10" => "🎬 كواي",
        "0"  => "👻 سناب شات",
        "8"  => "🎁 خدمات مجانية",
    ];
    $fadd_an = $cache['fadd']; // fadd يُمرَّر في كل خطوة
    foreach ($built_in_sections as $sec_num => $sec_label) {
        $section_btns[] = [['text' => $sec_label, 'callback_data' => "rfetch_sectype-$fadd_an-$svc_idx-$sec_num"]];
    }
    $custom_sections = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];
    foreach ($custom_sections as $sec) {
        $section_btns[] = [['text' => "{$sec['emoji']} {$sec['name']}", 'callback_data' => "rfetch_sectype-$fadd_an-$svc_idx-{$sec['num']}"]];
    }
    $section_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "rfetch_view-$svc_idx"]];

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
📂 *الخطوة 1 من 2 — اختر القسم:*

📝 الخدمة: *$svc_name*
🌐 الموقع: *$fsite*
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode(['inline_keyboard' => $section_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={الخطوة 2: اختيار النوع الفرعي بعد القسم}==========#
if ($exdata[0] == "rfetch_sectype") {
    $fadd_st    = $exdata[1];
    $svc_idx    = (int)$exdata[2];
    $target_num = $exdata[3]; // رقم القسم الرئيسي
    $cache_raw  = @file_get_contents("data/id/$id/rfetch_cache.json");
    if (!$cache_raw) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⏱ انتهت الجلسة، أعد الجلب.",
            'show_alert' => true,
        ]);
        exit;
    }
    $cache    = json_decode($cache_raw, true);
    $filtered = $cache['services'];
    $fsite    = getRashqSite($cache['fadd']);

    if (!isset($filtered[$svc_idx])) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⚠️ الخدمة غير موجودة.",
            'show_alert' => true,
        ]);
        exit;
    }
    $svc_name = mb_substr($filtered[$svc_idx]['name'] ?? 'خدمة', 0, 35, 'UTF-8');

    // اسم القسم المختار
    $_sec_names_map = [
        "1"=>"📱 تيليجرام","2"=>"📸 انستجرام","3"=>"🎵 تيك توك",
        "4"=>"📘 فيسبوك","5"=>"🐦 تويتر","6"=>"💬 واتساب",
        "7"=>"▶️ يوتيوب","10"=>"🎬 كواي","0"=>"👻 سناب شات","8"=>"🎁 خدمات مجانية",
    ];
    $sec_display = $_sec_names_map[$target_num] ?? "قسم $target_num";
    // فحص الأقسام المخصصة
    $_cust_secs = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];
    foreach ($_cust_secs as $_cs) {
        if ((string)$_cs['num'] == (string)$target_num) {
            $sec_display = "{$_cs['emoji']} {$_cs['name']}"; break;
        }
    }

    // الأنواع الثابتة لكل قسم (شاملة)
    $_types_by_sec = [
        "1"  => ["1"=>"أعضاء/متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","4"=>"بريميوم/نجوم ⭐","7"=>"تعليقات 💬","8"=>"رياكشنات 🔥","9"=>"مشاركات 🔁"],
        "2"  => ["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","7"=>"تعليقات 💬","9"=>"مشاركات 🔁","13"=>"حفظ 🔖","8"=>"تفاعلات 🔥"],
        "3"  => ["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","7"=>"تعليقات 💬","9"=>"مشاركات 🔁","13"=>"حفظ 🔖","5"=>"عملات 🪙"],
        "4"  => ["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","7"=>"تعليقات 💬","9"=>"مشاركات 🔁","8"=>"تفاعلات 🔥"],
        "5"  => ["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","9"=>"ريتويت 🔁"],
        "6"  => ["1"=>"متابعين 👥","3"=>"لايكات 👍","6"=>"استطلاع 📊"],
        "7"  => ["1"=>"مشتركين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","7"=>"تعليقات 💬"],
        "10" => ["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍"],
        "0"  => ["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍"],
        "8"  => ["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍"],
    ];

    // أنواع القسم الثابتة + الفرعية من sub_types.json
    $base_types = $_types_by_sec[$target_num] ?? ["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍"];
    $_sub_all = json_decode(@file_get_contents("data/sub_types.json") ?: '{}', true) ?: [];
    $_extra_types = $_sub_all[$target_num] ?? [];

    $type_btns = [];
    // أزرار الأنواع الثابتة
    foreach ($base_types as $_tnum => $_tname) {
        $type_btns[] = [['text' => $_tname, 'callback_data' => "rfetch_dosec-$fadd_st-$svc_idx-$target_num-$_tnum"]];
    }
    foreach ($_extra_types as $_et) {
        $type_btns[] = [['text' => "{$_et['emoji']} {$_et['btn_text']}", 'callback_data' => "rfetch_dosec-$fadd_st-$svc_idx-$target_num-{$_et['type']}"]];
    }
    $type_btns[] = [['text' => '- رجوع 🔜', 'callback_data' => "rfetch_addnow-$svc_idx"]];

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
📂 *الخطوة 2 من 2 — اختر النوع:*

📝 الخدمة: *$svc_name*
📂 القسم: *$sec_display*
🌐 الموقع: *$fsite*
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode(['inline_keyboard' => $type_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={تنفيذ الإضافة بعد اختيار القسم والنوع}==========#
if ($exdata[0] == "rfetch_dosec") {
    $fadd_ds    = $exdata[1]; // fadd مباشرة من callback - لا يتأثر بالـ cache
    $svc_idx    = (int)$exdata[2];
    $target_num = $exdata[3]; // رقم القسم (num) في spit.php
    $target_type= $exdata[4] ?? null; // رقم النوع (type) المختار
    $cache_raw  = @file_get_contents("data/id/$id/rfetch_cache.json");
    if (!$cache_raw) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⏱ انتهت الجلسة، أعد الجلب.",
            'show_alert' => true,
        ]);
        exit;
    }
    $cache    = json_decode($cache_raw, true);
    $filtered = $cache['services'];
    $fadd     = $cache['fadd'];
    $fnum     = $cache['fnum'];
    $ftype    = $cache['ftype'];
    $fsite    = getRashqSite($fadd);
    $fApp     = $RashqApps[$fnum]   ?? "تطبيق";
    $fTypeName= $RashqTypes[$ftype] ?? "خدمة";
    $cur_page = $cache['page'] ?? 0;

    if (!isset($filtered[$svc_idx])) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⚠️ الخدمة غير موجودة.",
            'show_alert' => true,
        ]);
        exit;
    }

    $svc      = $filtered[$svc_idx];
    $svc_id   = $svc['service'] ?? $svc['id'] ?? '0';
    $svc_name = $svc['name']    ?? 'خدمة';
    $svc_rate = $svc['rate']    ?? '0';
    $svc_rate_rub_original = round($svc_rate * 30, 4);
    $svc_min  = $svc['min']     ?? '10';
    $svc_max  = $svc['max']     ?? '10000';

    // تطبيق نسبة الربح
    $profit_pct = isset($cache['profit_pct']) ? (float)$cache['profit_pct'] : 0;
    $svc_rate_rub = $profit_pct > 0
        ? round($svc_rate_rub_original * (1 + $profit_pct / 100), 4)
        : $svc_rate_rub_original;
    $profit_note = $profit_pct > 0
        ? "\n🔖 سعر المورد: _(${svc_rate}\$ = ₽$svc_rate_rub_original)_\n💹 بعد ربح {$profit_pct}%: *₽ $svc_rate_rub*"
        : "";

    // اسم النوع المختار
    $_all_type_names = [
        "1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","4"=>"بريميوم/نجوم ⭐",
        "5"=>"عملات 🪙","6"=>"استطلاع 📊","7"=>"تعليقات 💬","8"=>"تفاعلات 🔥",
        "9"=>"مشاركات 🔁","10"=>"مشتركين 📧","11"=>"نقرات 👆","12"=>"حزمة 📦",
        "13"=>"حفظ/مفضلة 🔖",
    ];
    // الأنواع الفرعية المضافة
    $_sub_all_d = json_decode(@file_get_contents("data/sub_types.json") ?: '{}', true) ?: [];
    foreach ($_sub_all_d as $_sec_d) {
        foreach ($_sec_d as $_st_d) {
            if (isset($_st_d['type'])) $_all_type_names[(string)$_st_d['type']] = $_st_d['emoji'].' '.$_st_d['btn_text'];
        }
    }

    // إذا لم يُختر نوع بعد (قادم من مسار قديم بدون خطوة النوع) → استخدم ftype من الجلب
    $final_type = $target_type ?? $ftype;
    $final_type_name = $_all_type_names[$final_type] ?? "خدمة $final_type";

    $code  = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 14);
    $increase = json_decode(@file_get_contents('data/increase.json') ?: '{}', true) ?: [];

    $increase["idplus"][$code]['add']        = $fadd;
    $increase["idplus"][$code]['site']       = getRashqSite($fadd); // اسم الموقع مباشرة للأمان
    $increase["idplus"][$code]['num']        = $target_num;
    $increase["idplus"][$code]['type']       = $final_type; // النوع المختار من المستخدم
    $increase["idplus"][$code]['ID']         = $svc_id;
    $increase["idplus"][$code]['name']       = $svc_name;
    $increase["idplus"][$code]['quality']    = $svc['category'] ?? $fApp;
    $increase["idplus"][$code]['security']   = "مضمون";
    $increase["idplus"][$code]['get_off']    = "0%";
    $increase["idplus"][$code]['explained']  = "رابط الحساب أو القناة";
    $increase["idplus"][$code]['Type']       = $final_type_name;
    $increase["idplus"][$code]['price']      = $svc_rate_rub;
    $increase["idplus"][$code]['speed']      = "سريع";
    $increase["idplus"][$code]['start_time'] = "فوري";
    $increase["idplus"][$code]['minimum']    = $svc_min;
    $increase["idplus"][$code]['maximum']    = $svc_max;

    file_put_contents('data/increase.json', json_encode($increase, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT), LOCK_EX);

    // اسم القسم للعرض
    $all_sections = [
        "0" => "👻 سناب شات", "1" => "📱 تيليجرام", "2" => "📸 انستجرام",
        "3" => "🎵 تيك توك",  "4" => "📘 فيسبوك",   "5" => "🐦 تويتر",
        "6" => "💬 واتساب",  "7" => "▶️ يوتيوب",   "8" => "🎁 خدمات مجانية",
        "10" => "🎬 كواي",
    ];
    $section_name = $all_sections[$target_num] ?? "قسم $target_num";
    $custom_sections = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];
    foreach ($custom_sections as $s) {
        if ($s['num'] == $target_num) { $section_name = "{$s['emoji']} {$s['name']}"; break; }
    }

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
✅ *تم إضافة الخدمة بنجاح!*

🆔 رقم الخدمة: *#$svc_id*
📝 الاسم: *$svc_name*
💰 السعر / 1000: *₽ $svc_rate_rub*$profit_note
📉 الحد الأدنى: *$svc_min*
📈 الحد الأعلى: *$svc_max*
🌐 الموقع: *$fsite*
📂 القسم: *$section_name*
🔖 النوع: *$final_type_name*
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '➕ إضافة خدمة أخرى', 'callback_data' => "rfetch_page-$cur_page"]],
                [['text' => '- لوحة التحكم 🔜', 'callback_data' => 'Splash_settings']],
            ]
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={تعديل الخدمة قبل الإضافة - خطوة 1: اسم الخدمة والسعر}==========#
if ($exdata[0] == "rfetch_edit") {
    $svc_idx   = (int)$exdata[1];
    $cache_raw = @file_get_contents("data/id/$id/rfetch_cache.json");
    if (!$cache_raw) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⏱ انتهت الجلسة.",
            'show_alert' => true,
        ]);
        exit;
    }
    $cache    = json_decode($cache_raw, true);
    $filtered = $cache['services'];
    $fadd     = $cache['fadd'];
    $fnum     = $cache['fnum'];
    $ftype    = $cache['ftype'];
    $fsite    = getRashqSite($fadd);
    $fApp     = $RashqApps[$fnum]   ?? "تطبيق";
    $fTypeName= $RashqTypes[$ftype] ?? "خدمة";
    $cur_page = $cache['page'] ?? 0;

    $svc      = $filtered[$svc_idx];
    $svc_id   = $svc['service'] ?? $svc['id'] ?? '0';
    $svc_name = $svc['name']    ?? 'خدمة';
    $svc_rate = $svc['rate']    ?? '0';
    $svc_rate_rub_base = round($svc_rate * 30, 4);
    $profit_pct_ed = (float)($cache['profit_pct'] ?? 0);
    $svc_rate_rub  = $profit_pct_ed > 0
        ? round($svc_rate_rub_base * (1 + $profit_pct_ed / 100), 4)
        : $svc_rate_rub_base;
    $svc_min  = $svc['min']     ?? '10';
    $svc_max  = $svc['max']     ?? '10000';
    $edit_profit_note = $profit_pct_ed > 0 ? "\n💹 نسبة الربح: *{$profit_pct_ed}%* (مُضمّنة في السعر أعلاه)" : "";

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
✏️ *تعديل الخدمة قبل الإضافة*

القيم الحالية:
🆔 ID: *#$svc_id*
📝 الاسم: *$svc_name*
💰 السعر/1000: *₽ $svc_rate_rub*$edit_profit_note
📉 الأدنى: *$svc_min* | 📈 الأعلى: *$svc_max*

أرسل الآن القيم الجديدة بهذا الشكل (سطر لكل قيمة):
```
الاسم الجديد
السعر الجديد
الحد الأدنى
الحد الأعلى
شرح المطلوب من العميل
```
مثال:
```
متابعين تيك توك ذهبي
5.5
100
50000
رابط حسابك في تيك توك
```
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '- رجوع 🔜', 'callback_data' => "rfetch_view-$svc_idx"]],
            ]
        ]),
    ]);
    file_put_contents("data/id/$id/step.txt", "rfetch_edit_input|$svc_idx");
    exit;
}

#=========={استقبال التعديل من الأدمن}==========#
if ($text && $text != '/start' && $exstep[0] == 'rfetch_edit_input') {
    $svc_idx   = (int)$exstep[1];
    $cache_raw = @file_get_contents("data/id/$id/rfetch_cache.json");
    if (!$cache_raw) {
        bot('sendMessage', [
            'chat_id'    => $chat_id,
            'text'       => "⏱ انتهت الجلسة، أعد الجلب من البداية.",
            'parse_mode' => 'Markdown',
        ]);
        @unlink("data/id/$id/step.txt");
        exit;
    }
    $cache    = json_decode($cache_raw, true);
    $filtered = $cache['services'];
    $fadd     = $cache['fadd'];
    $fnum     = $cache['fnum'];
    $ftype    = $cache['ftype'];
    $fsite    = getRashqSite($fadd);
    $fApp     = $RashqApps[$fnum]   ?? "تطبيق";
    $fTypeName= $RashqTypes[$ftype] ?? "خدمة";

    $svc      = $filtered[$svc_idx];
    $svc_id   = $svc['service'] ?? $svc['id'] ?? '0';

    // قراءة السطور
    $lines    = explode("\n", trim($text));
    $new_name = trim($lines[0] ?? ($svc['name'] ?? 'خدمة'));
    // السعر الافتراضي: يشمل نسبة الربح إذا كانت محددة
    $_base_rub_ei   = round(($svc['rate'] ?? 0) * 30, 4);
    $_profit_pct_ei = (float)($cache['profit_pct'] ?? 0);
    $_default_rate  = $_profit_pct_ei > 0
        ? round($_base_rub_ei * (1 + $_profit_pct_ei / 100), 4)
        : $_base_rub_ei;
    $new_rate = isset($lines[1]) && trim($lines[1]) !== '' ? trim($lines[1]) : $_default_rate;
    $new_min  = trim($lines[2] ?? ($svc['min']  ?? '10'));
    $new_max  = trim($lines[3] ?? ($svc['max']  ?? '10000'));
    $new_expl = trim($lines[4] ?? "رابط الحساب");

    // تحقق من السعر
    if (!is_numeric($new_rate) || $new_rate <= 0) {
        bot('sendMessage', [
            'chat_id'           => $chat_id,
            'text'              => "❌ *السعر يجب أن يكون رقماً موجباً.*\nحاول مرة أخرى.",
            'parse_mode'        => 'Markdown',
            'reply_to_message_id' => $message_id,
        ]);
        exit;
    }
    if (!is_numeric($new_min) || !is_numeric($new_max) || (int)$new_min > (int)$new_max) {
        bot('sendMessage', [
            'chat_id'           => $chat_id,
            'text'              => "❌ *الحد الأدنى يجب أن يكون أقل من الحد الأعلى.*\nحاول مرة أخرى.",
            'parse_mode'        => 'Markdown',
            'reply_to_message_id' => $message_id,
        ]);
        exit;
    }

    // عرض ملخص التعديل - اختيار القسم الرئيسي أولاً
    $array = substr(str_shuffle("0123456789"), 0, 10);
    $edit_data = "$svc_idx|$fadd|$fnum|$ftype|$svc_id|$new_name|$new_rate|$new_min|$new_max|$new_expl";
    file_put_contents("data/id/$id/$array.txt", $edit_data);

    // الخطوة 1: اختيار القسم الرئيسي
    $section_btns = [];
    $built_in_sections = [
        "1"  => "📱 تيليجرام", "2"  => "📸 انستجرام", "3"  => "🎵 تيك توك",
        "4"  => "📘 فيسبوك",  "5"  => "🐦 تويتر",    "6"  => "💬 واتساب",
        "7"  => "▶️ يوتيوب", "10" => "🎬 كواي",      "0"  => "👻 سناب شات",
        "8"  => "🎁 خدمات مجانية",
    ];
    foreach ($built_in_sections as $sec_num => $sec_label) {
        // بعد اختيار القسم → rfetch_confirm_sec لاختيار النوع
        $section_btns[] = [['text' => $sec_label, 'callback_data' => "rfetch_confirm_sec-$array-$sec_num"]];
    }
    $custom_sections = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];
    foreach ($custom_sections as $sec) {
        $section_btns[] = [['text' => "{$sec['emoji']} {$sec['name']}", 'callback_data' => "rfetch_confirm_sec-$array-{$sec['num']}"]];
    }
    $section_btns[] = [['text' => '✏️ تعديل مرة أخرى', 'callback_data' => "rfetch_edit-$svc_idx"]];
    $section_btns[] = [['text' => '❌ إلغاء', 'callback_data' => "rfetch_view-$svc_idx"]];

    bot('sendMessage', [
        'chat_id'    => $chat_id,
        'text'       => "
📋 *ملخص الخدمة المعدلة*

🆔 رقم الخدمة: *#$svc_id*
📝 الاسم: *$new_name*
💰 السعر/1000: *₽ $new_rate*
📉 الحد الأدنى: *$new_min*
📈 الحد الأعلى: *$new_max*
📌 المطلوب: *$new_expl*
🌐 الموقع: *$fsite*

📂 *الخطوة 1 من 2 — اختر القسم:*
",
        'parse_mode'        => 'Markdown',
        'reply_to_message_id' => $message_id,
        'reply_markup'      => json_encode(['inline_keyboard' => $section_btns]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={تعديل — الخطوة 2: اختيار النوع بعد القسم}==========#
if ($exdata[0] == "rfetch_confirm_sec") {
    $array      = $exdata[1];
    $target_num = $exdata[2];

    $edit_raw = @file_get_contents("data/id/$id/$array.txt");
    if (!$edit_raw) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⏱ انتهت الجلسة.",
            'show_alert' => true,
        ]);
        exit;
    }
    $exp      = explode("|", $edit_raw);
    $svc_idx  = $exp[0];
    $new_name = $exp[5];
    $new_rate = $exp[6];

    // اسم القسم
    $_sec_nm = [
        "1"=>"📱 تيليجرام","2"=>"📸 انستجرام","3"=>"🎵 تيك توك",
        "4"=>"📘 فيسبوك","5"=>"🐦 تويتر","6"=>"💬 واتساب",
        "7"=>"▶️ يوتيوب","10"=>"🎬 كواي","0"=>"👻 سناب شات","8"=>"🎁 خدمات مجانية",
    ];
    $sec_display = $_sec_nm[$target_num] ?? "قسم $target_num";
    $_cust = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];
    foreach ($_cust as $_cs) {
        if ((string)$_cs['num'] == (string)$target_num) { $sec_display = "{$_cs['emoji']} {$_cs['name']}"; break; }
    }

    // الأنواع
    $_types_cs = [
        "1" =>["1"=>"أعضاء/متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","4"=>"بريميوم/نجوم ⭐","7"=>"تعليقات 💬","8"=>"رياكشنات 🔥","9"=>"مشاركات 🔁"],
        "2" =>["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","7"=>"تعليقات 💬","9"=>"مشاركات 🔁","13"=>"حفظ 🔖","8"=>"تفاعلات 🔥"],
        "3" =>["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","7"=>"تعليقات 💬","9"=>"مشاركات 🔁","13"=>"حفظ 🔖","5"=>"عملات 🪙"],
        "4" =>["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","7"=>"تعليقات 💬","9"=>"مشاركات 🔁","8"=>"تفاعلات 🔥"],
        "5" =>["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","9"=>"ريتويت 🔁"],
        "6" =>["1"=>"متابعين 👥","3"=>"لايكات 👍","6"=>"استطلاع 📊"],
        "7" =>["1"=>"مشتركين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","7"=>"تعليقات 💬"],
        "10"=>["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍"],
        "0" =>["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍"],
        "8" =>["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍"],
    ];
    $base_types_cs = $_types_cs[$target_num] ?? ["1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍"];
    $_sub_cs = json_decode(@file_get_contents("data/sub_types.json") ?: '{}', true) ?: [];
    $_extra_cs = $_sub_cs[$target_num] ?? [];

    $type_btns_cs = [];
    foreach ($base_types_cs as $_tn => $_tname) {
        $type_btns_cs[] = [['text' => $_tname, 'callback_data' => "rfetch_confirm-$array-$target_num-$_tn"]];
    }
    foreach ($_extra_cs as $_et_cs) {
        $type_btns_cs[] = [['text' => "{$_et_cs['emoji']} {$_et_cs['btn_text']}", 'callback_data' => "rfetch_confirm-$array-$target_num-{$_et_cs['type']}"]];
    }
    $type_btns_cs[] = [['text' => '- رجوع 🔜', 'callback_data' => "rfetch_edit-$svc_idx"]];

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
📋 *الخدمة:* $new_name
💰 *السعر:* ₽ $new_rate
📂 *القسم:* $sec_display

📂 *الخطوة 2 من 2 — اختر النوع:*
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode(['inline_keyboard' => $type_btns_cs]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={تأكيد الإضافة بعد التعديل}==========#
if ($exdata[0] == "rfetch_confirm") {
    $array      = $exdata[1];
    $target_num = $exdata[2] ?? null;
    $target_type_c = $exdata[3] ?? null; // النوع المختار

    $edit_raw = @file_get_contents("data/id/$id/$array.txt");
    if (!$edit_raw) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text' => "⏱ انتهت الجلسة.",
            'show_alert' => true,
        ]);
        exit;
    }

    $exp      = explode("|", $edit_raw);
    $svc_idx  = $exp[0];
    $fadd     = $exp[1];
    $fnum     = $exp[2];
    $ftype    = $exp[3]; // النوع الأصلي من الجلب
    $svc_id   = $exp[4];
    $new_name = $exp[5];
    $new_rate = $exp[6];
    $new_min  = $exp[7];
    $new_max  = $exp[8];
    $new_expl = $exp[9] ?? "رابط الحساب";

    $fsite    = getRashqSite($fadd);
    $fApp     = $RashqApps[$fnum]   ?? "تطبيق";

    if (!$target_num) {
        $target_num = $RashqToSpitNum[$fnum] ?? $fnum;
    }

    // النوع النهائي: المختار أو الأصلي
    $final_type_c = $target_type_c ?? $ftype;
    $_atn_c = [
        "1"=>"متابعين 👥","2"=>"مشاهدات 👁","3"=>"لايكات 👍","4"=>"بريميوم/نجوم ⭐",
        "5"=>"عملات 🪙","6"=>"استطلاع 📊","7"=>"تعليقات 💬","8"=>"تفاعلات 🔥",
        "9"=>"مشاركات 🔁","10"=>"مشتركين 📧","11"=>"نقرات 👆","12"=>"حزمة 📦",
        "13"=>"حفظ/مفضلة 🔖",
    ];
    $_sub_c = json_decode(@file_get_contents("data/sub_types.json") ?: '{}', true) ?: [];
    foreach ($_sub_c as $_sc_arr) {
        foreach ($_sc_arr as $_sc_t) {
            if (isset($_sc_t['type'])) $_atn_c[(string)$_sc_t['type']] = $_sc_t['emoji'].' '.$_sc_t['btn_text'];
        }
    }
    $final_type_name_c = $_atn_c[$final_type_c] ?? "خدمة $final_type_c";

    $code  = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 14);
    $increase = json_decode(@file_get_contents('data/increase.json') ?: '{}', true) ?: [];

    $increase["idplus"][$code]['add']        = $fadd;
    $increase["idplus"][$code]['site']       = getRashqSite($fadd); // اسم الموقع للأمان
    $increase["idplus"][$code]['num']        = $target_num;
    $increase["idplus"][$code]['type']       = $final_type_c;
    $increase["idplus"][$code]['ID']         = $svc_id;
    $increase["idplus"][$code]['name']       = $new_name;
    $increase["idplus"][$code]['quality']    = $fApp;
    $increase["idplus"][$code]['security']   = "مضمون";
    $increase["idplus"][$code]['get_off']    = "0%";
    $increase["idplus"][$code]['explained']  = $new_expl;
    $increase["idplus"][$code]['Type']       = $final_type_name_c;
    $increase["idplus"][$code]['price']      = $new_rate;
    $increase["idplus"][$code]['speed']      = "سريع";
    $increase["idplus"][$code]['start_time'] = "فوري";
    $increase["idplus"][$code]['minimum']    = $new_min;
    $increase["idplus"][$code]['maximum']    = $new_max;

    file_put_contents('data/increase.json', json_encode($increase, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT), LOCK_EX);

    // اسم القسم
    $all_sections_c = [
        "0"=>"👻 سناب شات","1"=>"📱 تيليجرام","2"=>"📸 انستجرام",
        "3"=>"🎵 تيك توك","4"=>"📘 فيسبوك","5"=>"🐦 تويتر",
        "6"=>"💬 واتساب","7"=>"▶️ يوتيوب","8"=>"🎁 خدمات مجانية","10"=>"🎬 كواي",
    ];
    $section_name_c = $all_sections_c[$target_num] ?? "قسم $target_num";
    $_cs_c = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];
    foreach ($_cs_c as $_s_c) {
        if ($_s_c['num'] == $target_num) { $section_name_c = "{$_s_c['emoji']} {$_s_c['name']}"; break; }
    }

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
✅ *تم إضافة الخدمة المعدلة بنجاح!*

🆔 رقم الخدمة: *#$svc_id*
📝 الاسم: *$new_name*
💰 السعر/1000: *₽ $new_rate*
📉 الحد الأدنى: *$new_min*
📈 الحد الأعلى: *$new_max*
🌐 الموقع: *$fsite*
📂 القسم: *$section_name_c*
🔖 النوع: *$final_type_name_c*
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '➕ إضافة خدمة أخرى', 'callback_data' => "rfetch_page-0"]],
                [['text' => '- لوحة التحكم 🔜', 'callback_data' => 'Splash_settings']],
            ]
        ]),
    ]);
    @unlink("data/id/$id/$array.txt");
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={إضافة أقسام للرشق - نظام إدارة الأقسام}==========#
// زر "إضافة قسم للرشق" في Splash_settings
if ($data == "rashq_sections_menu") {
    // قراءة الأقسام المخصصة
    $custom_sections = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];
    $sections_count  = count($custom_sections);

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
🗂 *إدارة أقسام الرشق*

عدد الأقسام المخصصة: *$sections_count*

اختر العملية:
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '➕ إضافة قسم جديد', 'callback_data' => 'rsec_add']],
                [['text' => '📋 عرض جميع الأقسام', 'callback_data' => 'rsec_list']],
                [['text' => '🗑 حذف قسم', 'callback_data' => 'rsec_delete_menu']],
                [['text' => '- رجوع 🔜', 'callback_data' => 'Splash_settings']],
            ]
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// إضافة قسم جديد للرشق
if ($data == "rsec_add") {
    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
➕ *إضافة قسم جديد للرشق*

أرسل اسم القسم الجديد (مثل: سناب شات، تويتر X، لينكدإن):
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '- رجوع 🔜', 'callback_data' => 'rashq_sections_menu']],
            ]
        ]),
    ]);
    file_put_contents("data/id/$id/step.txt", "rsec_add_name");
    exit;
}

if ($text && $text != '/start' && $exstep[0] == 'rsec_add_name') {
    $sec_name = trim($text);
    if (mb_strlen($sec_name, 'UTF-8') < 2) {
        bot('sendMessage', [
            'chat_id'           => $chat_id,
            'text'              => "❌ اسم القسم قصير جداً، أرسل اسماً صحيحاً.",
            'reply_to_message_id' => $message_id,
        ]);
        exit;
    }
    bot('sendMessage', [
        'chat_id'    => $chat_id,
        'text'       => "
📝 اسم القسم: *$sec_name*

الآن أرسل emoji للقسم (مثل: 👻 📸 🎵):
",
        'parse_mode'        => 'Markdown',
        'reply_to_message_id' => $message_id,
        'reply_markup'      => json_encode([
            'inline_keyboard' => [
                [['text' => '- رجوع 🔜', 'callback_data' => 'rsec_add']],
            ]
        ]),
    ]);
    file_put_contents("data/id/$id/step.txt", "rsec_add_emoji|$sec_name");
    exit;
}

if ($text && $text != '/start' && $exstep[0] == 'rsec_add_emoji') {
    $sec_name  = $exstep[1];
    $sec_emoji = trim($text);
    $sec_code  = "sec_" . substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);

    // حفظ القسم
    $custom_sections = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];
    $custom_sections[$sec_code] = [
        'name'  => $sec_name,
        'emoji' => $sec_emoji,
        'num'   => 90 + count($custom_sections), // رقم مخصص يبدأ من 90
    ];
    file_put_contents("data/rashq_sections.json", json_encode($custom_sections, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);

    bot('sendMessage', [
        'chat_id'    => $chat_id,
        'text'       => "
✅ *تم إضافة القسم بنجاح!*

$sec_emoji *$sec_name*

✅ سيظهر القسم في لوحة التحكم فوراً.
⚠️ سيظهر للمستخدمين بعد إضافة خدمات له.
",
        'parse_mode'        => 'Markdown',
        'reply_to_message_id' => $message_id,
        'reply_markup'      => json_encode([
            'inline_keyboard' => [
                [['text' => '- إدارة الأقسام 🔜', 'callback_data' => 'rashq_sections_menu']],
            ]
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// عرض جميع الأقسام
if ($data == "rsec_list") {
    $custom_sections = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];
    $built_in = [
        "1"  => "📱 تيليجرام", "2"  => "📸 انستجرام", "3"  => "🎵 تيك توك",
        "4"  => "📘 فيسبوك",  "5"  => "🐦 تويتر",    "6"  => "💬 واتساب",
        "7"  => "▶️ يوتيوب", "10" => "🎬 كواي",      "0"  => "👻 سناب شات",
        "8"  => "🎁 خدمات مجانية",
    ];

    $text_list = "📋 *جميع أقسام الرشق*\n\n🔷 *الأقسام الأساسية:*\n";
    foreach ($built_in as $num => $name) {
        $text_list .= "• $name\n";
    }

    if (!empty($custom_sections)) {
        $text_list .= "\n🔶 *الأقسام المضافة:*\n";
        foreach ($custom_sections as $code => $sec) {
            $text_list .= "• {$sec['emoji']} {$sec['name']}\n";
        }
    } else {
        $text_list .= "\n_لا توجد أقسام مخصصة مضافة بعد._";
    }

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => $text_list,
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '- رجوع 🔜', 'callback_data' => 'rashq_sections_menu']],
            ]
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// حذف قسم
if ($data == "rsec_delete_menu") {
    $custom_sections = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];

    if (empty($custom_sections)) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "لا توجد أقسام مضافة للحذف.",
            'show_alert'        => true,
        ]);
        exit;
    }

    $key = [];
    $key['inline_keyboard'][] = [['text' => '🗑 اختر القسم للحذف:', 'callback_data' => 'no']];
    foreach ($custom_sections as $code => $sec) {
        $key['inline_keyboard'][] = [
            ['text' => "{$sec['emoji']} {$sec['name']}", 'callback_data' => "rsec_del-$code"],
        ];
    }
    $key['inline_keyboard'][] = [['text' => '- رجوع 🔜', 'callback_data' => 'rashq_sections_menu']];

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "🗑 *حذف قسم من الرشق*\n\nاختر القسم الذي تريد حذفه:",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode($key),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

if ($exdata[0] == "rsec_del") {
    // sec_code قد يحتوي على underscore، نجمع كل ما بعد rsec_del-
    $sec_code = implode("-", array_slice($exdata, 1));
    $custom_sections = json_decode(@file_get_contents("data/rashq_sections.json") ?: '{}', true) ?: [];

    if (!isset($custom_sections[$sec_code])) {
        bot('answercallbackquery', [
            'callback_query_id' => $update->callback_query->id,
            'text'              => "⚠️ القسم غير موجود.",
            'show_alert'        => true,
        ]);
        exit;
    }

    $sec_name  = $custom_sections[$sec_code]['name'];
    $sec_emoji = $custom_sections[$sec_code]['emoji'];

    unset($custom_sections[$sec_code]);
    file_put_contents("data/rashq_sections.json", json_encode($custom_sections, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "✅ *تم حذف القسم:* $sec_emoji $sec_name",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '- إدارة الأقسام 🔜', 'callback_data' => 'rashq_sections_menu']],
            ]
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

#=========={زر نسبة الربح - تحديد نسبة ربح على الخدمات المجلوبة}==========#
if ($exdata[0] == "rfetch_profit") {
    $fadd  = $exdata[1];
    $fnum  = $exdata[2];
    $ftype = $exdata[3];
    $fsite = getRashqSite($fadd);
    $fApp  = $RashqApps[$fnum]  ?? "تطبيق";
    $fTypeName = $RashqTypes[$ftype] ?? "خدمة";

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
💹 *نسبة الربح على الخدمات المجلوبة*

🌐 الموقع: *$fsite*
📱 التطبيق: *$fApp*
💱 سعر الصرف: *1\$ = 30 ₽*

أرسل نسبة الربح التي تريدها (رقم فقط):
مثال: أرسل *20* إذا أردت ربح 20% على سعر الخدمة بالروبل

📌 طريقة الحساب:
سعر المورد (\$) × 30 = سعر الروبل
سعر الروبل × (1 + النسبة%) = السعر النهائي ₽

⚡️ سيتم تطبيق النسبة تلقائياً على الأسعار بالروبل عند الإضافة.
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '+10%',  'callback_data' => "rfetch_setprofit-$fadd-$fnum-$ftype-10"],
                    ['text' => '+20%',  'callback_data' => "rfetch_setprofit-$fadd-$fnum-$ftype-20"],
                    ['text' => '+30%',  'callback_data' => "rfetch_setprofit-$fadd-$fnum-$ftype-30"],
                ],
                [
                    ['text' => '+50%',  'callback_data' => "rfetch_setprofit-$fadd-$fnum-$ftype-50"],
                    ['text' => '+75%',  'callback_data' => "rfetch_setprofit-$fadd-$fnum-$ftype-75"],
                    ['text' => '+100%', 'callback_data' => "rfetch_setprofit-$fadd-$fnum-$ftype-100"],
                ],
                [['text' => '- رجوع للقائمة 🔜', 'callback_data' => "rfetch_page-0"]],
            ]
        ]),
    ]);
    // حفظ حالة الانتظار لاستقبال نسبة مخصصة
    file_put_contents("data/id/$id/step.txt", "rfetch_profit_input|$fadd|$fnum|$ftype");
    exit;
}

// استقبال نسبة ربح مخصصة (نص)
if ($text && $text != '/start' && $exstep[0] == 'rfetch_profit_input') {
    $fadd  = $exstep[1];
    $fnum  = $exstep[2];
    $ftype = $exstep[3];
    $profit_pct = trim($text);

    if (!is_numeric($profit_pct) || $profit_pct < 0) {
        bot('sendMessage', [
            'chat_id'             => $chat_id,
            'text'                => "❌ *أدخل رقماً صحيحاً موجباً* (مثال: 25)\nيمثل نسبة الربح بالمئة.",
            'parse_mode'          => 'Markdown',
            'reply_to_message_id' => $message_id,
        ]);
        exit;
    }

    // حفظ نسبة الربح في الكاش
    $cache_raw = @file_get_contents("data/id/$id/rfetch_cache.json");
    if ($cache_raw) {
        $cache = json_decode($cache_raw, true);
        $cache['profit_pct'] = (float)$profit_pct;
        file_put_contents("data/id/$id/rfetch_cache.json", json_encode($cache, JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    $fsite = getRashqSite($fadd);
    // عرض مثال على التطبيق
    $example_usd  = 1;
    $example_rub  = $example_usd * 30;
    $example_with_profit = round($example_rub * (1 + $profit_pct / 100), 4);

    bot('sendMessage', [
        'chat_id'    => $chat_id,
        'text'       => "
✅ *تم تفعيل نسبة الربح: $profit_pct%*

📊 مثال على التسعير:
• سعر الخدمة الأصلي: 1\$ = ₽ $example_rub
• بعد إضافة $profit_pct% ربح: ₽ $example_with_profit

⚡️ ستُضاف هذه النسبة تلقائياً على كل خدمة تضيفها من هذه القائمة.
",
        'parse_mode'          => 'Markdown',
        'reply_to_message_id' => $message_id,
        'reply_markup'        => json_encode([
            'inline_keyboard' => [
                [['text' => '- العودة للقائمة 🔜', 'callback_data' => "rfetch_page-0"]],
            ]
        ]),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

// زر اختيار نسبة ربح جاهزة
if ($exdata[0] == "rfetch_setprofit") {
    $fadd       = $exdata[1];
    $fnum       = $exdata[2];
    $ftype      = $exdata[3];
    $profit_pct = (float)($exdata[4] ?? 0);
    $fsite      = getRashqSite($fadd);
    $fApp       = $RashqApps[$fnum]  ?? "تطبيق";
    $fTypeName  = $RashqTypes[$ftype] ?? "خدمة";

    // حفظ نسبة الربح في الكاش
    $cache_raw = @file_get_contents("data/id/$id/rfetch_cache.json");
    $cache     = $cache_raw ? json_decode($cache_raw, true) : [];
    if ($cache) {
        $cache['profit_pct'] = $profit_pct;
        file_put_contents("data/id/$id/rfetch_cache.json", json_encode($cache, JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    // إعادة بناء قائمة الخدمات بالأسعار المحدثة
    $filtered    = $cache['services'] ?? [];
    $cur_page    = $cache['page'] ?? 0;
    $per_page    = 8;
    $total       = count($filtered);
    $total_pages = ceil($total / $per_page);
    $page        = max(0, min($cur_page, max(0, $total_pages - 1)));
    $page_svcs   = array_slice($filtered, $page * $per_page, $per_page);

    $key = [];
    $key['inline_keyboard'][] = [
        ['text' => "🌐 $fsite", 'callback_data' => 'no'],
        ['text' => "📱 $fApp | $fTypeName", 'callback_data' => 'no'],
    ];
    $key['inline_keyboard'][] = [
        ['text' => "💰 السعر/1000 (+{$profit_pct}% ربح)", 'callback_data' => 'no'],
        ['text' => "📋 الخدمة", 'callback_data' => 'no'],
    ];

    foreach ($page_svcs as $idx => $svc) {
        $svc_id   = $svc['service'] ?? $svc['id'] ?? '?';
        $svc_name = mb_substr($svc['name'] ?? 'خدمة', 0, 30, 'UTF-8');
        $svc_rate = $svc['rate'] ?? '?';
        $rate_rub = is_numeric($svc_rate) ? $svc_rate * 30 : 0;
        if ($profit_pct > 0 && $rate_rub > 0) {
            $rate_rub = round($rate_rub * (1 + $profit_pct / 100), 2);
        } else {
            $rate_rub = is_numeric($svc_rate) ? round($rate_rub, 2) : $svc_rate;
        }
        $real_idx = ($page * $per_page) + $idx;
        $key['inline_keyboard'][] = [
            ['text' => "₽ $rate_rub", 'callback_data' => "rfetch_view-$real_idx"],
            ['text' => "#$svc_id $svc_name", 'callback_data' => "rfetch_view-$real_idx"],
        ];
    }

    $nav = [];
    if ($page > 0)
        $nav[] = ['text' => '◀️ السابق', 'callback_data' => "rfetch_page-" . ($page - 1)];
    $nav[] = ['text' => ($page + 1) . "/$total_pages", 'callback_data' => 'no'];
    if (($page + 1) < $total_pages)
        $nav[] = ['text' => 'التالي ▶️', 'callback_data' => "rfetch_page-" . ($page + 1)];
    if (!empty($nav)) $key['inline_keyboard'][] = $nav;

    $key['inline_keyboard'][] = [
        ['text' => '- رجوع 🔜', 'callback_data' => "rfetch_app-$fadd-$fnum"],
        ['text' => '💹 تغيير النسبة', 'callback_data' => "rfetch_profit-$fadd-$fnum-$ftype"],
    ];

    bot('EditMessageText', [
        'chat_id'      => $chat_id,
        'message_id'   => $message_id,
        'text'         => "
✅ *تم تفعيل نسبة ربح: {$profit_pct}%*

🌐 الموقع: *$fsite* | 📱 *$fApp* | 🔖 *$fTypeName*
📦 الخدمات: *$total* | 💹 الربح: *+{$profit_pct}%*

الأسعار محدثة ✅ اضغط على خدمة لإضافتها:
",
        'parse_mode'   => 'Markdown',
        'reply_markup' => json_encode($key),
    ]);
    @unlink("data/id/$id/step.txt");
    exit;
}

//@E_O_E1