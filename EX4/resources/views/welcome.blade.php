<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Suivi des Stocks en Temps Réel</title>
    <style>
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input {
            padding: 8px;
            margin-right: 10px;
            width: 200px;
        }
        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
            margin-left: 10px;
        }
        .delete-btn:hover {
            background-color: #da190b;
        }
        .stock-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Suivi des Stocks en Temps Réel</h1>
        
        <!-- Formulaire d'ajout -->
        <form id="addStockForm" class="form-group">
            <input name="product" placeholder="Nom du produit" required>
            <input name="qty" type="number" placeholder="Quantité" required min="0">
            <button type="submit">Ajouter Produit</button>
        </form>

        <!-- Liste des stocks -->
        <div id="stockList"></div>

        <!-- Conteneur pour le graphique -->
        <div id="container" style="height: 400px;"></div>
    </div>

    <!-- Scripts -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        // Initialisation du graphique Highcharts
        const chart = Highcharts.chart('container', {
            chart: { type: 'column' },
            title: { text: 'Stock en temps réel' },
            xAxis: { categories: [] },
            series: [{ name: 'Quantité', data: [] }]
        });

        // Récupérer les stocks
        async function fetchStocks() {
            try {
                const response = await fetch('/stocks');
                if (!response.ok) throw new Error('Erreur lors de la récupération');
                const stocks = await response.json();
                updateChart(stocks);
                updateStockList(stocks);
            } catch (error) {
                console.error('Erreur fetch stocks:', error);
            }
        }

        // Mettre à jour le graphique
        function updateChart(stocks) {
            chart.xAxis[0].setCategories(stocks.map(s => s.product_name));
            chart.series[0].setData(stocks.map(s => s.quantity));
        }

        // Mettre à jour la liste des stocks
        function updateStockList(stocks) {
            const stockList = document.getElementById('stockList');
            stockList.innerHTML = stocks.map(stock => `
                <div class="stock-item">
                    <span>${stock.product_name} (Quantité: ${stock.quantity})</span>
                    <button class="delete-btn" onclick="deleteStock(${stock.id})">Supprimer</button>
                </div>
            `).join('');
        }

        // Configuration Pusher
        const pusher = new Pusher('0ce917774fec2bf3f916', {
            cluster: 'mt1'
        });
        const channel = pusher.subscribe('stock-channel');
        channel.bind('App\\Events\\StockUpdated', (data) => {
            fetchStocks();
        });

        // Ajouter un produit
        async function addStock(productName, quantity) {
            try {
                const response = await fetch('/stocks', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({ product_name: productName, quantity })
                });
                if (!response.ok) throw new Error('Erreur lors de l\'ajout');
                fetchStocks(); // Rafraîchir le graphique et la liste
            } catch (error) {
                console.error('Erreur ajout stock:', error);
                alert('Erreur lors de l\'ajout du produit');
            }
        }

        // Supprimer un produit
        async function deleteStock(id) {
            if (confirm('Voulez-vous vraiment supprimer ce produit ?')) {
                try {
                    const response = await fetch(`/stocks/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        }
                    });
                    if (!response.ok) throw new Error('Erreur lors de la suppression');
                    fetchStocks(); // Rafraîchir le graphique et la liste
                } catch (error) {
                    console.error('Erreur suppression stock:', error);
                    alert('Erreur lors de la suppression du produit');
                }
            }
        }

        // Gestion du formulaire
        document.getElementById('addStockForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const productName = e.target.product.value;
            const quantity = parseInt(e.target.qty.value);
            addStock(productName, quantity);
            e.target.reset();
        });

        // Charger les stocks au démarrage
        fetchStocks();
    </script>
</body>
</html>