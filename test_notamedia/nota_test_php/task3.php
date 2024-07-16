<?php

/**
 * A class to create, fill, and retrieve data from a MySQL table named 'Test'.
 * This class cannot be inherited.
 */
final class TableCreator
{
    // Database connection parameters
    private $dbHost = 'localhost';
    private $dbName = 'notamedia';
    private $dbUser = 'root';
    private $dbPass = '';
    private $pdo;

    /**
     * Constructor to initialize the database connection and execute the create and fill methods.
     */
    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->create();
        $this->fill();
    }

    /**
     * Creates the table 'Test' with specified fields.
     * Accessible only within the class.
     */
    private function create()
    {
        $sql = "CREATE TABLE IF NOT EXISTS Test (
            id INT AUTO_INCREMENT PRIMARY KEY,
            script_name VARCHAR(25),
            start_time DATETIME,
            end_time DATETIME,
            result ENUM('normal', 'illegal', 'failed', 'success')
        )";
        
        $this->pdo->exec($sql);
    }

    /**
     * Fills the 'Test' table with random data.
     * Accessible only within the class.
     */
    private function fill()
    {
        $results = ['normal', 'illegal', 'failed', 'success'];
        $stmt = $this->pdo->prepare("INSERT INTO Test (script_name, start_time, end_time, result) VALUES (?, ?, ?, ?)");

        for ($i = 0; $i < 10; $i++) {
            $script_name = 'script' . rand(1, 25);
            $start_time = date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'));
            $end_time = date('Y-m-d H:i:s', strtotime($start_time . ' + ' . rand(1, 24) . ' hours'));
            $result = $results[array_rand($results)];

            $stmt->execute([$script_name, $start_time, $end_time, $result]);
        }
    }

    /**
     * Retrieves data from the 'Test' table where the result is either 'normal' or 'success'.
     * Accessible from outside the class.
     *
     * @return array The selected data from the 'Test' table.
     */
    public function get()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Test WHERE result IN ('normal', 'success')");
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Instantiate the TableCreator class, which will automatically create and fill the table
$tableCreator = new TableCreator();

// Retrieve and print the data from the 'Test' table where the result is either 'normal' or 'success'
$data = $tableCreator->get();
print_r($data);

?>
