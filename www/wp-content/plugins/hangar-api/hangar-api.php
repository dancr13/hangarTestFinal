<?php
   /*
   Plugin Name: Hangar  plugin
   Description: Hangar prueba
   Version: 0
   Author: Daniel Bogarin
   License: GPL2
 */

include ('WPHangarApi.php');


add_action('rest_api_init', function () {
  register_rest_route('hangar-api/v1', '/song', array(
    'methods' => 'GET',
    'callback' => 'searchsong',
    'args' => array(
      'songname' => array(
        'required' => false,
        'type' => 'string',
        'description' => __('Nombre de la canción.'),
      ),
      'artistname' => array(
        'required' => false,
        'type' => 'string',
        'description' => __('Artista de la canción.'),
      ),
      'albumname' => array(
        'required' => false,
        'type' => 'string',
        'description' => __('Album de la canción.'),
      ),
    ),
  ));
  register_rest_route('hangar-api/v1', '/song', array(
    'methods' => 'POST',
    'callback' => 'createsong',
    'args' => array(
      'url' => array(
        'required' => false,
        'type' => 'string',
        'description' => __('URL de la canción.'),
      ),
      'songname' => array(
        'required' => true,
        'type' => 'string',
        'description' => __('Nombre de la canción.'),
      ),
      'artistid' => array(
        'required' => false,
        'type' => 'Integer',
        'description' => __('Id del artista.'),
      ),
      'artistname' => array(
        'required' => false,
        'type' => 'string',
        'description' => __('Nombre del Artisa.'),
      ),
      'albumid' => array(
        'required' => false,
        'type' => 'Integer',
        'description' => __('Id del album.'),
      ),
      'albumname' => array(
        'required' => false,
        'type' => 'string',
        'description' => __('Nombre del album.'),
      ),
    ),
  ));
  register_rest_route('hangar-api/v1', '/song', array(
    'methods' => 'DELETE',
    'callback' => 'deleteSong',
    'args' => array(
      'id' => array(
        'required' => true,
        'type' => 'Int',
        'description' => __('Id de la canción.'),
      ),
    ),
  ));
  register_rest_route('hangar-api/v1', '/song', array(
    'methods' => 'PUT',
    'callback' => 'updateSong',
    'args' => array(
      'id' => array(
        'required' => true,
        'type' => 'Int',
        'description' => __('Id de la canción.'),
      ),
      'url' => array(
        'required' => false,
        'type' => 'string',
        'description' => __('URL de la canción.'),
      ),
      'songname' => array(
        'required' => false,
        'type' => 'string',
        'description' => __('Nombre de la canción.'),
      ),
      'artistid' => array(
        'required' => false,
        'type' => 'Integer',
        'description' => __('Id del artista.'),
      ),
      'artistname' => array(
        'required' => false,
        'type' => 'string',
        'description' => __('Nombre del Artisa.'),
      ),
      'albumid' => array(
        'required' => false,
        'type' => 'Integer',
        'description' => __('Id del album.'),
      ),
      'albumname' => array(
        'required' => false,
        'type' => 'string',
        'description' => __('Nombre del album.'),
      ),
    ),
  ));
});

/**
 *
 * Actualiza una canción por id.
 *
 *  array['parameters']
 *          $parameters['url']
 *          $parameters['songname']
 *          $parameters['artistid']
 *          $parameters['artistname']
 *          $parameters['albumid']
 *          $parameters['albumname'] 
 * @param    $parameters  (ver arriba)
 * @return   array
 *
 */
function updateSong($parameters)
{
  $hangarApi = new WPHangarAPi();
  $songId = (int)$parameters['id'];

  $hangarApi->id = $songId;
  $hangarApi->url = isset($parameters['url']) ? $parameters['url'] : null;
  $hangarApi->songName = isset($parameters['songname']) ? $parameters['songname'] : null;
  $hangarApi->artistiD = isset($parameters['artistid']) ? $parameters['artistid'] : null;
  $hangarApi->artistName = isset($parameters['artistname']) ? $parameters['artistname'] : null;
  $hangarApi->albumId = isset($parameters['albumid']) ? $parameters['albumid'] : null;
  $hangarApi->albumName = isset($parameters['albumname']) ? $parameters['albumname'] : null;
  return  $hangarApi->update();
}

/**
 *
 * Consulta o obtiene canciones por parametros. .
 *
 *  array['parameters']
 *          $parameters['songname']
 *          $parameters['artistname']
 *          $parameters['albumname']
 * @param    $parameters  (ver arriba)
 * @return   array
 *
 */
function searchsong($request_data)
{
  $results = array();
  $parameters = $request_data->get_params();

  $hangarApi = new WPHangarAPi();
  $hangarApi->songname = isset($parameters['songname']) ? $parameters['songname'] : null;
  $hangarApi->artistname = isset($parameters['artistname']) ? $parameters['artistname'] : null;
  $hangarApi->albumname = isset($parameters['albumname']) ? $parameters['albumname'] : null;

  return ($hangarApi->search());
}
/**
 *
 * Elimina una canción 
 *
 * $parameters
 *     $parameters['id']
 * @parameters    (ver arriba)
 * @return   array
 *
 */
function deleteSong($parameters)
{
  $songId = (int)$parameters['id'];

  $hangarApi = new WPHangarAPi();
  $hangarApi->id = $songId;
  return $hangarApi->delete();
}
/**
 *
 * Crea una canción y guardar un canción.
 *
 *  array['parameters']
 *          $parameters['url']
 *          $parameters['songname']
 *          $parameters['artistid']
 *          $parameters['artistname']
 *          $parameters['albumid']
 *          $parameters['albumname'] 
 * @param    $parameters  (ver arriba)
 * @return   array
 *
 */
function createSong($parameters)
{
  $hangarApi = new WPHangarAPi();
  $hangarApi->url = isset($parameters['url']) ? $parameters['url'] : $hangarApi->url;
  $hangarApi->songName = isset($parameters['songname']) ? $parameters['songname'] : $hangarApi->songName;
  $hangarApi->artistiD = isset($parameters['artistid']) ? $parameters['artistid'] : $hangarApi->artistiD;
  $hangarApi->artistName = isset($parameters['artistname']) ? $parameters['artistname'] : $hangarApi->artistName ;
  $hangarApi->albumId = isset($parameters['albumid']) ? $parameters['albumid'] : $hangarApi->albumId ;
  $hangarApi->albumName = isset($parameters['albumname']) ? $parameters['albumname'] : $hangarApi->albumName ;
 
  return  $hangarApi->create();
}

?>
