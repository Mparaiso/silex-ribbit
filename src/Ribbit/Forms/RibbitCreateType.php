<?php

namespace Ribbit\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RibbitCreateType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("ribbit", "textarea", array("constraints" =>
            array(new Assert\NotBlank(),
                new Assert\MaxLength(255),
            ),
            "attr" => array("class" => "ribbitText")
                )
        );
        $builder->add("forward", "hidden");
    }

    public function getName() {
        return "ribbit_create";
    }

}