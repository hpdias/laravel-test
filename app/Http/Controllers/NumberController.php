<?php

namespace App\Http\Controllers;

use App\Models\Number;
use App\Models\NumberPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class NumberController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    private $error = '';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Get only the numbers of the logged user
        $numbers = Number::
            select('numbers.id', 'customers.name as customer_name', 'numbers.number', 'numbers.status')
            ->join('customers', 'numbers.customer_id', '=', 'customers.id')
            ->where('customers.user_id', Auth::user()->id)
            ->get();

        $n = new Number();
        foreach ($numbers as &$number) {
            $number->status = $n->statusOptions()[$number->status];
        }

        return \Inertia\Inertia::render('Number/Index', [
            'numbers' => $numbers,
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

        $number = new Number();
        $statusOption = $number->statusOptions();

        $customers = Auth::user()->customer;

        return \Inertia\Inertia::render('Number/Create', [
            'statusOptions' => $statusOption,
            'customers' => $customers,
            'number' => $number,
            'error' => $this->error,
            'flowCustomerId' => $request->query('customer_id') ?: null
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
            'customer_id' => 'required',
            'number' => 'required|min:8|max:14',
            'status' => 'required'
        ]);

        try {

            //In a transaction, if any of the inserts fails, nothing persists
            $number = DB::transaction(function () use ($data) {

                $number = Number::create($data);

                //creating and saving the number preferences
                $numberPreference['number_id'] = $number->id;
                $numberPreference['name'] = 'auto_attendant';
                $numberPreference['value'] = '1';

                NumberPreference::create($numberPreference);
                
                $numberPreference['name'] = 'voicemail_enabled';

                NumberPreference::create($numberPreference);

                return $number;
            });

            // if is flow go to the next page
            if($request->query('customer_id') !== null){
                return redirect(URL::route('number_preference.create', ['number_id' => $number->id]));
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
     * @param  \App\Models\Number  $numbers
     * @return \Illuminate\Http\Response
     */
    public function show(Number $number)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Number  $Number
     * @return \Illuminate\Http\Response
     */
    public function edit(Number $number)
    {

        $n = new Number();
        $statusOption = $n->statusOptions();

        $customers = Auth::user()->customer;

        return \Inertia\Inertia::render('Number/Create', [
            'statusOptions' => $statusOption,
            'customers' => $customers,
            'number' => $number,
            'error' => $this->error
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Number  $numbers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Number $number)
    {
        //Can edit only the status
        $data = $request->validate([
            'status' => 'required'
        ]);

        try {
            $number->update($data);

            return $this->index();
        } catch (\Throwable $th) {
            
            $this->error = 'Error: ' . $th->getMessage();
            
            return $this->edit($number);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Number  $numbers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Number $number)
    {

        if (!$number->numberPreference->isEmpty()) {

            $this->error = 'Error: This record has dependency on the table of number preferences';

            return $this->index();
        } else {
            $number->delete($number);

            return $this->index();
        }
    }

    private function validateRequest($request)
    {
        return $request->validate([
            'customer_id' => 'required',
            'number' => 'required|min:8|max:14',
            'status' => 'required'
        ]);
    }
}
