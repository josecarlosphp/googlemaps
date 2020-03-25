<?php

namespace josecarlosphp\googlemaps;

/**
 * @desc Clase para enumerar los tipos de mapas de Google Maps.
 * @author José Carlos Cruz Parra
 * @link https://josecarlosphp.com
 */
class GoogleMapTypes
{
	const HYBRID = 'HYBRID';
	const ROADMAP = 'ROADMAP';
	const SATELLITE = 'SATELLITE';
	const TERRAIN = 'TERRAIN';

	static function IsValid($str)
	{
		return defined("GoogleMapTypes::{$str}");
	}

	static function GetDefault()
	{
		return GoogleMapTypes::ROADMAP;
	}
}
