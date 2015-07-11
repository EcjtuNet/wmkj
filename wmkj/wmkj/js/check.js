$(document).ready(function(){

	var text = $("message").text();
	if(text === 'wrong'){
		$(".sign").addClass("warning");
		$(".sign .help-block").text("用户名或密码不正确。");
	}
	else if(text === 'success'){
		$(".success").removeClass("hidden");
		$(".submit").addClass("hidden");
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
		if($(".password input").val()==""){
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
	}
})
$(".password input").blur(function(){
	var passW = $(".password input").val();
	if(isnull(passW)){
		$(".password").addClass("warning");
		$(".password .help-block").text("密码不得为空");
		$(".password input").focus();
		return false;
	}else{ 
		if(passW.length < 6){
			$(".password").addClass("warning");
			$(".password .help-block").text("密码长度不得低于6位");
			$(".password input").focus();
			return false;
		}else{
		$(".password").removeClass("warning");
		$(".password .help-block").text("");
		}
	}
})
$(".department input").blur(function(){
	var passW = $(".department input").val();
	if(isnull(passW)){
		$(".department").addClass("warning");
		$(".department .help-block").text("部门不得为空");
		$(".department input").focus();
		return false;
	}else{
		$(".department").removeClass("warning");
		$(".department .help-block").text("");
	}
})

function isnull(q){
	if(q == "") return true;
	else return false;
}

$(".access").change(function(){
	var ace = $(".access").val();
	if(ace === "0"){
		$(".ace").addClass("warning");
		$(".ace .help-block").text("权限不得为空");
		return false;
	}else{ 
		if(ace == "5"|| ace == "7"){
			$(".ace").removeClass("warning");
			$(".ace .help-block").text("");
			$(".cloum").removeClass("hidden");
			return false;
		}else{
			$(".ace").removeClass("warning");
			$(".ace .help-block").text("");
			$(".cloum").addClass("hidden");
			return false;
		}
	}
	$(".ace").removeClass("warning");
	$(".ace .help-block").text("");
	$(".cloum").addClass("hidden");
})
$(".cloum").change(function(){
	var ace = $(".access").val();
	var cloum = $(".cloum").val();
	if(cloum == "0"&& ace != "10"){
		$(".clo").addClass("warning");
		$(".clo .help-block").text("栏目不得为空");
		return false;
	}
	$(".clo").removeClass("warning");
	$(".clo .help-block").text("");
})
function chenck(){
	$(".access").change();
	$(".cloum")
	$(".department input").blur();
	$(".password input").blur();
	$(".name input").blur();
}
