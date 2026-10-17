# Descargar cambios y elegir la rama correcta

## Comprobar el repositorio

Ejecutar desde la carpeta del proyecto:

```sh
git remote -v
git status -sb
```

- `Jazielle/Gestion_Ambiental`: repositorio de práctica. La tarea web de estaciones está en `feature/4-web-registro-estaciones`, PR #5 hacia `develop`.
- `elingeyesdev/t6-medicion-ambiental`: repositorio oficial de GeoCruz. El visor web de M2 está en `feature/19-web-focos-calor`, PR #26 hacia `develop`.

Las ramas de un repositorio no aparecen al descargar el otro. Los nombres y PR anteriores se comprobaron el 17/10/2026; el estado de cada PR puede cambiar después.

## Descargar las referencias publicadas

```sh
git fetch origin
git branch -r
```

`fetch` descarga los commits y actualiza las referencias remotas. No cambia la rama abierta ni integra esos cambios en `develop`. Las líneas que empiezan por `origin/` son ramas remotas conocidas por esta copia.

## Abrir la web de estaciones de esta práctica

Si todavía no existe una rama local con ese nombre:

```sh
git switch --track origin/feature/4-web-registro-estaciones
```

Si ya existe, usar en su lugar:

```sh
git switch feature/4-web-registro-estaciones
git pull --ff-only origin feature/4-web-registro-estaciones
```

Comprobar el resultado:

```sh
git branch --show-current
git log -5 --oneline
git status -sb
```

Guardar el trabajo propio antes de cambiar de rama. Si `pull --ff-only` informa de historias divergentes, revisar esos commits con el compañero antes de decidir cómo integrarlos; no descartar su trabajo.

## Abrir el visor de M2 en el repositorio oficial

Desde la copia cuyo `origin` es `elingeyesdev/t6-medicion-ambiental`, ejecutar:

```sh
git fetch origin
git branch -r
```

La primera vez:

```sh
git switch --track origin/feature/19-web-focos-calor
```

Si esa rama local ya existe:

```sh
git switch feature/19-web-focos-calor
git pull --ff-only origin feature/19-web-focos-calor
```

La preparación y el arranque están en el README de ese repositorio. No copiar la práctica de estaciones para sustituir la implementación oficial. Tener una rama de Flutter publicada tampoco acredita que ya contenga la aplicación; comprobar sus commits y archivos.

## Cómo se organizan las ramas

`main` conserva versiones estables. `develop` integra cambios revisados. De `develop` salen ramas por tarea, como `feature/4-web-registro-estaciones`; después se revisa su PR y se integra según el flujo del equipo.

Una rama no es una carpeta dentro de otra rama: es una referencia a una historia de commits. Las carpetas `web/`, `movil/` y `compartido/` separan el código dentro del mismo repositorio. Una rama puede contener las tres carpetas aunque su tarea solo cambie la web.

Una rama permanente llamada `web` es una opción de organización que el equipo puede acordar; el flujo actual ya separa las tareas mediante `feature/...` y sus PR hacia `develop`. Mientras un PR esté abierto y sin fusionar, descargar únicamente `develop` no incorpora sus cambios.

Los commits se consultan con `git log`; el selector de ramas muestra ramas. Para ver ambos relacionados:

```sh
git log --oneline --decorate --graph --all -20
```
