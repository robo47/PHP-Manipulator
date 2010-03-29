<?php

/**
* My Foo Class
*/
class Foo {

/**
* @var Baa
*/
protected $_baa = null;
// next line is not empty

/**
* Some Comment
*
* @param Baa $baa
*/
public function __construct($baa)
{
$this->_baa = $baa;
}

/**
*
* @param Baafoo $baafoo
* @return boolean
*/
public function doSomethingWithBaafoo(Baafoo $baafoo)
{
# Should work
$baafoo->doSomethingElse();
return true;
}
}