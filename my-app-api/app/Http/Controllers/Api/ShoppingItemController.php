<?php

namespace App\Http\Controllers\Api;

use App\Models\ShoppingList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShoppingItemController extends Controller
{
    public function index(ShoppingList $shoppingList)
    {
        // Authorization will come next
        return response()->json([
            'items' => $shoppingList->items()->latest()->get(),
        ]);
    }

    public function store(Request $request, ShoppingList $shoppingList)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $item = $shoppingList->items()->create($validated);

        return response()->json([
            'item' => $item,
        ], 201);
    }
}

