<?php
namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OffreControllerTest extends WebTestCase
{
    public function testIndexPageRequiresLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/offre/');

        $this->assertResponseRedirects('/login');
    }

    public function testIndexPageAfterLogin(): void
    {
        $client = static::createClient();
        $container = self::getContainer();

        // 1) Créer un utilisateur de test dans la base de test
        $em = $container->get('doctrine')->getManager();

        $user = new User();
        $user->setEmail('ci_test@example.com');
        // Hash du password "password123" pour être cohérent (pas crucial ici)
        $hasher = $container->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'password123'));
        $user->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        // 2) Simuler la connexion
        $client->loginUser($user);

        // 3) Accéder à la page index des offres
        $crawler = $client->request('GET', '/offre/');

        // 4) Vérifier qu'on a bien un 200 et le bon titre
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des offres');
    }
}
