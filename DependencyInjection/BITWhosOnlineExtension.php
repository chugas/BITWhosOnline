<?php

namespace BIT\BITWhosOnlineBundle\DependencyInjection;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class BITWhosOnlineExtension extends Extension
{
  
  public function load( array $configs, ContainerBuilder $container )
  {
    $processor = new Processor( );
    $configuration = new Configuration( );
    $config = $processor->processConfiguration( $configuration, $configs );
    
    $loader = new YamlFileLoader( $container, new FileLocator( __DIR__ . '/../Resources/config'));
    
    $container->setParameter( 'whos_online.single_session', $config[ 'single_session' ] );
    
    switch ( $config[ 'driver' ] )
    {
      case 'mysql':
      case 'postgre':
        {
          $dbm = 'orm';
          break;
        }
      case 'mongodb':
        {
          $dbm = 'mongodb';
        }
    }
    
    $container->setParameter( 'whos_online.db_options', $config );
    
    $loader->load( $dbm . "/whos_online.yml" );
    $loader->load( "whos_online_listeners.yml" );
  }
  
  public function getXsdValidationBasePath( )
  {
    return __DIR__ . '/../Resources/config/schema';
  }
  
  public function getNamespace( )
  {
    return 'http://symfony.com/schema/dic/bit_whos_online';
  }
  
  public function getAlias( )
  {
    return 'bit_whos_online';
  }
}
