<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\Favorite;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Order_medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ApiResponseTrait;
    private static $previousNumberOfOrders = 6;

    public function makeOrder(Request $request)

    {
        $orderIteme = $request->all();
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'status' => 'are preparing',
        ]);

        $order->save();
        $id = $order->id;
        foreach ($orderIteme['data'] as $medicineData) {
            $medicine = Medicine::where('commercial_name', $medicineData['name'])->first();

            $ord = Order_medicine::create([
                'order_id' => $id,
                'medicine_id' => $medicine->id,
                'request_quantity' => $medicineData['quantities'],
            ]);
        }

        return $this->apiResponse($orderIteme, 'done !!', 200);
    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function viewOrder()
    {
        $pharmacyId = auth()->user()->id;

        $ord = Order::with('medicines')
            ->whereHas('medicines', function ($query) use ($pharmacyId) {
                $query->where('user_id', $pharmacyId);
            })
            ->get();
        $phone = auth()->user()->phone;
        $data = [];
        $data['order'] = $ord;
        $data['phone'] = $phone;

        return $this->apiResponse($data, 'done!!!', 200);

        return response()->json('Your account is Admin');
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function viewOrderAdmin()
    {
        $orders = Order::with(['medicines', 'user'])->get();

        $data = [
            'orders' => $orders,
        ];

        return $this->apiResponse($data, 'ok!!', 200);
    }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function changeOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => ['required', 'string'],
        ]);
        $order = Order::find($orderId);
        $order->status = $request->input('status');
        $order->save();
        if ($order->status == "Sent") {

            foreach ($order->medicines as $medicine) {
                $newQuantity = $medicine->pivot->request_quantity;
                $medicine->available_quantity -= $newQuantity;
                $medicine->save();
            }
        }
        return $this->apiResponse($order, 'done!!', 200);
    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function changePaymentStatus(Request $request, $orderId)
    {
        $input = $request->validate([

            'paid_status' => ['required']
        ]);
        $order = Order::find($orderId);
        $order->update([
            'paid_status' => $input['paid_status']

        ]);
        $order->save();

        return $this->apiResponse($order, 'done!!', 200);
    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function favorite_medicine($id)
    {
        // Check if the medicine is already in favorites for the current user
        $existingFavorite = Favorite::where('favorite_medicine', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if ($existingFavorite) {
            // Medicine already in favorites, return an error response
            return $this->apiResponse(null, 'Medicine is already in favorites', 422);
        }

        // If not, add the medicine to favorites
        $favorite = Favorite::create([
            'favorite_medicine' => $id,
            'user_id' => auth()->user()->id
        ]);

        return $this->apiResponse($favorite, 'Medicine added to favorites successfully', 200);
    }


    public function unfavorite_medicine($id)
    {
        $favorite = Favorite::where('favorite_medicine', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return $this->apiResponse(null, 'Medicine unfavorited successfully', 200);
        } else {
            return $this->apiResponse(null, 'Medicine not found in favorites', 404);
        }
    }

    public function checkFavoriteStatus($id)
    {
        $favorite = Favorite::where('favorite_medicine', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        $isFavorite = $favorite ? true : false;

        return response()->json(['is_favorite' => $isFavorite]);
    }


    public function list()
    {
        $userFavorites = Favorite::where('user_id', auth()->user()->id)
            ->with('medicine')
            ->get();

        return $this->apiResponse($userFavorites, 'done!!', 200);
    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function orderSalesReport(Request $request)
    {

        $request->validate([
           'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);


        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');


        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('paid_status', '=', 1)
            ->get();

        $orders->each(function ($order) {
            $order->total_price = $order->medicines->sum('price');
        });

        return $orders;
    }

    public function orderReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Use eager loading to load medicines and user
        $orders = Order::with(['medicines', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('paid_status', '=', 0)
            ->get();

        // Calculate the total price for each order


        return $orders;
    }
}
