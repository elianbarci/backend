
# Ask Vader

Preguntarle al mismisimo Darth Vader que opina sobre personajes, planetas y vehiculos de la saga.

Postman API documentation: https://www.postman.com/galactic-turtles/workspace/public-workspace/collection/12429990-8dab3b3e-4a7e-4554-9b3d-432e57013039?action=share&creator=12429990






## Instalación

Para iniciar el proyecto hay que clonar el repositorio y luego escribir los siguientes comandos:

```bash
  composer install
```

```bash
  php artisan passport:install
```
    
```bash
  php artisan passport:keys
```

```bash
  php artisan migrate
```
## Rate Limiter

Para evitar que se hagan demasiados requests por parte de una misma IP se modifico el Rate Limiter de Laravel de 60 a 10. Este se encuentra el RouteServiceProvider.php

```code
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
});
```
## Observaciónes

La API Swapi tiene datos repetidos en el "name" por lo que se tuvieron que hacer cambios tales como poner Unique en las migrations correspondientes.

