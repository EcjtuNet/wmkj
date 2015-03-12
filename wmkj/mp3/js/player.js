(function(){
	"use strict"
	var musicGround = $("#music-ground"),
		player = new Audio(),
		playerControl = $("#player-controls-play"),
		mask = true,
		isClose = true,
		isTalk = false,
		isRed = false,
		title = $(".title"),
		author = $(".author"),
		list = null;
		console.log(title);
	$.post( "./index.php", {content:"list"}, function(data){
		player.src = data[0].url;
		title.text (data[0].title);
		author.text (data[0].pulishMan);
		//playerControl.click();
		$.each(data,function(id,obj){
			var html = "<li><a data-id="+obj.ID+" data-url="+obj.url+" data-author="+data.pulishMan+">"+obj.title+"</a></li>";
			$(".showlist ul").append(html);
		});
	},  "json");
	player.addEventListener("ended",function(){
		playerControl.click();
	});
	playerControl.click(function(){
		console.log(this);
		if(mask){
			musicGround.addClass("spinner");
			playerControl.removeClass("icon-play3").addClass("icon-pause2");
			mask = false;
			player.play();
		}else{
			musicGround.removeClass("spinner");
			playerControl.removeClass("icon-pause2").addClass("icon-play3");
			mask = true;
			player.pause();
		}
	});
	console.log(list);
	$(".showlist ul li").click(function(){
		console.log("heheaa");
		player.src = $(this).attr("data-url");
		playerControl.click();
	});
	$(".like").click(function(){
		if(!isRed){
			$(this).css("color","red");
			isRed = true;
		}else{
			$(this).css("color","white");
			isRed = false;
		}
	});
	$(".talk").click(function(){
		if(!isTalk){
			$(".talk-box").show();
			isTalk = true;
		}else{
			$(".talk-box").hide();
			isTalk = false;
		}
	});
	$(".list").click(function(){
		if(isClose){
			console.log("hehe");
			$('.showlist').show().animate({width:"150px",height:"100px"},"slow");
			isClose = false;
		}else{
			$(".showlist").fadeOut(1000);
			isClose=true; 
		}
	});
})();