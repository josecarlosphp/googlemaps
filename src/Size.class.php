<?php

namespace josecarlosphp\googlemaps;

/**
 * @desc Clase para encapsular un tamaño rectangular (ancho x alto).
 * @author José Carlos Cruz Parra
 * @link https://josecarlosphp.com
 */
class Size
{
	private $_width;
	private $_height;
	private $_widthUnit;
	private $_heightUnit;

	function __construct($width=0, $height=0, $widthUnit='px', $heightUnit='px')
	{
		$this->_width = doubleval($width);
		$this->_height = doubleval($height);
		$this->_widthUnit = $widthUnit;
		$this->_heightUnit = $heightUnit;
	}

	function SetWidth($valor)
	{
		$this->_width = doubleval($valor);
	}

	function SetHeight($valor)
	{
		$this->_height = doubleval($valor);
	}

	function SetWidthUnit($valor)
	{
		$this->_widthUnit = $valor;
	}

	function SetHeightUnit($valor)
	{
		$this->_heightUnit = $valor;
	}

	function GetWidth()
	{
		return $this->_width;
	}

	function GetHeight()
	{
		return $this->_height;
	}

	function GetWidthUnit()
	{
		return $this->_widthUnit;
	}

	function GetHeightUnit()
	{
		return $this->_heightUnit;
	}

	function GetWidthWithUnit()
	{
		return $this->_width.$this->_widthUnit;
	}

	function GetHeightWithUnit()
	{
		return $this->_height.$this->_heightUnit;
	}
}
