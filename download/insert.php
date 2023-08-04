<?php
	session_start();
	$table = "download";
	$mode = @$_GET['mode'];
	$num = @$_GET['num'];
	$page = @$_GET['page'];

	$html_ok = @$_POST['html_ok'];
	$subject = @$_POST['subject'];
	$content = @$_POST['content'];
	$upfile = @$_POST['upfile'];

	if (isset($_SESSION['userid'])) {
		$userid = $_SESSION['userid'];
		$username = $_SESSION['username'];
		$usernick = $_SESSION['usernick'];
		$userlevel = $_SESSION['userlevel'];
	}
?>

<meta charset="utf-8">
<?php
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

	// 다중 파일 업로드
	$files = $_FILES["upfile"];
	$count = count($files["name"]);
	$upload_dir = './data/';

	$copied_file_name = array(); // $copied_file_name 배열을 초기화

	$allowed_extensions = array("zip"); // 허용된 확장자 리스트에 zip 확장자 추가

	$file_name = preg_replace('/[^A-Za-z0-9_]/', '', $upfile_name[$i]);

	for ($i = 0; $i < $count; $i++) {
		$upfile_name[$i]     = $files["name"][$i];
		$upfile_tmp_name[$i] = $files["tmp_name"][$i];
		$upfile_type[$i]     = $files["type"][$i];
		$upfile_size[$i]     = $files["size"][$i];
		$upfile_error[$i]    = $files["error"][$i];

		if ($upfile_error[$i] === UPLOAD_ERR_NO_FILE) {
			// 파일이 없을 경우, 처리를 원하는 방식으로 작성
			$copied_file_name[$i] = "";
			continue; // 다음 파일로 넘어감
		}


		$file = explode(".", $upfile_name[$i]);
		@$file_name = $file[0];
		@$file_ext  = strtolower($file[1]);

		
	    if (!in_array($file_ext, $allowed_extensions)) {
			echo("
			<script>
			alert('허용되지 않는 파일 형식입니다. Zip 파일만 업로드 가능합니다.');
			history.go(-1)
			</script>
			");
			exit;
		}
	
		if (!$upfile_error[$i]) {
			$new_file_name = date("Y_m_d_H_i_s");
			$new_file_name = $new_file_name . "_" . $i;
			$copied_file_name[$i] = $new_file_name . "." . $file_ext;
			$uploaded_file[$i] = $upload_dir . $copied_file_name[$i];

			if ($upfile_size[$i] > 5000000) {
				echo("
				<script>
				alert('업로드 파일 크기가 지정된 용량(5MB)을 초과합니다!<br>파일 크기를 체크해주세요! ');
				history.go(-1)
				</script>
				");
				exit;
			}

			if (!move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i])) {
				echo("
					<script>
					alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
					history.go(-1)
					</script>
				");
				exit;
			}
		}
	}

	include "../lib/dbconn.php"; // dconn.php 파일을 불러옴
	if ($mode == "modify") {
		if(isset($_POST['del_file']) && empty($_POST['del_file'])) {
		$num_checked = count($_POST['del_file']);
		$position = $_POST['del_file'];

		for ($i = 0; $i < $num_checked; $i++) // delete checked item
		{
			$index = $position[$i];
			$del_ok[$index] = "y";
		}
	    }
		$sql = "select * from $table where num=$num"; // get target record
		$result = $connect->query($sql); // 데이터베이스 연결 객체를 사용
		$row = $result->fetch_array(MYSQLI_ASSOC);

		for ($i = 0; $i < $count; $i++) // update DB with the value of file input box
		{
			$field_org_name = "file_name_" . $i;
			$field_real_name = "file_copied_" . $i;

			$org_name_value = $upfile_name[$i];
			$org_real_value = $copied_file_name[$i];
			if ($del_ok[$i] == "y") {
				$delete_field = "file_copied_" . $i;
				$delete_name = $row[$delete_field];
				$delete_path = "./data/" . $delete_name;
				unlink($delete_path);

				$sql = "update $table set $field_org_name = '$org_name_value', $field_real_name = '$org_real_value'  where num=$num";
				$connect->query($sql); // 데이터베이스 연결 객체를 사용하여 쿼리 실행
			} else {
				if (!$upfile_error[$i]) {
					$sql = "update $table set $field_org_name = '$org_name_value', $field_real_name = '$org_real_value'  where num=$num";
					$connect->query($sql); // 데이터베이스 연결 객체를 사용하여 쿼리 실행
				}
			}
		}
		$sql = "update $table set subject='$subject', content='$content' where num=$num";
		$connect->query($sql); // 데이터베이스 연결 객체를 사용하여 쿼리 실행
	} else {
		$sql = "insert into $table (id, name, nick, subject, content, regist_day, hit, ";
		$sql .= " file_name_0, file_name_1, file_name_2, file_type_0, file_type_1, file_type_2, file_copied_0,  file_copied_1, file_copied_2) ";
		$sql .= " values('$userid', '$username', '$usernick', '$subject', '$content', '$regist_day', 0, ";
		$sql .= " '$upfile_name[0]', '$upfile_name[1]',  '$upfile_name[2]', '$upfile_type[0]', '$upfile_type[1]',  '$upfile_type[2]', ";
		@$sql .= " '$copied_file_name[0]', '$copied_file_name[1]','$copied_file_name[2]')";
		$connect->query($sql); // 데이터베이스 연결 객체를 사용하여 쿼리 실행
	}
	$connect->close();  // DB 연결 끊기

	echo "
	   <script>
	    location.href = 'list.php?table=$table&page=$page';
	   </script>
	";
?>
