<?php

namespace Arcsym\RestApiSymfony\Repository;

use Arcsym\RestApiSymfony\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StudentRepository extends ServiceEntityRepository
{
  /**
   * @param ManagerRegistry $registry
   * @param string|null $entityClass
   */
  public function __construct(ManagerRegistry $registry, ?string $entityClass = null)
  {
    parent::__construct($registry, Student::class);
  }
}
