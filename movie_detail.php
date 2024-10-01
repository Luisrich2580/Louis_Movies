<?php
// Replace this with your actual API key from TMDb
$apiKey = "9ae88cbf0e126575922d9262d6dbaccf";

// Get the movie ID from the URL
$movieId = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Function to fetch movie details by ID
function getMovieDetails($apiKey, $movieId)
{
    if (!$movieId) {
        return null;
    }

    $apiUrl = "https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=en-US";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Function to fetch related or recommended movies
function getRelatedMovies($apiKey, $movieId)
{
    if (!$movieId) {
        return null;
    }

    $apiUrl = "https://api.themoviedb.org/3/movie/{$movieId}/recommendations?api_key={$apiKey}&language=en-US";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Fetch movie details and related movies
$movieDetails = getMovieDetails($apiKey, $movieId);
$relatedMovies = getRelatedMovies($apiKey, $movieId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movieDetails['title'] ?? 'Movie Detail'); ?> | LOUI MOVIES</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .movie-details {
            background-color: #fff;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: 20px auto;
            display: flex;
        }

        .movie-poster img {
            width: 300px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .movie-info {
            margin-left: 20px;
            max-width: 800px;
        }

        .movie-info h2 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .movie-info p {
            font-size: 16px;
            color: #555;
        }

        .movie-info .release-date,
        .genres,
        .runtime {
            font-size: 14px;
            color: #777;
            margin: 10px 0;
        }

        .related-movies {
            margin-top: 40px;
        }

        .related-movies h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .related-movies .movie-card {
            display: inline-block;
            width: 150px;
            margin-right: 15px;
            vertical-align: top;
        }

        .related-movies .movie-card img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .related-movies .movie-card h4 {
            font-size: 14px;
            margin: 10px 0 0;
            color: #333;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 20px;
        }

        .back-link {
            display: inline-block;
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .back-link:hover {
            background-color: #555;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <header>
        <div class="logo">LOUI MOVIES</div>
    </header>

    <?php if ($movieDetails): ?>
        <!-- Movie Details Section -->
        <div class="movie-details">
            <div class="movie-poster">
                <?php
                $posterPath = $movieDetails['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $movieDetails['poster_path'] : "https://via.placeholder.com/300x450?text=No+Image";
                echo "<img src='{$posterPath}' alt='{$movieDetails['title']} poster'>";
                ?>
            </div>
            <div class="movie-info">
                <h2><?php echo htmlspecialchars($movieDetails['title']); ?></h2>
                <p><?php echo htmlspecialchars($movieDetails['overview']); ?></p>

                <div class="release-date">Release Date: <?php echo htmlspecialchars($movieDetails['release_date']); ?></div>

                <div class="genres">
                    Genres:
                    <?php
                    $genres = array_map(function ($genre) {
                        return $genre['name'];
                    }, $movieDetails['genres']);
                    echo htmlspecialchars(implode(', ', $genres));
                    ?>
                </div>

                <div class="runtime">Runtime: <?php echo htmlspecialchars($movieDetails['runtime']); ?> minutes</div>
            </div>
        </div>

        <!-- Related Movies Section -->
        <div class="related-movies">
            <h3>Related Movies</h3>
            <?php
            if (!empty($relatedMovies['results'])) {
                foreach ($relatedMovies['results'] as $relatedMovie) {
                    $relatedPosterPath = $relatedMovie['poster_path'] ? "https://image.tmdb.org/t/p/w200" . $relatedMovie['poster_path'] : "https://via.placeholder.com/150x225?text=No+Image";
                    echo "<div class='movie-card'>";
                    echo "<a href='movie_detail.php?id={$relatedMovie['id']}'>";
                    echo "<img src='{$relatedPosterPath}' alt='{$relatedMovie['title']} poster'>";
                    echo "<h4>" . htmlspecialchars($relatedMovie['title']) . "</h4>";
                    echo "</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No related movies found.</p>";
            }
            ?>
        </div>

        <!-- Back Button -->
        <div style="text-align: center;">
            <a href="index.php" class="back-link">Back to Home</a>
        </div>

    <?php else: ?>
        <!-- Error Message if No Movie Found -->
        <div style="text-align: center; padding: 50px;">
            <h2>Movie not found.</h2>
            <a href="index.php" class="back-link">Back to Home</a>
        </div>
    <?php endif; ?>

    <!-- Footer Section -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> LOUI MOVIES. All rights reserved.</p>
    </footer>

</body>

</html>
