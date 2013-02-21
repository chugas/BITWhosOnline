<?php

namespace BIT\BITWhosOnlineBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class WhosOnlineRepository extends EntityRepository
{
  
  public function findByUsername( $username )
  {
    return $this->findBy( array( "user" => $username ) );
  }
  
  public function findBySessionAndUsername( $sessionId, $username )
  {
    return $this->findOneBy( array( "user" => $username, "session" => $sessionId ) );
  }
}
