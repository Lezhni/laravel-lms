<?php

namespace Modules\Learning\Observers\Group;

use Modules\Learning\Models\Group\Group;

/**
 * Class GroupObserver
 * @package Modules\Learning\Observers\Group
 */
class GroupObserver
{
    /**
     * @param \Modules\Learning\Models\Group\Group $group
     */
    public function saving(Group $group)
    {
        if ($group->access_closed_at == null) {
            $group->access_closed_at = $group->finished_at;
        }
    }
}