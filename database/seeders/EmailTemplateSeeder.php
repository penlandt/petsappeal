<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $defaultTemplates = [
            [
                'type' => 'grooming',
                'template_key' => 'appointment_booked',
                'subject' => 'Appointment Confirmed for {{ pet_name }}',
                'body_html' => '<p>Hello {{ client_name }},</p><p>Your grooming appointment for {{ pet_name }} has been booked for {{ appointment_date }} at {{ appointment_time }} with {{ staff_name }} at {{ location_name }}.</p><p>Thank you!</p><p>- {{ company_name }}</p>',
                'body_plain' => "Hello {{ client_name }},\n\nYour grooming appointment for {{ pet_name }} has been booked for {{ appointment_date }} at {{ appointment_time }} with {{ staff_name }} at {{ location_name }}.\n\nThank you!\n- {{ company_name }}",
            ],
            [
                'type' => 'grooming',
                'template_key' => 'appointment_1_week',
                'subject' => 'Upcoming Appointment Reminder: {{ pet_name }}',
                'body_html' => '<p>Hi {{ client_name }},</p><p>This is a friendly reminder that {{ pet_name }} has a grooming appointment coming up in one week on {{ appointment_date }} at {{ appointment_time }} with {{ staff_name }}.</p><p>See you soon!</p><p>- {{ company_name }}</p>',
                'body_plain' => "Hi {{ client_name }},\n\nThis is a friendly reminder that {{ pet_name }} has a grooming appointment in one week on {{ appointment_date }} at {{ appointment_time }} with {{ staff_name }}.\n\nSee you soon!\n- {{ company_name }}",
            ],
            [
                'type' => 'grooming',
                'template_key' => 'appointment_1_day',
                'subject' => 'Reminder: Appointment Tomorrow for {{ pet_name }}',
                'body_html' => '<p>Hello {{ client_name }},</p><p>Just a reminder that {{ pet_name }} has a grooming appointment tomorrow ({{ appointment_date }}) at {{ appointment_time }} with {{ staff_name }} at {{ location_name }}.</p><p>We look forward to seeing you!</p><p>- {{ company_name }}</p>',
                'body_plain' => "Hello {{ client_name }},\n\nJust a reminder that {{ pet_name }} has a grooming appointment tomorrow ({{ appointment_date }}) at {{ appointment_time }} with {{ staff_name }} at {{ location_name }}.\n\nWe look forward to seeing you!\n- {{ company_name }}",
            ],
        ];

        $companies = Company::all();

        foreach ($companies as $company) {
            foreach ($defaultTemplates as $template) {
                EmailTemplate::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'type' => $template['type'],
                        'template_key' => $template['template_key'],
                    ],
                    [
                        'subject' => $template['subject'],
                        'body_html' => $template['body_html'],
                        'body_plain' => $template['body_plain'],
                    ]
                );
            }
        }
    }
}
