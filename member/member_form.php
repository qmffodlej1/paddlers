<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../css/common.css" rel="stylesheet" type="text/css" media="all">
<link href="../css/member.css" rel="stylesheet" type="text/css" media="all">
    <style>
        /* 가운데 정렬을 위한 스타일링 */
        .center-table {
            margin-left: 720px;
        }
    </style>
<script>
    function check_id()
    {
        window.open("check_id.php?id=" + document.member_form.id.value,
            "IDcheck",
            "left=200,top=200,width=200,height=60,scrollbars=no,resizable=yes");
    }

    function check_nick()
    {
        window.open("check_nick.php?nick=" + document.member_form.nick.value,
            "NICKcheck",
            "left=200,top=200,width=200,height=60,scrollbars=no,resizable=yes");
    } 

    function check_input()
    {
        if (!document.member_form.id.value) {
            window.alert("아이디를 입력하세요");
            document.member_form.id.focus();
            return false;
        }
        if (!document.member_form.pass.value) {
            window.alert("비밀번호를 입력하세요");    
            document.member_form.pass.focus();
            return false;
        }
        if (!document.member_form.pass_confirm.value) {
            window.alert("비밀번호확인을 입력하세요");    
            document.member_form.pass_confirm.focus();
            return false;
        }
        if (!document.member_form.name.value) {
            window.alert("이름을 입력하세요");    
            document.member_form.name.focus();
            return false;
        }
        if (!document.member_form.nick.value) {
            window.alert("닉네임을 입력하세요");    
            document.member_form.nick.focus();
            return false;
        }
        if (!document.member_form.hp2.value || !document.member_form.hp3.value ){
            window.alert("휴대폰 번호를 입력하세요");    
            document.member_form.nick.focus();
            return false;
        }
        if (document.member_form.pass.value != document.member_form.pass_confirm.value) {
            window.alert("비밀번호가 일치하지 않습니다.\n다시 입력해주세요.");    
            document.member_form.pass.focus();
            document.member_form.pass.select();
            return false;
        }
        return true;
    }

    function reset_form() {
        document.member_form.id.value = "";
        document.member_form.pass.value = "";
        document.member_form.pass_confirm.value = "";
        document.member_form.name.value = "";
        document.member_form.nick.value = "";
        document.member_form.hp1.value = "010";
        document.member_form.hp2.value = "";
        document.member_form.hp3.value = "";
        document.member_form.email1.value = "";
        document.member_form.email2.value = "";
        document.member_form.id.focus();
        
        return;
    }
    function idcheckbar(value) {
    const bar = document.querySelector('.bar_id');
    const minLength = 8;
    if ((value.length >= minLength) && (/\d/.test(value) && /[a-zA-Z]/.test(value))) {
        bar.style.background = '#98FB98' ;
    } 
    else if (value.length >= minLength) {
        bar.style.background = 'linear-gradient(to right, #f3f588 50%, transparent 50%)';
    } 
    else {
    bar.style.background = 'transparent';
    }
    }
    function passcheckbar(value) {
    const bar = document.querySelector('.bar_pass');
    const minLength = 10;
    let strength = 0; // 비밀번호 강도

    // 조건에 따라 강도를 측정하여 strength 변수에 반영
    if (/\d/.test(value)) {
        strength++;
    }
    if (/[a-z]/.test(value)) {
        strength++;
    }
    if (/[A-Z]/.test(value)) {
        strength++;
    }
    if (/\W/.test(value)) {
        strength++;
    }
    if (value.length >= minLength) {
        strength++;
    }

    // 강도에 따라 바(bar)의 배경색 변경
    if (strength === 1) {
        bar.style.background = 'linear-gradient(to right, #F08080 20%, transparent 20%)';
    } else if (strength === 2) {
        bar.style.background = 'linear-gradient(to right, #ffca66 40%, transparent 40%)';
    } else if (strength === 3) {
        bar.style.background = 'linear-gradient(to right, #f3f588 60%, transparent 60%)';
    } else if (strength === 4) {
        bar.style.background = 'linear-gradient(to right, #f3f588 80%, transparent 80%)';
    } else if (strength === 5) {
        bar.style.background = '#98FB98';
    } else {
        bar.style.background = 'transparent';
    }
}

    function complexity_id() {
    const bar = document.getElementById('idcheckbar');
    const computedStyle = window.getComputedStyle(bar);
    if (computedStyle.backgroundColor !== 'rgb(152, 251, 152)') {
        window.alert('숫자와 영문을 포함하여 8자리 이상 만들어주세요!!');
        document.member_form.id.focus();
    }
    }
    function complexity_pass() {
    const bar = document.getElementById('passcheckbar');
    const computedStyle = window.getComputedStyle(bar);
    if (computedStyle.backgroundColor !== 'rgb(152, 251, 152)') {
        window.alert('숫자와 영어 대/소 특수문자을 포함하여 10자리 이상 만들어주세요.');
        document.member_form.pass.focus();
        }
    }

