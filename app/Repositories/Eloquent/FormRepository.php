<?php


namespace App\Repositories\Eloquent;

use App\Models\Form;
use App\Repositories\Interfaces\FormRepositoryInterface;

class FormRepository implements FormRepositoryInterface
{
    public function all() {
        return Form::with('fields')->get();
    }

    public function find($id) {
        return Form::with('fields')->findOrFail($id);
    }

    public function create(array $data) {
        return Form::create($data);
    }

    public function update($id, array $data) {
        $form = $this->find($id);
        $form->update($data);
        return $form;
    }

    public function delete($id) {
        $form = $this->find($id);
        return $form->delete();
    }
}