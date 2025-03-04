<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="registration-container">
        <h2>Create an Account</h2>
        <?php if (!empty($generalMessage)): ?>
            <p class="general-error"><?= $generalMessage ?></p>
        <?php endif; ?>
        <form action="/register" method="POST">
            <label for="username">Username:</label>
            <span class="error"><?= $error['username'] ?? '' ?></span>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username']??'') ?>" required>
            

            <label for="email">Email:</label>
            <span class="error"><?= $error['email'] ?? '' ?></span>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email']??'') ?>" required>
            

            <label for="password">Password:</label>
            <span class="error"><?= $error['password'] ?? '' ?></span>
            <input type="password" id="password" name="password" value="<?= htmlspecialchars($_POST['password']??'') ?>" required>
            

            <label for="confirmPassword">Confirm Password:</label>
            <span class="error"><?= $error['confirmPassword'] ?? '' ?></span>
            <input type="password" id="confirmPassword" name="confirmPassword" value="<?= htmlspecialchars($_POST['confirmPassword']??'') ?>" required>
            

            <button type="submit" name="register">Register</button>
            <p>Already an account? <a href="/login">Login here</a></p>
        </form>
    </div>
</body>
</html>
