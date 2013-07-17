<?php

namespace Ist\Bundle\EnsinameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Professor
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Professor
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
     * @var \DateTime
     *
     * @ORM\Column(name="nascimento", type="string", length=255)
     */
    private $nascimento;

    /**
     * @var string
     *
     * @ORM\Column(name="linguas", type="text", nullable=true)
     */
    private $linguas;

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
     * @ORM\Column(name="horario", type="string", length=255)
     */
    private $horario;

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
     * @return Professor
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
     * Set nascimento
     *
     * @param string $nascimento
     * @return Professor
     */
    public function setNascimento($nascimento)
    {
        $this->nascimento = $nascimento;
    
        return $this;
    }

    /**
     * Get nascimento
     *
     * @return string 
     */
    public function getNascimento()
    {
        return $this->nascimento;
    }

    /**
     * Set linguas
     *
     * @param string $linguas
     * @return Professor
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
     * Set fone
     *
     * @param string $fone
     * @return Professor
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
     * @return Professor
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
     * Set horario
     *
     * @param string $horario
     * @return Professor
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
     * Set observacao
     *
     * @param string $observacao
     * @return Professor
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