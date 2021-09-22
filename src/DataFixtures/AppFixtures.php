<?php

namespace App\DataFixtures;

use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\Style;
use App\Entity\Category;
use App\Entity\Colection;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Slider;
use App\Entity\StatusSite;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // On crée une instance de Faker en français
        $faker = Faker\Factory::create('fr_FR');

        // Création des fausses catégories
        $category1 = New Category();
        $category1->setName('Bracelets');
        $category1->setPicture($faker->word().'.jpg');
            $manager->persist($category1);
        $category2 = New Category();
        $category2->setName('Colliers');
        $category2->setPicture($faker->word().'.jpg');
            $manager->persist($category2);
        $category3 = New Category();
        $category3->setName('Bagues');
        $category3->setPicture($faker->word().'.jpg');
            $manager->persist($category3);
        $category4 = New Category();
        $category4->setName('Boucles d\'oreilles');
        $category4->setPicture($faker->word().'.jpg');
            $manager->persist($category4);
        // On les range dans un tableau pour pouvoir les réutiliser plus tard
        $categories = [$category1, $category2, $category3, $category4];
    
        // Création des fausses collections
        $collection1 = new Colection();
        $collection1->setName('Printemps');
        $collection1->setPicture('printemps.jpg');
            $manager->persist($collection1);
        $collection2 = new Colection();
        $collection2->setName('Ete');
        $collection2->setPicture('ete.jpg');
            $manager->persist($collection2);
        $collection3 = new Colection();
        $collection3->setName('Hiver');
        $collection3->setPicture('hiver.jpg');
            $manager->persist($collection3);
        $collection4 = new Colection();
        $collection4->setName('Automne');
        $collection4->setPicture('automne.jpg');
            $manager->persist($collection4);
        // On les range dans un tableau pour pouvoir les réutiliser plus tard
        $collections= [$collection1, $collection2, $collection3, $collection4];
                

        // Création des faux styles
        $style1 = new Style();
        $style1->setName('Longs');
        $style1->setCategory($category2);
            $manager->persist($style1);
        $style2 = new Style();
        $style2->setName('Courts');
        $style2->setCategory($category2);
            $manager->persist($style2);
        $style3 = new Style();
        $style3->setName('Puces');
        $style3->setCategory($category4);
            $manager->persist($style3);
        $style4 = new Style();
        $style4->setName('Crochets');
        $style4->setCategory($category4);
            $manager->persist($style4);
        $style5 = new Style();
        $style5->setName('Créoles');
        $style5->setCategory($category4);
            $manager->persist($style5);
        // On les range dans un tableau pour pouvoir les réutiliser plus tard
        $styles = [$style1, $style2, $style3, $style4, $style5];
    

        // Création des faux produits
        // On les range dans un tableau pour pouvoir les réutiliser plus tard
        $products = [];  
        for ($i=0; $i < 20; $i++) { 
            $product = new Product();
            $product->setName($faker->word());
            $product->setPrice($faker->randomFloat(2,1,100));
            $product->setDescription($faker->text(100));
            $product->setPicture1($faker->word().'.jpg');
            $product->setPicture2($faker->word().'.jpg');
            $product->setPicture3($faker->word().'.jpg');
            $product->setStock($faker->randomDigit());
            $product->setCategory($categories[array_rand($categories,1)]);
            $product->setStyle($styles[array_rand($styles,1)]);
            $product->setColection($collections[array_rand($collections,1)]);
                $manager->persist($product);
            $products[] = $product;
        }
        

        // Création des utilisateurs
        // On les range dans un tableau pour pouvoir les réutiliser plus tard
        $users = []; 
        for ($i=0; $i < 8; $i++) { 
            $user = new User();
            $firstnameAndPassword = $faker->firstName();
            $user->setFirstname($firstnameAndPassword);
            $user->setLastname($faker->lastName());
            $user->setEmail($faker->email());
            $user->setPassword($this->encoder->encodePassword($user,$firstnameAndPassword));
            $user->setPhoneNumber($faker->numberBetween(0000000000,9999999999));
            $user->addProduct($products[array_rand($products,1)]);
            $user->addProduct($products[array_rand($products,1)]);
                $manager->persist($user);
            $users[] = $user;
        }

        // Création des commandes
        $orders = [];  
        for ($i=0; $i < 6; $i++) { 
            $order= new Order();
            // $order->addOrderLine($orderLines[array_rand($orderLines,1)]);
            $order->setUser($users[array_rand($users,1)]);
                $manager->persist($order);
            $orders[] = $order;
            }


        // Création des lignes de commandes
        // On les stock dans un tableau
        $orderLines = [];
        for ($i=0; $i < 35; $i++) { 
            $orderLine = new OrderLine();
            $orderLine->setQuantity($faker->numberBetween(1,5));
            // On sélectionne un produit random dans le tableau des produits, puis le convertit en objet
            $orderLine->setLabelProduct('Produit n° '.$i);
            $orderLine->setPriceProduct($faker->randomFloat(2,1,100));
            $orderLine->setOrderEntity($orders[array_rand($orders,1)]);
                $manager->persist($orderLine);
            $orderLines[] = $orderLine;
        }
                
        // Statut du site
        $status1 = new StatusSite();
            $manager->persist($status1);
        

        // Slider
        $slider1 = new Slider();
        $slider1->setPicture('slider1.jpg');
        $slider1->setActive(true);
            $manager->persist($slider1);
        $slider2 = new Slider();
        $slider2->setPicture('slider2.jpg');
        $slider2->setActive(false);
            $manager->persist($slider2);
        $slider3 = new Slider();
        $slider3->setPicture('slider3.jpg');
        $slider3->setActive(true);
            $manager->persist($slider3);

        $userAdmin = new User();
        $userAdmin->setFirstname("vendeuse");
        $userAdmin->setLastname("vendeuse");
        $userAdmin->setEmail("vendeuse@gmail.com");
        $userAdmin->setPassword($this->encoder->encodePassword($user,"vendeuse"));
        $userAdmin->setPhoneNumber('0111111111');
        $userAdmin->setRoles(['ROLE_ADMIN']);
            $manager->persist($userAdmin);

        // On envoie le tout en BDD
        $manager->flush();
    }
}
