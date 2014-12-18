<?php

namespace Symfochess\PartiesBundle\Entity;

/**
 * Piece
 *
 */
class Piece
{
    private $type;

    private $couleur;

    private $x;

    private $y;

    public function __construct($type = null, $couleur = null, $x = null, $y = null)
    {
        $this->type = $type;
        $this->couleur = $couleur;
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * @param mixed $couleur
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

}