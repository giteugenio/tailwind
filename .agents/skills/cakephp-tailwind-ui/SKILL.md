---
name: cakephp-tailwind-ui
description: "Configura e integra el plugin dereuromark/cakephp-tailwind-ui en aplicaciones CakePHP 5.2+ y PHP 8.2+: instalar con Composer, habilitar TailwindUi, registrar Form/Html/Flash/Paginator/Breadcrumbs mediante UiViewTrait, configurar Tailwind CSS 4 y DaisyUI 5, compilar estilos, incorporar assets, usar comandos de layouts y tema Bake, convertir y revisar templates PHP y diagnosticar problemas visuales. Activar al crear o mantener aplicaciones CakePHP que necesiten Tailwind, DaisyUI o el plugin TailwindUi."
---

# Integracion de CakePHP 5 con TailwindUi

Objetivo: dejar una aplicacion CakePHP 5.2+ existente o recien instalada **funcional**, con el plugin `dereuromark/cakephp-tailwind-ui`, sus helpers y CSS compilado; adaptar templates de forma conservadora siguiendo la documentacion real del plugin. Trabajar sobre el proyecto suministrado por el usuario, nunca sobre una aplicacion ficticia.

## Autoridad, compatibilidad y evidencia

1. Antes de instalar, consultar `composer.json`, `composer.lock`, `src/Application.php`, `config/plugins.php`, `src/View/AppView.php`, `templates/layout/`, `package.json`, CSS existente y estado Git. Ejecutar `python scripts/audit_app.py /ruta/a/aplicacion` (script de solo lectura) y comprobar con `composer show cakephp/cakephp`, `php -v`, `node --version` y `npm --version` cuando esten disponibles.
2. Exigir PHP >=8.2 y CakePHP >=5.2: lo exige el `composer.json` del plugin; **CakePHP 5.0/5.1 no basta**. No forzar versiones ni cambiar dependencias principales sin analizar el impacto. `composer prohibits dereuromark/cakephp-tailwind-ui` puede explicar incompatibilidades.
3. Verificar contra el codigo instalado en `vendor/dereuromark/cakephp-tailwind-ui/` y la documentacion de la version fijada en `composer.lock`; si difiere de las referencias de esta skill (rama master consultada en 2026-11-01), prevalece el codigo/version instalada. No inventar comandos, helpers, argumentos o rutas. Usar `bin/cake --help`, `bin/cake tailwind_ui --help`, `bin/cake bake --help` para confirmar comandos antes de ejecutarlos.
4. La integracion del plugin PHP **NO instala ni compila Tailwind CSS**, ni garantiza CSS cargado. La cadena Composer/plugin, NPM/CSS y layouts son pasos independientes.
5. Antes de modificar: inspeccionar diferencias Git, preservar codigo/funcionalidad y evitar sobrescribir archivos existentes sin consentimiento. Si hay conflictos con Bootstrap o helpers personalizados, definir migracion incremental. No tocar `vendor/`.

## Flujo para provisionar una app

### 1. Instalar y cargar plugin PHP

Desde la raiz de la app, despues de validar compatibilidad:

```bash
composer require dereuromark/cakephp-tailwind-ui
bin/cake plugin load TailwindUi
```

Si ya esta en `config/plugins.php` o `src/Application.php`, **no cargarlo dos veces**. No hace falta crear rutas, tablas o middleware del plugin.

### 2. Inicializar helpers en AppView

Editar `src/View/AppView.php` **sin reemplazar** imports, traits, helpers o inicializacion actual. Incorporar:

```php
use TailwindUi\View\UiViewTrait;

class AppView extends View
{
    use UiViewTrait;

    public function initialize(): void
    {
        parent::initialize();
        // Mantener inicializacion preexistente.
        $this->initializeUi();
    }
}
```

`initializeUi()` carga `Form`, `Html`, `Flash`, `Paginator`, `Breadcrumbs` y usa el layout `TailwindUi.default` si el actual es `default`; preserva layouts personalizados. Si se desea conservar el layout por defecto de la app, usar `$this->initializeUi(['layout' => false]);`; si se precisa otro, `['layout' => 'admin']`. Inspeccionar `src/View/UiViewTrait.php` instalado para confirmar comportamiento.

### 3. Instalar y compilar Tailwind 4 + DaisyUI 5

Para el preset `daisyui` predeterminado:

```bash
npm install -D tailwindcss@4 @tailwindcss/cli@4 daisyui@5
```

Crear o integrar en `src/css/app.css`, sin destruir CSS previo:

```css
@import "tailwindcss";
@plugin "daisyui" {
  themes: light --default, dark --prefersdark, cupcake;
}
@source "../../templates";
@source "../../vendor/dereuromark/cakephp-tailwind-ui";
```

Las rutas `@source` son relativas a `src/css/app.css`. Agregar otras rutas donde existan clases (plantillas propias, elementos, mapas personalizados); el escaneo explicito de `vendor` importa porque Tailwind normalmente ignora dependencias. Compilar:

```bash
npx @tailwindcss/cli -i src/css/app.css -o webroot/css/app.css --minify
```

Para desarrollo, repetir con `--watch`. Conservar, si los hay, scripts NPM ya existentes, o agregar `css:build`/`css:watch` sin pisarlos. **No depender del CDN para produccion**. Poner `<?= $this->Html->css('app', ['block' => true]) ?>` en template si el layout procesa el bloque CSS, o `<?= $this->Html->css('app') ?>` directamente en el `<head>` del layout propio; no incluir la hoja dos veces. Retirar referencias temporales a CDN una vez compilado. Ver `references/setup.md`.

