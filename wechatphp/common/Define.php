<?php 
require_once dirname(__FILE__).'/ErrorCode.php';
define('ROOT_PATH', dirname(__FILE__) . '/../');
define('DEFAULT_CHARSET', 'utf-8');
define('COMPONENT_VERSION', '1.0');
define('COMPONENT_NAME', 'wmkj');
//关闭NOTICE错误日志
//error_reporting(E_ALL ^ E_NOTICE);


define('WX_API_URL', "https://api.weixin.qq.com/cgi-bin/");
define('WX_API_APPID', "");
define('WX_API_APPSECRET', "");

define("WEIXIN_TOKEN", "EcjtuNet");
define("HINT_NOT_IMPLEMEMT", "later back~!");

define('NO_BD', "您是第一次使用小新附加查询功能,请先绑定你的学号,输入 xh你的学号，例如：xh20122010010000\n绑定成功后,你可以实现查询成绩等功能。");
define('NO_STYLE_XH', "绑定格式出错，正确的格式如下：xh你的学号，例如：xh20122010010000\n");

define('HINT_TPL', "<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <Content><![CDATA[%s]]></Content>
  <FuncFlag>0</FuncFlag>
</xml>
");

$GLOBALS['rule'] = array(
	'xh'=>array(
		'bit'=>'14',
		'rule'=>'1',
		),
	'sf'=>array(
		'bit' => '4',
		'rule' => '0',
		),
	'cd'=>array(
		'bit' => '6',
		'rule' => '1',
		),
	);

$GLOBALS['DB'] = array(
	'DB' => array(
		'HOST' => 'localhost',
		'DBNAME' => 'wx',
		'USER' => 'root',
		'PASSWD' => 'EcjtuNet7#214_51',
		'PORT' => 3306 
	),
	'CLASS' => array(
		'HOST' => 'localhost',
		'DBNAME' => 'class_ecjtu_net',
		'USER' => 'root',
		'PASSWD' => 'EcjtuNet7#214_51',
		'PORT' => 3306 
	),
	'SCORE' => array(
		'HOST' => 'localhost',
		'DBNAME' => 'scoreview',
		'USER' => 'root',
		'PASSWD' => 'EcjtuNet7#214_51',
		'PORT' => 3306
	),
	'WX' => array(
		'HOST' => 'localhost',
		'DBNAME' => 'weixin',
		'USER' => 'root',
		'PASSWD' => 'EcjtuNet7#214_51',
		'PORT' => 3306
	)
);

$GLOBALS['APPID_APPSECRET'] = array(
	'XX' => array(
		'appId' => '',
		'appSecret'=> ''
	)
);

define('SUCC_TPL_head', "<xml>
 <ToUserName><![CDATA[%s]]></ToUserName>
 <FromUserName><![CDATA[%s]]></FromUserName>
 <CreateTime>%s</CreateTime>
 <MsgType><![CDATA[%s]]></MsgType>
 <ArticleCount><![CDATA[%d]]></ArticleCount>
 <Articles>");
 define('SUCC_TPL_body',"
 <item>
 <Title><![CDATA[%s]]></Title>
 <Description><![CDATA[%s]]></Description>
 <PicUrl><![CDATA[%s]]></PicUrl>
 <Url><![CDATA[%s]]></Url>
 </item>");
 define('SUCC_TPL_footer',"</Articles>
 </xml>");

define('URL_HEADER', 'http://www.yourdomain.com/image/');
define('FF_URL_HEADER', 'http://www.yourdomain.com/image/');

define('MUSIC_TPL',"<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>
</xml>");
?>


