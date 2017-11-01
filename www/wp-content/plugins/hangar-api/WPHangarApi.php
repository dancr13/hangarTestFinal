
<?php
class WPHangarAPi
{
  public $url;
  public $id;
  public $songName;
  public $artistiD;
  public $artistName;
  public $albumId;
  public $albumName;

  public function __construct()
  {

  }
  /**
   *
   * Busca una canción 
   *
   * @return  array
   *
   */
  public function search()
  {
    $results = array();
    if (!isset($this->songName) && !isset($this->artistName) && !isset($this->albumName))
      {
      //return $this->artistName;
      $results = $this->getSongList();
      $statusCode = 202;

      $response = new WP_REST_Response($results, $statusCode);
      $response->header('Access-Control-Allow-Origin', apply_filters('giar_access_control_allow_origin', '*'));
      return $response;
    }
    if (isset($this->songName))
      {
      $resultByName = $this->getSongByName($this->songName);
      array_push($results, $resultByName);
    }
    if (isset($this->artistName))
      {
      $resultByArtistName = $this->getSongByArtistName($this->artistName);
      array_push($results, $resultByArtistName);
    }
    if (isset($this->albumName))
      {
      $resultByAlbumName = $this->getSongByAlbumName($this->albumName);
      array_push($results, $resultByAlbumName);
    }

    $resultadoFinal = $this->joinResults($results);

    if (count($results[0]) == 0)
      {
      $results = array('message' => 'No se encontro canciones con esos parametros', 'status' => 'error');
      $statusCode = 404;
    }
    else
      {
      $results = array('songs' => $resultadoFinal);
      $statusCode = 202;
    }

    $response = new WP_REST_Response($results, $statusCode);
    $response->header('Access-Control-Allow-Origin', apply_filters('giar_access_control_allow_origin', '*'));
    return $response;
  }


  /**
   *
   * Actualiza una canción 
   *
   * @return  array
   *
   */
  function update()
  {

    $songsList = $this->getSongList();
    $pathFile = $this->getDBPath();
    $results = array();
    $update_success = false;
    $idFound = false;


    foreach ($songsList as $song)
      {
      foreach ($song as $data)
        {
        if ($data['id'] == $this->id)
          {
          $idFound = true;
          $data['url'] = isset($this->url) ? $this->url : $data['url'];
          $data['songname'] = isset($this->songName) ? $this->songName : $data['songname'];
          $data['artistid'] = isset($this->artistiD) ? $this->artistiD : $data['artistid'];
          $data['artistname'] = isset($this->artistName) ? $this->artistName : $data['artistname'];
          $data['albumid'] = isset($this->albumId) ? $this->albumId : $data['albumid'];
          $data['albumname'] = isset($this->albumName) ? $this->albumName : $data['albumname'];
          array_push($results, $data);
        }
        else
          {
          array_push($results, $data);
        }
      }
    }
    $newListSong = array('songs' => $results);
    $newListSong = json_encode($newListSong);

    if (!$idFound)
      {
      $response = array('message' => 'Esa canción con ese Id no se encuentra', 'status' => 'error');
      $codeResponse = 404;
    }
    else
      {
      if (!file_put_contents($pathFile, $newListSong))
      {
        $response = array('message' => 'Canción actualizada', 'status' => 'ok');
        $codeResponse = 200;
      }
      else
      {
        $response = array('message' => 'Error al actualizar la canción', 'status' => 'error');
        $codeResponse = 404;
      }

    }

    $response = new WP_REST_Response($response, $codeResponse);
    $response->header('Access-Control-Allow-Origin', apply_filters('giar_access_control_allow_origin', '*'));
    return $response;
  }


  /**
   *
   * Borra una canción 
   *
   * @return  array
   *
   */
  function delete()
  {
    $songsList = $this->getSongList();
    $pathFile = $this->getDBPath();
    $results = array();
    $isinList = false;
    $counterAppearances = 0;

    foreach ($songsList as $song)
      {
      foreach ($song as $data)
        {
        if ($data['id'] !== $this->id)
          {
          array_push($results, $data);
        }
        else
          {
          $isinList = true;
        }
      }
    }
    $newListSong = array('songs' => $results);
    $newListSong = json_encode($newListSong);

    if (!$isinList)
      {
      $response = array(
        array('message' => 'Esa canción con ese Id no se encuentra', 'status' => 'error'),
      );
      $codeResponse = 404;
    }
    else
      {
      if (!file_put_contents($pathFile, $newListSong))
      {
        $response = array(
          array('message' => 'canción borrada', 'status' => 'ok'),
        );
        $codeResponse = 200;
      }
      else
      {
        $response = array('message' => 'Error al eliminar la canción', 'status' => 'error');
        $codeResponse = 404;
      }
    }

    $response = new WP_REST_Response($response, $codeResponse);
    $response->header('Access-Control-Allow-Origin', apply_filters('giar_access_control_allow_origin', '*'));
    return $response;
  }

  /**
   *
   * Crea una canción 
   *
   * @return  array
   *
   */
  public function create()
  {
    $newSong = array(
      'url' => $this->url,
      'id' => rand(100, 1000000000),
      'songname' => $this->songName,
      'artistid' => $this->artistiD,
      'artistname' => $this->artistName,
      'albumid' => $this->albumid,
      'albumname' => $this->albumName
    );

    $currentsongs = $this->getSongList();
    $currentsongs = $currentsongs['songs'];
    array_push($currentsongs, $newSong);

    $pathFile = $this->getDBPath();
    $currentsongs = array('songs' => $currentsongs);
    $currentsongs = json_encode($currentsongs);

    if (file_put_contents($pathFile, $currentsongs))
      {
      $statusMessage = array('message' => 'Canción creada correctamente', "status" => "ok");
      $statusCode = 200;
    }
    else
      {
      $statusMessage = array('message' => 'Problema al crear la canción.', "status" => "error");
      $statusCode = 404;
    }
    $response = new WP_REST_Response($statusMessage, $statusCode);
    $response->header('Access-Control-Allow-Origin', apply_filters('giar_access_control_allow_origin', '*'));
    return $response;
  }


  /**
   *
   *  Obtiene una lista de canciones por nombre de canción.
   *
   * @param  string  $songArtistName 
   * @return  array
   *
   */
  private function getSongByName($songnametoseach)
  {
    $songsList = $this->getSongList();
    $result = array();
    foreach ($songsList as $song)
      {
      foreach ($song as $data)
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
  private function getSongByAlbumName($songAlbumtoSeach)
  {
    $songsList = $this->getSongList();
    $result = array();
    foreach ($songsList as $song)
      {
      foreach ($song as $data)
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
  private function getSongByArtistName($songArtistName)
  {
    $songsList = $this->getSongList();
    $result = array();
    foreach ($songsList as $song)
      {
      foreach ($song as $data)
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
  private function joinResults($results)
  {

    $resultadoFinal = array();
    foreach ($results as $result)
      {
      foreach ($result as $song)
        {
        array_push($resultadoFinal, $song);
      }
    }
    return $resultadoFinal;
  }

  /**
   *
   *  Obtiene la ruta del archivo json  .
   *
   * @param   No aplica  
   * @return   string
   *
   */
  private function getDBPath()
  {
    return plugin_dir_path(__FILE__) . 'songs.json';
  }

  /**
   *
   *  Obtiene una estructura json desde un archivo.
   *
   * @param   No aplica  
   * @return  array
   *
   */
  private function getSongList()
  {
    $pathFile = $this->getDBPath();
    $json = json_decode(file_get_contents($pathFile), true);
    return $json;
  }
}

?>
