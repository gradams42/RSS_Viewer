<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RSS Reader</title>
</head>
<body>
    <?php
    // Define the RSS feed URL
    $rssUrl = "https://www.cshub.com/rss/news";

    // Define options for the HTTP context, specifically setting a user agent to mimic a browser request
    $options = [
        "http" => [
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
        ]
    ];

    // Create a stream context with the defined options
    $context = stream_context_create($options);

    // Attempt to fetch the RSS feed content using the custom context
    $rss = file_get_contents($rssUrl, false, $context);

    // Check if the fetch was successful
    if ($rss !== false) {
        // Parse the XML content of the RSS feed
        $rssFeed = simplexml_load_string($rss);

        // Ensure the feed was parsed successfully
        if (!empty($rssFeed)) {
            // Iterate through each item in the RSS feed
            foreach ($rssFeed->channel->item as $item) {
                // Extract title, link, and description from each item, casting them to string to avoid simplexml object issues
                $title = (string)$item->title;
                $link = (string)$item->link;
                $description = (string)$item->description;

                // Display the title as a link and the description, ensuring special characters are escaped to avoid XSS vulnerabilities
                echo "<div><a href='" . htmlspecialchars($link) . "'>" . htmlspecialchars($title) . "</a></div>";
                echo "<div>" . htmlspecialchars($description) . "</div><br>";
            }
        } else {
            // Handle errors in parsing the RSS feed
            echo "<p>Failed to parse the RSS feed.</p>";
        }
    } else {
        // Handle errors in fetching the RSS feed
        echo "<p>Failed to retrieve the RSS feed.</p>";
    }
    ?>
</body>
</html>
