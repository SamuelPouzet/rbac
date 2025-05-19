<?php

namespace SamuelPouzet\Rbac\Form;

use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use SamuelPouzet\Rbac\Interface\Entities\NewRoleInterface;

class NewRoleForm extends Form implements NewRoleInterface
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);
        $this->addFormElements();
        $this->addInputFilters();
    }

    protected function addFormElements(): void
    {
        $this->add([
            'name' => 'name',
            'type' => Text::class,
            'options' => [
                'label' => 'Nom du rôle',
            ],
        ]);

        $this->add([
            'name' => 'description',
            'type' => Text::class,
            'options' => [
                'label' => 'Description du rôle',
            ],
        ]);
    }

    protected function addInputFilters(): void
    {
        // todo check if name doesn't exists
    }
}
