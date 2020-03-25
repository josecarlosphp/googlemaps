<?php

namespace josecarlosphp\googlemaps;

/**
 * @desc Clase para generar mapas de Google Maps.
 * @author José Carlos Cruz Parra
 * @link https://josecarlosphp.com
 */
class GoogleMap
{
	private $_sensor;
	private $_region;
	private $_APIVersion;
    private $_APIKey;
	private $_initialLocation;
	private $_zoom;
	private $_divMapId;
	private $_mapType;
	private $_marks;
	private $_dirImages;
	private $_dirIcons;
	private $_shadowImage;
	private $_iconSize;

	function __construct($initialLat=37.182202, $initialLng=-3.598022, $zoom=12, $divMapId='map_canvas', $sensor=false, $region='ES', $APIVersion='3')
	{
		$this->_sensor = ($sensor && $sensor != 'false');
		$this->_region = $region;
		$this->_APIVersion = $APIVersion;
		$this->_initialLocation = new LatLng($initialLat, $initialLng);
		$this->_zoom = intval($zoom);
		$this->_divMapId = $divMapId;

		$this->_mapType = GoogleMapTypes::GetDefault();
		$this->_marks = array();

		$this->_dirImages = defined('SWPHP_THEME_DIR') ? quitarBarra(SWPHP_THEME_DIR.'img/icons/128x128') : 'images';
		$this->_dirIcons = defined('SWPHP_THEME_DIR') ? quitarBarra(SWPHP_THEME_DIR.'img/icons/48x48') : 'images/icons/48x48';
		$this->_shadowImage = 'shadow.png';
		$this->_iconSize = new Size(48, 48, 'px', 'px');
	}

	function SetSensor($valor)
	{
		$this->_sensor = ($valor && $valor != 'false') ? true : false;
	}

	function SetRegion($valor)
	{
		$this->_region = $valor;
	}

	function SetAPIVersion($valor)
	{
		$this->_APIVersion = $valor;
	}

    function SetAPIKey($valor)
	{
		$this->_APIKey = $valor;
	}

	function SetInitialLocation($lat, $lng)
	{
		$this->_initialLocation = new LatLng($lat, $lng);
	}

	function SetZoom($valor)
	{
		$this->_zoom = intval($valor);
	}

	function SetDivMapId($valor)
	{
		$this->_divMapId = $valor;
	}

	function SetMapType($type)
	{
		$this->_mapType = GoogleMapTypes::IsValid($type) ? $type : GoogleMapTypes::GetDefault();
	}

	function SetDirImages($str)
	{
		$pos = strlen($str) - 1;
		if(substr($str, $pos) == '/')
		{
			$str = substr($str, 0, $pos);
		}
		$this->_dirImages = $str;
	}

	function SetDirIcons($str)
	{
		$pos = strlen($str) - 1;
		if(substr($str, $pos) == '/')
		{
			$str = substr($str, 0, $pos);
		}
		$this->_dirIcons = $str;
	}

	function SetShadowImage($valor)
	{
		$this->_shadowImage = $valor;
	}

	function SetIconSize($width, $height, $widthUnit='px', $heightUnit='px')
	{
		$this->_iconSize = new Size($width, $height, $widthUnit, $heightUnit);
	}

	function AddMark($mark)
	{
		$this->_marks[$mark->GetId()] = $mark;
	}

	function RemoveMark($mark)
	{
		unset($this->_marks[$mark->GetId()]);
	}

	function AutoCenter()
	{
		$sLat = 0;
		$sLng = 0;
		$n = 0;
		foreach($this->_marks as $mark)
		{
			$sLat += $mark->GetLat();
			$sLng += $mark->GetLng();
			$n++;
		}

		$this->SetInitialLocation($sLat / $n, $sLng / $n);
	}

	function GetJS()
	{
		//Para algunos dispositivos puede ser necesario incluir esto en el html head:
		//<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

		$html = sprintf('<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=%s&region=%s&v=%s&key=%s"></script>
<!--<script type="text/javascript" src="https://code.google.com/apis/gears/gears_init.js"></script>-->
<script type="text/javascript">
function GoogleMapInitialize() {
  var myOptions = {
    zoom: %u,
    mapTypeId: google.maps.MapTypeId.%s
  };
  var map = new google.maps.Map(document.getElementById("%s"), myOptions);
  map.setCenter(new google.maps.LatLng(%s));
  var shadow = new google.maps.MarkerImage("%s/%s", new google.maps.Size(%s, %s, "%s", "%s"), new google.maps.Point(0,0), new google.maps.Point(0,0));
',
//, new google.maps.Size(24, 24, "px", "px")
			$this->_sensor ? "true" : "false", $this->_region, $this->_APIVersion, $this->_APIKey,
			$this->_zoom,
			$this->_mapType,
			$this->_divMapId,
			$this->_initialLocation->GetLatLng(),
			$this->_dirIcons, $this->_shadowImage, $this->_iconSize->GetWidth(), $this->_iconSize->GetHeight(), $this->_iconSize->GetWidthUnit(), $this->_iconSize->GetHeightUnit()
			);

		//Utilizaremos este array para ir guardando los iconos que creemos y reutilizarlos
		//sin tener que crear un objeto MarkerImage para todos y cada uno de ellos
		$icons = array();

		foreach($this->_marks as $mark)
		{
			$id = $mark->GetId();

			if(!array_key_exists($mark->GetIcon(), $icons))
			{
				$c = sizeof($icons);
				$html .= sprintf('  var icon%u = new google.maps.MarkerImage("%s/%s", new google.maps.Size(%s, %s, "%s", "%s"), new google.maps.Point(0,0), new google.maps.Point(%s,0));'."\n",
				//, new google.maps.Size(24, 24, "px", "px")
					$c,
					$this->_dirIcons,
					$mark->GetIcon(),
					$this->_iconSize->GetWidth(),
					$this->_iconSize->GetHeight(),
					$this->_iconSize->GetWidthUnit(),
					$this->_iconSize->GetHeightUnit(),
					$this->_iconSize->GetWidth() / 2
					);
				$icons[$mark->GetIcon()] = sprintf('icon%u', $c);
			}

			$html .= sprintf("  var pos%s = new google.maps.LatLng(%s);\n", $id, $mark->GetLatLng());
			$html .= sprintf("  var infowindow%s = new google.maps.InfoWindow();\n", $id);
			$html .= sprintf("  infowindow%s.setContent('<div class=\"map_infowindow\"><a href=\"%s\"><img src=\"%s\" alt=\"\" /></a><h3>%s</h3><div>%s</div></div>');\n", $id, $mark->GetUrl() ? $mark->GetUrl() : 'javascript:;', $mark->GetImage(), $mark->GetTitle(), $mark->GetDescription());
			$html .= sprintf("  infowindow%s.setPosition(pos%s);\n", $id, $id);
			$html .= sprintf("  var marker%s = new google.maps.Marker({
    position: pos%s,
    icon: %s,
    shadow: shadow,
    map: map
  });
  google.maps.event.addListener(marker%s, 'click', function() {
    infowindow%s.open(map);
  });%s\n", $id, $id, $icons[$mark->GetIcon()], $id, $id, $mark->GetOpen() ? "infowindow{$id}.open(map);" : '');
		}

		$html .= '}
</script>
';

		return $html;
	}
}
