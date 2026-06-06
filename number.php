<?php
include("doesnwork.php");
include("country.php");
#==========={Country}===========#
$ONE = [""=>"",""=>"","✥ الإمارات 🇦🇪٭"=>"95","✥ قطر 🇶🇦٭"=>"111","✥ عمان 🇴🇲٭"=>"107","✥ البحرين 🇧🇭٭"=>"145","✥ الكويت 🇰🇼٭"=>"100","✥ السعودية 🇸🇦٭"=>"53","✥ الأردن 🇯🇴٭"=>"116","✥ سوريا 🇸🇾٭"=>"110","✥ الجزائر 🇩🇿٭"=>"58","✥ مصر 🇪🇬٭"=>"21","✥ السودان 🇸🇩٭"=>"98","✥ ليبيا 🇱🇾٭"=>"102","✥ فلسطين 🇵🇸٭"=>"188","✥ تونس 🇹🇳٭"=>"89","✥ العراق 🇮🇶٭"=>"47","✥ لبنان 🇱🇧٭"=>"153","✥ اليمن 🇾🇪٭"=>"30","✥ المغرب 🇲🇦٭ ."=>"37"];
$TOW = ["x"=>"xx","xx"=>"xx","الأردن 🇯🇴"=>"116","إسرائيل 🇮🇱👞"=>"13","إيران 🇮🇷"=>"57","هونغ كونغ 🇭🇰"=>"14","رومانيا 🇷🇴"=>"32","البرتغال 🇵🇹"=>"117","سوريا 🇸🇾"=>"110","الهند 🇮🇳"=>"22","كمبوديا 🇰🇭"=>"24","النيبال 🇳🇵"=>"81","ماكو 🇲🇴"=>"20","اليمن 🇾🇪"=>"30","فلسطين 🇵🇸"=>"188","العراق 🇮🇶"=>"47","بابو 🇵🇬"=>"79","الفلبين 🇵🇭"=>"4"];
$THREE = ["x"=>"xx","xx"=>"xx","تركيا 🇹🇷"=>"62","لاوس 🇱🇦"=>"25","افغانستان 🇦🇫"=>"74","تايلاند 🇹🇭"=>"52","المانيا 🇩🇪"=>"43","طاجيكستان 🇹🇯"=>"143","لبنان 🇱🇧"=>"153","اوزبكستان 🇺🇿"=>"40","تركمانستان 🇹🇲"=>"161","باكستان 🇵🇰"=>"66","جورجيا 🇬🇪"=>"128","موريتانيا 🇲🇷"=>"114","مصر 🇪🇬"=>"21","جنوب افريقيا 🇿🇦"=>"31","قبرص 🇨🇾"=>"77","اسبانيا 🇪🇸"=>"56","سويسرا 🇨🇭"=>"173","السودان 🇸🇩"=>"98"];
$FOUR = ["x"=>"xx","xx"=>"xx","تونس 🇹🇳"=>"89","مدغشقر 🇲🇬"=>"17","إستونيا 🇪🇪"=>"34","الجزائر 🇩🇿"=>"58","إثيوبيا 🇪🇹"=>"71","زامبيا 🇿🇲"=>"147","جيبوتي 🇩🇯"=>"168","النيجر 🇳🇪"=>"139","فرنسا 🇫🇷"=>"78","لاتيفيا 🇱🇻"=>"49","ليبيا 🇱🇾"=>"102","ليبيريا 🇱🇷"=>"135","انغولا 🇦🇴"=>"76","السويد 🇸🇪"=>"46"];
$FIVE = ["x"=>"xx","xx"=>"xx","إيطاليا 🇮🇹"=>"86","ساحل العاج 🇨🇮"=>"27","كينيا 🇰🇪"=>"8","المغرب 🇲🇦"=>"37","موزمبيق 🇲🇿"=>"80","زيمبابوي 🇿🇼"=>"96","غانا 🇬🇭"=>"38","بوتسوانا 🇧🇼"=>"123","الصومال 🇸🇴"=>"149","برمودا 🇧🇲"=>"195"];
$SIX = ["x"=>"xx","xx"=>"xx","اليابان 🇯🇵"=>"182","كوريا الجنوبية 🇰🇷"=>"190","شمال مقدونيا 🇲🇰"=>"183","امريكا 🇺🇸"=>"187","البرازيل 🇧🇷"=>"73","الاكوادور 🇪🇨"=>"105","سيراليون 🇸🇱"=>"115","الأرجنتين 🇦🇷"=>"39","المكسيك 🇲🇽"=>"54","بوليفيا 🇧🇴"=>"92","سوازيلاند 🇸🇿"=>"106","كاليدونيا 🇳🇨"=>"185","بلجيكا 🇧🇪"=>"82","اليونان 🇬🇷"=>"129","أوكرانيا 🇺🇦"=>"1","هولندا 🇳🇱"=>"48","ليتوانيا 🇱🇹"=>"44","كندا 🇨🇦"=>"36","كابيفردي 🇨🇻"=>"186","ميانمار 🇲🇲"=>"5"];
$SEVEN = ["x"=>"xx","xx"=>"xx","بولندا 🇵🇱"=>"15","جزر سليمان 🇸🇧"=>"193","بريطانيا 🇬🇧"=>"16","هايتي 🇭🇹"=>"26","بوتان 🇧🇹"=>"158","تيمور 🇹🇱"=>"91","تايوان 🇹🇼"=>"55","فيجي 🇫🇯"=>"189","غامبيا 🇬🇲"=>"28","جزر القمر 🇰🇲"=>"133","إفريقيا الوسطى 🇨🇫"=>"125","جنوب السودان 🇸🇸"=>"177","إريتريا 🇪🇷"=>"176","ناميبيا 🇳🇦"=>"138","الغابون 🇬🇦"=>"154","أذربيجان 🇦🇿"=>"35"];
$EIGHT = ["x"=>"xx","xx"=>"xx","ليسوتو 🇱🇸"=>"136","غواتيمالا 🇬🇹"=>"94","سورينام 🇸🇷"=>"142","بليز 🇧🇿"=>"124","كوستاريكا 🇨🇷"=>"93","بروناي 🇧🇳"=>"121","بيرو 🇵🇪"=>"65","توجو 🇹🇬"=>"99","تشاد 🇹🇩"=>"42","جوادلوب 🇬🇵"=>"160","كوبا 🇨🇺"=>"113","مالي 🇲🇱"=>"69"];
$ARAB = ["x"=>"xx","xx"=>"xx","✥ الإمارات 🇦🇪٭"=>"95","✥ الإمارات 🇦🇪٭"=>"95","✥ قطر 🇶🇦٭"=>"111","✥ عمان 🇴🇲٭"=>"107","✥ البحرين 🇧🇭٭"=>"145","✥ الكويت 🇰🇼٭"=>"100","✥ السعودية 🇸🇦٭"=>"53","✥ الأردن 🇯🇴٭"=>"116","✥ سوريا 🇸🇾٭"=>"110","✥ الجزائر 🇩🇿٭"=>"58","✥ مصر 🇪🇬٭"=>"21","✥ السودان 🇸🇩٭"=>"98","✥ ليبيا 🇱🇾٭"=>"102","✥ فلسطين 🇵🇸٭"=>"188","✥ تونس 🇹🇳٭"=>"89","✥ العراق 🇮🇶٭"=>"47","✥ لبنان 🇱🇧٭"=>"153","✥ اليمن 🇾🇪٭"=>"30","✥ المغرب 🇲🇦٭ ."=>"37"];
$AFRICA = ["x"=>"xx","xx"=>"xx","✥ الكونغو 🇨🇬٭"=>"150","✥ ديم الكونغو 🇨🇩٭"=>"18","✥ كينيا 🇰🇪٭"=>"8","✥ الغابون 🇬🇦٭"=>"154","✥ جنوب افريقيا 🇿🇦٭"=>"31","✥ تشاد 🇹🇩٭"=>"42","✥ ملاوي 🇲🇼٭"=>"137","✥ النيجر 🇳🇪٭"=>"139","✥ زامبيا 🇿🇲٭"=>"147","✥ نيجيريا 🇳🇬٭"=>"19","✥ الكاميرون 🇨🇲٭"=>"41","✥ جزر القمر 🇰🇲٭"=>"133","✥ رواندا 🇷🇼٭"=>"140","✥ موزمبيق 🇲🇿٭"=>"80","✥ مدغشقر 🇲🇬٭"=>"17","✥ جنوب السودان 🇸🇸٭"=>"177","✥ إريتريا 🇪🇷٭"=>"176","✥ بنين 🇧🇯٭"=>"120","✥ جامايكا 🇯🇲٭"=>"103","✥ السنغال 🇸🇳٭"=>"61","مالي 🇲🇱٭"=>"69","✥ تنزانيا 🇹🇿٭"=>"9","✥ نيكاراغوا 🇳🇮٭"=>"90","✥ توجو 🇹🇬٭"=>"99","✥ غامبيا 🇬🇲٭"=>"28","✥ غيانا 🇬🇾٭"=>"131","✥ ناميبيا 🇳🇦٭"=>"138","✥ غينيا 🇬🇳٭"=>"68","✥ إثيوبيا 🇪🇹٭"=>"71","✥ المالديف 🇲🇻٭"=>"159","✥ بوروندي 🇧🇮٭"=>"119","✥ بروناي 🇧🇳٭"=>"121","✥ بربادوس 🇧🇧٭"=>"118","✥ اروبا 🇦🇼٭"=>"179","✥ غرينادا 🇬🇩٭"=>"127","✥ ساحل العاج 🇨🇮٭"=>"27","✥ غينيا الأستوائية 🇬🇶٭"=>"167","✥ غينيا بيساو 🇬🇼٭"=>"130"];
$EUROPE = ["x"=>"xx","xx"=>"xx","✥ تركيا 🇹🇷٭"=>"62","✥ فرنسا 🇫🇷٭"=>"78","✥ بولندا 🇵🇱٭"=>"15","✥ اسبانيا 🇪🇸٭"=>"56","✥ هولندا 🇳🇱٭"=>"48","✥ أوكرانيا 🇺🇦٭"=>"1","✥ سويسرا 🇨🇭٭"=>"173","✥ المانيا 🇩🇪٭"=>"43","✥ بلجيكا 🇧🇪٭"=>"82","✥ أذربيجان 🇦🇿٭"=>"35","✥ إيطاليا 🇮🇹٭"=>"86","✥ السويد 🇸🇪٭"=>"46","✥ صربيا 🇷🇸٭"=>"29","✥ أيرلندا 🇮🇪٭"=>"23","✥ فنلندا 🇫🇮٭"=>"163","✥ النمسا 🇦🇹٭"=>"50","✥ البانيا 🇦🇱٭"=>"155","✥ الدنمارك 🇩🇰٭"=>"172","✥ كرواتيا 🇭🇷٭"=>"45","✥ إسرائيل 🇮🇱👞٭"=>"13","✥ البرتغال 🇵🇹٭"=>"117","✥ بيلاروس 🇧🇾٭"=>"51","✥ نيوزيلندا 🇳🇿٭"=>"67","✥ لاتيفيا 🇱🇻٭"=>"49","✥ جزر البهاما 🇧🇸٭"=>"122","✥ سوازيلاند 🇸🇿٭"=>"106","✥ البوسنة 🇧🇦٭"=>"108","✥ سورينام 🇸🇷٭"=>"142","✥ قبرص 🇨🇾٭"=>"77","✥ التشيك 🇨🇿٭"=>"63","✥ مونتسيرات 🇲🇸٭"=>"180","✥ سانتكيتس 🇰🇳٭"=>"134","✥ سيراليون 🇸🇱٭"=>"115","✥ كابيفردي 🇨🇻٭"=>"186","✥ سيشيل 🇸🇨٭"=>"184"];
$AMERICA = ["x"=>"xx","xx"=>"xx","✥ البرازيل 🇧🇷٭"=>"73","✥ المكسيك 🇲🇽٭"=>"54","✥ امريكا 🇺🇸٭"=>"187","✥ تشيلي 🇨🇱٭"=>"151","✥ بيرو 🇵🇪٭"=>"65","✥ كولومبيا 🇨🇴٭"=>"33","✥ مولدوفا 🇲🇩٭"=>"85","✥ كوبا 🇨🇺٭"=>"113","✥ بنما 🇵🇦٭"=>"112","✥ كوستاريكا 🇨🇷٭"=>"93","✥ الاكوادور 🇪🇨٭"=>"105","✥ بوليفيا 🇧🇴٭"=>"92","✥ هندوراس 🇭🇳٭"=>"88","✥ سلفادور 🇸🇻٭"=>"101","✥ باراغواي 🇵🇾٭"=>"87","✥ ليسوتو 🇱🇸٭"=>"136","✥ بورتوريكو 🇵🇷٭"=>"97","✥ غواتيمالا 🇬🇹٭"=>"94"];
$AUSTRALIA = ["x"=>"xx","xx"=>"xx","✥ استراليا 🇦🇺٭"=>"175"];
$ASIA = ["x"=>"xx","xx"=>"xx","✥ باكستان 🇵🇰٭"=>"66","✥ كازاخستان 🇰🇿٭"=>"2","✥ افغانستان 🇦🇫٭"=>"74","✥ ماليزيا 🇲🇾٭"=>"7","✥ الفلبين 🇵🇭٭"=>"4","✥ اوزبكستان 🇺🇿٭"=>"40","✥ تركمانستان 🇹🇲٭"=>"161","✥ جورجيا 🇬🇪٭"=>"128","✥ إيران 🇮🇷٭"=>"57","✥ أنغيلا 🇦🇮٭"=>"181","✥ تايلاند 🇹🇭٭"=>"52","✥ تايوان 🇹🇼٭"=>"55","✥ طاجيكستان 🇹🇯٭"=>"143","✥ كوريا الجنوبية 🇰🇷٭"=>"190","✥ بنغلاديش 🇧🇩٭"=>"60","✥ إستونيا 🇪🇪٭"=>"34","✥ بلغاريا 🇧🇬٭"=>"83","✥ النرويج 🇳🇴٭"=>"174","✥ سريلانكا 🇱🇰٭"=>"64","✥ ماكو 🇲🇴٭"=>"20","✥ لاوس 🇱🇦٭"=>"25","✥ ليبيريا 🇱🇷٭"=>"135","✥ الدومينيكان 🇩🇴٭"=>"109","✥ الصحراء الغربية 🇪🇭٭"=>"196","✥ بوتسوانا 🇧🇼٭"=>"123","✥ فوركينافاسو 🇧🇫٭"=>"152","✥ دومينيكا 🇩🇲٭"=>"126"];
$Availability = ["x"=>"xx","xx"=>"xx","✥ الإمارات 🇦🇪٭"=>"95","✥ قطر 🇶🇦٭"=>"111","✥ عمان 🇴🇲٭"=>"107","✥ البحرين 🇧🇭٭"=>"145","✥ ماليزيا 🇲🇾٭"=>"7","✥ الكويت 🇰🇼٭"=>"100","✥ السعودية 🇸🇦٭"=>"53","✥ الأردن 🇯🇴٭"=>"116","✥ الكونغو 🇨🇬٭"=>"150","✥ ديم الكونغو 🇨🇩٭"=>"18","✥ كينيا 🇰🇪٭"=>"8","✥ الغابون 🇬🇦٭"=>"154","✥ جنوب افريقيا 🇿🇦٭"=>"31","✥ تشاد 🇹🇩٭"=>"42","✥ ملاوي 🇲🇼٭"=>"137","✥ النيجر 🇳🇪٭"=>"139","✥ زامبيا 🇿🇲٭"=>"147","✥ نيجيريا 🇳🇬٭"=>"19","✥ تركيا 🇹🇷٭"=>"62","✥ فرنسا 🇫🇷٭"=>"78","✥ بولندا 🇵🇱٭"=>"15","✥ اسبانيا 🇪🇸٭"=>"56","✥ هولندا 🇳🇱٭"=>"48","✥ أوكرانيا 🇺🇦٭"=>"1","✥ سويسرا 🇨🇭٭"=>"173","✥ المانيا 🇩🇪٭"=>"43","✥ بلجيكا 🇧🇪٭"=>"82","✥ البرازيل 🇧🇷٭"=>"73","✥ المكسيك 🇲🇽٭"=>"54","✥ امريكا 🇺🇸٭"=>"187","✥ تشيلي 🇨🇱٭"=>"151","✥ بيرو 🇵🇪٭"=>"65","✥ كولومبيا 🇨🇴٭"=>"33","✥ مولدوفا 🇲🇩٭"=>"85","✥ كوبا 🇨🇺٭"=>"113","✥ بنما 🇵🇦٭"=>"112","✥ كوستاريكا 🇨🇷٭"=>"93","✥ باكستان 🇵🇰٭"=>"66","✥ كازاخستان 🇰🇿٭"=>"2","✥ افغانستان 🇦🇫٭"=>"74","✥ ماليزيا 🇲🇾٭"=>"7","✥ الفلبين 🇵🇭٭"=>"4","✥ اوزبكستان 🇺🇿٭"=>"40","✥ تركمانستان 🇹🇲٭"=>"161","✥ جورجيا 🇬🇪٭"=>"128","✥ إيران 🇮🇷٭"=>"57","✥ أنغيلا 🇦🇮٭"=>"181","✥ تايلاند 🇹🇭٭"=>"52","✥ تايوان 🇹🇼٭"=>"55","✥ طاجيكستان 🇹🇯٭"=>"143"];
#
if($ex_text[0] == '/start' and strpos($ex_text[1],"ID")!== false){
$idSend = str_replace('ID','',$ex_text[1]);
$EEM=$ORDERALL[$idSend][account];
$order=$ORDERALL[$idSend][order];
$BUYSNUM_S = json_decode(file_get_contents("EMILS/$EEM/number.json"),true);
$zero=$BUYSNUM_S[number][$order][zero];
$price=$buy['number'][$zero][price];
$country = $buy['number'][$zero][country];
$add = $buy['number'][$zero][add];
$operator = $buy['number'][$zero][operator];
$app = $buy['number'][$zero][app];
$site = $buy['number'][$zero][site];
$maxPrice = $buy['number'][$zero][maxPrice];
$status_zero = $buy['number'][$zero];
$name = $_co['country'][$country];
$code = "$country$app$operator$add";
$idSend=$orderall;
if($add >= 21 and $add <= 25){
$add=21;
}elseif($add >= 26 and $add <= 30){
$add=26;
}
$APP = str_replace(["10","11","12","13","14","21","26","31","36","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي تيليجرام","الشراء الذكي ","عروض تيليجرام","عروض واتساب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$server = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);
$APP_S = str_replace(["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],["الواتسأب","التيليجرام","الفيسبوك","الإنستقرام","التويتر","التيك توك","القوقل","الإيمو","فايبر","سناب شات","نيتفلكس","حراج","أي تطبيق لديك"],$server);
if($add == 1){
$back = "Wo";
}elseif($add >= 21 and $add <= 30){
$back = "worldwide";
}elseif($add >= 31 and $add <= 40){
$back = "Buynum";
}else{
$back = "Ms-$add-$country";
}
if($app == "wa"){
$wa = "✥تحقق من الرقم في وتساب٭";
}elseif($app == "tg"){
$tg = "✥تحقق من الرقم في تيلجرام٭";
}
if(time() - $BUYSNUM[number][$Detector][times] <= 2){
unlink("data/id/$id/step.txt");
exit;
}
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getNum&site=$site&country=$country&app=$app&operator=$operator&maxPrice=$maxPrice"),1);
if($site=="2ndline"){
$num2nd = $api[num2nd];
}
$status = $api[status];
$number = $api[number];
$idnumber = $api[idnumber];
$time = $api[time];
$Location = $api[Location];
#__________Time
$z=$time/60;
$ex1 = explode(".", $z);
$z="0.$ex1[1]";
$z=$z*60;
$ex2 = explode(".", $z);
$n = mb_strlen("$ex2[0]");
if($n > 1){
$start_time="$ex1[0]:$ex2[0]";
}else{
$start_time="$ex1[0]:0$ex2[0]";
}
if($ex1[0] == null){
$k="ثانية";
}else{
$k="دقيقة";
}
#__________Time
if($status_zero == null){
unlink("data/id/$id/step.txt");
}elseif($status != "200"){
$zero = "$country$app$operator$add";
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
☑️ *- عذرا عزيزي ليس متوفر حاليا هذه الدولة.
💠 - قم بتحربة أي سيرفر آخر.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- رجوع 🔜','callback_data'=>"$back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}else{
if($site == "2ndline"){
if($num2nd == null){
for($ii=0; $ii<10;$ii++){
sleep(1);
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getNum2nd&site=$site&country=$country&id=$idds"),1);
$status = $api[status];
$num2nd = $api[num2nd];
$number = $api[number];
$idnumber = $api[idnumber];
$Location = $api[Location];
$see = str_replace(["0","1","2","3","4","5","6","7","8","9"],["..","...","....",".....","..","...","....",".....","..","..."],$ii);
if($num2nd == null and $ii==10){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- عذرا عزيزي ليس متوفر حاليا هذه الدولة.
💠 - قم بتحربة أي سيرفر آخر.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- رجوع 🔜','callback_data'=>"$back"]]
]
])
]);
exit;
}elseif($num2nd == null){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- يتم الان محاولات شراء رقم لك♻️.

🔄 - الرجاء الانتظار$see* ⏳.
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭. 🔜','callback_data'=>"::"]]
]
])
]);
}else{
$get=bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
<b>➖ رقم الطلب | <s>$randomNumber</s> 🛎•</b>
<b>➖ الدولة | $name •</b>
<b>➖ الرقم | <code>$number</code>  ☎️•</b>
<b>➖ الكود | قيد الانتظار 📩</b>
<b>➖ الحالة | RECEIVED ... 🔎•</b>
<b>➖ التطبيق : $APP •</b>
<b>➖ السعر | ₽ $price 💙•</b>

<b>➖ انشاء : $DAY3 •  📫•</b>
<b>➖ انتهاء : $start_time   📭•</b>
<blockquote>➖ عدد المحاولات الشراء : 1</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$wa",'url'=>"wa.me/$number"]],[['text'=>"$tg",'url'=>"T.me/$number"]],[['text'=>'✥تغيير الرقم↻','callback_data'=>"Xi-$code"]],[['text'=>'✥طلب الكود٭','callback_data'=>"Code-$idSend"]],[['text'=>'✥الغاء الطلب٭','callback_data'=>"Ban-$idSend"]],[['text'=>'✥رجوع٭','callback_data'=>"Ban-$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
⚜ تم شراء رقم لتطبيق $APP:

☎️ - الرقم: *$number*
🧩 - الدولة: *$name*
🎯 - رمز الدولة: *$country*
🎟 - الرصيد: *$Balance*
🏋 - الايدي: *$idnumber*
🔥 - السعر : *₽ $price*
🤸‍♂ - الحساب : *$EM*
🎗 - الموقع: *$Location & $operator*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
file_put_contents("data/id/$id/restriction.txt","$ordermy");
$times=time();
$get=$get->result->message_id;
$BUYSNUM[number][$ordermy][idSend] = $idSend;
$BUYSNUM[number][$ordermy][phone] = $number;
$BUYSNUM[number][$ordermy][sms][0][code] = null;
$BUYSNUM[number][$ordermy][status] = 1;
$BUYSNUM[number][$ordermy][operator] = $operator;
$BUYSNUM[number][$ordermy][app] = $app;
$BUYSNUM[number][$ordermy][add] = $add;
$BUYSNUM[number][$ordermy][price] = $price;
$BUYSNUM[number][$ordermy][id] = $idnums;
$BUYSNUM[number][$ordermy][site] = $site;
$BUYSNUM[number][$ordermy][zero] = $zero;
$BUYSNUM[number][$ordermy][country] = $country;
$BUYSNUM[number][$ordermy][idnumber] = $idnumber;
$BUYSNUM[number][$ordermy][message] = $get;
$BUYSNUM[number][$ordermy][type] = "direct";
$BUYSNUM[number][$ordermy][finish] = $time;
$BUYSNUM[number][$ordermy][times] = $times;
$BUYSNUM[number][$ordermy]["chat-id"] = $id;
$BUYSNUM[number][$ordermy][DAY] = $DAY;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][account] = $EM;
$ORDERALL[$idSend][order] = $ordermy;
$ORDERALL[$idSend][status] = 1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
}
}
}else{
$get=bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
<b>➖ رقم الطلب | <s>$randomNumber</s> 🛎•</b>
<b>➖ الدولة | $name •</b>
<b>➖ الرقم | <code>$number</code>  ☎️•</b>
<b>➖ الكود | قيد الانتظار 📩</b>
<b>➖ الحالة | RECEIVED ... 🔎•</b>
<b>➖ التطبيق : $APP •</b>
<b>➖ السعر | ₽ $price 💙•</b>

<b>➖ انشاء : $DAY3 •  📫•</b>
<b>➖ انتهاء : $start_time   📭•</b>
<blockquote>➖ عدد المحاولات الشراء : 1</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$wa",'url'=>"wa.me/$number"]],[['text'=>"$tg",'url'=>"T.me/$number"]],[['text'=>'✥تغيير الرقم↻','callback_data'=>"Xi-$code"]],[['text'=>'✥طلب الكود٭','callback_data'=>"Code-$idSend"]],[['text'=>'✥الغاء الطلب٭','callback_data'=>"Ban-$idSend"]],[['text'=>'✥رجوع٭','callback_data'=>"Ban-$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
⚜ تم شراء رقم لتطبيق $APP:

☎️ - الرقم: *$number*
🧩 - الدولة: *$name*
🎯 - رمز الدولة: *$country*
🎟 - الرصيد: *$Balance*
🏋 - الايدي: *$idnumber*
🔥 - السعر : *₽ $price*
🤸‍♂ - الحساب : *$EM*
🎗 - الموقع: *$Location & $operator*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
file_put_contents("data/id/$id/restriction.txt","$ordermy");
$times=time();
$get=$get->result->message_id;
$BUYSNUM[number][$ordermy][idSend] = $idSend;
$BUYSNUM[number][$ordermy][phone] = $number;
$BUYSNUM[number][$ordermy][sms][0][code] = null;
$BUYSNUM[number][$ordermy][status] = 1;
$BUYSNUM[number][$ordermy][operator] = $operator;
$BUYSNUM[number][$ordermy][app] = $app;
$BUYSNUM[number][$ordermy][add] = $add;
$BUYSNUM[number][$ordermy][price] = $price;
$BUYSNUM[number][$ordermy][id] = $idnums;
$BUYSNUM[number][$ordermy][site] = $site;
$BUYSNUM[number][$ordermy][zero] = $zero;
$BUYSNUM[number][$ordermy][country] = $country;
$BUYSNUM[number][$ordermy][idnumber] = $idnumber;
$BUYSNUM[number][$ordermy][message] = $get;
$BUYSNUM[number][$ordermy][type] = "direct";
$BUYSNUM[number][$ordermy][finish] = $time;
$BUYSNUM[number][$ordermy][times] = $times;
$BUYSNUM[number][$ordermy]["chat-id"] = $id;
$BUYSNUM[number][$ordermy][DAY] = $DAY;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][account] = $EM;
$ORDERALL[$idSend][order] = $ordermy;
$ORDERALL[$idSend][status] = 1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
}
}
#=========={لاتعبث بهذي الاكواد!}==========#
if($update){
bot("setMyCommands",[
    "commands"=>json_encode([
        ['command'=>"/start",'description'=>'القائمة الرئيسية 🏡'],
        ['command'=>"create_email",'description'=>'بريد اكتروني موقت 📨'],
        ['command'=>"smart_purchase",'description'=>'الشراء الذكي 🛰'],
        ['command'=>"offers",'description'=>'عروض واتساب وتيليجرام 🎁'],
        ['command'=>"app",'description'=>'قائمة التطبيقات 📊'],
        ['command'=>"twi",'description'=>'أرقام تويتر X 🖤'],
        ['command'=>"imo",'description'=>'أرقام ايمو 🧡'],
        ['command'=>"insta",'description'=>'أرقام انستقرام ❤️'],
        ['command'=>"haraj",'description'=>'أرقام حراج 💙'],
        ['command'=>"google",'description'=>'أرقام قوقل 💛'],
        ['command'=>"facebook",'description'=>'أرقام فيسبوك 💎'],
        ['command'=>"snap",'description'=>'أرقام سناب 💛'],
        ['command'=>"other",'description'=>'أرقام سيرفر العام 📮'],
        ['command'=>"view",'description'=>'الدول المضافه في البوت 🌏'],
        ['command'=>"help",'description'=>'قائمة المساعدة 💚↘️'],
        ['command'=>"language",'description'=>'تغيير اللغة🔹️Language'],
        ])
    ]); 
}
#rave - القائمة الرئيسية 🏡
#free - ربح روبل مجاني 💎
#offers - عروض واتساب وتيليجرام 🎁#
#app - قائمة التطبيقات 📊
#whatsapp - أرقام واتسأب 💚
##telegram - أرقام تيليجرام 💙
#twi - أرقام تويتر X 🖤
#insta - أرقام انستقرام ❤️
#tik - أرقام تيك توك 🖤
#imo - أرقام ايمو 🧡
#snappp
#haraj - أرقام حراج 💙
#google - أرقام قوقل 💛
#facebook - أرقام فيسبوك 💎
#view - الدول المضافه في البوت 🌏
#language - تغيير اللغة🔹️Language
#help - قائمة المساعدة 💚↘️#
#=========={قائمة البرامج}==========#
if($data == "Buynum"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>✅ مرحبا بك في قسم شراء رقم ✅</b>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>

<blockquote>يرجي اختيار التطبيق الذي تود شرا رقم له</blockquote>                    
💰 رصيدك : $Balance ₽                    
",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'واتساب || 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣','callback_data'=>"Kn-2"]],
[['text'=>'تيليجرام || 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠','callback_data'=>"Kn-3"]],
[['text'=>'انستغرام || 𝗟𝗡𝗦𝗧𝗔𝗚𝗥𝗔𝗠','callback_data'=>"Kn-5"]],
[['text'=>'فيسبوك || 𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞','callback_data'=>"Kn-4"]],
[['text'=>'تويتر || 𝗧𝗪𝗜𝗧𝗧𝗘𝗥','callback_data'=>"Kn-6"]],
[['text'=>'تيك توك || 𝗧𝗜𝗞𝗧𝗢𝗞','callback_data'=>"Kn-7"],['text'=>'جوجل || 𝗚𝗢𝗢𝗚𝗟𝗘','callback_data'=>"Kn-8"]],
[['text'=>'سناب شات || 𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧','callback_data'=>"Kn-11"],['text'=>'حراج || 𝗛𝗔𝗥𝗔𝗚','callback_data'=>"Kn-13"]],[['text'=>'ايمو || 𝗜𝗠𝗢','callback_data'=>"Kn-9"]],
[['text'=>'🌏 - السيرفر العام - 🌏','callback_data'=>"Kn-14"]],
[['text'=>'🎮 - عروض واتساب - 🎮','callback_data'=>"Wo"],['text'=>'🎮 - عروض تيليجرام - 🎮','callback_data'=>"Cwt-36"]], 
[['text'=>'🔄 - رجوع للخلف - 🔄','callback_data'=>"back"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
#=========={القارآت}==========#
#By : JIMI @ZISlZ @T0qiR
if($exdata[0] == "Kn"){
$add=$exdata[1];
$APP = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","واتساب || 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣","واتساب || 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣","تيليجرام","فيسبوك || 𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞","إنستقرام","تويتر","تيك توك","جوجل || 𝗚𝗢𝗢𝗚𝗟𝗘","ايمو"],$add);
$APPS = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["Viber","سناب شات || 𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧","Netflix","✥|حراج -𝗛𝗔𝗥𝗔j 🪗 ٭","✥|🧿 السيرفر العام 🤖","واتساب || 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣","واتساب || 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣","تيليجرام || 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠","فيسبوك || 𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞","انستغرام || 𝗟𝗡𝗦𝗧𝗔𝗚𝗥𝗔𝗠","Twitter","تيك توك || 𝗧𝗜𝗞𝗧𝗢𝗞 🎥٭","جوجل || 𝗚𝗢𝗢𝗚𝗟𝗘","ايمو || 𝗜𝗠𝗢"],$add);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*💚 مرحبا 💚
➖ قسم الارقام  🎁. 
➖ تطبيق : $APPS .
➖ قم بإختيار احد القارات لعرض الدول المتوفرة بها 🌏.
*➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ العرب 🧩 ٭','callback_data'=>"Kn2-$add-1"]],
[['text'=>'✥ أفريقيا 🎳 ٭','callback_data'=>"Kn2-$add-2"],['text'=>'✥ أوربا 🪗 ٭','callback_data'=>"Kn2-$add-3"]],
[['text'=>'✥ أمريكا 🎯 ٭','callback_data'=>"Kn2-$add-4"],['text'=>'✥ أستراليا ⛱ ٭','callback_data'=>"Kn2-$add-5"]],
[['text'=>'✥ آسيا 💎 ٭','callback_data'=>"Kn2-$add-6"]],
[['text'=>'✥ الأكثر توفرآ  🎲 ٭','callback_data'=>"Kn2-$add-7"]],
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#By : JIMI @ZISlZ @T0qiR
#=========={الدول}==========#
if($exdata[0] == "Kn2"){
$add=$exdata[1];
$con=$exdata[2];
$APP = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عروض -𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣🛍 ٭","واتس اب -𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣🛍 ٭","تيليجرام","فيسبوك -𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞🎯","إنستقرام","تويتر","تيك توك"," جوجل-G𝗼𝗼𝗴𝗹𝗲⛱","ايمو"],$add);
$APPS = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["Viber","✥|سناب شات -𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧♣️ ٭","Netflix","✥|حراج -𝗛𝗔𝗥𝗔j 🪗 ٭","✥|🧿 السيرفر العام 🤖","✥|واتس اب -𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣🛍 ٭","✥|واتس اب -𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣🛍 ٭","✥|تليجرام -𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠 🎲","✥ فيسبوك -𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞🎯","✥|انستغرام -𝗟𝗡𝗦𝗧𝗔𝗚𝗥𝗔𝗠🎳 ٭","Twitter","✥|تويتر -𝗧𝗪𝗜𝗧𝗧𝗘𝗥 🐤","✥ جوجل-G𝗼𝗼𝗴𝗹𝗲⛱","✥|ايمو - 𝗜𝗠𝗢 💎 ٭"],$add);
if($con == 1) { $continent     = $ARAB;  }
if($con == 2) { $continent     = $AFRICA;  }
if($con == 3) { $continent     = $EUROPE;  }
if($con == 4) { $continent     = $AMERICA;  }
if($con == 5) { $continent     = $AUSTRALIA;  }
if($con == 6) { $continent     = $ASIA;  }
if($con == 7) { $continent     = $Availability;  }
if($continent == null){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>✅ مرحبا بك في قسم شراء رقم ✅</b>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>

<blockquote>يرجي اختيار التطبيق الذي تود شرا رقم له</blockquote>                    
💰 رصيدك : $Balance ₽                    
",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'واتساب || 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣','callback_data'=>"Kn-2"]],
[['text'=>'تيليجرام || 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠','callback_data'=>"Kn-3"]],
[['text'=>'انستغرام || 𝗟𝗡𝗦𝗧𝗔𝗚𝗥𝗔𝗠','callback_data'=>"Kn-5"]],
[['text'=>'فيسبوك || 𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞','callback_data'=>"Kn-4"]],
[['text'=>'تويتر || 𝗧𝗪𝗜𝗧𝗧𝗘𝗥','callback_data'=>"Kn-6"]],
[['text'=>'تيك توك || 𝗧𝗜𝗞𝗧𝗢𝗞','callback_data'=>"Kn-7"],['text'=>'جوجل || 𝗚𝗢𝗢𝗚𝗟𝗘','callback_data'=>"Kn-8"]],
[['text'=>'سناب شات || 𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧','callback_data'=>"Kn-11"],['text'=>'حراج || 𝗛𝗔𝗥𝗔𝗚','callback_data'=>"Kn-13"]],[['text'=>'ايمو || 𝗜𝗠𝗢','callback_data'=>"Kn-9"]],
[['text'=>'🌏 - السيرفر العام - 🌏','callback_data'=>"Kn-14"]],
[['text'=>'🎮 - عروض واتساب - 🎮','callback_data'=>"Wo"],['text'=>'🎮 - عروض تيليجرام - 🎮','callback_data'=>"Cwt-36"]], 
[['text'=>'🔄 - رجوع للخلف - 🔄','callback_data'=>"back"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
$i = 0;
$e = 0;
$key     = [];
foreach($continent as $country=>$val){
$co = $val;
$name      = $country;
$e++;
$key['inline_keyboard'][$i][]  =   ["text"=>$name,"callback_data"=>"Ms-$add-$co-$con"];
if($e%2 == 0) $i++;
}
$key['inline_keyboard'][0] = [['text'=>'- البحث عن الدول ( للإختصار ) 🔎','callback_data'=>"research-$add-$con"]];
$key['inline_keyboard'][] = [['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Kn-$add"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💙 مرحبآ 💙

➖ قسم الارقام  🎁. 
➖ تطبيق :  $APPS ٭ .
➖  قائمة الدول الخاصة بهذة القارة 🌐.

➖  قم بإختيار الدولة التي تريد شراء رقم منها لـ  $APP 🛍 ٭ 🔰.

  الدول المتوفرة حالياً 🇾🇪🇻🇳🇮🇩🇸🇦   ٭
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
}
}
#By : JIMI @ZISlZ @T0qiR
#=========={عرض سيرفرات الدول}==========#
if($exdata[0] == "Ms"){
$add=$exdata[1];
$co=$exdata[2];
$con=$exdata[3];
$APP = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عروض واتساب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$APPS = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["Viber","Snapchat","Netflix","Haraj","Other","Whatsapp","Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo"],$add);
$name = $_co['country'][$co];
$codee = $_cod['code'][$co];
if($codee == null){
$codee = "??";
}else{
$codee = "+$codee";
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- يتم تجميع السيرفرات تلقائياً....*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>":"]]
]
])
]);
$key     = [];
$key['inline_keyboard'][] = [['text'=>'✥ السعر ₽ 🎲٭','callback_data'=>'no'],['text'=>'-✥ السيرفرات 🧩٭','callback_data'=>'no']];
foreach($buy['number'] as $zero=>$num){
if($num['add'] == $add and $num['country'] == $co){
$price=$num['price'];
$app = $num['app'];
$country = $num['country'];
$operator = $num['operator'];
$name = $_co['country'][$co];
$code = "$country$app$operator$add";
$key['inline_keyboard'][] = [['text'=>"✥₽ $price ٭",'callback_data'=>"Xi-$code"],['text'=>"٭$APP $operator ✥",'callback_data'=>"Xi-$code"]];
}
}
$key['inline_keyboard'][] = [['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Kn-$add"]];
$keyboad      = json_encode($key);
if($country == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
⚠️ - لم يتم إضافة سيرفرات لتطبيق $APP في الوقت الحالي.
",
'show_alert'=>true
]);
if($con == 1) { $continent     = $ARAB;  }
if($con == 2) { $continent     = $AFRICA;  }
if($con == 3) { $continent     = $EUROPE;  }
if($con == 4) { $continent     = $AMERICA;  }
if($con == 5) { $continent     = $AUSTRALIA;  }
if($con == 6) { $continent     = $ASIA;  }
if($con == 7) { $continent     = $Availability;  }
if($continent == null){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>✅ مرحبا بك في قسم شراء رقم ✅</b>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>

<blockquote>يرجي اختيار التطبيق الذي تود شرا رقم له</blockquote>                    
💰 رصيدك : $Balance ₽                    
",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'واتساب || 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣','callback_data'=>"Kn-2"]],
[['text'=>'تيليجرام || 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠','callback_data'=>"Kn-3"]],
[['text'=>'انستغرام || 𝗟𝗡𝗦𝗧𝗔𝗚𝗥𝗔𝗠','callback_data'=>"Kn-5"]],
[['text'=>'فيسبوك || 𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞','callback_data'=>"Kn-4"]],
[['text'=>'تويتر || 𝗧𝗪𝗜𝗧𝗧𝗘𝗥','callback_data'=>"Kn-6"]],
[['text'=>'تيك توك || 𝗧𝗜𝗞𝗧𝗢𝗞','callback_data'=>"Kn-7"],['text'=>'جوجل || 𝗚𝗢𝗢𝗚𝗟𝗘','callback_data'=>"Kn-8"]],
[['text'=>'سناب شات || 𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧','callback_data'=>"Kn-11"],['text'=>'حراج || 𝗛𝗔𝗥𝗔𝗚','callback_data'=>"Kn-13"]],[['text'=>'ايمو || 𝗜𝗠𝗢','callback_data'=>"Kn-9"]],
[['text'=>'🌏 - السيرفر العام - 🌏','callback_data'=>"Kn-14"]],
[['text'=>'🎮 - عروض واتساب - 🎮','callback_data'=>"Wo"],['text'=>'🎮 - عروض تيليجرام - 🎮','callback_data'=>"Cwt-36"]], 
[['text'=>'🔄 - رجوع للخلف - 🔄','callback_data'=>"back"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
$i = 0;
$e = 0;
$key     = [];
foreach($continent as $country=>$val){
$co = $val;
$name      = $country;
$e++;
$key['inline_keyboard'][$i][]  =   ["text"=>$name,"callback_data"=>"Ms-$add-$co-$con"];
if($e%2 == 0) $i++;
}
$key['inline_keyboard'][0] = [['text'=>'- البحث عن الدول ( للإختصار ) 🔎','callback_data'=>"research-$add-$con"]];
$key['inline_keyboard'][] = [['text'=>'- رجوع.','callback_data'=>"Kn-$add"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💙 مرحبآ 💙

➖ قسم الارقام  🎁. 
➖ تطبيق :  $APP
➖  قائمة الدول الخاصة بهذة القارة 🌐.

➖  قم بإختيار الدولة التي تريد شراء رقم منها لـ  تيك توك 🛍 ٭ 🔰.

➖ 📞[  الدول المتوفرة حالياً.  ](http://t.me/$usrch3/850)🇸🇦🇻🇳🇪🇬
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
}
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ شراء رقم جديد ✅.
➖ التفاصيل ‼️.
➖ التطبيق | $APP  ٭.
➖ الدوله | $name 🌏.
➖ مفتاح الدولة 💚  | $codee 🔢
➖ قم بالضغط على احد السيرفرات لشراء الرقم 🔘.
➖ اذا لم تجد ارقام في احد السيرفرات قم بتجربة سيرفر اخر 🧿.
➖➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
#By : JIMI @ZISlZ @T0qiR
#=========={قسم عروض واتساب}==========#
if($data == "Wo"){
$wrod = file_get_contents("data/txt/wrod.txt");
if($wrod != null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"$wrod",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}else{
$key     = [];
$a=0;//keyboard
$b=0;//count
foreach($buy['number'] as $zero=>$num){
if($num['add'] == 1){
$price=$num['price'];
$add=$num['add'];
$app = $num['app'];
$country = $num['country'];
$operator = $num['operator'];
$name = $_co['country'][$country];
$code = "$country$app$operator$add";
$b++;
if($b%2!=0){
$key[inline_keyboard][$a][]=[text=>"✥ 🚀 $name : ₽ $price ٭",callback_data=>"Xi-$code"];
}else{
$a++;//لنزول سطر
$key[inline_keyboard][$a][]=[text=>"✥ 🚀 $name : ₽ $price ٭",callback_data=>"Xi-$code"];
}
}
}
$key['inline_keyboard'][] = [['text'=>'✅ شراء رقم جاهز : 15 روبل 💡','url'=>"http://t.me/E_O_E1"]];
$key['inline_keyboard'][] = [['text'=>'✥ عودة ↩ ٭..','callback_data'=>"back"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<blockquote>🎮 - عــــروض الــــواتــــســــاب - 🎮</blockquote>
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱☠</b>
<b>➖ 💰 رصيدك : $Balance ₽</b>
☑️ l― قـنـاة المـتـوفـر : @$usrch3
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱☠</b>
",
'parse_mode'=>"html",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
}
#By : JIMI @ZISlZ @T0qiR
#=========={تغيير اللغات.}==========#
if($data == "saavmotamy"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ قم بأختيار لغة البوت 
 ➖ Choose the bot language
➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'English','callback_data'=>"eng"],['text'=>'العربية','callback_data'=>"back"]],
[['text'=>'🇫🇷France🇫🇷','callback_data'=>"franc"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"back"]],
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
#By : JIMI @ZISlZ @T0qiR
#=========={لايعمل}==========#
if($data == "u0u"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>مرحبا بك عزيزي : 💙 $first 💙</b>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>

<blockquote>• تم ربط الخدمة بقناة التفعيلات لتحسين الاداء</blockquote>
<blockquote>• تعمل الخدمة علا رصد تفعيلات العملاء تلقائيا</blockquote>
<blockquote>• تقوم الخدمة بمراقبة التفعيلات علا مدار 24 ساعة</blockquote>
<blockquote>• تراقب التفعيلات وتجلب الدول المتوفرة بدلآ عنك</blockquote>
<blockquote>• فائدتها وقت اقل و اختيار امثل وكود اسرع</blockquote>
<blockquote>• يتم تحديث قائمة الدول تلقائيا كل 5 دقائق</blockquote>

<b>⬇️▱▱▱▱▱▱▱▱⬇️▱▱▱▱▱▱▱▱⬇️</b>
",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• إحصـ%ـائيات الشـراء الناجح 📠 •','callback_data'=>'Download1']],
[['text'=>'• الشراء لــ 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣','callback_data'=>"ans-31"]],
[['text'=>'• الشراء لــ 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠','callback_data'=>"Cw-36"]],
[['text'=>'الشراء لـ Google','callback_data'=>"Google"]],
[['text'=>'الشراء لـ Facebook','callback_data'=>"Facebook"]],
[['text'=>'الشراء لـ Snapchat','callback_data'=>"Snapchat"]],
[['text'=>'الشراء لـ Haraj app','callback_data'=>"Harajapp"]],
[['text'=>'الشراء لـ TikTok','callback_data'=>"TikTok"]],
[['text'=>'الشراء لـ Imo app','callback_data'=>"Imo"]],
[['text'=>'الشراء لـ Twitterm','callback_data'=>"Twitter"]],
[['text'=>'الشراء لـ Any other','callback_data'=>"Other"]],
[['text'=>'- رجوع.','callback_data'=>"Buynum"]],
]
])
]);
unlink("data/id/$id/step.txt");
exit;
} 
#=========={امر الشراء الذكي}==========#
$smart = "/smart_purchase";
#
if ($text == $smart ) {
bot('sendMessage', [
'chat_id' => $chat_id,
'text' => "
<b>مرحبا بك عزيزي : 💙 $first 💙</b>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>

<blockquote>• تم ربط الخدمة بقناة التفعيلات لتحسين الاداء</blockquote>
<blockquote>• تعمل الخدمة علا رصد تفعيلات العملاء تلقائيا</blockquote>
<blockquote>• تقوم الخدمة بمراقبة التفعيلات علا مدار 24 ساعة</blockquote>
<blockquote>• تراقب التفعيلات وتجلب الدول المتوفرة بدلآ عنك</blockquote>
<blockquote>• فائدتها وقت اقل و اختيار امثل وكود اسرع</blockquote>
<blockquote>• يتم تحديث قائمة الدول تلقائيا كل 5 دقائق</blockquote>

<b>⬇️▱▱▱▱▱▱▱▱⬇️▱▱▱▱▱▱▱▱⬇️</b>
",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'• إحصـ%ـائيات الشـراء الناجح 📠 •','callback_data'=>'Download1']],
[['text'=>'• الشراء لــ 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣','callback_data'=>"ans-31"]],
[['text'=>'• الشراء لــ 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠','callback_data'=>"Cw-36"]],
[['text'=>'الشراء لـ Google','callback_data'=>"Google"]],
[['text'=>'الشراء لـ Facebook','callback_data'=>"Facebook"]],
[['text'=>'الشراء لـ Snapchat','callback_data'=>"Snapchat"]],
[['text'=>'الشراء لـ Haraj app','callback_data'=>"Harajapp"]],
[['text'=>'الشراء لـ TikTok','callback_data'=>"TikTok"]],
[['text'=>'الشراء لـ Imo app','callback_data'=>"Imo"]],
[['text'=>'الشراء لـ Twitterm','callback_data'=>"Twitter"]],
[['text'=>'الشراء لـ Any other','callback_data'=>"Other"]],
[['text'=>'- رجوع.','callback_data'=>"Buynum"]],
            ]
        ])
    ]);
    exit;
}
#=========={سيرفرات المميز}==========#
#By : JIMI @ZISlZ @T0qiR
if($exdata[0] == "ans"){
$add=$exdata[1];
$APP = str_replace(["31",""],["واتسأب",""],$add);
$a=0;//keyboard
$b=0;//count
foreach($buy['number'] as $zero=>$num){
if($num['add'] == $add){
$price=$num['price'];
$app = $num['app'];
$country = $num['country'];
$operator = $num['operator'];
$name = $_co['country'][$country];
$code = "$country$app$operator$add";
$b++;
if($b%2!=0){
$key[inline_keyboard][$a][]=[text=>"✥ 🚀 $name : ₽ $price ٭",callback_data=>"Xi-$code"];
}else{
$a++;//لنزول سطر
$key[inline_keyboard][$a][]=[text=>"✥ 🚀 $name : ₽ $price ٭",callback_data=>"Xi-$code"];
}
}
}
if($country == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
- عذرا عزيزي لم نقم ب إضافة السيرفرات إلى الآن. 💙
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
$key['inline_keyboard'][] = [['text'=>' عودة ↩','callback_data'=>"u0u"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💙 مرحبآ بك عزيزي العميل في قسم الشراء الذكي 💙

<blockquote>🌩 هذة أكثر دول تم تفعيلها في الدقائق السابقة ونجحت بإرسال الكود</blockquote>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>
<blockquote>• يتم الإستعانة بقناة التفعيلات في إختيار الدول</blockquote>
<blockquote>• راقب التفعيلات وتجلب الدول المتوفرة بدلآ عنك</blockquote>
<blockquote>• ستظهر في الاسفل اكثر الدول تفعيلآ لاخر 5 دقائق</blockquote>
<blockquote>• يتم تحديث قائمة الدول تلقائيا كل 5 دقائق</blockquote>

<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱</b>

<b><s>🤖 - التطبيق : واتساب - 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣</s></b>

<blockquote>• رصيدك : $Balance ₽</blockquote>
<b>⬇️▱▱▱▱▱▱▱▱⬇️▱▱▱▱▱▱▱▱⬇️</b>
",
'parse_mode'=>"html",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
#_________________تعديل معلوماتك في ملف JIMIPHP________________#
include("name.php");
#==========اصير}==========#
if($exdata[0] == "Cwt"){
$add=$exdata[1];
$APP = str_replace(["31","36"],["واتسأب","تيليجرام"],$add);
$a=0;//keyboard
$b=0;//count
foreach($buy['number'] as $zero=>$num){
if($num['add'] == $add){
$price=$num['price'];
$app = $num['app'];
$country = $num['country'];
$operator = $num['operator'];
$name = $_co['country'][$country];
$code = "$country$app$operator$add";
$b++;
if($b%2!=0){
$key[inline_keyboard][$a][]=[text=>"✥ 🚀 $name : ₽ $price ٭",callback_data=>"Xi-$code"];
}else{
$a++;//لنزول سطر
$key[inline_keyboard][$a][]=[text=>"✥ 🚀 $name : ₽ $price ٭",callback_data=>"Xi-$code"];
}
}
}
if($country == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
- عذرا عزيزي لم نقم ب إضافة السيرفرات إلى الآن. 💙
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
$key['inline_keyboard'][] = [['text'=>'✥ عودة ↩ ٭..','callback_data'=>"back"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<blockquote>🎮 - عــــروض تــيــلــيــجــرام - 🎮</blockquote>
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱☠</b>
<b>➖ 💰 رصيدك : $Balance ₽</b>
☑️ l― قـنـاة المـتـوفـر : @$usrch3
<b>☠▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱☠</b>
",
'parse_mode'=>"html",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
#=========={سيرفرات المميز}==========#
if($exdata[0] == "Cw"){
$add=$exdata[1];
$APP = str_replace(["31","36"],["واتسأب","تيليجرام"],$add);
$a=0;//keyboard
$b=0;//count
foreach($buy['number'] as $zero=>$num){
if($num['add'] == $add){
$price=$num['price'];
$app = $num['app'];
$country = $num['country'];
$operator = $num['operator'];
$name = $_co['country'][$country];
$code = "$country$app$operator$add";
$b++;
if($b%2!=0){
$key[inline_keyboard][$a][]=[text=>"✥ 🚀 $name : ₽ $price ٭",callback_data=>"Xi-$code"];
}else{
$a++;//لنزول سطر
$key[inline_keyboard][$a][]=[text=>"✥ 🚀 $name : ₽ $price ٭",callback_data=>"Xi-$code"];
}
}
}
if($country == null){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>"
- عذرا عزيزي لم نقم ب إضافة اللغات إلى الآن. 💙
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
$key['inline_keyboard'][] = [['text'=>' عودة ↩','callback_data'=>"u0u"]];
$keyboad      = json_encode($key);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💙 مرحبآ بك عزيزي العميل في قسم الشراء الذكي 💙

<blockquote>🌩 هذة أكثر دول تم تفعيلها في الدقائق السابقة ونجحت بإرسال الكود</blockquote>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>
<blockquote>• يتم الإستعانة بقناة التفعيلات في إختيار الدول</blockquote>
<blockquote>• راقب التفعيلات وتجلب الدول المتوفرة بدلآ عنك</blockquote>
<blockquote>• ستظهر في الاسفل اكثر الدول تفعيلآ لاخر 5 دقائق</blockquote>
<blockquote>• يتم تحديث قائمة الدول تلقائيا كل 5 دقائق</blockquote>

<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱</b>

<b><s>🤖 - التطبيق : تيليحرام - 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠</s></b>

<blockquote>• رصيدك : $Balance ₽</blockquote>
<b>⬇️▱▱▱▱▱▱▱▱⬇️▱▱▱▱▱▱▱▱⬇️</b>
",
'parse_mode'=>"html",
'reply_markup'=>($keyboad),
]);
unlink("data/id/$id/step.txt");
exit;
}
#By : JIMI @ZISlZ @T0qiR
#==========الملف لجيمي}==========#
#=========={البحث عن الدول}==========#
if($exdata[0] == "research"){
$add=$exdata[1];
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
✅ - يمكنك *البحث عن الدول* من هنا .

🔍 - للبحث *ارسل النداء او الرمز الخاص بالدولة.*

⬅️ - مثال: *967 - 966 - 965 - 1 -213* وهكذا ...

*⚠️ - ملاحظات:
⚠️ - كندا ندائها 1000 بينما أمريكا ندائها 1.
⚠️ - روسيا ندائها 7 بينما كازخستان ندائها 77.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Kn-$add"]]
]
])
]);
file_put_contents("data/id/$id/step.txt","res|$add");
}
if($text != '/start' and $text != null and $exstep[0] == 'res'){
$code = str_replace('+','',$text);
$co = $_co['code'][$code];
$add = $exstep[1];
$APP = str_replace(["10","11","12","13","14","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عروض واتساب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$name = $_co['country'][$co];
if($co != null){
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
✅ *نتيجة البحث: $APP ♻️
☑️ الدولة: •".$name."• رمز الدولة: •+".$code."•*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$name",'callback_data'=>"Ms-$add-$co"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Kn-$add"]]
]
])
]);
unlink("data/id/$id/step.txt");
}else{
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*❌ لايوجد دولة بهذا النداء مسجل في البوت*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Kn-$add"]]
]
])
]);
}
}
#=========={رقم عشوائي للطلب}==========#
$randomNumber = mt_rand(10000, 99999);
echo $randomNumber;
#=========={اللغات}==========#
if($text == '/language'){
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
➖ قم بأختيار لغة البوت 
 ➖ Choose the bot language
➖➖➖➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'English','callback_data'=>"eng"],['text'=>'العربية','callback_data'=>"back"]],
[['text'=>'🇫🇷France🇫🇷','callback_data'=>"franc"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"back"]],
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#By : JIMI @ZISlZ @T0qiR
#=========={قائمة.الدول}==========#
if($text == '/view'){
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
هذي قائمة الدول المتاحة بالبوت 💙..
    ~~~~~~~~~
    
- اليمن 🇾🇪 | yemen
- أفغانستان 🇦🇫 | afghanistan
- السعودية 🇸🇦 | saudiarabia
- الاردن 🇯🇴 | jordan
- الكويت 🇰🇼 | kuwait
- روسيا‌ 🇷🇺 | russia
- امريكا  🇺🇸 | usa
- كازاخستان 🇰🇿 | kazakhstan
- مصر 🇪🇬 | egypt
- كينيا 🇰🇪 | kenya
- بولندا 🇵🇱 | poland
- سوريا 🇸🇾 | syria
- العراق 🇮🇶 | iraq
- 🇮🇱 👞 | israel
- إندونيسيا 🇮🇩 | indonesia
- كمبوديا 🇰🇭 | cambodia
- المكسيك 🇲🇽 | mexico
- فرنسا 🇫🇷 | france
- الهند 🇮🇳 | india
- شيلي 🇨🇱 | chile
- باكستان 🇵🇰 | pakistan
- كندا 🇨🇦 | canada
- الامارات 🇦🇪 | uae
- تونس 🇹🇳 | tunisia
- ليبيا 🇱🇾 | libya
- الصومال 🇸🇴 | somalia
- تركيا 🇹🇷 | turkey
- الصين🇨🇳 | china
- البرازيل 🇧🇷 | brazil
- الجزائر 🇩🇿 | algeria
- منغوليا 🇲🇳 | mongolia
- أوكرانيا 🇺🇦 | ukraine
- اوزبكستان 🇺🇿 | uzbekistan
- الفلبين 🇵🇭 | philippines
- هولندا 🇳🇱 | netherlands
- النمسا 🇦🇹 | austria
- النرويج 🇳🇴 | norway
- ميانمار 🇲🇲 | myanmar
- ماليزيا 🇲🇾 | malaysia
- اسبانيا 🇪🇸 | spain
- كولمبيا 🇨🇴 | colombia
- بيرو 🇵🇪 | peru
- بنما 🇵🇦 | panama
- بلجيكا 🇧🇪 | belgium
- المانيا 🇩🇪 | germany
- سويسرا 🇨🇭 | switzerland
- رومانيا 🇷🇴 | romania
- السويد 🇸🇪 | sweden
- استراليا 🇦🇺 | australia
- فينلندا 🇫🇮 | finland
- بلغاريا 🇧🇬 | bulgaria
- تشاد 🇹🇩 | chad
- الكاميرون 🇨🇲 | cameroon
- الكونغو 🇨🇩 | congo
- الكونغو 🇨🇬 | congon
- غامبيا 🇬🇲 | gambia
- غانا 🇬🇭 | ghana
- ايطاليا 🇮🇹 | italy
- لاوس 🇱🇦 | laos
- البحرين 🇧🇭 | bahrain
- اذربيجان 🇦🇿 | azerbaijan
- عمان 🇴🇲 | oman
- السودان 🇸🇩 | sudan
- قطر 🇶🇦 | qatar
- تنزانيا 🇹🇿 | tanzania
- فيتنام 🇻🇳 | vietnam
- نيجيريا 🇳🇬 | nigeria
- أيرلندا 🇮🇪 | ireland
- هايتي 🇭🇹 | haiti
- صربيا 🇷🇸 | serbia
- ج أفريقيا 🇿🇦 | southafrica
- غيانا 🇬🇾 | guyana
- ألبانيا 🇦🇱 | albania
- موريشيوس 🇲🇺 | mauritius
- تركمانستان 🇹🇲 | turkmenistan
- غينياالاستوائية 🇬🇶 | equatorialguinea
- المغرب 🇲🇦 | morocco
- جورجيا 🇬🇪 | georgia
- نيبال 🇳🇵 | nepal
- تايلاند 🇹🇭 | thailand
- ارمينيا 🇦🇲 | armenia
- طاجيكستان 🇹🇯 | tajikistan
- تايوان 🇹🇼 | taiwan
- رواندا 🇷🇼 | rwanda
- أوغندا 🇺🇬 | uganda
- مدغشقر 🇲🇬 | madagascar
- بوتسوانا 🇧🇼 | botswana
- ناميبيا 🇳🇦 | namibia
- موزمبيق 🇲🇿 | mozambique
- جيبوتي 🇩🇯 | djibouti
- إثيبوبيا 🇪🇹 | ethiopia
- التشيك 🇨🇿 | czech
- مولدوفا 🇲🇩 | moldova
- إستونيا 🇪🇪 | estonia
- قبرص 🇨🇾 | cyprus
- بيلاروس 🇧🇾 | belarus
- فنزويلا 🇻🇪 | venezuela
- الأرجنتين 🇦🇷 | argentina
- بوليفيا 🇧🇴 | bolivia
- باراغواي 🇵🇾 | paraguay
- ماكو 🇲🇴 | macau
- توغو 🇹🇬 | togo
- نيكاراغوا 🇳🇮 | nicaragua
- الإكوادور 🇪🇨 | ecuador
- سورينام 🇸🇷 | suriname
- ليبيريا 🇱🇷 | liberia
- البرتغال 🇵🇹 | portugal
- غواتيمالا 🇬🇹 | guatemala
- زامبيا 🇿🇼 | zimbabwe
- ليتوانيا 🇱🇹 | lithuania
- كرواتيا 🇭🇷 | croatia
- لاتفيا 🇱🇻 | latvia
- ايران 🇮🇷 | iran
- بنقلادش 🇧🇩 | bangladesh
- السنقال 🇸🇳 | senegal
- سيريلانكا 🇱🇰 | srilanka
- نيوزلاندا 🇳🇿 | newzealand
- غينيا 🇬🇳 | guinea
- مالي 🇲🇱 | mali
- أنغولا 🇦🇴 | angola
- بابو 🇵🇬 | papuanewguinea
- المجر 🇭🇺 | hungary
- هندراوس 🇭🇳 | honduras
- كوستاريكا 🇨🇷 | costarica
- بورتوريكو 🇵🇷 | puertorico
- السلفادور 🇸🇻 | salvador
- جاميكا 🇯🇲 | jamaica
- سوازيلاند 🇸🇿 | swaziland
- البوسنة والهرسك 🇧🇦 | bih
- الدومينيكان 🇩🇴 | dominican
- كوبا 🇨🇺 | cuba
- موريتانيا 🇲🇷 | mauritania
- سيراليون 🇸🇱 | sierraleone
- بربادوس 🇧🇧 | barbados
- بوروندي 🇧🇮 | burundi
- بنين 🇧🇯 | benin
- بروني 🇧🇳 | brunei
- جزر البهاما 🇧🇸 | bahamas
- دومينيكا 🇩🇲 | dominica
- غرينادا 🇬🇩 | grenada
- غينيا-بيساو 🇬🇼 | guineabissau
- جزر القمر 🇰🇲 | comoros
- سانت كيتس ونيفيس 🇰🇳 | saintkittsandnevis
- ليسوتو 🇱🇸 | lesotho
- مالاوي 🇲🇼 | malawi
- النيجر 🇳🇪 | niger
- ريونيون 🇫🇷 | reunion
- زامبيا 🇿🇲 | zambia
- بوركينافاسو 🇧🇫 | burkinafaso
- لبنان 🇱🇧 | lebanon
- الغابون 🇬🇦 | gabon
- المالديف 🇲🇻 | maldives
- الدينمارك 🇩🇰 | denmark
- ارتيريا 🇪🇷 | eritrea
- جنوب السودان 🇸🇸 | southsudan
- أروبا 🇦🇼 | aruba
- اليابان 🇯🇵 | japan
- مونتسيرات 🇲🇸 | montserrat
- أنغيلا 🇦🇮 | anguilla
- شمال مقدونيا 🇲🇰 | northmacedonia
- سيشيل 🇸🇨 | seychelles
- كاليدونيا الجديدة 🇳🇨 | newcaledonia
- الرأس الأخضر 🇨🇻 | capeverde
- فلسطين 🇵🇸 | palestine
- كورياالجنوبية 🇰🇷 | southkorea
- الصحراء الغربية 🇪🇭 | westernsahara
- ساحل العاج 🇨🇮 | ivorycoast
- قرغيزستان 🇰🇬 | kyrgyzstan
- ساموا 🇼🇸 | samoa
- العشوائي 🎲  | rand
- انجلترا 🏴󠁧󠁢󠁥󠁮󠁧󠁿 | england
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#By : JIMI @ZISlZ 
#=========={ربح الروبل عبر الامرfree}==========#
if($text == '/free'){
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
*▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱*
💙 - مرحبا 💙  $first 💙
*
💲 إربح روبل مجاناً قم بمشاركة رابط البوت مع أصدقائك 🤵🏻
- سوف تربح $assignru روبل مقابل كل شخص يقوم بالدخول إلى البوت عبر الرابط الخاص بك 🧿
*
*💚 - رابط الدعوة الخاص بك 💚* : `https://t.me/$me?start=$cod`
اضغط لنسخ 👆

إجمالي أرباحك :  $counmyru ₽
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
#By : JIMI @ZISlZ 
#=========={المساعدة}==========#
if($text == '/help'){
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
*➖ مرحبا $first 💙
🔹️ - إليك قائمة المساعدة 

🔸️➖ اذا كنت مستخدم جديد ارسل /start ليتم التحقق من حسابك 
من ثم ارسل $zozamr للدخول للقائمة الرئيسية 💙
🔹️➖  لبريد اكتروني مؤقت  📨 /create_email .✥*.
🔸️➖ لقسم الشراء الذكي 🛰 /smart_purchase
🔹️➖ لعرض عروض البوت ارسل /offers 
🔸️➖ لعرض الأرقام المتوفرة للتطبيقات ارسل /app
🔹️➖  لعرض أرقام تويتر ارسل /twi .✥*.
🔸️➖  لعرض أرقام تيك توك ارسل /tik 
🔹️➖ لعرض أرقام سناب شات ارسل /snap .✥*.
🔸️➖  لعرض أرقام قوقل ارسل /google 
🔹️➖  لربح أرقام انستقرام ارسل /insta .✥*.
🔸️➖  لعرض أرقام حراج ارسل /haraj 
🔹️➖  لعرض أرقام ايمو ارسل /imo .✥*.
🔸️➖  لعرض أرقام السيرفر العام ارسل /other
🔹️➖  لربح أرقام فيسبوك ارسل /facebook .✥*.
🔸️➖  لمعرفة الدول المضافه في البوت ارسل /view 
⚜️ -   الإدارة : $useradmin  - ⚜️

  𓆩•|ــــــــ( $yasein )ـــــــ|•𓆪
➖➖➖➖➖➖➖➖➖➖*
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"قناة البوت الرسمية | 🏡.", 'url'=>"http://t.me/$usrch1"],['text'=>"✔️ | طلبات الشراء.", 'url'=>"http://t.me/$usrch2"]],
[['text'=>"⚜️ | الادارة.", 'url'=>"http://t.me/$usradmin"]],
[['text'=>"📘 | التعليمات.", 'url'=>"http://t.me/$usrch1"]],
[['text'=>'🔸️القائمة الرئيسية 🔹️','callback_data'=>'back']]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#By : JIMI @ZISlZ 
#=========={ قائمة التطبيقاتت الامر}==========#
if($text == '/app'){
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
*➖ مرحبا $first 💙
🔹️ - إليك قائمة التطبيقات المتاحه
➖ قم بأختيار نوع التطبيق الذي تريد شراء رقم له  ✥*.
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ واتس اب -𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣🛍 ٭','callback_data'=>"Kn-2"]],
[['text'=>'✥ تليجرام -𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠 🎲','callback_data'=>"Kn-3"]],
[['text'=>'✥ انستغرام -𝗟𝗡𝗦𝗧𝗔𝗚𝗥𝗔𝗠🎳 ٭','callback_data'=>"Kn-5"]],
[['text'=>'✥ فيسبوك -𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞🎯','callback_data'=>"Kn-4"]],
[['text'=>'✥ تويتر -𝗧𝗪𝗜𝗧𝗧𝗘𝗥 🐤','callback_data'=>"Kn-6"]],
[['text'=>'✥ تيك توك -𝗧𝗜𝗞𝗧𝗢𝗞 🎥','callback_data'=>"Kn-7"],['text'=>'✥ جوجل-G𝗼𝗼𝗴𝗹𝗲⛱','callback_data'=>"Kn-8"]],
[['text'=>'✥ سناب شات -𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧♣️ ٭','callback_data'=>"Kn-11"],['text'=>'✥ حراج -𝗛𝗔𝗥𝗔j 🪗 ٭','callback_data'=>"Kn-13"]],[['text'=>'✥ ايمو - 𝗜𝗠𝗢 💎 ٭','callback_data'=>"Kn-9"]],
[['text'=>'✥ 🧿 السيرفر العام 🤖️','callback_data'=>"Kn-14"]],
[['text'=>'✥ عروض WhatsApp 💙 ٭','callback_data'=>"Wo"],['text'=>'✥ عروض Telegram 💚 ٭','callback_data'=>"Cwt-36"]], 
[['text'=>'✥ عودة ↩️ ٭','callback_data'=>"back"]]
]
])
]);
unlink("data/id/$id/step.txt");
}
}
#By : JIMI @ZISlZ 
#=========={Pay_am}==========#
if($exdata[0] == "Pay_am"){
$EEM = $exdata[1];
$price = $exdata[2];
$Balancc = file_get_contents("EMILS/$EEM/points.txt");
$BALANCE = $Balancc + $price;
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
*➖️ الايدي؟ : $EEM 🆔️•*
➖️ تم بنجاح إرجاع $price روبل ♻️•*
➖️ رصيده الان : $Balancc روبل ✅️•
*➖️ رصيده بعد الإرجاع : $BALANCE روبل 📮•
➖️➖️➖️⚜️➖️➖️➖️
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
$points = file_get_contents("EMILS/$EEM/points.txt");
$as = $points + $price;
file_put_contents("EMILS/$EEM/points.txt",$as);
}
#=========={تسجيل الدخول}==========#
if($exdata[0] == "emilead"){
$emile = $exdata[1];
$pase = $EMILS['emils'][$emile]['pass'];
if($EMILS['emils'][$emile]['emil'] == null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
☑️ - عذرا هذا الحساب قد تم حذفة من البوت بشكل كامل ⚠️
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
}else{
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
✅ - تم بنجاح تسجل الدخول للحساب إذهب إلى البوت لرؤية الحساب
",
'show_alert'=>true
]);
bot('sendMessage',[
'chat_id'=>$sudo,
'text'=>"
⚠️ - تم تسجيل دخولك إلى الحساب *$emile*
",
'parse_mode'=>"MarkDown",
]);
$EMILNow['emil'][$sudo] = $emile;
$EMILNow['password'][$sudo] = $pase;
Now($EMILNow);
unlink("data/id/$id/step.txt");
}
}
#=========={الشراء العشوائي}==========#
if($exdata[0] == "Wi"){
$add = $exdata[1];
$price=str_replace(["21","22","23","24","25","26","27","28","29","30"],["10","16","0","0","0","15","16","0","0","0"],$add);
if($price > $Balance or $Balance < $price or $Balance == 0 or $Balance === 0 or $Balance < 0){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"
➖ رصيدك غير كافي ❤
➖ رصيدك الحالي $Balance ₽
",
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}
$idSend=$orderall;
$ads = $random[$nums]['add'];
if($ads <= 1){
exit;
}
$ads=$ads-1;
if(time() - $BUYSNUM[number][$Detector][times] <= 2){
unlink("data/id/$id/step.txt");
exit;
}
for($i=1; $i<26;$i++){
$rand=rand(0,$ads);
$zero = $random[$add]['zero'][$rand];
$price=$buy['number'][$zero][price];
$country = $buy['number'][$zero][country];
$operator = $buy['number'][$zero][operator];
$app = $buy['number'][$zero][app];
$site = $buy['number'][$zero][site];
$status_zero = $buy['number'][$zero];
$name = $_co['country'][$country];
if($add >= 21 and $add <= 25){
$add=21;
}elseif($add >= 26 and $add <= 30){
$add=26;
}
$APP = str_replace(["21","26"],["عشوائي واتسأب","عشوائي تيليجرام"],$add);
$server = str_replace(["21","26"],["Whatsapp","Telegram"],$add);
$APP_S = str_replace(["Whatsapp","Telegram"],["الواتسأب","التيليجرام"],$server);
if($add == 1){
$back = "Wo";
}elseif($add >= 21 and $add <= 30){
$back = "worldwide";
}elseif($add >= 31 and $add <= 40){
$back = "Buynum";
}else{
$back = "Ms-$add-$country";
}
if($app == "wa"){
$wa = "✥ تحقق من الرقم في WhatsApp ٭ ️.";
}elseif($app == "tg"){
$tg = "✥ تحقق من الرقم في Telegram ٭.";
}
if($addblusdel[$site]['add'] == "ok"){
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getNum&site=$site&country=$country&app=$app&operator=$operator"),1);
}
if($site=="2ndline"){
$num2nd = $api[num2nd];
}
$status = $api[status];
$number = $api[number];
$idnumber = $api[idnumber];
$time = $api[time];
$Location = $api[Location];
#__________Time
$z=$time/60;
$ex1 = explode(".", $z);
$z="0.$ex1[1]";
$z=$z*60;
$ex2 = explode(".", $z);
$n = mb_strlen("$ex2[0]");
if($n > 1){
$start_time="$ex1[0]:$ex2[0]";
}else{
$start_time="$ex1[0]:0$ex2[0]";
}
if($ex1[0] == null){
$k="ثانية";
}else{
$k="دقيقة";
}
#__________Time
if($status != "200" or $status_zero == null){
if($i==25){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*- القسم الذي طلبته غير متوفر مؤقتًا ، يرجى المحاولة مرة أخرى وقد يضمن جلب الدولة عشوائياً*
",
'parse_mode'=>'Markdown',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- محاولة مرة أخرى. 🔄','callback_data'=>"Wi-$add"]],
[['text'=>'✥ عودة ↩ ٭. 🔜','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*- جاري جلب الرقم لك الآن، يرجى الإنتظار ...* ☑️
",
'parse_mode'=>"MarkDown",
]);
unlink("data/id/$id/step.txt");
}else{
if($site == "2ndline"){
if($num2nd == null){
for($ii=0; $ii<10;$ii++){
sleep(1);
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getNum2nd&site=$site&country=$country&id=$idds"),1);
$status = $api[status];
$num2nd = $api[num2nd];
$number = $api[number];
$idnumber = $api[idnumber];
$Location = $api[Location];
$see = str_replace(["0","1","2","3","4","5","6","7","8","9"],["••","•••","••••","••","•••","••••","••","•••","••••"],$ii);
if($num2nd == null and $ii==10){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- عذرا عزيزي ليس متوفر حاليا هذه الدولة.
💠 - قم بتحربة أي سيرفر آخر.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭. 🔜','callback_data'=>"Buynum"]]
]
])
]);
exit;
}elseif($num2nd == null){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- جاري تحضير الرقم يرجى الإنتظار ثواني فقط $see*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭. 🔜','callback_data'=>"::"]]
]
])
]);
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب | <s>$randomNumber</s> 🛎•</b>
<b>➖ الدولة | $name •</b>
<b>➖ الرقم | <code>$number</code>  ☎️•</b>
<b>➖ الكود | قيد الانتظار 📩</b>
<b>➖ الحالة | RECEIVED ... 🔎•</b>
<b>➖ التطبيق : $APP •</b>
<b>➖ السعر | ₽ $price 💙•</b>

