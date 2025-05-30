<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FormService;
use App\Models\Form;
use Inertia\Inertia;
use App\Http\Requests\Form\StoreFormRequest;
use App\Http\Requests\Form\UpdateFormRequest;

class FormController extends Controller
{
    protected $service;

    public function __construct(FormService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
    
        return Inertia::render('Forms/index', [
            'forms' => Form::with('fields')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Forms/create');
    }

    public function store(StoreFormRequest $request)
    {
        $form = $this->service->storeFormWithFields(
            $request->only(['title', 'method', 'action']),
            $request->input('fields')
        );

        return redirect()->route('forms.index')->with('success', 'Form created.');
    }

    public function update(UpdateFormRequest $request, Form $form)
    {
        $this->service->updateFormWithFields(
            $form->id,
            $request->only(['title', 'method', 'action']),
            $request->input('fields')
        );

        return redirect()->route('forms.index')->with('success', 'Form updated.');
    }
    public function destroy($id)
    {
       return $this->service->deleteForm($id);
        
    }
 
    
}
