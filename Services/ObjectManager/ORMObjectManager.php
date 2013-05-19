<?php

namespace BIT\BITWhosOnlineBundle\Services\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Connection;
use BIT\BITWhosOnlineBundle\Services\ObjectManager\ObjectManagerInterface;

class ORMObjectManager implements ObjectManagerInterface
{
  
  private $entityManager;
  
  public function __construct( EntityManager $entityManager )
  {
    $this->entityManager = $entityManager;
  }
  
  public function create( $configs )
  {
    $params = $this->entityManager->getConnection( )->getParams( );
    
    $params[ 'dbname' ] = $configs[ 'database' ];
    $params[ 'host' ] = $configs[ 'host' ];
    $params[ 'port' ] = $configs[ 'port' ];
    $params[ 'user' ] = $configs[ 'user' ];
    $params[ 'password' ] = $configs[ 'password' ];
    $params[ 'driver' ] = $configs[ 'driver' ];
    
    $connection = $this->entityManager->getConnection( );
    
    // overwrite connection
    $connection = new Connection( $params, $connection->getDriver( ), $connection->getConfiguration( ),
        $connection->getEventManager( ));
    
    // return the entity manager
    return $this->entityManager
        ->create( $connection, $this->entityManager->getConfiguration( ), $this->entityManager->getEventManager( ) );
  }
}
