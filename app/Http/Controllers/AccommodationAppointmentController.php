<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccommodationAppointmentRequest;
use App\Models\AccommodationAppointment;
use App\Models\PointOfInterest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AccommodationAppointmentController extends Controller
{
    /**
     * POST /api/appointments
     */
    public function store(StoreAccommodationAppointmentRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Ensure the POI is an accommodation
        $poi = PointOfInterest::query()
            ->whereKey($data['point_of_interest_id'])
            ->firstOrFail();

        if (mb_strtolower($poi->type) !== 'accommodation') {
            return response()->json([
                'message' => 'Bookings are only available for accommodation points.',
            ], 422);
        }

        // (Optional) basic overlap guard — adjust to your needs
        $overlapExists = AccommodationAppointment::query()
            ->where('point_of_interest_id', $poi->id)
            ->where(function ($q) use ($data) {
                $q->whereBetween('appointment_date', [$data['appointment_date'], $data['end_date']])
                  ->orWhereBetween('end_date', [$data['appointment_date'], $data['end_date']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('appointment_date', '<=', $data['appointment_date'])
                         ->where('end_date', '>=', $data['end_date']);
                  });
            })->exists();

        if ($overlapExists) {
            return response()->json([
                'message' => 'The selected dates are not available for this accommodation.',
            ], 409);
        }

        $appointment = AccommodationAppointment::create([
            'point_of_interest_id' => $poi->id,
            'user_id'              => Auth::id(), // null if guest
            'appointment_date'     => $data['appointment_date'],
            'end_date'             => $data['end_date'],
            'number_of_guests'     => $data['number_of_guests'],
            'special_requests'     => $data['special_requests'] ?? null,
            'appointment_details'  => $data['appointment_details'] ?? null,
            'status'               => 'pending',
        ]);

        return response()->json([
            'message'     => 'Appointment created successfully.',
            'appointment' => [
                'id'                 => $appointment->id,
                'status'             => $appointment->status,
                'appointment_date'   => $appointment->appointment_date->toDateString(),
                'end_date'           => $appointment->end_date->toDateString(),
                'number_of_guests'   => $appointment->number_of_guests,
                'special_requests'   => $appointment->special_requests,
                'appointment_details'=> $appointment->appointment_details,
                'poi'                => [
                    'id'    => $poi->id,
                    'name'  => $poi->name,
                    'type'  => $poi->type,
                    'price' => $poi->price,
                    'rating'=> $poi->rating,
                ],
            ],
        ], 201);
    }
}
