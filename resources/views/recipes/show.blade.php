<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $recipe->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        img {
            max-width: 100%;
            display: block;
            margin: 0 auto;
            border-radius: 8px;
        }

        .section {
            margin-top: 20px;
        }

        .ingredients ul, .other-info ul {
            list-style: none;
            padding: 0;
        }

        .ingredients li, .other-info li {
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        .comments {
            margin-top: 30px;
        }

        .comments ul {
            list-style: none;
            padding: 0;
        }

        .comments li {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            background: #f9f9f9;
        }

        .comments form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .comments form button {
            padding: 10px 20px;
            background: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .comments form button:hover {
            background: #0056b3;
        }

        .favorite-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .favorite-btn:hover {
            background: #218838;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            text-align: center;
        }

        .back-btn:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="javascript:history.back()" class="back-btn">Back</a>
    <h1>{{ $recipe->title }}</h1>
    <img src="{{ $recipe->thumbnail }}" alt="{{ $recipe->title }}">

    <div class="section favorites">
        <button
            class="favorite-btn"
            data-id="{{ $recipe->id }}"
            data-title="{{ $recipe->title }}">
            Add to Favorites
        </button>
    </div>

    <div class="section ingredients">
        <h2>Ingredients</h2>
        <ul>
            @foreach ($recipe->ingredients as $ingredient)
                <li>{{ $ingredient['name'] }} - {{ $ingredient['measure'] }}</li>
            @endforeach
        </ul>
    </div>

    <div class="section instructions">
        <h2>Instructions</h2>
        <p>{{ $recipe->instructions }}</p>
    </div>

    <div class="section other-info">
        <h2>Other Information</h2>
        <ul>
            @if ($recipe->category)
                <li><strong>Category:</strong> {{ $recipe->category->name }}</li>
            @endif
            @if ($recipe->area)
                <li><strong>Area:</strong> {{ $recipe->area->name }}</li>
            @endif
            @if ($recipe->tags)
                <li><strong>Tags:</strong> {{ implode(', ', $recipe->tags) }}</li>
            @endif
            @if ($recipe->youtube)
                <li><strong>YouTube:</strong> <a href="{{ $recipe->youtube }}" target="_blank">Watch Video</a></li>
            @endif
            @if ($recipe->source)
                <li><strong>Source:</strong> <a href="{{ $recipe->source }}" target="_blank">View Source</a></li>
            @endif
            @if ($recipe->image_source)
                <li><strong>Image Source:</strong> <a href="{{ $recipe->image_source }}" target="_blank">View Image</a></li>
            @endif
            @if ($recipe->creative_commons_confirmed)
                <li><strong>Creative Commons:</strong> Yes</li>
            @endif
            @if ($recipe->date_modified)
                <li><strong>Last Modified:</strong> {{ \Carbon\Carbon::parse($recipe->date_modified)->format('Y-m-d H:i') }}</li>
            @endif
        </ul>
    </div>

    <div class="comments">
        <h2>Comments</h2>
        <form method="POST" action="{{ route('comments.store', $recipe->id) }}">
            @csrf
            <textarea name="comment" placeholder="Add a comment..." required></textarea>
            <button type="submit">Add Comment</button>
        </form>

        <ul>
            @foreach ($recipe->comments()->latest()->take(20)->get() as $comment)
                <li>{{ $comment->comment }} - <small>{{ $comment->created_at->format('Y-m-d H:i:s') }}</small></li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const favoriteButton = document.querySelector('.favorite-btn');
        const favorites = JSON.parse(localStorage.getItem('favorites')) || [];

        const isFavorite = (id) => favorites.some(fav => fav.id === id);

        const toggleFavorite = (id, title) => {
            const index = favorites.findIndex(fav => fav.id === id);
            if (index === -1) {
                favorites.push({ id, title });
                alert(`${title} added to favorites.`);
            } else {
                favorites.splice(index, 1);
                alert(`${title} removed from favorites.`);
            }
            localStorage.setItem('favorites', JSON.stringify(favorites));
            updateButtonState(id);
        };

        const updateButtonState = (id) => {
            if (isFavorite(id)) {
                favoriteButton.textContent = 'Remove from Favorites';
                favoriteButton.style.background = '#dc3545';
            } else {
                favoriteButton.textContent = 'Add to Favorites';
                favoriteButton.style.background = '#28a745';
            }
        };

        favoriteButton.addEventListener('click', () => {
            const id = parseInt(favoriteButton.dataset.id);
            const title = favoriteButton.dataset.title;
            toggleFavorite(id, title);
        });

        updateButtonState(parseInt(favoriteButton.dataset.id));
    });
</script>
</body>
</html>
