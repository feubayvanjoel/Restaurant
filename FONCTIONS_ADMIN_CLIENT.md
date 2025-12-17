# Fonctions des Administrateurs et Clients

Ce document décrit les fonctions disponibles pour les administrateurs et les clients dans le système de gestion de restaurant.

## Fonctions des Administrateurs

Les administrateurs ont accès à toutes les fonctionnalités de gestion du restaurant. Les fonctions sont définies dans le modèle `App\Models\Administrateur`.

### Fonctions de base (tous les administrateurs)

Tous les administrateurs peuvent :

1. **Gérer le stock** (`canGererStock()`)
   - Consulter les quantités de plats et boissons
   - Mettre à jour les stocks
   - Ajouter de nouveaux produits

2. **Gérer les commandes** (`canGererCommandes()`)
   - Voir toutes les commandes
   - Modifier le statut des commandes
   - Annuler des commandes
   - Voir les détails des commandes

3. **Gérer les réservations** (`canGererReservations()`)
   - Voir toutes les réservations
   - Confirmer/Annuler des réservations
   - Gérer les réservations expirées

4. **Gérer les tables** (`canGererTables()`)
   - Voir le statut de toutes les tables
   - Modifier le statut des tables (Libre, Occupee, Reservee)
   - Libérer des tables

5. **Gérer les clients** (`canGererClients()`)
   - Voir la liste des clients
   - Modifier les informations des clients
   - Créer de nouveaux clients

6. **Voir les statistiques** (`canVoirStatistiques()`)
   - Statistiques de ventes
   - Statistiques de réservations
   - Statistiques de fréquentation

### Fonctions avec accès spécifique

Certaines fonctions nécessitent des accès spécifiques :

1. **Supprimer** (`canSupprimer()`)
   - Nécessite l'accès `SUPPRESSION`
   - Permet de supprimer des enregistrements (commandes, réservations, clients, etc.)

2. **Imprimer** (`canImprimer()`)
   - Nécessite l'accès `IMPRESSION`
   - Permet d'imprimer des tickets, factures, rapports

### Utilisation dans les Controllers

```php
use App\Models\Administrateur;

// Dans un controller
public function supprimerCommande($id)
{
    $admin = auth()->user(); // Supposant que l'admin est authentifié
    
    if (!$admin->canSupprimer()) {
        return redirect()->back()->with('error', 'Vous n\'avez pas les droits pour supprimer');
    }
    
    // Logique de suppression
}

public function imprimerTicket($id)
{
    $admin = auth()->user();
    
    if (!$admin->canImprimer()) {
        return redirect()->back()->with('error', 'Vous n\'avez pas les droits pour imprimer');
    }
    
    // Logique d'impression
}
```

## Fonctions des Clients

Les clients ont accès aux fonctionnalités de base du restaurant. Les fonctions sont définies dans le modèle `App\Models\Client`.

### Fonctions de base (tous les clients)

Tous les clients peuvent :

1. **Réserver une table** (`canReserver()`)
   - Consulter les tables disponibles
   - Créer une réservation
   - Voir leurs réservations
   - Annuler leurs réservations

2. **Passer une commande** (`canCommander()`)
   - Consulter le menu (plats et boissons)
   - Créer une commande
   - Voir leurs commandes
   - Suivre le statut de leurs commandes

### Fonctions avec accès spécifique

1. **Imprimer** (`canImprimer()`)
   - Nécessite l'accès `IMPRESSION`
   - Permet d'imprimer leurs tickets de commande

### Utilisation dans les Controllers

```php
use App\Models\Client;

// Dans un controller
public function creerReservation(Request $request)
{
    $client = auth()->user(); // Supposant que le client est authentifié
    
    if (!$client->canReserver()) {
        return redirect()->back()->with('error', 'Vous ne pouvez pas réserver');
    }
    
    // Logique de création de réservation
}

public function passerCommande(Request $request)
{
    $client = auth()->user();
    
    if (!$client->canCommander()) {
        return redirect()->back()->with('error', 'Vous ne pouvez pas commander');
    }
    
    // Logique de création de commande
}

public function imprimerTicket($id)
{
    $client = auth()->user();
    
    if (!$client->canImprimer()) {
        return redirect()->back()->with('error', 'Vous n\'avez pas les droits pour imprimer');
    }
    
    // Logique d'impression
}
```

## Vérification des accès dans les vues (Blade)

```blade
{{-- Vérifier si l'utilisateur peut supprimer --}}
@if(auth()->user()->canSupprimer())
    <button type="button" class="btn btn-danger">Supprimer</button>
@endif

{{-- Vérifier si l'utilisateur peut imprimer --}}
@if(auth()->user()->canImprimer())
    <button type="button" class="btn btn-primary">Imprimer</button>
@endif

{{-- Pour les clients --}}
@if(auth()->user()->canReserver())
    <a href="{{ route('reservation.create') }}" class="btn btn-success">Réserver</a>
@endif
```

## Méthodes utilitaires des modèles

### Client

- `hasAcces(string $accesNom)`: Vérifie si le client possède un accès spécifique
- `canReserver()`: Vérifie si le client peut réserver
- `canCommander()`: Vérifie si le client peut commander
- `canImprimer()`: Vérifie si le client peut imprimer

### Administrateur

- `hasAcces(string $accesNom)`: Vérifie si l'administrateur possède un accès spécifique
- `canSupprimer()`: Vérifie si l'administrateur peut supprimer
- `canImprimer()`: Vérifie si l'administrateur peut imprimer
- `canGererStock()`: Vérifie si l'administrateur peut gérer le stock
- `canGererCommandes()`: Vérifie si l'administrateur peut gérer les commandes
- `canGererReservations()`: Vérifie si l'administrateur peut gérer les réservations
- `canGererTables()`: Vérifie si l'administrateur peut gérer les tables
- `canGererClients()`: Vérifie si l'administrateur peut gérer les clients
- `canVoirStatistiques()`: Vérifie si l'administrateur peut voir les statistiques

### Commande

- `calculerTotal()`: Calcule le total de la commande (plats + boissons)
- `isConfirmee()`: Vérifie si la commande est confirmée
- `isTerminee()`: Vérifie si la commande est terminée
- `isAnnulee()`: Vérifie si la commande est annulée

### GestionSalle

- `isLibre()`: Vérifie si la table est libre
- `isOccupee()`: Vérifie si la table est occupée
- `isReservee()`: Vérifie si la table est réservée

### Reservation

- `isExpiree()`: Vérifie si la réservation est expirée
- `isConfirmee()`: Vérifie si la réservation est confirmée

## Notes importantes

1. Les mots de passe sont hashés avec bcrypt lors de la création via les seeders
2. Les accès sont gérés via les tables `acces`, `gerer` (pour les admins) et `posseder` (pour les clients)
3. Les triggers SQL du script original sont remplacés par la logique Laravel dans les modèles et seeders
4. Les relations Eloquent permettent d'accéder facilement aux données liées

