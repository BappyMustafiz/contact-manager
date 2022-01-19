<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ContactsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        unset($rows[0]);
        foreach ($rows as $row) {
            $contact = Contact::where('user_id', auth()->id())->where('email', $row[1])->first();
            if ($contact) {
                $contact->update([
                    'first_name' => $row[0] ?? $contact->first_name,
                    'phone' => $row[2] ?? $contact->phone,
                ]);
            } else {
                Contact::create([
                    'first_name' => $row[0],
                    'email' => $row[1],
                    'phone' => $row[2],
                ]);
            }
        }
    }
}
