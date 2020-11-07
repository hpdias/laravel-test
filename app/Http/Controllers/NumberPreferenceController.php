<?php

namespace App\Http\Controllers;

use App\Models\Number;
use App\Models\NumberPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NumberPreferenceController extends Controller
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


        // Get only the numbers preferenfces of the logged user
        $numbersPreferences = DB::table('number_preferences')
            ->select('number_preferences.id', 'customers.name as customer_name', 'numbers.number as number_name', 'number_preferences.name', 'number_preferences.value')
            ->join('numbers', 'numbers.id', '=', 'number_preferences.number_id')
            ->join('customers', 'numbers.customer_id', '=', 'customers.id')
            ->where('customers.user_id', Auth::user()->id)
            ->where('number_preferences.deleted_at',NULL)
            ->get();

        return \Inertia\Inertia::render('NumberPreference/Index', [
            'numbersPreferences' => $numbersPreferences,
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
        $numbers = Number::all();

        return \Inertia\Inertia::render('NumberPreference/Create', [
            'numbers' => $numbers,
            'error' => $this->error,
            'flowNumberId' => $request->query('number_id') ?: null

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
        $data = $this->validateRequest($request);

        try {

            NumberPreference::create($data);

            return $this->index();
        } catch (\Throwable $th) {

            $this->error = 'Error: ' . $th->getMessage();

            return $this->create($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NumberPreference  $numberPreference
     * @return \Illuminate\Http\Response
     */
    public function show(NumberPreference $numberPreference)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NumberPreference  $numberPreference
     * @return \Illuminate\Http\Response
     */
    public function edit(NumberPreference $numberPreference)
    {

        $numbers = Number::all();

        return \Inertia\Inertia::render('NumberPreference/Create', [
            'numbers' => $numbers,
            'numberPreference' => $numberPreference,
            'error' => $this->error

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NumberPreference  $numberPreference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NumberPreference $numberPreference)
    {
        $data = $this->validateRequest($request);

        try {

            $numberPreference->update($data);

            return $this->index();
        } catch (\Throwable $th) {

            $this->error = 'Error: ' . $th->getMessage();

            return $this->edit($numberPreference);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NumberPreference  $numberPreference
     * @return \Illuminate\Http\Response
     */
    public function destroy(NumberPreference $numberPreference)
    {

        $numberPreference->delete($numberPreference);

        return $this->index();
    }

    private function validateRequest($request)
    {
        return $request->validate([
            'number_id' => 'required',
            'name' => 'required',
            'value' => 'required'
        ]);
    }

}
