<?php

namespace Beam\Taxonomies;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Beam\Taxonomies\Facades\Resolver;

trait HasTags
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function addTags($tags): void
    {
        $tags = Resolver::tags($tags);

        $this->tags()->attach($tags->modelKeys());
    }

    public function syncTags($tags): void
    {
        $tags = Resolver::tags($tags);

        $this->tags()->sync($tags->modelKeys());
    }

    public function removeTags($tags): void
    {
        $tags = Resolver::tags($tags);

        $this->tags()->detach($tags->modelKeys());
    }

    public function removeAllTags(): void
    {
        $this->tags()->detach();
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function hasTags($tags): bool
    {
        $tags = Resolver::tags($tags);

        return $this->tags()
            ->whereIn('tags.id', $tags->modelKeys())
            ->exists();
    }
}
