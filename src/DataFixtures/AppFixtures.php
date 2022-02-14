<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        
        $faker = Factory::create("FR-fr");

        for ($i = 1; $i <= 30; $i++){
            $ad     = new Ad();

            $title  = $faker->sentence();
            $img    = "https://picsum.photos/1000/350";
            $introduction = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>';
            $ad->setTitle($title)
                ->setCoverImage($img)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(30,250))
                ->setRooms(mt_rand(1,8));

            for ($j = 1; $j <= mt_rand(2,5); $j++){
                $img    = new Image();
                $img->setUrl( "https://picsum.photos/1000/350")
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($img);
            }

            $manager->persist($ad);
    }
        $manager->flush();


    }
}