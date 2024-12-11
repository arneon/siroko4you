<?php

namespace Arneon\LaravelCategories\Domain\Entities;


class Category
{
    private $id;
    private $name;
    private $slug;
    private $parentId;
    private $enabled;

    /**
     * @return mixed
     */
    public function getId() :string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName() :string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Category
     */
    public function setName(string $name) : Category
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Category
     */
    public function setSlug(string $slug) : Category
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParentId() : int|string
    {
        return $this->parentId;
    }

    /**
     * @param int|string $parentId
     * @return Category
     */
    public function setParentId(int|string $parentId) : Category
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnabled() : bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return Category
     */
    public function setEnabled(bool $enabled) : Category
    {
        $this->enabled = $enabled;
        return $this;
    }
}
