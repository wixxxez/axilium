<?php

// Copyright (c) Lellys Informática. All rights reserved. See License.txt in the project root for license information.
namespace Easy\Collections\Linq\Expr;

/**
 * An Expression visitor walks a graph of expressions and turns them into a
 * query for the underlying implementation.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @deprecated
 */
abstract class ExpressionVisitor
{

    /**
     * Converts a comparison expression into the target query language output.
     *
     * @param Comparison $comparison
     *
     * @return mixed
     */
    abstract public function walkComparison(Comparison $comparison);

    /**
     * Converts a value expression into the target query language part.
     *
     * @param Value $value
     *
     * @return mixed
     */
    abstract public function walkValue(Value $value);

    /**
     * Converts a composite expression into the target query language output.
     *
     * @param CompositeExpression $expr
     *
     * @return mixed
     */
    abstract public function walkCompositeExpression(CompositeExpression $expr);

    /**
     * Dispatches walking an expression to the appropriate handler.
     *
     * @param ExpressionInterface $expr
     *
     * @return null|callable
     *
     * @throws \RuntimeException
     */
    public function dispatch(ExpressionInterface $expr)
    {
        switch (true) {
            case ($expr instanceof Comparison):
                return $this->walkComparison($expr);

            case ($expr instanceof Value):
                return $this->walkValue($expr);

            case ($expr instanceof CompositeExpression):
                return $this->walkCompositeExpression($expr);

            default:
                throw new \RuntimeException("Unknown Expression " . get_class($expr));
        }
    }
}
