<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;


class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $assets = Asset::with('category')->get();
            return response()->json([
                'status' => 'success',
                'data' => $assets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'asset_code' => 'required|string|max:100|unique:assets,asset_code,' . $request->id,
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'quantity' => 'integer|min:0',
            'amount' => 'numeric|min:0',
            'established_at' => 'date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $asset = Asset::create($validator->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'Aset berhasil ditambahkan.',
                'data' => $asset
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $asset = Asset::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $asset
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Aset tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'asset_code' => 'sometimes|required|string|max:100|unique:assets,asset_code,' . $id . ',id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'location' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:0',
            'amount' => 'sometimes|required|numeric|min:0',
            'established_at' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $asset = Asset::findOrFail($id);
            $asset->update([
                'name' => $request->name,
                'asset_code' => $request->asset_code,
                'category_id' => $request->category_id,
                'location' => $request->location,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'amount' => $request->amount,
                'established_at' => $request->established_at,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Aset berhasil diperbarui.',
                'data' => $asset
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Aset tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $asset = Asset::findOrFail($id);
            $asset->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Aset berhasil dihapus.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Aset tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generatePdf()
    {
        $assets = Asset::all();
        $categories = Category::all();

        $pdf = Pdf::loadView('pdf.asset_report', compact('assets', 'categories'))
                ->setPaper('A4', 'portrait');

        return $pdf->stream('laporan_aset.pdf');
    }
}