</script>
<div id="container">
    <body>
        <header class="header">
		<a href="../index.php"> <!-- 로고를 클릭하면 현재 페이지(index.php)로 연결되도록 설정 -->
                <img src="../img/logo2.png" class="logo" alt="로고">
            </a>
            <?php
            if (empty($userid)) {
                echo '<div id="top_login"><a href="../login/login_form.php">로그인</a> | <a href="../member/member_form.php">회원가입</a></div>';
            } else {
                echo '<div id="top_login">' . $usernick . ' (level: ' . $userlevel . ') | <a href="../login/logout.php">로그아웃</a> | <a href="../login/member_form_modify.php">정보수정</a></div>';
            }
            ?>
        </header>
		<div id="body">
        <div id="wrap">
            <div id="menu">
                <?php include "../lib/top_menu2.php"; ?>
            </div> <!-- end of menu -->
        </div> <!-- end of wrap -->
	<div id="col_2">
        <form  name="member_form" method="post" action="insert.php"> 
		<div id="title">
			<h1>회 원 가 입</h1>
		</div>
<div class="center-table">
    <table>
        <tr>
            <td>아이디</td>
            <td>
                <input type="text" name="id" class="input_12_1" oninput="idcheckbar(this.value)">
            </td>
            <td>
                <div class="bar_id" id="idcheckbar"></div>
            </tb>
            <td id="id3_id">
                ↳ 영문과 숫자를 조합하여 8자 이상 입력하세요. 
            </td>
        </tr>
        <tr>
            <td>비밀번호</td>
            <td>complexity_id() 
                <input type="password" name="pass" class="input_12_2" oninput="passcheckbar(this.value)">
            </td>
            <td>
            <div class="bar_pass" id="passcheckbar"></div>
            </td>
            <td id="id3">
            ↳영문 대/소문자, 특수 문자, 숫자를 입력하여 10글자 이상을 입력하세요.
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td id="id3">
            비밀번호 확인
        </td>
            <td>
                <input type="password" class="input_12" name="pass_confirm">
            </td>
        </tr>
        <tr>
            <td>
            이름
            </td>
            <td>
                <input type="text" name="name" class="input_12">
            </td>
        </tr>
        <tr>
            <td>
                별명
            </td>
            <td>
                <input type="text" name="nick" class="input_12">
            </td>
        </tr>
        <tr>
            <td>
            휴대전화
            </td>
            <td class="phone-inputt">
                <select class="hp input_2" name="hp1"> 
                    <option value='010'>010</option>
                    <option value='011'>011</option>
                    <option value='016'>016</option>
                    <option value='017'>017</option>
                    <option value='018'>018</option>
                    <option value='019'>019</option>
                </select><b> -</b><input type="text" class="hp inputt123" name="hp2"><b>-</b><input type="text" class="hp inputt123" name="hp3">
        </td>
        </table>
    </table>
			<div class="clear"></div>
            <div id="buttong" class="center-bottom">
            <a href="#">
    <input type="button" class="button_1" onclick="if(check_input() && complexity_id() && complexity_pass() ) document.member_form.submit();" value="회원가입">
            </a>
		    <a href="#"><input type="button" class="button_1" onclick="reset_form()" value="리셋"></a>
	    </form>
        </div>
	</div> <!-- end of col2 -->
    </div> <!-- end of content -->
</div> <!-- end of wrap -->

</body>
</html>
