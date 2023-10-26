# Esto es importantisimo. En desarrollo yo voy a tener una carpeta llamada RESOURCES que tiene 2 sobreescrituras de dos archivos de la carpeta VENDOR
# El tema es que no puedo ponerlo en PROD porque me falla, ya busqué y no encontre la solucion. Asi que voy a optar por modificar la carpeta VENDOR en producción directamente
# Ambos archivos son 
## AbstractToken.php y ContextListener.php

# Y en vendor se encuentran en la ubicacion
## Vendor/Symfony/Security-Core/Authentication/Token/AbstractToken.php
## Vendor/Symfony/Security-Http/Firewall/ContextListener.php

# Y en cada archivo tengo que agregar las siguientes modificaciones
## AbstractToken.php Linea 312 reemplazar
###  $userRoles = array_map('strval', (array) $user->getRoles());
### Por 
###      $userRoles = array_map(function ($role) {
###           return $role->getRole();
###      }, $user->getRoles()); 

## ContextListener.php Linea 312 reemplazar
###  $userRoles = array_map('strval', (array) $refreshedUser->getRoles());
### Por 
###     $userRoles = array_map(function ($role) {
###            return $role->getRole();
###     }, $refreshedUser->getRoles());

###      $userRoles = array_map('strval', $userRoles);

# Esto tambien es importante, por lo tanto en produccion va a tener esos cambios, la carpeta Resource no va a existir y 
# el composer.json en el autoload saco la declaracion de esa importacion. También en el services.yaml hay unos servicios que tengo que sacar



