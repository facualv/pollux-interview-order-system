# Vitácora del proceso de desarrollo

# **09/03/2025**

- [x]  Leer documentacíon de Filament.
- [x]  Crear repositorio en Github.
- [x]  Crear proyecto (Php 8.1, Laravel 11, Filament 3.2)
- [x]  Analizar requerimientos y definir criterios de aceptación.
- [x]  Diseñar esquema de base de datos.

## Criterios de aceptación

- El sistema permite crear un **USUARIOS** y hacer **LOGIN** con el usuario creado.
- El sistema permite crear, editar, eliminar y listar **PRODUCTOS** existentes.
- El sistema permite crear una **ORDEN** de venta que incluya productos con una cantidad y precio determinado.
- Implementar algun patrón de diseño dentro de la solución.

## Esquema de base de datos

```yaml
Users:
  attributes:
    id: int
    name: string
    email: string
    password: string
    remember_token: string
    created_at: timestamp
    updated_at: timestamp

  relationships:
    order: hasMany

Products:
  attributes:
    id: int
    name: string
    description: string
    is_available: boolean
    quantity: int
    price: decimal
    created_at: timestamp
    updated_at: timestamp

  relationships:
  Order Item: hasMany

Orders:
  attributes:
    id: int
    user_id: int
    number: string
    total_price: decimal(10,2)
    status: enum(pending, canceled, dispatched)
    created_at: timestamp
    updated_at: timestamp

  relationships:
    User: belongsTo
    Order Item: hasMany

Order Item:
  attributes:
    id: int
    order_id: int
    product_id: int
    quantity: int
    unit_price: decimal(10,2)
    created_at: timestamp
    updated_at: timestamp
```

## Takeaway Knowledge

- Core concepts de Filament: Eloquent Models, Resources, Pages, Forms, Tables, Actions, , RelationManagers, Assets, Icons, Colors, Style customization, Render Hooks, Enums, Plugins, Blade Components, Stubs.
- ¿Qué sucede cuando instalamos Fillament?
    - Dashboard por defecto. Se crea un `AdminPanelServiceProvider`.
    - Nos habilita varios comandos de artisan para facilitar el trabajo.
    - Se agregan rutas por defecto, admin, admin/login, admin/logout (formulario de Autenticación por defecto)
- Filament proporciona un archivo de configuración que se puede publicar con el comando
php artisan vendor:publish —tag:filament-config.
- ¿Que se puede hacer desde el `AdminPanelServiceProvider` ?
    - Registrar recursos, widgets y páginas.
    - Personalizar la navegación y el tema.
    - Configurar middlewares, autenticación y políticas de acceso.
    - Agregar scripts, estilos y funcionalidades personalizadas.
- Todos los assets de filament estan almacenados en el directorio vendor, no en la carpeta resources. ¿Y entonces como hago para personalizar?.

# **10/03/2025**

- [x]  Migraciones, enumeraciones y modelos.
- [x]  “Resources” de Fillament: Product Resource y Order Resource.
- [x]  Formulario para creación de productos.
- [x]  Tabla para motrar Productos. Agregar filtros filtro básico.

## Takeaway Knowledge

- Los **Recursos** son el núcleo de Filament. Representan los modelos de Eloquent y permiten gestionar (crear, leer, actualizar y eliminar) registros en la base de datos. Cada recurso está asociado a un modelo de Laravel y define cómo se muestran y gestionan los datos en el panel de administración. Para ver todas las propiedades y el comportamiento revisar la clase abstracta `Resource` .

- El método `form()` se utiliza para definir los campos y la estructura de los formularios en Filament. Estos formularios se usan tanto para la creación como para la edición de registros.¿Qué puedo hacer con `form()`?
    - Agregar campos de entrada (inputs) como texto, números, selectores, etc.
    - Organizar los campos en secciones o pestañas.
    - Agregar validaciones, placeholders, y otras configuraciones a los campos.
    - Personalizar el comportamiento de los campos (por ejemplo, hacer que un campo sea condicional).
    
- El método `table()` se utiliza para definir cómo se muestran los datos en una tabla dentro del panel de administración. Permite definer las columnas, filtros, ordenamientos y acciones que estarán disponibles. ¿Qué puedo hacer con `table()`?
    - Definir las columnas que se mostrarán en la tabla.
    - Agregar filtros para buscar o filtrar datos.
    - Habilitar la ordenación de columnas.
    - Agregar acciones personalizadas (como editar, eliminar, o acciones personalizadas).
    
