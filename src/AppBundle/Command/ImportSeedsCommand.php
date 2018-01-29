<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;

class ImportSeedsCommand extends ContainerAwareCommand
{
    private $seeds_url = 'https://raw.githubusercontent.com/TalentNet/coding-challenges/master/data/seeds/electronic-catalog.json';
    private $em;

    protected function configure()
    {
        $this
            ->setName('app:import-seeds')
            ->setDescription('Imports data from seeds document.')
            ->setHelp('This command fetches products from seeds file on github.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $arrContextOptions= [
            "ssl"=>[
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ],
        ];
        $seeds_arr = json_decode( file_get_contents($this->seeds_url, false, stream_context_create($arrContextOptions)), true);
        // save categories and products
        foreach ($seeds_arr['products'] as $productObj){
            $category = new Category();
            $category->setName($productObj['category']);
            $em->persist($category);

            $product = new Product();
            $product->setName($productObj['name']);
            $product->setCategory($category);
            $product->setSku($productObj['sku']);
            $product->setPrice($productObj['price']);
            $product->setQuantity($productObj['quantity']);
            $em->persist($product);
        }
        // save users
        foreach ($seeds_arr['users'] as $userObj){
            $user = new User();
            $user->setFullName($userObj['name']);
            $user->setEmail($userObj['email']);
            $user->setPlainPassword($this->rand_string(10));
            $em->persist($user);
        }
        $em->flush();
    }

    private function rand_string( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars),0,$length);
    }
}
