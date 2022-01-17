<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('contacts', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contactLists = Http::get('https://a.klaviyo.com/api/v2/lists?api_key=pk_81c9f2bf0ce5176814fee9cad0f42b2c3d');
        $lists = [];
        if ($contactLists->successful()) {
            $lists = $contactLists->json();
        }
        return view('new_contacts', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'first_name'  => 'required|string',
            'email'  => 'required|email',
            'phone'  => 'required|string',
            'list_id' => 'required|string',
        ]);

        $existRes = Http::post('https://a.klaviyo.com/api/v2/list/' . $request->list_id . '/get-members?api_key=pk_81c9f2bf0ce5176814fee9cad0f42b2c3d', [
            'emails' => [
                $request->email
            ],
            'phone_numbers' => [
                $request->phone
            ]
        ]);


        if ($existRes->successful()) {
            if (count($existRes->json()) > 0) {
                //update
                $updateRes = Http::put('https://a.klaviyo.com/api/v1/person/' . $existRes[0]['id'] . '?first_name=' . $request->first_name . '&email=' . $request->email . '&phone_number=' . $request->phone . '&api_key=pk_81c9f2bf0ce5176814fee9cad0f42b2c3d');
                if ($updateRes->successful()) {
                    $contact = new Contact();
                    $contact->first_name = $request->first_name;
                    $contact->email = $request->email;
                    $contact->phone = $request->phone;
                    $contact->list_id = $request->list_id;
                    $contact->profile_id = $updateRes['id'];
                    $contact->save();

                    session()->flash('success', 'New Contact has been created successfully !!');
                }
            } else {
                //create
                $createRes = Http::post('https://a.klaviyo.com/api/v2/list/' . $request->list_id . '/members?api_key=pk_81c9f2bf0ce5176814fee9cad0f42b2c3d', [
                    'profiles' => [
                        'email' => $request->email,
                        'phone_number' => $request->phone,
                        'first_name' => $request->first_name,
                    ]
                ]);
                if ($createRes->successful()) {
                    $contact = new Contact();
                    $contact->first_name = $request->first_name;
                    $contact->email = $request->email;
                    $contact->phone = $request->phone;
                    $contact->list_id = $request->list_id;
                    $contact->profile_id = $createRes[0]['id'];
                    $contact->save();

                    session()->flash('success', 'New Contact has been created successfully !!');
                }
            }
        }

        return redirect()->route('contacts.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contactLists = Http::get('https://a.klaviyo.com/api/v2/lists?api_key=pk_81c9f2bf0ce5176814fee9cad0f42b2c3d');
        $lists = [];
        if ($contactLists->successful()) {
            $lists = $contactLists->json();
        }
        $contact = Contact::find($id);
        return view('edit_contacts', compact('lists', 'contact'));
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
        $request->validate([
            'first_name'  => 'required|string',
            'email'  => 'required|email',
            'phone'  => 'required|string',
            'list_id' => 'required|string',
        ]);

        $contact = Contact::find($id);

        $updateRes = Http::put('https://a.klaviyo.com/api/v1/person/' . $contact->profile_id . '?first_name=' . $request->first_name . '&email=' . $request->email . '&phone_number=' . $request->phone . '&api_key=pk_81c9f2bf0ce5176814fee9cad0f42b2c3d');

        if ($request->list_id != $contact->list_id) {
            //delete from existing list and assign to new list
            $deleteRes = Http::delete('https://a.klaviyo.com/api/v2/list/' . $contact->list_id . '/members?api_key=pk_81c9f2bf0ce5176814fee9cad0f42b2c3d', [
                'emails' => [
                    $request->email
                ],
                'phone_numbers' => [
                    $request->phone
                ]
            ]);
            if ($deleteRes->successful()) {
                $createRes = Http::post('https://a.klaviyo.com/api/v2/list/' . $request->list_id . '/members?api_key=pk_81c9f2bf0ce5176814fee9cad0f42b2c3d', [
                    'profiles' => [
                        'email' => $request->email,
                        'phone_number' => $request->phone,
                        'first_name' => $request->first_name,
                    ]
                ]);
            }
        }
        if ($updateRes->successful()) {
            $contact->first_name = $request->first_name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->list_id = $request->list_id;
            $contact->save();

            session()->flash('success', 'Contact has been updated successfully !!');
        }
        return redirect()->route('contacts.index');
    }
}
