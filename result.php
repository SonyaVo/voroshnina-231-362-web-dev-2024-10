<?php
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text = $_POST['text'];
    if (empty(trim($text))) {
        $text = null;
    }
} else {
    $text = null;
}

// Функция для анализа текста
function analyzeText($text)
{
    if ($text === null) {
        return null;
    }

    $charCount = mb_strlen($text, 'UTF-8'); // Подсчёт символов с учётом UTF-8
    $letterCount = 0;
    $lowerCount = 0;
    $upperCount = 0;
    $punctuationCount = 0;
    $digitCount = 0;
    $wordCount = 0;
    $charFrequency = [];
    $wordFrequency = [];

    // Анализ слов
    $words = preg_split('/\s+/', $text);
    foreach ($words as $word) {
        $wordCount++;
        $word = mb_strtolower(trim($word), 'UTF-8');
        if (!isset($wordFrequency[$word])) {
            $wordFrequency[$word] = 0;
        }
        $wordFrequency[$word]++;
    }

    // Подсчёт символов и знаков препинания
    for ($i = 0; $i < mb_strlen($text, 'UTF-8'); $i++) {
        $char = mb_substr($text, $i, 1, 'UTF-8');
        if (preg_match('/[а-яА-ЯёЁa-zA-Z]/u', $char)) { // Буквы
            $letterCount++;
            if (preg_match('/[a-zа-я]/u', $char)) {
                $lowerCount++;
            } elseif (preg_match('/[A-ZА-Я]/u', $char)) {
                $upperCount++;
            }
        } elseif (preg_match('/[0-9]/', $char)) { // Цифры
            $digitCount++;
        } elseif (preg_match('/[.,!?;:\'\"-]/u', $char)) { // Знаки препинания
            $punctuationCount++;
        }

        // Учет частоты символов
        $char = mb_strtolower($char, 'UTF-8');
        if (!isset($charFrequency[$char])) {
            $charFrequency[$char] = 0;
        }
        $charFrequency[$char]++;
    }

    return [
        'text' => $text,
        'charCount' => $charCount,
        'letterCount' => $letterCount,
        'lowerCount' => $lowerCount,
        'upperCount' => $upperCount,
        'punctuationCount' => $punctuationCount,
        'digitCount' => $digitCount,
        'wordCount' => $wordCount,
        'charFrequency' => $charFrequency,
        'wordFrequency' => $wordFrequency
    ];
}

$result = analyzeText($text);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты анализа</title>
    <link rel="stylesheet" href="result.css">
</head>

<body>
    <h1>Результаты анализа текста</h1>

    <?php if ($result): ?>
        <div class="text-output" style="color: red; font-style: italic;"><?php echo htmlspecialchars($result['text'], ENT_QUOTES, 'UTF-8'); ?></div>

        <h2>Информация о тексте</h2>
        <table>
            <tr>
                <th>Количество символов</th>
                <td><?php echo $result['charCount']; ?></td>
            </tr>
            <tr>
                <th>Количество букв</th>
                <td><?php echo $result['letterCount']; ?></td>
            </tr>
            <tr>
                <th>Количество строчных букв</th>
                <td><?php echo $result['lowerCount']; ?></td>
            </tr>
            <tr>
                <th>Количество заглавных букв</th>
                <td><?php echo $result['upperCount']; ?></td>
            </tr>
            <tr>
                <th>Количество знаков препинания</th>
                <td><?php echo $result['punctuationCount']; ?></td>
            </tr>
            <tr>
                <th>Количество цифр</th>
                <td><?php echo $result['digitCount']; ?></td>
            </tr>
            <tr>
                <th>Количество слов</th>
                <td><?php echo $result['wordCount']; ?></td>
            </tr>
        </table>

        <h2>Частота символов</h2>
        <table>
            <tr>
                <th>Символ</th>
                <th>Количество</th>
            </tr>
            <?php foreach ($result['charFrequency'] as $char => $count): ?>
                <tr>
                    <td><?php echo htmlspecialchars($char, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $count; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Частота слов</h2>
        <table>
            <tr>
                <th>Слово</th>
                <th>Количество</th>
            </tr>
            <?php foreach ($result['wordFrequency'] as $word => $count): ?>
                <tr>
                    <td><?php echo htmlspecialchars($word, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $count; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <h2>Нет текста для анализа</h2>
    <?php endif; ?>

    <a href="index.html">Другой анализ</a>
</body>

</html>
