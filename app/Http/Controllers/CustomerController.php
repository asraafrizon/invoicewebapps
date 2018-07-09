<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    public function search()
    {
        $results = Customer::orderBy('firstname')
        ->when(request('q'), function($query) {
            $query->where('firstname', 'like', '%'.request('q').'%')
            ->orWhere('lastname', 'like', '%'.request('q').'%')
            ->orWhere('email', 'like', '%'.request('q').'%');
        })
        ->limit(6)
        ->get();

        return response()
        ->json(['results' => $results]);
    }

    public function index() 
    {
        $results = Customer::latest()->paginate(15);

        return response()
        ->json(['results' => $results]);
    }

    public function create()
    {
        $customer = [
           'firstname' => null,
           'lastname' => null,
           'email' => null,
           'address' => null
       ];

       return response()
       ->json(['customer' => $customer]);
   }

   public function store(Request $request)
   {
    $request->validate([
        'firstname' => 'required|max:100',
        'lastname' => 'required|max:100',
        'email' => 'required|email|unique:users',
        'address' => 'required|max:2000'
    ]);
}
}
