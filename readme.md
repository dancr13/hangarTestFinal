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

> mysqldump -u root -p  testhangar  < testhangar.sql

#### Accesos

## Admin de wordpress

>  suDomino/wp-admin

1. Usuario: hangar
2. Contraseña: 12345


### API REST

Lo siguiente explica cada operación permitida, los parametros aceptados y la respuesta en cada uno.

**Eliminar canción**
----
  Elimina una o varias canciones
  
* **URL**

  /wp-json/hangar-api/v1/song?id=

* **Métodos:**

  `DELETE`
  
*  **URL Params**

   **Requerdo:**
 
   `id=[integer]`
   
* **Data Params**

  None

* **Respuesta exitosa:**

  * **Código:** 200 <br />
    **Contenido:** `[{"message": "canción borrada", "status": "ok"}]`
 
* **Respuesta erronea:**

  * **Código:** 404 NOT FOUND <br />
    **Contenido:** `[{"message": "Esa canción con ese Id no se encuentra","status": "warning"}]`
    
  * **Código:** 404 NOT FOUND <br />
    **Contenido:** `  { "code": "rest_missing_callback_param", "message": "Missing parameter(s): id", "data": {
        "status": 400,
        "params": ["id"] }}`
    
  

* **Ejemplos de llamadas:**

  * **Post man**
   ![alt text](https://raw.githubusercontent.com/dbogarin88/hangarTestFinal/master/docs/img/delete.png)
  
  * **En jquery**
   ``` javascript
   jQuery.ajax({
        url: "/wp-json/hangar-api/v1/song?id="+idCancion,
        type: 'DELETE',
        dataType:"json",
        contentType:"application/json;charset=utf-8;",
        success: function (res) {
            mensaje= JSON.parse(res.responseText);
            mensaje = mensaje[0].message;
            alert(mensaje);
        },
        error: function(res)
        {
            mensaje= JSON.parse(res.responseText);
            mensaje = mensaje[0].message;
            alert(mensaje);
        }
    });
   ```

**Actualizar canción**
----
  Actualiza una canción por un indentificador
  
* **URL**

  /wp-json/hangar-api/v1/song

* **Métodos:**

  `PUT`
  
*  **URL Params**

   **Requerdo:**
 
   `id=[integer]`
   
   **Opcionales:**
   
   `url=[string]`
   
   `songname=[string]`
   
   `artistid=[integer]`
   
   `artistname=[string]`
   
   `albumid=[integer]`
   
   `albumname=[string]`
   
* **Respuesta exitosa:**

  * **Código:** 200 <br />
    **Contenido:** `{"message": "Canción actualizada", "status": "ok"}`
 
* **Respuesta erronea:**

  * **Código:** 404 NOT FOUND <br />
    **Contenido:** `{ "message": "Esa canción con ese Id no se encuentra","status": "error" }`
    
  * **Código:** 404 NOT FOUND <br />
    **Contenido:** `{
    "code": "rest_missing_callback_param",
    "message": "Missing parameter(s): id",
    "data": {
        "status": 400,
        "params": [
            "id"
        ]
    }
}`
    
    

* **Ejemplos de llamadas:**

  * **Post man**
   ![alt text](https://raw.githubusercontent.com/dbogarin88/hangarTestFinal/master/docs/img/put.png)
  
  * **En jquery**
   ``` javascript
    jQuery.ajax({
        url: "/wp-json/hangar-api/v1/song",
        type: 'PUT',
        dataType:"json",
        data:   JSON.stringify(parameters),
        contentType:"application/json;charset=utf-8;",
        success: function (res) {
            alert(res.message);
        },
        error: function(res)
        {
            res= JSON.parse(res.responseText);
            mensaje = res.message;
            alert(mensaje);
        }
    });

   ```
   
**Buscar canción**
----
  Busca y lista una(s) canción(es)
  
* **URL**

  /wp-json/hangar-api/v1/son

* **Métodos:**

  `GET`
  
*  **URL Params**

   **Requerdo:**
 
   `Ninguno`
   
   **Opcionales:**
   
   `songname=[string]`
   
   `artistname=[string]`
   
   `albumname=[integer]`
   
   
* **Respuesta exitosa:**

  * **Código:** 200 <br />
    **Contenido:** `{
    "songs": [
        {
            "url": "plat",
            "id": 499095116,
            "songname": "Painkiller",
            "artistid": 9958,
            "artistname": "Judas Priest2",
            "albumid": 9874,
            "albumname": "Grandes exitos"
        }
    ]
}`
 
* **Respuesta erronea:**

  * **Código:** 404 NOT FOUND <br />
    **Contenido:** `{ "message": "No se encontro canciones con esos parametros","status": "error" }`

* **Ejemplos de llamadas:**

  * **Post man**
   ![alt text](https://raw.githubusercontent.com/dbogarin88/hangarTestFinal/master/docs/img/get.png)
  
  * **En jquery**
   ``` javascript
    jQuery.ajax({
        url: "/wp-json/hangar-api/v1/song",
        type: 'GET',
        data: parameters,
        dataType: "json",
        contentType: "application/json;charset=utf-8;",
        success: function (res) {
          
        },
        error: function (res) {

        }
    });

   ```

**Agregar  canción**
----
  Agregar una nueva canción
  
* **URL**

  /wp-json/hangar-api/v1/song

* **Métodos:**

  `POST`
  
*  **URL Params**

   **Requerdo:**
 
 `songname=[string]`
   
   **Opcionales:**
   
   `url=[string]`
   
   `artistid=[integer]`
   
   `artistname=[string]`
   
   `albumid=[integer]`
   
   `albumname=[string]`
   
   Al ser opcionales, se agregara información al azar en el contenido de la canción.
   
* **Respuesta exitosa:**

  * **Código:** 200 <br />
    **Contenido:** `{"message": "Canción creada correctamente'", "status": "ok"}`
 
* **Respuesta erronea:**

  * **Código:** 404 NOT FOUND <br />
    **Contenido:** `{ "message": "Problema al crear la canción.","status": "error" }`

* **Ejemplos de llamadas:**

  * **Post man**
   ![alt text](https://raw.githubusercontent.com/dbogarin88/hangarTestFinal/master/docs/img/post.png)
  
  * **En jquery**
   ``` javascript
    jQuery.ajax({
        url: "/wp-json/hangar-api/v1/song?",
        type: 'POST',
        dataType: "json",
        data: JSON.stringify(parameters),
        contentType: "application/json;charset=utf-8;",
        success: function (res) {
            console.log(res);
            alert(res.message);
        },
        error: function (res) {
            res = JSON.parse(res.responseText);
            mensaje = res.message;
            alert(mensaje);
        }
    });

   ```








