<?php

use App\Models\Client;
use App\Models\Commande;
use App\Models\HoraireReservation;

// Load Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- DEBUG START ---\n";

// 1. Check Client
$client = Client::first();
if ($client) {
    echo "Client Attributes: " . implode(', ', array_keys($client->getAttributes())) . "\n";
    echo "Client Primary Key Name: " . $client->getKeyName() . "\n";
    echo "Client Key Value: " . var_export($client->getKey(), true) . "\n";
} else {
    echo "No Client found.\n";
}

echo "----------------\n";

// 2. Check Commande
$commande = Commande::first();
if ($commande) {
    echo "Commande Attributes: " . implode(', ', array_keys($commande->getAttributes())) . "\n";
    echo "Commande Primary Key Name: " . $commande->getKeyName() . "\n";
    echo "Commande Key Value: " . var_export($commande->getKey(), true) . "\n";
} else {
    echo "No Commande found.\n";
}

echo "----------------\n";

// 3. Check HoraireReservation
$reservation = HoraireReservation::first();
if ($reservation) {
    echo "Reservation Attributes: " . implode(', ', array_keys($reservation->getAttributes())) . "\n";
    echo "Reservation Primary Key Name: " . $reservation->getKeyName() . "\n";
    echo "Reservation Key Value: " . var_export($reservation->getKey(), true) . "\n";
} else {
    echo "No Reservation found.\n";
}

echo "--- DEBUG END ---\n";
