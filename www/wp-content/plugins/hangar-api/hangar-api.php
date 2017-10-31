<?php
   /*
   Plugin Name: Hangar  plugin
   Description: Hangar prueba
   Version: 0
   Author: Daniel Bogarin
   License: GPL2
   */



/**
 *
 *  Obtiene un arreglo json desde un archivo.
 *
 * @param  string  $songArtistName 
 * @return  array
 *
 */
  function getJsonFromFile( ) {
    $pathFile = plugin_dir_path( __FILE__ ).'songs.json';
    $json = json_decode( file_get_contents( $pathFile ), true );
    return $json;	
  }

/**
 *
 *  Obtiene una lista de canciones por nombre de canción.
 *
 * @param  string  $songArtistName 
 * @return  array
 *
 */
  function getSongByName($songnametoseach)
  {
    $songsList= getJsonFromFile();
    $result = array();
    foreach($songsList as $song)
    {
      foreach($song as $data)
      {
          if (strpos($data['songname'], $songnametoseach) !== false) 
           {
             array_push($result, $data);
           }
      }
    }
    return $result;
  }

/**
 *
 *  Obtiene una lista de canciones por nombre de album.
 *
 * @param  string  $songArtistName 
 * @return  array
 *
 */
  function getSongByAlbumName($songAlbumtoSeach)
  {
    $songsList= getJsonFromFile();
    $result = array();
    foreach($songsList as $song)
    {
      foreach($song as $data)
      {
          if (strpos($data['albumname'], $songAlbumtoSeach) !== false) 
           {
             array_push($result, $data);
           }
      }
    }
    return $result;
  }

/**
 *
 *  Obtiene una lista de canciones por nombre de Artista.
 *
 * @param  string  $songArtistName
 * @return  array
 *
 */
  function getSongByArtistName($songArtistName)
  {
    $songsList= getJsonFromFile();
    $result = array();
    foreach($songsList as $song)
    {
      foreach($song as $data)
      {
          if (strpos($data['artistname'], $songArtistName) !== false) 
           {
             array_push($result, $data);
           }
      }
    }
    return $result;
  }


/**
 *
 *  Da formato a un arreglo de resultados  .
 *
 *  array['results']
 *          $results['url']
 *          $results['id']
 *          $results['songname']
 *          $results['artistid']
 *          $results['artistname']
 *          $results['albumid']
 *          $results['albumname']
 * @param   $results  (ver arriba)
 * @return   array
 *
 */
