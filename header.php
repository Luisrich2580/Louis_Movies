<!-- header.php -->
<header>
    <div class="logo">LOUI MOVIES</div>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="movies.php">Movies</a></li>
            <li><a href="series.php">Series</a></li>
        </ul>
    </nav>
    <div class="search-container">
        <form class="search-form" action="index.php" method="GET">
            <input type="text" name="query" placeholder="Enter movie name..." value="<?php echo isset($searchQuery) ? htmlspecialchars($searchQuery) : ''; ?>" required>
            <button type="submit">Search</button>
        </form>

        <!-- Error message display -->
        <?php if (isset($_GET['error'])): ?>
            <div class="error">
                <p><?php echo htmlspecialchars($_GET['error']); ?></p>
            </div>
        <?php endif; ?>
    </div>
</header>
