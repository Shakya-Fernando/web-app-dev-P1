<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; 
// IMPORTANT: DB class’s methods insert(), select(), update(), delete(), and raw() !!!

////////////////////////// All View navigations //////////////////////////

// Navigation layout //
Route::get('/', function () {
    return view('layouts.app');
});

////////////////////////// Home Page //////////////////////////

// Route to SHOW Home Page
Route::get('/home', function () {
    // Fetch the top 3 items with the highest average rating using raw SQL
    $topItems = DB::select("SELECT items.*, AVG(reviews.rating) as avg_rating 
        FROM items
        JOIN reviews ON items.id = reviews.item_id
        GROUP BY items.id
        ORDER BY avg_rating DESC, items.id
        LIMIT 3");
    // Note: Joins the reviews table on the condition that items.id matches reviews.item_id

    // Return the 'home' view - passing the fetched top items to the view
    return view('home', ['topItems' => $topItems]);
})->name('home');

////////////////////////// About Us Page //////////////////////
Route::get('/aboutus', function () {
    return view('aboutus');
})->name('aboutus');

////////////////////////// Profile Page ///////////////////////
Route::get('/profile', function () {
    return view('profile');
})->name('profile');

////////////////////////// Settings Page //////////////////////
Route::get('/settings', function () {
    return view('settings');
})->name('settings');

////////////////////////// Items Page /////////////////////////

// Route to SHOW Items Page //
Route::get('/items', function () {
    // Fetch sorting parameters from the request
    $sort_by = request('sort_by', 'review_count'); // Default to sorting by review_count
    $order = request('order', 'desc'); // Default to descending order (DESC)

    // Build raw SQL query for items with review count and average rating
    $query = "SELECT items.*, COUNT(reviews.id) as review_count, AVG(reviews.rating) as avg_rating
        FROM items
        LEFT JOIN reviews ON items.id = reviews.item_id
        GROUP BY items.id
        ORDER BY " . ($sort_by === 'review_count' ? 'review_count' : 'avg_rating') . " $order"; // sort based on request input: either by review_count or avg_rating

    // Execute the SQL query and store the result in the $items variable
    $items = DB::select($query);

    // Return the 'items' view - passing the retrieved items to the view
    return view('items', ['items' => $items]);
})->name('items');


////////////////////////// Create Item Page ///////////////////

// Route to SHOW Create Item Page //
Route::get('/item/create', function () {
    return view('create_item');
});

// Route to ADD New Item //
Route::post('/item/store', function () {
    // Fetch input data from the request
    $name = request('name');
    $manufacturer = request('manufacturer');
    $price = request('price');
    $description = request('description');
    $image = request()->file('image'); // Get the uploaded image file

    $errors = []; // Initialize an empty array to hold validation errors

    // Manual validation logic
    // Item name must be longer than 2 characters and not contain certain special characters
    if (strlen($name) <= 2 || preg_match('/[-_+\"]/', $name)) { // Documentation: https://www.php.net/manual/en/function.preg-match.php
        $errors[] = 'Item name must have more than 2 characters and cannot contain -, _, +, or ".';
    }

    // Manufacturer name - same rules as item name
    if (strlen($manufacturer) <= 2 || preg_match('/[-_+\"]/', $manufacturer)) {
        $errors[] = 'Manufacturer name must have more than 2 characters and cannot contain -, _, +, or ".';
    }

    // Price must be a number
    if (!is_numeric($price)) {
        $errors[] = 'Price must be a valid number.';
    }

    // Image must be provided and valid
    // Not really needed
    // Stackoverflow: https://stackoverflow.com/questions/34432208/laravel-5-1-file-upload-isvalid-on-string
    if (!$image || !$image->isValid()) { // Check if it's a file, not the name of file
        $errors[] = 'A valid image is required.';
    }

    // If any errors exist, redirect back with errors (passes the $error array into the session to display when it reloads) and input data
    if (count($errors) > 0) {
        return redirect()->back()->with('errors', $errors)->withInput(); // Still has filled in data - Stackoverflow: https://stackoverflow.com/questions/31081644/how-to-redirect-back-to-form-with-input-laravel-5
    }

    // Image upload - store the image in the 'images' folder within the public storage
    $imagePath = $image->store('images', 'public');
    
    // To view image and to create symbolic link run php artisan storage:link //
    // Stackoverflow: https://stackoverflow.com/questions/51676037/laravel-storage-symlink

    // Insert the new item into the 'items' table using raw SQL
    DB::insert("INSERT INTO items (name, manufacturer, price, description, image)
        VALUES (?, ?, ?, ?, ?)", 
        [$name, $manufacturer, $price, $description, $imagePath]);

    // Redirect back to the items page with a success message
    return redirect('/items')->with('success', 'Item added successfully.');
});


// Route to DELETE Item //
Route::delete('/item/{id}/delete', function ($id) {
    // Delete all REVIEWS associated with the item using the item ID
    DB::delete("DELETE FROM reviews WHERE item_id = ?", [$id]);

    // Delete the ITEM itself using its ID
    DB::delete("DELETE FROM items WHERE id = ?", [$id]);

    // Redirect to the items page with a success message after deletion
    return redirect('/items')->with('success', 'Item and its reviews deleted successfully.');
});

////////////////////////// Item Details Page //////////////////

// Route to SHOW Item Details Page //
Route::get('/item/{id}', function ($id) {
    // Fetch the item details using the item ID from the 'items' table
    $item = DB::select("SELECT * FROM items WHERE id = ?", [$id])[0];

    // Fetch all reviews for the item using the item ID from the 'reviews' table
    $reviews = DB::select("SELECT * FROM reviews WHERE item_id = ?", [$id]);

    // Return the 'item_detail' view - passing both the item details AND its reviews
    return view('item_detail', ['item' => $item, 'reviews' => $reviews]);
});

////////////////////////// Review Page ////////////////////////

// Route to SHOW the review form //
// Route::get('/review', function () {
//     return view('review');
// })->name('review');

// Route to SHOW the review form //
Route::get('/item/{id}/review/create', function ($id) {
    // Fetch the item details using the item ID from the 'items' table
    $item = DB::select("SELECT * FROM items WHERE id = ?", [$id])[0];

    // Return the 'create_review' view - passing the item details to the view
    return view('create_review', ['item' => $item]);
});

////////////////////////// Create Review Page /////////////////

// Route to ADD New Review //
Route::post('/item/{id}/review/store', function ($id) {
    // Request data
    $username = request('username');
    $rating = request('rating');
    $review_text = request('review_text');

    // Manual validation
    $errors = []; // Empty error array

    // Validation logic
    // Username - same checks as Add Item
    if (strlen($username) <= 2 || preg_match('/[-_+\"]/', $username)) {
        $errors[] = 'Username must have more than 2 characters and cannot contain -, _, +, or ".';
    }

    // Rating - a number between 1 and 5
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        $errors[] = 'Rating must be a number between 1 and 5.';
    }

    // Review text - text must have more than 5 characters
    if (strlen($review_text) < 5) {
        $errors[] = 'Review text must have at least 5 characters.';
    }

    // If there are errors from validation, redirect back to the review page with old inputs
    if (count($errors) > 0) {
        return redirect()->back()->with('errors', $errors)->withInput();
    }

    // Remove odd numbers from the username
    $originalUsername = $username; // Store input username 
    $username = preg_replace_callback('/\d+/', function ($matches) { // From Documentation: Perform a regular expression search and replace using a callback - https://www.php.net/manual/en/function.preg-replace-callback.php 
        return ((int)$matches[0] % 2 !== 0) ? '' : $matches[0]; // Checks if the FULL number is divisible by 2
    }, $username); // Return new username without any odd numbers

    // Trim any leading or trailing whitespace
    // Testing Example: 'Person 1' changed to 'Person ' | 'Person21' changed to 'Person' - notice the space
    // By trimming it will remove whitespaces -> Both inputs will now change to 'Person' -> code below checks dupes and will NOT allow it!
    $username = trim($username);

    // Check if the altered username has already reviewed this item
    $existingReview = DB::select("SELECT * FROM reviews WHERE item_id = ? AND username = ?", 
        [$id, $username]);

    // If the review exists, add an error message
    if (!empty($existingReview)) {
        $errors[] = "The username '$username' has already reviewed this item.";
    }

    // If there are errors from duplicate review check, return early
    if (count($errors) > 0) {
        return redirect()->back()->with('errors', $errors)->withInput();
    }

    // Fake Review Detection using time approach - helps prevent bots spamming fake reviews
    // Check the time of the last review by this user
    $lastReview = DB::select("SELECT date_posted FROM reviews WHERE item_id = ? ORDER BY date_posted DESC LIMIT 1", [$id]); // Get latest review from sorted list above

    // Check if a review was found, if not this will be null
    // If found, checks the time the review was created and compares it to time now -10 seconds
    // If the condition above is true (i.e. the user submitted a review less than 10 seconds ago), an error message is added to the $errors array
    // Also can use subMinutes() or subHours()
    if (!empty($lastReview) && $lastReview[0]->date_posted > now()->subSeconds(10)) { // The time can be adjusted, but use a higher number to show
        $errors[] = 'You are posting reviews too quickly. Please wait before submitting another review.'; // This error message will prevent the review from being stored (below code)
    }

    // If there are ANY errors, redirect back with errors and input data (old stuff in blades)
    if (count($errors) > 0) {
        return redirect()->back()->with('errors', $errors)->withInput();
    }

    // Store the username in the session (if needed for tracking)
    session(['username' => $username]);

    // OR Store originalUsername
    // session(['username' => $originalUsername]);

    // Insert the new review
    DB::insert("INSERT INTO reviews (item_id, username, rating, review_text, date_posted)
        VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)", // Record the time of submission - Same as date_posted
        [$id, $username, $rating, $review_text]);

    // Success message
    $successMessage = "Review added successfully.";

    // Redirect back to Item details page
    // If the username was altered, show the user of this change; otherwise, normal success message is displayed
    return redirect('/item/' . $id)->with('success', $originalUsername !== $username? "The username you entered has been changed from '$originalUsername' to '$username' - Review added successfully."
    : $successMessage);
});

