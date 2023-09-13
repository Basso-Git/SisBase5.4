<?php

namespace App\Controller;

use App\Entity\Area;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        dd($entityManager->getRepository(Area::class)->findAll());
        return $this->render('home/index.twig');
    }
}
