<?php
namespace PGTRest\Controller;

use PGTRest\Service\ResponseOptionsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class ControllerBasePGT extends AbstractController {


    public function createView(){
        dd('ok');
    }

}