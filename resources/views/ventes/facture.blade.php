<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $vente->numero_vente }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }
        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        .header-right {
            text-align: right;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .facture-title {
            font-size: 28px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 5px;
        }
        .facture-numero {
            font-size: 14px;
            color: #7f8c8d;
        }
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .info-box {
            display: table-cell;
            width: 50%;
            padding: 15px;
            background: #ecf0f1;
            border-radius: 5px;
        }
        .info-box + .info-box {
            margin-left: 20px;
        }
        .info-box h3 {
            font-size: 14px;
            color: #2c3e50;
            margin-bottom: 10px;
            border-bottom: 2px solid #bdc3c7;
            padding-bottom: 5px;
        }
        .info-box p {
            margin: 5px 0;
            line-height: 1.6;
        }
        .table-container {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background: #34495e;
            color: white;
        }
        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #ecf0f1;
        }
        tbody tr:hover {
            background: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            width: 100%;
            margin-top: 20px;
        }
        .totals-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .totals-label, .totals-value {
            display: table-cell;
            padding: 8px 12px;
        }
        .totals-label {
            text-align: right;
            width: 70%;
            font-weight: bold;
        }
        .totals-value {
            text-align: right;
            width: 30%;
            background: #ecf0f1;
        }
        .total-final {
            background: #2c3e50;
            color: white;
            font-size: 16px;
        }
        .total-final .totals-value {
            background: #2c3e50;
            color: white;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
        }
        .remarques {
            margin-top: 20px;
            padding: 15px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .remarques h4 {
            color: #856404;
            margin-bottom: 8px;
        }
        .statut-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .statut-validee {
            background: #d4edda;
            color: #155724;
        }
        .statut-en-attente {
            background: #fff3cd;
            color: #856404;
        }
        .statut-annulee {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="header-left">
                <div class="company-name">Pharmacie</div>
                <p>Adresse de votre pharmacie</p>
                <p>Code Postal Ville</p>
                <p>Tél: +226 XX XX XX XX</p>
                <p>Email: contact@pharmacie.bf</p>
            </div>
            <div class="header-right">
                <div class="facture-title">FACTURE</div>
                <div class="facture-numero">{{ $vente->numero_vente }}</div>
                <p style="margin-top: 10px;">
                    <span class="statut-badge statut-{{ $vente->statut }}">
                        {{ ucfirst($vente->statut) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Informations client et vente -->
        <div class="info-section">
            <div class="info-box">
                <h3>CLIENT</h3>
                @if($vente->client)
                    <p><strong>{{ $vente->client->nom }} {{ $vente->client->prenom }}</strong></p>
                    @if($vente->client->telephone)
                        <p>Tél: {{ $vente->client->telephone }}</p>
                    @endif
                    @if($vente->client->email)
                        <p>Email: {{ $vente->client->email }}</p>
                    @endif
                    @if($vente->client->adresse)
                        <p>{{ $vente->client->adresse }}</p>
                    @endif
                @else
                    <p><em>Client non spécifié (Vente au comptoir)</em></p>
                @endif
            </div>
            <div style="width: 20px; display: table-cell;"></div>
            <div class="info-box">
                <h3>INFORMATIONS VENTE</h3>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($vente->date_vente)->format('d/m/Y H:i') }}</p>
                <p><strong>Vendeur:</strong> {{ $vente->user->name ?? 'Non spécifié' }}</p>
                <p><strong>Mode de paiement:</strong> {{ ucfirst(str_replace('_', ' ', $vente->mode_paiement)) }}</p>
                @if($vente->remise > 0)
                    <p><strong>Remise:</strong> {{ number_format($vente->remise, 0, ',', ' ') }} FCFA</p>
                @endif
            </div>
        </div>

        <!-- Tableau des produits -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40%;">Produit</th>
                        <th class="text-center" style="width: 15%;">Quantité</th>
                        <th class="text-right" style="width: 15%;">Prix Unit. HT</th>
                        <th class="text-center" style="width: 10%;">TVA</th>
                        <th class="text-right" style="width: 20%;">Total TTC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vente->lignesVentes as $ligne)
                        <tr>
                            <td>
                                <strong>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</strong>
                                @if($ligne->produit && $ligne->produit->description)
                                    <br><small style="color: #7f8c8d;">{{ Str::limit($ligne->produit->description, 60) }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $ligne->quantite }}</td>
                            <td class="text-right">{{ number_format($ligne->prix_unitaire_ht, 0, ',', ' ') }} FCFA</td>
                            <td class="text-center">{{ $ligne->taux_tva }}%</td>
                            <td class="text-right"><strong>{{ number_format($ligne->prix_total_ttc, 0, ',', ' ') }} FCFA</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totaux -->
        <div class="totals">
            <div class="totals-row">
                <div class="totals-label">Total HT:</div>
                <div class="totals-value">{{ number_format($vente->montant_ht, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="totals-row">
                <div class="totals-label">TVA:</div>
                <div class="totals-value">{{ number_format($vente->montant_tva, 0, ',', ' ') }} FCFA</div>
            </div>
            @if($vente->remise > 0)
                <div class="totals-row">
                    <div class="totals-label">Remise:</div>
                    <div class="totals-value">- {{ number_format($vente->remise, 0, ',', ' ') }} FCFA</div>
                </div>
            @endif
            <div class="totals-row total-final">
                <div class="totals-label">TOTAL À PAYER:</div>
                <div class="totals-value">{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>

        <!-- Remarques -->
        @if($vente->remarques)
            <div class="remarques">
                <h4>Remarques:</h4>
                <p>{{ $vente->remarques }}</p>
            </div>
        @endif

        <!-- Pied de page -->
        <div class="footer">
            <p><strong>Merci de votre confiance</strong></p>
            <p style="margin-top: 10px;">
                Cette facture est générée électroniquement et ne nécessite pas de signature.<br>
                Facture imprimée le {{ now()->format('d/m/Y à H:i') }}
            </p>
        </div>
    </div>
</body>
</html>