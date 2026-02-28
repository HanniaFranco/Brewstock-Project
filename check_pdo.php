<?php
echo "Available PDO drivers:\n";
$drivers = PDO::getAvailableDrivers();
if (empty($drivers)) {
    echo "No PDO drivers found!\n";
} else {
    foreach ($drivers as $driver) {
        echo "- " . $driver . "\n";
    }
}
?>
