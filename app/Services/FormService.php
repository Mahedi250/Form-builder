<?php


namespace App\Services;

use App\Repositories\Interfaces\FormRepositoryInterface;

class FormService
{
    protected $formRepo;

    public function __construct(FormRepositoryInterface $formRepo)
    {
        $this->formRepo = $formRepo;
    }

    public function storeFormWithFields(array $formData, array $fields)
    {
        $form = $this->formRepo->create($formData);
        foreach ($fields as $index => $field) {
            $form->fields()->create(array_merge($field, ['order_index' => $index]));
        }
        return $form;
    }

    public function updateFormWithFields($formId, array $formData, array $fields)
    {
        $form = $this->formRepo->update($formId, $formData);
        $form->fields()->delete(); // simple replace strategy
        foreach ($fields as $index => $field) {
            $form->fields()->create(array_merge($field, ['order_index' => $index]));
        }
        return $form;
    }
}