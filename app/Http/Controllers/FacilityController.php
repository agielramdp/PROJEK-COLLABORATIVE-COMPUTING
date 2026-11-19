<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class FacilityController extends Controller
{
    // ========================================================
    // 1. GEDUNG
    // ========================================================

    public function indexBuildings()
    {
        try {
            $buildings = Building::withCount(['rooms'])->get();
            return response()->json(['success' => true, 'data' => $buildings]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeBuilding(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'         => 'required|string|max:30|unique:buildings,code',
            'name'         => 'required|string|max:150',
            'total_floors' => 'required|integer|min:1',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ], [
            'code.required' => 'Kode gedung wajib diisi.',
            'code.unique'   => 'Kode gedung sudah terdaftar.',
            'name.required' => 'Nama gedung wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $building = Building::create($request->all());
            AuditLogger::log('CREATE_BUILDING', 'Facility', ['name' => $building->name, 'code' => $building->code], $building->id);

            return response()->json(['success' => true, 'message' => 'Gedung berhasil ditambahkan!', 'data' => $building], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showBuilding($id)
    {
        try {
            $building = Building::with(['rooms'])->find($id);
            if (!$building) {
                return response()->json(['success' => false, 'message' => 'Gedung tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $building]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateBuilding(Request $request, $id)
    {
        $building = Building::find($id);
        if (!$building) {
            return response()->json(['success' => false, 'message' => 'Gedung tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'code'         => 'required|string|max:30|unique:buildings,code,' . $id,
            'name'         => 'required|string|max:150',
            'total_floors' => 'required|integer|min:1',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $building->update($request->all());
            AuditLogger::log('UPDATE_BUILDING', 'Facility', ['name' => $building->name], $building->id);

            return response()->json(['success' => true, 'message' => 'Gedung berhasil diperbarui!', 'data' => $building]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyBuilding($id)
    {
        try {
            $building = Building::find($id);
            if (!$building) {
                return response()->json(['success' => false, 'message' => 'Gedung tidak ditemukan.'], 404);
            }

            $name = $building->name;
            $building->delete();
            AuditLogger::log('DELETE_BUILDING', 'Facility', ['name' => $name], $id);

            return response()->json(['success' => true, 'message' => "Gedung '{$name}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ========================================================
    // 2. RUANGAN & LANTAI
    // ========================================================

    public function indexRooms(Request $request)
    {
        try {
            $query = Room::with('building');

            if ($request->filled('building_id')) {
                $query->where('building_id', $request->building_id);
            }
            if ($request->filled('floor')) {
                $query->where('floor', $request->floor);
            }
            if ($request->filled('room_type')) {
                $query->where('room_type', $request->room_type);
            }

            $rooms = $query->orderBy('building_id')->orderBy('floor')->orderBy('code')->get();
            return response()->json(['success' => true, 'data' => $rooms]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeRoom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'building_id' => 'required|exists:buildings,id',
            'code'        => 'required|string|max:50|unique:rooms,code',
            'name'        => 'required|string|max:150',
            'floor'       => 'required|integer|min:1',
            'capacity'    => 'required|integer|min:1',
            'room_type'   => 'required|in:Teori,Laboratorium,Auditorium,Seminar,Lainnya',
            'facilities'  => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ], [
            'building_id.required' => 'Gedung wajib dipilih.',
            'code.required'        => 'Kode ruangan wajib diisi.',
            'code.unique'          => 'Kode ruangan sudah digunakan.',
            'name.required'        => 'Nama ruangan wajib diisi.',
            'capacity.required'    => 'Kapasitas ruangan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $room = Room::create($request->all());
            AuditLogger::log('CREATE_ROOM', 'Facility', ['name' => $room->name, 'code' => $room->code, 'floor' => $room->floor], $room->id);

            return response()->json([
                'success' => true,
                'message' => 'Ruangan berhasil ditambahkan!',
                'data'    => $room->load('building'),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showRoom($id)
    {
        try {
            $room = Room::with(['building', 'classSchedules.courseClass.course'])->find($id);
            if (!$room) {
                return response()->json(['success' => false, 'message' => 'Ruangan tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $room]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateRoom(Request $request, $id)
    {
        $room = Room::find($id);
        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Ruangan tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'building_id' => 'required|exists:buildings,id',
            'code'        => 'required|string|max:50|unique:rooms,code,' . $id,
            'name'        => 'required|string|max:150',
            'floor'       => 'required|integer|min:1',
            'capacity'    => 'required|integer|min:1',
            'room_type'   => 'required|in:Teori,Laboratorium,Auditorium,Seminar,Lainnya',
            'facilities'  => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $room->update($request->all());
            AuditLogger::log('UPDATE_ROOM', 'Facility', ['name' => $room->name, 'code' => $room->code], $room->id);

            return response()->json([
                'success' => true,
                'message' => 'Ruangan berhasil diperbarui!',
                'data'    => $room->load('building'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyRoom($id)
    {
        try {
            $room = Room::find($id);
            if (!$room) {
                return response()->json(['success' => false, 'message' => 'Ruangan tidak ditemukan.'], 404);
            }

            $name = $room->name;
            $room->delete();
            AuditLogger::log('DELETE_ROOM', 'Facility', ['name' => $name], $id);

            return response()->json(['success' => true, 'message' => "Ruangan '{$name}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
