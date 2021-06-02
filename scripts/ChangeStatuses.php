<?php
namespace Command;

use Models\Orders;

class ChangeStatuses
{
    public static function execute()
    {
        $model = new Orders;

        $model->updateData(['status' => '"in_progress"', 'status_changed' => time()], 'created_at <= NOW() - 60 AND status != "canceled"');
        $model->updateData(['status' => '"ready"'], 'status_changed <= NOW() - 60 AND status != "canceled"');
    }
}

ChangeStatuses::execute();
