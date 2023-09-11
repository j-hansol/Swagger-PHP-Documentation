<?php

namespace JHansol\PhpSwaggerDocumentation\Annotation;

/**
 * @OA\Schema()
 */
class Reference {
    /**
     * @var VirtualModel $model
     * @OA\Property()
     */
    public VirtualModel $model;

    /**
     * @OA\Property()
     */
    public VirtualModel $prev;
}