<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class SiteController extends Controller
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('app_formato');
    }
}