<?
	$id = $_POST['id'];
	$pass = $_POST['pass'];
	$name = $_POST['name'];
	$nick = $_POST['nick'];
	$hp1 = $_POST['hp1'];
	$hp2 = $_POST['hp2'];
	$hp3 = $_POST['hp3'];
?>
<meta charset="utf-8">
<?
   $hp = $hp1."-".$hp2."-".$hp3;

   $regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장
   $ip = $_SERVER['REMOTE_ADDR'];         // 방문자의 IP 주소를 저장

   include "../lib/dbconn.php";       // dconn.php 파일을 불러옴

  $data = $pdo->prepare('SELECT * FROM member WHERE id = (:id) LIMIT 1;');
  $data->bindParam(':id', $id, PDO::PARAM_STR );
  $data->execute();
  $row = $data->fetch();


   if(isset($row['id'])) {
     echo("
           <script>
             window.alert('해당 아이디가 존재합니다.')
             history.go(-1)
           </script>
         ");
         exit;
   }
   else
   {            // 레코드 삽입 명령을 $sql에 입력
      $data = $pdo->prepare('INSERT INTO member (id, pass, name, nick, hp, regist_day, level) values ((:id), (:pass), (:nme), (:nick), (:hp), (:regist_day), (:lv));');
      $data->bindParam(':id', $id, PDO::PARAM_STR);
      $data->bindParam(':pass', $pass, PDO::PARAM_STR);
      $data->bindParam(':nme', $name, PDO::PARAM_STR);
      $data->bindParam(':nick', $nick, PDO::PARAM_STR);
      $data->bindParam(':hp', $hp, PDO::PARAM_STR);
      $data->bindParam(':regist_day', $regist_day, PDO::PARAM_STR);
      $data->bindValue(':lv', 9, PDO::PARAM_INT);
		  $data->execute();
      $data2 = $pdo->prepare('INSERT INTO login_attempts(id,fail_count) values ((:id),0);');
      $data2->BindParam(':id',$id,PDO::PARAM_STR);
      $data2->execute();
   }
   echo "
   <script>
   location.href = '../login/login_form.php';
   </script>
	";
?>

   
