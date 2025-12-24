<?php
// Fix passwords in database
$mysqli = new mysqli("localhost", "root", "", "event_management");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Generate correct password hashes
$adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
$pesertaPassword = password_hash('peserta123', PASSWORD_DEFAULT);

echo "Fixing passwords...\n\n";

// Update admin password
$stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE email = ?");
$email = 'admin@bikinevent.my.id';
$stmt->bind_param("ss", $adminPassword, $email);
if ($stmt->execute()) {
    echo "✅ Admin password updated successfully\n";
    echo "   Email: admin@bikinevent.my.id\n";
    echo "   Password: admin123\n";
    echo "   Hash: $adminPassword\n\n";
} else {
    echo "❌ Failed to update admin password\n\n";
}

// Update peserta password
$email = 'peserta@bikinevent.my.id';
$stmt->bind_param("ss", $pesertaPassword, $email);
if ($stmt->execute()) {
    echo "✅ Peserta password updated successfully\n";
    echo "   Email: peserta@bikinevent.my.id\n";
    echo "   Password: peserta123\n";
    echo "   Hash: $pesertaPassword\n\n";
} else {
    echo "❌ Failed to update peserta password\n\n";
}

// Verify
echo "Verifying passwords...\n\n";

$result = $mysqli->query("SELECT email, password FROM users");
while ($user = $result->fetch_assoc()) {
    $testPassword = ($user['email'] == 'admin@bikinevent.my.id') ? 'admin123' : 'peserta123';
    $match = password_verify($testPassword, $user['password']);
    echo ($match ? "✅" : "❌") . " " . $user['email'] . " - " . ($match ? "OK" : "FAILED") . "\n";
}

$stmt->close();
$mysqli->close();

echo "\n✅ Done! You can now login.\n";

