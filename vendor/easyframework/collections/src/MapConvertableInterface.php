<?php

// Copyright (c) Lellys Informática. All rights reserved. See License.txt in the project root for license information.
namespace Easy\Collections;

/**
 * Provides functionality to convert the collection into any IDictionary
 */
interface MapConvertableInterface extends CollectionConvertableInterface
{

    /**
     * Returns another ICollection based on this ICollection.
     * @return VectorInterface
     */
    public function toList();
}
