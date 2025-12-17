<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $commande->ticket->idTicket ?? 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .ticket {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 30px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .restaurant-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .ticket-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 5px;
            background-color: #f0f0f0;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
            padding: 5px 0;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .items-table thead {
            background-color: #f0f0f0;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .items-table th {
            font-weight: bold;
        }
        
        .text-right {
            text-align: right;
        }
        
        .total-section {
            margin-top: 20px;
            border-top: 2px solid #000;
            padding-top: 15px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            border-top: 1px dashed #000;
            padding-top: 15px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .status-en-attente {
            background-color: #ffd700;
            color: #000;
        }
        
        .status-en-cours {
            background-color: #87ceeb;
            color: #000;
        }
        
        .status-terminee {
            background-color: #90ee90;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- En-tête -->
        <div class="header">
            <div class="restaurant-name">🍽️ RESTAURANT</div>
            <div>Adresse du restaurant</div>
            <div>Téléphone: +32 XXX XX XX XX</div>
            <div class="ticket-title">TICKET DE CAISSE</div>
        </div>

        <!-- Informations du ticket -->
        <div class="section">
            <div class="section-title">INFORMATIONS DU TICKET</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Numéro de ticket:</div>
                    <div class="info-value">#{{ $commande->ticket->idTicket ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Numéro de commande:</div>
                    <div class="info-value">#{{ $commande->idCommande }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($commande->horaire)->format('d/m/Y à H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Table:</div>
                    <div class="info-value">Table {{ $commande->table->numero ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Statut:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $commande->statut)) }}">
                            {{ $commande->statut }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations client -->
        <div class="section">
            <div class="section-title">CLIENT</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nom:</div>
                    <div class="info-value">{{ $commande->client?->nom ?? 'Inconnu' }} {{ $commande->client?->prenom }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $commande->client->email ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Téléphone:</div>
                    <div class="info-value">{{ $commande->client->numero ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Articles commandés -->
        <div class="section">
            <div class="section-title">ARTICLES</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Type</th>
                        <th class="text-right">P.U.</th>
                        <th class="text-right">Qté</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Plats -->
                    @foreach($commande->composer as $composer)
                        <tr>
                            <td>{{ $composer->plat->nom }}</td>
                            <td>Plat</td>
                            <td class="text-right">{{ number_format($composer->plat->prix, 2) }} €</td>
                            <td class="text-right">{{ $composer->quantite }}</td>
                            <td class="text-right">
                                <strong>{{ number_format($composer->plat->prix * $composer->quantite, 2) }} €</strong>
                            </td>
                        </tr>
                    @endforeach

                    <!-- Boissons -->
                    @foreach($commande->contenir as $contenir)
                        <tr>
                            <td>{{ $contenir->boisson->nom }}</td>
                            <td>Boisson</td>
                            <td class="text-right">{{ number_format($contenir->boisson->prix, 2) }} €</td>
                            <td class="text-right">{{ $contenir->quantite }}</td>
                            <td class="text-right">
                                <strong>{{ number_format($contenir->boisson->prix * $contenir->quantite, 2) }} €</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="total-section">
            <div class="total-row">
                <span>TOTAL À PAYER:</span>
                <span>{{ number_format($commande->ticket->prix ?? 0, 2) }} €</span>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>Merci de votre visite !</p>
            <p>TVA BE XXXXXXXXX</p>
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>
