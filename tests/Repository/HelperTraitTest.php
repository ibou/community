<?php
namespace App\Tests\Repository;

use Doctrine\Common\Persistence\ObjectRepository;





/**
 * Helper for test units, database connection
 */
trait HelperTraitTest
{

    public function getManagerRegistry()
    {
        $kernel = self::bootKernel();
        return $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
