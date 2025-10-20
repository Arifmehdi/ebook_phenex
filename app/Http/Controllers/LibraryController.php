<?php

namespace App\Http\Controllers;

use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{

    public function purchasedBooks()
    {
        // Using pagination is a good practice for long lists
        $user = Auth::user();
        $books = UserBook::where('user_id', Auth::id())
                           ->where('status', 'purchased')
                           ->with('product') 
                           ->paginate(15);
        $activeTab = 'purchase';
        // You will need to create this view file: 
        // resources/views/mypanel/library/purchased_books.blade.php
        return view('mypanel.library.purchased_books', compact('user', 'activeTab', 'books'));
    }


    public function favoriteBooks()
    {
        $user = Auth::user();
        $books = UserBook::where('user_id', Auth::id())
                                         ->where('status', 'favorite')
                                         ->with('product')
                                         ->paginate(15);
        $activeTab = 'favorite';
        // You will need to create this view file: 
        // resources/views/mypanel/library/favorite_books.blade.php
        return view('mypanel.library.favorite_books', compact('activeTab', 'user' ,'books'));
    }

    public function lastReadBook()
    {
        $user = Auth::user();
        $bookEntry = UserBook::where('user_id', Auth::id())
                                             ->whereNotNull('last_read_at')
                                             ->orderBy('last_read_at', 'desc')
                                             ->with('product')
                                             ->first();
        $activeTab = 'last';
        // You will need to create this view file: 
        // resources/views/mypanel/library/last_read_book.blade.php
        return view('mypanel.library.last_read_book', compact('activeTab','user','bookEntry'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function createBook()
    {
        $user = Auth::user();
        $categories = ProductCategory::latest()->get();
        $activeTab = 'upload'; 

        return view('mypanel.library.upload_book', compact('user', 'activeTab', 'categories'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function storeBook(Request $request)
    {
        $request->validate([
            'name_en'        => 'required|string|max:255',
            'price'          => 'required|numeric',
            'description_en' => 'nullable|string',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'file_path'      => 'required|mimes:pdf|max:10240', // 10MB Max
        ]);

        $product = new Product();
        $product->name_en = $request->name_en;
        $product->price = $request->price;
        $product->description_en = $request->description_en;
        $product->slug = Str::slug($request->name_en);
        $product->active = 1;
        $product->addedby_id = Auth::id();
        $product->book_type = $request->price > 0 ? 'paid' : 'free';
        $product->save();

        // Upload featured image
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $imageName = $product->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put('product_images/' . $imageName, File::get($file));
            $product->featured_image = $imageName;
        }

        // Upload book PDF
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = $product->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put('product_files/' . $fileName, File::get($file));
            $product->file_path = $fileName;
        }

        $product->save();

        // Attach categories if any
        if ($request->has('categories')) {
            $product->categories()->attach($request->categories);
        }

        /**
         * 🔹 Create or link record in user_books table
         * Meaning: this user owns or uploaded this book.
         */
        UserBook::create([
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
            'status'     => $request->price > 0 ? 'purchased' : 'reading', // you can customize logic
            'last_read_at' => now(),
        ]);

        return redirect()->route('user.library.purchased_books')
            ->with('success', 'Book uploaded and added to your library successfully!');
    }

    /**
     * Show the page with the user's referral link.
     */
    public function showInvitePage()
    {
        $user = Auth::user();
        $activeTab = 'invite'; 
        return view('mypanel.library.invite', compact('user','activeTab'));
    }
}
