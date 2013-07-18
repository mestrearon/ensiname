<?php

namespace Ist\Bundle\EnsinameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aluno
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Aluno
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
     * @var string
     *
     * @ORM\Column(name="estudo", type="string", length=1)
     */
    private $estudo;

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
     * @return Aluno
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
     * @return Aluno
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
     * @return Aluno
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
     * Has linguas
     *
     * @return string 
     */
    public function hasLinguas()
    {
        return !empty($this->linguas);
    }

    /**
     * Set estudo
     *
     * @param string $estudo
     * @return Aluno
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
     * Set fone
     *
     * @param string $fone
     * @return Aluno
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
     * @return Aluno
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
     * Set status
     *
     * @param string $status
     * @return Aluno
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
     * @return Aluno
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