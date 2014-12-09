/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'fileindex.php'
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );
      // Load existing files:
    $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });

});
$(function(){
	//console.log($(".title"));
	$(".title input").blur(function(){
		var title = $(".title input").val();
		//console.log(title);
		if(isnull(title)||title.length>50||title == "标题(50个字符内)"){
			$(".title").addClass("warning");
			$(".title .help-block").text("标题为空或标题长度多于50个字符");
			$(".title input").focus();
		}else{
			$(".title").removeClass("warning");
			$(".title .help-block").text("");
		}
	});
	$(".desc input").blur(function(){
		var desc = $(".desc input").val();
		if(isnull(desc)||desc.length>255||desc == "描述(255个字符内)"){
			$(".desc").addClass("warning");
			$(".desc .help-block").text("描述为空或描述长度多于255个字符");
			$(".desc input").focus();
		}else{
			$(".desc").removeClass("warning");
			$(".desc .help-block").text("");
		}
	});
	$(".submit").click(function(){
		$(".title input").blur();
		$(".desc input").blur();
		var url;
		var picurl;	
		var title = $(".title input").val();
		var desc  = $(".desc input").val();
		$("input[name='audio']:checked").each(function(){
			console.log($(this).val());
			var type = $(this).val().split(".");
			console.log(type);
			if(type[3] == "mp3"){	
				url = $(this).val();
			}else{
				picurl = $(this).val();
			}
		});
	//	console.log("hehe"+url);
		if(isnull(url)){
			$(".file").addClass("warning");
			$(".file .help-block").text("请选择你要使用的音频文件");
		}else{
			$(".file").removeClass("warning");
			$(".file .help-block").text("");
			var content = {"submit":"submit","title":title,"desc":desc,"url":url,"picurl":picurl,"colum":"8"};
			$.post("Submit_gbz.php",content,function(data){
				console.log(data);
				if(data.status=="200"){
					$(".submit-help").text("发布成功，");
					$(".submit-help-a").text("点我查看");
				}
			},"json");
		}
	});	
	
	function isnull(q){
	if(q == ""||q === undefined) return true;
	else return false;
}
});
