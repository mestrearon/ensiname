<?php

namespace Ist\Bundle\EnsinameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Interessado
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Interessado
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
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;

    /**
     * @var integer
     *
     * @ORM\Column(name="fone", type="string", length=255)
     */
    private $fone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="linguas", type="text", nullable=true)
     */
    private $linguas;

    /**
     * @var integer
     *
     * @ORM\Column(name="fonte", type="string", length=255)
     */
    private $fonte;

    /**
     * @var string
     *
     * @ORM\Column(name="estudo", type="string", length=1)
     */
    private $estudo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inicio", type="date")
     */
    private $inicio;

    /**
     * @var string
     *
     * @ORM\Column(name="horario", type="string", length=255)
     */
    private $horario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="chamada", type="date")
     */
    private $chamada;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1)
     */
    private $status;

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
     * Set nome
     *
     * @param string $nome
     * @return Interessado
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    
        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set fone
     *
     * @param string $fone
     * @return Interessado
     */
    public function setFone($fone)
    {
        $this->fone = $fone;
    
        return $this;
    }

    /**
     * Get fone
     *
     * @return string 
     */
    public function getFone()
    {
        return $this->fone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Interessado
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set linguas
     *
     * @param string $linguas
     * @return Interessado
     */
    public function setLinguas($linguas)
    {
        $this->linguas = $linguas;
    
        return $this;
    }

    /**
     * Get linguas
     *
     * @return string 
     */
    public function getLinguas()
    {
        return $this->linguas;
    }

    /**
     * Set fonte
     *
     * @param string $fonte
     * @return Interessado
     */
    public function setFonte($fonte)
    {
        $this->fonte = $fonte;
    
        return $this;
    }

    /**
     * Get fonte
     *
     * @return string 
     */
    public function getFonte()
    {
        return $this->fonte;
    }

    /**
     * Set estudo
     *
     * @param string $estudo
     * @return Interessado
     */
    public function setEstudo($estudo)
    {
        $this->estudo = $estudo;
    
        return $this;
    }

    /**
     * Get estudo
     *
     * @return string 
     */
    public function getEstudo()
    {
        return $this->estudo;
    }

    /**
     * Set inicio
     *
     * @param \DateTime $inicio
     * @return Interessado
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    
        return $this;
    }

    /**
     * Get inicio
     *
     * @return \DateTime 
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set horario
     *
     * @param string $horario
     * @return Interessado
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;
    
        return $this;
    }

    /**
     * Get horario
     *
     * @return string 
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set chamada
     *
     * @param \DateTime $chamada
     * @return Interessado
     */
    public function setChamada($chamada)
    {
        $this->chamada = $chamada;
    
        return $this;
    }

    /**
     * Get chamada
     *
     * @return \DateTime 
     */
    public function getChamada()
    {
        return $this->chamada;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Interessado
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set observacao
     *
     * @param string $observacao
     * @return Interessado
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