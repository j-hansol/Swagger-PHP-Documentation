<?php
namespace JHansol\PhpSwaggerDocumentation\Attribute;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    properties: [
        'name' => new Property(property: 'name', type: 'string'),
        'email' => new Property(property: 'email', type: 'string'),
    ]
)]
class VirtualModel {}
