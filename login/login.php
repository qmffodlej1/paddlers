<?
date_default_timezone_set('Asia/Seoul');
include "../lib/dbconn.php";
session_start();
$id = $_POST['id'];
$pass = $_POST['pass'];
$id = stripslashes($id);
$pass = stripslashes($pass);
$table = "member";

	$total_failed_login = 3;
	$lockout_time       = 15;
  //로그인 실패 변수 생성
?>
<meta charset="utf-8">
<?
if(!$id) {
  echo("
    <script>
      window.alert('아이디를 입력하세요.')
      history.go(-1)
    </script>
  ");
  exit;
}
if(!$pass) {
  echo("
    <script>
    
      window.alert('비밀번호를 입력하세요.')
      history.go(-1)
    </script>
  ");
  exit;
}
$data_1stcheck = $pdo->prepare('SELECT * from login_attempts WHERE id = (:id);');
$data_1stcheck->bindParam(':id',$id,PDO::PARAM_STR);
$data_1stcheck->execute();
$row_1stcheck = $data_1stcheck->fetch();
$lock_check = new DateTime($row_1stcheck['lock_time']);
$time_check = new DateTime(); // 현재 시간
if($lock_check >= $time_check) {
  echo "
  <script>
    alert(`
      <p><em>Warning</em>: 3회 이상 로그인 실패 하셨습니다.</p>
      <p>실패 횟수: <em>{$row_1stcheck['fail_count']}</em>.</p>
      <p>잠금 시간: <em>{$row_1stcheck['lock_time']}</em>.</p>
    `);
  </script>
  ";
}
else {
  $data = $pdo->prepare('SELECT * from member WHERE id = (:id);');
  $data->bindParam( ':id', $id, PDO::PARAM_STR );
  $data->execute();
  $row = $data->fetch();
  $db_pass = $row['pass'];
  if(!isset($row['id']) || $pass != $db_pass ) {
  sleep( rand( 2, 4 ) );
  echo("
      <script>
        window.alert('아이디 또는 비밀번호를 잘못 입력 했습니다.');
        history.go(-1)
      </script>
    ");
    if($pass != $db_pass) {
    $data = $pdo->prepare( 'SELECT fail_count, lock_time FROM login_attempts WHERE id = (:id) LIMIT 1;' );
    $data->bindParam( ':id', $id, PDO::PARAM_STR );
    $data->execute();
    $row = $data->fetch();  //로그인 실패 횟수 확인문
    $data_count = $pdo->prepare( 'UPDATE login_attempts SET fail_count = (fail_count + 1) WHERE id = (:id) LIMIT 1;' );
    $data_count->bindParam( ':id', $id, PDO::PARAM_STR );
    $data_count->execute(); // 실패시 실패 카운트 증가
      if( ( $data->rowCount() == 1 ) && ( $row[ 'fail_count' ] == $total_failed_login ) )  { //실패 횟수가 3회라면
        $timenow = time();
        $timeout = $timenow + ($lockout_time * 60); // 실패 시 타임 아웃 시간 지정 (에포크 타임스탬프로 계산)
        $data_lock = $pdo->prepare('UPDATE login_attempts SET lock_time = FROM_UNIXTIME(:lock_time) WHERE id = :id LIMIT 1;');
        $data_lock->bindParam(':lock_time', $timeout, PDO::PARAM_INT); 
        $data_lock->bindParam(':id', $id, PDO::PARAM_STR);
        $data_lock->execute();
      }
    }
  }
  else {
    $userid = $row['id'];
    $username = $row['name'];
    $usernick = $row['nick'];
    $userlevel = $row['level'];
    $_SESSION['userid'] = $userid;
    $_SESSION['username'] = $username;
    $_SESSION['usernick'] = $usernick;
    $_SESSION['userlevel'] = $userlevel;
    $data = $pdo->prepare( 'UPDATE login_attempts SET fail_count = 0,lock_time = 0 WHERE id = (:id) LIMIT 1;' );  // 성공이라면 초기화 
    $data->bindParam( ':id', $id, PDO::PARAM_STR );
    $data->execute();      
    echo("
    <script>
      location.href = '../index.php';
    </script>
    ");
  }      
}
?>
