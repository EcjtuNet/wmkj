<?php
	if(!isset($content)){
		header("Location: http://wx.ecjtu.net/wmkj/mp3/index.php");
	}
?>
<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="utf-8" />
		<title>华交微电台</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="Responsive &amp; Touch-Friendly Audio Player" />
		<meta name="author" content="Osvaldas Valutis, www.osvaldas.info" />
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<script type="text/javascript" src="./js/lode.js"></script>
		<link rel="stylesheet" href="./css/style.css" />
		<link rel="shortcut icon" href="../image/logo_icon.png" type="image/png"> 
		
		<script type="text/javascript">
			
		</script>
		<script>
		
			/*
				VIEWPORT BUG FIX
				iOS viewport scaling bug fix, by @mathias, @cheeaun and @jdalton
			*/
			(function(doc){var addEvent='addEventListener',type='gesturestart',qsa='querySelectorAll',scales=[1,1],meta=qsa in doc?doc[qsa]('meta[name=viewport]'):[];function fix(){meta.content='width=device-width,minimum-scale='+scales[0]+',maximum-scale='+scales[1];doc.removeEventListener(type,fix,true);}if((meta=meta[meta.length-1])&&addEvent in doc){fix();scales=[.25,1.6];doc[addEvent](type,fix,true);}}(document));
		</script>
	</head>
	<body>
		
		<div id="wrapper" class="radius">
			<h1>华交微电台</h1>
			<ul id="list">
				<li class="radius" data-id="<?php echo $content['0']['ID'] ?>" ><?php echo $content['0']['title']?></li>
				<li class="radius" data-id="<?php echo $content['1']['ID'] ?>" ><?php echo $content['1']['title']?></li>
				<li class="radius" data-id="<?php echo $content['2']['ID'] ?>" ><?php echo $content['2']['title']?></li>
				<li class="radius" data-id="<?php echo $content['3']['ID'] ?>" ><?php echo $content['3']['title']?></li>
			</ul>
			<audio preload="auto" controls>
				<source class="source" src="<?php echo $content['0']['url'] ?>">
			</audio>
			<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
			<script src="./js/mp3.js"></script>
			<script>
				$( function() { $( 'audio' ).audioPlayer(); } );
				$("#list li:first").css("background","#FF7F00");
				//console.log("ni mei xiao hei ");
				/*$("#list li").click(function(){
				//console.log("hello word");
					$("#list li").css("background","#F1F1F1");
					$(this).css("background","#FF7F00");
					var ID = $(this).attr("data-id");
					//console.log(ID);
				});*/
			
			</script>
			<!-- 多说评论框 start -->
	<div class="ds-thread" data-thread-key="<?php echo $content['0']['ID'] ?>" data-title="<?php echo $content['0']['title'] ?>" data-url="http://wx.ecjtu.net/wmkj/mp3/index.php"></div>
<!-- 多说评论框 end -->
<!-- 多说公共JS代码 start (一个网页只需插入一次) -->
<script type="text/javascript">
var duoshuoQuery = {short_name:"ecjtuwx"};
	(function() {
		var ds = document.createElement('script');
		ds.type = 'text/javascript';ds.async = true;
		ds.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//static.duoshuo.com/embed.js';
		ds.charset = 'UTF-8';
		(document.getElementsByTagName('head')[0] 
		 || document.getElementsByTagName('body')[0]).appendChild(ds);
	})();
	</script>
<!-- 多说公共JS代码 end -->

		</div>

	</body>
</html>
