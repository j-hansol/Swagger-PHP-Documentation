<?php

namespace JHansol\PhpSwaggerDocumentation\Attribute;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema()]
class Reference {
    /**
     * @var VirtualModel $model
     */
    #[Property()]
    public VirtualModel $model;

    #[Property()]
    public VirtualModel $prev;
}