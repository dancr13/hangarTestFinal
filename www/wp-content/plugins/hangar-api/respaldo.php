
<?php
   /*
   Plugin Name: Hangar  plugin
   Description: Hangar prueba
   Version: 0
   Author: Daniel Bogarin
   License: GPL2
   */


   add_action('init','hello_world');
   function hello_world()
   {
       return;
   }




  function getJsonFromFile( ) {
    ##$file= 'http://localhost:3000/songs';
    $pathFile = plugin_dir_path( __FILE__ ).'songs.json';
    $json = json_decode( file_get_contents( $pathFile ), true );
    return $json;	
  }


  function getSongByName($songnametoseach)
  {
    $songs= getJsonFromFile();
    $result = array();

    foreach($songs as $song)
    {
      if (strpos($song['songname'], $songnametoseach) !== false) {
        array_push($result, $song);
      }
    } 
    return $result;
  }

  function getSongByAlbumName($songAlbumtoSeach)
  {
    $songs= getJsonFromFile();
    $result = array();

    foreach($songs as $song)
    {
      if (strpos($song['albumname'], $songAlbumtoSeach) !== false) {
        array_push($result, $song);
      }
    } 
    return $result;
  }

  function getSongByArtistName($songArtistName)
  {
    $songs= getJsonFromFile();
    $result = array();

    foreach($songs as $song)
    {
      if (strpos($song['artistname'], $songArtistName) !== false) {
        array_push($result, $song);
      }
    } 
    return $result;
  }
 

  function searchsong($request_data)
  {
    $results = array();
    $parameters = $request_data->get_params();


  if( isset( $parameters['songname'] ) )
  {
    $songName= $parameters['songname']; 
    $resultByName = getSongByName($songName);
    array_push($results, $resultByName);
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


  if( !isset( $parameters['songname'] ) &&  !isset( $parameters['artistname'] ) && !isset( $parameters['albumname']   ))
  {
    $results=getJsonFromFile();;
  }

  $response = new WP_REST_Response( $results );
  $response->header( 'Access-Control-Allow-Origin', apply_filters( 'giar_access_control_allow_origin','*' ) );
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
    $update_success = array('Mesage'=> 'Esa cancion con ese Id no se encuentra.');
  }
  else
  {
    if(!file_put_contents($pathFile, $newListSong));
    {
      $update_success = array('Mesage'=> 'cancion borrada');
    }
  }
      
  $response = new WP_REST_Response( $update_success );
  $response->header( 'Access-Control-Allow-Origin', apply_filters( 'giar_access_control_allow_origin', '*' ) );
  return $response;  
}

  function createSong($parameters)
  {

    $update_success = false;
    $url = isset($parameters['url']) ? $parameters['url'] : "Not url";
    $songName = isset($parameters['songname']) ? $parameters['songname'] : "Los pollitos :( ";
    $artistID = isset($parameters['artistid']) ? $parameters['artistid'] : rand(100,10000);
    $artistname = isset($parameters['artistname']) ? $parameters['artistname'] : 'Chente';
    $albumid = isset($parameters['albumid']) ? $parameters['albumid'] : rand(100,10000);
    $albumname = isset($parameters['albumname']) ? $parameters['albumname'] : 'Grandes exitos';
    
    $newSong= array(
      'id' => rand(100,1000000000),
      'url'=> $url,
      'songname'=> $songName,
      'artistid'=> $artistID,
      'artistname'=>$artistname,
      'albumid'=> $albumid,
      'albumname'=> $albumname
    );

    $currentsongs= getJsonFromFile();
    array_push($currentsongs,$newSong);
    $pathFile = plugin_dir_path( __FILE__ ).'songs.json';
    $currentsongs = json_encode($currentsongs);
    
    if(!file_put_contents($pathFile, $currentsongs));
    {
       $update_success =True;
    }
    
    $response = new WP_REST_Response( $update_success );
    $response->header( 'Access-Control-Allow-Origin', apply_filters( 'giar_access_control_allow_origin', '*' ) );

    return $response;
  }


  add_action( 'rest_api_init', function () {


    register_rest_route( 'hangar-api/v1', '/search', array(
      'methods' => 'GET',
      'callback' => 'searchsong',
    ) );


    register_rest_route( 'hangar-api/v1', '/create', array(
			'methods' => 'POST',
			'callback' =>  'createsong',
    ) );
    

    register_rest_route( 'hangar-api/v1', '/song', array(
			'methods' => 'DELETE',
			'callback' =>  'deleteSong',
		) );





  } );

?>