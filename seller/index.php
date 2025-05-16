<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login & Register Admin - StyleHeaven</title>
    <link rel="stylesheet" href="style/login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="main-container">
        
        <div class="company-logo">STYLEHEAVEN</div>
        <div class="form-container">
            <div class="login-section">
                <h2>Login Admin</h2>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit">Login</button>
                </form>
            </div>

            <div class="register-section">
                <h2>Register Admin</h2>
                <form action="register.php" method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>