<?php
namespace CarloNicora\Minimalism\Services\SimpleResourceBuilder\Enums;

enum ResourceDetailType
{
    case Attribute;
    case Meta;
    case Link;
    case RelationshipLink;
}