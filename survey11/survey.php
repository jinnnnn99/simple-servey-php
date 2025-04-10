<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>このページにアクセスしている端末を選んでください。</title>
</head>
<body>
    <h1>このページにアクセスしている端末を選んでください。</h1>
    <form method="post" action="survey.php">
        <input type="radio" id="choice1" name="choice" value="Windows">
        <label for="choice1">Windows</label><br>
        
        <input type="radio" id="choice2" name="choice" value="Macintosh">
        <label for="choice2">Macintosh</label><br>
        
        <input type="radio" id="choice3" name="choice" value="iPhone">
        <label for="choice3">iPhone</label><br>
        
        <input type="radio" id="choice4" name="choice" value="Android">
        <label for="choice4">Android</label><br>
        
        <input type="radio" id="choice5" name="choice" value="Microsoft Phone">
        <label for="choice5">Microsoft Phone</label><br>
        
        <input type="radio" id="choice6" name="choice" value="Blackberry">
        <label for="choice6">Blackberry</label><br>
        
        <input type="radio" id="choice7" name="choice" value="DOCOMO 携帯電話">
        <label for="choice7">DOCOMO 携帯電話</label><br>
        
        <input type="radio" id="choice8" name="choice" value="AU 携帯電話">
        <label for="choice8">AU 携帯電話</label><br>
        
        <input type="radio" id="choice9" name="choice" value="SOFTBANK 携帯電話">
        <label for="choice9">SOFTBANK 携帯電話</label><br>
        
        <input type="radio" id="choice10" name="choice" value="Willcom PHS">
        <label for="choice10">Willcom PHS</label><br>
        
        <button type="submit">投票する</button>
    </form>

    <h2>投票結果</h2>
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

    // 설문 항목과 결과를 매핑하는 배열
    $choices = [
        1 => 'Windows',
        2 => 'Macintosh',
        3 => 'iPhone',
        4 => 'Android',
        5 => 'Microsoft Phone',
        6 => 'Blackberry',
        7 => 'DOCOMO 携帯電話',
        8 => 'AU 携帯電話',
        9 => 'SOFTBANK 携帯電話',
        10 => 'Willcom PHS',
    ];

    try {
        $pdo = new PDO($dsn, $user, $password, $options);
        
        // 설문 조사 폼 제출 시 처리
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $choice = $_POST['choice'];
            
            // 선택된 항목의 인덱스 찾기
            $choiceIndex = array_search($choice, $choices);
            
            // 설문 조사 응답 저장
            $stmt = $pdo->prepare("INSERT INTO survey_responses (choice) VALUES (:choice)");
            $stmt->execute(['choice' => $choiceIndex]);
            
            // 선택된 항목의 카운트 업데이트
            $stmt = $pdo->prepare("INSERT INTO survey_results (choice, count) VALUES (:choiceIndex, 1)
                                    ON CONFLICT (choice) DO UPDATE SET count = survey_results.count + 1");
            $stmt->execute(['choiceIndex' => $choiceIndex]);
            
            // 다시 페이지를 로드하여 결과를 표시
            header('Location: survey.php');
            exit;
        }
        
        // 설문 조사 결과 가져오기
        $stmt = $pdo->query("SELECT choice, count FROM survey_results ORDER BY choice");
        $results = $stmt->fetchAll();
        
        // 결과 출력
        echo '<table border="1">';
        echo '<tr><th>選択肢</th><th>投票数</th></tr>';
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . $choices[$row['choice']] . '</td>';
            echo '<td>' . $row['count'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
    ?>
</body>
</html>
