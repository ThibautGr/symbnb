<?php

namespace App\form;

use Symfony\Component\Form\AbstractType;


class ApplicationType extends AbstractType{

    /**
     * @param string $label
     * @param ?string $placeholder
     * @param  array $option
     * @return array
     */
    public function setLabelHolder(string $label, string $placeholder = null, $option = []) : array
    {
        return array_merge_recursive([
            'label' => $label,
            'attr'  => [
                'placeholder' => $placeholder
            ]
        ], $option);
    }
}