### 4. Opcional: layouts, JS y Bake

- Para copiar y personalizar los layouts y elemento flash del plugin sin sobrescribir overrides existentes: `bin/cake tailwind_ui copy_layouts`. Revisar resultados bajo `templates/plugin/TailwindUi/`.
- Si se usa cierre de alertas Flash, incluir `$this->Html->script('TailwindUi.dismiss', ['block' => true]);` y asegurar que el layout emita el bloque `script`. Publicar assets mediante `bin/cake plugin assets symlink` o `bin/cake plugin assets copy`, segun la app/hosting. Verificar que `/tailwind_ui/js/dismiss.js` responda correctamente.
- Opcional: si se va a generar CRUD, instalar `composer require --dev cakephp/bake` y verificar registro CLI de Bake; luego `bin/cake bake template Articles --theme TailwindUi`. **Nunca** usar `--force` sobre templates existentes sin copia de seguridad y aprobacion. El tema Bake no instala CSS. Ver `references/commands.md`.

### 5. Migrar templates gradualmente

1. Identificar layout activo y templates (`templates/**/*.php`), helpers ya cargados, CSS/framework previo, bloques `css`/`script` y scripts de formularios.
2. Priorizar los helpers del plugin para formularios, paginacion, alertas, breadcrumbs, badges e iconos; conservar CakePHP's escaping, CSRF, POST links, validaciones y asociaciones.
3. Cambiar solo el markup/clases necesarios. Asegurar estilo DaisyUI 5 (no mezclar indiscriminadamente clases Bootstrap); verificar outputs y responsiveness. Usar `references/templates.md` para ejemplos comprobados.
4. Cuando se use `Html->icon()`, no inventar nombres de iconos integrados (lista reducida; ver docs). Para SVG propio, usar la opcion `content` con contenido confiable.
5. Para personalizacion de clases, configurar el mapa antes de crear los helpers; nunca alterar archivos en `vendor`. Ver `references/commands.md`.

### 6. Breadcrumbs: scroll horizontal aunque haya espacio

Las llamadas a `$this->Breadcrumbs->add()` definen los enlaces y `render()` genera el contenedor `.breadcrumbs`; no cambiar esas llamadas para corregir un desbordamiento CSS.

Con DaisyUI 5.7.50 se verifico que un breadcrumb dentro de un encabezado `flex`, cuyo bloque de titulo se ajusta al contenido, puede desbordar 4 px aunque la pagina tenga espacio. La combinacion de `margin-inline-start: -0.25rem`, `max-width: 100%` y el padding de la lista provoca el desbordamiento; `overflow-x: auto` hace aparecer la barra. Confirmar los estilos de la version instalada y comparar `scrollWidth` con `clientWidth` antes de atribuirlo a falta de espacio.

Para este caso, incorporar en `src/css/app.css`, despues de la configuracion de Tailwind/DaisyUI:

```css
/* Evita el desbordamiento al ajustar el breadcrumb al contenido en encabezados flex. */
.breadcrumbs {
  margin-inline-start: 0;
}
```

Recompilar con el script de la aplicacion (`ddev npm run css:build` en este proyecto). Comprobar en navegador a anchos de escritorio y movil que los breadcrumbs cortos mantienen una sola linea y cumplen `scrollWidth <= clientWidth`. No ocultar la barra con `overflow: hidden` ni forzar `flex-wrap` para disimular este problema. Si un breadcrumb largo realmente no cabe, evaluar su comportamiento responsive por separado. No aplicar la correccion automaticamente si la version instalada ya resuelve el problema.

## Validacion exigida para dar por terminada una instalacion

- Verificar Composer (`composer show dereuromark/cakephp-tailwind-ui`), plugin cargado (`bin/cake plugin list` si el CLI lo permite), clases de helpers registradas, sintaxis PHP (`php -l` de archivos modificados).
- Construir CSS sin errores; comprobar archivo en `webroot/css/app.css`, ruta web y clases relevantes (`btn`, `input`, `alert`, `join`) y que el HTML carga esa hoja. No interpretar un CSS generado como prueba automatica de que la pagina funciona visualmente.
- Probar manualmente o mediante tests de integracion una vista con formulario y errores, paginacion, flashes, layout y un template existente; comprobar accesibilidad basica, JS de cierre si aplica y ausencia de errores en consola.
- Si se dispone de tests, ejecutar `bin/cake test`/`vendor/bin/phpunit` solo segun el proyecto; informar numero y resultado real, no inventarlo.
- Entregar resumen de archivos editados, comandos ejecutados con salida relevante, verificaciones superadas, pendientes y advertencias. Distinguir 'verificado' de 'pendiente de ejecutar' si no hay entorno terminal.

## Referencias de esta skill

- `references/setup.md`: requisitos, CSS, layout, solucion de problemas y fuentes primarias.
- `references/templates.md`: recetas concretas de helpers y conversion de templates.
- `references/commands.md`: comandos verificados, Bake, class maps y limites.
- `scripts/audit_app.py`: diagnostico estatico read-only de una aplicacion CakePHP.

Fuente primaria: https://github.com/dereuromark/cakephp-tailwind-ui y https://raw.githubusercontent.com/dereuromark/cakephp-tailwind-ui/master/docs/ . Revalidar versiones y capacidades en cada proyecto antes de cambiar archivos.
