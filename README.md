 Sistema de Gestión de Inventario TI

Proyecto de inventario de infraestructura informática con arquitectura cliente-servidor distribuida.

Tecnologías Utilizadas

- **Base de Datos:** PostgreSQL en la nube vía [Supabase](https://supabase.com/).
- **Frontend:** HTML5 semántico y CSS3 con enfoque de diseño minimalista.
- **Backend Web:** PHP 8+ mediante conexión PDO y consultas preparadas contra inyección SQL.
- **Módulo de Auditoría:** Java 21 (vía JDBC PostgreSQL) para reportes consolidados por consola.
- **Servidor Local:** Apache (XAMPP).
- **Control de Versiones:** Git y GitHub.

 Estructura del Repositorio

- `index.php`: Panel visual principal y formulario de registro.
- `conexion.php`: Conexión segura con el Connection Pooler de Supabase.
- `eliminar.php`: Módulo de baja física de equipos.
- `estilos.css`: Hoja de estilos minimalista y responsiva.
- `java/ReporteInventario.java`: Script de consola en Java que audita la disponibilidad en tiempo real.
