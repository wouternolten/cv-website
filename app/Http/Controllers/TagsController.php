<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    /**
     * Create tags from an array of tag titles.
     *
     * @param $tags array
     * @return Tag[]
     */
    public function createTags(array $tags)
    {
        $returnTags = [];

        foreach ($tags as $tag) {
            $findTag = Tag::where('name', $tag)->first();

            if ($findTag) {
                $returnTags[] = $findTag;
            } else {
                $returnTags[] = Tag::create(['name' => $tag]);
            }
        }

        return collect($returnTags);
    }
}
