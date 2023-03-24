### Configuraciones para correr el proyecto
```sh
git clone www.local.com
composer install
```

### Configuracion en package.json para vite
```sh
"scripts": {
        "dev": "vite",
        "build": "vite build",
        "watch": "mix watch",
        "watch-poll": "mix watch -- --watch-options-poll=1000",
        "hot": "mix watch --hot",
        "prod": "npm run production",
```

```sh
npm install
npm run dev
```

### Configuracion laravel
```sh
php artisan key:generate
php artisan migrate --seed
php artisan passport:install
php artisan optimize
```
