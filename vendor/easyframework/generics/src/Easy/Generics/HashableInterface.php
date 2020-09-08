<?php

namespace Easy\Generics;

/**
 * Serves as the default hash function.
 *
 * @author Ítalo Lelis de Vietro <italolelis@gmail.com>
 */
interface HashableInterface
{

    /**
     * Returns a hash code value for the object. This method is supported for the benefit of hash tables such as those
     * provided by HashMap.
     */
    public function getHashCode();
}
