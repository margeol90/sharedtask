<?php

namespace App\Http\Controllers;

use App\Models\ShoppingItem;
use App\Models\ShoppingList;
use Illuminate\Http\Request;
use App\Http\Requests\StoreShoppingItemRequest;
use App\Http\Resources\ShoppingItemResource;

class ShoppingItemController extends Controller
{
    public function index(ShoppingList $shoppingList)
    {
        $this->authorize('view', $shoppingList);

        return ShoppingItemResource::collection($shoppingList->items()->latest()->get());
    }

    public function store(StoreShoppingItemRequest $request, ShoppingList $shoppingList)
    {
        $this->authorize('update', $shoppingList);

        $item = $shoppingList->items()->create($request->validated());

        return new ShoppingItemResource($item);
    }

    public function update(StoreShoppingItemRequest $request, ShoppingItem $item)
    {
        $this->authorize('update', $item->shoppingList);

        $item->update($request->validated());

        return new ShoppingItemResource($item);
    }

    public function toggle(ShoppingItem $item)
    {
        $this->authorize('update', $item->shoppingList);

        $item->update(['is_completed' => ! $item->is_completed]);

        return new ShoppingItemResource($item);
    }

    public function destroy(ShoppingItem $item)
    {
        $this->authorize('delete', $item->shoppingList);

        $item->delete();

        return response()->noContent();
    }
}