<b>➖ انشاء : $DAY3 •  📫•</b>
<b>➖ انتهاء : $start_time   📭•</b>
<blockquote>➖ عدد المحاولات الشراء : 1</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$wa",'url'=>"wa.me/$number"]],
[['text'=>"$tg",'url'=>"T.me/$number"]],
[['text'=>'🔄 - تغيير الرقم - 🔄','callback_data'=>"$data"]],
[['text'=>'✅ - طلب الكود - ✅','callback_data'=>"Code-$idSend"]],
[['text'=>'❌ - الغاء الرقم - ❌','callback_data'=>"Ban-$idSend"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Ban-$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
👤➖ زبون︙قام بشراء رقم جديد لتطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name*
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المدفوع للرقم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>".✥ تحقق من  العضو  ٭ ️.",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
file_put_contents("data/id/$id/restriction.txt","$ordermy");
$times=time();
$BUYSNUM[number][$ordermy][idSend] = $idSend;
$BUYSNUM[number][$ordermy][phone] = $number;
$BUYSNUM[number][$ordermy][sms][0][code] = null;
$BUYSNUM[number][$ordermy][status] = 1;
$BUYSNUM[number][$ordermy][operator] = $operator;
$BUYSNUM[number][$ordermy][app] = $app;
$BUYSNUM[number][$ordermy][add] = $add;
$BUYSNUM[number][$ordermy][price] = $price;
$BUYSNUM[number][$ordermy][id] = $idnums;
$BUYSNUM[number][$ordermy][site] = $site;
$BUYSNUM[number][$ordermy][zero] = $zero;
$BUYSNUM[number][$ordermy][country] = $country;
$BUYSNUM[number][$ordermy][idnumber] = $idnumber;
$BUYSNUM[number][$ordermy][type] = "direct";
$BUYSNUM[number][$ordermy][finish] = $time;
$BUYSNUM[number][$ordermy][times] = $times;
$BUYSNUM[number][$ordermy]["chat-id"] = $id;
$BUYSNUM[number][$ordermy][DAY] = $DAY;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][account] = $EM;
$ORDERALL[$idSend][order] = $ordermy;
$ORDERALL[$idSend][status] = 1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
}
}
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب | <s>$randomNumber</s> 🛎•</b>
<b>➖ الدولة | $name •</b>
<b>➖ الرقم | <code>$number</code>  ☎️•</b>
<b>➖ الكود | قيد الانتظار 📩</b>
<b>➖ الحالة | RECEIVED ... 🔎•</b>
<b>➖ التطبيق : $APP •</b>
<b>➖ السعر | ₽ $price 💙•</b>

