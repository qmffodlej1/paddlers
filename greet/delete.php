<?
   session_start();

   include "../lib/dbconn.php";
	$num = $_GET['num'];

	$sql = "select * from greet where num=$num";
	$result = $connect->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$item_id = $row['id'];
	if (isset($_SESSION['userid'])) 
	{
			$userid = $_SESSION['userid'];
			$username = $_SESSION['username'];
			$usernick = $_SESSION['usernick'];
			$userlevel = $_SESSION['userlevel'];
	}

	if(!$userid != $item_id) {
		echo("
		<script>
	     window.alert('글쓴이 정보가 일치하지 안습니다.')
	     history.go(-1)
	   </script>
		");
		exit;
	}

   $sql = "delete from greet where num = $num";
	$result = $connect->query($sql);
	$result = $connect->query($sql); // 옛날 코드라서 바꿔줘야한다

   echo "
	   <script>
	    location.href = 'list.php';
	   </script>
	";
?>

