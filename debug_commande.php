<?php

use App\Models\Client;
use App\Models\Commande;

// Load Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find a client with commands
$client = Client::has('commandes')->first();

if (!$client) {
    echo "No client with commands found.\n";
    exit;
}

echo "Client ID: " . $client->getKey() . "\n";

$commande = $client->commandes()->first();

if (!$commande) {
    echo "No command found for client.\n";
    exit;
}

echo "Commande Attributes:\n";
print_r($commande->getAttributes());

echo "\nPrimary Key Name: " . $commande->getKeyName() . "\n";
echo "Primary Key Value (getKey()): " . $commande->getKey() . "\n";

if ($commande->getKey() === null) {
    echo "ERROR: getKey() returned null!\n";
} else {
    echo "SUCCESS: getKey() returned a value.\n";
}
