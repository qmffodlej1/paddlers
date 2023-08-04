<? 
	session_start();
	@$table = $_GET['table'];
	@$mode = $_GET['mode'];
	@$num = $_GET['num'];
	@$page = $_GET['page'];

	if (isset($_SESSION['userid'])) 
	{
			$userid = $_SESSION['userid'];
			$username = $_SESSION['username'];
			$usernick = $_SESSION['usernick'];
			$userlevel = $_SESSION['userlevel'];
	}
	include "../lib/dbconn.php";

	if ($mode=="modify")
	{
		$sql = "select * from $table where num=$num";
		$result = $connect->query($sql);
		$row = $result->fetch_array(MYSQLI_ASSOC);       
	
		$item_subject     = $row['subject'];
		$item_content     = $row['content'];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head> 
<meta charset="utf-8">
<link href="../css/common.css" rel="stylesheet" type="text/css" media="all">
<link href="../css/board4.css" rel="stylesheet" type="text/css" media="all">
<script>
  function check_input()
   {
      if (!document.board_form.subject.value)
      {
          alert("제목을 입력하세요1");    
          document.board_form.subject.focus();
          return;
      }

      if (!document.board_form.content.value)
      {
          alert("내용을 입력하세요!");    
          document.board_form.content.focus();
          return;
      }
      document.board_form.submit();
   }
</script>
</head>
<div id="container">
    <body>
        <header class="header">
		<a href="../index.php"> <!-- 로고를 클릭하면 현재 페이지(index.php)로 연결되도록 설정 -->
                <img src="../img/logo2.png" class="logo" alt="로고">
            </a>
            <?php
            if (empty($userid)) {
                echo '<div id="top_login"><a href="../login/login_form.php">로그인</a> | <a href="../member/member_form.php">회원가입</a></div>';
            } else {
                echo '<div id="top_login">' . $usernick . ' (level: ' . $userlevel . ') | <a href="../login/logout.php">로그아웃</a> | <a href="../login/member_form_modify.php">정보수정</a></div>';
            }
            ?>
        </header>
		<div id="body">
        <div id="wrap">
            <div id="menu">
                <?php include "../lib/top_menu2.php"; ?>
            </div> <!-- end of menu -->
        </div> <!-- end of wrap -->
		<div id="col_2">        
		<div id="title">
			<h1>익명 게시판</h1>
		</div>
		<div class="clear"></div>

		<div id="write_form_title">
			<ul>
				<li><h2>글쓰기</h2><li>
		</ul>
		</div>
		<div class="clear"></div>
<?
	if($mode=="modify")
	{
?>
		<form  name="board_form" method="post" action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&table=<?=$table?>" enctype="multipart/form-data"> 
<?
	}
	else
	{
?>
		<form  name="board_form" method="post" action="insert.php?table=<?=$table?>" enctype="multipart/form-data"> 
<?
	}
?>	
		<form  name="board_form" method="post" action="insert.php"> 
		<div id="write_form">
			<div id="write_row1">
				<div class="col1"><h3>닉네임:</h3> <h3><?=@$usernick?></h3></div>
				<div class="col1"><input type="checkbox" name="html_ok" value="y"><h3>HTML 쓰기</h3></div>
			</div>
			<div class="write_line"></div>
			<div id="write_row2"><h3>제목</h3><input class="cat" type="text" name="subject" value="<?=@$item_subject?>"></div>
			<div class="write_line"></div>
			<div id="write_row3"><h3>내용</h3><textarea class="dog" name="content"><?=@$item_content?></textarea></div>
			<div class="write_line"></div>
		</div>

			<div class="clear"></div>
			
			<div id="write_button"><a href="#"><button type="submit" class="button_3" onclick="check_input()">완료</button></a>&nbsp;
			</form>
								<a href="list.php?page=<?=$page?>"><input type="button" class="button_3" value="목록"></a>
		</div>
</div>

	</div> <!-- end of col2 -->
  </div> <!-- end of content -->
</div> <!-- end of wrap -->

</body>
</html>

