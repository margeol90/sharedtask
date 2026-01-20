<?php

namespace App\Http\Controllers\Api;

use App\Models\ShoppingList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShoppingListController extends Controller
{
    public function index(Request $request)
    {
        $account = $request->user()->activeAccount;

        return ShoppingList::with('items')
            ->where('account_id', $account->id)
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        $shoppingList = ShoppingList::create([
            'account_id' => $user->activeAccount->id,
            'created_by' => $user->id,
            'name' => $request->name,
        ]);

        return $shoppingList->load('items');
    }

        public function find($id)
        {
            $id = (int) $id;
            
            $shoppingList = ShoppingList::findOrFail($id);
            
            $account = auth()->user()->activeAccount;
            
            if ($shoppingList->account_id !== $account->id) {
                abort(403, 'Unauthorized');
            }
            
            return $shoppingList->load('items');
        }

}
