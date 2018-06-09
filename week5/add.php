<?php
session_start();

if (!isset($_SESSION['name'])) {
    die('ACCESS DENIED');
}

require_once "pdo.php";

if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year'])
    && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION['error'] = 'All values are required';
        header("Location: add.php");
        return;
    } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = "Mileage and year must be numeric";
        header("Location: add.php");
        return;
    }
    {
        $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage) VALUES ( :make, :model, :year, :mileage)');
        $stmt->execute(array(
                ':make' => $_POST['make'],
                ':model' => $_POST['model'],
                ':year' => $_POST['year'],
                ':mileage' => $_POST['mileage'])
        );
        $_SESSION['success'] = "Record added.";
        header("Location: index.php");
        return;
    }
} ?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <title>Ziang Cui 's Login Page</title>
</head>
<body>
<div class="container">
    <h1>Tracking Autos for <?php echo $_SESSION['name']; ?></h1>
    <?php
    // Note triple not equals and think how badly double
    // not equals would work here...
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    <form method="post">
        <p>Make:

            <input type="text" name="make" size="40"/></p>
        <p>Model:

            <input type="text" name="model" size="40"/></p>
        <p>Year:

            <input type="text" name="year" size="10"/></p>
        <p>Mileage:

            <input type="text" name="mileage" size="10"/></p>
        <input type="submit" name='add' value="Add">
        <input type="submit" name="cancel" value="Cancel">
    </form>


</div>
</body>
</html>