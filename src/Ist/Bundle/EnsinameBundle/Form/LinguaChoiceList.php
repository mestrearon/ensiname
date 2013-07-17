<?php

namespace Ist\Bundle\EnsinameBundle\Form;

use Symfony\Component\Form\Extension\Core\ChoiceList\LazyChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class LinguaChoiceList extends LazyChoiceList
{
    protected $linguas;

    function __construct($linguas) 
    {
        $this->linguas = $linguas;
    }

    public function loadChoiceList()
    {
        foreach ($this->linguas as $lingua)
        {
            $choices[] = $lingua->getId();
            $labels[] = $lingua->getTitulo();
        }

        return new ChoiceList($choices, $labels);
    }
}
