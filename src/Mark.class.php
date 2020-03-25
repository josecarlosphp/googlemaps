<?php

namespace josecarlosphp\googlemaps;

/**
 * @desc Clase para encapsular una marca para usarla en un mapa de Google.
 * @author José Carlos Cruz Parra
 * @link https://josecarlosphp.com
 */
class Mark
{
	private $_id;
	private $_location;
	private $_title;
	private $_description;
	private $_image;
	private $_icon;
	private $_url;
	private $_open;

	function __construct($id, $lat, $lng, $title, $description, $image, $icon, $url='', $open=false)
	{
		$this->_id = $id;
		$this->_location = new LatLng($lat, $lng);
		$this->_title = $title;
		$this->_description = $description;
		$this->_image = $image;
		$this->_icon = $icon;
		$this->_url = $url;
		$this->_open = $open;
	}

	function GetId()
	{
		return $this->_id;
	}

	function GetLocation()
	{
		return $this->_location;
	}

	function GetLat()
	{
		return $this->_location->GetLat();
	}

	function GetLng()
	{
		return $this->_location->GetLng();
	}

	function GetLatLng()
	{
		return $this->_location->GetLatLng();
	}

	function GetTitle()
	{
		return $this->_title;
	}

	function GetDescription()
	{
		return $this->_description;
	}

	function GetImage()
	{
		return $this->_image;
	}

	function GetIcon()
	{
		return $this->_icon;
	}

	function GetUrl()
	{
		return $this->_url;
	}

	function GetOpen()
	{
		return $this->_open;
	}
}
