# UFC MMA Laravel Projekts

Šis ir Laravel 12 projekts, kas ietver reāllaika tērzēšanu, dream fights, pound-for-pound top sarakstu un administrācijas paneli.

## Prasības
PHP 8.2 vai jaunāks  
Composer  
Node.js 18 vai jaunāks  
MySQL datubāze  

## Projekta uzstādīšana

1. Klonēt projektu

git clone <projekta URL>
cd <projekta mape>


2. Instalēt PHP atkarības

composer install


3. Instalēt Node.js atkarības

npm install


4. Izveidot .env failu

galvenā maināmā .env faila informācija APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:ZVIgdgVJYu9xFX8NqXbpe/6lYXIESUX/ulf+p056yms=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com
"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

SPORTSDATA_API_KEY=d966c6cdce8143da883523ed754cd488

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=2046240
PUSHER_APP_KEY=6c4b79358ae1f2d4d906
PUSHER_APP_SECRET=054330bfb3fe4362694f
PUSHER_APP_CLUSTER=eu

VITE_PUSHER_APP_KEY=6c4b79358ae1f2d4d906
VITE_PUSHER_APP_CLUSTER=eu

VITE_APP_NAME="${APP_NAME}"

9. palaist seeder

php artisan migrate --seed

te arī ir komanda lai sagatavotu fighters 

php artisan fighters:import

ja tā nestrādā 
php artisan fighters:sync



10. Palaist projektu

php artisan serve

11. Palaist frontend

npm run dev

ja atsevišķi seeders nenostrādāja tad 

php artisan db:seed --class=UsersTableSeeder
php artisan db:seed --class=DivisionsSeeder
php artisan db:seed --class=PoundSeeder


Lietotāji

Administrators:  
E-pasts: admin@ufc.test  
Parole: password  

Parasts lietotājs:  
E-pasts: user@ufc.test  
Parole: password  
