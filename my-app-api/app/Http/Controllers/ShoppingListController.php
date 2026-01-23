<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\Request;
use App\Http\Requests\StoreShoppingListRequest;
use App\Http\Resources\ShoppingListResource;

class ShoppingListController extends Controller
{
    public function index(Request $request)
    {
        $account = $request->user()->activeAccount;

        $lists = ShoppingList::with('items')
            ->where('account_id', $account->id)
            ->get();

        return ShoppingListResource::collection($lists);
    }

    public function store(StoreShoppingListRequest $request)
    {
        $this->authorize('create', ShoppingList::class);

        $shoppingList = ShoppingList::create([
            'account_id' => $request->user()->activeAccount->id,
            'created_by' => $request->user()->id,
            'name' => $request->validated()['name'],
        ]);

        return new ShoppingListResource($shoppingList->load('items'));
    }

    public function show( $shoppingListID)
    {
        $shoppingList = ShoppingList::findOrFail($shoppingListID);

        $this->authorize('view', $shoppingList);

        return new ShoppingListResource($shoppingList->load('items'));
    }
}
