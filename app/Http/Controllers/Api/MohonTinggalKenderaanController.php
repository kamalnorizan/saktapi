<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetPermohonanByDateRequest;
use App\Models\MohonTinggalKenderaan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MohonTinggalKenderaanController extends Controller
{

    public function getByDate(GetPermohonanByDateRequest $request)
    {
        try {
            $requestedDate = Carbon::parse($request->date);

            $permohonanList = MohonTinggalKenderaan::with(['user:id,name,email', 'lot:id,name'])
                ->where(function ($query) use ($requestedDate) {
                    $query->whereDate('tarikh_mula', '<=', $requestedDate)
                          ->whereDate('tarikh_tamat', '>=', $requestedDate);
                })
                ->where('status', '1') // Active status
                ->orderBy('tarikh_mula', 'desc')
                ->get();

            $formattedData = $permohonanList->map(function ($item) {
                return [
                    'id' => $item->id,
                    'uuid' => $item->uuid,
                    'no_rujukan' => $item->no_rujukan,
                    'user' => [
                        'id' => $item->user->id ?? null,
                        'name' => $item->user->name ?? null,
                        'email' => $item->user->email ?? null,
                    ],
                    'lot' => [
                        'id' => $item->lot->id ?? null,
                        'name' => $item->lot->name ?? null,
                    ],
                    'vehicle_details' => [
                        'model' => $item->model,
                        'no_pendaftaran' => $item->no_pendaftaran,
                        'warna' => $item->warna,
                    ],
                    'parking_details' => [
                        'aras' => $item->aras,
                        'bangunan' => $item->bangunan,
                    ],
                    'period' => [
                        'tarikh_mula' => $item->tarikh_mula ? Carbon::parse($item->tarikh_mula)->format('Y-m-d H:i:s') : null,
                        'tarikh_tamat' => $item->tarikh_tamat ? Carbon::parse($item->tarikh_tamat)->format('Y-m-d H:i:s') : null,
                    ],
                    'tujuan' => $item->tujuan,
                    'tarikh_mohon' => $item->tarikh_mohon ? Carbon::parse($item->tarikh_mohon)->format('Y-m-d H:i:s') : null,
                    'status_permohonan' => $item->status_permohonan,
                    'status_permohonan_text' => $this->getStatusText($item->status_permohonan),
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => [
                    'requested_date' => $requestedDate->format('Y-m-d'),
                    'total_records' => $formattedData->count(),
                    'permohonan_list' => $formattedData
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status_permohonan' => 'nullable|integer|in:0,1,2,3',
            'user_id' => 'nullable|integer|exists:users,id',
            'date_from' => 'nullable|date|date_format:Y-m-d',
            'date_to' => 'nullable|date|date_format:Y-m-d|after_or_equal:date_from',
            'selected_date' => 'nullable|date|date_format:Y-m-d',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = MohonTinggalKenderaan::with(['user:id,name,email', 'lot:id,name'])
                ->where('status', '1');

            if ($request->has('status_permohonan')) {
                $query->where('status_permohonan', $request->status_permohonan);
            }

            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('selected_date')) {
                $requestedDate = Carbon::parse($request->selected_date);
                $query->where(function ($q) use ($requestedDate) {
                    $q->whereDate('tarikh_mula', '<=', $requestedDate)
                          ->whereDate('tarikh_tamat', '>=', $requestedDate);
                });
            }

            if ($request->has('date_from')) {
                $query->whereDate('tarikh_mula', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('tarikh_tamat', '<=', $request->date_to);
            }

            $perPage = $request->get('per_page', 15);
            $permohonanList = $query->orderBy('created_at', 'desc')->paginate($perPage);

            $formattedData = $permohonanList->getCollection()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'uuid' => $item->uuid,
                    'no_rujukan' => $item->no_rujukan,
                    'user' => [
                        'id' => $item->user->id ?? null,
                        'name' => $item->user->name ?? null,
                        'email' => $item->user->email ?? null,
                    ],
                    'lot' => [
                        'id' => $item->lot->id ?? null,
                        'name' => $item->lot->name ?? null,
                    ],
                    'vehicle_details' => [
                        'model' => $item->model,
                        'no_pendaftaran' => $item->no_pendaftaran,
                        'warna' => $item->warna,
                    ],
                    'parking_details' => [
                        'aras' => $item->aras,
                        'bangunan' => $item->bangunan,
                    ],
                    'period' => [
                        'tarikh_mula' => $item->tarikh_mula ? Carbon::parse($item->tarikh_mula)->format('Y-m-d H:i:s') : null,
                        'tarikh_tamat' => $item->tarikh_tamat ? Carbon::parse($item->tarikh_tamat)->format('Y-m-d H:i:s') : null,
                    ],
                    'tujuan' => $item->tujuan,
                    'tarikh_mohon' => $item->tarikh_mohon ? Carbon::parse($item->tarikh_mohon)->format('Y-m-d H:i:s') : null,
                    'status_permohonan' => $item->status_permohonan,
                    'status_permohonan_text' => $this->getStatusText($item->status_permohonan),
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => [
                    'permohonan_list' => $formattedData,
                    'pagination' => [
                        'current_page' => $permohonanList->currentPage(),
                        'last_page' => $permohonanList->lastPage(),
                        'per_page' => $permohonanList->perPage(),
                        'total' => $permohonanList->total(),
                        'from' => $permohonanList->firstItem(),
                        'to' => $permohonanList->lastItem(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get status text based on status code
     */
    private function getStatusText(int $status): string
    {
        return match($status) {
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
            3 => 'Cancelled',
            default => 'Unknown'
        };
    }
}
