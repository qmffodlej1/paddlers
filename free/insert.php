<? 
	session_start(); 
	$table = "free";
	$mode = @$_GET['mode'];
	$num = @$_GET['num'];
	$page = @$_GET['page'];

	$html_ok = @$_POST['html_ok'];
	$subject = @$_POST['subject'];
	$content = @$_POST['content'];
	$upfile = @$_POST['upfile'];


if (isset($_SESSION['userid'])) 
{
		$userid = $_SESSION['userid'];
		$username = $_SESSION['username'];
		$usernick = $_SESSION['usernick'];
		$userlevel = $_SESSION['userlevel'];

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
	$file_name = preg_replace('/[^A-Za-z0-9_]/', '', @$upfile_name[$i]);

	$regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장

	// 다중 파일 업로드
	$files = $_FILES["upfile"];
	$count = count($files["name"]);
	$upload_dir = './data/';

	for ($i=0; $i<$count; $i++)
	{
		$upfile_name[$i]     = $files["name"][$i];
		$upfile_tmp_name[$i] = $files["tmp_name"][$i];
		$upfile_type[$i]     = $files["type"][$i];
		$upfile_size[$i]     = $files["size"][$i];
		$upfile_error[$i]    = $files["error"][$i];
      
		$file = explode(".", $upfile_name[$i]);
		$file_name = @$file[0];
		$file_ext  = @$file[1];

		if (!$upfile_error[$i])
		{
			$new_file_name = date("Y_m_d_H_i_s");
			$new_file_name = $new_file_name."_".$i;
			$copied_file_name[$i] = $new_file_name.".".$file_ext;      
			$uploaded_file[$i] = $upload_dir.$copied_file_name[$i];

			if( $upfile_size[$i]  > 500000 ) {
				echo("
				<script>
				alert('업로드 파일 크기가 지정된 용량(500KB)을 초과합니다!<br>파일 크기를 체크해주세요! ');
				history.go(-1)
				</script>
				");
				exit;
			}

        // 추가된 부분: 이미지 파일인지 확인
		$lowercase_upfile_type = strtolower($upfile_type[$i]);
        $allowed_image_types = array("image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/jpg");
        if (in_array($upfile_type[$i], $allowed_image_types)) {
            if (!move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i])) {
                echo("
                    <script>
                    alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
                    history.go(-1)
                    </script>
                ");
                exit;
            }
        } else {
            echo("
                <script>
                alert('JPG와 GIF 이미지 파일만 업로드 가능합니다!');
                history.go(-1)
                </script>
            ");
            exit;
        }
    



			// if ( ($upfile_type[$i] != "image/gif") &&
			// 	($upfile_type[$i] != "image/jpeg") &&
			// 	($upfile_type[$i] != "image/pjpeg") &&
			// 	($upfile_type[$i] != "image/png"))
			// {
			// 	echo("
			// 		<script>
			// 			alert('JPG와 GIF 이미지 파일만 업로드 가능합니다!');
			// 			history.go(-1)
			// 		</script>
			// 		");
			// 	exit;
			// }

			// if (!move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i]) )
			// {
			// 	echo("
			// 		<script>
			// 		alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
			// 		history.go(-1)
			// 		</script>
			// 	");
			// 	exit;
			// }
		}
	}

	include "../lib/dbconn.php";       // dconn.php 파일을 불러옴

 	if ($mode=="modify")
	{
		$sql = "select * from free where num=$num";
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

		if(isset($_POST['del_file']) && empty($_POST['del_file'])) {
		$num_checked = count($_POST['del_file']);
		$position = $_POST['del_file'];
		
		for($i=0; $i<$num_checked; $i++)                      // delete checked item
		{
			$index = $position[$i];
			$del_ok[$index] = "y";
		}
	    }

		$data = $pdo->prepare('select * from '.$table.' where num=(:num);');
		$data->BindParam(':num',$num,PDO::PARAM_INT);
		$data->execute();
		$row = $data->fetch_array(MYSQLI_ASSOC);

		for ($i=0; $i<$count; $i++)					// update DB with the value of file input box
		{

			$field_org_name = "file_name_".$i;
			$field_real_name = "file_copied_".$i;

			$org_name_value = $upfile_name[$i];
			$org_real_value = $copied_file_name[$i];
			if ($del_ok[$i] == "y")
			{
				$delete_field = "file_copied_".$i;
				$delete_name = $row['$delete_field'];				
				$delete_path = "./data/".$delete_name;

				unlink($delete_path);
            
				$data = $pdo->prepare('update '.$table.' set (:field_org_name) = (:org_name_value), $field_real_name = (:org_real_value)  where num= (:num);');
				$data->BindParam(':field_org_name',$field_org_name,PDO::PARAM_STR);
				$data->BindParam(':org_name_value',$org_name_value,PDO::PARAM_STR);
				$data->BindParam(':org_real_value',$org_real_value,PDO::PARAM_STR);
				$data->BindParam(':num',$num,PDO::PARAM_INT);
				$data->execute();
			}
			else
			{
				if (!$upfile_error[$i])
				{
					$data = $pdo->prepare('update '.$table.' set (:field_org_name) = (:org_name_value), $field_real_name = (:org_real_value)  where num= (:num);');
					$data->BindParam(':field_org_name',$field_org_name,PDO::PARAM_STR);
					$data->BindParam(':org_name_value',$org_name_value,PDO::PARAM_STR);
					$data->BindParam(':org_real_value',$org_real_value,PDO::PARAM_STR);
					$data->BindParam(':num',$num,PDO::PARAM_INT);
					$data->execute();			
				}
			}
		}
		$data = $pdo->prepare('update (:table) set subject= (:subject), content= (:content) where num= (:num);');
		$data->BindParam(':table',$table,PDO::PARAM_STR);
		$data->BindParam(':subject',$subject,PDO::PARAM_STR);
		$data->BindParam(':content',$content,PDO::PARAM_STR);
		$data->BindParam(':num',$num,PDO::PARAM_INT);
		$data->execute();			
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
			$content = htmlspecialchars($content);
		}

		$data = $pdo->prepare('insert into '.$table.' (id, name, nick, subject, content, regist_day, hit, is_html, file_name_0, file_name_1, file_name_2, file_copied_0,  file_copied_1, file_copied_2) values (:userid, :username, :usernick, :subject, :content, :regist_day, 0, :is_html, :upfile_name_0, :upfile_name_1, :upfile_name_2, :copied_file_name_0, :copied_file_name_1, :copied_file_name_2);');
		$data->BindParam(':userid',$userid,PDO::PARAM_STR);
		$data->BindParam(':username',$username,PDO::PARAM_STR);
		$data->BindParam(':usernick',$usernick,PDO::PARAM_STR);
		$data->BindParam(':subject',$subject,PDO::PARAM_STR);
		$data->BindParam(':content',$content,PDO::PARAM_STR);
		$data->BindParam(':regist_day',$regist_day,PDO::PARAM_STR);
		$data->BindParam(':is_html',$is_html,PDO::PARAM_STR);
		$data->BindParam(':upfile_name_0',$upfile_name[0],PDO::PARAM_STR);
		$data->BindParam(':upfile_name_1',$upfile_name[1],PDO::PARAM_STR);
		$data->BindParam(':upfile_name_2',$upfile_name[2],PDO::PARAM_STR);
		$data->BindParam(':copied_file_name_0',$copied_file_name[0],PDO::PARAM_STR);
		$data->BindParam(':copied_file_name_1',$copied_file_name[1],PDO::PARAM_STR);
		$data->BindParam(':copied_file_name_2',$copied_file_name[2],PDO::PARAM_STR);
		$data->execute();
	}
	$pdo = null;             // DB 연결 끊기

	echo "
	   <script>
	    location.href = 'list.php?table=$table&page=$page';
	   </script>
	";
?>

  
