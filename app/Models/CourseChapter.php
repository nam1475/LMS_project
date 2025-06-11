<?php

namespace App\Models;

use App\Traits\Draft;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Oddvalue\LaravelDrafts\Concerns\HasDrafts;

class CourseChapter extends Model
{
    use HasFactory, HasDrafts, Draft;

    protected $fillable = [
        'title',
        'course_id',
        'order',
        'instructor_id',
        'is_published',
        'is_current',
    ];

    protected array $draftableRelations = ['lessons'];  

    // Ghi đè lại logic publish mặc định
    public function replicateDraftAttributesTo(Model $target): void
    {
        parent::replicateDraftAttributesTo($target);
        $target->course_id = $target->getOriginal('course_id');
    }


    function lessons(): HasMany
    {
        return $this->hasMany(CourseChapterLession::class, 'chapter_id', 'id')->orderBy('order');
        // return $this->hasMany(CourseChapterLession::class, 'chapter_id', 'id');
    }
}
