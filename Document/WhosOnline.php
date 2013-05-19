<?php

namespace BIT\BITWhosOnlineBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use BIT\BITWhosOnlineBundle\Model\WhosOnline as BaseWhosOnline;

/**
 * @MongoDB\Document(collection="whos_online", repositoryClass="BIT\BITWhosOnlineBundle\Document\Repository\WhosOnlineRepository")
 */
class WhosOnline extends BaseWhosOnline
{
  /**
   * @MongoDB\Id
   */
  protected $id;
  
  /**
   * @MongoDB\Id
   * @MongoDB\String
   */
  protected $session;
  
  /**
   * @MongoDB\String
   */
  protected $ip;
  
  /**
   * @MongoDB\Timestamp
   */
  protected $time_entry;
  
  /**
   * @MongoDB\Timestamp
   */
  protected $time_last_action;
  
  /**
   * @MongoDB\String
   */
  protected $last_page;
  
  /**
   * @MongoDB\String
   */
  protected $user_agent;
  
  /**
   * @MongoDB\String
   */
  protected $user;
}
