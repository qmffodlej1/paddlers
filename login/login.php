<?
include "../lib/dbconn.php";
session_start();
$id = $_POST['id'];
$pass = $_POST['pass'];
$id = stripslashes($id);
$pass = stripslashes($pass);
$table = "member";

	$total_failed_login = 3;
	$lockout_time       = 15;
	$account_locked     = false;
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
        history.go(-1);
      </script>
    ");
    if($pass != $db_pass) {
    $data = $pdo->prepare( 'SELECT fail_count, lock_time FROM login_attempts WHERE id = (:id) LIMIT 1;' );
    $data->bindParam( ':id', $id, PDO::PARAM_STR );
    $data->execute();
    $row = $data->fetch();  //로그인 실패 횟수 확인문
      if( ( $data->rowCount() == 1 ) && ( $row[ 'fail_count' ] = $total_failed_login ) )  { //3회라면 실패한다면
        $timenow = strtotime( time());
        $timeout = $timenow + ($lockout_time * 60); //실패 시 타임 아웃 시간 지정
        $data = $pdo->prepare('UPDATE login_attempts SET lock_time = (:lock_time) where id = (:id) LIMIT 1;');
        $data->bindParam(':lock_time',$timeout,PDO::PARAM_STR);
        $data->execute();
      }
      else if(( $data->rowCount() == 1 ) && ( $row['failed_login']>= $total_failed_login)) { 
        $failed_count = $row[ 'failed_count' ];
        $lock_time    = $row[ 'lock_time' ];
        if( $failed_login >= $total_failed_login ) {
          $html .= "<p><em>Warning</em>: 3회 이상 로그인 실패 하셨습니다. </p>";
          $html .= "<p>실패 횟수: <em>{$failed_login}</em>.</p>";
          $html .= "<p>잠금 시간: <em>{$lock_time}</em>.</p>";
        }
      } 
      $data = $pdo->prepare( 'UPDATE login_attempts SET failed_count = (failed_count + 1) WHERE id = (:id) LIMIT 1;' );
      $data->bindParam( ':id', $id, PDO::PARAM_STR );
      $data->execute(); // 실패시 실패 카운트 증가
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
    $data = $pdo->prepare( 'UPDATE login_attempts SET fail_count = "0" WHERE id = (:id) LIMIT 1;' );  // 마지막 접속 시간 수정 
    $data->bindParam( ':id', $id, PDO::PARAM_STR );
    $data->execute();      
    echo("
    <script>
      location.href = '../index.php';
    </script>
    ");
  }          
?>
