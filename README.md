# CakePHP Application Skeleton

![Build Status](https://github.com/cakephp/app/actions/workflows/ci.yml/badge.svg?branch=5.x)
[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

A skeleton for creating applications with [CakePHP](https://cakephp.org) 5.x.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

## Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally, run

```bash
composer create-project --prefer-dist cakephp/app
```

In case you want to use a custom app dir name (e.g. `/myapp/`):

```bash
composer create-project --prefer-dist cakephp/app myapp
```

You can now either use your machine's webserver to view the default home page, or start
up the built-in webserver with:

```bash
bin/cake server -p 8765
```

Then visit `http://localhost:8765` to see the welcome page.

## Demo app

Check out the [5.x-demo branch](https://github.com/cakephp/app/tree/5.x-demo), which contains demo migrations and a seeder.
See the [README](https://github.com/cakephp/app/blob/5.x-demo/README.md) on how to get it running.

## Update

Since this skeleton is a starting point for your application and various files
would have been modified as per your needs, there isn't a way to provide
automated upgrades, so you have to do any updates manually.

## Configuration

Read and edit the environment specific `config/app_local.php` and set up the
`'Datasources'` and any other configuration relevant for your application.
Other environment agnostic settings can be changed in `config/app.php`.

## Layout

La aplicación utiliza Tailwind CSS 4 y DaisyUI 5 mediante TailwindUi. Los
layouts `default` y `user` cargan los estilos compilados y las bibliotecas
JavaScript locales. La página de bienvenida de CakePHP mantiene su diseño
independiente.

## Tailwind UI

El proyecto integra `dereuromark/cakephp-tailwind-ui` con Tailwind CSS 4 y
DaisyUI 5. `AppView` registra los helpers Form, Html, Flash, Paginator y
Breadcrumbs. El layout de la aplicación carga el CSS compilado localmente y
el JavaScript para cerrar los mensajes flash.

Usa el entorno DDEV del proyecto (PHP 8.4 y Node.js 24):

```bash
ddev composer install
ddev npm ci
ddev npm run js:build
ddev npm run css:build
ddev exec bin/cake plugin assets symlink TailwindUi --relative
```

Para recompilar al modificar las plantillas o los estilos:

```bash
ddev npm run css:watch
```

El CSS de entrada está en `src/css/app.css` y el resultado en
`webroot/css/app.css`. Las fuentes incluyen las plantillas de la aplicación y
el plugin. No se necesita un CDN. Los temas disponibles son `light`, `dark`
(según las preferencias del sistema) y `cupcake`; este último se activa con
`data-theme="cupcake"` en un contenedor.

La página de bienvenida original de CakePHP (`templates/Pages/home.php`)
conserva su HTML y CSS de diagnóstico independientes. Las vistas que usan el
layout predeterminado de la aplicación y el área `/user` ya reciben Tailwind y
DaisyUI.

## HTMX: selector de usuarios por rol

El formulario de creación de artículos (`/articles/add`) utiliza HTMX para
actualizar el selector de autor sin recargar la página:

- El campo `user_role` permite elegir `user` o `editor`; inicialmente usa `user`.
- Al cambiar, `hx-get` solicita `/articles/users-by-role?user_role=editor`
  (o `user`) y `hx-trigger="change"` inicia la actualización.
- `hx-target="#user-selector"` y `hx-swap="outerHTML"` sustituyen únicamente
  el selector de usuarios. `hx-sync="this:replace"` reemplaza las peticiones
  pendientes cuando se cambia de rol rápidamente.
- `ArticlesController::usersByRole()` acepta GET y devuelve HTML parcial sin
  layout, mediante `templates/Articles/users_by_role.php` y el elemento
  `templates/element/article_user_selector.php`.

El servidor valida los roles admitidos y devuelve 400 si el valor es inválido.
Al guardar el artículo, comprueba además que el usuario seleccionado existe y
pertenece al rol elegido. Este selector filtra autores; no identifica al
visitante ni concede permisos de acceso.

## Alpine.js: confirmación de acciones

Alpine.js controla el diálogo de confirmación de las acciones del CRUD.
`AppView` configura la plantilla `confirmJs` del FormHelper para emitir el
evento `app-confirm` en lugar de enviar inmediatamente el formulario.

El elemento `templates/element/confirm.php`, incluido en el layout `default`,
escucha ese evento con `@app-confirm.window`. Su estado `x-data` guarda la
acción pendiente, muestra el mensaje mediante `x-text` y abre un `<dialog>`
con estilo DaisyUI. Cancelar cierra el diálogo sin enviar el formulario;
confirmar usa `requestSubmit()` y bloquea confirmaciones repetidas. Se
conservan el método HTTP y los campos de protección del formulario CakePHP.

El layout `user` carga Alpine.js, pero sus vistas son de lectura y no incluyen
el diálogo de confirmación del CRUD.

### Assets JavaScript locales

Las dependencias `alpinejs` y `htmx.org` están declaradas en `package.json`.
Tras instalar o actualizar las dependencias, publicar sus archivos locales:

```bash
ddev npm ci
ddev npm run js:build
```

`js:build` copia las distribuciones a `webroot/js/alpine.min.js` y
`webroot/js/htmx.min.js`. Ambos layouts las cargan con `defer`, sin CDN.
Este paso es independiente de la compilación CSS y de los assets del plugin
TailwindUi.

## Prefix routing: área User

`config/routes.php` registra `$routes->prefix('User', ...)` antes de las
rutas generales. El prefijo usa rutas GET explícitas, sin `fallbacks` dentro
de su bloque:

| URL | Controlador en `App\Controller\User` | Acción |
| --- | --- | --- |
| `/user` | `DashboardController` | `index` |
| `/user/articles` | `ArticlesController` | `index` |
| `/user/articles/view/{id}` | `ArticlesController` | `view` |

El identificador del detalle debe ser numérico. Los controladores del área
heredan de `App\Controller\User\AppController`, que selecciona
`templates/layout/user.php`. Las plantillas se encuentran en
`templates/User/Dashboard/` y `templates/User/Articles/`; comparten el modelo
`Articles` y los helpers de `AppView` con el resto de la aplicación.

El inicio enlaza al listado paginado de artículos publicados de cualquier
autor, ordenados inicialmente del más reciente al más antiguo. El detalle
muestra autor, fechas, contenido escapado y etiquetas como texto. Los
artículos sin publicar y los inexistentes devuelven 404. El área ofrece
únicamente lectura y su menú contiene Inicio, Artículos y Volver al sitio.

Para generar enlaces internos, indicar el prefijo con su nombre de namespace:

```php
$this->Html->link(__('Artículos'), [
    'prefix' => 'User',
    'controller' => 'Articles',
    'action' => 'index',
]);
```

Para salir del prefijo hacia las rutas generales, usar `'prefix' => false`:

```php
$this->Html->link(__('Volver al sitio'), [
    'prefix' => false,
    'controller' => 'Pages',
    'action' => 'display',
    'home',
]);
```

**Acceso actual:** `/user` es público. El prefijo organiza rutas, controladores
y plantillas; todavía no aplica login ni restringe el acceso a `role=user`.
La autenticación y la comprobación del rol quedan para una fase posterior.
Las rutas generales, como `/` y `/articles`, mantienen su comportamiento.

## Validación

Suite completa:

```bash
ddev exec vendor/bin/phpunit
```

Pruebas específicas del área User y de los helpers y layouts:

```bash
ddev exec vendor/bin/phpunit tests/TestCase/Controller/User
ddev exec vendor/bin/phpunit tests/TestCase/View/AppViewTest.php
```

Las pruebas de `UserAreaTest` cubren rutas y plantillas propias, paginación,
filtrado de publicados, estados vacíos, escape del contenido, respuestas 404,
navegación de lectura y conservación de las rutas generales. Las pruebas del
selector HTMX y de la validación del autor están en
`tests/TestCase/Controller/ArticlesControllerTest.php`.

Para revisar los estilos en el navegador, recompilar con
`ddev npm run css:build` y comprobar `/user`, `/user/articles` y el detalle de
un artículo publicado en móvil y escritorio.
