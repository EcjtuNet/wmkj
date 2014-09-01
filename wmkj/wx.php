<?php
	if($_SESSION['access'] == NULL){
		 header("Location: http://wx.ecjtu.net/wmkj/index.php"); 
		 exit(0);
	}
?>
<!DOCTYPE html>
<html>
<head>
    <title>ForPP</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="http://cdn.bootcss.com/bootstrap/2.3.1/css/bootstrap.min.css" rel="stylesheet">
<link href="http://cdn.bootcss.com/bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://cdn.bootcss.com/bootstrap/3.1.1/css/bootstrap-theme.css" rel="stylesheet">
<link href="./css/style.css" rel="stylesheet">
<link rel="shortcut icon" href="./image/logo_icon.png" type="image/png"> 
<style type="text/css" rel="stylesheet">
 
</style>
</head>
<body>
    <div class="container-fluid">
    <div class="row-fluid">
        <div class="span10 offset1 bgfff">
            <h1>
                <image src="./image/logo.png" alt=" ^.^ "/><span>日小新内容发布系统</span>
            </h1>
            <div class="loginName"><span id="usrName">用户名:<?php echo $usename?></span><?php if($_SESSION['access']['access'] == "10") echo '<span><a href="./login_new.php">  添加用户</a></span>'?><span><a href="bglogin.php?quit=out">退出</a></span><span>实时绑定人数：<?php echo $bdcount['count(wxh)']?></span><span class="label"><a href="#publish">内容发布</a></span></div>

            <hr/>
            <div class="tabbable" id="tabs-957518">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#panel-669300" data-toggle="tab">关键词管理</a>
                    </li>
                    <li>
                        <a href="#panel-317624" data-toggle="tab">栏目内容管理</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="panel-669300">
                        <table class="table table-hover table-bordered table-striped"> 
                <thead>
                    <tr>
                        <th class="span1">
                            编号
                        </th>
                        <th class="span2">
                            关键词
                        </th>
                        <th class= "span4">
                            返回内容或加载模式
                        </th>
                        <th class="span1">
                            权值
                        </th>
                        <th class="span2">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
               <?php foreach($keyW as $value){ ?>
                    <tr>
                        <td>
                            <?php echo $value['ID']; ?>
                        </td>
                        <td>
                            <?php echo $value['keyW']?>
                        </td>
                        <td>
                            <?php echo $value['content']?>
                        </td>
                        <td>
                            <?php echo $value['weight']?>
                        </td>
                        <td>
                             <span class="label badge-important" ><a href="./bglogin.php?dele=<?php echo $value['ID'] ?>&type=keyW">删除关键字</a></span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button class="btn btn-block rx_show" type="button">发布</button>
            <div class="rx_hidden">
                    <form class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="inputEmail">关键字</label>
                            <div class="controls">
                                <input id="inputEmail" type="text" value="输入空格可确认添加标签"/>
                                <span>
                                </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputPassword">内容</label>
                            <div class="controls">
                                <textarea name="content"></textarea>
                                <span class="err_txt">输入文字长度不得超过255！</span>
                            </div>
                        </div>
                         <a id="modal-540283" href="#modal-container-540283" role="button" class="btn rx_submit" data-toggle="modal">发布</a>
                         <input type="reset" class="btn" value="重置"/>
                    </form>
                </div>
                    </div>
                    <div class="tab-pane" id="panel-317624">
                            <table class="table table-hover table-bordered table-striped">  
                <thead>
                    <tr>
                        <th class="span1">
                            编号
                        </th>
                        <th class="span2">
                            标题
                        </th>
                        <th class="span1">
                            描述
                        </th>
                          <th class="span1">
                            栏目
                        </th>
                        <th class="span1">
                            发布人
                        </th>
                        <th class="span1">
                            操作
                        </th>
                         <th class="span1">
                            点击
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($content as $value){?>
                    <tr>
                        <td>
                            <?=$value['ID']?>
                        </td>
                        <td>
                            <?=$value['title']?>
                        </td>
                        <td>
                            <?=$value['describle']?>
                        </td>
                        <td>
                            <?php 
                                switch($value['colum'])
                                {
                                    case '1': $value['colum'] = "小新快递";break;
                                    case '2': $value['colum'] = "微科技";break;
                                    case '3': $value['colum'] = "孔目胡讲坛";break;
                                    case '4': $value['colum'] = "微音乐";break;
                                    case '5': $value['colum'] = "微杂志";break;
                                    case '6': $value['colum'] = "微童话";break;
                                    case '7': $value['colum'] = "微动漫";break;
                                    case '8': $value['colum'] = "微电台";break;
                                }
                                echo $value['colum'];
                            ?>
                        </td>
                        <td>
                            <?php echo $value['pulishMan']?>
                        </td>
                        <td>
                             <span class="label badge-important" ><a href="./bglogin.php?dele=<?php echo $value['ID'] ?>&type=content">删除</a></span>
                        </td>
                        <td>
                             <?php echo "hahah" ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <button class="btn btn-block rx_show_art" type="button" >发布</button>
            <div class="rx_hidden_art">
                    <form class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="inputTitle">标题</label>
                            <div class="controls">
                                <input id="inputTitle" type="text" />
                                <span class="err">为什么不填呢</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputDes">描述</label>
                            <div class="controls">
                                <input id="inputDes" type="text" />
                                <span class="err">为什么不填呢</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputArcUrl">文章连接</label>
                            <div class="controls">
                                <input id="inputArcUrl" type="url" />
                                <span class="err">为什么不填呢</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputPicUrl">图片连接</label>
                            <div class="controls">
                                <input id="inputPicUrl" type="url" />
                                <span class="err">为什么不填呢</span>
                            </div>
                        </div>
                         <div class="control-group">
                            <span style="margin-left:117px;">栏目名</span>
                            <select name="theme" id="inputTheme" style="margin-left:18px;padding:1px; background:transparent; width:220px; font-size: 16px; height:30px;-webkit-appearance: none;">
                                <option value="1">小新快递</option>
                                <option value="2">微科技</option>
                                <option value="3">孔目胡讲坛</option>
                                <option value="4">微音乐</option>
                                <option value="5">微杂志</option>
                                <option value="6">微童话</option>
                                <option value="7">博约课堂</option>
                                <option value="8">微电台</option>
                            </select>
                        </div>
                        <a id="modal-540284" href="#modal-container-540283" role="button" class="btn rx_submit" data-toggle="modal">发布</a>
                         <input type="reset" class="btn" value="重置"/>
                    </form>
                </div>
                    </div>
                </div>
            </div>
            
            
            
            <div id="modal-container-540283" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">
                        Attention
                    </h3>
                </div>
                <div class="modal-body">
                    <p>
                        确认提交。
                    </p>
                </div>
                <div class="modal-footer">
                     <button id="confirm" class="btn" data-dismiss="modal" aria-hidden="true">确认</button> <button data-dismiss="modal" class="btn btn-primary">取消</button>
                </div>
            </div>
        </div>
    </div>
    <div class="footer"><a name="publish"></a><p class="support">技术支持<a href="http://www.ecjtu.net"><img src="./image/logo_icon.png" alt="^_^">日新网技术研发中心</a></p></div>
</div>
<script src="http://cdn.bootcss.com/jquery/1.7.2/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="./js/odata.js" language="javascript" type="text/javascript"></script>
</body>
</html>
