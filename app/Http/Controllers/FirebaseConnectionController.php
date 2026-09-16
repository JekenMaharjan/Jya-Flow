<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class FirebaseConnectionController extends Controller
{
    public function index() {
        $path = storage_path('app/firebase/firebase_credentials.json');

        if (!file_exists($path)) {
            die("This File Path {$path} does not Exists");
        }

        try {
            $factory = (new Factory)
                ->withServiceAccount($path)
                ->withDatabaseUri('https://jya-flow-default-rtdb.firebaseio.com/');

                $database = $factory->createDatabase();
                $reference = $database->getReference('contacts');
                $reference->set(['connection' => true]);
                $snapShot = $reference->getSnapshot();
                $value = $snapShot->getValue();

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
