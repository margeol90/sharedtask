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
        return new ShoppingItemResource($shoppingList->items()->latest()->get());
    }

    public function store(StoreShoppingItemRequest $request, ShoppingList $shoppingList)
    {
        $this->authorize('update', $shoppingList);

        $item = $shoppingList->items()->create($request->validated());

        return new ShoppingItemResource($item);
    }

    public function update(StoreShoppingItemRequest $request, $itemID)
    {
        $item = ShoppingItem::findOrFail($itemID);

        $this->authorize('update', $item->shoppingList);

        $item->update($request->validated());

        return new ShoppingItemResource($item);
    }

    public function toggle(int $listID, int $itemID)
    {
        $item = ShoppingItem::findOrFail($itemID);
        $list = ShoppingList::findOrFail($listID);

        $this->authorize('update', $list);

        $item->update(['is_completed' => ! $item->is_completed]);

        return new ShoppingItemResource($item);
    }

    public function destroy(int $listID, int $itemID)
    {
        $item = ShoppingItem::findOrFail($itemID);
        $list = ShoppingList::findOrFail($listID);

        $this->authorize('delete', $list);

        $item->delete();

        return response()->noContent();
    }
}
