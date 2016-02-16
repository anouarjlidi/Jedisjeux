<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 15/02/16
 * Time: 23:07
 */

namespace AppBundle\Entity;

use AppBundle\Traits\BlameableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JDJ\JeuBundle\Entity\Jeu;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_game_rate")
 */
class GameRate implements ResourceInterface
{
    use BlameableEntity,
        TimestampableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Jeu
     *
     * @ORM\ManyToOne(targetEntity="JDJ\JeuBundle\Entity\Jeu")
     */
    protected $game;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $value;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Jeu
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Jeu $game
     *
     * @return $this
     */
    public function setGame($game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
