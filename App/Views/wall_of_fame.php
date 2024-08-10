<!DOCTYPE html>
<html>
<head>
    <title>Wall of Fame</title>
    <link rel="stylesheet" href="./public/css/styles.css">
</head>
<body>
    <h2>Wall of Fame</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Wins</th>
                <th>Losses</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($scores as $score): ?>
                <tr>
                    <td><?= htmlspecialchars($score['username']) ?></td>
                    <td><?= htmlspecialchars($score['win']) ?></td>
                    <td><?= htmlspecialchars($score['lost']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
