# 🚀 Atelier 5 : Programmation Asynchrone

## 🎯 Objectif
L'objectif principal de ce travail pratique est de se familiariser avec les concepts de la programmation asynchrone en JavaScript et Laravel.

---

## 📌 Exercice 1 : Gestion des utilisateurs avec Promises
### 🛠️ Étapes :
1️⃣ **Création d'une fonction asynchrone** pour simuler la récupération de données utilisateur après un délai.

```js
const userData = {
    name: 'John Doe',
    email: 'johndoe@example.com',
    avatar: 'avatar.png',
    gender: 'M'
};
```

2️⃣ **Affichage des informations** sous forme de profil utilisateur.
3️⃣ **Ajout de nouvelles données** (login, mot de passe, adresse, etc.) et affichage sous forme de tableau.
4️⃣ **Chaînage des Promises** pour simuler une séquence d'actions asynchrones.

---

## 📂 Exercice 2 : Gestion de fichiers avec Laravel API
### 🔄 Étapes :
1️⃣ **Création d'une route et d'un contrôleur** pour le téléchargement de fichiers.
2️⃣ **Utilisation de `fetch`** pour envoyer une requête POST à l'API Laravel.
3️⃣ **Récupération des fichiers** avec une requête GET et affichage dynamique.
4️⃣ **Gestion des Promises** pour assurer l'affichage des données.

---

## 🏢 Exercice 3 : Gestion des salles avec API Laravel
### 🔧 Étapes :
1️⃣ **Création d'une migration pour les salles** `{id, name, capacity}`.
2️⃣ **Mise en place des méthodes CRUD** dans `RoomController`.
3️⃣ **Création des fonctions frontend** pour interagir avec l'API Laravel via `fetch`.
4️⃣ **Interface utilisateur dynamique** avec affichage des salles et formulaires interactifs.

---

## 📊 Exercice 4 : Suivi des stocks en temps réel
> **Remarque ⚠️ :** WebSockets n'a pas été implémenté en raison d'une incompatibilité avec PHP 8.

### 🔗 Étapes prévues :
1️⃣ Configuration de **Laravel WebSockets** et **Pusher**.
2️⃣ Création de la migration `stocks` `{id, product_name, quantity}`.
3️⃣ Implémentation des méthodes CRUD dans `StockController` avec émission d'événements `StockUpdated`.
4️⃣ Intégration de **Highcharts** pour afficher l'évolution des stocks en temps réel.

---

## 📝 Conclusion
Ce TP a permis de mieux comprendre la gestion des opérations asynchrones en JavaScript et leur interaction avec une API Laravel.
Malgré l'incompatibilité avec PHP 8 pour WebSockets, les autres fonctionnalités ont été mises en œuvre avec succès. 🚀
