<?php

namespace BIT\BITWhosOnlineBundle\Services\WhosOnline;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use FOS\UserBundle\Model\UserManager;
use BIT\BITWhosOnlineBundle\Entity\WhosOnline;
use BIT\BITWhosOnlineBundle\Document\WhosOnline as MongoWhosOnline;
use BIT\BITWhosOnlineBundle\Services\ObjectManager\ObjectManagerInterface;
use \DateTime;

class WhosOnlineService
{
  private $config;
  private $session;
  private $securityContext;
  private $whosOnlineObjectManager;
  private $logger;
  
  public function __construct( Array $config, Session $session, SecurityContext $securityContext,
      ObjectManagerInterface $whosOnlineObjectManager, LoggerInterface $logger )
  {
    $this->config = $config;
    $this->session = $session;
    $this->securityContext = $securityContext;
    $this->whosOnlineObjectManager = $whosOnlineObjectManager->create( $config[ 'db_options' ] );
    $this->logger = $logger;
  }
  
  private function create( Request $request, $onlineUser )
  {
    $datetime = new DateTime( date( 'Y/m/d H:i:s' ));
    
    switch ( $this->config[ 'db_options' ][ 'driver' ] )
    {
      case 'mysql':
      case 'postgre':
        {
          $whosOnlineUser = new WhosOnline( );
          $whosOnlineUser->setTimeEntry( $datetime );
          $whosOnlineUser->setTimeLastAction( $datetime );
          break;
        }
      case 'mongodb':
        {
          $whosOnlineUser = new MongoWhosOnline( );
          $whosOnlineUser->setTimeEntry( $datetime->getTimestamp( ) );
          $whosOnlineUser->setTimeLastAction( $datetime->getTimestamp( ) );
        }
    }
    
    $whosOnlineUser->setUser( $onlineUser->getUsername() );
    $whosOnlineUser->setSession( $this->session->getId( ) );
    $whosOnlineUser->setIp( $request->getClientIp( ) );
    $whosOnlineUser->setLastPage( $request->getRequestUri( ) );
    $whosOnlineUser->setUserAgent( $_SERVER[ 'HTTP_USER_AGENT' ] );
    $this->whosOnlineObjectManager->persist( $whosOnlineUser );
    $this->whosOnlineObjectManager->flush( );
  }
  
  private function getOnlineUser( )
  {
    $token = $this->securityContext->getToken( "user" );
    
    if ( $token )
      return $this->securityContext->getToken( "user" )->getUser( $token );
    
    return null;
  }
  
  private function getRepository()
  {
    return $this->whosOnlineObjectManager->getRepository( "BITWhosOnlineBundle:WhosOnline" );
  }
  
  private function getDBWhosOnline( $session, $onlineUser )
  {
    return $this->getRepository()->findBySessionAndUsername( $session, $onlineUser->getUsername( ) );
  }
  
  private function getDBWhosOnlineByUsername( $onlineUser )
  {
    return $this->getRepository()->findByUsername( $onlineUser->getUsername() );
  }
  
  private function checkUserOnline( $onlineUser )
  {
    return count( $this->getDBWhosOnlineByUsername( $onlineUser ) );
  }
  
  public function update( Request $request )
  {
    $onlineUser = $this->getOnlineUser( );
    
    if ( $this->securityContext->getToken() && !$this->securityContext->isGranted( 'ROLE_BYPASS' ) && is_object( $onlineUser ) )
    {
      $whosOnlineUser = $this->getDBWhosOnline( $this->session->getId( ), $onlineUser );
      
      $datetime = new DateTime( date( 'Y/m/d H:i:s' ));
      
      if ( is_object( $whosOnlineUser ) )
      {
        $this->logger->debug( "Begin Updating Online User" );
        $whosOnlineUser->setLastPage( $request->getRequestUri( ) );
        $whosOnlineUser->setTimeLastAction( $datetime->getTimestamp( ) );
        $this->whosOnlineObjectManager->flush( );
        $this->logger->debug( "Updated" );
      }
      else
        return false;
    }
    
    return true;
  }
  
