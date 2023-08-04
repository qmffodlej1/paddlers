<? session_start(); 
if (isset($_SESSION['userid'])) 
{
        $userid = $_SESSION['userid'];
        $username = $_SESSION['username'];
        $usernick = $_SESSION['usernick'];
        $userlevel = $_SESSION['userlevel'];
}
$table = "memo";
$content = isset($_POST['content']) ? htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8') : '';
if (isset($_GET['mode'])) {
$mode = $_GET['mode'];
$find = isset($_POST['find']) ? $_POST['find'] : '';
$search = isset($_POST['search']) ? $_POST['search'] : '';
}?>
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

	// 데이터베이스에 삽입하기 전에 데이터 처리
	$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
	$nick = htmlspecialchars($nick, ENT_QUOTES, 'UTF-8');
	$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
	
	$sql = "select * from member where id='$userid'";
	$result = $connect->query($sql);
	
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
	$nick = htmlspecialchars($row['nick'], ENT_QUOTES, 'UTF-8');
	
	$sql = "insert into memo (id, name, nick, content, regist_day) ";
	$sql .= "values('$userid', '$name', '$nick', '$content', '$regist_day')";
	
	$result = $connect->query($sql);
	$connect->close();                // DB 연결 끊기
	
	echo "<script>
			location.href = 'memo.php';
		  </script>";
	?>
  