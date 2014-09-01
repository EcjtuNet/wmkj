<?php
//echo "jdfklajs";
require_once dirname(dirname(__FILE__)) .'/common/Define.php';
require_once dirname(dirname(__FILE__)) .'/common/GlobalFunctions.php';
//require_once dirname(dirname(__FILE__)) .'/class/menuStub.php';

interface_log(DEBUG, 0, "***start menu**");
//下拉菜单范例。 第一范例是简单的点击切换式的范例，第二个则是日小新现在在用的。

/*
$menuData = array(
	'button'=>array(
		array(
			'type' => 'click',
			'name' => 'append模式',
			'key' => 'APPEND'
		),
		array(
			'type' => 'click',
			'name' => '正常模式',
			'key' => 'NORMAL'
		),
		
	)
);
*/
$menuData = array(
	'button'=>array(
		array(
			'name' => '花椒助手',
			'sub_button' => array(
				array(
					'type' => 'click',
					'name' => '小新快递',
					'key'  => 'KUAIDI'
				),
				array(
					'type' => 'view',
					'name' => '跳蚤市场',
					'url'  => 'http://bbs.ecjtu.net/forum-28-1.html'
				),
				array(
					'type' => 'click',
					'name' => '一卡通',
					'key'  => 'YKT'
				),
				array(
					'type' => 'click',
					'name' => '查课表',
					'key'  => 'KEBIAO'
				),
				array(
					'type' => 'click',
					'name' => '查成绩',
					'key'  => 'CHENGJI'
				),
			)
		),
		array(
			'name' => "微生活",
			'sub_button' => array(
				array(
					'type' => 'view',
					'name' => '日新微店',
					'url'  => 'http://wd.koudai.com/s/163327932'
				),
				array(
					'type' => 'view',
					'name' => '交大微电台',
					'url' => 'http://wx.ecjtu.net/wmkj/mp3/'
				),
				array(
					'type' => 'view',
					'name' => '微电台社区',
					'url' => 'http://wx.wsq.qq.com/236192005'
				),
				array(
					'type' => 'click',
					'name' => '博约课堂',
					'key' => 'DONGMAN'
				),
				array(
					'type' => 'click',
					'name' => '孔目湖讲坛',
					'key' => 'JIANGTAN'
				),
			)
		),
		array(
			'name' => '日新社区',
			'type' => 'view',
			'url'=>'http://wx.wsq.qq.com/198297806 ',
		),
	)
);

echo json_encode($menuData);
