<?php

// Realtime Database : Tests and Verifies Laravel application can successfully authenticate and communicate with Firebase Realtime Database

namespace App\Http\Controllers;

use Exception;
use Kreait\Firebase\Factory;

class FirebaseConnectionController extends Controller
{
    public function index()
    {
        // Path to Google Firebase service account JSON key file
        $path = storage_path('app/firebase/firebase_credentials.json');

        // Check if JSON file exists or not
        if (!file_exists($path)) {
            die("This File Path {$path} does not Exists");
        }

        try {
            // Initialize Kreait's Firebase factory
            $factory = (new Factory)
                ->withServiceAccount($path)
                ->withDatabaseUri('https://jya-flow-default-rtdb.firebaseio.com/');

            // Create an active client instance for Realtime Database
            $database = $factory->createDatabase();

            // Create a pointer (reference) to a JSON node named 'contacts' in Realtime Database
            $reference = $database->getReference('contacts');
            
            // Performs write operation i.e. it stores {"connection": true} under contacts node in Firebase
            $reference->set(['connection' => true]);
            
            // Performs read operation i.e. it fetches point-in-time snapshot & extracts raw array value
            $snapShot = $reference->getSnapshot();
            $value = $snapShot->getValue();

            // Returns successful JSON response containing data read from Firebase if both read and write operation succeeds
            return response([
                'message' => true,
                'value' => $value
            ]);

        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => 'False',
            ]);
        }
    }
}
