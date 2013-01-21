<?php

namespace Ribbit\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FollowType extends AbstractType {

    function buildForm(formBuilderInterface $builder,array $options) {
        $builder->add("user_id", "hidden");
    }

    public function getName() {
        return "follow";
    }

}