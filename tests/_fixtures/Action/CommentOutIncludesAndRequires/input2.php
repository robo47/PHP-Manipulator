<?php

include implode('', include 'test1.php');
include implode('', require 'test1.php');
include implode('', include_once 'test1.php');
include implode('', require_once 'test1.php');

include_once implode('', include 'test1.php');
include_once implode('', require 'test1.php');
include_once implode('', include_once 'test1.php');
include_once implode('', require_once 'test1.php');

require implode('', include 'test1.php');
require implode('', require 'test1.php');
require implode('', include_once 'test1.php');
require implode('', require_once 'test1.php');

require_once implode('', include 'test1.php');
require_once implode('', require 'test1.php');
require_once implode('', include_once 'test1.php');
require_once implode('', require_once 'test1.php');