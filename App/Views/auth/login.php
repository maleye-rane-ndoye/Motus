<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="./public/css/styles.css">
    <script src="./public/js/login.js" defer></script>
</head>
<body>
    <div class="contente">
        <form id="login-form" method="POST">
            <h2>Connexion</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p id="message"></p>
        </form>
    </div>
</body>
</html>
