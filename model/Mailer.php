<?php
/**
 * Created by PhpStorm.
 * User: My Peeps
 * Date: 13/11/2017
 * Time: 6:21 PM
 */

abstract class Mailer
{
  public abstract function SendMail(string $name, string $emailAddress, string $subject, string $body);
}