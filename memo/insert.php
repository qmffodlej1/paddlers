<? session_start(); 
if (isset($_SESSION['userid'])) 
{
        $userid = $_SESSION['userid'];
        $username = $_SESSION['username'];
        $usernick = $_SESSION['usernick'];
        $userlevel = $_SESSION['userlevel'];
}
$table = "memo";
$content = $_POST['content'];
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
	// $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
	// $nick = htmlspecialchars($nick, ENT_QUOTES, 'UTF-8');
	$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
	
	$data = $pdo->prepare('SELECT * FROM member WHERE id = (:id);');
	$data->bindParam(':id', $userid, PDO::PARAM_STR);
	$data->execute();
	$row = $data->fetch(PDO::FETCH_ASSOC);
	$name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
	$nick = htmlspecialchars($row['nick'], ENT_QUOTES, 'UTF-8');
	
	$data_insert =$pdo->prepare('insert into memo (id, name, nick, content, regist_day) valus (:userid), (:name),(:nick),(:content),(:regist_day);');
	$data_insert->bindParam(':userid',$userid,PDO::PARAM_STR);
	$data_insert->bindParam(':name',$name,PDO::PARAM_STR);
	$data_insert->bindParam(':nick',$nick,PDO::PARAM_STR);
	$data_insert->bindParam(':content',$content,PDO::PARAM_STR);
	$data_insert->bindParam(':regist_day',$regist_day,PDO::PARAM_STR);
	echo "<script>
			location.href = 'memo.php';
		  </script>";
	?>
  