<?php

class DocumentNamespace
{
    public $name;
    public $keyword;
    public $subspace;
    public $module;
    public $fieldType;
    public $subfield;
    public $subdivide;

    /**
     * DocumentNamespace constructor.
     * @param $name
     * @param $keyword
     * @param bool $subspaces
     * @param bool $subspaces_2
     */
    public function __construct($name, $keyword, $subspace = '.', $module = 'Contacts', $fieldType = false, $subfield = false, $subdivide = false)
    {
        $this->name = $name;
        $this->keyword = $keyword;
        $this->subspace = $subspace;
        $this->module = $module;
        $this->fieldType = $fieldType;
        $this->subfield = $subfield;
        $this->subdivide = $subdivide;
    }
}