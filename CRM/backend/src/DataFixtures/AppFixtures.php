<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['Ameliya', 'ameliya.cer@gmail.com', '-2 minutes'],
            ['Lindsey Stroud', 'lindsey.stroud@gmail.com', '-5 minutes'],
            ['Nicci Troiani', 'nicci.troiani@gmail.com', '-14 minutes'],
            ['George Fields', 'george.fields@gmail.com', '-3 days'],
            ['Rebecca Moore', 'rebecca.moore@gmail.com', '-12 days'],
            ['Jane Doe', 'jane.doe@gmail.com', '-20 days'],
        ];

        foreach ($users as [$name, $email, $time]) {
            $user = new User();
            $user->setName($name);
            $user->setEmail($email);
            $user->setPassword($this->hasher->hashPassword($user, '12345678'));
            $user->setLastActivity(new \DateTimeImmutable($time));
            $manager->persist($user);
        }

        $companyNames = [
            ['Artel', 'info@artel.uz'],
            ['Uzum Market', 'hello@uzum.uz'],
            ['Kadirov Inc', 'office@kadirov.uz'],
            ['Najot Ta\'lim', 'info@najottalim.uz'],
        ];

        $companies = [];
        foreach ($companyNames as $i => [$name, $email]) {
            $company = new Company();
            $company->setName($name);
            $company->setEmail($email);
            $company->setPassword(password_hash('12345678', PASSWORD_DEFAULT));
            $company->setLastActivity(new \DateTimeImmutable('-'.($i * 2 + 1).' days'));
            $manager->persist($company);
            $companies[] = $company;
        }

        $clients = [
            ['Dermot Jones', 'dermot.jones@gmail.com', 0],
            ['Martin Merces', 'martin.merces@gmail.com', 1],
            ['Franz Ferdiand', 'franz.ferdiand@gmail.com', 2],
            ['John Smith', 'john.smith@gmail.com', 0],
            ['Judith Williams', 'judith.williams@gmail.com', 3],
        ];

        foreach ($clients as $i => [$name, $email, $companyIndex]) {
            $client = new Client();
            $client->setName($name);
            $client->setEmail($email);
            $client->setCompany($companies[$companyIndex]);
            $client->setLastActivity(new \DateTimeImmutable('-'.($i + 1).' hours'));
            $manager->persist($client);
        }

        $manager->flush();
    }
}
