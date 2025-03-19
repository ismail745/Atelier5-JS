<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EX2 - Gestion des Fichiers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-4 sm:p-6 max-w-2xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Gestion des Fichiers</h1>
        
        <!-- Formulaire de téléchargement -->
        <form method="POST" id="uploadForm" class="bg-white p-6 rounded-lg shadow-md mb-6" aria-label="Formulaire de téléchargement">
            <div class="mb-4">
                <label for="fileInput" class="block text-gray-700 font-medium mb-2">
                    Choisir un fichier
                </label>
                <input 
                    type="file" 
                    id="fileInput" 
                    name="file"
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required 
                    aria-required="true"
                    aria-describedby="file-error"
                >
                <p id="file-error" class="text-red-500 text-sm mt-1 hidden">Erreur lors du téléchargement</p>
            </div>
            <button 
                type="submit" 
                id="uploadButton"
                class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-300 disabled:bg-gray-400"
            >
                Télécharger
            </button>
        </form>

        <!-- Liste des fichiers -->
        <div id="fileList" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Fichiers Téléchargés</h2>
            <div id="loading" class="text-gray-500 mb-2 hidden">Chargement...</div>
            <ul id="filesContainer" class="space-y-2" aria-live="polite"></ul>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        async function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);

            try {
                const response = await fetch('/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
                
                const result = await response.json();
                return result;
            } catch (error) {
                document.getElementById('file-error').classList.remove('hidden');
                throw error;
            }
        }

        async function fetchFiles() {
            const loading = document.getElementById('loading');
            loading.classList.remove('hidden');
            try {
                const response = await fetch('/files');
                if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
                const files = await response.json();
                displayFiles(files);
                return files;
            } catch (error) {
                alert('Erreur lors de la récupération: ' + error.message);
            } finally {
                loading.classList.add('hidden');
            }
        }

        function displayFiles(files) {
            const filesContainer = document.getElementById('filesContainer');
            filesContainer.innerHTML = files.length === 0 
                ? '<li class="text-gray-500">Aucun fichier disponible</li>'
                : files.map(file => `
                    <li class="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                        <span class="text-gray-700">${file.name}</span>
                        <a href="${file.url}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">Voir</a>
                    </li>
                `).join('');
        }

        const uploadForm = document.getElementById('uploadForm');
        const uploadButton = document.getElementById('uploadButton');
        uploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];
            const errorElement = document.getElementById('file-error');

            if (!file) return;

            uploadButton.disabled = true;
            uploadButton.textContent = 'Téléchargement...';
            errorElement.classList.add('hidden');

            try {
                console.log(file);
                await uploadFile(file);
                await fetchFiles();
                fileInput.value = '';
                alert('Fichier téléchargé avec succès !');
            } catch (error) {
                console.error('Erreur:', error);
            } finally {
                uploadButton.disabled = false;
                uploadButton.textContent = 'Télécharger';
            }
        });

        document.addEventListener('DOMContentLoaded', fetchFiles);
    </script>
</body>
</html>