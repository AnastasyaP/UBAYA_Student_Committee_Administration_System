<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($token)
    {
        $registration = Registration::where('invitation_token',$token)->first();

        if(!$registration){
            abort(404);
        }

        return view('invitation.confirm', compact('registration'));
    }

    public function accept($token)
    {
        $registration = Registration::where('invitation_token',$token)->first();

        $registration->status = 'accepted';
        $registration->save();

        return redirect('/')->with('success','Invitation accepted!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
