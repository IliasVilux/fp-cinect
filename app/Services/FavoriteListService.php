<?php

namespace App\Services;

use App\Models\FavoriteList;
use App\Models\Content;
use Illuminate\Support\Facades\Auth;

class FavoriteListService
{
    /**
     * Get all favorite lists of the authenticated user with content IDs.
     *
     * @param  Content  $content  The content to check against the lists
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserListsWithContent(Content $content)
    {
        return Auth::user()->favoriteLists()
            ->with('contents:id')
            ->get()
            ->map(function ($list) use ($content) {
                $list->has_content = $list->contents->contains($content->id);
                return $list;
            });
    }

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

    /**
     * ToAdd or remove a content from a favorite list.
     *
     * @param  FavoriteList  $list  The favorite list to toggle content in
     * @param  Content  $content  The content to toggle
     * @return void
     */
    public function toggleContent(FavoriteList $list, Content $content)
    {
        $list->contents()->toggle($content->id);
    }
}