function joinResults($results)
  {

    $resultadoFinal= array();
    foreach($results as $result)
    {
      foreach($result as $song)
      {
        array_push($resultadoFinal, $song);
      }
    }
    return $resultadoFinal;
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

  if( !isset( $parameters['songname'] ) &&  !isset( $parameters['artistname'] ) && !isset( $parameters['albumname']   ))
  {
    $results=getJsonFromFile();
    $statusCode = 202;

    $response = new WP_REST_Response( $results, $statusCode );
    $response->header( 'Access-Control-Allow-Origin', apply_filters( 'giar_access_control_allow_origin','*' ) );
    return $response;
  
  }

  if( isset( $parameters['songname'] ) )
  {
    $songName= $parameters['songname']; 
    $resultByName = getSongByName($songName);
    array_push($results, $resultByName);
//    return $results[0];
  }
  if( isset( $parameters['artistname'] ))
  {
    $artistName= $parameters['artistname']; 
    $resultByArtistName = getSongByArtistName($artistName);
    array_push($results, $resultByArtistName);
  

  }
  if( isset( $parameters['albumname'] ))
  {
    $albumname = $parameters['albumname'];
    $resultByAlbumName = getSongByAlbumName($albumname);
    array_push($results, $resultByAlbumName);
  }
    
  $resultadoFinal= joinResults($results);

    if(count($results[0])==0)
    {
      $results = array('message'=>'No se encontro canciones con esos parametros', 'status'=>'error');
      $statusCode = 404;
    }
    else
    {
      $results = array('songs'=>$resultadoFinal);
      $statusCode = 202;
    }

  $response = new WP_REST_Response( $results, $statusCode );
  $response->header( 'Access-Control-Allow-Origin', apply_filters( 'giar_access_control_allow_origin','*' ) );
  return $response;

}


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

  $songId= (int)$parameters['id'];
  $songsList= getJsonFromFile();
  $pathFile = plugin_dir_path( __FILE__ ).'songs.json';
  $results= array();
  $update_success =false;
  $idFound  = false;
  foreach($songsList as $song)
  {
    foreach($song  as $data)
    {
         if($data['id'] == $songId )
         {
          $idFound = true;
          $url = isset($parameters['url']) ? $parameters['url'] : $data['url'];
          $songName = isset($parameters['songname']) ? $parameters['songname'] : $data['songname'];
          $artistID = isset($parameters['artistid']) ? $parameters['artistid'] : $data['artistid'];
          $artistname = isset($parameters['artistname']) ? $parameters['artistname'] : $data['artistname'];
          $albumid = isset($parameters['albumid']) ? $parameters['albumid'] :  $data['albumid'];
          $albumname = isset($parameters['albumname']) ? $parameters['albumname'] : $data['albumname'];

          $data['url'] = $url;
          $data['songname'] = $songName;
          $data['artistid'] = $artistID;
          $data['artistname'] = $artistname;
          $data['albumid'] = $albumid;
          $data['albumname'] = $albumname;
          array_push($results, $data);

         }
         else
         {
          array_push($results, $data);
         }
    }
  }
  $newListSong =  array( 'songs' =>  $results);
  $newListSong = json_encode($newListSong);


  if(!$idFound)
  {
    $response = array('message'=> 'Esa canción con ese Id no se encuentra', 'status'=> 'error');
    $codeResponse = 404;
  }
  else
  {
    if(!file_put_contents($pathFile, $newListSong));
    {
      $response = array('message'=> 'Canción actualizada', 'status'=> 'ok');
      $codeResponse = 200;
    }
  }
      
  $response = new WP_REST_Response( $response, $codeResponse );
  $response->header( 'Access-Control-Allow-Origin', apply_filters( 'giar_access_control_allow_origin', '*' ) );
  return $response;  
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
  $songId= (int)$parameters['id'];
  $songsList= getJsonFromFile();
  $pathFile = plugin_dir_path( __FILE__ ).'songs.json';
  $results= array();
  $update_success =false;
  $counterAppearances = 0;

  foreach($songsList as $song)
  {
    foreach($song  as $data)
    {
         if($data['id'] !== $songId )
         {
            array_push($results, $data);
         }
         else
         {
            $counterAppearances++;
         }
    }
  }
  $newListSong =  array( 'songs' =>  $results);
  $newListSong = json_encode($newListSong);


  if($counterAppearances == 0)
  {
    $response = array(
      array('message'=> 'Esa canción con ese Id no se encuentra', 'status'=> 'error'),
      
    );
    $codeResponse = 404;
  }
  else
  {
    if(!file_put_contents($pathFile, $newListSong));
    {
      
      $response = array(
        array('message'=> 'canción borrada', 'status'=> 'ok'),
      );
      $codeResponse = 200;
      
    }
  }
      
  $response = new WP_REST_Response( $response , $codeResponse );
  $response->header( 'Access-Control-Allow-Origin', apply_filters( 'giar_access_control_allow_origin', '*' ) );
  return $response;  
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

    $update_success =array('message'=>'Error al crear la canción.');
    $url = isset($parameters['url']) ? $parameters['url'] : "Not url";
    $songName = isset($parameters['songname']) ? $parameters['songname'] : "Los pollitos :( ";
    $artistID = isset($parameters['artistid']) ? $parameters['artistid'] : rand(100,10000);
    $artistname = isset($parameters['artistname']) ? $parameters['artistname'] : 'Chente';
    $albumid = isset($parameters['albumid']) ? $parameters['albumid'] : rand(100,10000);
    $albumname = isset($parameters['albumname']) ? $parameters['albumname'] : 'Grandes exitos';
    
    $newSong= array(
      'url'=> $url,
      'id' => rand(100,1000000000),
      'songname'=> $songName,
      'artistid'=> $artistID,
      'artistname'=>$artistname,
      'albumid'=> $albumid,
      'albumname'=> $albumname
    );

    $currentsongs= getJsonFromFile();
    $currentsongs =$currentsongs['songs'];
    array_push($currentsongs,$newSong);

    $pathFile = plugin_dir_path( __FILE__ ).'songs.json';
    $currentsongs= array('songs'=> $currentsongs);
    $currentsongs =  json_encode($currentsongs);
    
    if(file_put_contents($pathFile, $currentsongs))
    {
      $statusMessage =array('message'=>'Canción creada correctamente', "status"=> "ok");
      $statusCode = 200;

    }
    else
    {
      $statusMessage =array('message'=>'Problema al crear la canción.', "status"=> "error");
      $statusCode = 404;
    }
    $response = new WP_REST_Response( $statusMessage, $statusCode );
    $response->header( 'Access-Control-Allow-Origin', apply_filters( 'giar_access_control_allow_origin', '*' ) );
    return $response;
  }


  add_action( 'rest_api_init', function () {


    register_rest_route( 'hangar-api/v1', '/song', array(
      'methods' => 'GET',
      'callback' => 'searchsong',
      'args'                => array(
        'songname' => array(
          'required'=> false,
          'type'        => 'string',
          'description' => __( 'Nombre de la canción.' ),
        ),
        'artistname' => array(
          'required'=> false,
          'type'        => 'string',
          'description' => __( 'Artista de la canción.' ),
        ),
        'albumname' => array(
          'required'=> false,
          'type'        => 'string',
          'description' => __( 'Album de la canción.' ),
        ),
      ),
    ) );


    register_rest_route( 'hangar-api/v1', '/song', array(
			'methods' => 'POST',
      'callback' =>  'createsong',
      'args'                => array(
        'url' => array(
          'required'=> false,
          'type'        => 'string',
          'description' => __( 'URL de la canción.' ),
        ),

        'songname' => array(
          'required'=> true,
          'type'        => 'string',
          'description' => __( 'Nombre de la canción.' ),
        ),

        'artistid' => array(
          'required'=> false,
          'type'        => 'Integer',
          'description' => __( 'Id del artista.' ),
        ),
        'artistname' => array(
          'required'=> false,
          'type'        => 'string',
          'description' => __( 'Nombre del Artisa.' ),
        ),
        'albumid' => array(
          'required'=> false,
          'type'        => 'Integer',
          'description' => __( 'Id del album.' ),
        ),
        'albumname' => array(
          'required'=> false,
          'type'        => 'string',
          'description' => __( 'Nombre del album.' ),
        ),
      ),
    ) );

    register_rest_route( 'hangar-api/v1', '/song', array(
			'methods' => 'DELETE',
      'callback' =>  'deleteSong',
      'args'                => array(
        'id' => array(
          'required'=> true,
          'type'        => 'Int',
          'description' => __( 'Id de la canción.' ),
        ),
      ),
    ) );
    
    register_rest_route( 'hangar-api/v1', '/song', array(
			'methods' => 'PUT',
      'callback' =>  'updateSong',
      'args'                => array(
        'id' => array(
          'required'=> true,
          'type'        => 'Int',
          'description' => __( 'Id de la canción.' ),
        ),
        'url' => array(
          'required'=> false,
          'type'        => 'string',
          'description' => __( 'URL de la canción.' ),
        ),

        'songname' => array(
          'required'=> false,
          'type'        => 'string',
          'description' => __( 'Nombre de la canción.' ),
        ),

        'artistid' => array(
          'required'=> false,
          'type'        => 'Integer',
          'description' => __( 'Id del artista.' ),
        ),
        'artistname' => array(
          'required'=> false,
          'type'        => 'string',
          'description' => __( 'Nombre del Artisa.' ),
        ),
        'albumid' => array(
          'required'=> false,
          'type'        => 'Integer',
          'description' => __( 'Id del album.' ),
        ),
        'albumname' => array(
          'required'=> false,
          'type'        => 'string',
          'description' => __( 'Nombre del album.' ),
        ),
      ),
      
		) );

  } );

?>