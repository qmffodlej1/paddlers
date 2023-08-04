<?
session_start();
if (isset($_SESSION['userid'])) {
	$userid = $_SESSION['userid'];
	$username = $_SESSION['username'];
	$usernick = $_SESSION['usernick'];
	$userlevel = $_SESSION['userlevel'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head> 
<meta charset="utf-8">
<link href="../css/common.css" rel="stylesheet" type="text/css" media="all">
<link href="../css/board4.css" rel="stylesheet" type="text/css" media="all">
</head>
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
    <body>
        <div id="chlwhd2">
    <h1>Encoding Tool</h1>
    <form method="post" action="">
        <label for="order"><h2>Enter a string:<h2></label>
        <div id="chlwhd">
        <input type="text" class="input_2" name="order" id="order" required>
        <input type="submit" value="sumbmit" class="button0">
        </div>
        </div>
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $target = $_POST["order"];
        $target = htmlspecialchars($target, ENT_QUOTES, 'UTF-8');
        
        // Avoid shell_exec and use PHP functions
        // Base64 Encoding
        $base64_encoded = base64_encode($target);
        echo "<h2>Base64 Encoding:</h2><pre>{$base64_encoded}</pre>";
        
        // MD5 Hash
        $md5_hash = md5($target);
        echo "<h2>MD5 Hash:</h2><pre>{$md5_hash}</pre>";
        
        // SHA-256 Hash
        $sha256_hash = hash('sha256', $target);
        echo "<h2>SHA-256 Hash:</h2><pre>{$sha256_hash}</pre>";
        
        // SHA-512 Hash
        $sha512_hash = hash('sha512', $target);
        echo "<h2>SHA-512 Hash:</h2><pre>{$sha512_hash}</pre>";
        
        // URI Encoding
        $uri_encoded = urlencode($target);
        echo "<h2>URI Encoding:</h2><pre>{$uri_encoded}</pre>";
    }
    ?></pre>

   
</body>

</html>