<b>➖ انشاء : $DAY3 •  📫•</b>
<b>➖ انتهاء : $start_time   📭•</b>
<blockquote>➖ عدد المحاولات الشراء : 1</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$wa",'url'=>"wa.me/$number"]],
[['text'=>"$tg",'url'=>"T.me/$number"]],
[['text'=>'🔄 - تغيير الرقم - 🔄','callback_data'=>"$data"]],
[['text'=>'✅ - طلب الكود - ✅','callback_data'=>"Code-$idSend"]],
[['text'=>'❌ - الغاء الرقم - ❌','callback_data'=>"Ban-$idSend"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Ban-$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
👤➖ زبون︙قام بشراء رقم جديد لتطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name*
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المدفوع للرقم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>".✥ تحقق من  العضو  ٭ ️.️",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
file_put_contents("data/id/$id/restriction.txt","$ordermy");
$times=time();
$BUYSNUM[number][$ordermy][idSend] = $idSend;
$BUYSNUM[number][$ordermy][phone] = $number;
$BUYSNUM[number][$ordermy][sms][0][code] = null;
$BUYSNUM[number][$ordermy][status] = 1;
$BUYSNUM[number][$ordermy][operator] = $operator;
$BUYSNUM[number][$ordermy][app] = $app;
$BUYSNUM[number][$ordermy][add] = $add;
$BUYSNUM[number][$ordermy][price] = $price;
$BUYSNUM[number][$ordermy][id] = $idnums;
$BUYSNUM[number][$ordermy][site] = $site;
$BUYSNUM[number][$ordermy][zero] = $zero;
$BUYSNUM[number][$ordermy][country] = $country;
$BUYSNUM[number][$ordermy][idnumber] = $idnumber;
$BUYSNUM[number][$ordermy][type] = "direct";
$BUYSNUM[number][$ordermy][finish] = $time;
$BUYSNUM[number][$ordermy][times] = $times;
$BUYSNUM[number][$ordermy]["chat-id"] = $id;
$BUYSNUM[number][$ordermy][DAY] = $DAY;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][account] = $EM;
$ORDERALL[$idSend][order] = $ordermy;
$ORDERALL[$idSend][status] = 1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
}
}
}
#By : JIMI @ZISlZ 
#=========={Buy Site All2}==========#
if($exdata[0] == "Ii"){
$zero = $exdata[1];
$order = $exdata[2];
$zero = md5($zero);
$numbers=$BUYSNUM[number][$order][phone];
$price=$buy['number'][$zero][price];
$country = $buy['number'][$zero][country];
$add = $buy['number'][$zero][add];
$operator = $buy['number'][$zero][operator];
$app = $buy['number'][$zero][app];
$site = $buy['number'][$zero][site];
$status_zero = $buy['number'][$zero];
$name = $_co['country'][$country];
$idSend=$orderall;
if($add >= 21 and $add <= 25){
$add=21;
}elseif($add >= 26 and $add <= 30){
$add=26;
}
$APP = str_replace(["10","11","12","13","14","21","26","31","36","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي تيليجرام","واتسأب المميز","عروض تيليجرام","عروض واتساب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$server = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);
$APP_S = str_replace(["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],["الواتسأب","التيليجرام","الفيسبوك","الإنستقرام","التويتر","التيك توك","القوقل","الإيمو","فايبر","سناب شات","نيتفلكس","حراج","أي تطبيق لديك"],$server);
if($add == 1){
$back = "Wo";
}elseif($add >= 21 and $add <= 30){
$back = "worldwide";
}elseif($add >= 31 and $add <= 40){
$back = "Buynum";
}else{
$back = "Ms-$add-$country";
}
if($app == "wa"){
$wa = "☑️ - رؤية حالة الرقم في واتسأب. ↖️";
}elseif($app == "tg"){
$tg = "☑️ - رؤية حالة الرقم في تيليجرام. ↖️";
}
if(time() - $BUYSNUM[number][$Detector][times] <= 2){
unlink("data/id/$id/step.txt");
exit;
}
if($addblusdel[$site]['add'] == "ok" and $order == null){
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getNum&site=$site&country=$country&app=$app&operator=$operator"),1);
}elseif($addblusdel[$site]['add'] == "ok" and $order != null){
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getNum5sim&site=$site&country=$country&app=$app&number=$numbers"),1);
}
if($site=="2ndline"){
$num2nd = $api[num2nd];
}
$status = $api[status];
$number = $api[number];
$idnumber = $api[idnumber];
$time = $api[time];
$Location = $api[Location];
#__________Time
#By : JIMI @ZISlZ 
$z=$time/60;
$ex1 = explode(".", $z);
$z="0.$ex1[1]";
$z=$z*60;
$ex2 = explode(".", $z);
$n = mb_strlen("$ex2[0]");
if($n > 1){
$start_time="$ex1[0]:$ex2[0]";
}else{
$start_time="$ex1[0]:0$ex2[0]";
}
if($ex1[0] == null){
$k="ثانية";
}else{
$k="دقيقة";
}
#By : JIMI @ZISlZ 
#__________Time
if($status_zero == null){
unlink("data/id/$id/step.txt");
}elseif($status != "200"){
$zero = "$country$app$operator$add";
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- يبدوا أن هذا السيرفر لا يوجد به أرقام في الوقت الحالي، جرب سيرفر آخر .

↩️ - قم ب تجربة المحاولة 12 مرات قد تزيد من سرعة جلب الأرقام المعدومة.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'- محاولة الشراء 12 مره ♻️','callback_data'=>"Xi-$zero"]],
[['text'=>'- اعادة الشراء ✅','callback_data'=>"$data"]],
[['text'=>'- العوده الى السرفرات 🔁','callback_data'=>"$back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}else{
if($site == "2ndline"){
if($num2nd == null){
for($ii=0; $ii<10;$ii++){
sleep(1);
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getNum2nd&site=$site&country=$country&id=$idds"),1);
$status = $api[status];
$num2nd = $api[num2nd];
$number = $api[number];
$idnumber = $api[idnumber];
$Location = $api[Location];
$see = str_replace(["0","1","2","3","4","5","6","7","8","9"],["••","•••","••••","••","•••","••••","••","•••","••••"],$ii);
if($num2nd == null and $ii==10){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- عذرا عزيزي ليس متوفر حاليا هذه الدولة.
💠 - قم بتحربة أي سيرفر آخر.*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭. 🔜','callback_data'=>"Buynum"]]
]
])
]);
exit;
}elseif($num2nd == null){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
☑️ *- جاري تحضير الرقم يرجى الإنتظار ثواني فقط $see*
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭. 🔜','callback_data'=>"::"]]
]
])
]);
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب | <s>$randomNumber</s> 🛎•</b>
<b>➖ الدولة | $name •</b>
<b>➖ الرقم | <code>$number</code>  ☎️•</b>
<b>➖ الكود | قيد الانتظار 📩</b>
<b>➖ الحالة | RECEIVED ... 🔎•</b>
<b>➖ التطبيق : $APP •</b>
<b>➖ السعر | ₽ $price 💙•</b>

