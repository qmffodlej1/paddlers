<? session_start();
if (isset($_SESSION['userid'])) 
{
		$userid = $_SESSION['userid'];
		$username = $_SESSION['username'];
		$usernick = $_SESSION['usernick'];
		$userlevel = $_SESSION['userlevel'];
}
$table = @$_GET['table'];
$mode = @$_GET['mode'];
$num = @$_GET['num'];
$page = @$_GET['page'];
$html_ok = @$_POST['html_ok'];
$subject = @$_POST['subject'];
$content = @$_POST['content'];

if (isset($_GET['mode'])) {
$mode = $_GET['mode'];
$find = $_POST['find'];
$search = $_POST['search'];

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

	if(!$subject) {
		echo("
	   <script>
	     window.alert('제목을 입력하세요.')
	     history.go(-1)
	   </script>
		");
	 exit;
	}

	if(!$content) {
		echo("
	   <script>
	     window.alert('내용을 입력하세요.')
	     history.go(-1)
	   </script>
		");
	 exit;
	}

	$regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장
	include "../lib/dbconn.php";       // dconn.php 파일을 불러옴

	if ($mode=="modify")
	{
		$sql = "select * from greet where num=$num";
		$result = $connect->query($sql);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$item_id = $row['id'];
		
		if(!$userid != $item_id) {
			echo("
			<script>
	    	 window.alert('글쓴이 정보가 일치하지 안습니다.')
	    	 history.go(-2)
	   		</script>
			");
			exit;
		}
		$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
		$subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
		$sql = "update greet set subject='$subject', content='$content' where num=$num";
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

		$sql = "insert into greet (id, name, nick, subject, content, regist_day, hit, is_html) ";
		$sql .= "values('$userid', '$username', '$usernick', '$subject', '$content', '$regist_day', 0, '$is_html')";
	}
	$result = $connect->query($sql);
	$connect->close();                // DB 연결 끊기

	echo "
	   <script>
	    location.href = 'list.php?page=$page';
	   </script>
	";
?>

  
