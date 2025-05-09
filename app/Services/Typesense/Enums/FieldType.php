<?php

declare(strict_types=1);

namespace App\Services\Typesense\Enums;

enum FieldType: string
{
    case STRING = 'string';
    case INT32 = 'int32';
    case FLOAT = 'float';
    case BOOL = 'bool';
    case GEOPOINT = 'geopoint';
    case ARRAY = 'array';
    case OBJECT = 'object';
    case STRING_ARRAY = 'string[]';
    case INT32_ARRAY = 'int32[]';
    case FLOAT_ARRAY = 'float[]';
    case BOOL_ARRAY = 'bool[]';
}
