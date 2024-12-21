<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Recipe List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            margin-bottom: 20px;
        }

        form input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        form button:hover {
            background: #0056b3;
        }

        ul.recipes-list {
            list-style: none;
            padding: 0;
        }

        ul.recipes-list li {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        ul.recipes-list li img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            margin-right: 15px;
        }

        ul.recipes-list li a {
            flex: 1;
            text-decoration: none;
            color: #333;
        }

        ul.recipes-list li a:hover {
            color: #007bff;
        }

        ul.recipes-list li button {
            background: #28a745;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        ul.recipes-list li button:hover {
            background: #218838;
        }

        .favorites-section {
            margin-top: 40px;
        }

        .favorites-section h2 {
            text-align: center;
        }

        .favorites-section ul {
            list-style: none;
            padding: 0;
        }

        .favorites-section ul li {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f9f9f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .favorites-section ul li a {
            text-decoration: none;
            color: #007bff;
        }

        .favorites-section ul li a:hover {
            text-decoration: underline;
        }

        .favorites-section ul li button {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .favorites-section ul li button:hover {
            background: #c82333;
        }

    </style>
</head>
<body>
<div class="container">
    <h1>Recipe List</h1>
    <form method="GET" action="{{ route('recipes.index') }}">
        <input type="text" name="search" placeholder="Search recipes..." value="{{ request('search') }}">
        <button type="submit">Search</button>
    </form>
    <ul class="recipes-list">
        @foreach ($recipes as $recipe)
            <li>
                <img src="{{ $recipe->thumbnail }}" alt="{{ $recipe->title }}">
                <a href="{{ route('recipes.show', $recipe->id) }}">{{ $recipe->title }}</a>
                <button class="favorite-btn" data-id="{{ $recipe->id }}" data-title="{{ $recipe->title }}">
                    Add to Favorites
                </button>
            </li>
        @endforeach
    </ul>

    <div class="pagination">
        {{ $recipes->appends(['search' => request('search')])->links() }}
    </div>

    <div class="favorites-section">
        <h2>Your Favorites</h2>
        <ul id="favorites-list">
            <!-- Favorites list will be rendered here -->
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const favoriteButtons = document.querySelectorAll('.favorite-btn');
        const favoritesList = document.getElementById('favorites-list');
        const favorites = JSON.parse(localStorage.getItem('favorites')) || [];

        const renderFavorites = () => {
            favoritesList.innerHTML = '';
            favorites.forEach(fav => {
                const li = document.createElement('li');
                const link = document.createElement('a');
                link.href = `/recipes/${fav.id}`;
                link.textContent = fav.title;
                li.appendChild(link);

                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'Remove';
                removeBtn.addEventListener('click', () => removeFavorite(fav.id));
                li.appendChild(removeBtn);

                favoritesList.appendChild(li);
            });
        };

        const addFavorite = (id, title) => {
            if (!favorites.some(fav => fav.id === id)) {
                favorites.push({ id, title });
                localStorage.setItem('favorites', JSON.stringify(favorites));
                renderFavorites();
            }
        };

        const removeFavorite = (id) => {
            const index = favorites.findIndex(fav => fav.id === id);
            if (index !== -1) {
                favorites.splice(index, 1);
                localStorage.setItem('favorites', JSON.stringify(favorites));
                renderFavorites();
            }
        };

        favoriteButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                const title = btn.dataset.title;
                addFavorite(id, title);
            });
        });

        renderFavorites();
    });
</script>
</body>
</html>