<b>➖ انشاء : $DAY3 •  📫•</b>
<b>➖ انتهاء : $start_time   📭•</b>
<blockquote>➖ عدد المحاولات الشراء : 1</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'🔄 - تغيير الرقم - 🔄','callback_data'=>"$data"]],
[['text'=>'✅ - طلب الكود - ✅','callback_data'=>"Code-$idSend"]],
[['text'=>"$wa",'url'=>"wa.me/$number"]],
[['text'=>"$tg",'url'=>"T.me/$number"]],
[['text'=>'❌ - الغاء الرقم - ❌','callback_data'=>"Ban-$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
👤➖ زبون︙قام بشراء رقم جديد لتطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name*
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المدفوع للرقم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>".✥ تحقق من  العضو  ٭ ️.",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
file_put_contents("data/id/$id/restriction.txt","$ordermy");
$times=time();
$BUYSNUM[number][$ordermy][idSend] = $idSend;
$BUYSNUM[number][$ordermy][phone] = $number;
$BUYSNUM[number][$ordermy][sms][0][code] = null;
$BUYSNUM[number][$ordermy][status] = 1;
$BUYSNUM[number][$ordermy][operator] = $operator;
$BUYSNUM[number][$ordermy][app] = $app;
$BUYSNUM[number][$ordermy][add] = $add;
$BUYSNUM[number][$ordermy][price] = $price;
$BUYSNUM[number][$ordermy][id] = $idnums;
$BUYSNUM[number][$ordermy][site] = $site;
$BUYSNUM[number][$ordermy][zero] = $zero;
$BUYSNUM[number][$ordermy][country] = $country;
$BUYSNUM[number][$ordermy][idnumber] = $idnumber;
$BUYSNUM[number][$ordermy][type] = "direct";
$BUYSNUM[number][$ordermy][finish] = $time;
$BUYSNUM[number][$ordermy][times] = $times;
$BUYSNUM[number][$ordermy]["chat-id"] = $id;
$BUYSNUM[number][$ordermy][DAY] = $DAY;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][account] = $EM;
$ORDERALL[$idSend][order] = $ordermy;
$ORDERALL[$idSend][status] = 1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
}
}
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب | <s>$randomNumber</s> 🛎•</b>
<b>➖ الدولة | $name •</b>
<b>➖ الرقم | <code>$number</code>  ☎️•</b>
<b>➖ الكود | قيد الانتظار 📩</b>
<b>➖ الحالة | RECEIVED ... 🔎•</b>
<b>➖ التطبيق : $APP •</b>
<b>➖ السعر | ₽ $price 💙•</b>

