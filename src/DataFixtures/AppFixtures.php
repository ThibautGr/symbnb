<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use phpDocumentor\Reflection\Type;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("FR-fr");


        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $adminUser = new User();
        $adminUser->setFirstName('Thib')
                    ->setLastName('Ger')
                    ->setEmail('mail.mail@mail.com')
        ->setHash($this->hasher->hashPassword($adminUser, 'password123'))
        ->setPicture('https://cdnb.artstation.com/p/users/avatars/000/651/565/large/e33d6c26a1742fecfe5bc1d50d89d8d5.jpg?1621520414')
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>'.join('<p>',$faker->paragraphs(3)).'</p>')
            ->addUserRole($adminRole);
        $manager->persist($adminUser);



        $genres = ['male', 'female'];
        //users:
        $users = [];
        for ($i = 1; $i <= 10; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);
            $picture = "https://randomuser.me/pi/portraits/thumb/";
            $idPicture = $faker->numberBetween(1,99). 'jpg';

//            if ($genre == "male") $picture = $picture .'men/'.$idPicture;
//            else $picture = $picture.'woman/'.$idPicture;
            $picture .=  ($genre == 'male'? 'men/' : 'women/') .$idPicture;

            //encode pwd
            $user->setFirstName($faker->firstName($genre))
                    ->setLastName($faker->lastName())
                    ->setIntroduction($faker->sentence())
                    ->setDescription('<p>'.join('<p>',$faker->paragraphs(3)).'</p>')
                    ->setHash($this->hasher->hashPassword($user, 'password'))
                    ->setEmail($faker->email());
            $manager->persist($user);
            $users[]  = $user;
        }
        // annonces :

        for ($i = 1; $i <= 30; $i++){
            $ad     = new Ad();

            $title  = $faker->sentence();
            $img    = "https://picsum.photos/1000/350";
            $introduction = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>';
            $user = $users[mt_rand(0, count($users)-1)];
            $ad->setTitle($title)
                ->setCoverImage($img)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(30,250))
                ->setRooms(mt_rand(1,8))
                ->setAuthor($user);

            for ($j = 1; $j <= mt_rand(2,5); $j++){
                $img    = new Image();
                $img->setUrl( "https://picsum.photos/1000/350")
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($img);
            }
            // gestion des reservation
            for($j =1; $j <= mt_rand(0,10); $j++)
            {
                $booking = new Booking();
                $createdAt = $faker->dateTimeBetween('-6 months');
                $startAt = $faker->dateTimeBetween('-3 months');
                $duration = mt_rand(3, 10);
                $endDate = $startAt->modify("+$duration days");
                $amount = $ad->getPrice() * $duration;

                $booker = $users[mt_rand(0,count($users)-1) ];

                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startAt)
                    ->setEndTime($endDate)
                    ->setCreateAt($createdAt)
                    ->setAmount($amount)
                    ->setComment($comment);
                $manager->persist($booking);
            }

            $manager->persist($ad);
    }
        $manager->flush();



    }
}
