* Proyecto final del Bootcamp de Full Stack Web Development

Formatio es una web de cursos dirigida tanto a trabajadores como a particulares con el objetivo de
mejorar su desempeño profesional, su adaptabilidad a las empresas y los equipos de trabajo, el clima laboral y el afrontamiento de los nuevos retos y cambios de nuestra sociedad.

Para el mismo he usado en la parte del Frontend React y CSS, y en la del Backend PHP y Symfony.

* Proceso de clonado e instalación

- git clone

En este repositorio de github: https://github.com/Elvira-88/final-project-server.git server:

-symfony serve -d

* Dependencias y configuración

Necesita tener instalado PHPMyAdmin.

El servidor se levantará en el puerto 8000.

* Páginas

Nuestra web es pública y por lo tanto cualquier usuario podrá acceder a:

- Página principal.
- Página de team.
- Página de courses (cuando un usuario accede con su cuenta en esta página además de aparecer todos los cursos, en aquellos que esté matriculado en lugar de la opción de contratar aparecerá la opción cursando)
- Página de hire-course (accedemos a ella cuando pulsamos contratar en algún curso, elegimos el método de pago y al pulsar el botón de pagar si no estamos logueados nos saltará una alerta indicando que debemos hacerlo y nos redirigirá a la página de login. Es decir, solo podremos contratar un curso si disponemos de una cuenta).
- Página de register.
- Página de login.

Si nos logueamos con un usuario administrador, además de toda la navegación pública disponible, también accederemos a:

- Página de admin (donde podremos seleccionar cursos o profesores).
- Página de admin-courses (al darle a añadir un curso nos llevará a la página de admin-courses-add, si le damos a editar nos redirigirá a la página de admin-course-edit donde podremos actualizarlo o eliminarlo).
- Página de admin-teachers (si hacemos click en añadir un profesor nos redirige a la página de admin-teachers-add, si lo hacemos sobre editar podremos actualizar o eliminar un profesor en la página de admin-teacher-edit).
