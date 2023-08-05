<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인 후 이용해주세요.');history.go(-1);</script>";
    exit;
}

if (isset($_GET['num'])) {
    $num = $_GET['num'];
} else {
    echo "<script>alert('잘못된 접근입니다.');history.go(-1);</script>";
    exit;
}

include "../lib/dbconn.php";

// 사용자가 삭제 권한이 있는지 확인합니다.
$data = $pdo->prepare('SELECT id FROM memo WHERE num=(:num);');
$data->bindParam(':num',$num,PDO::PARAM_INT);
$data->execute();
$row = $data->fetch();
$memo_id = $row['id'];

$userid = $_SESSION['userid'];
if ($userid != "admin" && $userid != $memo_id) {
    echo "<script>alert('권한이 없습니다.');history.go(-1);</script>";
    exit;
}

// 선택한 댓글을 데이터베이스에서 삭제합니다.
$sql = "DELETE FROM memo WHERE num='$num'";
$result = $connect->query($sql);
$del = $pdo->prepare('DELETE FROM memo WHERE num=(:num);');
$del->bindParam(':num',$num);
$row2=$del->execute();
echo $row2;
if (isset($row2)) {
    echo "<script>alert('댓글이 삭제되었습니다.');window.location.href='memo.php';</script>";
} else {
    echo "<script>alert('댓글 삭제에 실패했습니다.');history.go(-1);</script>";
}
?>
