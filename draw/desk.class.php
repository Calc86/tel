<?php

require("shapes.class.php");

class Desk
{
    var $im;
    var $db;
    var $objects;
    var $color;
    
    var $w;
    var $h;
    
    function Desk()
    {
	$this->db = mysql_connect("localhost","root","");
	$this->w = 800;
	$this->h = 600;
	$this->objects = array();
	$this->color = array();
	$this->font = array();
	$this->text = array();
	
	//создать картинку
	$this->im = imagecreate($this->w,$this->h);
	$this->color['white'] = imagecolorallocate($this->im, 255, 255, 255);
	$this->color['black'] = imagecolorallocate($this->im, 0, 0, 0);
	
	$this->font['avante_3'] = "./AVANTE_3.TTF";
	$this->font['avant_12'] = "./AVANT_12.TTF";
	$this->font['arial'] = "./ARIAL.TTF";
	
	$this->text['describe']['font'] = $this->font['avant_12'];
	$this->text['describe']['text'] = "© 2009 MX Telecom, Calc";
	$this->text['describe']['size'] = 11;
	$this->text['describe']['color']= $this->color['black'];
	
	$this->text['name']['font'] = $this->font['arial'];
	$this->text['name']['text'] = "Default";
	$this->text['name']['size'] = 11;
	$this->text['name']['color']= $this->color['black'];
	
	
	//массив объектов
	$this->GetDB();
	array_push($this->objects, new Line($this->im,0,0,100,100));
	array_push($this->objects, new Line($this->im,0,0,100,10));
	array_push($this->objects, new Line($this->im,0,0,100,310));
    }
    
    function DrawFrame()
    {
	imagerectangle($this->im, 0, 0, $this->w-1, $this->h-1, $this->color['black']);
	imagerectangle($this->im, 3, 3, $this->w-4, $this->h-4, $this->color['black']);
    }
    
    function Draw()
    {
	//отрисовать
	foreach($this->objects as $obj){
	    $obj->SetOffset(3,3);
	    $obj->Draw();
	}
	
	//вывести картинку
	$this->DrawFrame();
	$this->Describe();
	$this->Name();
	imagepng($this->im);
	//уничтожить
	imagedestroy($this->im);
    }
    
    function Name()
    {
	$bb = imagettfbbox($this->text['name']['size'] , 0, $this->text['name']['font'], $this->text['name']['text']);
	$x = $this->w-$bb[2]-10;	//270 x
	$y = -$bb[5]+10;	//-18 y
	//$x = 200;
	//$y = 400;
	//imagettftext($this->im, $this->text['describe']['size'], 0, $x, $y, $this->color['black'], $this->font['describe'], $this->text['describe']);
	$this->DrawText($x,$y,$this->text['name']);
    }
    
    function Describe()
    {
	//array imagettfbbox ( float $size , float $angle , string $fontfile , string $text )
	//array imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )
	/*
	0	lower left corner, X position
	1	lower left corner, Y position
	2	lower right corner, X position
	3	lower right corner, Y position
	4	upper right corner, X position
	5	upper right corner, Y position
	6	upper left corner, X position
	7	upper left corner, Y position
	*/
	$bb = imagettfbbox($this->text['describe']['size'] , 0, $this->text['describe']['font'], $this->text['describe']['text']);
	$x = $this->w-$bb[2]-10;	//270
	$y = $this->h+$bb[5];	//-18
	$this->DrawText($x,$y,$this->text['describe']);
    }
    
    function DrawText($x,$y,$text)
    {
	imagettftext($this->im, $text['size'], 0, $x, $y, $text['color'], $text['font'], $text['text']);
    }
    
    function GetDB()
    {
	$q = "select 1";
	$r = mysql_query($q);
	$n = mysql_num_rows($r);
	for($i=0;$i<$n;$i++)
	{
	}
    }
}




?>
