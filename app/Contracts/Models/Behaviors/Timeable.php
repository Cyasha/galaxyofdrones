<?php

namespace Koodilab\Contracts\Models\Behaviors;

interface Timeable
{
    /**
     * Get the remaining attribute.
     *
     * @return int
     */
    public function getRemainingAttribute();
}
