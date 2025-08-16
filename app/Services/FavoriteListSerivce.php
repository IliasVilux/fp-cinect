<?php

namespace App\Services;

use App\Models\FavoriteList;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class FavoriteListSerivce
{
    /**
     * Create a new favorite list for the authenticated user.
     *
     * @param  array  $data  The data for the favorite list
     * @return \App\Models\FavoriteList
     */
    public function create(array $data)
    {
        return Auth::user()->favoriteLists()->create($data);
    }

    /**
     * Update an existing favorite list.
     *
     * @param  FavoriteList  $list  The favorite list to update
     * @param  array  $data  The updated data for the favorite list
     * @return \App\Models\FavoriteList
     */
    public function update(FavoriteList $list, array $data)
    {
        $list->update($data);

        return $list;
    }

    /**
     * Delete a favorite list.
     *
     * @param  FavoriteList  $list  The favorite list to delete
     * @return void
     */
    public function delete(FavoriteList $list)
    {
        if (Auth::id() !== $list->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return $list->delete();
    }
}
