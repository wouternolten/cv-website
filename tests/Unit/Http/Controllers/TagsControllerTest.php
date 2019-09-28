<?php

namespace Tests\Unit;

use App\Http\Controllers\TagsController;
use App\Models\Tag;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagsControllerTest extends TestCase
{
    private $tagsController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tagsController = new TagsController();
    }

    public function testCreateTagsOnlyNewTags()
    {
        $tags = [
            'New tag 1',
            'New tag 2'
        ];

        $newTags = $this->tagsController->createTags($tags);

        $this->assertEquals(count($tags), count($newTags));
        $this->assertEquals($tags[0], $newTags[0]->name);
        $this->assertEquals($tags[1], $newTags[1]->name);
    }


    public function testCreateTagsNewAndOldTags()
    {
        $existingTag = factory(Tag::class)->create();

        $tags = [
            'New tag 1',
            $existingTag->name
        ];

        $newTags = $this->tagsController->createTags($tags);

        $this->assertEquals(count($tags), count($newTags));
        $this->assertEquals($tags[0], $newTags[0]->name);
        $this->assertEquals($existingTag->name, $newTags[1]->name);
    }
}
