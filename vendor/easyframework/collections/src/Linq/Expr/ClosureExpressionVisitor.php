<?php

// Copyright (c) Lellys Informática. All rights reserved. See License.txt in the project root for license information.
namespace Easy\Collections\Linq\Expr;

use ArrayAccess;
use Closure;
use RuntimeException;

/**
 * Walks an expression graph and turns it into a PHP closure.
 *
 * This closure can be used with \Easy\Collections\Linq\QueryableInterface::filter().
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @since  2.3
 * @deprecated
 */
class ClosureExpressionVisitor extends ExpressionVisitor
{

    /**
     * Accesses the field of a given object. This field has to be public
     * directly or indirectly (through an accessor get*, is*, or a magic
     * method, __get, __call).
     *
     * @param object $object
     * @param string $field
     *
     * @return mixed
     */
    public static function getObjectFieldValue($object, $field)
    {
        $accessors = array('get', 'is');

        foreach ($accessors as $accessor) {
            $accessor .= $field;

            if (!method_exists($object, $accessor)) {
                continue;
            }

            return $object->$accessor();
        }

        // __call should be triggered for get.
        $accessor = $accessors[0] . $field;

        if (method_exists($object, '__call')) {
            return $object->$accessor();
        }

        if ($object instanceof ArrayAccess || is_array($object)) {
            return $object[$field];
        }

        return $object->$field;
    }

    /**
     * Helper for sorting arrays of objects based on multiple fields + orientations.
     *
     * @param string   $name
     * @param int      $orientation
     * @param Closure $next
     *
     * @return Closure
     */
    public static function sortByField($name, $orientation = 1, Closure $next = null)
    {
        if (!$next) {
            $next = function() {
                return 0;
            };
        }

        return function ($a, $b) use ($name, $next, $orientation) {
            $aValue = ClosureExpressionVisitor::getObjectFieldValue($a, $name);
            $bValue = ClosureExpressionVisitor::getObjectFieldValue($b, $name);

            if ($aValue === $bValue) {
                return $next($a, $b);
            }

            return (($aValue > $bValue) ? 1 : -1) * $orientation;
        };
    }

    /**
     * {@inheritDoc}
     */
    public function walkComparison(Comparison $comparison)
    {
        $field = $comparison->getField();
        $value = $comparison->getValue()->getValue(); // shortcut for walkValue()

        switch ($comparison->getOperator()) {
            case Comparison::EQ:
                return function ($object) use ($field, $value) {
                    return ClosureExpressionVisitor::getObjectFieldValue($object, $field) === $value;
                };

            case Comparison::NEQ:
                return function ($object) use ($field, $value) {
                    return ClosureExpressionVisitor::getObjectFieldValue($object, $field) !== $value;
                };

            case Comparison::LT:
                return function ($object) use ($field, $value) {
                    return ClosureExpressionVisitor::getObjectFieldValue($object, $field) < $value;
                };

            case Comparison::LTE:
                return function ($object) use ($field, $value) {
                    return ClosureExpressionVisitor::getObjectFieldValue($object, $field) <= $value;
                };

            case Comparison::GT:
                return function ($object) use ($field, $value) {
                    return ClosureExpressionVisitor::getObjectFieldValue($object, $field) > $value;
                };

            case Comparison::GTE:
                return function ($object) use ($field, $value) {
                    return ClosureExpressionVisitor::getObjectFieldValue($object, $field) >= $value;
                };

            case Comparison::IN:
                return function ($object) use ($field, $value) {
                    return in_array(ClosureExpressionVisitor::getObjectFieldValue($object, $field), $value);
                };

            case Comparison::NIN:
                return function ($object) use ($field, $value) {
                    return !in_array(ClosureExpressionVisitor::getObjectFieldValue($object, $field), $value);
                };

            case Comparison::CONTAINS:
                return function ($object) use ($field, $value) {
                    return false !== strpos(ClosureExpressionVisitor::getObjectFieldValue($object, $field), $value);
                };

            case Comparison::STARTS_WITH:
                return function ($object) use ($field, $value) {
                    return strpos(ClosureExpressionVisitor::getObjectFieldValue($object, $field), $value) === 0;
                };

            case Comparison::ENDS_WITH:
                return function ($object) use ($field, $value) {
                    $haystack = ClosureExpressionVisitor::getObjectFieldValue($object, $field);
                    return strpos($haystack, $value) + strlen($value) === strlen($haystack);
                };

            case Comparison::REGEX:
                return function ($object) use ($field, $value) {
                    $haystack = ClosureExpressionVisitor::getObjectFieldValue($object, $field);
                    return preg_match($value, $haystack);
                };

            default:
                throw new RuntimeException("Unknown comparison operator: " . $comparison->getOperator());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function walkValue(Value $value)
    {
        return $value->getValue();
    }

    /**
     * {@inheritDoc}
     */
    public function walkCompositeExpression(CompositeExpression $expr)
    {
        $expressionList = array();

        foreach ($expr->getExpressionList() as $child) {
            $expressionList[] = $this->dispatch($child);
        }

        switch ($expr->getType()) {
            case CompositeExpression::TYPE_AND:
                return $this->andExpressions($expressionList);

            case CompositeExpression::TYPE_OR:
                return $this->orExpressions($expressionList);

            default:
                throw new RuntimeException("Unknown composite " . $expr->getType());
        }
    }

    /**
     * @param array $expressions
     *
     * @return Closure
     */
    private function andExpressions($expressions)
    {
        return function ($object) use ($expressions) {
            foreach ($expressions as $expression) {
                if (!$expression($object)) {
                    return false;
                }
            }
            return true;
        };
    }

    /**
     * @param array $expressions
     *
     * @return Closure
     */
    private function orExpressions($expressions)
    {
        return function ($object) use ($expressions) {
            foreach ($expressions as $expression) {
                if ($expression($object)) {
                    return true;
                }
            }
            return false;
        };
    }
}
