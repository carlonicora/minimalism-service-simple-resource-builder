<?php
namespace CarloNicora\Minimalism\Services\SimpleResourceBuilder\Enums;

enum ResourceDetailType
{
    case Id;
    case Attribute;
    case Meta;
    case Link;
    case RelationshipLink;
}