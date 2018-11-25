<?php

namespace App\Security\Link;

use App\Entity\User; 
use App\Entity\PasswordReset;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface; 

class GenerateLink
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function generateResetPasswordLink($hostName, $email, $expirationDelay = 'P12M', $action = "login/reset-password-checker")
    {
        $UserRepository = $this->em->getRepository(User::class);
        $PasswordResetRepository = $this->em->getRepository(PasswordReset::class);
        $em = $this->em;

        $PasswordReset = new PasswordReset();

        $user = $UserRepository->findByEmail($email);
        if (count($user) > 0) {
                // Get Old links and delete
            $oldLink = $PasswordResetRepository->findBy(array('email' => $email));
            foreach ($oldLink as $pwReset) {
                $em->remove($pwReset);
            }
            $em->flush();
                // generate link and save on Db
            $selector = bin2hex(random_bytes(8));
            $token = bin2hex(random_bytes(32));
            $expires = new \DateTime('NOW');
            $expires->add(new \DateInterval($expirationDelay));
            $passwordReset = new PasswordReset();
            $passwordReset->setEmail($email);
            $passwordReset->setToken($token);
            $passwordReset->setSelector($selector);
            $passwordReset->setExpires($expires);
            $em->persist($passwordReset);
            $em->flush();

            $url = sprintf('%/' . $hostName . '/' . $action . '?%s', null, http_build_query([
                'selector' => $selector,
                'validator' => $token
            ]));

            return $url;

        } else {
            return false;
        }
    }

}
