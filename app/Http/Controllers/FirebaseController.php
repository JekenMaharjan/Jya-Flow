<?php

// Realtime Database : Verifies that custom FirebaseService wrapper works properly within Laravel's Service Container by writing to a 'testing' node

namespace App\Http\Controllers;

use App\Services\FirebaseService;

class FirebaseController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase->getDatabase();
    }

    public function test()
    {
        // Creates a reference to a node named 'testing' in Firebase Realtime Database
        $this->firebase->getReference("testing")
            ->set([
                'message' => 'Firebase Integration Successful!'
            ]);

        return 'Firebase Connected and Test Data Added!';
    }
}
