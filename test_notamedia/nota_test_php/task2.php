<?php

/**
 * Downloads the Wikipedia homepage, extracts headings, abstracts, pictures, and links,
 * and saves the extracted data into the wiki_sections table.
 */
function downloadAndParseWikipedia()
{
    // Database connection details
    $dbHost = 'localhost';
    $dbName = 'notamedia';
    $dbUser = 'root';
    $dbPass = '';

    // URL of the Wikipedia homepage
    $url = 'https://www.wikipedia.org/';

    // Download the Wikipedia homepage
    $htmlContent = downloadPage($url);

    if (!$htmlContent) {
        echo "Failed to download the page.";
        return;
    }

    // Extract sections from the HTML content
    $sections = extractSections($htmlContent);

    // Save extracted sections to the database
    saveSectionsToDatabase($sections, $dbHost, $dbName, $dbUser, $dbPass);
}

/**
 * Downloads a webpage and returns the HTML content.
 *
 * @param string $url The URL of the webpage to download.
 * @return string|false The HTML content of the downloaded page or false on failure.
 */
function downloadPage($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $htmlContent = curl_exec($ch);
    curl_close($ch);

    return $htmlContent;
}

/**
 * Extracts sections (headings, abstracts, pictures, and links) from HTML content.
 *
 * @param string $htmlContent The HTML content to parse.
 * @return array An array of extracted sections.
 */
function extractSections($htmlContent)
{
    $dom = new DOMDocument();
    @$dom->loadHTML($htmlContent);

    $xpath = new DOMXPath($dom);

    $sections = [];

    // XPath queries to extract data from <h1>, <h2>, and <strong> tags, <p> tags, <img> tags, and <a> tags
    //$headings = $xpath->query('//h1 | //h2 | //strong');
    $headings = $xpath->query('//h1 | //h2 | //strong');
    $abstracts = $xpath->query('//p');
    $pictures = $xpath->query('//img');
    $links = $xpath->query('//a');
    
    foreach ($headings as $index => $heading) {
       
        $title = substr($heading->nodeValue, 0, 230);
        $abstract = isset($abstracts[$index]) ? substr($abstracts[$index]->nodeValue, 0, 256) : '';
        $picture = isset($pictures[$index]) ? substr($pictures[$index]->getAttribute('src'), 0, 240) : '';
        $url = isset($links[$index]) ? substr($links[$index]->getAttribute('href'), 0, 240) : '';

        // Store empty picture URLs as NULL
        if (empty($picture)) {
            $picture = NULL;
        }

        $section = [
            'title' => $title,
            'abstract' => $abstract,
            'picture' => $picture,
            'url' => $url
        ];

        $sections[] = $section;
    }

    return $sections;
}

/**
 * Saves extracted sections to the database.
 *
 * @param array $sections An array of sections to save.
 * @param string $dbHost The database host.
 * @param string $dbName The database name.
 * @param string $dbUser The database user.
 * @param string $dbPass The database password.
 */
function saveSectionsToDatabase($sections, $dbHost, $dbName, $dbUser, $dbPass)
{
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $checkStmt = $pdo->prepare('SELECT COUNT(*) FROM wiki_sections WHERE picture = :picture OR url = :url OR abstract = :abstract');

        $stmt = $pdo->prepare('INSERT INTO wiki_sections (date_created, title, url, picture, abstract) VALUES (:date_created, :title, :url, :picture, :abstract)');

        foreach ($sections as $section) {
            // Check for existing entries with the same picture, url or abstract
            $checkStmt->execute([
                'picture' => $section['picture'],
                'url' => $section['url'],
                'abstract' => $section['abstract']
            ]);

            $count = $checkStmt->fetchColumn();

            if ($count == 0) {
                try {
                    $stmt->execute([
                        'date_created' => date('Y-m-d H:i:s'),
                        'title' => $section['title'],
                        'url' => $section['url'],
                        'picture' => $section['picture'],
                        'abstract' => $section['abstract']
                    ]);
                } catch (PDOException $e) {
                    // Handle duplicate entry
                    if ($e->getCode() == 23000) {
                        echo "Duplicate entry found for picture URL: " . $section['picture'] . "\n";
                    } else {
                        throw $e;
                    }
                }
            } else {
                echo "Skipping duplicate entry for picture URL: " . $section['picture'] . "\n";
            }
        }

        echo "Sections saved successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Call the function to download, parse, and save Wikipedia sections
downloadAndParseWikipedia();

?>
