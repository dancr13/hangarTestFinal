Descripción - API Prueba HANGAR
==================

El código en este repositorio es un sitio wordpress, basicamente con dos plugins instaldos.

1.Plugin WP JSON API

2.Plugin creado que con tiene la api de está prueba. 


### Requirimientos:
1.PHP version 7  o superior.

2.MySQL version 5.6 o superior.

3.Servidor web(apache o nginx)

### Despligue

Para este paso debe de configurar su servidor web ya sea apache o nginx.

## Cargar base de datos

En el siguiente comando, edite el usuario que usa  para conectarse a mysql y ejecute lo siguiente:

> mysqldump -u root -p  testhangar  > testhangar.sql

#### Accesos

## Admin de wordpress

>  suDomino/wp-admin

1. Usuario: hangar
2. Contraseña: 12345


### API REST

Lo siguiente explica cada operación permitida, los parametros aceptados y la respuesta en cada uno.

**Eliminr canción**
----
  Elimina una o varias canciones
  
* **URL**

  /wp-json/hangar-api/v1/song?id=

* **Method:**

  `DELETE`
  
*  **URL Params**

   **Required:**
 
   `id=[integer]`
   
* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** `[{"message": "canción borrada", "status": "ok"},200]`
 
* **Error Response:**

  * **Code:** 404 NOT FOUND <br />
    **Content:** `[{"message": "Esa canción con ese Id no se encuentra","status": "warning"},400]`

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/users/1",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```





