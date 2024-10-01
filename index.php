<?php
// Replace this with your actual API key from TMDb
$apiKey = "9ae88cbf0e126575922d9262d6dbaccf";

// Get the current page from the URL, default to 1 if not set
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Get the search query from the URL if set
$searchQuery = isset($_GET['query']) ? $_GET['query'] : null;

// Function to fetch popular movies with pagination
function getPopularMovies($apiKey, $page)
{
    $apiUrl = "https://api.themoviedb.org/3/movie/popular?api_key={$apiKey}&language=en-US&page={$page}";

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response into a PHP array
    $moviesData = json_decode($response, true);

    return $moviesData;
}

// Function to search for movies
function searchMovies($apiKey, $query, $page)
{
    $apiUrl = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&language=en-US&query=" . urlencode($query) . "&page={$page}";
    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Execute the cURL request
    $response = curl_exec($ch);
    curl_close($ch);
    // Decode the JSON response into a PHP array
    $moviesData = json_decode($response, true);
    return $moviesData;
}

// Fetch movies based on search query or default to popular movies
if ($searchQuery) {
    $movies = searchMovies($apiKey, $searchQuery, $currentPage);
} else {
    $movies = getPopularMovies($apiKey, $currentPage);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOUI MOVIES</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your stylesheet -->
</head>

<body>
    <?php include 'header.php'; ?>


    <!-- Page Description Section -->
    <div class="page-description">
        <h1>Welcome to Loui Movies!</h1>
        <p>Your one-stop destination for the latest and greatest movies. Explore our collection, search for your favorites, and enjoy the cinematic experience!</p>
    </div>

    <!-- Movie Results Section -->
    <div class="popular-movies">
        <?php if ($searchQuery): ?>
            <h2>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
        <?php else: ?>
            <h2>Popular Movies Right Now</h2>
        <?php endif; ?>

        <div class="movies-grid">
            <?php
            if (isset($movies['results']) && count($movies['results']) > 0) {
                foreach ($movies['results'] as $movie) {
                    $movieId = $movie['id']; // Get the movie ID
                    $title = $movie['title'];
                    $overview = $movie['overview'];
                    $posterPath = $movie['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : "https://via.placeholder.com/200x300?text=No+Image";

                    echo "<div class='movie-card'>";
                    echo "<a href='movie_detail.php?id={$movieId}'>";
                    echo "<img src='{$posterPath}' alt='{$title} poster'>";
                    echo "<h3>{$title}</h3>";
                    echo "</a>";
                    echo "<p>{$overview}</p>";
                    echo "</div>";
                }
            } else {
                echo "<p style='text-align: center;'>No movies found.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Pagination Section -->
    <div class="pagination">
        <?php
        $totalPages = $movies['total_pages'];
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Display "First" link
        if ($currentPage > 1) {
            echo "<a href='?page=1&query=" . urlencode($searchQuery) . "' class='pagination-button'>First</a>";
        }

        // Display previous link
        if ($currentPage > 1) {
            echo "<a href='?page=" . ($currentPage - 1) . "&query=" . urlencode($searchQuery) . "' class='pagination-button'>Previous</a>";
        }

        // Display pagination links around the current page
        for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++) {
            if ($i == $currentPage) {
                echo "<span class='pagination-button active'>{$i}</span>";
            } else {
                echo "<a href='?page={$i}&query=" . urlencode($searchQuery) . "' class='pagination-button'>{$i}</a>";
            }
        }

        // Display next link
        if ($currentPage < $totalPages) {
            echo "<a href='?page=" . ($currentPage + 1) . "&query=" . urlencode($searchQuery) . "' class='pagination-button'>Next</a>";
        }

        // Display "Last" link
        if ($currentPage < $totalPages) {
            echo "<a href='?page={$totalPages}&query=" . urlencode($searchQuery) . "' class='pagination-button'>Last</a>";
        }
        ?>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> LOUI MOVIES. All rights reserved.</p>
    </footer>

</body>

</html>