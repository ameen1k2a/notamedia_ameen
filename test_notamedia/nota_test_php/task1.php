<?php

/**
 * Find and display all files in the /datafiles folder with names consisting
 * of numbers and letters of the Latin alphabet, having the .ixt extension.
 * The file names are displayed ordered by name.
 */
function findAndDisplayFiles()
{
    // Set the directory to search files in
    $directory = __DIR__ . '/datafiles';

    // Check if the directory exists
    if (!is_dir($directory)) {
        echo "Directory not found!" . PHP_EOL;
        return;
    }

    // Scan the directory for files
    $files = scandir($directory);
    $matchedFiles = [];

    // Regular expression to match files with names consisting of numbers and letters of the Latin alphabet, having the .ixt extension
    $pattern = '/^[a-zA-Z0-9]+\.ixt$/';

    // Iterate over the files and match them using the regular expression
    foreach ($files as $file) {
        if (preg_match($pattern, $file)) {
            $matchedFiles[] = $file;
        }
    }

    // Sort the matched files by name
    sort($matchedFiles);

    // Display the matched files
    foreach ($matchedFiles as $file) {
        echo $file ."<br>";
    }
}

// Call the function to find and display files
findAndDisplayFiles();

?>
