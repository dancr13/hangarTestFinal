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

**Eliminarr canción**
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

* **Respuesta exitosa:**

  * **código:** 200 <br />
    **Content:** `[{"message": "canción borrada", "status": "ok"}]`
 
* **Respuesta erronea:**

  * **Code:** 404 NOT FOUND <br />
    **Cóntent:** `[{"message": "Esa canción con ese Id no se encuentra","status": "warning"}]`

* **Ejemplos de llamdas:**

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







