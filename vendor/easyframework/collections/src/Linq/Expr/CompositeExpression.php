<?php

// Copyright (c) Lellys Informática. All rights reserved. See License.txt in the project root for license information.
namespace Easy\Collections\Linq\Expr;

/**
 * Expression of Expressions combined by AND or OR operation.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @since  2.3
 * @deprecated
 */
class CompositeExpression implements ExpressionInterface
{
    const TYPE_AND = 'AND';
    const TYPE_OR = 'OR';

    /**
     * @var string
     */
    private $type;

    /**
     * @var ExpressionInterface[]
     */
    private $expressions = array();

    /**
     * @param string $type
     * @param array  $expressions
     *
     * @throws \RuntimeException
     */
    public function __construct($type, array $expressions)
    {
        $this->type = $type;

        foreach ($expressions as $expr) {
            if ($expr instanceof Value) {
                throw new \RuntimeException("Values are not supported expressions as children of and/or expressions.");
            }
            if (!($expr instanceof ExpressionInterface)) {
                throw new \RuntimeException("No expression given to CompositeExpression.");
            }

            $this->expressions[] = $expr;
        }
    }

    /**
     * Returns the list of expressions nested in this composite.
     *
     * @return ExpressionInterface[]
     */
    public function getExpressionList()
    {
        return $this->expressions;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function visit(ExpressionVisitor $visitor)
    {
        return $visitor->walkCompositeExpression($this);
    }
}
