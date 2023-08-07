<?
   session_start();
   $table = $_GET['table'];
   $num = $_GET['num'];
   if (isset($_SESSION['userid'])) 
	{
			$userid = $_SESSION['userid'];
			$username = $_SESSION['username'];
			$usernick = $_SESSION['usernick'];
			$userlevel = $_SESSION['userlevel'];
	}
   include "../lib/dbconn.php";
   $sql = "select * from anonym where num=$num";
	$result = $connect->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$item_id = $row['id'];

	if(!$userid != $item_id) {
		echo("
		<script>
	     window.alert('글쓴이 정보가 일치하지 안습니다.')
	     history.go(-1)
	   </script>
		");
		exit;
	}
   $sql = "select * from $table where num = '$num'";
   $result = $connect->query($sql);

   $row = $result->fetch_array(MYSQLI_ASSOC);

   $copied_name[0] = $row['file_copied_0'];
   $copied_name[1] = $row['file_copied_1'];
   $copied_name[2] = $row['file_copied_2'];

   for ($i=0; $i<3; $i++)
   {
		if ($copied_name[$i])
	   {
			$image_name = "./data/".$copied_name[$i];
			unlink($image_name);
	   }
   }

   $sql = "delete from $table where num = '$num'";
   $connect->query($sql);

   $connect->close();

   echo "
	   <script>
	    location.href = 'list.php?table=$table';
	   </script>
	";
?>