<b>➖ انشاء : $DAY3 •  📫•</b>
<b>➖ انتهاء : $start_time   📭•</b>
<blockquote>➖ عدد المحاولات الشراء : 1</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'🔄 - تغيير الرقم - 🔄','callback_data'=>"$data"]],
[['text'=>'✅ - طلب الكود - ✅','callback_data'=>"Code-$idSend"]],
[['text'=>"$wa",'url'=>"wa.me/$number"]],
[['text'=>"$tg",'url'=>"T.me/$number"]],
[['text'=>'❌ - الغاء الرقم - ❌','callback_data'=>"Ban-$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
👤➖ زبون︙قام بشراء رقم جديد لتطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name*
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المدفوع للرقم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>".✥ تحقق من  العضو  ٭ ️.",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
file_put_contents("data/id/$id/restriction.txt","$ordermy");
$times=time();
$BUYSNUM[number][$ordermy][idSend] = $idSend;
$BUYSNUM[number][$ordermy][phone] = $number;
$BUYSNUM[number][$ordermy][sms][0][code] = null;
$BUYSNUM[number][$ordermy][status] = 1;
$BUYSNUM[number][$ordermy][operator] = $operator;
$BUYSNUM[number][$ordermy][app] = $app;
$BUYSNUM[number][$ordermy][add] = $add;
$BUYSNUM[number][$ordermy][price] = $price;
$BUYSNUM[number][$ordermy][id] = $idnums;
$BUYSNUM[number][$ordermy][site] = $site;
$BUYSNUM[number][$ordermy][zero] = $zero;
$BUYSNUM[number][$ordermy][country] = $country;
$BUYSNUM[number][$ordermy][idnumber] = $idnumber;
$BUYSNUM[number][$ordermy][type] = "direct";
$BUYSNUM[number][$ordermy][finish] = $time;
$BUYSNUM[number][$ordermy][times] = $times;
$BUYSNUM[number][$ordermy]["chat-id"] = $id;
$BUYSNUM[number][$ordermy][DAY] = $DAY;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][account] = $EM;
$ORDERALL[$idSend][order] = $ordermy;
$ORDERALL[$idSend][status] = 1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
}
}
#=========={Buy Site All TO}==========#
if($exdata[0] == "Xi"){
$zero = $exdata[1];
$zero = md5($zero);
$price=$buy['number'][$zero][price];
$country = $buy['number'][$zero][country];
$add = $buy['number'][$zero][add];
$operator = $buy['number'][$zero][operator];
$app = $buy['number'][$zero][app];
$site = $buy['number'][$zero][site];
$status_zero = $buy['number'][$zero];
$name = $_co['country'][$country];
$idSend=$orderall;
if($add >= 21 and $add <= 25){
$add=21;
}elseif($add >= 26 and $add <= 30){
$add=26;
}
$APP = str_replace(["10","11","12","13","14","21","26","31","36","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي تيليجرام","واتسأب المميز","عروض تيليجرام","عروض واتساب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$server = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);
$APP_S = str_replace(["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],["الواتسأب","التيليجرام","الفيسبوك","الإنستقرام","التويتر","التيك توك","القوقل","الإيمو","فايبر","سناب شات","نيتفلكس","حراج","أي تطبيق لديك"],$server);
if($add == 1){
$back = "Wo";
}elseif($add >= 21 and $add <= 30){
$back = "worldwide";
}elseif($add >= 31 and $add <= 40){
$back = "Buynum";
}else{
$back = "Ms-$add-$country";
}
if($app == "wa"){
$wa = "✥ تحقق من الرقم في WhatsApp ٭ ️";
}elseif($app == "tg"){
$tg = "✥ تحقق من الرقم في Telegram ٭ ️";
}
if(time() - $BUYSNUM[number][$Detector][times] <= 2){
unlink("data/id/$id/step.txt");
exit;
}
if($status_zero == null){
unlink("data/id/$id/step.txt");
}elseif($status != "200"){
for($i=1; $i<21;$i++){
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getNum&site=$site&country=$country&app=$app&operator=$operator"),1);
$status = $api[status];
if($site=="2ndline"){
$num2nd = $api[num2nd];
}
if($site=="nagm" and $status == "200"){
$site = $api[site];
}
$number = $api[number];
$idnumber = $api[idnumber];
$time = $api[time];
$Location = $api[Location];
#__________Time
$z=$time/60;
$ex1 = explode(".", $z);
$z="0.$ex1[1]";
$z=$z*60;
$ex2 = explode(".", $z);
$n = mb_strlen("$ex2[0]");
if($n > 1){
$start_time="$ex1[0]:$ex2[0]";
}else{
$start_time="$ex1[0]:0$ex2[0]";
}
if($ex1[0] == null){
$k="ثانية";
}else{
$k="دقيقة";
}
#__________Time
if($status != "200"){
if($i==20){
$zero = "$country$app$operator$add";
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 لا يوجد أرقام في هذا السيرفر حالياً... 
   قم بتجربة سيرفر اخر 💙
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ أعادة المحاولة ٭↻.','callback_data'=>"$data"]],
[['text'=>'✥ عودة ↩️ ٭ .','callback_data'=>"$back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}
$see = str_replace(["10","11","12","13","14","15","16","17","18","19","0","1","2","3","4","5","6","7","8","9"],["....",".....","..","...","....",".....","..","...","....",".....","..","...","....",".....","..","...","....",".....","..","..."],$i);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*يتم* الان *محاولة* شراء *رقم* 🔁
  *يرجى*الانتظار  6 *ثواني* 💙.
",
'parse_mode'=>"MarkDown",
]);
unlink("data/id/$id/step.txt");
}else{
if($site == "2ndline"){
if($num2nd == null){
for($ii=0; $ii<10;$ii++){
sleep(1);
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getNum2nd&site=$site&country=$country&id=$idds"),1);
$status = $api[status];
$num2nd = $api[num2nd];
$number = $api[number];
$idnumber = $api[idnumber];
$Location = $api[Location];
$see = str_replace(["0","1","2","3","4","5","6","7","8","9"],["..","...","....",".....","..","...","....",".....","..","..."],$ii);
if($num2nd == null and $ii==10){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 لا يوجد أرقام في هذا السيرفر حالياً... 
   قم بتجربة سيرفر اخر 💙
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭. 🔜','callback_data'=>"$back"]]
]
])
]);
exit;
}elseif($num2nd == null){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*يتم* الان *محاولة* شراء *رقم* 🔁
  *يرجى*الانتظار  6 *ثواني* 💙.
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✥ عودة ↩ ٭. 🔜','callback_data'=>"::"]]
]
])
]);
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*يتم* الان *محاولة* شراء *رقم* 🔁
  *يرجى*الانتظار  6 *ثواني* 💙.
",
'parse_mode'=>"MarkDown",
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب | <s>$randomNumber</s> 🛎•</b>
<b>➖ الدولة | $name •</b>
<b>➖ الرقم | <code>$number</code>  ☎️•</b>
<b>➖ الكود | قيد الانتظار 📩</b>
<b>➖ الحالة | RECEIVED ... 🔎•</b>
<b>➖ التطبيق : $APP •</b>
<b>➖ السعر | ₽ $price 💙•</b>

<b>➖ انشاء : $DAY3 •  📫•</b>
<b>➖ انتهاء : $start_time   📭•</b>
<blockquote>➖ عدد المحاولات الشراء : 1</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"$wa",'url'=>"wa.me/$number"]],
[['text'=>"$tg",'url'=>"T.me/$number"]],
[['text'=>'🔄 - تغيير الرقم - 🔄','callback_data'=>"$data"]],
[['text'=>'✅ - طلب الكود - ✅','callback_data'=>"Code-$idSend"]],
[['text'=>"$wa",'url'=>"wa.me/$number"]],
[['text'=>"$tg",'url'=>"T.me/$number"]],
[['text'=>'❌ - الغاء الرقم - ❌','callback_data'=>"Ban-$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
👤➖ زبون︙قام بشراء رقم جديد لتطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name*
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المدفوع للرقم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>".✥ تحقق من  العضو  ٭ ️.",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
file_put_contents("data/id/$id/restriction.txt","$ordermy");
$times=time();
$BUYSNUM[number][$ordermy][idSend] = $idSend;
$BUYSNUM[number][$ordermy][phone] = $number;
$BUYSNUM[number][$ordermy][sms][0][code] = null;
$BUYSNUM[number][$ordermy][status] = 1;
$BUYSNUM[number][$ordermy][operator] = $operator;
$BUYSNUM[number][$ordermy][app] = $app;
$BUYSNUM[number][$ordermy][add] = $add;
$BUYSNUM[number][$ordermy][price] = $price;
$BUYSNUM[number][$ordermy][id] = $idnums;
$BUYSNUM[number][$ordermy][site] = $site;
$BUYSNUM[number][$ordermy][zero] = $zero;
$BUYSNUM[number][$ordermy][country] = $country;
$BUYSNUM[number][$ordermy][idnumber] = $idnumber;
$BUYSNUM[number][$ordermy][type] = "direct";
$BUYSNUM[number][$ordermy][finish] = $time;
$BUYSNUM[number][$ordermy][times] = $times;
$BUYSNUM[number][$ordermy]["chat-id"] = $id;
$BUYSNUM[number][$ordermy][DAY] = $DAY;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][account] = $EM;
$ORDERALL[$idSend][order] = $ordermy;
$ORDERALL[$idSend][status] = 1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
}
}
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*يتم* الان *محاولة* شراء *رقم* 🔁
  *يرجى*الانتظار  6 *ثواني* 💙.
