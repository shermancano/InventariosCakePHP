# InventariosCakePHP
Proyecto para inventarios sobre cakephp

## CONFIGURACION PROYECTO DAEMARAUCO DOCKER PHP 5.4 Postgres 10

## CONFIGURACION MEDIANTE XAMPP
```
Habilitar extensiones en php.ini
extension=php_pdo_pgsql.dll
extension=php_pdo_sqlite.dll
extension=php_pgsql.dll

copiar c:/xampp/php/libpq.dll
en c:/xampp/apache/bin/
```
## CONFIGURACION GIT
```
Error git config
git config --replace-all user.email "you@domain.com"
git config --replace-all user.name "github_username"

Permisos git
git config core.fileMode false
```

## Instalar docker-compose
`https://docs.docker.com/engine/install/ubuntu/`

## Iniciar proyecto
`docker-compose up --build`

## Bajar proyecto
`docker-compose up --build`

## Conexión BD pgAdmin
`Sobre container de postgres dar "Inspect" y buscar IPAddress para agregar a host`

## Copiar archivos a un container
`docker cp <origen> <contenedor>:<destino>`
`docker cp backupinvarauco29052019.sql pg_container:home`

## Conexión a postgres
`docker exec -it <name_container> psql -U root`

## Backup BD postgres
`docker exec <name_container> pg_dumpall -U root > <ruta/archivo.sql>`

## Restaurar BD postgres
`docker exec -it <nombre_o_ID_del_contenedor> bash`
`psql -U <nombre_usuario> -d <nombre_base_datos> -f <archivo_respaldo.sql>`
`psql -U postgres -d daemarauco -f /home/backupinvarauco29052019.sql`
