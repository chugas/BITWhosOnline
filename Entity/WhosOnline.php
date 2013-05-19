<?php

namespace BIT\BITWhosOnlineBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use BIT\BITWhosOnlineBundle\Model\WhosOnline as BaseWhosOnline;

/**
 * @ORM\Table(name="whos_online")
 * @ORM\Entity(repositoryClass="BIT\BITWhosOnlineBundle\Entity\Repository\WhosOnlineRepository")
 */
class WhosOnline extends BaseWhosOnline
{
  
  /**
   * @ORM\Id
   * @ORM\Column(name="session", type="string", length=255, nullable=false)
   * @ORM\GeneratedValue(strategy="NONE")
   */
  protected $session;
  
  /**
   * @ORM\Column(name="ip", type="string", length=15, nullable=false)
   */
  protected $ip;
  
  /**
   * @ORM\Column(name="time_entry", type="datetime", nullable=false)
   */
  protected $time_entry;
  
  /**
   * @ORM\Column(name="time_last_action", type="datetime", nullable=false)
   */
  protected $time_last_action;
  
  /**
   * @ORM\Column(name="last_page", type="string", length=255, nullable=false)
   */
  protected $last_page;
  
  /**
   * @ORM\Column(name="user_agent", type="string", length=255, nullable=false)
   */
  protected $user_agent;
  
  /**
   * @ORM\Column(name="user", type="string", length=255, nullable=false)
   */
  protected $user;
}
