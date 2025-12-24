<?php
// Test login credentials
$mysqli = new mysqli("localhost", "root", "", "event_management");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$email = "admin@bikinevent.my.id";
$password = "admin123";

$result = $mysqli->query("SELECT * FROM users WHERE email = '$email'");
$user = $result->fetch_assoc();

echo "=== TEST LOGIN ===\n\n";
echo "Email: $email\n";
echo "Password to test: $password\n\n";

if ($user) {
    echo "User found in database:\n";
    echo "- ID: " . $user['id'] . "\n";
    echo "- Name: " . $user['name'] . "\n";
    echo "- Role: " . $user['role'] . "\n";
    echo "- Password Hash: " . $user['password'] . "\n\n";
    
    if (password_verify($password, $user['password'])) {
        echo "✅ PASSWORD MATCH! Login should work.\n";
    } else {
        echo "❌ PASSWORD DOES NOT MATCH! Login will fail.\n";
        echo "\nGenerating new hash for '$password':\n";
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        echo $newHash . "\n\n";
        echo "To fix, run this SQL:\n";
        echo "UPDATE users SET password = '$newHash' WHERE email = '$email';\n";
    }
} else {
    echo "❌ User not found in database!\n";
}

$mysqli->close();

