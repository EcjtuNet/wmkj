<?php
	if(!isset($_GET['wechatID'])){
		header("Location: http://wx.ecjtu.net/wmkj/mp3/index.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>日小新</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <style type="text/css">
      .audiojs{ position: fixed; bottom: 0px; left: 0px; }
      hr{ margin:10px 0; }
      ol{padding: 0;}
      ol li{
        padding-left: 10px;
        list-style: none;
      }
      ol li a{ font-size:16px; margin-left:20px; width:50%;height:40px; line-height:40px; display:inline-block;}
      .thumbs{ float:right; margin-right:20px;}
    </style>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    <script src="./js/audiojs/audio.js"></script>
    <script>
      $(function() { 
        // Setup the player to autoplay the next track
        var a = audiojs.createAll({
          trackEnded: function() {
            var next = $('ol li.playing').next();
            if (!next.length) next = $('ol li').first();
            next.addClass('playing').siblings().removeClass('playing');
            audio.load($('a', next).attr('data-src'));
            audio.play();
          }
        });
        
        // Load in the first track
        var audio = a[0];
            first = $('ol a').attr('data-src');
        $('ol li').first().addClass('playing');
        audio.load(first);

        // Load in a track on click
        $('ol li a').click(function(e) {
          e.preventDefault();
          $(this).addClass('playing').siblings().removeClass('playing');
          audio.load($(this).attr('data-src'));
          audio.play();
        });
        // Keyboard shortcuts
        $(document).keydown(function(e) {
          var unicode = e.charCode ? e.charCode : e.keyCode;
             // right arrow
          if (unicode == 39) {
            var next = $('li.playing').next();
            if (!next.length) next = $('ol li').first();
            next.click();
            // back arrow
          } else if (unicode == 37) {
            var prev = $('li.playing').prev();
            if (!prev.length) prev = $('ol li').last();
            prev.click();
            // spacebar
          } else if (unicode == 32) {
            audio.playPause();
          }
        });
        $('.thumbs').click(function(e){
        	var id = $(this).children(".ID").text();
		var thumbs = $(this);
        	console.log(id);
        	$.post("./index.php",{"wechatID":id},function(data){
        		//console.log(data.error == "exists");
        		if(data.error != "exists"){
        			thumbs.children(".badge").text(data.zan);
        			thumbs.children(".ups").text("已投");
        		}else{
        			//console.log(thumbs.children(".ups").text());
        			thumbs.children(".ups").text("已投");
        		}
        	},"json")
        });
        
      });
    </script>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
      <div class="">
        <div class="page-header">
          <h1>日新网 <small>华交微电台</small></h1>
        </div>
        <div class="player">
          <audio class="player" preload></audio>
        </div>
        <ol>
        <?php foreach($resback as $value){ ?>
          <li>
            <img src="<?php echo $value['picurl']?>" width="40px"; height="40px;" alt="..." class="img-circle"/>
            <a href="javascript:;" data-src="<?php echo $value['arcurl']?>"><?php echo $value['title']?></a>
            <button type="button" class="btn btn-info thumbs">
                <span class="glyphicon glyphicon-thumbs-up"></span> <span class="ups">投一票</span><span class="badge"><?php echo $value['zan']?></span>
                <span class="ID" style="display:none"><?php echo $wechat.$value['ID']?></span>
            </button>
          </li>
          <hr/>
         <?php } ?>
        </ol>
        </div>
      </div>
    </div>
  </body>
</html>
