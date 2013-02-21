<?php

namespace BIT\BITWhosOnlineBundle\Services\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\MongoDB\Connection;
use BIT\BITWhosOnlineBundle\Services\ObjectManager\ObjectManagerInterface;

class MongoDBObjectManager implements ObjectManagerInterface
{

  private $documentManager;

  public function __construct( DocumentManager $documentManager )
  {
    $this->documentManager = $documentManager;
  }

  public function create( $configs )
  {
    $connection = $this->documentManager->getConnection( );

    // overwrite connection
    $connection = new Connection( $configs['host'], array( ), $connection->getConfiguration( ), $connection->getEventManager( ));

    $configuration = clone $this->documentManager->getConfiguration( );
    $configuration->setDefaultDB( $configs['database'] );
    return $this->documentManager->create( $connection, $configuration, $this->documentManager->getEventManager( ) );
  }
}
