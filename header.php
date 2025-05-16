<head>
    <link rel="stylesheet" href="style/header.css">
</head>


<header>
    <div class="top-header">
        <div class="logo"><a href="index.php">STYLEHEAVEN</a></div>
        <div class="menu-toggle" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
    </div>
    <nav>
        <a href="men.php">MEN</a>
        <a href="women.php">WOMEN</a>
        <a href="kids.php">KIDS</a>
        <a href="new.php">NEW</a>
        <a href="looklist.php">LOOKS</a>


    </nav>
    <div class="actions">
        <div class="search">
            <form action="search.php" method="get" class="search-form">
                <input placeholder="Search" type="text" name="search">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <?php if (isset($_SESSION['username'])): ?>
        <a href="cart.php?id=<?= $_SESSION['user_id']; ?>">
            <i class="fas fa-shopping-bag" style="font-size: 20px;"></i>
        </a>
            <a onclick="toggleDropdown()"><i class="bi bi-person-circle" style="font-size: 20px;"></i></a>
            <div class="dropdown-menu" id="dropdownMenu">
                <a><i class="bi bi-person-check"> </i><?= ucwords(htmlspecialchars($_SESSION['username'])); ?></a>
                <a href="order_history.php">Order History</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            <?php else: ?>
                <a id="loginButton">Login</a>
            <?php endif; ?>
            </div>
    </div>
</header>

<div id="loginPanel" class="login-panel">
    <div class="login-container">
        <span class="close-btn" id="closeLogin">&times;</span>
        <h2>Login</h2>
        <form action="login.php" method="post">
            <input type="text" placeholder="Username" name="username" required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit">Login</button>
            <p>Don't have an account? <a id="registerButton">Register</a></p>
        </form>
    </div>
</div>

<div id="registerPanel" class="login-panel">
    <div class="login-container">
        <span class="close-btn" id="closeRegister">&times;</span>
        <h2>Register</h2>
        <form action="register.php" method="post">
            <input type="text" placeholder="Username" name="username" required>
            <input type="password" placeholder="Password" name="password" required>
            <input type="password" placeholder="Confirm Password" name="confirm_password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</div>