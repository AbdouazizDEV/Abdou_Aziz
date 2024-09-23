<?php

/* return [
    'credentials_file' => env('FIREBASE_CREDENTIALS'),  // Chemin vers le fichier des credentials Firebase
    'database_url' => env('FIREBASE_DATABASE_URL'),     // URL de la base de données Firebase
    'project_id' => env('FIREBASE_PROJECT_ID'),         // ID du projet Firebase

    'credentials' => [
        'file' => base_path('config/firebase.json'),
    ],
    'database' => [
        'name' => env('FIREBASE_DATABASE_NAME', 'default'),
    ],
    'user_data_source' => env('USER_DATA_SOURCE', 'mysql'),  // Par défaut, MySQL est utilisé
]; */
return [
    'credentials_file' => env('FIREBASE_CREDENTIALS', base_path('storage/app/apprenant-laravel-firebase-adminsdk-3pqj9-315a6c8b89.json')),
    'database_url' => env('FIREBASE_DATABASE_URL'),
    'project_id' => env('FIREBASE_PROJECT_ID'),
];

