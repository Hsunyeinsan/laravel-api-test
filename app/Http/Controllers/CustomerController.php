<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $userId=Auth::id();

        $cachekey="customer.index." . md5(json_encode(
        [
            "user_id"=>$userId,
            "q"=>$request->get("q"),
            "state_division" => $request->get("state_division"),
            "township" => $request->get("township"),
            "sort_by" => $request->get("sort_by", "id"),
            "sort_direction" => $request->get("sort_direction", "desc"),

        ]
        ));

        $customers=Cache::tags(['customers','user:' . $userId])->rememberForever($cachekey,function () use ($request,$userId)
        {

        $query=Customer::query();
        $keyword=$request->get("q");
        $query->where(function ($q) use ($keyword) {
            $q->where("name","like","%{$keyword}%")
            ->orWhere("year","like","%{$keyword}%")
            ->orWhere("phone","like","%{$keyword}%")
            ->orWhere("email","like","%{$keyword}%")
            ->orWhere("township","like","%{$keyword}%")
            ->orWhere("state_division","like","%{$keyword}%");
        });

        //must filter




        $query->when(Auth::id() != 1, function ($query) {
                $query->where("user_id", Auth::id());
            });
        // $query->where('user_id',Auth::id());

        //filter_by

        $filterByStateDivision=$request->get("filter_by_state_division");
        if($filterByStateDivision){
            $query->where("state_division",$filterByStateDivision);
        }
        $filterByTownship=$request->get("filter_by_township");
        if($filterByTownship){
            $query->where("township",$filterByTownship);
        }

        //order by

        $sort_by=$request->get('sort_by') ?? "id";
        $sort_direction=$request->get('sort_direction') ?? "desc" ;

        $query ->orderBy($sort_by,$sort_direction);
        // $query->latest('id');
        // paginate
        $customers=$query->paginate(5);

        return $customers;


        });
        return response()->json([
	        "data"=>CustomerResource::collection($customers)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {

        $customer = Customer::create([...$request->validated(),'user_id' => Auth::id(),]);

        // $customer=Customer::create([...$request->validated(),"user_id"=>Auth::id()]);
        // $customer=Customer::create($request->validated());
        // $customer=Customer::insert($request->validated());

        Cache::tags(['customers'])->flush();

        return response()->json([
            "message"=>"customer created successfully",
            "data"=>new CustomerResource($customer),
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        Gate::authorize('view',$customer);
        return response()->json([
                "data"=>new CustomerResource($customer),
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {

        Gate::authorize('update',$customer);
        $customer->update($request->validated());
        
        Cache::tags(['customers'])->flush();

        return response()->json([
            "message"=>"Customer updated successfully",
            "data"=>new CustomerResource($customer->fresh())
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {

    Gate::authorize('delete',$customer);
        $customer->delete();

        Cache::tags(['customers'])->flush();

        return response()->json([
            "data"=>[
                "message"=>"Customer deleted successfully",
            ]
        ],200);
    }
}
