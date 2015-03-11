(function(){
	"use strict"
	$.post( "./index.php", {content:"list"}, function(data){
		$.each(data,function(id,obj){
			var html = "<li><a data-id="+obj.id+" data-url="+obj.url+">"+obj.title+"</li>";
			$(".showlist ul").append(html);
		});
	},  "json");
	var musicGround = $("#music-ground"),
		player = $("#player")[0],
		playerControl = $("#player-controls-play"),
		mask = true,
		isClose = true;
	playerControl.click(function(){
		console.log(this);
		if(mask){
			musicGround.addClass("spinner");
			playerControl.removeClass("icon-play3").addClass("icon-pause2");
			mask = false;
			console.log(player.play());
		}else{
			musicGround.removeClass("spinner");
			playerControl.removeClass("icon-pause2").addClass("icon-play3");
			mask = true;
			player.pause();
		}
	});
	$(".list").click(function(){
		console.log("hehe");
		$('.showlist').show().animate({width:"150px",height:"100px"},"slow");
		isClose = false;
	});
	$(document).click(function (event) { isClose&&$('.showlist').slideUp('slow');isClose = true; });  
	$('.showlist').click(function (event) { isClose&&$(this).fadeOut(1000);isClose=true; });  
})();