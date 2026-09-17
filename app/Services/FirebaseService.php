<?php

// Realtime Database : Custom service class designed to isolate, configure and manage connection to Firebase services

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\Firestore;
use Kreait\Firebase\Messaging;

class FirebaseService
{
    protected Database $database;  // Realtime Database
    protected Messaging $messaging;  // Firebase Cloud Messaging
    protected Firestore $firestore;  // Firestore

    public function __construct()
    {
        $factory = (new Factory)
            //Path to service account file
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
            //Change This to firebase realtime database path
            ->withDatabaseUri('https://jya-flow-default-rtdb.firebaseio.com/');

        $this->database = $factory->createDatabase();
        $this->messaging = $factory->createMessaging();
        $this->firestore = $factory->createFirestore();
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getMessaging(): Messaging
    {
        return $this->messaging;
    }

    public function getFirestore(): Firestore
    {
        return $this->firestore;
    }
}
