<?php

namespace BIT\BITWhosOnlineBundle\Document\Repository;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MongoDbSessionHandler;

class WhosOnlineRepository extends DocumentRepository
{

  public function findAll( )
  {
    return $this->createQueryBuilder( )->getQuery( );
  }

  public function findByUserEmail( $userEmail )
  {
    $queryBuilder = $this->createQueryBuilder( );
    $queryBuilder = $queryBuilder->field( 'user' )->equals( $userEmail );
    return $queryBuilder->getQuery( )->execute( );
  }

  public function findBySessionAndUserEmail( $sessionId, $userEmail )
  {
    $queryBuilder = $this->createQueryBuilder( );
    $queryBuilder = $queryBuilder->field( 'session' )->equals( $sessionId );
    $queryBuilder = $queryBuilder->field( 'user' )->equals( $userEmail );
    return $queryBuilder->getQuery( )->execute( )->getNext( );
  }
}