",
'parse_mode'=>"MarkDown",
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب | <s>$randomNumber</s> 🛎•</b>
<b>➖ الدولة | $name •</b>
<b>➖ الرقم | <code>$number</code>  ☎️•</b>
<b>➖ الكود | قيد الانتظار 📩</b>
<b>➖ الحالة | RECEIVED ... 🔎•</b>
<b>➖ التطبيق : $APP •</b>
<b>➖ السعر | ₽ $price 💙•</b>

<b>➖ انشاء : $DAY3 •  📫•</b>
<b>➖ انتهاء : $start_time   📭•</b>
<blockquote>➖ عدد المحاولات الشراء : 1</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'🔄 - تغيير الرقم - 🔄','callback_data'=>"$data"]],
[['text'=>'✅ - طلب الكود - ✅','callback_data'=>"Code-$idSend"]],
[['text'=>"$wa",'url'=>"wa.me/$number"]],
[['text'=>"$tg",'url'=>"T.me/$number"]],
[['text'=>'❌ - الغاء الرقم - ❌','callback_data'=>"Ban-$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
👤➖ زبون︙قام بشراء رقم جديد لتطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name*
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المدفوع للرقم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>".✥ تحقق من  العضو  ٭ ️.",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
file_put_contents("data/id/$id/restriction.txt","$ordermy");
$times=time();
$BUYSNUM[number][$ordermy][idSend] = $idSend;
$BUYSNUM[number][$ordermy][phone] = $number;
$BUYSNUM[number][$ordermy][sms][0][code] = null;
$BUYSNUM[number][$ordermy][status] = 1;
$BUYSNUM[number][$ordermy][operator] = $operator;
$BUYSNUM[number][$ordermy][app] = $app;
$BUYSNUM[number][$ordermy][add] = $add;
$BUYSNUM[number][$ordermy][price] = $price;
$BUYSNUM[number][$ordermy][id] = $idnums;
$BUYSNUM[number][$ordermy][site] = $site;
$BUYSNUM[number][$ordermy][zero] = $zero;
$BUYSNUM[number][$ordermy][country] = $country;
$BUYSNUM[number][$ordermy][idnumber] = $idnumber;
$BUYSNUM[number][$ordermy][type] = "direct";
$BUYSNUM[number][$ordermy][finish] = $time;
$BUYSNUM[number][$ordermy][times] = $times;
$BUYSNUM[number][$ordermy]["chat-id"] = $id;
$BUYSNUM[number][$ordermy][DAY] = $DAY;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][account] = $EM;
$ORDERALL[$idSend][order] = $ordermy;
$ORDERALL[$idSend][status] = 1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
}
}
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
*يتم* الان *محاولة* شراء *رقم* 🔁
  *يرجى*الانتظار  6 *ثواني* 💙.
",
'parse_mode'=>"MarkDown",
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب | <s>$randomNumber</s> 🛎•</b>
<b>➖ الدولة | $name •</b>
<b>➖ الرقم | <code>$number</code>  ☎️•</b>
<b>➖ الكود | قيد الانتظار 📩</b>
<b>➖ الحالة | RECEIVED ... 🔎•</b>
<b>➖ التطبيق : $APP •</b>
<b>➖ السعر | ₽ $price 💙•</b>

