# PCU

Autor:
https://steamcommunity.com/profiles/Apecengo
https://github.com/apecengo

Licencia: GNU General Public License v3.0

## Características

La PCU (Panel de Control del Usuario) es una solución integral de gestión de usuarios originalmente creada para el servidor POPLife de Plata o Plomo.

- Inicio de sesión con Steam
- Sistema integral de Whitelist
  - Verificación de cuenta, edad y correo electrónico
  - Exámenes
    - Corrección semiautomatizada
  - Generación automática de archivo de whitelist
- Sistema de noticias
- Sistema de notificaciones
- Sistema de nombres
  - Cambios de nombres
  - Sistema anti cambios de nombre
- Sistema de páginas
- Conexión SSO con foros Discourse

Ojo: cuando empecé a desarrollar la PCU, no tenía mucha experiencia. La PCU no tiene tests ni sigue patrones de diseño recomendados

## Requisitos

- Laravel 5.5
- PHP 7+
- Composer
- NPM
- MYSQL/MariaDB
- Servidor SMTP

## Instalación

Instalar la PCU no es difícil, aunque si no te lo explican puede resultar algo cuesta arriba.
Recomendado instalar en Linux para producción. En macOS para desarrollo.

### Instalaciones previas

#### Instalar PHP

Para que la PCU funcione hace falta PHP 7 como mínimo. No hay problema en usar versiones más recientes.

#### Instalar Composer

[Composer](https://getcomposer.org/download/) es un gestor de dependencias para PHP. Se encarga de instalar librerías y de mantenerlas a la versión que le indiquemos.

Las instrucciones de instalación dependen de la versión de Composer. Sigue las instrucciones en su página oficial:

https://getcomposer.org/download/

#### Instalar MYSQL

Instala MYSQL o MariaDB. 
Apunta los datos del usuario que vayas a utilizar.

### Instalar la PCU en sí

Una vez hayas instalado los requisitos, sigue leyendo por aquí.

#### Descargar los archivos

Descarga los archivos con GIT para que actualizar la PCU sea más fácil.
Además, recuerda: según la licencia, **debes hacer públicos claramente los cambios que realices al código**, según la licencia que viene con la PCU: la licencia GNU.

    git clone https://github.com/Apecengo/pcu.git
    
#### Instalar las dependencias

Con Composer, es fácil descargar todas las dependencias y librerías de la PCU:

    composer install

#### Migrar la base de datos    
    
Abre el archivo `.env` y cambia los datos de la base de datos.

    php artisan migrate
    
#### Obtén una clave de API de Steam

Para que el inicio de sesión con Steam funcione, [obtén una clave de API de Steam](https://steamcommunity.com/dev/apikey) y pégala en el .env en la siguiente línea, detrás del `=`:

    STEAM_KEY=
    
#### Genera los archivos estáticos

La PCU genera unos archivos .css y .js comprimidos y optimizados. Hay que reconstruirlos cada vez que se editen

    npm install 
    npm run production
    
Si estás desarrollando, sustituye la anterior línea por:

    npm run dev

## Roadmap
La PCU no está actualmente en desarrollo.







