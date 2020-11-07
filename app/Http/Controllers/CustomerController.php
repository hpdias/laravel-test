<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CustomerController extends Controller
{

    private $error = '';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $customers = Auth::user()->customer;

        foreach ($customers as &$customer) {
            $customer['status']      = $customer->statusOptions()[$customer['status']];
            $customer['count_numbers'] = count($customer->number);
        }

        return \Inertia\Inertia::render('Customer/Index', [
            'customers' => $customers,
            'error' => $this->error
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customer = new Customer();
        $statusOption = $customer->statusOptions();



        return \Inertia\Inertia::render('Customer/Create', [
            'statusOptions' => $statusOption,
            'error' => $this->error,
            'customer' => $customer,
            'flow' => $request->query('flow') ? true : false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'document' => 'required|min:6|max:12',
            'status' => 'required'
        ]);

        try {

            $data['user_id'] = Auth::user()->id;

            $result = Customer::create($data);

            if($request->query('flow') !== null){
                return redirect(URL::route('number.create', ['customer_id' =>$result['id']]));
            }

            return $this->index();
        } catch (\Throwable $th) {

            $this->error = 'Error: ' . $th->getMessage();

            return $this->create($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $c = new Customer();
        $statusOption = $c->statusOptions();

        return \Inertia\Inertia::render('Customer/Create', [
            'statusOptions' => $statusOption,
            'customer' => $customer,
            'error' => $this->error
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //Can edit only the status
        $data = $request->validate([
            'status' => 'required'
        ]);

        try {
            $customer->update($data);

            return $this->index();
        } catch (\Throwable $th) {

            $this->error = 'Error: ' . $th->getMessage();

            return $this->edit($customer);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if (!$customer->number->isEmpty()) {
            $this->error = 'Error: This record has dependency on the table of numbers';

            return $this->index();
        } else {

            $customer->delete($customer);

            return $this->index();
        }
    }
}
