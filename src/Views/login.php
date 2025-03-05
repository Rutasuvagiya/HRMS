
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="flexbody">
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($generalMessage)): ?>
            <p class="general-error"><?= $generalMessage ?></p>
        <?php endif; ?>
        <form action="/login" method="POST">
            <label for="username">Username:</label>
            <span class="error"><?= $error['username'] ?? '' ?></span>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username']??'') ?>"  required>

            <label for="password">Password:</label>
            <span class="error"><?= $error['password'] ?? '' ?></span>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="login">Login</button>
            <p>Don't have an account? <a href="/register">Register here</a></p>
        </form>
    </div>
