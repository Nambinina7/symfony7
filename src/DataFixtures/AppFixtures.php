<?php

namespace App\DataFixtures;

use App\Entity\BannerItems;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 3; $i++) {
            $banner = new BannerItems();
            $banner->setTitle("Title".$i);
            $banner->setOrderNumber($i+1);
            $banner->setDescription("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500");
            $banner->setImage($faker->imageUrl(width: 640, height: 450));
            $manager->persist($banner);
        }

        $manager->flush();
    }
}