<b>➖ انشاء : $DAY3 •  📫•</b>
<b>➖ انتهاء : $start_time   📭•</b>
<blockquote>➖ عدد المحاولات الشراء : 1</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'🔄 - تغيير الرقم - 🔄','callback_data'=>"$data"]],
[['text'=>'✅ - طلب الكود - ✅','callback_data'=>"Code-$idSend"]],
[['text'=>"$wa",'url'=>"wa.me/$number"]],
[['text'=>"$tg",'url'=>"T.me/$number"]],
[['text'=>'❌ - الغاء الرقم - ❌','callback_data'=>"Ban-$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
👤➖ زبون︙قام بشراء رقم جديد لتطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name*
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المدفوع للرقم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>".✥ تحقق من  العضو  ٭ ️.",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
file_put_contents("data/id/$id/restriction.txt","$ordermy");
$times=time();
$BUYSNUM[number][$ordermy][idSend] = $idSend;
$BUYSNUM[number][$ordermy][phone] = $number;
$BUYSNUM[number][$ordermy][sms][0][code] = null;
$BUYSNUM[number][$ordermy][status] = 1;
$BUYSNUM[number][$ordermy][operator] = $operator;
$BUYSNUM[number][$ordermy][app] = $app;
$BUYSNUM[number][$ordermy][add] = $add;
$BUYSNUM[number][$ordermy][price] = $price;
$BUYSNUM[number][$ordermy][id] = $idnums;
$BUYSNUM[number][$ordermy][site] = $site;
$BUYSNUM[number][$ordermy][zero] = $zero;
$BUYSNUM[number][$ordermy][country] = $country;
$BUYSNUM[number][$ordermy][idnumber] = $idnumber;
$BUYSNUM[number][$ordermy][type] = "direct";
$BUYSNUM[number][$ordermy][finish] = $time;
$BUYSNUM[number][$ordermy][times] = $times;
$BUYSNUM[number][$ordermy]["chat-id"] = $id;
$BUYSNUM[number][$ordermy][DAY] = $DAY;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][account] = $EM;
$ORDERALL[$idSend][order] = $ordermy;
$ORDERALL[$idSend][status] = 1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
}
}
#=========={Code & AgeCod & ending & Ban}==========#
if($exdata[0] == "Code" or $exdata[0] == "AgeCod" or $exdata[0] == "ending" or $exdata[0] == "Ban"){
$idSend=$exdata[1];
$order=$ORDERALL[$idSend][order];
$account=$ORDERALL[$idSend][account];
$number = $BUYSNUM[number][$order][phone];
$status = $BUYSNUM[number][$order][status];
$operator = $BUYSNUM[number][$order][operator];
$app = $BUYSNUM[number][$order][app];
$add = $BUYSNUM[number][$order][add];
$price = $BUYSNUM[number][$order][price];
$idnum = $BUYSNUM[number][$order][id];
$site = $BUYSNUM[number][$order][site];
$zero = $BUYSNUM[number][$order][zero];
$country = $BUYSNUM[number][$order][country];
$idnumber = $BUYSNUM[number][$order][idnumber];
$finish = $BUYSNUM[number][$order][finish];
$times = $BUYSNUM[number][$order][times];
$status_zero = $buy['number'][$zero];
$name = $_co['country'][$country];
if($add >= 21 and $add <= 25){
$add=21;
}elseif($add >= 26 and $add <= 30){
$add=26;
}
$APP = str_replace(["10","11","12","13","14","21","26","31","36","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي تيليجرام","الشراء الذكي","عروض تيليجرام","عروض واتساب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$server = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);
$APP_S = str_replace(["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],["الواتسأب","التيليجرام","الفيسبوك","الإنستقرام","التويتر","التيك توك","القوقل","الإيمو","فايبر","سناب شات","نيتفلكس","حراج","أي تطبيق لديك"],$server);
if($add == 1){
$back = "Wo";
}elseif($add >= 21 and $add <= 30){
$back = "worldwide";
}elseif($add >= 31 and $add <= 40){
$back = "Buynum";
}else{
$back = "Ms-$add-$country";
}
$cod = "$country$app$operator$add";
if($add == 21 or $add ==30){
$BUYING="Wi-$add";
}else{
$BUYING="Xi-$cod";
}
if($status_zero == null and $exdata[0] == "Code"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>✅ مرحبا بك في قسم شراء رقم ✅</b>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>

<blockquote>يرجي اختيار التطبيق الذي تود شرا رقم له</blockquote>                    
💰 رصيدك : $Balance ₽                    
",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'واتساب || 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣','callback_data'=>"Kn-2"]],
[['text'=>'تيليجرام || 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠','callback_data'=>"Kn-3"]],
[['text'=>'انستغرام || 𝗟𝗡𝗦𝗧𝗔𝗚𝗥𝗔𝗠','callback_data'=>"Kn-5"]],
[['text'=>'فيسبوك || 𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞','callback_data'=>"Kn-4"]],
[['text'=>'تويتر || 𝗧𝗪𝗜𝗧𝗧𝗘𝗥','callback_data'=>"Kn-6"]],
[['text'=>'تيك توك || 𝗧𝗜𝗞𝗧𝗢𝗞','callback_data'=>"Kn-7"],['text'=>'جوجل || 𝗚𝗢𝗢𝗚𝗟𝗘','callback_data'=>"Kn-8"]],
[['text'=>'سناب شات || 𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧','callback_data'=>"Kn-11"],['text'=>'حراج || 𝗛𝗔𝗥𝗔𝗚','callback_data'=>"Kn-13"]],[['text'=>'ايمو || 𝗜𝗠𝗢','callback_data'=>"Kn-9"]],
[['text'=>'🌏 - السيرفر العام - 🌏','callback_data'=>"Kn-14"]],
[['text'=>'🎮 - عروض واتساب - 🎮','callback_data'=>"Wo"],['text'=>'🎮 - عروض تيليجرام - 🎮','callback_data'=>"Cwt-36"]], 
[['text'=>'🔄 - رجوع للخلف - 🔄','callback_data'=>"back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($BUYSNUM[number][$order] == null){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>✅ مرحبا بك في قسم شراء رقم ✅</b>
<b>▱▱▱▱▱▱▱▱▱☠▱▱▱▱▱▱▱▱▱ </b>

<blockquote>يرجي اختيار التطبيق الذي تود شرا رقم له</blockquote>                    
💰 رصيدك : $Balance ₽                    
",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'واتساب || 𝗪𝗛𝗔𝗧𝗦𝗔𝗣𝗣','callback_data'=>"Kn-2"]],
[['text'=>'تيليجرام || 𝗧𝗘𝗟𝗘𝗚𝗥𝗔𝗠','callback_data'=>"Kn-3"]],
[['text'=>'انستغرام || 𝗟𝗡𝗦𝗧𝗔𝗚𝗥𝗔𝗠','callback_data'=>"Kn-5"]],
[['text'=>'فيسبوك || 𝗙𝗔𝗖𝗘𝗕𝗢𝗢𝗞','callback_data'=>"Kn-4"]],
[['text'=>'تويتر || 𝗧𝗪𝗜𝗧𝗧𝗘𝗥','callback_data'=>"Kn-6"]],
[['text'=>'تيك توك || 𝗧𝗜𝗞𝗧𝗢𝗞','callback_data'=>"Kn-7"],['text'=>'جوجل || 𝗚𝗢𝗢𝗚𝗟𝗘','callback_data'=>"Kn-8"]],
[['text'=>'سناب شات || 𝗦𝗡𝗔𝗣 𝗖𝗛𝗔𝗧','callback_data'=>"Kn-11"],['text'=>'حراج || 𝗛𝗔𝗥𝗔𝗚','callback_data'=>"Kn-13"]],[['text'=>'ايمو || 𝗜𝗠𝗢','callback_data'=>"Kn-9"]],
[['text'=>'🌏 - السيرفر العام - 🌏','callback_data'=>"Kn-14"]],
[['text'=>'🎮 - عروض واتساب - 🎮','callback_data'=>"Wo"],['text'=>'🎮 - عروض تيليجرام - 🎮','callback_data'=>"Cwt-36"]], 
[['text'=>'🔄 - رجوع للخلف - 🔄','callback_data'=>"back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($account != $EM){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"- هذة العملية ليست بحسابك المستخدم ❌",
'show_alert'=>true,
]);
exit;
}elseif($status == -1){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 تم إلغاء الرقم بنجاح ...
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيرفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"✥ عودة ↩ ٭. 🔜",'callback_data'=>"$back"]],
[['text'=>'- الصفحة الرئيسية 🔙','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($status == -2 and $exdata[0] != "ending"){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 تم إلغاء الرقم بنجاح ... 
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيرفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"✥ عودة ↩ ٭. 🔜",'callback_data'=>"$back"]],
[['text'=>'- الصفحة الرئيسية 🔙','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($status == 3){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 تم إلغاء الرقم بنجاح ...
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيرفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"✥ عودة ↩ ٭. 🔜",'callback_data'=>"$back"]],
[['text'=>'- الصفحة الرئيسية 🔙','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($status != 1 and $exdata[0] == "Code"){
unlink("data/id/$id/step.txt");
exit;
}elseif($status != 2 and $exdata[0] == "AgeCod"){
unlink("data/id/$id/step.txt");
exit;
}elseif($status != 1 and $exdata[0] == "Ban"){
unlink("data/id/$id/step.txt");
exit;
}
}
#=========={Code Site All}==========#
if($exdata[0] == "Code"){
$idSend=$exdata[1];
$order=$ORDERALL[$idSend][order];
$account=$ORDERALL[$idSend][account];
$number = $BUYSNUM[number][$order][phone];
$status = $BUYSNUM[number][$order][status];
$operator = $BUYSNUM[number][$order][operator];
$app = $BUYSNUM[number][$order][app];
$add = $BUYSNUM[number][$order][add];
$price = $BUYSNUM[number][$order][price];
$idnum = $BUYSNUM[number][$order][id];
$site = $BUYSNUM[number][$order][site];
$zero = $BUYSNUM[number][$order][zero];
$country = $BUYSNUM[number][$order][country];
$idnumber = $BUYSNUM[number][$order][idnumber];
$finish = $BUYSNUM[number][$order][finish];
$times = $BUYSNUM[number][$order][times];
$status_zero = $buy['number'][$zero];
$name = $_co['country'][$country];
$BALANCE = $Balance - $price;
if($add >= 21 and $add <= 25){
$add=21;
}elseif($add >= 26 and $add <= 30){
$add=26;
}
$APP = str_replace(["10","11","12","13","14","21","26","31","36","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي تيليجرام","الشراء الذكي","عروض تيليجرام","عروض واتساب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$server = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);
$APP_S = str_replace(["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],["الواتسأب","التيليجرام","الفيسبوك","الإنستقرام","التويتر","التيك توك","القوقل","الإيمو","فايبر","سناب شات","نيتفلكس","حراج","أي تطبيق لديك"],$server);
if($add == 1){
$back = "Wo";
$MS="➡️ قائمة سيرفرات عروض واتساب ✔️";
}elseif($add >= 21 and $add <= 25){
$back = "Buynum";
$MS="➡️ قائمة سيرفرات $APP ✔️";
}elseif($add >= 26 and $add <= 30){
$back = "worldwide";
$MS="➡️ قائمة سيرفرات ال$APP ✔️";
}else{
$back = "Ms-$add-$country";
$MS="➡️ قائمة سيرفرات $APP ✔️";
}
$cod = "$country$app$operator$add";
if($add == 21 or $add ==30){
$BUYING="Wi-$add";
}else{
$BUYING="Xi-$cod";
}
#__________Time
$start_time=time() - $times;
$start_time=$finish - $start_time;
$z=$start_time/60;
$ex1 = explode(".", $z);
$z="0.$ex1[1]";
$z=$z*60;
$ex2 = explode(".", $z);
$n = mb_strlen("$ex2[0]");
if($n > 1){
$start_time="$ex1[0]:$ex2[0]";
}else{
$start_time="$ex1[0]:0$ex2[0]";
}
if($ex1[0] == null){
$k="ثانية";
}else{
$k="دقيقة";
}
#__________Time
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getStatus&site=$site&idnumber=$idnumber&number=$number&app=$app&add=0"),1);
$status = $api[status];
$code = $api[code];
$agen = $api[agen];
$Location = $api[Location];
if($agen == "200"){
$agen = "✥ طلب الكود مرة أخرى ٭.";
}else{
$agen = null;
}
if($price > $Balance or $Balance < $price or $Balance == 0 or $Balance === 0 or $Balance < 0){
unlink("data/id/$id/step.txt");
exit;
}elseif($code != null and $status == 2 and time() - $times < $finish){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s>  🛎•</b>
<b>➖ الدولة : $name •</b>
<b>➖ المنصة :  #$APP 🌐•</b>
<b>➖ الرقم : <code>$number</code> ☎️•</b>
<b>➖ الكود :  <code>$code</code>  💚•</b>
        
<b>➖ السعر : <s>₽ $price</s> 💙•</b>
<b>➖ عدد الرسائل : 1⃣</b>
        🔰
        
<b>📨 رقم الرسالة : 1️⃣ </b>
<b>➕ الاستلام : $DAY3 •  📥•</b>
<b>➕ المرسل : $APP •</b>
<b>➕ كود التفعيل : <code>$code</code> 💚•</b>
➖➖➖➖➖➖
<s>➖ تم خصم $price ₽ من رصيدك•</s>
<pre>➖ المتبقي في رصيدك : ₽ $BALANCE •</pre>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$agen",'callback_data'=>"AgeCod-$idSend-1"]],
[['text'=>'🔙 ✥ عودة ↩ ٭..','callback_data'=>"$back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($code != null and $status == 2 and time() - $times >= $finish){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ رقم الطلب : ~ $code ~ 🛎•
➖ الدولة : $name •
➖ المنصة :  #$APP 🌐•
➖ الرقم : `$number` ☎️•
➖ الكود : `$code` 💚•
        
➖ السعر : ₽ $price 💙•
➖ عدد الرسائل : 1⃣
        🔰
        
📨 رقم الرسالة : 1️⃣ 
➕ الاستلام : $DAY2 •  📥•
➕ المرسل : $APP •
➕ كود التفعيل : `$code` 💚•
➖➖➖➖➖➖
➖ تم خصم $price ₽ من رصيدك•
➖ المتبقي في رصيدك : ₽ $BALANCE •

",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"$back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($code == null and time() - $times < $finish){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"- لم يصل الكود بعد، حاول بعد 10 ثواني 💬",
'show_alert'=>false,
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب | <s>$randomNumber</s> 🛎•</b>
<b>➖ الدولة | $name •</b>
<b>➖ الرقم | <code>$number</code>  ☎️•</b>
<b>➖ الكود | قيد الانتظار 📩</b>
<b>➖ الحالة | RECEIVED ... 🔎•</b>
<b>➖ التطبيق : $APP •</b>
<b>➖ السعر | ₽ $price 💙•</b>

<b>➖ انشاء : $DAY3 •  📫•</b>
<b>➖ انتهاء : $start_time   📭•</b>
<blockquote>➖ عدد المحاولات الشراء : 1</blockquote>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✅ - طلب الكود - ✅','callback_data'=>"Code-$idSend"]],
[['text'=>'❌ - الغاء الرقم - ❌','callback_data'=>"Ban-$idSend"]],
[['text'=>'🔄 - تغيير الرقم - 🔄','callback_data'=>"$BUYING"]],
[['text'=>'🔙 ✥ عودة ↩ ٭..','callback_data'=>"Ban-$idSend"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($code == null and time() - $times >= $finish){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 تم إلغاء الرقم بنجاح ...
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيرفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$MS",'callback_data'=>"$back"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Buynum"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
👤➖ زبون︙قام بشراء رقم جديد لتطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name*
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المدفوع للرقم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>".✥ تحقق من  العضو  ٭ ️.",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
$BUYSNUM[number][$order][status] = -2;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][status] = -2;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}else{
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"💚 لقد تم وصول الكود بنجاح! رصيدك: ₽ $BALANCE 💙",
'show_alert'=>false,
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s>  🛎•</b>
<b>➖ الدولة : $name •</b>
<b>➖ المنصة :  #$APP 🌐•</b>
<b>➖ الرقم : <code>$number</code> ☎️•</b>
<b>➖ الكود :  <code>$code</code>  💚•</b>
        
<b>➖ السعر : <s>₽ $price</s> 💙•</b>
<b>➖ عدد الرسائل : 1⃣</b>
        🔰
        
<b>📨 رقم الرسالة : 1️⃣ </b>
<b>➕ الاستلام : $DAY3 •  📥•</b>
<b>➕ المرسل : $APP •</b>
<b>➕ كود التفعيل : <code>$code</code> 💚•</b>
➖➖➖➖➖➖
<s>➖ تم خصم $price ₽ من رصيدك•</s>
<pre>➖ المتبقي في رصيدك : ₽ $BALANCE •</pre>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$agen",'callback_data'=>"AgeCod-$idSend-1"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"$back"]]
]
])
]);
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
𓆩•|ـــــــ( $namebot )ــــــ|•𓆪 ⬇️
➖✅ 𝐍𝗨𝐌𝐁𝐄𝐑 : `$number`
➖💬 𝐂𝐎𝐃𝐄 : `$code`
[  ➖ 💚  تعليمات لسلامة رقمك   💡  ](http://t.me/$usrch1/14833)
➖ ✅ - إضغط على الكود او الرقم للنسخ. 😌🌸
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
$hnum=substr($number, 0,-4)."••••";
$nid=substr($id, 0,-3)."•••";
bot('SendMessage',[
'chat_id'=>$sim1,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s> 🛎•</b>
<b>➖ الدولة $name •</b>
<b>➖ التفعيل : يدوي 👍🏻•</b>
<b>➖ السيرفر : $APP •</b>
<b>➖ المنصة #$APP_S 🌐•</b>
<b>➖ المالك :</b> <tg-spoiler>$nid</tg-spoiler> 🆔.
<b>➖ السعر : ₽ $price 💙•</b>
<b>➖ انشاء : $DAY2 •  📫•</b>
<b>➖ انتهاء :  $DAY2 • 📭•</b>
<b>➖ الوقت المتبقي : 00:00:00 انتهى ⌛•</b>
<b>➖ الحالة :  تم التفعيل  ✅• </b>
<b>➖ الرقم  : $hnum</b>

<b>📨 رقم الرسالة :  ️1⃣</b>
<b>➕ الاستلام : $DAY2 •  📥•</b>
<b>➕ المرسل :  $APP_S  •</b>
<b>➕ كود التفعيل : $code•</b> <tg-spoiler>$code</tg-spoiler> 🧿 .
➖➖➖➖➖➖
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥✓ طلب رقم من السيرفر  ٭",'url'=>"t.me/$me?start=ID$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$PAY,
'text'=>"
➖ تم وصول كود : ~ $code ~ 🛎•
➖ الدولة : $name •
➖ المنصة :  #$APP 🌐•
➖ الرقم : `$number` ☎️•
➖ الكود : `$code` 💚•
        
➖ السعر : ₽ $price 💙•
➖ عدد الرسائل : 1⃣
        🔰
        
📨 رقم الرسالة : 1️⃣ 
➕ الاستلام : $DAY3 •  📥•
➕ المرسل : $APP •
➕ كود التفعيل : `$code` 💚•
➖➖➖➖➖➖
➖ تم خصم $price ₽ من رصيده•
➖ المتبقي في رصيده : ₽ $BALANCE •
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>".✥ تحقق من  العضو  ٭ ️.",'url'=>"tg://openmessage?user_id=$id"]],
[['text'=>"تسجيل الدخول.",'callback_data'=>"emilead-$EM"]],
[['text'=>'دفع المبلغ.','callback_data'=>"Pay_am-$EM-$price"]]
]
])
]);
$BUYSNUM[number][$order][sms][0][code] = "$code";
$BUYSNUM[number][$order][status] = 2;
$BUYSNUM[number_my] += 1;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][status] = 2;
$ORDERALL[number] +=1;
$ORDERALL[ruble] +=$price;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
$points = file_get_contents("EMILS/$EM/points.txt");
$as = $points - $price;
file_put_contents("EMILS/$EM/points.txt",$as);
unlink("data/id/$id/restriction.txt");
unlink("data/id/$id/step.txt");
}
}
#=========={Code Site All_2}==========#
if($exdata[0] == "AgeCod"){
$idSend = $exdata[1];
$smsnum = $exdata[2];
$allsms = $smsnum+1;
$addnumber = str_replace(["1","2","3","4","5","6","7","8","9"],["1⃣","2⃣","3⃣","4⃣","5⃣","6⃣","7⃣","8⃣","9⃣"],$allsms);
$order=$ORDERALL[$idSend][order];
$account=$ORDERALL[$idSend][account];
$number = $BUYSNUM[number][$order][phone];
$status = $BUYSNUM[number][$order][status];
$operator = $BUYSNUM[number][$order][operator];
$app = $BUYSNUM[number][$order][app];
$add = $BUYSNUM[number][$order][add];
$price = $BUYSNUM[number][$order][price];
$idnum = $BUYSNUM[number][$order][id];
$site = $BUYSNUM[number][$order][site];
$zero = $BUYSNUM[number][$order][zero];
$country = $BUYSNUM[number][$order][country];
$idnumber = $BUYSNUM[number][$order][idnumber];
$finish = $BUYSNUM[number][$order][finish];
$times = $BUYSNUM[number][$order][times];
$status_zero = $buy['number'][$zero];
$name = $_co['country'][$country];
if($add >= 21 and $add <= 25){
$add=21;
}elseif($add >= 26 and $add <= 30){
$add=26;
}
$APP = str_replace(["10","11","12","13","14","21","26","31","36","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي تيليجرام","الشراء الذكي","عروض تيليجرام","عروض واتساب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$server = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);
$APP_S = str_replace(["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],["الواتسأب","التيليجرام","الفيسبوك","الإنستقرام","التويتر","التيك توك","القوقل","الإيمو","فايبر","سناب شات","نيتفلكس","حراج","أي تطبيق لديك"],$server);
if($add == 1){
$back = "Wo";
}elseif($add >= 21 and $add <= 30){
$back = "worldwide";
}elseif($add >= 31 and $add <= 40){
$back = "Buynum";
}else{
$back = "Ms-$add-$country";
}
$cod = "$country$app$operator$add";
if($add == 21 or $add ==30){
$BUYING="Wi-$add";
}else{
$BUYING="Xi-$cod";
}
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getStatus2&site=$site&idnumber=$idnumber&smsnum=$smsnum&allsms=$allsms"),1);
$status = $api[status];
$code = $api[code];
$agen = $api[agen];
$Location = $api[Location];
if($agen == "200"){
$agen = "✥ طلب الكود مرة أخرى ٭.";
}
if($code == null and time() - $times < $finish){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"- لم يصل كود جديد بعد...💙🔄",
'show_alert'=>false,
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($code == null and time() - $times >= $finish){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 تم إلغاء الرقم بنجاح ...
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيرفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$MS",'callback_data'=>"$back"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($BUYSNUM[number][$order][sms][$smsnum] != null and time() - $times >= $finish){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"☑️ - تم إرسال الكود الجديد من قبل...",
'show_alert'=>false,
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s>  🛎•</b>
<b>➖ الدولة : $name •</b>
<b>➖ المنصة :  #$APP 🌐•</b>
<b>➖ الرقم : <code>$number</code> ☎️•</b>
<b>➖ الكود :  <code>$code</code> $addnumber 💚•</b>
        
<b>➖ السعر : <s>₽ $price</s> 💙•</b>
<b>➖ عدد الرسائل : 1⃣</b>
        🔰
        
<b>📨 رقم الرسالة : 1️⃣ </b>
<b>➕ الاستلام : $DAY3 •  📥•</b>
<b>➕ المرسل : $APP •</b>
<b>➕ كود التفعيل : <code>$code</code> 💚•</b>
➖➖➖➖➖➖
<s>➖ تم خصم $price ₽ من رصيدك•</s>
<pre>➖ المتبقي في رصيدك : ₽ $BALANCE •</pre>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"$back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($BUYSNUM[number][$order][sms][$smsnum] != null and time() - $times < $finish){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"☑️ - تم إرسال الكود الجديد من قبل...",
'show_alert'=>false,
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s>  🛎•</b>
<b>➖ الدولة : $name •</b>
<b>➖ المنصة :  #$APP 🌐•</b>
<b>➖ الرقم : <code>$number</code> ☎️•</b>
<b>➖ الكود :  <code>$code</code> $addnumber 💚•</b>
        
<b>➖ السعر : <s>₽ $price</s> 💙•</b>
<b>➖ عدد الرسائل : 1⃣</b>
        🔰
        
<b>📨 رقم الرسالة : 1️⃣ </b>
<b>➕ الاستلام : $DAY3 •  📥•</b>
<b>➕ المرسل : $APP •</b>
<b>➕ كود التفعيل : <code>$code</code> 💚•</b>
➖➖➖➖➖➖
<s>➖ تم خصم $price ₽ من رصيدك•</s>
<pre>➖ المتبقي في رصيدك : ₽ $BALANCE •</pre>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$agen",'callback_data'=>"AgeCod-$idSend-$allsms"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"$back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($code != null and time() - $times >= $finish){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"☑️ - تم وصول الكود الجديد $code",
'show_alert'=>false,
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s>  🛎•</b>
<b>➖ الدولة : $name •</b>
<b>➖ المنصة :  #$APP 🌐•</b>
<b>➖ الرقم : <code>$number</code> ☎️•</b>
<b>➖ الكود :  <code>$code</code>  💚•</b>
        
<b>➖ السعر : <s>₽ $price</s> 💙•</b>
<b>➖ عدد الرسائل : 1⃣</b>
        🔰
        
<b>📨 رقم الرسالة : 1️⃣ </b>
<b>➕ الاستلام : $DAY3 •  📥•</b>
<b>➕ المرسل : $APP •</b>
<b>➕ كود التفعيل : <code>$code</code> 💚•</b>
➖➖➖➖➖➖
<s>➖ تم خصم $price ₽ من رصيدك•</s>
<pre>➖ المتبقي في رصيدك : ₽ $BALANCE •</pre>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"$back"]]
]
])
]);
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
𓆩•|ـــــــ( $namebot )ــــــ|•𓆪 ⬇️
➖✅ 𝐍𝗨𝐌𝐁𝐄𝐑 : `$number`
➖💬 𝐂𝐎𝐃𝐄 : `$code`
[  ➖ 💚  تعليمات لسلامة رقمك   💡  ](http://t.me/$usrch1/14833)
➖ ✅ - إضغط على الكود او الرقم للنسخ. 😌🌸
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
$hnum=substr($number, 0,-4)."••••";
$nid=substr($id, 0,-3)."•••";
bot('SendMessage',[
'chat_id'=>$sim1,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s> 🛎•</b>
<b>➖ الدولة $name •</b>
<b>➖ التفعيل : يدوي 👍🏻•</b>
<b>➖ السيرفر : $APP •</b>
<b>➖ المنصة #$APP_S 🌐•</b>
<b>➖ المالك :</b> <tg-spoiler>$nid</tg-spoiler> 🆔.
<b>➖ السعر : ₽ $price 💙•</b>
<b>➖ انشاء : $DAY2 •  📫•</b>
<b>➖ انتهاء :  $DAY2 • 📭•</b>
<b>➖ الوقت المتبقي : 00:00:00 انتهى ⌛•</b>
<b>➖ الحالة :  تم التفعيل  ✅• </b>
<b>➖ الرقم  : $hnum</b>

<b>📨 رقم الرسالة :  ️1⃣</b>
<b>➕ الاستلام : $DAY2 •  📥•</b>
<b>➕ المرسل :  $APP_S  •</b>
<b>➕ كود التفعيل : $code•</b> <tg-spoiler>$code</tg-spoiler> 🧿 .
➖➖➖➖➖➖
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥✓ طلب رقم من السيرفر  ٭",'url'=>"t.me/$me?start=ID$idSend"]]
]
])
]);
bot('SendMessage',[
'chat_id'=>$PAY,
'text'=>"
☑️ 𝗡𝗨𝗺𝗕𝗘𝗥 : *$number*
💬 𝗖𝗢𝗗𝗘 : *$code* $addnumber
✅ 𝗡𝗮𝗺𝗘 : [$name](tg://id=>user?$id)
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
$BUYSNUM[number][$order][sms][$smsnum][code] = "$code";
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}elseif($code != null and time() - $times < $finish){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"☑️ - تم وصول الكود الجديد $code",
'show_alert'=>false,
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s>  🛎•</b>
<b>➖ الدولة : $name •</b>
<b>➖ المنصة :  #$APP 🌐•</b>
<b>➖ الرقم : <code>$number</code> ☎️•</b>
<b>➖ الكود :  <code>$code</code>  💚•</b>
        
<b>➖ السعر : <s>₽ $price</s> 💙•</b>
<b>➖ عدد الرسائل : 1⃣</b>
        🔰
        
<b>📨 رقم الرسالة : 1️⃣ </b>
<b>➕ الاستلام : $DAY3 •  📥•</b>
<b>➕ المرسل : $APP •</b>
<b>➕ كود التفعيل : <code>$code</code> 💚•</b>
➖➖➖➖➖➖
<s>➖ تم خصم $price ₽ من رصيدك•</s>
<pre>➖ المتبقي في رصيدك : ₽ $BALANCE •</pre>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$agen",'callback_data'=>"AgeCod-$idSend-$allsms"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"$back"]]
]
])
]);
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
𓆩•|ـــــــ( $namebot )ــــــ|•𓆪 ⬇️
➖✅ 𝐍𝗨𝐌𝐁𝐄𝐑 : `$number`
➖💬 𝐂𝐎𝐃𝐄 : `$code` $addnumber
[  ➖ 💚  تعليمات لسلامة رقمك   💡  ](http://t.me/$usrch1/14833)
➖ ✅ - إضغط على الكود او الرقم للنسخ. 😌🌸
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
$hnum=substr($number, 0,-4)."••••";
$nid=substr($id, 0,-3)."•••";
bot('SendMessage',[
'chat_id'=>$sim1,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s> 🛎•</b>
<b>➖ الدولة $name •</b>
<b>➖ التفعيل : يدوي 👍🏻•</b>
<b>➖ السيرفر : $APP •</b>
<b>➖ المنصة #$APP_S 🌐•</b>
<b>➖ المالك :</b> <tg-spoiler>$nid</tg-spoiler> 🆔.
<b>➖ السعر : ₽ $price 💙•</b>
<b>➖ انشاء : $DAY2 •  📫•</b>
<b>➖ انتهاء :  $DAY2 • 📭•</b>
<b>➖ الوقت المتبقي : 00:00:00 انتهى ⌛•</b>
<b>➖ الحالة :  تم التفعيل  ✅• </b>
<b>➖ الرقم  : $hnum</b>

<b>📨 رقم الرسالة :  ️1⃣</b>
<b>➕ الاستلام : $DAY2 •  📥•</b>
<b>➕ المرسل :  $APP_S  •</b>
<b>➕ كود التفعيل : $code•</b> <tg-spoiler>$code</tg-spoiler> 🧿 .
➖➖➖➖➖➖
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥✓ طلب رقم من السيرفر  ٭",'url'=>"t.me/$me?start=ID$idSend"]]
]
])
]);
bot('SendMessage',[
'chat_id'=>$PAY,
'text'=>"
☑️ 𝗡𝗨𝗺𝗕𝗘𝗥 : *$number*
💬 𝗖𝗢𝗗𝗘 : *$code* $addnumber
✅ 𝗡𝗮𝗺𝗘 : [$name](tg://id=>user?$id)
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
$BUYSNUM[number][$order][sms][$smsnum][code] = "$code";
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}
}
#=========={End Site All}==========#
#By : JIMI  @T0qiR
if($exdata[0] == "ending"){
$idSend=$exdata[1];
$order=$ORDERALL[$idSend][order];
$account=$ORDERALL[$idSend][account];
$number = $BUYSNUM[number][$order][phone];
$status = $BUYSNUM[number][$order][status];
$operator = $BUYSNUM[number][$order][operator];
$app = $BUYSNUM[number][$order][app];
$add = $BUYSNUM[number][$order][add];
$price = $BUYSNUM[number][$order][price];
$idnum = $BUYSNUM[number][$order][id];
$site = $BUYSNUM[number][$order][site];
$zero = $BUYSNUM[number][$order][zero];
$country = $BUYSNUM[number][$order][country];
$idnumber = $BUYSNUM[number][$order][idnumber];
$finish = $BUYSNUM[number][$order][finish];
$times = $BUYSNUM[number][$order][times];
$status_zero = $buy['number'][$zero];
$name = $_co['country'][$country];
if($add >= 21 and $add <= 25){
$add=21;
}elseif($add >= 26 and $add <= 30){
$add=26;
}
$APP = str_replace(["10","11","12","13","14","21","26","31","36","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي تيليجرام","الشراء الذكي","عروض تيليجرام","عروض واتساب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$server = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);
$APP_S = str_replace(["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],["الواتسأب","التيليجرام","الفيسبوك","الإنستقرام","التويتر","التيك توك","القوقل","الإيمو","فايبر","سناب شات","نيتفلكس","حراج","أي تطبيق لديك"],$server);
if($add == 1){
$back = "Wo";
}elseif($add >= 21 and $add <= 30){
$back = "worldwide";
}elseif($add >= 31 and $add <= 40){
$back = "Buynum";
}else{
$back = "Ms-$add-$country";
}
$cod = "$country$app$operator$add";
if($add == 21 or $add ==30){
$BUYING="Wi-$add";
}else{
$BUYING="Xi-$cod";
}
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=setStatus&site=$site&country=$country&app=$app&idnumber=$idnumber&number=$number"),1);
$status = $api[status];
$Location = $api[Location];
if(time() - $times >= $finish){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 تم إلغاء الرقم بنجاح ...
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيرفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$MS",'callback_data'=>"$back"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Buynum"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 تم إلغاء الرقم بنجاح ...
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيرفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$MS",'callback_data'=>"$back"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Buynum"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
👤➖ ︙تم انتهاء الوقت رقم الزبون الخاص ب تطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name* •
➖️ رمز الدولة : *$country* 🧿•
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المدفوع للرقم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
$BUYSNUM[number][$order][status] = 3;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][status] = 3;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
}
}
#=========={Ban Site All}==========#
#By : JIMI  @T0qiR
if($exdata[0] == "Ban"){
$idSend=$exdata[1];
$order=$ORDERALL[$idSend][order];
$account=$ORDERALL[$idSend][account];
$number = $BUYSNUM[number][$order][phone];
$status = $BUYSNUM[number][$order][status];
$operator = $BUYSNUM[number][$order][operator];
$app = $BUYSNUM[number][$order][app];
$add = $BUYSNUM[number][$order][add];
$price = $BUYSNUM[number][$order][price];
$idnum = $BUYSNUM[number][$order][id];
$site = $BUYSNUM[number][$order][site];
$zero = $BUYSNUM[number][$order][zero];
$country = $BUYSNUM[number][$order][country];
$idnumber = $BUYSNUM[number][$order][idnumber];
$finish = $BUYSNUM[number][$order][finish];
$times = $BUYSNUM[number][$order][times];
$status_zero = $buy['number'][$zero];
$name = $_co['country'][$country];
$BALANCE = $Balance - $price;
if($add >= 21 and $add <= 25){
$add=21;
}elseif($add >= 26 and $add <= 30){
$add=26;
}
$APP = str_replace(["10","11","12","13","14","21","26","31","36","1","2","3","4","5","6","7","8","9"],["فايبر","سناب شات","نيتفلكس","حراج","السيرفر العام","عشوائي واتسأب","عشوائي تيليجرام","الشراء الذكي","عروض تيليجرام","عروض واتساب","واتسأب","تيليجرام","فيسبوك","إنستقرام","تويتر","تيك توك","قوقل","ايمو"],$add);
$server = str_replace(["wa","tg","fb","ig","tw","lf","go","im","vi","fu","nf","au","ot"],["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],$app);
$APP_S = str_replace(["Whatsapp","Telegram","Facebook","Instagram","Twitter","TikTok","Google","Imo","Viber","Snapchat","Netflix","Haraj","Other"],["الواتسأب","التيليجرام","الفيسبوك","الإنستقرام","التويتر","التيك توك","القوقل","الإيمو","فايبر","سناب شات","نيتفلكس","حراج","أي تطبيق لديك"],$server);
if($add == 1){
$back = "Wo";
}elseif($add >= 21 and $add <= 30){
$back = "worldwide";
}elseif($add >= 31 and $add <= 40){
$back = "Buynum";
}else{
$back = "Ms-$add-$country";
}
$cod = "$country$app$operator$add";
if($add == 21 or $add ==30){
$BUYING="Wi-$add";
}else{
$BUYING="Xi-$cod";
}
$api=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=getStatus&site=$site&app=$app&idnumber=$idnumber&number=$number"),1);
$status = $api[status];
$code = $api[code];
$agen = $api[agen];
$Location = $api[Location];
$api2=json_decode(file_get_contents("https://".$_SERVER['SERVER_NAME']."/$bot/api-sites.php?action=addBlack&site=$site&app=$app&idnumber=$idnumber&number=$number"),1);
$status2 = $api2[status];
$Location2 = $api2[Location];
if($agen == "200"){
$agen = "✥ طلب الكود مرة أخرى ٭.";
}else{
$agen = null;
}
if($code != null and $status == 2 and time() - $times < $finish){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s>  🛎•</b>
<b>➖ الدولة : $name •</b>
<b>➖ المنصة :  #$APP 🌐•</b>
<b>➖ الرقم : <code>$number</code> ☎️•</b>
<b>➖ الكود :  <code>$code</code>  💚•</b>
        
<b>➖ السعر : <s>₽ $price</s> 💙•</b>
<b>➖ عدد الرسائل : 1⃣</b>
        🔰
        
<b>📨 رقم الرسالة : 1️⃣ </b>
<b>➕ الاستلام : $DAY3 •  📥•</b>
<b>➕ المرسل : $APP •</b>
<b>➕ كود التفعيل : <code>$code</code> 💚•</b>
➖➖➖➖➖➖
<s>➖ تم خصم $price ₽ من رصيدك•</s>
<pre>➖ المتبقي في رصيدك : ₽ $BALANCE •</pre>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$agen",'callback_data'=>"AgeCod-$idSend-1"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"$back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($code != null and $status == 2 and time() - $times >= $finish){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖ رقم الطلب : ~ $code ~ 🛎•
➖ الدولة : $name •
➖ المنصة :  #$APP 🌐•
➖ الرقم : `$number` ☎️•
➖ الكود : `$code` 💚•
        
➖ السعر : ₽ $price 💙•
➖ عدد الرسائل : 1⃣
        🔰
        
📨 رقم الرسالة : 1️⃣ 
➕ الاستلام : $DAY2 •  📥•
➕ المرسل : $APP •
➕ كود التفعيل : `$code` 💚•
➖➖➖➖➖➖
➖ تم خصم $price ₽ من رصيدك•
➖ المتبقي في رصيدك : ₽ $BALANCE •
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيرفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"$back"]]
]
])
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($code != null and time() - $times < $finish){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"لا يمكنك إلغاء الرقم بعد وصول الكود🙄.",
'show_alert'=>true,
]);
exit;
}elseif($status2 != "200"){
bot('answercallbackquery',[
'callback_query_id'=>$update->callback_query->id,
'text'=>'🙂 - لايمكنك الغاء الرقم لانك قمت بشراء اكثر من رقم ولم تلغيهم💚',
'show_alert'=>true
]);
unlink("data/id/$id/step.txt");
exit;
}elseif($code == null and $status == 2){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
➖️ الرقم : *$number* 🤎
➖️ الدولة : *$name* 💜
➖️ التطبيق : *$APP* 🤍
➖️ الحالة : *••• Canceled* 💙

➖️ تم إلغاء *الرقم بنجاح* 💚
📆 - *$DAY2* 
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$MS",'callback_data'=>"$back"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Buynum"]]
]
])
]);
$BUYSNUM[number][$order][status] = -1;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][status] = -1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/restriction.txt");
unlink("data/id/$id/step.txt");
exit;
}elseif($code != null){
bot('answercallbackquery',[
'callback_query_id' => $update->callback_query->id,
'text'=>"💚 لقد تم وصول الكود بنجاح! رصيدك: ₽ $BALANCE 💙",
'show_alert'=>false,
]);
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s>  🛎•</b>
<b>➖ الدولة : $name •</b>
<b>➖ المنصة :  #$APP 🌐•</b>
<b>➖ الرقم : <code>$number</code> ☎️•</b>
<b>➖ الكود :  <code>$code</code>  💚•</b>
        
<b>➖ السعر : <s>₽ $price</s> 💙•</b>
<b>➖ عدد الرسائل : 1⃣</b>
        🔰
        
<b>📨 رقم الرسالة : 1️⃣ </b>
<b>➕ الاستلام : $DAY3 •  📥•</b>
<b>➕ المرسل : $APP •</b>
<b>➕ كود التفعيل : <code>$code</code> 💚•</b>
➖➖➖➖➖➖
<s>➖ تم خصم $price ₽ من رصيدك•</s>
<pre>➖ المتبقي في رصيدك : ₽ $BALANCE •</pre>
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$agen",'callback_data'=>"AgeCod-$idSend-1"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"$back"]]
]
])
]);
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
𓆩•|ـــــــ( $namebot )ــــــ|•𓆪 ⬇️
➖✅ 𝐍𝗨𝐌𝐁𝐄𝐑 : `$number`
➖💬 𝐂𝐎𝐃𝐄 : `$code`
[  ➖ 💚  تعليمات لسلامة رقمك   💡  ](http://t.me/$usrch1/14833)
➖ ✅ - إضغط على الكود او الرقم للنسخ. 😌🌸
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
]);
$hnum=substr($number, 0,-4)."••••";
$nid=substr($id, 0,-3)."•••";
bot('SendMessage',[
'chat_id'=>$sim1,
'text'=>"
<b>➖ رقم الطلب : <s>$code</s> 🛎•</b>
<b>➖ الدولة $name •</b>
<b>➖ التفعيل : يدوي 👍🏻•</b>
<b>➖ السيرفر : $APP •</b>
<b>➖ المنصة #$APP_S 🌐•</b>
<b>➖ المالك :</b> <tg-spoiler>$nid</tg-spoiler> 🆔.
<b>➖ السعر : ₽ $price 💙•</b>
<b>➖ انشاء : $DAY2 •  📫•</b>
<b>➖ انتهاء :  $DAY2 • 📭•</b>
<b>➖ الوقت المتبقي : 00:00:00 انتهى ⌛•</b>
<b>➖ الحالة :  تم التفعيل  ✅• </b>
<b>➖ الرقم  : $hnum</b>

<b>📨 رقم الرسالة :  ️1⃣</b>
<b>➕ الاستلام : $DAY2 •  📥•</b>
<b>➕ المرسل :  $APP_S  •</b>
<b>➕ كود التفعيل : $code•</b> <tg-spoiler>$code</tg-spoiler> 🧿 .
",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥✓ طلب رقم من السيرفر  ٭",'url'=>"t.me/$me?start=ID$idSend"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$PAY,
'text'=>"
👤➖ ︙تم وصول كود الزبون $code
لـ↩️ تطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name* •
➖️ رمز الدولة : *$country* 🧿•
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$BALANCE*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المخصوم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
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
$BUYSNUM[number][$order][sms][0][code] = "$code";
$BUYSNUM[number][$order][status] = 2;
$BUYSNUM[number_my] += 1;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][status] = 2;
$ORDERALL[number] +=1;
$ORDERALL[ruble] +=$price;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
$points = file_get_contents("EMILS/$EM/points.txt");
$as = $points - $price;
file_put_contents("EMILS/$EM/points.txt",$as);
unlink("data/id/$id/restriction.txt");
unlink("data/id/$id/step.txt");
exit;
}elseif(time() - $times >= $finish){
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 تم إلغاء الرقم بنجاح ...
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيرفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$MS",'callback_data'=>"$Ms"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Buynum"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
👤➖ ︙تم انتهاء الوقت رقم الزبون الخاص ب تطبيق $APP 💙•

➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name* •
➖️ رمز الدولة : *$country* 🧿•
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️

➖️ المبلغ المدفوع للرقم︙*₽ $price*  📮•

        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"☑️ - رابط العضو ↖️",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
$BUYSNUM[number][$order][status] = -2;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][status] = -2;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/step.txt");
exit;
}else{
bot('EditMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>"
💚 تم إلغاء الرقم بنجاح ...
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"✥ شراء من نفس السيرفر ↻ ٭",'callback_data'=>"$BUYING"]],
[['text'=>"$MS",'callback_data'=>"$back"]],
[['text'=>'✥ عودة ↩ ٭..','callback_data'=>"Buynum"]]
]
])
]);
bot('sendMessage',[
'chat_id'=>$ess,
'text'=>"
➖ قام الزبون بالغاء الرقم الذي اشتراه ⛔️•
➖️ تطبيق $APP 🌐
➖️ الرقم︙ *$number* ☎️ •
➖️ الدولة︙ *$name* •
➖️ رمز الدولة : *$country* 🧿•
➖ إسم الشخص︙ $first 📝.•
➖ ايدي الشخص︙ $EM 🆔️.•

➖ رصيدة︙ *$Balance*  💸 •
➖️ الموقع︙ *$Location & $operator* 🌐
➖️➖️💙➖️➖️
➖️ تم ارجاع قيمه الرقم الى حسابه $price روبل 📤
        🔰
➖➖➖➖➖➖
",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>".✥ تحقق من  العضو  ٭ ️.",'url'=>"tg://openmessage?user_id=$id"]]
]
])
]);
$BUYSNUM[number][$order][status] = -1;
file_put_contents("EMILS/$EM/number.json", json_encode($BUYSNUM,64|128|256));
$ORDERALL[$idSend][status] = -1;
file_put_contents('BUY/Orderall.json', json_encode($ORDERALL,64|128|256));
unlink("data/id/$id/restriction.txt");
unlink("data/id/$id/step.txt");
}
}
#By : JIMI @ZISlZ @T0qiR