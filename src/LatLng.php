<?php

namespace josecarlosphp\googlemaps;

/**
 * @desc Clase para encapsular coordenadas de longitud y latitud.
 * @author José Carlos Cruz Parra
 * @link https://josecarlosphp.com
 */
class LatLng
{
	private $_lat;
	private $_lng;

	function __construct($lat, $lng)
	{
		$this->_lat = floatval($lat);
		$this->_lng = floatval($lng);
	}

	function GetLat()
	{
		return $this->_lat;
	}

	function GetLng()
	{
		return $this->_lng;
	}

	function GetLatLng()
	{
		return sprintf('%s,%s', number_format($this->_lat, 6, '.', ''), number_format($this->_lng, 6, '.', ''));
	}

	function SetLat($lat)
	{
		return $this->_lat = floatval($lat);
	}

	function SetLng($lng)
	{
		return $this->_lng = floatval($lng);
	}

	function SetLatLng($lat, $lng)
	{
		$this->_lat = floatval($lat);
		$this->_lng = floatval($lng);
	}
}
