<?
session_start();
  $id = $_POST[ 'id' ];
  $id = stripslashes( $id );
  $id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $id ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
  $pass = $_POST[ 'pass' ];
	$pass = stripslashes( $pass );
	$pass = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $pass ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$table = "member";
  // 받은 변수들 문자열로 치환

	$total_failed_login = 3;
	$lockout_time       = 15;
	$account_locked     = false;
  //로그인 실패 변수 생성
?>
<meta charset="utf-8">
<?
	$data = $db->prepare( 'SELECT failed_login, last_login FROM memeber WHERE id = (:id) LIMIT 1;' );
	$data->bindParam( ':id', $id, PDO::PARAM_STR );
	$data->execute();
	$row = $data->fetch();  //로그인 실패 횟수 확인문

  if( ( $data->rowCount() == 1 ) && ( $row[ 'failed_login' ] >= $total_failed_login ) )  { //3회 이상 실패한다면
		$last_login = strtotime( $row[ 'last_login' ] );
		$timeout    = $last_login + ($lockout_time * 60); //실패 시 타임 아웃 시간 지정
		$timenow    = time();
		if( $timenow < $timeout ) {  //현재시간이 잠금시간 보다 작다면
			$account_locked = true;
		}
	}
	if( ( $data->rowCount() == 1 ) && ( $account_locked == false ) ) { 
		$failed_login = $row[ 'failed_login' ];
		$last_login   = $row[ 'last_login' ];
		if( $failed_login >= $total_failed_login ) {
			$html .= "<p><em>Warning</em>: 3회 이상 로그인 실패 하셨습니다. </p>";
			$html .= "<p>실패 횟수: <em>{$failed_login}</em>.<br />마지막 접속 시간: <em>{$last_login}</em>.</p>";
		}
		$data = $db->prepare( 'UPDATE users SET failed_login = "0" WHERE user = (:user) LIMIT 1;' );
		$data->bindParam( ':user', $user, PDO::PARAM_STR );
		$data->execute();  //실패 횟수 초기화
	} 
  else {
		sleep( rand( 2, 4 ) ); // 로그인 실패시 sleep 시간 지정
		$data = $db->prepare( 'UPDATE users SET failed_login = (failed_login + 1) WHERE user = (:user) LIMIT 1;' );
		$data->bindParam( ':user', $user, PDO::PARAM_STR );
		$data->execute(); // 실패시 실패 카운트 증가
	}

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
include "../lib/dbconn.php";
   $sql = "select * from $table where id='$id'";
  $result = $connect->query($sql);
  $num_match = $result->num_rows; //SQL 쿼리문

  if(!$num_match) {
    echo("
      <script>
        window.alert('등록되지 않은 아이디입니다.')
        history.go(-1)
      </script>
    ");
  }
  else {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $db_pass = $row['pass'];
      if($pass != $db_pass) {
        echo("
          <script>
            window.alert('비밀번호가 틀립니다.')
            history.go(-1)
          </script>
        ");
        exit;
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
        $data = $db->prepare( 'UPDATE users SET last_login = now() WHERE id = (:id) LIMIT 1;' );
        $data->bindParam( ':user', $id, PDO::PARAM_STR );
        $data->execute();      
          echo("
            <script>
              location.href = '../index.php';
            </script>
          ");
      }
  }          
?>
