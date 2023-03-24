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
php artisan queue:work
```

### Flujo
```sh
1. Ingresar como admin y registrar vendedores (el sistema registra automaticamente como cliente) 
email: admin@admin.com
contraseña: password
2. Para registrar vendedores, modulo Vendedores, estos tambien pueden loguear
3. Los clientes solo pueden ver sus cotizaciones, los vendedores pueden crear/ver/editar en vendedores, clientes y eliminar cotizaciones
```