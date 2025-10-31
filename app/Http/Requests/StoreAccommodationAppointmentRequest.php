<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccommodationAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // tighten if you require auth
    }

    public function rules(): array
    {
        return [
            'point_of_interest_id'   => ['required', 'integer', 'exists:points_of_interest,id'],
            'appointment_date'       => ['required', 'date', 'after_or_equal:today'],
            'end_date'               => ['required', 'date', 'after:appointment_date'],
            'number_of_guests'       => ['required', 'integer', 'min:1', 'max:20'],
            'special_requests'       => ['nullable', 'string', 'max:5000'],
            'appointment_details'    => ['nullable', 'array'],

            // Optional known detail keys; won’t fail if others come through
            'appointment_details.room_type'           => ['nullable', 'string', Rule::in(['single','double','twin','suite','apartment','studio'])],
            'appointment_details.cuisine_preferences' => ['nullable', 'string', 'max:255'],
            'appointment_details.duration'            => ['nullable', 'string', 'max:255'],
            'appointment_details.equipment_rental'    => ['nullable', 'boolean'],
            'appointment_details.consultation_type'   => ['nullable', 'string', 'max:255'],
            'appointment_details.document_preparation'=> ['nullable', 'boolean'],
            'appointment_details.service_type'        => ['nullable', 'string', 'max:255'],
            'appointment_details.documents'           => ['nullable', 'array'],
            'appointment_details.ticket_type'         => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'point_of_interest_id.exists' => 'Selected property was not found.',
            'end_date.after'              => 'Check-out must be after check-in.',
        ];
    }
}
