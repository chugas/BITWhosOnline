<?php

namespace BIT\BITWhosOnlineBundle\DependencyInjection;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

  public function getConfigTreeBuilder( )
  {
    $treeBuilder = new TreeBuilder( );
    $rootNode = $treeBuilder->root( 'bit_whos_online' );

    $supportedDrivers = array( 'mysql', 'mongodb' );

    $rootNode->children( ) // childrens
        ->booleanNode( 'active' )->defaultFalse( )->end( ) // true or false
        ->booleanNode( 'single_session' )->defaultTrue( )->end( ) // true or false
        ->scalarNode( 'driver' )->validate( ) // validate
        ->ifNotInArray( $supportedDrivers )->thenInvalid( 'The driver %s is not supported. Please choose one of ' . json_encode( $supportedDrivers ) ) // end validate
        ->end( )->cannotBeOverwritten( )->isRequired( )->cannotBeEmpty( )->end( ) // drivers
        ->scalarNode( 'host' )->isRequired( )->cannotBeEmpty( )->defaultValue( 'localhost' )->end( ) // host
        ->scalarNode( 'port' )->isRequired( )->end( ) // port
        ->scalarNode( 'database' )->isRequired( )->cannotBeEmpty( )->end( ) // database
        ->scalarNode( 'user' )->isRequired( )->end( ) // user
        ->scalarNode( 'password' )->isRequired( )->end( ) // password
        ->scalarNode( 'table' )->cannotBeEmpty( )->defaultValue( 'whos_online' )->end( ) // table
        ->end( );

    return $treeBuilder;
  }
}
