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

        $data['phar_bcode'] = $data['phar_bcode'] ?? HisCode::numericCode();

        $pharmaceutical = Pharmaceutical::create($data);

        return response()->json([
            'message' => 'Pharmaceutical Added',
            'data' => $pharmaceutical,
        ], 201);
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

        $data['v_number'] = $data['v_number'] ?? HisCode::vendorNumber();

        $vendor = Vendor::create($data);

        return response()->json([
            'message' => 'Vendor Added',
            'data' => $vendor,
        ], 201);
    }
}
