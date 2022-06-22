<?php
    // index.php
    $connection = new PDO(
        "mysql:host=127.0.0.1;dbname=blog_db", 
        'myuser', 
        'mypassword'
    );
    $result = $connection->query('SELECT id, title FROM post');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice MVC</title>
</head>
<body>
    <h1>Une liste de posts</h1>
    <ul>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <li>
            <?= $row['title'] ?>
        </li>
        <?php endwhile ?>
    </ul>    
</body>
</html>

<?php
$connection = null;
?>