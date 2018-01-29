<?php
namespace AppBundle\Importer;

use Doctrine\ORM\EntityManager;

class DBChecker
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function checkAndGetObject($type, $identifier){
        switch ($type) {
            case 'category':
                $category = $this->em->getRepository('AppBundle:Category')->findOneBy([
                    'name' => $identifier
                ]);
                if ($category) return $category;
                break;

            case 'user':
                $user = $this->em->getRepository('AppBundle:User')->findOneBy([
                    'email' => $identifier
                ]);
                if ($user) return $user;
                break;
        }
        return false;
    }
}
