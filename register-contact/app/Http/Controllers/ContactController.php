<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostContact;
class ContactController extends Controller
{
    // Lấy danh sách contact
    public function index()
    {
       $contacts = Contact::all();
       return response() -> json($contacts);
    }

    // Tạo mới contact
    public function store(StorePostContact $request)
    {
       $validated = $request->safe() -> only(['id','name', 'email', 'phone', 'address']);
       $contact = Contact::create($validated);
       return response() -> json($contact, 201);
    }

  
    public function show(Contact $contact)
    {
        return response()->json($contact);
    }

   
    public function update(Request $request, Contact $contact)
    {
       
    }

   
    public function destroy(Contact $contact)
    {
       
    }
}