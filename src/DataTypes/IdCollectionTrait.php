<?php
namespace DataTypes;



use DataTypes\Helper\IdHelper;

trait IdCollectionTrait
{
    protected $_hashMap = [];
    protected $_collection = [];
    private $length = 0;

    public function length()
    {
        return $this->getLength();
    }

    protected function getLength()
    {
        return $this->length;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->_collection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->_collection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->_collection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return !!current($this->_collection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->_collection);
    }

    protected function _getIds()
    {
        return array_keys($this->_hashMap);
    }

    protected function put($id, $value)
    {

        if ($this->hasId($id)) {
            throw new \Exception('Id "' . $id . '" already in collection');
        }
        $index = count($this->_collection);

        $this->_collection[$index] = $value;

        $id = BasicObject::formatId($id);
        $this->_hashMap[$id] = $index;
        $this->length++;

        return $index;
    }

    protected function hasId($id)
    {
        $id = IdHelper::formatId($id);

        return isset($this->_hashMap[$id]);
    }

    protected function getById($id)
    {
       

        $index = $this->getObjectIndexById($id);
        if (!isset($this->_collection[$index])) {
            throw new \Exception('no object for id "' . $id . '" not in collection');
        }

        return $this->_collection[$index];

    }

    protected function getObjectIndexById($id)
    {
        $id = IdHelper::formatId($id);
        if (isset($this->_hashMap[$id])) {
            return $this->_hashMap[$id];
        }

        throw new \Exception('id "' . $id . '" not in collection');
    }

    protected function getObjectIdByIndex($index)
    {
        return array_search($index, $this->_hashMap);
    }

    protected function deleteById($id)
    {
        //TODO: update all hashmaps or not? or just remove the object from collection and the key from the hashmap (do we have 'holes' in our hashmap?)

        $id = IdHelper::formatId($id);

        if ($this->hasId($id)) {
            $index = $this->getObjectIndexById($id);
            if (isset($this->_collection[$index])) {
                unset($this->_collection[$index]);
                unset($this->_hashMap[$id]);
                $this->length--;

                return true;
            }
        }

        return false;
    }

}