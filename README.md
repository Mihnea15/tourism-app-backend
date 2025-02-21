# Backend pentru Aplicația Mobilă

Acest proiect reprezintă backend-ul pentru aplicația mobilă de turism, construit folosind Yii2 Framework. Backend-ul oferă API-uri RESTful pentru a gestiona datele aplicației, inclusiv utilizatori, trasee și afaceri.

## Cerințe

- PHP 7.4 sau mai recent
- Composer
- MySQL sau altă bază de date compatibilă
- Yii2 Framework

## Instalare

1. Clonează repository-ul:
   ```bash
   git clone https://github.com/username/repo-name.git
   cd repo-name
   ```

2. Instalează dependențele folosind Composer:
   ```bash
   composer install
   ```

3. Configurează baza de date în fișierul `config/db.php`:
   ```php
   return [
       'class' => 'yii\db\Connection',
       'dsn' => 'mysql:host=your_production_host;dbname=your_database',
       'username' => 'your_username',
       'password' => 'your_password',
       'charset' => 'utf8',
   ];
   ```

4. Creează baza de date și rulează migrațiile (dacă este cazul):
   ```bash
   php yii migrate
   ```

## Pornire

Pentru a porni serverul de dezvoltare, folosește următoarea comandă:
```bash
php -S 0.0.0.0:3000 -t web
```

Accesează API-ul la `http://localhost:3000` sau `http://<IP-ul_tău>:3000` de pe alte dispozitive din rețea.

## API-uri disponibile

- **Utilizatori**
  - `POST /user/register` - Înregistrare utilizator
  - `POST /user/login` - Autentificare utilizator
  - `GET /user/get-user-data?userId={id}` - Obține datele utilizatorului

- **Trasee**
  - `GET /api-city` - Obține toate traseele
  - `GET /api-business/get-favourites?userId={id}` - Obține favoritele utilizatorului

## Configurare CORS

Asigură-te că ai configurat CORS corect în `config/web.php` pentru a permite accesul din aplicația mobilă.

## Contribuții

Contribuțiile sunt binevenite! Te rog să deschizi un issue sau un pull request pentru a discuta despre modificările dorite.

## Licență

Acest proiect este licențiat sub [Licența MIT](LICENSE).
