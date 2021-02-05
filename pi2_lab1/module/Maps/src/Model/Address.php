<?php

namespace Maps\Model;

use Laminas\Db\Adapter as DbAdapter;
use Laminas\Db\Sql\Sql;

class Address implements DbAdapter\AdapterAwareInterface
{
  use DbAdapter\AdapterAwareTrait;

  private function geocode($address)
  {
    $address = urlencode($address); // url encode the address

    // google map geocode api url
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyB_s-LXrbjdnWghWb_xHaJ8UsEMYVO0YZw";
    $resp_json = file_get_contents($url); // get the json response
    $resp = json_decode($resp_json, true); // decode the json

    // response status will be 'OK', if able to geocode given address
    //  if ($resp['status'] == 'OK') {

    // get the important data
    $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
    $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
    $formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
    var_dump($resp);

    // verify if data is complete
    if ($lati && $longi && $formatted_address) {

      // put the data in the array
      $data_arr = array();

      array_push(
        $data_arr,
        $lati,
        $longi,
        $formatted_address
      );
      dump($data_arr);

      return $data_arr;
      // } else {
      //   return false;
      // }
    } else {
      echo "<strong>ERROR: {$resp['status']}</strong>";
      return false;
    }
  }

  private function osm_geocode($address)
  {
    $address = urlencode($address); // url encode the address

    // google map geocode api url
    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$address}";
    var_dump($url);

    $opts = [
      "http" => [
        "method" => "GET",
        "header" => "user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36\r\n" .
          "cookie: _osm_totp_token=254613\r\n"
      ]
    ];

    $context = stream_context_create($opts);

    // Open the file using the HTTP headers set above
    $resp_json = file_get_contents($url, false, $context);


    // $resp_json = file_get_contents($url); // get the json response
    $resp = json_decode($resp_json, true); // decode the json

    // response status will be 'OK', if able to geocode given address
    //  if ($resp['status'] == 'OK') {

    // get the important data
    $lati = isset($resp[0]['lat']) ? $resp[0]['lat'] : "";
    $longi = isset($resp[0]['lon']) ? $resp[0]['lon'] : "";
    // var_dump($resp);
    // verify if data is complete
    if ($lati && $longi) {

      // put the data in the array
      $data_arr = array();

      array_push(
        $data_arr,
        $lati,
        $longi,
      );

      return $data_arr;
    } else {
      return false;
    }
  }


  public function add($dane)
  {
    $result = $this->osm_geocode($dane->address);

    if (!$result) return false;

    $dbAdapter = $this->adapter;

    $sql = new Sql($dbAdapter);
    $insert = $sql->insert('lokalizacja');
    $insert->values([
      'lat' => $result[0],
      'lng' => $result[1],
      'desc' => $dane->details,
      'addr' => isset($result[2]) ? $result[2] : $dane->address,
      'color' => $dane->color,
    ]);

    $selectString = $sql->buildSqlString($insert);
    $wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

    try {
      return $wynik->getGeneratedValue();
    } catch (\Exception $e) {
      return false;
    }
  }

  public function listAll()
  {
    $dbAdapter = $this->adapter;
    $sql = new Sql($dbAdapter);
    $select = $sql->select();
    $select->from(['a' => 'lokalizacja']);

    $selectString = $sql->buildSqlString($select);
    $wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

    return $wynik;
  }

  }