  public function onAuthenticationSuccess( Request $request, TokenInterface $token )
  {
    $onlineUser = $this->getOnlineUser( );
    
    if ( !$this->securityContext->isGranted( 'ROLE_BYPASS' ) )
    {
      // if user can't login multiple times
      if ( $this->config[ 'single_session' ] )
      {
        // check if this user is already authenticated
        if ( $this->checkUserOnline( $onlineUser ) )
        {
          $this->logger->debug( "User already logged in" );
          $this->session->set( "CONCURRENCY", true );
          
          return false;
        }
      }
      
      $this->logger->debug( "Begin Created Online User" );
      $this->create( $request, $onlineUser );
      $this->logger->debug( "Created" );
    }
    else
      $this->logger->debug( "Do not create whos online record, BYPASS USER logged in" );
    
    return true;
  }
  
  public function onLogoutSuccess( Request $request )
  {
    $onlineUser = $this->getOnlineUser( );
    
    if ( !$this->securityContext->isGranted( 'ROLE_BYPASS' ) )
    {
      if ( $this->config[ 'single_session' ] )
      {
        $this->logger->debug( "Begin remove from online" );
        if ( $this->securityContext->getToken( "user" ) )
        {
          // rdbmoving from whos online
          $onlineUser = $this->getOnlineUser( );
          $dbOnlineUser = $this->getDBWhosOnline( $this->session->getId( ), $onlineUser );
          
          if ( $dbOnlineUser )
          {
            $this->whosOnlineObjectManager->remove( $dbOnlineUser );
            $this->whosOnlineObjectManager->flush( );
            $this->logger->debug( "Removed from online" );
          }
        }
      }
    }
    return true;
  }
  
  public function cleanForUser( Request $request )
  {
    $onlineUser = $this->getOnlineUser( );
    
    if ( !$this->securityContext->isGranted( 'ROLE_BYPASS' ) )
    {
      if ( $this->config[ 'single_session' ] )
      {
        $this->logger->debug( "Cleaning the database sessions by user" );
        
        // getting the online user
        $onlineUser = $this->getOnlineUser( );
        
        if ( is_object( $this->sessionObjectManager ) )
        {
          // cleaning the other sessions
          $sessions = $this->sessionObjectManager->getRepository( "BITWhosOnlineBundle:Session" )->findAll( );
          
          foreach ( $sessions as $session )
          {
            $sessionBag = unserialize( str_replace( "_sf2_attributes|", "", $session->getSessionData( ) ) );
            $user = null;
            
            if ( is_array( $sessionBag ) && array_key_exists( '_security_main', $sessionBag ) )
            {
              $sessionBag = unserialize( $sessionBag[ '_security_main' ] );
              $user = $sessionBag->getUser( );
            }
            
            if ( $user && !( $user instanceof \Symfony\Component\Security\Core\User\User )
                && $user->getId( ) === $onlineUser->getId( ) && $session->getSessionId( ) !== $this->session->getId( ) )
              $this->sessionObjectManager->remove( $session );
          }
          
          $this->sessionObjectManager->flush( );
        }
        
        // cleaning the whosonline
        $dbOnlineUsers = $this->getDBWhosOnlineByUserEmail( $onlineUser );
        foreach ( $dbOnlineUsers as $dbOnlineUser )
          $this->whosOnlineObjectManager->remove( $dbOnlineUser );
        
        $this->create( $request, $onlineUser );
        $this->whosOnlineObjectManager->flush( );
        
        $this->logger->debug( "Ending the cleaning" );
      }
      
      return true;
    }
  }
  
  public function hasConcurrency( )
  {
    return $this->session->get( "CONCURRENCY", false );
  }
  
  public function getLogoutPath()
  {
    return $this->config["logout_path"];
  }
}
