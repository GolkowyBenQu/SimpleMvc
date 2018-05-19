<?php

namespace App\Controller;

use Framework\Controller;
use Framework\Exception\FrameworkException;

class IndexController extends Controller
{
    /**
     * @return string
     * @throws FrameworkException
     */
    public function indexAction()
    {
        return $this->render('Index\index');
    }

    /**
     * @return string
     * @throws FrameworkException
     */
    public function contactAction()
    {
        return $this->render('Index\contact');
    }
}