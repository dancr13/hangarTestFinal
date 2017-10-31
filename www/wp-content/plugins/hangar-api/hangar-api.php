<?php
   /*
   Plugin Name: Hangar  plugin
   Description: Hangar prueba
   Version: 0
   Author: Daniel Bogarin
   License: GPL2
   */

  function getJsonFromFile( ) {
    $pathFile = plugin_dir_path( __FILE__ ).'songs.json';
    $json = json_decode( file_get_contents( $pathFile ), true );
    return $json;	
  }


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
    
    if(!file_put_contents($pathFile, $currentsongs));
    {
       $update_success =array('message'=>'Canción creada correctamente');
    }
    $response = new WP_REST_Response( $update_success );
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