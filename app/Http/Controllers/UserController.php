<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(["users" => User::all()], 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            "name" => "required|min:5|max:50",
            "email" => "required|email|unique:users",
            "password" => "required|min:5|max:50"
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) { 
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(["message" => "User has been Created!", "user" => $user], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(is_null($id)){
            return response()->json(["message" => "User not found"], 404);
        }

        $user = User::find($id);
        if(is_null($user)){
            return response()->json(["message" => "User not found"], 404);
        }

        return response()->json(["users" => $user], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            "name" => "required|min:5|max:50",
            "email" => "required|email",
            "password" => "required|min:5|max:50"
        ];

        $validator = \Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::find($id);
        if(is_null($user)){
            return response()->json(["message" => "User not found"], 404);
        }

        $user->update($request->all());

        return response()->json(["message" => "User has been Updated!", "users" => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return response()->json(["message" => "User not found"], 404);
        }

        $user->delete();
        return response()->json(["message" => "User has been Deleted!"], 200);
        
    }
}
