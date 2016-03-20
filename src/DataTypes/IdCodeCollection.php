<?php
declare(strict_types = 1);
namespace DataTypes;

class IdCodeCollection extends BasicObject implements \Iterator, \Countable, \JsonSerializable
{
    use CodeCollectionTrait;

    public function count():int
    {
        return $this->getLength();
    }

    function jsonSerialize():array
    {
        //TODO: good idea to remove keys?
        $data = [];
        /** @var IdCodeObject $item */
        foreach ($this->_collection as $item) {
            $data[] = $item->jsonSerialize();
        }

        return $data;
    }
}
