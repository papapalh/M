<?php

namespace M\Controller\CGI;

abstract class Layout extends \M\Controller\CGI
{
    public $view;
    protected static $layout_name = 'layout';
}