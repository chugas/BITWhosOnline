<?php

namespace BIT\BITWhosOnlineBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * User controller.
 *
 * @Route("/user")
 */
class WhosOnlineController extends Controller
{

  /**
   * Concurrency
   *
   * @Template("BITWhosOnlineBundle:WhosOnline:concurrency.html.twig")
   */

  public function concurrencyAction( )
  {
    return array( "CONCURRENCY", $this->get( 'session' )->get( 'CONCURRENCY' ) );
  }

  /**
   * Overwrite Concurrency
   *
   */

  public function overwriteConcurrencyAction( )
  {
    // remove all other sessions form database
    $this->get( 'whosonline' )->cleanForUser( $this->get( 'request' ) );

    // cleaning the session
    $this->get( 'session' )->remove( 'CONCURRENCY' );

    // redirecting to target path
    $targetPath = $this->get( 'session' )->get( '_security.target_path' );
    $targetPath = ( !empty( $targetPath ) ) ? $this->generateUrl( $targetPath ) : "/";

    $response = new Response( );
    $response->headers->set( "location", $this->get( 'request' )->getUriForPath( $targetPath ) );
    return $response;
  }
}
