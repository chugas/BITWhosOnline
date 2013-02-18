<?php

namespace BIT\BITWhosOnlineBundle\Model;

class WhosOnline
{
  
  public function setSession( $session )
  {
    $this->session = $session;
    
    return $this;
  }
  
  public function getSession( )
  {
    return $this->session;
  }
  
  public function setUser( $user )
  {
    $this->user = $user;
    
    return $this;
  }
  
  public function getUser( )
  {
    return $this->user;
  }
  
  public function setIp( $ip )
  {
    $this->ip = $ip;
  }
  
  public function getIp( )
  {
    return $this->ip;
  }
  
  public function setTimeEntry( $time_entry )
  {
    $this->time_entry = $time_entry;
  }
  
  public function getTimeEntry( )
  {
    return $this->time_entry;
  }
  
  public function setTimeLastAction( $time_last_action )
  {
    $this->time_last_action = $time_last_action;
  }
  
  public function getTimeLastAction( )
  {
    return $this->time_last_action;
  }
  
  public function setLastPage( $last_page )
  {
    $this->last_page = $last_page;
  }
  
  public function getLastPage( )
  {
    return $this->last_page;
  }
  
  public function setUserAgent( $user_agent )
  {
    $this->user_agent = $user_agent;
  }
  
  public function getUserAgent( )
  {
    return $this->user_agent;
  }
}
