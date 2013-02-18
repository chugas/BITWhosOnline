<?php

namespace BIT\BITWhosOnlineBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class WhosOnlineRepository extends EntityRepository
{

  public function findByUserEmail( $userEmail )
  {
    return $this->findBy( array( "user" => $userEmail ) );
  }

  public function findBySessionAndUserEmail( $sessionId, $userEmail )
  {
    return $this->findOneBy( array( "user" => $userEmail, "session" => $sessionId ) );
  }
}
