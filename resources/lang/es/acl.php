<?php

return [
    // -- permissions
    // acl/permissions/list.blade.php
    'permissions.list.title' => 'Lista de permissos',
    'permissions.list.heading' => 'Permisos',
    'permissions.list.subtitle' => 'Lista de permisos asignables. No se pueden editar ni eliminar.',
    'permissions.list.table.id' => 'ID',
    'permissions.list.table.name' => 'Nombre',
    'permissions.list.table.description' => 'Descripción',
    'permissions.list.empty' => 'No hay ningún permiso.',
    // -- roles
    // acl/roles/edit.blade.php
    'roles.edit.heading' => 'Editar grupo ":name"',
    'roles.edit.form.info.heading' => 'Información',
    'roles.edit.form.info.id' => 'Identificador',
    'roles.edit.form.info.id.description' => 'Una palabra corta y solo con letras. Identifica el grupo.',
    'roles.edit.form.info.displayname' => 'Nombre a mostrar',
    'roles.edit.form.info.displayname.description' => 'El nombre que se mostrará a los usuarios.',
    'roles.edit.form.info.description' => 'Descripción',
    'roles.edit.form.info.description.description' => 'Describe el grupo y su función de forma corta y concisa.',
    'roles.edit.form.permissions.heading' => 'Permisos',
    'roles.edit.form.submit' => 'Editar grupo',
    'roles.edit.danger.heading' => 'Mucho ojo:',
    'roles.edit.danger.delete.button' => 'Quitar a todos del grupo y eliminarlo',
    'roles.edit.danger.delete.confirm' => '¿Vaciar y borrar grupo? No se puede deshacer.',
    'roles.edit.users.heading' => 'Usuarios',
    'roles.edit.users.empty' => 'Ningún usuario en el grupo.',
    // acl/roles/list.blade.php
    'roles.list.title' => 'Grupos',
    'roles.list.heading' => 'Grupos',
    'roles.list.add.button' => 'Crear grupo',
    'roles.list.table.heading.name' => 'Nombre',
    'roles.list.table.heading.members' => 'Integrantes',
    'roles.list.table.heading.actions' => 'Acciones',
    'roles.list.table.empty' => '<p><b>No hay ningún grupo.</b> Crea alguno usando el botón de arriba.</p>
                            <p>Al no haber grupos, todos los usuarios tendrán los permisos por defecto.</p>',
    // acl/roles/new.blade.php
    'roles.add.title' => 'Crear grupo',
    'roles.add.heading' => 'Crear grupo',
    'roles.add.form.info.heading' => 'Información',
    'roles.add.form.info.id' => 'Identificador',
    'roles.add.form.info.id.description' => 'Una palabra corta y solo con letras. Identifica el grupo.',
    'roles.add.form.info.displayname' => 'Nombre a mostrar',
    'roles.add.form.info.displayname.description' => 'El nombre que se mostrará a los usuarios.',
    'roles.add.form.info.description' => 'Descripción',
    'roles.add.form.info.description.description' => 'Describe el grupo y su función de forma corta y concisa.',
    'roles.add.form.permissions.heading' => 'Permisos',
    'roles.add.form.submit' => 'Crear grupo',
    // -- Users
    // acl/users/edit.blade.php
    'users.edit.data.heading' => 'Datos y permisos',
    'users.edit.data.id' => 'Identificador',
    'users.edit.data.steamid' => 'SteamID',
    'users.edit.data.email' => 'Correo electrónico',
    'users.edit.data.disabled' => 'Cuenta desactivada',
    'users.edit.groups.heading' => 'Grupos',
    'users.edit.permissions.heading' => 'Permisos individuales del usuario',
    'users.edit.submit' => 'Editar usuario',


];