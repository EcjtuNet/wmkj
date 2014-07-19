$(document).ready(function(){

	var text = $("message").text();
	if(text === 'wrong'){
		$(".sign").addClass("warning");
		$(".sign .help-block").text("用户名或密码不正确。");
	}
});
$(".name input").blur(function(){
	var uname = $(".name input").val();
	if(isnull(uname)){
		$(".name").addClass("warning");
		$(".name .help-block").text("用户名不得为空");
		$(".name input").focus();
		return false;
	}else{
		$.post("./bglogin.php",{"apply":"apply","usename":uname},function(result){
			console.log(result.status);
			if(result.status !== "true"){
				$(".name").addClass("error");
				$(".name .help-block").text("该用户名已经被注册。");
				$(".name input").focus();
				return false;
			}else{
				$(".name").removeClass("error").removeClass("warning");
				$(".name .help-block").text("");
			}
		},"json")
	}
})
$(".password input").blur(function(){
	var passW = $(".password input").val();
	if(isnull(passW)){
		$(".password").addClass("warning");
		$(".password .help-block").text("密码不得为空");
		$(".password input").focus();
		return false;
	}else if(passw.length < 6){
		$(".password").addClass("warning");
		$(".password .help-block").text("密码长度不得低于6位");
		$(".password input").focus();
		return false;
	}else $(".password").removeClass("warning");
})
$(".department input").blur(function(){
	var passW = $(".department input").val();
	if(isnull(passW)){
		$(".department").addClass("warning");
		$(".department .help-block").text("部门不得为空");
		$(".department input").focus();
		return false;
	}else $(".department").removeClass("warning")
})

function isnull(q){
	if(q == "") return true;
	else return false;
}

$(".access").change(function(){
	var ace = $(".access").val();
	if(ace === "0")
})

function chenck(){
	$(".department input").blur();
	$(".password input").blur();
	$(".name input").blur();
	return false;
}
