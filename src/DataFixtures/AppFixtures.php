<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use App\Entity\Permissions;
use App\Entity\Status;
use App\Entity\UserAdmin;
use App\Entity\UserPartenaire;
use App\Entity\UserStructure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager): void
    {

        $admin = new UserAdmin();
        $admin->setEmail('admin@admin.fr')
            ->setPassword($this->passwordHasher->hashPassword($admin, 'password'));
 
        $manager->persist($admin);

        $partenaires = [];
        for($i = 0; $i < 10; $i++) {

            $partenaire = new UserPartenaire();
            $partenaire
                ->setEmail($this->faker->email)
                ->setDescription($this->faker->text(20))
                ->setStatus(mt_rand(0, 1))
                ->setPhone($this->faker->phoneNumber)
                ->setPartenaireName($this->faker->name)
                ->setPassword($this->passwordHasher->hashPassword($partenaire, 'password'))
                ->setAddress($this->faker->address)
                ->setCity($this->faker->city)
                ->setZipcode($this->faker->countryCode);

            $partenaires[] = $partenaire;
            $manager->persist($partenaire);

        }

        $permissions = [];
        for($i = 0; $i < 5; $i++){
            $permission = new Permission();
            $permission->setName($this->faker->name);
            $permissions[] = $permission;
            $manager->persist($permission);

        }

        $structures = [];
        for($j = 0; $j < 20; $j++){
            $structure = new UserStructure();
            $structure
                ->setEmail($this->faker->email)
                ->setDescription($this->faker->text(20))
                ->setStatus(0)
                ->setPassword($this->passwordHasher->hashPassword($structure, 'password'))
                ->setAddress($this->faker->address)
                ->setPhone($this->faker->phoneNumber)
                ->setCity($this->faker->city)
                ->setZipcode($this->faker->countryCode)
                ->setStructureName($this->faker->name)
                ->setUserPartenaire($partenaires[mt_rand(0, count($partenaires) -1)]);



                for($i = 0; $i < mt_rand(0, count($permissions) -1); $i++){
                    $structure->addPermission($permissions[mt_rand(0, count($permissions) -1)]);
                }
            $structures[] = $structure;
            $manager->persist($structure);

        }







        $manager->flush();
    }
}
