<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{

    use ApiResponseTrait;

    public function showAll()
    {
        $medicines=Medicine::all();
        return $this->apiResponse($medicines,'ok',200);
    }

    public function store(Request $request)
    {

        $med=Medicine::create($request->all());
        $med->save();
        return $this->apiResponse($med,'the medicine saved successfully',201);
    }

    public function MedicinesByCategory()
    {
        $categories = Category::with('medicines')->get();
        return $this->apiResponse($categories,'ok',200);
    }

    public function MedicinesByCategoryId($categoryId){

        $category = Category::findOrFail($categoryId);
        $medicines = Medicine::where('category_id', $category->id)->get();

        return $this->apiResponse($medicines,'ok',200);
    }

    public function choseToShow($id){

        $medicine= Medicine::find($id);
        if($medicine){
            return $this->apiResponse($medicine,'ok',200);
        }
        return $this->apiResponse(null,'the medicine not found',404);
    }

    /////////////////////////////////////////////////////////////////////////////////////////

    public function search(Request $request)
    {
        $input = $request->validate([
            'name' => ['required']
        ]);

        $category = Category::where('name', $request['name'])->first();

        if (!$category) {
            $medicine = Medicine::where('commercial_name', '=', $request['name'])->first();

            if (!$medicine) {
                return response()->json(['error' => 'No medicines found.']);
            }

            return $this->apiResponse($medicine, 'done', 200);
        }

        $medicines = Medicine::where('category_id', '=', $category->id)->get();

        return $this->apiResponse($medicines, 'done', 200);
    }

}
