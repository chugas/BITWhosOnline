<?php

namespace BIT\BITWhosOnlineBundle\Services\WhosOnline;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;

class BITUserLoginSuccessService extends DefaultAuthenticationSuccessHandler
{
  
  private $whosOnline;
  
  public function __construct( HttpUtils $httpUtils, array $options, WhosOnlineService $whosOnline )
  {
    parent::__construct( $httpUtils, $options );
    
    $this->whosOnline = $whosOnline;
  }
  
  public function onAuthenticationSuccess( Request $request, TokenInterface $token )
  {
    if ( $this->whosOnline->onAuthenticationSuccess( $request, $token ) )
      return parent::onAuthenticationSuccess( $request, $token );
    else
    {
      $response = new Response( );
      $response->headers->set( "location", $request->getUriForPath( "/security/concurrency" ) );
      return $response;
    }
  }
}
