<?php
$host = "localhost"; // 호스트 주소
$user = "paddlers"; // 데이터베이스 사용자명
$password = "qhdks12"; // 데이터베이스 비밀번호
$db = "paddlers"; // 데이터베이스 이름
// $page = 1;
$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);

// 데이터베이스 연결 오류 체크
// if ($pdo->connect_error) {
//     die("데이터베이스 연결에 실패했습니다: " . $connect->connect_error);
// }
?>
