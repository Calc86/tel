<?php

class Object
{
    var $ox;
    var $oy;
    var $im;
    function Object(&$im)
    {
	$this->im = $im;
	$this->ox = 0;
	$this->oy = 0;
    }
    
    function Draw()
    {
    }
    
    function SetOffset($x,$y)
    {
	$this->ox = $x;
	$this->oy = $y;
    }
}

class Line extends Object
{
    var $x1;
    var $y1;
    var $x2;
    var $y2;

    function Line(&$im, $x1,$y1,$x2,$y2)
    {
	parent::__construct($im);

	$this->x1 = $x1;
	$this->y1 = $y1;
	$this->x2 = $x2;
	$this->y2 = $y2;
    }

    function Draw()
    {
	$black = imagecolorallocate($this->im, 0, 0, 0);
	imageline($this->im,
	    $this->x1+$this->ox,
	    $this->y1+$this->oy,
	    $this->x2+$this->ox,
	    $this->y2+$this->oy,
	    $black);
    }
}

class Text extends Object
{
    var $str;
    var $x;
    var $y;
    var $font;
    var $size;
    var $box;	//0 or 1
    var $color;
    
    function Text(&$im,$x,$y,$str,$font,$size, $color, $box=0)
    {
	parent::__construct($im);
	
	$this->x = $x;
	$this->y = $y;
	$this->font = $font;
	$this->size = $size;
	$this->str = $str;
	$this->box = $box;
	$this->color= $color;
    }
    
    function Draw()
    {
	imagettftext($this->im, $this->size, $this->angle, $this->x, $this->y, $this->color, $this->file, $this->str );
    }
}

?>