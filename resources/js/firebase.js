import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getAuth } from "firebase/auth";
import { getFirestore, collection, onSnapshot } from "firebase/firestore";

const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
    measurementId: import.meta.env.VITE_FIREBASE_MEASUREMENT_ID,
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

// Export required Firebase services
export const auth = getAuth(app);
export const db = getFirestore(app);
export default app;

let isInitialLoad = true;

// Attach real-time listener to the 'tasks' collection
onSnapshot(collection(db, 'tasks'), (snapshot) => {
    // Skip firing on initial page boot-up payload
    if (isInitialLoad) {
        isInitialLoad = false;
        return;
    }

    // Check if there are actual document mutations
    if (snapshot.docChanges().length > 0) {
        // Dispatch event directly to Livewire components
        if (window.Livewire) {
            window.Livewire.dispatch('refresh-task-list');
        }
    }
});