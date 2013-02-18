<?php

namespace BIT\BITWhosOnlineBundle\Services\WhosOnline;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;

class BITUserLogoutSuccessService extends DefaultLogoutSuccessHandler
{

  private $whosOnline;

  public function __construct( HttpUtils $httpUtils, $targetUrl = '/', WhosOnlineService $whosOnline )
  {
    parent::__construct( $httpUtils, $targetUrl );

    $this->whosOnline = $whosOnline;
  }

  public function onLogoutSuccess( Request $request )
  {
    $this->whosOnline->onLogoutSuccess( $request );

    return parent::onLogoutSuccess( $request );
  }
}
