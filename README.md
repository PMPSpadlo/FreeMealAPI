FreeMealAPI

Aplikacja do synchronizacji i wyświetlania przepisów kulinarnych z API TheMealDB.

1. Wymagania wstępne

-PHP 8.3 lub nowszy

-Laravel 11

-Composer

-MySQL 8.x

2. Instalacja

Sklonuj repozytorium:
```bash
git clone https://github.com/PMPSpadlo/FreeMealAPI.git

cd FreeMealAPI
```
Zainstaluj zależności PHP:
```bash
composer install
```

Skopiuj domyślny plik .env.example:
```bash
cp .env.example .env
```
Wypełnij plik .env danymi dostępu do bazy danych:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=free_meal_api
DB_USERNAME=root
DB_PASSWORD=your_password
```
Wygeneruj klucz aplikacji:
```bash
php artisan key:generate
```
3. Uruchom migracje bazy danych:
```bash
php artisan migrate
```

4. Uruchamianie aplikacji
```bash
php artisan serve
```
Domyślnie aplikacja będzie dostępna pod adresem http://127.0.0.1:8000.

5. Synchronizuj dane z API:

Wykonaj poniższe komendy, aby pobrać kategorie i przepisy z TheMealDB API:
```bash
php artisan app:sync-categories
php artisan app:sync-meals-by-category
```


Funkcjonalności

Synchronizacja przepisów: automatyczne pobieranie przepisów z API TheMealDB.

Lista przepisów: przeglądanie przepisów z funkcją wyszukiwania i stronicowaniem.

Szczegóły przepisu: wyświetlanie pełnych informacji o przepisie.

Komentarze: możliwość dodawania komentarzy do przepisu.

Ulubione przepisy: oznaczanie przepisów jako ulubione z wykorzystaniem localStorage.