- La infomación en las tablas en general se muestra usando el componente `TextColumn` porque puede manejar múltiples tipos de datos (Strings, Numerics, Dates, Booleans, Enums, etc).
- Para armar el layout se pueden usar los componentes `Group` , que agrupa campos relacionados dentro de un formulario y `Section` para dividir el contenido en secciones visuales dentro de un formulario o recurso.
- El método `schema()` sirve para definir la estructura de los campos y componentes en formularios, recursos, páginas y otros elementos. En otras palabras, especificar qué campos, grupos, secciones u otros componentes deben mostrarse en la interfaz de usuario .
- Los **Resource Modifiers (o modificadores de recursos)** permiten modificar dinámicamente la configuración de los componentes de un componente, como  campos, filtros, acciones, columnas.

# **11/03/2025**

- [x]  Agregar funcionalidad de registro para el login funcion **register()**.
- [x]  Formulario para creacion ordenes. Ver validaciones y comportamiento de los componentes. Ver modificadores de los form components (ej: dehydrated).
- [x]  Tabla para mostrar Ordenes. Ver acciones de tabla por defecto y que modificadores tiene los componentes de tabla.
- [x]  Funcionalidad de notificaciones. Agregar migraciones de colas. (acordate del worker para que funcionen las colas).
- [x]  Recurso Usuarios. Solo quiero ver los usuarios y las ordenes que tienen creadas. Ver RelationshipManager.
- [x]  Funcionalidad de exportar csvs y excel. Agregar migraciones.
- [x]  PATRON DE DISEÑO PORTS & ADAPTERS:
    - [x]  Crear una accion de tabla customizada para generar una factura por la orden de venta.
    - [x]  Crear controlador y clase servicio `GenerateInvoiceService` para manejar esta accion (podria ser un evento tambien).
    - [x]  Crear interfaz `InvoiceGenerator`, crear dos adaptadores (uno por paquete) que implementen la interfaz.
    - [x]  La clase servicio depende de la interfaz creada `InvoiceGenerator`
    - [x]  Crear un service provider `InvoiceGeneratorServiceProvider` donde pueda inyectar indistintamente las dos implementaciones concretas de la generacion del pdf.

## Takeaway Knowledge

- El formulario de autenticación por defecto en **FilamentPHP** se encarga de manejar el inicio de sesión en el panel de administración. Filament usa **Laravel Fortify** internamente para gestionar la autenticación.

- Ejemplos de algunos modificacadores de componentes:
    - Filament permite modificar dinámicamente los campos de los formularios con `hidden()`, `visible()`, `disabled()`, `readonly()`, etc.
    - Se puede establecer valores y opciones dinámicas con `default()`, `options()`, `afterStateUpdated()`, etc.
    - Los campos pueden reaccionar a cambios de otros con `reactive()`, `live()`, `afterStateUpdated()`.
    - Es posible agregar validaciones y reglas condicionales con `required()`, `rule()`, `helperText()`, etc.
    
- Filament facilita la exportación de datos desde las tablas en su panel de administración mediante el uso de **acciones de exportación** (`ExportAction`). Estas acciones exportar fácilmente datos a **formatos como CSV, Excel, PDF y más** desde la interfaz de usuario de Filament. Filament utiliza la libreria `pxlrbt/filament-excel`. Permite exportaciones en segundo plano usando Laravel Queues.

- Filament proporciona un sistema para trabajar con **acciones predeterminadas** y **acciones personalizadas**, lo que te permite agregar funcionalidad personalizada a las tablas de tu panel de administración. Las acciones predeterminadas para tablas son **Ver**, **Editar**, **Eliminar**, **Exportar.**

# **11/03/2025**

- [x]  Revisión de que se cumplan los requerimientos funcionales.
- [x]  Repasar todo lo aprendido.
- [x]  Subir el repo a Githuib.
- [x]  Enviar email a David con el link del repositorio.

# **13/03/2025**

- [x]  Mejorar el layout de la vista de una orden. Crear una pagina `ViewOrder.php`.
