<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adventure";

// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$isError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($country)) {
        $message = "Please fill in all required fields (Name, Email, Country).";
        $isError = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
        $isError = true;
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, country, remarks) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            $message = "Prepare failed: " . htmlspecialchars($conn->error); // Sanitize DB error message
            $isError = true;
        } else {
            $stmt->bind_param("ssss", $name, $email, $country, $remarks);

            // Execute
            if ($stmt->execute()) {
                $message = "Message sent successfully!";
            } else {
                $message = "Execute failed: " . htmlspecialchars($stmt->error); // Sanitize DB error message
                $isError = true;
            }
            $stmt->close();
        }
    }
}

// Fetch all contacts to show in backend
$result = $conn->query("SELECT * FROM contacts ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Contact</title>
    <style>
        form { max-width: 400px; margin-bottom: 30px; }
        label { display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 8px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        .message { margin-bottom: 20px; font-weight: bold; color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<h2>Contact Form</h2>

<?php if ($message): ?>
    <div class="<?= $isError ? 'error' : 'message' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <label for="name">Name *</label>
    <input type="text" name="name" id="name" required>

    <label for="email">Email *</label>
    <input type="email" name="email" id="email" required>

    <label for="country">Country *</label>
    <input type="text" name="country" id="country" required>

    <label for="remarks">Remarks</label>
    <textarea name="remarks" id="remarks"></textarea>

    <button type="submit">Submit</button>
</form>

<h2>Contacts in Database</h2>

<?php if ($result && $result->num_rows > 0): ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Country</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['country']) ?></td>
            <td><?= htmlspecialchars($row['remarks']) ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <p>No contacts found.</p>
<?php endif; ?>

<?php $conn->close(); ?>

</body>
</html>
