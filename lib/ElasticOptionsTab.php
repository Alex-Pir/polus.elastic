<?php

namespace Polus\Elastic;

use Polus\Elastic\Entity\OptionTable;
use Polus\Options\Tab;

class ElasticOptionsTab extends Tab
{
    public function save(): void
    {
        parent::save();
        OptionTable::getEntity()->cleanCache();
    }
}
