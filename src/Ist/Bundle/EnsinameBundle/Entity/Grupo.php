<?php

namespace Ist\Bundle\EnsinameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grupo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Grupo
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
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var integer
     *
     * @ORM\Column(name="lingua", type="integer")
     */
    private $lingua;

    /**
     * @var integer
     *
     * @ORM\Column(name="professor", type="integer")
     */
    private $professor;

    /**
     * @var string
     *
     * @ORM\Column(name="alunos", type="text")
     */
    private $alunos;

    /**
     * @var string
     *
     * @ORM\Column(name="horarios", type="string", length=255)
     */
    private $horarios;


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
     * Set titulo
     *
     * @param string $titulo
     * @return Grupo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    
        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set lingua
     *
     * @param integer $lingua
     * @return Grupo
     */
    public function setLingua($lingua)
    {
        $this->lingua = $lingua;
    
        return $this;
    }

    /**
     * Get lingua
     *
     * @return integer 
     */
    public function getLingua()
    {
        return $this->lingua;
    }

    /**
     * Set professor
     *
     * @param integer $professor
     * @return Grupo
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
     * Set alunos
     *
     * @param string $alunos
     * @return Grupo
     */
    public function setAlunos($alunos)
    {
        $this->alunos = $alunos;
    
        return $this;
    }

    /**
     * Get alunos
     *
     * @return string 
     */
    public function getAlunos()
    {
        return $this->alunos;
    }

    /**
     * Set horarios
     *
     * @param string $horarios
     * @return Grupo
     */
    public function setHorarios($horarios)
    {
        $this->horarios = $horarios;
    
        return $this;
    }

    /**
     * Get horarios
     *
     * @return string 
     */
    public function getHorarios()
    {
        return $this->horarios;
    }
}
