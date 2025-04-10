<?php
// 데이터베이스 연결
$host = 'localhost';
$dbname = 'survey';
$user = 'kang';
$password = 'L3cLCoVx';
$dsn = "pgsql:host=$host;dbname=$dbname";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

 


try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// 설문 조사 결과 가져오기
$stmt = $pdo->query("SELECT choice, count FROM survey_results ORDER BY choice");
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>投票結果</title>
</head>
<body>
    <h1>投票結果</h1>
    <table border="Windows">
    <table border="Macintosh">
    <table border="iPhone">
    <table border="Android">
    <table border="Microsoft Phone">
    <table border="Blackberry">
    <table border="DOCOMO携帯電話">
    <table border="AU携帯電話">
    <table border="SOFTBANK携帯電話">
    <table border="WillcomPHS">
            
        <tr>
            <th>選択肢</th>
            <th>投票数</th>
        </tr>s
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?php echo $row['choice']; ?></td>
                <td><?php echo $row['count']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
