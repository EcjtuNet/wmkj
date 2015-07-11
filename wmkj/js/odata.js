    $(document).ready(function(){
    	if($("message").val()){
    		alert($("message").val());
    	}
        $(".rx_show").toggle(function() {
            $(".rx_hidden").show(100);
        }, function(){
            $(".rx_hidden").hide(100);
        });
        
        $(".rx_show_art").toggle(function() {
            $(".rx_hidden_art").show(100);
        }, function(){
            $(".rx_hidden_art").hide(100);
        });
        
        $(".controls textarea").blur(function () {
            if($(this).val().length > 255) {
                $(this).next().show();
            } else {
                $(this).next().hide();
            }
        });
        var inputEmail = $("#inputEmail")
        ,   inputDef   = '输入空格可确认添加标签';
        inputEmail.keydown(function (e) {
            if(e.which === 32){
                if($(this).next().children().length >= 5){
                    alert("关键字标签最多只能有5个哦，因为佳诚说不能多于5个");
                    return false;
                }
                var val  = $(this).val().replace(/( )/g, '');
                if(val !== ''){
                    var cont = "<span>" + val + "<i class='icon-remove'></i><\/span>";
                    $(this).next().append(cont);
                    $(this).blur().val(inputDef);
                }
            }
        });
        inputEmail.focus(function () {
            if($(this).val() === inputDef)
                $(this).val("");
        });
        inputEmail.blur(function () {
            if($(this).val() === '') $(this).val(inputDef);
        });
        $("#inputEmail + span span").live('click', function () {
            $(this).remove();
        });
        
        var inputTitle = $('#inputTitle')
        ,   inputDes   = $('#inputDes')
        ,   inputArcUrl= $('#inputArcUrl')
        ,   inputPicUrl= $('#inputPicUrl')
        ,   inputTheme = $('#inputTheme');
        
        $(".rx_hidden_art input").blur(function () {
            if($(this).val() === '') $(this).next().show();
            else $(this).next().hide();
        });
        $("#modal-540283").click(function (){
            var items = inputEmail.next().children()
            ,   kws   = items.length
            ,   cont  = $(".controls textarea").val();
            if(!(kws&&cont)){
                alert("怎么不填内容呢？看看关键字有没有确认吧");
                return false;
            }
        });
        $("#modal-540284").click(function () {
            var title = inputTitle.val()
            ,   des   = inputDes.val()
            ,   arc   = inputArcUrl.val()
            ,   pic   = inputPicUrl.val();
            if(!(title && des && arc && pic)){
                alert("看看什么没填吧");
                return false;
            }            
        });
        
        $("#confirm").click(function () {
            var name = $("li.active a").text();
            if (name === "关键词管理") kwsSub(name);
            else contSub(name);
        });
        
        function writeDom(obj, arr) {
            var tbody= obj
            ,   num  = 1
            ,   l    = arr.length
            ,   newCont = '';
            
            if(parseInt(tbody.children().first().children().first().text()) !== '') {
                num = tbody.children().first().children().first().text()-1+2;
            }
            $.each(arr, function(index,item){
                if (index === l-1) {
                    newCont += '<td><span class="label badge-important">' + item + '<\/span><\/td>';
                    return false;
                }
                newCont += '<td>' + item + '<\/td>';
            });
            newCont = '<tr><td>' + num + '<\/td>' + newCont + '<\/tr>';
            $("div.active table tbody").prepend(newCont);
        }
        
        function res(data, arr, dlt) {
            var d = $.parseJSON(data);
            if(d['status'] == "success") {
            	//arr.pop();
            	//arr.pop();
                arr.push(dlt);
                //console.log(arr);
                writeDom($("div.active table tbody"), arr);
            } else {
                alert('error');
            }
        }
        function resc(data, arr, dlt) {
            var d = $.parseJSON(data);
            if(d['status'] == "success") {
            	theme = themeChange(arr[4]);
            	arr.splice(2,3,theme);
                arr.push(dlt);
                arr.push(0);
                console.log(arr);
                writeDom($("div.active table tbody"), arr);
            } else {
                alert('error');
            }
        }
        
        function kwsSub(name) {
        	//console.log(inputEmail.next().children());
            var items = inputEmail.next().children()
            ,   kws   = ''
            ,   cont  = $(".controls textarea").val()
            ,   rank  = '10'
            ,   dlt   = '删除关键字';
            //console.log(items);
            $.each(items, function(index, item) {
                kws += '(' + item.textContent + '),';
            });
            if(!(kws&&cont)){
                alert("怎么不填内容呢？看看关键字有没有确认吧");
                return false;
            }
            arr = [kws, cont, rank];
            $.post("Submit.php", {name : name, content : arr}, function (data) {
                res(data, arr, dlt);
            });
        }           
        function contSub(name) {
            var title = inputTitle.val()
            ,   des   = inputDes.val()
            ,   arc   = inputArcUrl.val()
            ,   pic   = inputPicUrl.val()
            ,   theme = inputTheme.val()
            ,   writer= $("#usrName").text().slice(4)
            ,   dlt   = "删除"
            ,   arr   = [];
            if(!(title && des && arc && pic)){
                alert("看看什么没填吧");
                return false;
            }
            arr = [title,des, arc, pic, theme, writer];
            $.post("Submit.php", {name : name, content : arr}, function (data) {
                resc(data, arr, dlt);
            });
        }
        function themeChange(theme) {
        	switch(theme){
        		case '1': return "小新快递"  ; break;
        		case '2': return "微科技"    ; break;
        		case '3': return "孔目湖讲坛"; break;
        		case '4': return "微音乐"    ; break;
        		case '5': return "微杂志"    ; break;
        		case '6': return "微通话"    ; break;
        		case '7': return "微动漫"    ; break;
        		case '8': return "微电台"    ; break; 
        	}
        }
    });
