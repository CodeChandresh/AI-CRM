<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Jobs\LeadAIScorerJob;
use App\Imports\LeadsImport;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

/**
 * LeadController handles CRUD operations for Leads with AI integration
 * and bulk import functionality.
 */
class LeadController extends Controller
{
    /**
     * Display a listing of all leads with pagination.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $leads = Lead::with(['user', 'company'])->paginate(15);
        return response()->json($leads);
    }

    /**
     * Store a newly created lead in storage and trigger AI scoring.
     *
     * @param LeadRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function store(LeadRequest $request)
    {
        DB::beginTransaction();
        try {
            $lead = Lead::create($request->validated());
            dispatch(new LeadAIScorerJob($lead->id));
            DB::commit();
            return response()->json($lead, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified lead with related models.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $lead = Lead::with(['user', 'company', 'notes'])->findOrFail($id);
        return response()->json($lead);
    }

    /**
     * Update the specified lead in storage and trigger AI scoring.
     *
     * @param LeadRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function update(LeadRequest $request, int $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->update($request->validated());
        dispatch(new LeadAIScorerJob($lead->id));
        return response()->json($lead);
    }

    /**
     * Remove the specified lead from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();
        return response()->json(null, 204);
    }

    /**
     * Import leads from CSV/Excel file.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimetypes:text/csv,text/x-csv,application/vnd.ms-excel'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            Excel::import(new LeadsImport, $request->file('file'));
            return response()->json(['message' => 'Leads imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Import failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Assign lead to a specific user.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lead = Lead::findOrFail($id);
        $lead->assigned_user_id = $request->input('user_id');
        $lead->save();

        return response()->json($lead);
    }
}