// Route to SHOW the Edit Review form //
Route::get('/item/{itemId}/review/{reviewId}/edit', function ($itemId, $reviewId) {
    // Fetch the item details using the item ID from the 'items' table
    $item = DB::select("SELECT * FROM items WHERE id = ?", [$itemId])[0];

    // Fetch the review details using the review ID from the 'reviews' table
    $review = DB::select("SELECT * FROM reviews WHERE id = ?", [$reviewId])[0];

    // Return the 'edit_review' view - passing both the item and the review to the view
    return view('edit_review', ['item' => $item, 'review' => $review]);
});


// Route to UPDATE/EDIT a review //
Route::post('/item/{itemId}/review/{reviewId}/update', function ($itemId, $reviewId) {
    // Fetch input data from the request
    $username = request('username');
    $rating = request('rating');
    $review_text = request('review_text');

    $errors = []; // empty array

    // Manual validation
    // Check if the rating is a number between 1 and 5
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        $errors[] = 'Rating must be a number between 1 and 5.';
    }

    // Check if the review text is at least 5 characters long
    if (strlen($review_text) < 5) {
        $errors[] = 'Review text must have at least 5 characters.';
    }

    // No need to alter the username, as it's read-only and remains unchanged
    // No need to check username for odd numbers also

    // If there are validation errors, redirect back to the form with the errors and input data
    if (count($errors) > 0) {
        return redirect()->back()->with('errors', $errors)->withInput();
    }

    // Update the review in the 'reviews' table using raw SQL
    DB::update("UPDATE reviews 
        SET rating = ?, review_text = ?, updated_at = CURRENT_TIMESTAMP 
        WHERE id = ?",
        [$rating, $review_text, $reviewId]);

    // Redirect back to the item's detail page with a success message
    return redirect('/item/' . $itemId)->with('success', 'Review updated successfully.');
});

