    <nav class="blue-grey darken-1">
        <div class="nav-wrapper">
            <div class="container">
                <ul id="nav-mobile" class="left">
                    <li><a href="{{ route('acl-users') }}" class="waves-effect waves-light"><i class="material-icons left">supervisor_account</i> Usuarios</a></li>
                    <li><a href="{{ route('acl-roles') }}" class="waves-effect waves-light"><i class="material-icons left">group_work</i> Grupos</a></li>
                    <li><a href="{{ route('acl-permissions') }}" class="waves-effect waves-light"><i class="material-icons left">lock_outline</i> Permisos</a></li>
                </ul>
                <ul id="nav-mobile" class="right">
                    <li><i class="material-icons left">vpn_key</i>ACL</li>
                    <li><a href="#modal-acl" class=""><i class="material-icons left">help</i></a></li>
                </ul>
            </div>
        </div>
    </nav>

<!-- Modal ayuda acl -->
<div id="modal-acl" class="modal">
    <div class="modal-content">
        <h5>Ayuda: ACL</h5>
        <p>Desde el ACL (Access Control List) se gestionan los usuarios con acceso al panel, los grupos y sus permisos.</p>
        <b>Usuarios</b>
        <p>Los usuarios son aquellos que acceden y usan el panel. Cada usuario tiene asignada una SteamID, e inicia sesión con su cuenta de Steam.</p>
        <p>Los usuarios pueden pertenecer a todos los grupos que el administrador del panel considere oportuno.</p>
        <p>Se pueden crear tantos grupos como sea necesario.</p>

        <b>Grupos</b>
        <p>Un grupo está formado por un conjunto de permisos y de usuarios. Los usuarios que estén añadidos a un grupo tendrán los permisos del mismo.</p>
        <p>Se pueden crear tantos grupos como sea necesario.</p>

        <b>Permisos</b>
        <p>Un permiso permite realizar una o varias acciones en el panel. Están relacionados con uno o varios grupos.</p>
        <p>Los permisos <b>no deben ser editados</b>. Vienen por defecto y bajo ningún concepto deben ser editados a través de la base de datos.</p>
        <p>En caso de haber editado los permisos, habrá que borrar el contenido de la tabla <code>permissions</code> y <code>permission_role</code> y ejecutar el comando <code>php artisan db:seed --PermissionsTableSeeder</code></p>

        <b>Administradores del panel</b>
        <p>Los administradores del panel tienen acceso al ACL. Este permiso es independiente de los grupos y debe ser dado a través de la base de datos directamente.</p>
        <p>Para ello, hay que cambiar la columna <code>admin</code> de la tabla <code>users</code> entre <code>1</code> (administrador del panel) y <code>0</code> (usuario normal).</p>
        <p>No hay límite en cuanto al número de administradores del panel, aunque, como regla general, cuantos menos haya, mejor.</p>
        <p>Los administradores del panel no pueden ser editados por otros administradores del panel. Además, su jugador no puede ser baneado a través del panel.</p>

        <b>Consideraciones</b>
        <p>Los permisos marcados con <code>[!]</code> y <code>[!!!]</code> deben ser considerados peligrosos si se abusan y no deben ser concedidos a cualquiera.</p>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect btn-flat">Cerrar</a>
    </div>
</div>

<br>