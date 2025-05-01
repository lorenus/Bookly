<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Libros con Google Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-4">Buscar Libros con Google Books</h1>
        <input type="text" id="book-search" placeholder="Buscar libros..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <div id="results" class="mt-4">
            </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('book-search');
            const resultsContainer = document.getElementById('results');

            searchInput.addEventListener('input', function(e) {
                const query = e.target.value;
                if (query.length < 3) {
                    resultsContainer.innerHTML = '';
                    return;
                }

                fetch(`/api/books/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsContainer.innerHTML = '';
                        if (data.items) {
                            data.items.forEach(item => {
                                const book = item.volumeInfo;
                                const title = book.title || 'Título no disponible';
                                const authors = book.authors ? book.authors.join(', ') : 'Autor desconocido';
                                const link = book.infoLink || '#';

                                const bookElement = document.createElement('div');
                                bookElement.classList.add('bg-white', 'shadow', 'rounded-md', 'p-4', 'mb-2');
                                bookElement.innerHTML = `
                                    <h3><a href="<span class="math-inline">\{link\}" target\="\_blank" class\="text\-blue\-500 hover\:underline"\></span>{title}</a></h3>
                                    <p>Autor: ${authors}</p>
                                `;
                                resultsContainer.appendChild(bookElement);
                            });
                        } else {
                            resultsContainer.innerHTML = '<p>No se encontraron resultados.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error al buscar libros:', error);
                        resultsContainer.innerHTML = '<p class="text-red-500">Error al buscar libros.</p>';
                    });
            });
        });
    </script>
</body>
</html>