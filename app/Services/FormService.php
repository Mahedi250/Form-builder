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

    public function getAllForms()
    {
        return $this->formRepo->all();
    }

    public function getFormById($formId)
    {
        return $this->formRepo->find($formId);
    }
    public function deleteForm($formId)
    {
     $form = $this->formRepo->find($formId);
       if ($form) {
        $form->delete();
        return true;
      }
      return false;
    }
}