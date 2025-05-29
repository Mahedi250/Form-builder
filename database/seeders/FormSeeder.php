<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Form;
use App\Models\FormField;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        // Example 1: Contact Form
        $contactForm = Form::create([
            'title' => 'Contact Us',
            'method' => 'POST',
            'action' => '/contact',
        ]);

        $contactForm->fields()->createMany([
            [
                'label' => 'Name',
                'type' => 'text',
                'name' => 'name',
                'order_index' => 0,
                'required' => true,
            ],
            [
                'label' => 'Email',
                'type' => 'email',
                'name' => 'email',
                'order_index' => 1,
                'required' => true,
            ],
            [
                'label' => 'Message',
                'type' => 'textarea',
                'name' => 'message',
                'order_index' => 2,
                'required' => true,
            ],
        ]);

        // Example 2: Feedback Form
        $feedbackForm = Form::create([
            'title' => 'Feedback',
            'method' => 'POST',
            'action' => '/feedback',
        ]);

        $feedbackForm->fields()->createMany([
            [
                'label' => 'Full Name',
                'type' => 'text',
                'name' => 'full_name',
                'order_index' => 0,
                'required' => true,
            ],
            [
                'label' => 'Rating',
                'type' => 'select',
                'name' => 'rating',
                'order_index' => 1,
                'required' => true,
                'options' => json_encode(['Excellent', 'Good', 'Average', 'Poor']),
            ],
            [
                'label' => 'Comments',
                'type' => 'textarea',
                'name' => 'comments',
                'order_index' => 2,
                'required' => false,
            ],
        ]);
    }
}