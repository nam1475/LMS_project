<?php

namespace App\Traits;

use Exception;

trait Draft {
    // withoutRevision(): Để khi sử dụng update() bản hiện tại sẽ ko tạo bản nháp mới
    public function scopeCurrentWithoutRevision($query, $id)
    {
        return $query->where('id', $id)->current()->first()->withoutRevision() ?? null;
    }

    public function scopeCurrentWithoutRevisionWithRelations($query, $id)
    {
        return $query->with([
            'chapters' => fn($q) => $q->current(),
            'chapters.lessons' => fn($q) => $q->current()
        ])->currentWithoutRevision($id);
    }
}