<!DOCTYPE html>
<html>
<head>
    <title>Wall of Fame</title>
    <base href="/motus/">
    <link rel="stylesheet" href="./public/css/styles.css">
    <script src="./public/js/menu.js" defer></script>

</head>
<body>
    <h2>Wall of Fame</h2>
    <div class="menu">
        <button id="menu-toggle">Options</button>
        <div id="menu-options" class="hidden">
            <button id="gamePage-button">New part</button>
            <button id="wall-of-fame-button">Wall of Fame</button>
            <button id="logout-button">Déconnexion</button>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Player</th>
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
