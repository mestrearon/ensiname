<?php

namespace Ist\Bundle\EnsinameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aula
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Aula
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="professor", type="integer")
     */
    private $professor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="date")
     */
    private $data;

    /**
     * @var integer
     *
     * @ORM\Column(name="grupo", type="integer", nullable=true)
     */
    private $grupo;

    /**
     * @var string
     *
     * @ORM\Column(name="presencas", type="text", nullable=true)
     */
    private $presencas;

    /**
     * @var string
     *
     * @ORM\Column(name="dada", type="string", length=1)
     */
    private $dada;

    /**
     * @var string
     *
     * @ORM\Column(name="observacao", type="text", nullable=true)
     */
    private $observacao;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set professor
     *
     * @param integer $professor
     * @return Aula
     */
    public function setProfessor($professor)
    {
        $this->professor = $professor;
    
        return $this;
    }

    /**
     * Get professor
     *
     * @return integer 
     */
    public function getProfessor()
    {
        return $this->professor;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return Aula
     */
    public function setData($data)
    {
        $this->data = $data;
    
        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set grupo
     *
     * @param integer $grupo
     * @return Aula
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    
        return $this;
    }

    /**
     * Get grupo
     *
     * @return integer 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set presencas
     *
     * @param string $presencas
     * @return Aula
     */
    public function setPresencas($presencas)
    {
        $this->presencas = $presencas;
    
        return $this;
    }

    /**
     * Get presencas
     *
     * @return string 
     */
    public function getPresencas()
    {
        return $this->presencas;
    }

    /**
     * Set dada
     *
     * @param string $dada
     * @return Aula
     */
    public function setDada($dada)
    {
        $this->dada = $dada;
    
        return $this;
    }

    /**
     * Get dada
     *
     * @return string 
     */
    public function getDada()
    {
        return $this->dada;
    }

    /**
     * Set observacao
     *
     * @param string $observacao
     * @return Aula
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    
        return $this;
    }

    /**
     * Get observacao
     *
     * @return string 
     */
    public function getObservacao()
    {
        return $this->observacao;
    }
}