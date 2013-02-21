<?php

namespace BIT\BITWhosOnlineBundle\Services\WhosOnline;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class WhosOnlineListener
{
  private $whosOnline;
  
  public function __construct( WhosOnlineService $whosOnline )
  {
    $this->whosOnline = $whosOnline;
  }
  
  public function register( EventDispatcher $dispatcher, $priority = 0 )
  {
    $dispatcher->connect( 'whos_online.event', array( $this, 'handle' ), $priority );
  }
  
  public function onKernelRequest( GetResponseEvent $event )
  {
    $concurrencyURL = $event->getRequest( )->getUriForPath( "/security/concurrency" );
    $overwriteConcurrencyURL = $event->getRequest( )->getUriForPath( "/security/overwrite-concurrency" );
    $concurrencyURLActive = false;
    $debug = false;
    
    $uri = $event->getRequest( )->getUri( );
    if ( $uri != $concurrencyURL && $uri != $overwriteConcurrencyURL )
      $concurrencyURLActive = true;
    if ( strpos( $uri, "_profiler" ) || strpos( $uri, "_wdt" ) )
      $debug = true;
    
    $this->whosOnline->update( $event->getRequest( ) );
    
    if ( $this->whosOnline->hasConcurrency( ) && $concurrencyURLActive && !$debug )
    {
      $response = new Response( );
      $response->headers->set( "location", $event->getRequest( )->getUriForPath( $concurrencyURL ) );
      $event->setResponse( $response );
    }
  }
}
