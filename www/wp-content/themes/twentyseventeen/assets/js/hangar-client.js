function buscarCancion() {

    var nombreCancion, colummns, resultadosTabla, nombreCancion,
        nombreArtist, nombreAlbum, parameters;

    nombreCancion = document.getElementById("nombre-cancion").value;
    colummns = '<tr><th>ID</th><th>Canción</th><th>Artista</th><th>Album</th></tr>';
    resultadosTabla = '';
    nombreCancion = jQuery('#nombre-cancion').val();
    nombreArtista = jQuery('#artista-cancion').val();
    nombreAlbum = jQuery('#album-cancion').val();
    parameters = {};

    if (nombreCancion !== '') {
        parameters.songname = nombreCancion;
    }
    if (nombreArtista !== '') {
        parameters.artistname = nombreArtista;
    }
    if (nombreAlbum !== '') {
        parameters.albumname = nombreAlbum;
    }

    jQuery.ajax({
        url: "/wp-json/hangar-api/v1/song",
        type: 'GET',
        data: parameters,
        dataType: "json",
        contentType: "application/json;charset=utf-8;",
        success: function (res) {
            var songs = res.songs;
            jQuery.each(songs, function () {
                resultadosTabla += '<tr>' +
                    '<td>' + this.id + '</td>' +
                    '<td>' + this.songname + '</td>' +
                    '<td>' + this.artistname + '</td>' +
                    '<td>' + this.albumname + '</td>' +
                    '</tr>';
            });

            jQuery("#resultados").html(colummns + resultadosTabla);

            jQuery('#nombre-cancion').val('');
            jQuery('#artista-cancion').val('');
            jQuery('#album-cancion').val('');
        },
        error: function (res) {
            mensaje = JSON.parse(res.responseText);
            mensaje = mensaje.message;
            alert(mensaje);
        }
    });
}

function crearCancion() {

    var idCancion, parameters;
    idCancion = jQuery('#id-cancion').val();
    parameters = {};
    url = jQuery('#id-add-url').val();
    songname = jQuery('#id-add-songname').val();
    artistid = jQuery('#id-add-artistid').val();
    artistname = jQuery('#id-add-artistname').val();
    albumid = jQuery('#id-add-albumid').val();
    albumname = jQuery('#id-add-albumname').val();

    if (songname == '') {
        alert('Debe al menos ponerle un nombre a la canción.');
        return;
    }


    if (url !== '') {
        parameters.url = url;
    }

    if (songname !== '') {
        parameters.songname = songname;
    }

    if (artistid !== '') {
        parameters.artistid = artistid;
    }

    if (artistname !== '') {
        parameters.artistname = artistname;
    }

    if (albumid !== '') {
        parameters.albumid = albumid;
    }

    if (albumname !== '') {
        parameters.albumname = albumname;
    }

    jQuery.ajax({
        url: "/wp-json/hangar-api/v1/song?",
        type: 'POST',
        dataType: "json",
        data: JSON.stringify(parameters),
        contentType: "application/json;charset=utf-8;",
        success: function (res) {

            alert(res.message);
            cleanSectionCreate();
        },
        error: function (res) {
            res = JSON.parse(res.responseText);
            mensaje = res.message;
            alert(mensaje);
            cleanSectionCreate();
        }
    });

}

function cleanSectionCreate() {
    jQuery('#id-add-url').val('');
    jQuery('#id-add-songname').val('');
    jQuery('#id-add-artistid').val('');
    jQuery('#id-add-artistname').val('');
    jQuery('#id-add-albumid').val('');
    jQuery('#id-add-albumname').val('');
    return;
}

function cleanSectionUpdate() {
    jQuery('#id-update-cancion').val('');
    jQuery('#id-update-url').val('');
    jQuery('#id-update-songname').val('');
    jQuery('#id-update-artistid').val('');
    jQuery('#id-update-artistname').val('');
    jQuery('#id-update-albumid').val('');
    jQuery('#id-update-albumname').val('');

}

function actualizarCancion() {

    var idCancion, parameters;
    parameters = {};

    idCancion = jQuery('#id-update-cancion').val();
    url = jQuery('#id-update-url').val();
    songname = jQuery('#id-update-songname').val();
    artistid = jQuery('#id-update-artistid').val();
    artistname = jQuery('#id-update-artistname').val();
    albumid = jQuery('#id-update-albumid').val();
    albumname = jQuery('#id-update-albumname').val();

    if (idCancion == '') {
        alert('Debe de indicar el id de la canción.');
        return;
    } else {
        parameters.id = idCancion;
    }


    if (url !== '') {
        parameters.url = url;
    }

    if (songname !== '') {
        parameters.songname = songname;
    }

    if (artistid !== '') {
        parameters.artistid = artistid;
    }

    if (artistname !== '') {
        parameters.artistname = artistname;
    }

    if (albumid !== '') {
        parameters.albumid = albumid;
    }

    if (albumname !== '') {
        parameters.albumname = albumname;
    }

    jQuery.ajax({
        url: "/wp-json/hangar-api/v1/song",
        type: 'PUT',
        dataType: "json",
        data: JSON.stringify(parameters),
        contentType: "application/json;charset=utf-8;",
        success: function (res) {
            alert(res.message);
            cleanSectionUpdate();
        },
        error: function (res) {
            res = JSON.parse(res.responseText);
            mensaje = res.message;
            alert(mensaje);
            cleanSectionUpdate();
        }
    });

}

function borrarCancion() {
    var idCancion, parameters, mensaje;
    idCancion = jQuery('#id-cancion').val();
    parameters = {};


    if (idCancion == '') {
        alert('Debe de ingresar el id de la canción');
        return;
    }

    jQuery.ajax({
        url: "/wp-json/hangar-api/v1/song?id=" + idCancion,
        type: 'DELETE',
        dataType: "json",
        contentType: "application/json;charset=utf-8;",
        success: function (res) {
            mensaje = res[0].message;
            alert(mensaje);
            jQuery('#id-cancion').val('');
        },
        error: function (res) {
            mensaje = JSON.parse(res.responseText);
            mensaje = mensaje[0].message;
            alert(mensaje);
            jQuery('#id-cancion').val('');
        }
    });

}