<?php

namespace BIT\BITWhosOnlineBundle\Document\Repository;
use Doctrine\ODM\MongoDB\DocumentRepository;

class WhosOnlineRepository extends DocumentRepository
{
  
  public function findAll( )
  {
    return $this->createQueryBuilder( )->getQuery( );
  }
  
  public function findByUsername( $username )
  {
    $queryBuilder = $this->createQueryBuilder( );
    $queryBuilder = $queryBuilder->field( 'user' )->equals( $username );
    return $queryBuilder->getQuery( )->execute( );
  }
  
  public function findBySessionAndUsername( $sessionId, $username )
  {
    $queryBuilder = $this->createQueryBuilder( );
    $queryBuilder = $queryBuilder->field( 'session' )->equals( $sessionId );
    $queryBuilder = $queryBuilder->field( 'user' )->equals( $username );
    return $queryBuilder->getQuery( )->execute( )->getNext( );
  }
}
