<?php

namespace App\Http\Controllers;

use App\Models\Pharmaceutical;
use App\Models\PharmaceuticalCategory;
use App\Models\Vendor;
use App\Support\HisCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | Pharmaceuticals
    |----------------------------------------------------------------------
    */

    public function pharmaceuticals(Request $request): JsonResponse
    {
        $query = Pharmaceutical::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('phar_name', 'like', "%{$search}%")
                    ->orWhere('phar_bcode', 'like', "%{$search}%")
                    ->orWhere('phar_cat', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'data' => $query->orderBy('phar_id')->get(),
        ]);
    }

    /**
     * Add a pharmaceutical. Legacy logic preserved: the barcode is a
     * random numeric code generated with the original algorithm.
     */
    public function storePharmaceutical(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phar_name' => ['required', 'string', 'max:200'],
            'phar_desc' => ['nullable', 'string'],
            'phar_qty' => ['nullable', 'string', 'max:200'],
            'phar_cat' => ['nullable', 'string', 'max:200'],
            'phar_vendor' => ['nullable', 'string', 'max:200'],
            'phar_bcode' => ['nullable', 'string', 'max:200'],
        ]);

        if (!isset($data['phar_bcode'])) {
            do {
                $data['phar_bcode'] = HisCode::numericCode();
            } while (Pharmaceutical::where('phar_bcode', $data['phar_bcode'])->exists());
        }

        $pharmaceutical = Pharmaceutical::create($data);

        return response()->json([
            'message' => 'Pharmaceutical Added',
            'data' => $pharmaceutical,
        ], 201);
    }

    /**
     * Update a pharmaceutical. Legacy logic preserved from
     * his_doc_update_single_pharm.php: name, description, quantity,
     * category and vendor are updated by barcode — the barcode itself
     * never changes.
     */
    public function updatePharmaceutical(Request $request, Pharmaceutical $pharmaceutical): JsonResponse
    {
        $data = $request->validate([
            'phar_name' => ['sometimes', 'string', 'max:200'],
            'phar_desc' => ['sometimes', 'nullable', 'string'],
            'phar_qty' => ['sometimes', 'nullable', 'string', 'max:200'],
            'phar_cat' => ['sometimes', 'nullable', 'string', 'max:200'],
            'phar_vendor' => ['sometimes', 'nullable', 'string', 'max:200'],
        ]);

        $pharmaceutical->update($data);

        return response()->json([
            'message' => 'Pharmaceutical Updated',
            'data' => $pharmaceutical->fresh(),
        ]);
    }

    /**
     * Remove a pharmaceutical. Legacy logic preserved: the manage pages
     * deleted pharmaceutical rows (by id).
     */
    public function destroyPharmaceutical(Pharmaceutical $pharmaceutical): JsonResponse
    {
        $pharmaceutical->delete();

        return response()->json(['message' => 'Pharmaceutical Removed']);
    }

    /*
    |----------------------------------------------------------------------
    | Pharmaceutical categories
    |----------------------------------------------------------------------
    */

    public function categories(): JsonResponse
    {
        return response()->json([
            'data' => PharmaceuticalCategory::orderBy('pharm_cat_id')->get(),
        ]);
    }

    public function storeCategory(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pharm_cat_name' => ['required', 'string', 'max:200'],
            'pharm_cat_vendor' => ['nullable', 'string', 'max:200'],
            'pharm_cat_desc' => ['nullable', 'string'],
        ]);

        $category = PharmaceuticalCategory::create($data);

        return response()->json([
            'message' => 'Category Added',
            'data' => $category,
        ], 201);
    }

    /**
     * Update a category. Legacy logic preserved: the original update set
     * vendor and description by category name — the name never changes.
     */
    public function updateCategory(Request $request, PharmaceuticalCategory $category): JsonResponse
    {
        $data = $request->validate([
            'pharm_cat_vendor' => ['sometimes', 'nullable', 'string', 'max:200'],
            'pharm_cat_desc' => ['sometimes', 'nullable', 'string'],
        ]);

        $category->update($data);

        return response()->json([
            'message' => 'Category Updated',
            'data' => $category->fresh(),
        ]);
    }

    public function destroyCategory(PharmaceuticalCategory $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Category Removed']);
    }

    /*
    |----------------------------------------------------------------------
    | Vendors
    |----------------------------------------------------------------------
    */

    public function vendors(): JsonResponse
    {
        return response()->json([
            'data' => Vendor::orderBy('v_id')->get(),
        ]);
    }

    /**
     * Add a vendor. Legacy logic preserved: the vendor number is generated
     * with the original algorithm when not supplied.
     */
    public function storeVendor(Request $request): JsonResponse
    {
        $data = $request->validate([
            'v_name' => ['required', 'string', 'max:200'],
            'v_adr' => ['nullable', 'string', 'max:200'],
            'v_mobile' => ['nullable', 'string', 'max:200'],
            'v_email' => ['nullable', 'email', 'max:200'],
            'v_phone' => ['nullable', 'string', 'max:200'],
            'v_desc' => ['nullable', 'string'],
            'v_number' => ['nullable', 'string', 'max:200'],
        ]);

        if (!isset($data['v_number'])) {
            do {
                $data['v_number'] = HisCode::vendorNumber();
            } while (Vendor::where('v_number', $data['v_number'])->exists());
        }

        $vendor = Vendor::create($data);

        return response()->json([
            'message' => 'Vendor Added',
            'data' => $vendor,
        ], 201);
    }

    /**
     * Update a vendor. Legacy logic preserved from
     * his_admin_update_single_vendor.php: name, address, email, phone and
     * description are updated by vendor number — the number never changes.
     */
    public function updateVendor(Request $request, Vendor $vendor): JsonResponse
    {
        $data = $request->validate([
            'v_name' => ['sometimes', 'string', 'max:200'],
            'v_adr' => ['sometimes', 'nullable', 'string', 'max:200'],
            'v_email' => ['sometimes', 'nullable', 'email', 'max:200'],
            'v_phone' => ['sometimes', 'nullable', 'string', 'max:200'],
            'v_desc' => ['sometimes', 'nullable', 'string'],
        ]);

        $vendor->update($data);

        return response()->json([
            'message' => 'Vendor Updated',
            'data' => $vendor->fresh(),
        ]);
    }
}
