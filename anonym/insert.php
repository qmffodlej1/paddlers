<? 
	session_start(); 
	$table = "anonym";
	$mode = @$_GET['mode'];
	$num = @$_GET['num'];
	$page = @$_GET['page'];

	$html_ok = @$_POST['html_ok'];
	$subject = @$_POST['subject'];
	$content = @$_POST['content'];


if (isset($_SESSION['userid'])) 
{
		$userid = $_SESSION['userid'];
		$username = $_SESSION['username'];
		$usernick = $_SESSION['usernick'];
		$userlevel = $_SESSION['userlevel'];

}
?>


<meta charset="utf-8">
<?
	if(!$userid) {
		echo("
		<script>
	     window.alert('로그인 후 이용해 주세요.')
	     history.go(-1)
	   </script>
		");
		exit;
	}

	$regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장

	
	include "../lib/dbconn.php";       // dconn.php 파일을 불러옴

 	if ($mode=="modify")
	{
		$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
		$subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
		$sql = "update $table set subject='$subject', content='$content' where num='$num'";
		$connect->query($sql);  // $sql 에 저장된 명령 실행
	}
	else
	{
		if ($html_ok=="y")
		{
			$is_html = "y";
		}
		else
		{
			$is_html = "";
			$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
			$subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
		}

		$sql = "insert into $table (id, name, nick, subject, content, regist_day, hit, is_html)";
		$sql .= "values('$userid', '$username', '$usernick', '$subject', '$content', '$regist_day', 0, '$is_html')";
		$connect->query($sql);  // $sql 에 저장된 명령 실행
	}
	$connect->close();                // DB 연결 끊기

	echo "
	   <script>
	    location.href = 'list.php?table=$table&page=$page';
	   </script>
	";
?>

  
