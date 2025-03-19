<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion des Salles</title>
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input {
            padding: 8px;
            margin-right: 10px;
            width: 200px;
        }
        button {
            padding: 8px 15px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }
        button:hover {
            background-color: #45a049;
        }
        .room-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #da190b;
        }
        .edit-btn {
            background-color: #2196F3;
            margin-right: 5px;
        }
        .edit-btn:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Salles</h1>
        
        <!-- Formulaire de création -->
        <form id="createRoomForm" class="form-group">
            <input type="text" id="roomName" placeholder="Nom de la salle" required>
            <input type="number" id="roomCapacity" placeholder="Capacité" required min="1">
            <button type="submit">Ajouter Salle</button>
        </form>

        <!-- Liste des salles -->
        <div id="roomList"></div>
    </div>

    <script>
        // Fonctions CRUD ajustées pour les routes dans web.php
        async function createRoom(name, capacity) {
            try {
                const response = await fetch('/rooms', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content // Si CSRF est activé
                    },
                    body: JSON.stringify({ name, capacity })
                });
                if (!response.ok) throw new Error('Erreur lors de la création');
                fetchRooms(); // Rafraîchir la liste
            } catch (error) {
                console.error('Erreur création:', error);
                alert('Erreur lors de la création de la salle');
            }
        }

        async function fetchRooms() {
            try {
                const response = await fetch('/rooms');
                if (!response.ok) throw new Error('Erreur lors de la récupération');
                const rooms = await response.json();
                
                const roomList = document.getElementById('roomList');
                roomList.innerHTML = rooms.map(room => `
                    <div class="room-item">
                        <span>${room.name} (Capacité: ${room.capacity})</span>
                        <div>
                            <button class="edit-btn" onclick="editRoom(${room.id}, '${room.name}', ${room.capacity})">Modifier</button>
                            <button class="delete-btn" onclick="deleteRoom(${room.id})">Supprimer</button>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Erreur fetch rooms:', error);
                alert('Erreur lors de la récupération des salles');
            }
        }

        async function updateRoom(id, name, capacity) {
            try {
                const response = await fetch(`/rooms/${id}`, {
                    method: 'PUT',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content // Si CSRF est activé
                    },
                    body: JSON.stringify({ name, capacity })
                });
                if (!response.ok) throw new Error('Erreur lors de la mise à jour');
                fetchRooms(); // Rafraîchir la liste
            } catch (error) {
                console.error('Erreur mise à jour:', error);
                alert('Erreur lors de la mise à jour de la salle');
            }
        }

        async function deleteRoom(id) {
            if (confirm('Voulez-vous vraiment supprimer cette salle ?')) {
                try {
                    const response = await fetch(`/rooms/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content // Si CSRF est activé
                        }
                    });
                    if (!response.ok) throw new Error('Erreur lors de la suppression');
                    fetchRooms(); // Rafraîchir la liste
                } catch (error) {
                    console.error('Erreur suppression:', error);
                    alert('Erreur lors de la suppression de la salle');
                }
            }
        }

        // Gestion du formulaire
        document.getElementById('createRoomForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('roomName').value;
            const capacity = parseInt(document.getElementById('roomCapacity').value);
            createRoom(name, capacity);
            e.target.reset(); // Réinitialiser le formulaire
        });

        // Fonction d'édition
        function editRoom(id, currentName, currentCapacity) {
            const name = prompt('Nouveau nom:', currentName);
            const capacity = parseInt(prompt('Nouvelle capacité:', currentCapacity));
            if (name && !isNaN(capacity) && capacity > 0) {
                updateRoom(id, name, capacity);
            }
        }

        // Charger les salles au démarrage
        fetchRooms();
    </script>
</body>
</html>