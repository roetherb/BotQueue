<?php

namespace Tests\Helpers\Models;


use App\Cluster;

class ClusterBuilder
{
    private $attributes;

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @return Cluster
     */
    public function create()
    {
        return Cluster::unguarded(function () {
            return Cluster::create($this->attributes);
        });
    }

    private function newWith($newAttributes)
    {
        return new ClusterBuilder(
            array_merge(
                $this->attributes,
                $newAttributes
            )
        );
    }

    public function creator(\App\User $user)
    {
        return $this->newWith(['creator_id' => $user->id]);
    }

    public function name(string $name)
    {
        return $this->newWith(['name' => $name]);
    }
}