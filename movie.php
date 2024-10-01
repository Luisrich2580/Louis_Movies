<?php
// Use your actual API key here
$apiKey = "9ae88cbf0e126575922d9262d6dbaccf";

if (isset($_GET['query'])) {
    $query = urlencode($_GET['query']); // Movie search query

    // Construct the API URL
    $apiUrl = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query={$query}";

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($ch);
    curl_close($ch);

    // Convert the JSON response to a PHP array
    $moviesData = json_decode($response, true);

    // Check if any movies were found
    if (isset($moviesData['results']) && count($moviesData['results']) > 0) {
        echo "<div style='text-align: center; font-family: Arial; padding: 20px;'>";

        // Loop through the first 10 movies (you can adjust this number)
        foreach ($moviesData['results'] as $index => $movie) {
            if ($index >= 10) break; // Show only the first 10 movies
            
            $title = $movie['title'];
            $releaseDate = $movie['release_date'];
            $overview = $movie['overview'];
            $posterPath = $movie['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : null;

            // Display movie information
            echo "<div style='display: inline-block; margin: 10px; text-align: left; width: 200px;'>";
            if ($posterPath) {
                echo "<img src='{$posterPath}' alt='{$title} poster' style='width: 100%; height: auto;'>";
            }
            echo "<h3>{$title}</h3>";
            echo "<p><strong>Release Date:</strong> {$releaseDate}</p>";
            echo "<p style='overflow: hidden; height: 50px; text-overflow: ellipsis;'>{$overview}</p>";
            echo "</div>";
        }

        echo "</div>";

        echo "<div style='text-align: center; margin-top: 20px;'>";
        echo "<a href='index.php' style='text-decoration: none; color: blue;'>Search again</a>";
        echo "</div>";
    } else {
        // Redirect back with an error message if no movies were found
        $error = "No results found for \"" . htmlspecialchars($_GET['query']) . "\".";
        header("Location: index.php?error=" . urlencode($error));
        exit;
    }
}
?>
