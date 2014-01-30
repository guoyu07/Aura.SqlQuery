<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Sql_Query
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Sql_Query\Common;

use Aura\Sql_Query\AbstractQuery;

/**
 *
 * An object for UPDATE queries.
 *
 * @package Aura.Sql_Query
 *
 */
class Update extends AbstractQuery implements UpdateInterface
{
    /**
     *
     * The table to update.
     *
     * @var string
     *
     */
    protected $table;

    /**
     *
     * Sets the table to update.
     *
     * @param string $table The table to update.
     *
     * @return $this
     *
     */
    public function table($table)
    {
        $this->table = $this->quoteName($table);
        return $this;
    }
    
    /**
     * 
     * Builds this query object into a string.
     * 
     * @return string
     * 
     */
    protected function build()
    {
        return 'UPDATE'
            . $this->buildFlags()
            . $this->buildTable()
            . $this->buildValuesForUpdate()
            . $this->buildWhere();
    }
    
    /**
     * 
     * Builds the table clause.
     * 
     * @return null
     * 
     */
    protected function buildTable()
    {
        return " {$this->table}";
    }

    /**
     *
     * Adds a WHERE condition to the query by AND. If the condition has
     * ?-placeholders, additional arguments to the method will be bound to
     * those placeholders sequentially.
     *
     * @param string $cond The WHERE condition.
     * @param mixed ...$bind arguments to bind to placeholders
     *
     * @return $this
     *
     */
    public function where($cond)
    {
        $bind = func_get_args();
        array_shift($bind);

        $this->addWhere($cond, 'AND', $bind);

        return $this;
    }

    /**
     *
     * Adds a WHERE condition to the query by OR. If the condition has
     * ?-placeholders, additional arguments to the method will be bound to
     * those placeholders sequentially.
     *
     * @param string $cond The WHERE condition.
     * @param mixed ...$bind arguments to bind to placeholders
     *
     * @return $this
     *
     * @see where()
     *
     */
    public function orWhere($cond)
    {
        $bind = func_get_args();
        array_shift($bind);

        $this->addWhere($cond, 'OR', $bind);

        return $this;
    }

    /**
     *
     * Sets one column value placeholder; if an optional second parameter is
     * passed, that value is bound to the placeholder.
     *
     * @param string $col The column name.
     *
     * @param mixed  $val Optional: a value to bind to the placeholder.
     *
     * @return $this
     *
     */
    public function col($col)
    {
        return call_user_func_array(array($this, 'addCol'), func_get_args());
    }

    /**
     *
     * Sets multiple column value placeholders. If an element is a key-value
     * pair, the key is treated as the column name and the value is bound to
     * that column.
     *
     * @param array $cols A list of column names, optionally as key-value
     *                    pairs where the key is a column name and the value is a bind value for
     *                    that column.
     *
     * @return $this
     *
     */
    public function cols(array $cols)
    {
        return $this->addCols($cols);
    }

    /**
     *
     * Sets a column value directly; the value will not be escaped, although
     * fully-qualified identifiers in the value will be quoted.
     *
     * @param string $col   The column name.
     *
     * @param string $value The column value expression.
     *
     * @return $this
     *
     */
    public function set($col, $value)
    {
        return $this->setCol($col, $value);
    }

    /**
     *
     * Gets the values to bind to placeholders.
     *
     * @return array
     *
     */
    public function getBindValues()
    {
        $bind_values = $this->bind_values;
        $i = 1;
        foreach ($this->bind_where as $val) {
            $bind_values[$i] = $val;
            $i ++;
        }
        return $bind_values;
    }
}
