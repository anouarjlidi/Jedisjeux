<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 21:38
 */

namespace AppBundle\Command\Installer\Data;

interface LoadYamlDataInterface
{
    /**
     * @return string
     */
    public function getYAMLFileName();
}