// Route to DELETE a review //
Route::delete('/item/{itemId}/review/{reviewId}/delete', function ($itemId, $reviewId) {
    // Delete the review from the 'reviews' table using the review ID
    DB::delete("DELETE FROM reviews WHERE id = ?", [$reviewId]);

    // Redirect back to the item's detail page with a success message
    return redirect('/item/' . $itemId)->with('success', 'Review deleted successfully.');
});


////////////////////////// Manufacturer Page //////////////////

// Route to SHOW manufacturers and their ratings //
Route::get('/manufacturers', function () {
    // Fetch manufacturers with their average review rating and total number of reviews
    $manufacturers = DB::select("SELECT items.manufacturer, AVG(reviews.rating) as manufacturer_avg_rating, COUNT(reviews.id) as total_reviews
        FROM items
        LEFT JOIN reviews ON items.id = reviews.item_id
        GROUP BY items.manufacturer");

    // Return the 'manufacturers' view - passing the manufacturer data to the view
    return view('manufacturers', ['manufacturers' => $manufacturers]);
})->name('manufacturers');


////////////////////////// Manufacturer Items Page //////////////////

// Route to SHOW items from a specific manufacturer //
Route::get('/manufacturer/{manufacturer}', function ($manufacturer) {
    // Fetch all items from the specified manufacturer and calculate the average rating for each item using raw SQL
    $items = DB::select("SELECT items.*, AVG(reviews.rating) as avg_rating
        FROM items
        LEFT JOIN reviews ON items.id = reviews.item_id
        WHERE items.manufacturer = ?
        GROUP BY items.id",
        [$manufacturer]); // Use the manufacturer provided in the route

    // Return the 'manufacturer_items' view - passing both the items and the manufacturer name to the view
    return view('manufacturer_items', ['items' => $items, 'manufacturer' => $manufacturer]);
})->name('manufacturer.items');

